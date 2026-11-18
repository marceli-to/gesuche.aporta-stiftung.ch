# Gesuche – Dr. Stephan à Porta-Stiftung

Gesuchsverwaltung der à Porta-Stiftung: öffentliches Gesuchsformular (`/formular`) und
Administration für die Bearbeitung, Bewilligung und Ablehnung der Gesuche.

- Laravel 13 (PHP 8.3), Sanctum, DomPDF, Laravel Excel
- Vue 2 SPA (Administration) und Formular, gebaut mit Vite

## Setup

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm ci
npm run dev   # Vite dev server
```

Production build: `npm run build`. The compiled assets in `public/build` are committed.

The scheduler (`routes/console.php`) sends the queued notification mails every minute, so
`php artisan schedule:run` must run from cron on the server.

## Notes

- `CLAUDE.md` – architecture overview
- `INCIDENTS.md` – open incidents (mail delivery)
- `PLAN-EDIT-LINK.md` – planned applicant edit link
