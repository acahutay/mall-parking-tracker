# Mall Parking Tracker

A Laravel-based REST API for tracking parking availability and sessions in a mall environment.

## Features

- Manage **parking lots** with configurable capacity
- Track **parking spots** (regular, handicap, electric) per lot
- Register **vehicles** (car, motorcycle, truck) by license plate
- Record **parking sessions** with automatic fee calculation ($2 / hour)

## Requirements

- PHP ^8.2
- Composer
- Node.js & npm
- SQLite (default) or any supported RDBMS

## Quick Start

```bash
composer run setup
```

This installs PHP and JS dependencies, generates the app key, runs migrations, and builds front-end assets.

## Development Server

```bash
composer run dev
```

## Running Tests

```bash
composer run test
```

## API Endpoints

All endpoints are prefixed with `/api`.

### Parking Lots

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/parking-lots` | List all parking lots |
| POST | `/api/parking-lots` | Create a parking lot |
| GET | `/api/parking-lots/{id}` | Show a parking lot |
| PUT | `/api/parking-lots/{id}` | Update a parking lot |
| DELETE | `/api/parking-lots/{id}` | Delete a parking lot |

### Parking Spots

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/parking-spots` | List all spots (filterable by `parking_lot_id`, `is_available`, `type`) |
| POST | `/api/parking-spots` | Create a parking spot |
| GET | `/api/parking-spots/{id}` | Show a parking spot |
| PUT | `/api/parking-spots/{id}` | Update a parking spot |
| DELETE | `/api/parking-spots/{id}` | Delete a parking spot |

### Vehicles

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/vehicles` | List all vehicles |
| POST | `/api/vehicles` | Register a vehicle |
| GET | `/api/vehicles/{id}` | Show a vehicle |
| DELETE | `/api/vehicles/{id}` | Remove a vehicle |

### Parking Sessions

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/parking-sessions` | List all sessions |
| POST | `/api/parking-sessions` | Check a vehicle in (start session) |
| GET | `/api/parking-sessions/{id}` | Show a session |
| PATCH | `/api/parking-sessions/{id}/checkout` | Check a vehicle out (end session, calculate fee) |

## Fee Calculation

Fees are calculated at checkout based on **$2.00 per hour** (minimum 1 hour, rounded up to the nearest hour).

## License

The MIT License (MIT).
