# eCommerce CMS System

*A robust Laravel 11 eCommerce + CMS platform with web & API interfaces, Sanctum‑secured authentication, clean architecture, and test coverage.*

> **Repo:** [https://github.com/MarwanMSalem/e-comm](https://github.com/MarwanMSalem/e-comm)

---

## Table of Contents

* [Overview](#overview)
* [Features](#features)
* [Architecture](#architecture)
* [Technologies](#technologies)
* [Project Structure](#project-structure)
* [Setup Instructions](#setup-instructions)
* [Usage](#usage)

  * [Web Interface](#web-interface)
  * [API Usage](#api-usage)
* [Design Decisions](#design-decisions)
* [Contributing](#contributing)
* [License](#license)

---

## Overview

The **eCommerce CMS System** is a demonstration‑grade yet production‑ready Laravel 11 application that powers an online store **and** exposes the same capabilities over a versioned REST API. It was built to showcase **high‑quality code**, real‑world **business logic alignment**, and thoughtful **architecture decisions** you can extend in larger projects.

### Project Goals

* Deliver a working eCommerce back office (CMS) + storefront skeleton.
* Expose the same capabilities over a versioned REST API.
* Demonstrate **SOLID** design using the **Repository pattern** & service layer.
* Implement lightweight **role‑based access control** via a `role` enum (`admin`, `user`, `employee`).
* Share common domain logic between Web & API controllers.
* Support **API versioning** to allow future changes without breaking clients.
* Use **DB transactions** to keep product stock correct when orders are placed/updated/deleted.
* Ship with **seed data** so the app runs immediately.
* Include **unit & feature tests** for core flows.

---

## Features

| Area                            | Capabilities                                                                        | Notes                                                             |
| ------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------- |
| **User Authentication & Roles** | Registration, login, Sanctum token auth, role column (`admin`, `user`, `employee`). | Easily swap to Spatie Permissions for granular roles/permissions. |
| **Product Management**          | CRUD (create, read, update, delete); price, category, quantity, description.        | Admin‑only write ops by default.                                  |
| **Order Management**            | Place orders, assign employees, update status (`pending`, `shipped`, `delivered`).  | Uses transactions to protect stock counts.                        |
| **Web UI**                      | Blade + Bootstrap responsive screens.                                               | Admin dashboards; user order history.                             |
| **REST API**                    | Versioned (`/api/v1/...`) + Sanctum‑secured.                                        | Mobile / SPA ready.                                               |
| **Scalable Architecture**       | Repository pattern, shared service layer, API + Web parity.                         | SOLID‑aligned organization.                                       |

---

## Architecture

The system is organized to encourage **separation of concerns**, **testability**, and **future growth**.

### SOLID & Clean Code

* **Single Responsibility Principle (SRP):** Repositories encapsulate persistence; controllers remain thin.
* **Open/Closed Principle:** Service interfaces allow new implementations without rewriting callers.
* **Liskov Substitution:** Contracts & type‑hinted abstractions simplify swapping mock vs. DB repos in tests.
* **Interface Segregation:** Role‑based capabilities exposed in focused services (e.g., ProductCatalogService vs. OrderFulfillmentService).
* **Dependency Inversion:** Controllers depend on interfaces; bindings resolved in service providers.

### Common Modules Layer

Shared domain operations (validation, DTO transforms, stock adjustments, assignment rules) live in a **Common** or **Domain** namespace consumed by both API and Web controllers—ensuring **consistent behavior** no matter the entry point.

### API Versioning Strategy

All API routes live under `/api/v1/` (e.g., `/api/v1/products`). Future breaking changes roll into `/api/v2/` without disrupting existing clients.

> \[!TIP] Use route groups in `routes/api_v1.php`, `routes/api_v2.php`, etc., and register them in `bootstrap/app.php` for clean multi‑version support.

---

## Technologies

**Backend**: Laravel 11 • PHP 8.2+ • Laravel Sanctum (API tokens)
**Frontend**: Blade templates • Bootstrap responsive UI
**Database**: MySQL (default) — works with SQLite/PostgreSQL/etc.
**Testing**: PHPUnit Feature & Unit tests included (auth, product CRUD, order flows).
**Auth Modes**: Sanctum personal access tokens (API) + session/cookie (web).
**Patterns**: Repository + Service layers; transaction‑safe order operations.

---

## Setup Instructions

### Prerequisites

* PHP **8.2+**
* Composer 2+
* MySQL (or other Laravel-supported DB) *or* SQLite for quick tests
* Node.js + npm/yarn (if you will compile frontend assets)

### Clone the Repo

```bash
git clone https://github.com/MarwanMSalem/e-comm.git
cd e-comm
```

### Install Backend Dependencies

```bash
composer install
```

```bash
```

### Environment Setup

```bash
cp .env.example .env   # Windows: copy .env.example .env
php artisan key:generate
```

Edit `.env` with DB credentials and app URL.

### Migrate & Seed

```bash
php artisan migrate --seed
# Dev rebuild shortcut (drops & recreates DB):
php artisan migrate:fresh --seed
```

**Seeds create:** 1 admin, 2 employees, 3 sample users (password: `123456789`), 15 demo products, and 1 sample order.

### Sanctum API Install (only if you skipped `install:api` earlier)

```bash
php artisan install:api --force
```

### Run the Development Server

```bash
php artisan serve
```

Visit: [http://localhost:8000](http://localhost:8000)

---

## Usage

### Web Interface

The Blade + Bootstrap UI provides a clean admin & storefront experience.

**Auth Screens** – Login / Register / Forgot Password.
**Dashboard** – Quick stats; links to Products, Orders, Users.
**Products** – List, create, edit, delete (admins); view (all users). Quantity & price inline.
**Orders** – Users: view own orders. Admins: view all, assign employees, change status.
**Users** – Admin‑only: view accounts, change roles (`admin`, `user`, `employee`).

> Need screenshots? Add them in `/public/images/readme/` then embed below.

## API Usage

A complete Postman collection ships with the repo in `postman/` (or see the release attachments). Import it into Postman or Bruno to explore all authenticated and public endpoints.

**Quick steps:**

1. Run the app and seed the DB (`php artisan migrate --seed`).
2. Open the Postman collection.
3. Use the *Login* request to obtain a Sanctum token (demo credentials seeded: `admin@example.com`, `user1@example.com`, `employee1@example.com`; password `123456789`).
4. Click **"Collection Variables"** and paste the token into the `auth_token` variable; requests will auto‑inject the `Authorization: Bearer` header.

> Endpoints are documented in the collection—kept here instead of the README so docs never drift out of sync.

---

## Design Decisions

### Simple Role Column vs. Spatie

This starter uses a **simple enum column** on `users.role` (`admin`, `user`, `employee`) for lightweight role gating in middleware, and views. For large projects needing many roles and granular permissions, swap in **\[spatie/laravel-permission]**. Migration path: add `roles` + `model_has_roles` tables, map existing enum values to seeded roles, phase out the enum.

### Transaction Boundaries for Orders

When an order is placed / updated / deleted we wrap operations in a DB **transaction**:

1. Validate order data & stock.
2. Adjust product quantity (+/‑).
3. Create or update order row.
4. Commit.

This prevents overselling during concurrent requests without the complexity of Redis‑based pessimistic locking—appropriate for CRUD‑scale workloads. Upgrade to row‑level locking or distributed locks if concurrency demands increase.

### Common Modules (Shared Domain Logic)

Reusable domain actions are used by both Web & API controllers so behavior stays consistent and test coverage applies everywhere.

---

## License

This project is open‑sourced software licensed under the **MIT license**.
