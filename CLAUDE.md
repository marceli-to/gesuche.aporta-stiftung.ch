# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 13 application with Vue.js 2 frontend for managing grant applications (Gesuche) for the A-Porta Foundation. The system handles application submissions, file uploads, user management, and application processing workflows.

## Tech Stack

- **Backend**: Laravel 13 (PHP 8.3; composer platform is pinned to 8.3 because production runs 8.3)
- **Frontend**: Vue.js 2 with Vue Router and Vuex
- **Database**: MySQL/MariaDB with Eloquent ORM
- **Build Tools**: Vite 7 (`laravel-vite-plugin`, `@vitejs/plugin-vue2`), Node 22
- **PDF Generation**: DomPDF
- **Excel Export**: Maatwebsite/Excel
- **Authentication**: Laravel Sanctum
- **Testing**: PHPUnit

## Development Commands

### Backend Commands
```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Run database seeders  
php artisan db:seed

# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generate application key
php artisan key:generate

# Run tests
vendor/bin/phpunit
```

### Frontend Commands
```bash
# Install dependencies
npm ci

# Dev server with hot reload (uses Herd's TLS certificate, writes public/hot)
npm run dev

# Production build to public/build (committed, deployed via git)
npm run build
```

## Application Architecture

### Backend Structure
- **Models**: Core entities are Application, ApplicationState, ApplicationFile, ApplicationComment, ApplicationLog, and User
- **Controllers**: API controllers in `app/Http/Controllers/Api/` handle CRUD operations
- **Authentication**: Uses Laravel Sanctum with email verification required
- **File Handling**: Application files stored in `storage/app/files/{uuid}/` directories
- **PDF Generation**: Custom templates for approval/denial letters in `resources/views/pdf/`
- **Exports**: Excel exports available for applications by year

### Frontend Structure
- **Vue Components**: Organized by feature in `resources/js/views/pages/`
- **Routing**: Separate route files for each major feature (applications, comments, logs, users)
- **State Management**: Vuex store configuration in `resources/js/config/store.js`
- **Mixins**: Common functionality in `resources/js/mixins/` (ErrorHandling, Filters, Sort, etc.)

### Key Models and Relationships
- `Application`: Main grant application entity with multiple states and file attachments
- `ApplicationState`: Defines workflow states (OPEN, IN_PROCESS_EXTERNAL, PENDING_APPROVAL, APPROVED, DENIED, etc.)
- `ApplicationFile`: File attachments linked to applications
- `User`: System users with role-based access

### Application Workflow
1. **Open**: Initial application submission
2. **In Process (External)**: Under external review
3. **Pending Approval**: Awaiting internal approval
4. **Approved External**: Externally approved
5. **Denied External**: Externally denied  
6. **Approved**: Final approval
7. **Denied**: Final denial

### File Upload System
- Files uploaded to temporary storage during form completion
- Moved to permanent UUID-based directories upon application submission
- Support for multiple file types: portraits, annual reports, budgets, project descriptions, etc.

### Structure and conventions
- Laravel 11+ app structure: middleware, routing and exceptions are configured in `bootstrap/app.php`, providers in `bootstrap/providers.php`, the scheduler (notification mails) in `routes/console.php`.
- Form requests that feed the Vue error handling extend `App\Http\Requests\FieldErrorRequest` and define `fieldMessages()` as `'attr.rule' => ['field' => ..., 'error' => ...]`. The 422 response lists each error as `{field, error}`, which `resources/js/mixins/ErrorHandling.js` reads. Do not return arrays from `messages()`; Laravel 13 crashes on them.

### Deployment (Hostpoint)
- The site runs PHP 8.3 via `public/.htaccess` on the server (not in git).
- The scheduler cron must call PHP 8.3 (`/usr/local/php83/bin/php artisan schedule:run`), otherwise no notification or confirmation mails go out.

## Database Configuration

The application uses MySQL/MariaDB with the following key tables:
- `applications`: Main application data
- `application_states`: Workflow state definitions
- `application_files`: File attachments
- `application_comments`: Comments/notes on applications
- `application_logs`: Audit trail
- `users`: System users

## Testing

- PHPUnit configuration in `phpunit.xml`
- Tests located in `tests/Feature/` and `tests/Unit/`
- Uses SQLite in-memory database for testing
- Run with `vendor/bin/phpunit`

## Frontend Build Process

Vite (`vite.config.js`) builds four entries into `public/build`, loaded with `@vite([...])` in the Blade layouts:
- `resources/sass/app.scss`: all styles
- `resources/js/app.js`: admin SPA (`layout/authenticated`, also loaded on guest pages)
- `resources/js/form.js`: public application form (`layout/form`)
- `resources/js/validation.js`: plain input validation on guest pages and the form

Notes:
- `resources/js/bootstrap.js` must be imported first: it sets `window.Vue` and `window.axios`, which `vue-axios-interceptors` and `mixins/Filters.js` use as globals.
- `vue` is aliased to the full build (`vue/dist/vue.esm.js`), because the layouts mount on in-DOM markup (`<app-component />`) that Vue compiles at runtime.
- Images and PDF fonts stay in `public/assets` (not processed by Vite); Sass references them with absolute `/assets/img/...` URLs.

## Authentication & Authorization

- Email verification required for new users
- Registration disabled in production
- Role-based access control through middleware
- Sanctum provides API authentication for SPA

## File Management

- Application files stored in `storage/app/files/{application_uuid}/`
- PDF generation for approval/denial letters
- Excel export functionality for application data
- Image processing with custom filters and caching

## Mail System

- Confirmation emails for application submissions
- Notification emails for status changes  
- Queue system for background mail processing
- Mail templates in `resources/views/mails/`
- Local (`APP_ENV=local`): mail goes to MailHog (SMTP 127.0.0.1:1025, UI http://localhost:8025). `AppServiceProvider` falls back to the log mailer if local SMTP points anywhere else, so .test never sends real mail. Outside production, `Mail::alwaysTo(MAIL_TO)` redirects all recipients.
- `php artisan mail:smtp-check` talks to the SMTP host you enter directly (bypasses the guard), use it deliberately.

## Open Incidents and Planned Work

- `INCIDENTS.md`: the 2026 mail outage (M365 SMTP auth, resolved 2026-11-06 by sending through `noreply@`), plus open follow-ups. Read this first when working on mail or the application form.
- `PLAN-EDIT-LINK.md`: planned feature, an edit link that lets applicants correct a submitted application. It has the status, the open decisions from the foundation, and an implementation checklist. Read it first when the user mentions the edit link, "Bearbeitungslink", or changes to `Form.vue` or `FormController`. Keep its status line and checkboxes up to date.
