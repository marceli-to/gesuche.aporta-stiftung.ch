# Plan: edit link for submitted applications

Status as of 2026-11-17: **planned, not started. Waiting for the foundation's decisions (section 1).**
Update this file as work progresses. Tick the boxes and keep the status line current.

## Request (foundation)

> "Wäre es möglich, einer Organisation nach Eingabe des Gesuches einen Link zuzustellen, damit sie
> das Gesuch selbst anpassen kann? Im Moment verlangen wir ein neues Gesuch, was natürlich mit
> Aufwand für die Gesuchstellenden verbunden ist. Wir möchten nach Möglichkeit die Gesuche nicht
> selbst anpassen müssen."

Goal: the foundation sends an applicant a link. The applicant opens the public form with their
submitted application filled in, corrects it and saves. No new application is created.

Estimate: about 3 days.

## How it works today

- The public form `resources/js/views/pages/form/Form.vue` (~1,300 lines, mounted through
  `resources/js/form.js` into `resources/views/layout/form.blade.php`) is create-only. It POSTs to
  `/form/submit`, and `FormController::store` always creates a new `Application`. `year` is
  hardcoded to `2026`, and two `MailQueue` rows are queued (`notification` and `confirmation`).
- Uploads go to temp storage through `/file/upload` (`Media::store`). On submit,
  `Media::copyToApplicationFolder` moves them to `storage/app/files/{uuid}/` and creates
  `ApplicationFile` rows (`user_id` = 1).
- Admins can already edit applications in the backend (`resources/js/views/pages/application/Update.vue`
  → `PUT api/application/{uuid}` → `Api\ApplicationController::update`). This is the fallback
  the foundation wants to avoid.
- `Logger::log` writes `auth()->user()->id`. `application_logs.user_id` is a non-null FK
  (`2022_02_25_100555_create_application_logs_table.php`), so a guest can't be logged.
- `App\Tasks\Notification` handles only the `notification` and `confirmation` types, 2 mails per
  run. It marks failed mails as processed, so nothing is retried (see `INCIDENTS.md`).

## 1. Decisions needed from the foundation (blocking)

Defaults are in brackets. Build with the defaults if the foundation doesn't answer otherwise.

- [ ] **When is editing allowed?** [Only in state `OPEN` (1). Once the state changes, the link stops
      working.]
- [ ] **How long is the link valid?** [14 days. Unlimited saves until it expires. A new link
      replaces the old one.]
- [ ] **IBAN and bank details** (`iban`, `bank_account`, `beneficiary`): editable or locked?
      [Editable, but changes are highlighted in the notification to the foundation.] This is a
      fraud risk if the mail is intercepted or forwarded.
- [ ] **History:** a full before/after diff, or just "edited on X"? [A diff of the changed fields,
      stored in the log entry and shown in the notification mail.]
- [ ] **Recipient:** always the email address in the application, or one the foundation enters?
      [The application's email address, which the admin can overwrite in the dialog.]

## 2. Dependency: mail must be stable

The feature is useless if the link mail doesn't arrive. The M365 basic-auth question is open (see
`INCIDENTS.md` and the message sent to the foundation's IT,
`~/Desktop/gesuchsportal-mailversand-m365.txt`). Fix this first or at the same time. As a
workaround, the admin dialog should also show the link with a copy button. Then the foundation can
send it from Outlook if the mail fails.

## 3. Implementation

### 3.1 Backend

- [ ] Migration for `applications`: `edit_token` (string 64, nullable, unique), `edit_token_expires_at`
      (timestamp, nullable) and `edited_by_applicant_at` (timestamp, nullable). Add them to
      `$fillable` only if needed. Hide `edit_token` from JSON (`$hidden`) so it never leaks
      through the API or the `MailQueue` data.
- [ ] Migration: make `application_logs.user_id` nullable. Show "Gesuchsteller/in" in the log list
      when it is null (`resources/js/views/pages/application/log/List.vue`). `Logger::log` gets
      an optional user (`auth()->id()` or null).
- [ ] `Application` helpers: `issueEditToken(int $days)`, `revokeEditToken()`,
      `scopeByEditToken($token)` (valid, not expired, state `OPEN`), `canBeEditedByApplicant()`.
      Generate the token with `Str::random(64)`.
- [ ] Admin API, inside the `auth:sanctum` group in `routes/api.php`:
  - `POST api/application/edit-link/{application:uuid}` takes `email` and `message` (optional),
    sets the token, queues the `edit_link` mail, logs "Bearbeitungslink gesendet" and returns the
    URL (for the copy button).
  - `DELETE api/application/edit-link/{application:uuid}` revokes the token and logs it.
  - Role: decide whether this needs `role:admin`. Probably yes, like `reverse` and `archive`.
- [ ] Public routes in `routes/web.php`, placed before the catch-all auth group:
  - `GET /formular/bearbeiten/{token}` renders the same form view, with `window.APORTA_EDIT_TOKEN`
    set. Invalid or expired tokens get a friendly German error page, not a 404.
  - `GET /form/edit/{token}` returns the application as JSON, with only the form fields and the
    files (name, title, key).
  - `PUT /form/edit/{token}` saves.
  - Add `throttle` middleware to all three.
- [ ] `FormController::update`:
  - Reuse the field mapping from `store()`. Extract a private `mapRequest(Request $r): array` used
    by both, so they can't drift apart.
  - Never touch `uuid`, `year`, `application_state_id`, `approved_*`, `denied_*`, `textblock_*`,
    `remarks*` or `project_contribution_approved*`.
  - Recompute `project_add_instit_final_total`.
  - Compute the diff (`getDirty()` before saving), and store it in the log entry and the
    notification.
  - Set `edited_by_applicant_at`. Keep the token if the default "unlimited until expiry" holds.
  - Queue an `edit_notification` mail to `config('mail.to')`, plus a confirmation to the
    applicant if wanted.
- [ ] `ApplicationUpdateByApplicantRequest`: copy the rules from `ApplicationCreateRequest`. The
      required `files.*` count as present if the payload carries an existing file reference.
      Write a custom rule or prepare the input. Reuse the messages, ideally from a shared trait.
- [ ] Files:
  - The payload per file key is either `{name, title}` (a new temp upload) or
    `{uuid, existing: true}` (unchanged).
  - A replaced or removed file: delete its `ApplicationFile` row and remove the file with
    `Media::removeFromFolder`. A new file: copy it as in `store()`.
  - Problem: `ApplicationFile` has no "key" column (tax_exemption, budget …), only `title`
    ("Steuerbefreiung", "Budget" …). Either map by title (fragile, since admins can rename) or add
    a `key` column (nullable, backfilled from title for existing rows). **Preferred: add `key`.**
  - Admin-uploaded files (`user_id` ≠ 1) must not be shown or deleted in the applicant form.
- [ ] Mail: extend `App\Tasks\Notification` with the types `edit_link` (to the applicant, with the URL,
      expiry date and the admin's message) and `edit_notification` (to the foundation, with the
      diff, IBAN changes highlighted). New Mailables and Blade templates in
      `resources/views/mails/`. The texts are German, with "Sie" for applicants.

### 3.2 Form (`Form.vue`), the riskiest part

- [ ] Edit mode when `window.APORTA_EDIT_TOKEN` is set: skip the password step, load
      `/form/edit/{token}`, fill `form`, and submit with PUT.
- [ ] Mapping that needs care:
  - `name_change` = `former_name ? 'yes' : 'no'`.
  - The number-masked amounts (`project_cost_total` etc., the `number` component with `'`
    separators). Check that they show up and come back unformatted.
  - `project_add_instit_2..5`, plus the totals and answers.
  - `iban` (stored without the `CH` prefix, check the mask).
  - The confirmation checkboxes (`isTaxExempt`, `isIdealistic`, `isExtraordinary`,
    `isSupportingResidents`, `confirm_correctness`) aren't stored. The applicant ticks them again.
- [ ] Dropzones: show the existing file per key (filename plus a "replace" or "delete" action)
      instead of an empty dropzone. Dropzone's `manuallyAddFile` could show them, but a simple
      "existing file" row that hides the dropzone until "replace" is clicked is probably more
      robust.
- [ ] Headline and success text: "Gesuch bearbeiten" and "Ihre Änderungen wurden gespeichert".
      Don't call `reset()` after saving in edit mode.
- [ ] An expired token or a changed state shows a clear German message: "Dieser Link ist nicht mehr
      gültig. Bitte wenden Sie sich an die Stiftung."
- [ ] Regression check: create mode must behave exactly as before. That's the main risk, because
      the live form is in the same component.

### 3.3 Admin backend

- [ ] `Show.vue`: a "Bearbeitungslink senden" button, visible while the state is `OPEN`. A dialog
      with the email (prefilled) and the message, then sends. After that it shows the URL with a
      copy button and the expiry date. Plus "Link widerrufen" while a token is active.
- [ ] Show "Vom Gesuchsteller bearbeitet am …" on the application if `edited_by_applicant_at` is set.
- [ ] The log list shows the new entries, including the diff.

### 3.4 Tests (`tests/Feature`, currently only `ExampleTest.php`)

- [ ] Token lifecycle: issue, valid, expired, revoked, state ≠ `OPEN` → 403 or the error page.
- [ ] Update keeps the protected fields (state, year, approvals, remarks) unchanged.
- [ ] Files: keep, replace, delete. Admin files are untouched.
- [ ] Validation: required files are satisfied by existing files.
- [ ] Mails are queued with the correct types. `edit_token` never appears in a JSON response or in
      `mail_queue.data`.
- [ ] Manual: the full flow in the browser, create mode included.

## 4. Rollout

- [ ] Deploy together with the pending deploys from `INCIDENTS.md`. Production was still on b81d960
      as of 2026-11-09. Run `php artisan migrate`, then `npm run production` and commit the built
      assets, as usual.
- [ ] Brief the foundation: how to send a link, the validity period, what applicants can change.
