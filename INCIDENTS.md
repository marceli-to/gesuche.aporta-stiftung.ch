# Incident Oct 2026: missing mails, upload crashes

Status as of 2026-10-22. Update this file as items get resolved.

## Trigger

The foundation asked (2026-10-22) whether "20 Gesuche, last one on 9.10.2026" was
correct or a technical problem. Analysis was based on production `storage/logs/laravel.log`
and `scheduler.log`.

## Findings

### 1. Outgoing mail broken (OPEN, needs action outside the code)

- Every send since at least 2026-04-17 fails with
  `535 5.7.3 Authentication unsuccessful … OUTLOOK.COM` for SMTP user
  `gesuch@aporta-stiftung.ch` (Microsoft 365). Last successful send in the log: 2025-12-17.
- Effect: applicants get no confirmation, the foundation gets no notification.
- `app/Tasks/Notification.php` catches the exception, sets `processed = 1` and stores
  the error in `mail_queue.error`. So the scheduler reports DONE and nothing is retried.
- Likely cause: SMTP AUTH disabled for the mailbox, changed password, or Microsoft's
  basic-auth deprecation. Fix in M365 (enable SMTP AUTH / new credentials) or switch to a
  transactional mail provider, then update `.env` (`MAIL_*`).
- Follow-up once mail works: resend the failed mails. Candidates are `mail_queue` rows
  with `processed = 1` and `error IS NOT NULL` (Sept/Oct 2026 = the 20 applications,
  2 rows each: `confirmation` + `notification`). Not built yet. An artisan command that
  resets `processed`/`error` for those rows would let the scheduler pick them up again.

### 2. The application count is correct

Each submission produces one pair of SMTP errors (2 queued mails). Counting the pairs from
2026-09-02 to 2026-10-09 gives exactly 20, and the last pair is 2026-10-09 09:07. There are no log
entries after that, so there have been no further submissions. The scheduler (`scheduler.log`) ran
every ~3 min with no gaps through 2026-10-22. Its occasional FAILs are short MySQL outages
(`SQLSTATE[HY000] [2002]`), not lost submissions.

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

## Open items

- [ ] Fix M365 SMTP authentication (owner: Marcel / foundation IT)
- [ ] Resend failed `mail_queue` mails (artisan command still to be written)
- [ ] Confirm the migration ran on production
- [ ] Optional: verify the Kispi application (angelina.schlegel@kispi.uzh.ch) exists in the DB
- [ ] Optional: alerting when mail sending fails (currently errors are only logged)
