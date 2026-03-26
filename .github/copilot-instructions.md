# GitHub Copilot Instructions

## Project Overview

This is a **Mall Parking Tracker** web application built with Laravel (PHP 8.2+). It tracks parking availability and usage within a shopping mall.

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Vite, Blade templates
- **Database**: SQLite (development), compatible with MySQL/PostgreSQL
- **Testing**: PHPUnit 11
- **Code Style**: Laravel Pint (PSR-12)

## Coding Conventions

- Follow PSR-12 coding standards enforced by Laravel Pint.
- Use Laravel's built-in features (Eloquent ORM, Blade views, Artisan commands) wherever possible.
- Keep controllers thin — move business logic to service classes or model methods.
- Use Laravel's validation, form requests, and resource classes for API responses.
- Write PHPUnit feature and unit tests for all new functionality under the `tests/` directory.

## Directory Structure

- `app/Models/` — Eloquent models
- `app/Http/Controllers/` — HTTP controllers
- `app/Http/Requests/` — Form request validation classes
- `database/migrations/` — Database schema migrations
- `database/seeders/` — Database seeders
- `resources/views/` — Blade templates
- `routes/web.php` — Web routes
- `tests/` — PHPUnit tests (Feature and Unit)

## PR Review Guidelines

When reviewing pull requests, pay attention to:

1. **Security**: Check for SQL injection, XSS, CSRF protection, and proper input validation.
2. **Performance**: Look for N+1 query issues in Eloquent relationships; suggest eager loading where needed.
3. **Laravel Best Practices**: Ensure use of Eloquent over raw queries, proper use of middleware, and adherence to MVC separation.
4. **Test Coverage**: New features should include corresponding PHPUnit tests.
5. **Code Style**: Code must conform to PSR-12 / Laravel Pint standards.
