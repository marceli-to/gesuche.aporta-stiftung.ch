# Deploy: Laravel 13 + Bearbeitungslink (Nov 2026)

Production is on `b81d960`. This deploy brings the Laravel 13 upgrade and the edit link feature
together. Back up the production database and the `storage` folder before you start.

## 1. Check the server's state

```
cd ~/www/gesuche.aporta-stiftung.ch
git status --short
git log --oneline -1
crontab -l
php -v | head -1
composer --version
grep -E "^(APP_ENV|APP_URL|MAIL_TO)" .env
```

Expect:
- `git status` shows no changes to tracked files. Untracked files are fine.
- The last commit is `b81d960`.
- `APP_ENV=production` and `APP_URL=https://gesuche.aporta-stiftung.ch`. The edit link URL is
  built from `APP_URL`.
- `MAIL_TO=gesuch@aporta-stiftung.ch`.

## 2. Maintenance mode

```
php artisan down
```

## 3. Pull the new code

```
git pull origin master
git log --oneline -1
```

The last commit should be the newest one on `origin/master`.

## 4. Install PHP packages

```
composer install --no-dev --optimize-autoloader
```

Composer has to run on PHP 8.3, because Laravel 13 needs it. If `composer` runs on an older PHP,
use this instead:

```
/usr/local/php83/bin/php $(which composer) install --no-dev --optimize-autoloader
```

## 5. Run the database migrations

```
php artisan migrate --force
```

There should be three migrations:
- `2026_11_18_100000_alter_applications_table_add_edit_token`
- `2026_11_18_100100_alter_application_logs_table_for_applicant_entries`
- `2026_11_18_100200_alter_application_files_table_add_key`: this one also fills in the key for
  existing files.

## 6. Clear the caches

```
php artisan optimize:clear
```

## 7. Point the cron at PHP 8.3

Run `crontab -e`. In the `schedule:run` line, change `/usr/local/php82/bin/php` to
`/usr/local/php83/bin/php`. Without this, no mails from the queue go out.

## 8. Take the site live again

```
php artisan up
```

## 9. Test the site

- Log in, open an application and download a letter (PDF).
- Submit a test application through the public form.
- On that test application, send an edit link to yourself, change one field and save.
- Open the link again: it should say "nicht mehr gültig".
- After about a minute, the addresses you used should get three mails: the submission
  confirmation, the link mail and the confirmation of the changes. `gesuch@` gets "Neues
  Beitragsgesuch" and "Gesuch geändert".
- In the log of the test application, the change should show up as a list.
- Then delete the test application.

## 10. Check the logs

```
tail -50 storage/logs/laravel.log
php artisan tinker --execute='echo App\Models\MailQueue::latest("id")->take(6)->get(["id","type","processed"])->toJson();'
```

There should be no new errors, and the queued mails should be marked as processed.

## 11. Afterwards

- Send Corinna the mail: `~/Desktop/gesuche-bearbeitungslink-mail.txt`.
- Tick the deploy and briefing boxes in `PLAN-EDIT-LINK.md` and `INCIDENTS.md`.

## If something goes wrong

If you need to roll back after step 5, do it in this order, while the new code is still in place:

```
php artisan down
php artisan migrate:rollback --step=3 --force
git checkout b81d960
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan up
```

Leave the cron on php82 for the old code, or switch it back. If the migrations themselves failed,
restore the database from the backup instead.
