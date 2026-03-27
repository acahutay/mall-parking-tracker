# Mall Parking Tracker (Simple Demo)

This is a very simple parking tracker app for mall guards.

Main idea:
- Guard checks if section is available
- Guard gives driver a card
- If full, driver cannot park
- On checkout, guard updates the slot back

## Stack

- Laravel 12
- SQLite
- Blade (basic html page)

## Quick Start

1. install dependencies
2. setup env
3. migrate and seed
4. run server

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Open in browser:

`http://127.0.0.1:8000`

## Features

- Simple dashboard for guards
- View parking sections + available slots
- Park endpoint + form
- Checkout endpoint + form
- Basic API routes

## Pages / Routes

Web:
- `GET /` dashboard page
- `POST /park`
- `POST /checkout`

API:
- `GET /api/sections`
- `POST /api/park`
- `POST /api/checkout`

## API Samples

Park:

```json
{
  "section_id": 1,
  "plate_number": "ABC-111"
}
```

Checkout:

```json
{
  "card_id": 1
}
```

## Seeded Data

By default seeder creates:
- Floor 1 Section A
- Floor 1 Section B
- Floor 2 Section C

all sections have 5 slots.

## Notes

- This is demo quality and not production ready
- Validation is minimal
- Concurrency handling is not strict
- Security hardening is not complete

## Test

```bash
php artisan test
```
