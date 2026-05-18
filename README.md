# Revenue Model Cashflow Engine

A Laravel-based API for modeling and calculating financial cashflows for projects.

## Core Components

**Projects** - Create and manage projects with revenue models and project periods

**Scenario** - Define revenue models (sale, hybrid, etc.) and project timeline

**Inputs** - Configure project parameters:
- Construction: costs, duration, scheduling
- Sales: pricing, duration, transaction costs
- Operation: rental metrics, occupancy rates
- Opex: property management, insurance, maintenance, utilities

**Cash Flow** - Generate and analyze cashflow projections:
- Latest calculations
- Historical runs
- Detailed period-by-period breakdowns

## Setup

```bash
composer install
npm install
php artisan key:generate
php artisan migrate
npm run dev
```

## API Documentation

See `cashflow engine.postman_collection.json` for complete API endpoints.

Refer to `cashflow engine schema diagram.pdf` for architecture details.
