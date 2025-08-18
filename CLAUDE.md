# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 11 application with Vue.js 2 frontend for managing grant applications (Gesuche) for the A-Porta Foundation. The system handles application submissions, file uploads, user management, and application processing workflows.

## Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue.js 2 with Vue Router and Vuex
- **Database**: MySQL/MariaDB with Eloquent ORM
- **Build Tools**: Laravel Mix with Webpack
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
npm install

# Development build with watch
npm run watch

# Production build
npm run production

# Development build
npm run dev

# Hot module replacement
npm run hot
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
- **Mixins**: Common functionality in `resources/js/mixins/` (DateTime, ErrorHandling, Filters, etc.)

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

Uses Laravel Mix (webpack.mix.js) to compile:
- Sass files from `resources/sass/app.scss` to `public/assets/css/app.css`
- Main Vue app from `resources/js/app.js` to `public/assets/js/app.js`
- Form validation from `resources/js/form.js` to `public/assets/js/form.js`
- Client-side validation from `resources/js/validation.js`

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