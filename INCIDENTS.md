# Incident Oct 2026: missing mails, upload crashes

Status as of 2026-11-17. Update this file as items get resolved.

## Trigger

The foundation asked (2026-10-22) whether "20 Gesuche, last one on 9.10.2026" was
correct or a technical problem. Analysis was based on production `storage/logs/laravel.log`
and `scheduler.log`.

## Findings

### 1. Outgoing mail broken (RESOLVED 2026-11-06, workaround)

- From 2026-04-17 to 2026-11-05, every send failed with
  `535 5.7.3 Authentication unsuccessful … OUTLOOK.COM` for SMTP user
  `gesuch@aporta-stiftung.ch` (Microsoft 365). Last successful send before that: 2025-12-17.
- Effect: applicants got no confirmation, the foundation got no notification.
- `app/Tasks/Notification.php` catches the exception, sets `processed = 1` and stores
  the error in `mail_queue.error`. So the scheduler reports DONE and nothing is retried.
- **Fix:** on 2026-11-06 at 11:20 the production `.env` was switched to send through the mailbox
  `noreply@aporta-stiftung.ch` (`MAIL_USERNAME` and `MAIL_FROM_ADDRESS`). `MAIL_TO` stays
  `gesuch@aporta-stiftung.ch`. The first submissions afterwards (2026-11-06 Stiftung Mühlehalde,
  2026-11-07 Verein gegen Altersarmut) sent both the notification and the confirmation.
- The root cause for `gesuch@` was never found (likely a changed password or SMTP AUTH disabled
  for that mailbox). That doesn't matter as long as `noreply@` is used.
- 26 applications got no mails (`mail_queue` rows of type `confirmation` with an error:
  1 in Apr, 18 in Sep, 4 in Oct, 3 on 3–5 Nov 2026). **Decision: no resend.** The foundation
  was informed.
- `php artisan mail:smtp-check` (added in 7f764c8) tests any SMTP mailbox with prompted
  credentials. Use it before changing `MAIL_*` on the server.

### 2. The application count is correct

Each submission produces one pair of SMTP errors (2 queued mails). Counting the pairs from
2026-09-02 to 2026-10-09 gives exactly 20, and the last pair is 2026-10-09 09:07. There are no
log entries after that up to 2026-10-22, so there were no further submissions in that period.
The scheduler (`scheduler.log`) ran every ~3 min with no gaps through 2026-10-22. Its occasional
FAILs are short MySQL outages (`SQLSTATE[HY000] [2002]`), not lost submissions.

### 3. Upload validation crash (FIXED in b81d960)

- The dropzone accepted `.doc, .docx, .xls, .xlsx, .zip`, but `FileUploadRequest` only allowed
  `png,jpg,jpeg,csv,txt,xlx,xls,pdf`. The nested message arrays then caused
  `Array to string conversion` (500) instead of a validation error. 5 occurrences
  (Sept/Oct 2026).
- Fix: aligned `mimes`, flattened messages. The frontend now shows upload errors, and each
  dropzone has its own ref (`dropzone_<key>`).

### 4. "Data too long" on `number_residents_benefit_program` (FIXED in b81d960)

- The column was `varchar(255)` and an applicant (kispi.uzh.ch) failed 3 times on 2026-09-29/30.
  They most likely succeeded at 2026-09-30 07:07 after shortening the text (not verified in the DB).
- Fix: migration `2026_10_22_100000_…_to_text.php`.
  **Requires `php artisan migrate` on production.** It was deployed by Marcel on 2026-10-22. Verify
  that it ran.

### 5. `env()` calls outside config (FIXED in 146d519, not deployed)

`MAIL_TO` and `MAIL_FROM_*` were read with `env()` in app code. Those calls return `null` once
`php artisan config:cache` has run, which would silently break mail again. They now read
`config('mail.to')` and `config('mail.from')`. Production config is currently not cached, so the
deployed code still works.

## Open items

- [ ] Deploy 7f764c8 + 146d519 (production is still on b81d960 as of 2026-11-09)
- [ ] Confirm the migration ran on production
- [ ] Decide on a Reply-To: replies from applicants now go to `noreply@`, not `gesuch@`
- [ ] Before Dec 2026: Microsoft plans to disable basic auth for SMTP AUTH by default. If sending
      breaks again, re-enable it in M365 or move to OAuth2 (Entra app with `SMTP.SendAsApp`, no DNS
      changes needed). **2026-11-17:** a request to the foundation's external IT was drafted
      (`~/Desktop/gesuchsportal-mailversand-m365.txt`). It asks for OAuth2 (variant A: Entra app,
      Tenant-ID, Client-ID, Client Secret, FullAccess on `noreply@`) or, as an interim, keeping basic
      auth enabled (variant B), plus advance notice of credential changes and a contact person.
      Waiting for their answer. If OAuth2: token fetching (XOAUTH2, client credentials) still has
      to be built on our side, about half a day.
- [ ] Optional: alerting when mail sending fails (currently errors are only logged)
- [ ] Optional: verify the Kispi application (angelina.schlegel@kispi.uzh.ch) exists in the DB
- [ ] Optional: remove plaintext passwords from `INSTALL.txt` (committed to git) and rotate them
