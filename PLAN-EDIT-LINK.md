# Plan: edit link for submitted applications

Status as of 2026-11-24: **merged into `master` (`1b52217`), not deployed yet. Since 2026-11-19: the link works for one save, the admin message is gone, the change list shows only real changes, mails are aligned. The foundation's point 6 (reopening for corrections) is answered in the draft mail to Corinna (`~/Desktop/gesuche-bearbeitungslink-mail.txt`), not sent yet. Next: deploy step by step with `deploy.md` (Laravel 13 / Vite, migrations, PHP 8.3 cron, smoke test, rollback), send the mail, then rollout (section 4).**
Prerequisite done: the app is on Laravel 13 with Vite (on `master`, not deployed yet, see `INCIDENTS.md` item 7).
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
  hardcoded to `2026` and two `MailQueue` rows are queued (`notification` and `confirmation`).
  **`year` is the funding cycle, not the calendar year** (the 2026 applications were submitted
  Sept 2025 to Apr 2026), so "use the current year" would be wrong. Left as is; see open points.
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

## 1. Decisions (made 2026-11-18)

- [x] **When is editing allowed?** Only in state `OPEN` (1). Once the state changes, the link stops
      working.
- [x] **Closing the window (2026-11-19):** leaving `OPEN` (confirm, deny, final approval) or archiving
      deletes the link (`Application::booted`). "Genehmigung rückgängig machen" brings the application
      back to `OPEN` but not the old link: the foundation sends a new one if needed. This covers their
      request to reopen an application later for corrections ("nachträglich anpassen"): undo, send a link,
      then click "Gesuch vollständig" again. Applications already with the city have no way back to `OPEN`.
- [x] **How long is the link valid?** 14 days, and it works once: saving the form ends it
      (changed 2026-11-24, was "unlimited saves until expiry"). A new link
      replaces the old one.
- [x] **IBAN and bank details** (`iban`, `bank_account`, `beneficiary`): editable, but changes are
      highlighted in the notification to the foundation (fraud risk if the mail is intercepted or
      forwarded).
- [x] **History:** a diff of the changed fields, stored in the log entry and shown in the
      notification mail.
- [x] **Recipient:** the application's email address, which the admin can overwrite in the dialog.
- [x] **Confirmation to the applicant:** yes. After saving, the applicant also gets a short
      confirmation mail ("Ihre Änderungen wurden gespeichert"), in addition to the notification
      to the foundation.
- [x] **Role:** sending and revoking links needs `role:admin`, like `reverse` and `archive`.

## 2. Dependency: mail must be stable

Resolved: mail has worked again since 2026-11-06, sent through `noreply@aporta-stiftung.ch`
(`INCIDENTS.md` item 1). The admin dialog still shows the link with a copy button, as a fallback
if a single send fails.

## 3. Implementation

### 3.1 Backend

- [x] Migration for `applications`: `edit_token` (string 64, nullable, unique), `edit_token_expires_at`
      (timestamp, nullable) and `edited_by_applicant_at` (timestamp, nullable). Add them to
      `$fillable` only if needed. Hide `edit_token` from JSON (`$hidden`) so it never leaks
      through the API or the `MailQueue` data.
- [x] Migration: make `application_logs.user_id` nullable. Show "Gesuchsteller/in" in the log list
      when it is null (`resources/js/views/pages/application/log/List.vue`). `Logger::log` gets
      an optional user (`auth()->id()` or null).
- [x] `Application` helpers: `issueEditToken(int $hours)`, `revokeEditToken()`,
      `scopeByEditToken($token)` (valid, not expired, state `OPEN`), `canBeEditedByApplicant()`.
      Generate the token with `Str::random(64)`.
- [x] Admin API, inside the `auth:sanctum` group in `routes/api.php`:
  - `POST api/application/edit-link/{application:uuid}` takes `email` (the admin message was dropped on 2026-11-24),
    sets the token, queues the `edit_link` mail, logs "Bearbeitungslink gesendet" and returns the
    URL (for the copy button).
  - `DELETE api/application/edit-link/{application:uuid}` revokes the token and logs it.
  - Both need `role:admin` (decided).
- [x] Public routes in `routes/web.php`, placed before the catch-all auth group:
  - `GET /formular/bearbeiten/{token}` renders the same form view, with `window.APORTA_EDIT_TOKEN`
    set. Invalid or expired tokens get a friendly German error page, not a 404.
  - `GET /form/edit/{token}` returns the application as JSON, with only the form fields and the
    files (name, title, key).
  - `PUT /form/edit/{token}` saves.
  - Add `throttle` middleware to all three.
- [x] `FormController::update`:
  - Reuse the field mapping from `store()`. Extract a private `mapRequest(Request $r): array` used
    by both, so they can't drift apart.
  - Never touch `uuid`, `year`, `application_state_id`, `approved_*`, `denied_*`, `textblock_*`,
    `remarks*` or `project_contribution_approved*`.
  - Recompute `project_add_instit_final_total`.
  - Compute the diff (`getDirty()` before saving), and store it in the log entry and the
    notification.
  - Set `edited_by_applicant_at`. Delete the token: the link works once.
  - Queue an `edit_notification` mail to `config('mail.to')`, plus an `edit_confirmation` mail
    to the applicant (decided).
- [x] `ApplicationUpdateByApplicantRequest`: extend `FieldErrorRequest` (messages go in
      `fieldMessages()`, never as arrays in `messages()`) and copy the rules from
      `ApplicationCreateRequest`. The required `files.*` count as present if the payload carries an
      existing file reference. Write a custom rule or prepare the input. Reuse the messages, ideally
      from a shared trait.
- [x] Files:
  - The payload per file key is either `{name, title}` (a new temp upload) or
    `{uuid, existing: true}` (unchanged).
  - A replaced or removed file: delete its `ApplicationFile` row and remove the file with
    `Media::removeFromFolder`. A new file: copy it as in `store()`.
  - Problem: `ApplicationFile` has no "key" column (tax_exemption, budget …), only `title`
    ("Steuerbefreiung", "Budget" …). Either map by title (fragile, since admins can rename) or add
    a `key` column (nullable, backfilled from title for existing rows). **Preferred: add `key`.**
  - Admin-uploaded files (`user_id` ≠ 1) must not be shown or deleted in the applicant form.
- [x] Mail: extend `App\Tasks\Notification` with the types `edit_link` (to the applicant, with the URL,
      expiry date), `edit_notification` (to the foundation, with the
      diff, IBAN changes highlighted) and `edit_confirmation` (to the applicant, changes saved). New Mailables and Blade templates in
      `resources/views/mails/`. The texts are German, with "Sie" for applicants.

### 3.2 Form (`Form.vue`), the riskiest part

- [x] Edit mode when `window.APORTA_EDIT_TOKEN` is set: skip the password step, load
      `/form/edit/{token}`, fill `form`, and submit with PUT.
- [x] Mapping that needs care:
  - `name_change` = `former_name ? 'yes' : 'no'`.
  - The number-masked amounts (`project_cost_total` etc., the `number` component with `'`
    separators). Check that they show up and come back unformatted.
  - `project_add_instit_2..5`, plus the totals and answers.
  - `iban` (stored without the `CH` prefix, check the mask).
  - The confirmation checkboxes (`isTaxExempt`, `isIdealistic`, `isExtraordinary`,
    `isSupportingResidents`, `confirm_correctness`) aren't stored. The applicant ticks them again.
- [x] Dropzones: show the existing file per key (filename plus a "replace" or "delete" action)
      instead of an empty dropzone. Dropzone's `manuallyAddFile` could show them, but a simple
      "existing file" row that hides the dropzone until "replace" is clicked is probably more
      robust.
- [x] Headline and success text: "Gesuch bearbeiten" and "Ihre Änderungen wurden gespeichert".
      Don't call `reset()` after saving in edit mode.
- [x] An expired token or a changed state shows a clear German message: "Dieser Link ist nicht mehr
      gültig. Bitte wenden Sie sich an die Stiftung."
- [x] Regression check: create mode must behave exactly as before. That's the main risk, because
      the live form is in the same component.

### 3.3 Admin backend

- [x] `Show.vue`: a "Bearbeitungslink senden" button, visible while the state is `OPEN`. A dialog
      with the email (prefilled), then sends. After that it shows the URL with a
      copy button and the expiry date. Plus "Link widerrufen" while a token is active.
- [x] Show "Vom Gesuchsteller bearbeitet am …" on the application if `edited_by_applicant_at` is set.
- [x] The log list shows the new entries, including the diff.

### 3.4 Tests (`tests/Feature`, currently only `ExampleTest.php`)

- [x] Token lifecycle: issue, valid, expired, revoked, state ≠ `OPEN` → 403 or the error page.
- [x] Update keeps the protected fields (state, year, approvals, remarks) unchanged.
- [x] Files: keep, replace, delete. Admin files are untouched.
- [x] Validation: required files are satisfied by existing files.
- [x] Mails are queued with the correct types. `edit_token` never appears in a JSON response or in
      `mail_queue.data`.
- [x] Manual: the edit flow in headless Chrome (load, values and masks, replace a file, missing
      confirmation, save, mails in MailHog, invalid link) and a create-mode smoke test (questions,
      empty submit shows the errors). A full create submission with uploads was not repeated; the
      feature test covers `store()`.

### 3.5 As built (backend), deviations from the plan above

- `edit_token` stores the **SHA-256 hash** of the token. The plain link exists only in the mail
  and in the response to the admin, so it can't be read back later. The admin dialog shows the
  URL once, right after sending.
- The `edit_link` mail is **sent right away**, not through `mail_queue`, because the queue
  stores its data in the database. If sending fails, the response says `mail_sent: false`, the
  log says so, and the admin copies the URL from the dialog.
- `application_logs` got a `details` column (JSON list of changes: field, label, old, new, bank).
  The log list shows it, bank changes in red.
- `application_files.key` is backfilled for files created by user 1 within 5 minutes of their
  application (first file per key wins). New submissions set it from the form's file key.
  Locally about 195 applications got keys; admin files stay null and are never shown or touched.
- Extra route `GET /form/edit/{token}/file/{uuid}`: the applicant can download their own files.
- `GET /form/edit/{token}` returns empty further-institution amounts as `''` (stored as 0.00),
  otherwise `required_with` asks for an institution name on save.
- Link validity is `config('client.edit_link_hours')` (`APORTA_EDIT_LINK_HOURS`, default 48).
- Applicant edits queue `edit_notification` (subject starts with "ACHTUNG Bankangaben geändert"
  if bank fields changed) and `edit_confirmation`.
- Tests: `tests/Feature/EditLinkTest.php` (12 tests).
- 2026-11-24: the optional admin message in the link mail was removed (free text from the dialog
  ended up in front of the applicant unedited). Mail buttons are left-aligned, and all five mails
  start with an `<h1>`.

### 3.6 As built (form)

- Edit mode skips the password step and the four requirement questions (they were answered on
  submission). `confirm_correctness` must be ticked again.
- Amounts are loaded as whole francs (the form has no decimals). Values round-trip without
  false changes (checked: only the edited fields end up in the diff).
- An existing file shows its name with a download link (`/form/edit/{token}/file/{uuid}`); the
  trash icon only drops it from the form, the dropzone appears for a replacement. The server
  replaces or removes it on save.
- After saving the form shows the success text; the link no longer works (one save per link).
- Download of a file missing on disk returns 404 (locally, production files aren't present).

### 3.7 As built (admin)

- `Show.vue`, admin and state `OPEN` only, not archived: "Bearbeitungslink senden" (or "Neuen
  Bearbeitungslink senden" plus "Link widerrufen" while a link is active, with its expiry).
- The dialog is its own component (`application/components/EditLinkDialog.vue`), because the
  shared `Dialog.vue` closes on any click inside it. It prefills the application's email, then shows the URL with a copy button. If the mail failed, it says so and
  asks the admin to send the link themselves.
- "Von Gesuchsteller/in bearbeitet am …" shows for all users when `edited_by_applicant_at` is set.
- A link for a non-open application returns 409 (not 422, which would trigger the generic
  "Bitte alle mit * markierten Felder prüfen!" notice).
- Tested in headless Chrome with a temporary admin: send (invalid email, then valid), mail in
  MailHog, copy, status after send, link opens, revoke, revoked link shows the error page, log.

### Open points found while building

- ~~`role:admin` let editors through~~: fixed on `master` (a2d21db, `INCIDENTS.md` item 8) and
  merged into this branch. Editors get 403 on the edit-link routes (tested). Editors are the city's
  reviewers (external), admins are the foundation.
- ~~Routes without any role check~~: user management, `PUT application/{uuid}` and `approve/final`
  now need `role:admin` (on this branch, `317dd1a`, `INCIDENTS.md` item 8). Still open: anyone
  logged in can delete any comment.
- **Local test data:** open application #244 "Testverein Bearbeitungslink" (copy of #240 with test
  contact data and placeholder PDFs) in the local DB. Production has no OPEN test application.
- **`year`**: submissions since Sept 2026 are probably stored as 2026 instead of 2027 on
  production. Check and update `FormController::store` for the new cycle.

## 4. Rollout

- [x] Run `npm run build` and commit `public/build` (2026-11-24, on `master`).
- [x] Manual test of the whole flow locally (2026-11-24): send link, edit, mails in MailHog, link
      invalid after saving, undo approval and new link.
- [ ] Deploy with `deploy.md` (step by step, includes the three migrations from 2026-11-18, the
      cron change, a smoke test and a rollback). Planned for 2026-11-24.
- [ ] Brief the foundation: how to send a link, the validity period, what applicants can change.
      Draft ready: `~/Desktop/gesuche-bearbeitungslink-mail.txt` (also answers point 6 and Corinna's
      2026-11-24 question when it goes live). Send after the deploy.
