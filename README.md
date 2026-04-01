# Accounting Software

A full-featured double-entry accounting application built with **Laravel 10**, **AdminLTE 3**, and **Spatie Laravel Permission**. Manage your chart of accounts, journal entries, invoices, payments, expenses, and generate financial reports — all from a clean, responsive dashboard.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
  - [Dashboard](#dashboard)
  - [Chart of Accounts](#chart-of-accounts)
  - [Journal Entries](#journal-entries)
  - [Customers & Suppliers](#customers--suppliers)
  - [Invoices (Sales & Purchase)](#invoices-sales--purchase)
  - [Payments](#payments)
  - [Expenses](#expenses)
  - [Reports](#reports)
  - [PDF Export](#pdf-export)
  - [User Management & Roles](#user-management--roles)
- [API Reference](#api-reference)
- [Database Schema](#database-schema)
- [License](#license)

---

## Features

- **Double-Entry Bookkeeping** — Every transaction records balanced debits and credits
- **Chart of Accounts** — Hierarchical account groups (Asset, Liability, Equity, Income, Expense)
- **Journal Entries** — Multiple voucher types: Journal, Receipt, Payment, Contra, Sales, Purchase
- **Sales & Purchase Invoices** — Line-item invoices with tax, discount, and payment tracking
- **Customer & Supplier Management** — Contact details, opening balances, and due tracking
- **Payments** — Record receipts from customers and payments to suppliers
- **Expense Tracking** — Categorize and record business expenses
- **Financial Reports** — Trial Balance, Income Statement, Balance Sheet
- **PDF Export** — Generate PDFs for invoices, journal entries, reports, and lists (Bengali font support)
- **Role-Based Access Control** — Manage users, roles, and granular permissions
- **Responsive Dashboard** — Real-time KPIs, charts, recent activity, and overdue alerts
- **Email Verification** — Secure user registration with email verification

---

## Tech Stack

| Layer        | Technology                                    |
| ------------ | --------------------------------------------- |
| Backend      | Laravel 10, PHP 8.1+                          |
| Frontend     | Blade, AdminLTE 3, Bootstrap 4, Alpine.js     |
| Database     | MySQL / MariaDB                               |
| Auth         | Laravel Breeze, Laravel Sanctum               |
| RBAC         | Spatie Laravel Permission                      |
| PDF          | mPDF 8.3, TCPDF 6.11                          |
| Build Tool   | Vite 5                                         |

---

## Requirements

- PHP >= 8.1
- Composer
- MySQL 5.7+ or MariaDB
- Node.js & npm
- Apache or Nginx (with URL rewriting enabled)

---

## Installation

```bash
# 1. Clone the repository
git clone <repository-url> accounting-software
cd accounting-software

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create a MySQL database
mysql -u root -p -e "CREATE DATABASE accounting_db"

# 6. Configure your .env file (see Configuration section)

# 7. Run database migrations
php artisan migrate

# 8. Install frontend dependencies and build assets
npm install
npm run build

# 9. Start the development server
php artisan serve
```

The application will be available at `http://localhost:8000`.

> **WAMP/XAMPP Users:** Point your virtual host to the `public/` directory and access via `http://localhost/accounting-software/public`.

---

## Configuration

Edit the `.env` file with your settings:

```env
APP_NAME="Accounting Software"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=accounting_db
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

---

## Usage Guide

### Dashboard

After logging in, the dashboard (`/dashboard`) displays:

- **Cash in Hand & Cash at Bank** — Current balances from accounts 1001 and 1002
- **Total Receivable / Payable** — Outstanding customer and supplier balances
- **Income vs Expense** — Monthly comparison for the last 6 months
- **Recent Transactions** — Latest invoices, payments, and expenses
- **Overdue Invoices** — Invoices past their due date
- **Invoice Status Breakdown** — Draft, Sent, Paid, Partial, Overdue, Cancelled
- **Top Expense Categories** — Highest spending areas

---

### Chart of Accounts

Manage your general ledger accounts at `/accounts`.

| Action          | Route                       | Description                            |
| --------------- | --------------------------- | -------------------------------------- |
| List Accounts   | `GET /accounts`             | View all accounts with balances        |
| Create Account  | `GET /accounts/create`      | Add a new account                      |
| Edit Account    | `GET /accounts/{id}/edit`   | Modify account details                 |
| Delete Account  | `DELETE /accounts/{id}`     | Remove an account                      |
| View Ledger     | `GET /accounts/{id}/ledger` | See all transactions for an account    |

**Account Fields:**
- **Code** — Unique account code (e.g., 1001 for Cash)
- **Name** — Account name
- **Type** — Asset, Liability, Equity, Income, or Expense
- **Group** — Parent account group for hierarchy
- **Opening Balance** — Starting balance
- **Status** — Active or Inactive

---

### Journal Entries

Create double-entry journal transactions at `/journals`.

| Action         | Route                    | Description                          |
| -------------- | ------------------------ | ------------------------------------ |
| List Entries   | `GET /journals`          | View all journal entries             |
| Create Entry   | `GET /journals/create`   | Create a new journal entry           |
| View Entry     | `GET /journals/{id}`     | View entry details with line items   |
| Delete Entry   | `DELETE /journals/{id}`  | Remove a journal entry               |

**Voucher Types & Prefixes:**

| Type     | Prefix |
| -------- | ------ |
| Journal  | JOR-   |
| Receipt  | REC-   |
| Payment  | PAY-   |
| Contra   | CON-   |
| Sales    | SAL-   |
| Purchase | PUR-   |

> Voucher numbers are auto-generated. Debits must equal credits — the system validates this before saving.

---

### Customers & Suppliers

Manage business contacts at `/customers` and `/suppliers`.

| Action  | Customers Route              | Suppliers Route              |
| ------- | ---------------------------- | ---------------------------- |
| List    | `GET /customers`             | `GET /suppliers`             |
| Create  | `GET /customers/create`      | `GET /suppliers/create`      |
| Edit    | `GET /customers/{id}/edit`   | `GET /suppliers/{id}/edit`   |
| Delete  | `DELETE /customers/{id}`     | `DELETE /suppliers/{id}`     |

**Fields:** Name, Email, Phone, Address, Opening Balance, Status (Active/Inactive)

The system automatically calculates **Total Due** for each customer and **Total Payable** for each supplier based on linked invoices and payments.

---

### Invoices (Sales & Purchase)

Create and manage invoices at `/invoices`.

| Action           | Route                                    | Description                |
| ---------------- | ---------------------------------------- | -------------------------- |
| Sales List       | `GET /invoices?type=sales`               | All sales invoices         |
| Purchase List    | `GET /invoices?type=purchase`            | All purchase bills         |
| Create Sales     | `GET /invoices/create?type=sales`        | New sales invoice          |
| Create Purchase  | `GET /invoices/create?type=purchase`     | New purchase bill          |
| View Invoice     | `GET /invoices/{id}`                     | Invoice details            |
| Delete Invoice   | `DELETE /invoices/{id}`                  | Remove invoice             |

**Invoice Features:**
- Auto-generated numbers: `INV-` (sales), `BILL-` (purchase)
- Line items with quantity, unit price, and amount
- Tax and discount calculation
- Due date tracking
- Status management: Draft, Sent, Paid, Partial, Overdue, Cancelled
- Automatic paid/due amount tracking

---

### Payments

Record money received and paid at `/payments`.

| Action           | Route                                    | Description                  |
| ---------------- | ---------------------------------------- | ---------------------------- |
| Received List    | `GET /payments?type=received`            | Payments from customers      |
| Made List        | `GET /payments?type=made`                | Payments to suppliers        |
| Create Received  | `GET /payments/create?type=received`     | Record customer payment      |
| Create Made      | `GET /payments/create?type=made`         | Record supplier payment      |
| Delete Payment   | `DELETE /payments/{id}`                  | Remove payment               |

**Payment Fields:**
- Auto-generated number: `RCV-` (received), `PAY-` (made)
- Link to customer/supplier and optionally to an invoice
- Payment method (Cash, Cheque, Bank Transfer, etc.)
- Reference number and notes

---

### Expenses

Track business expenses at `/expenses`.

| Action  | Route                      | Description              |
| ------- | -------------------------- | ------------------------ |
| List    | `GET /expenses`            | View all expenses        |
| Create  | `GET /expenses/create`     | Record a new expense     |
| Edit    | `GET /expenses/{id}/edit`  | Modify expense           |
| Delete  | `DELETE /expenses/{id}`    | Remove expense           |

**Expense Fields:**
- Auto-generated number: `EXP-`
- Expense account (linked to chart of accounts)
- Optional supplier link
- Category, payment method, reference, and description

---

### Reports

Generate financial reports at `/reports`.

| Report            | Route                                                  | Parameters    |
| ----------------- | ------------------------------------------------------ | ------------- |
| Trial Balance     | `GET /reports/trial-balance?date=YYYY-MM-DD`           | Date          |
| Income Statement  | `GET /reports/income-statement?start_date=&end_date=`  | Date range    |
| Balance Sheet     | `GET /reports/balance-sheet?start_date=&end_date=`     | Date range    |

- **Trial Balance** — Lists all accounts with their debit and credit balances as of a given date
- **Income Statement** — Shows revenue and expenses over a period to calculate net profit/loss
- **Balance Sheet** — Snapshot of assets, liabilities, and equity at a point in time

---

### PDF Export

Generate PDF documents for printing or sharing.

| Document           | Route                             |
| ------------------ | --------------------------------- |
| Invoice            | `GET /pdf/invoice/{id}`           |
| Journal Entry      | `GET /pdf/journal/{id}`           |
| Customers List     | `GET /pdf/customers`              |
| Suppliers List     | `GET /pdf/suppliers`              |
| Expenses List      | `GET /pdf/expenses`               |
| Trial Balance      | `GET /pdf/trial-balance`          |
| Income Statement   | `GET /pdf/income-statement`       |
| Balance Sheet      | `GET /pdf/balance-sheet`          |

> PDFs support Bengali text rendering via the SolaimanLipi font.

---

### User Management & Roles

Manage users, roles, and permissions under `/settings` (requires appropriate permissions).

#### Users (`/settings/users`)
- Create, edit, and delete user accounts
- Assign roles to users
- Email verification required for new accounts

#### Roles (`/settings/roles`)
- Create custom roles (e.g., Admin, Accountant, Viewer)
- Assign permissions to each role

#### Permissions (`/settings/permissions`)
- Granular permission system
- Permission naming pattern: `module.action.resource`

**Available Permission Groups:**

| Module                 | Permissions                     |
| ---------------------- | ------------------------------- |
| `settings.users`       | view, create, edit, delete      |
| `settings.roles`       | view, create, edit, delete      |
| `settings.permissions` | view, create, edit, delete      |

---

## API Reference

The application includes a Sanctum-based API endpoint:

```
GET /api/user
```

Returns the authenticated user's details. Requires a valid Sanctum token in the `Authorization: Bearer <token>` header.

All other functionality is accessed through the web routes listed above.

---

## Database Schema

| Table                  | Description                                |
| ---------------------- | ------------------------------------------ |
| `users`                | Application users                          |
| `accounts`             | Chart of accounts                          |
| `account_groups`       | Account group hierarchy                    |
| `customers`            | Customer records                           |
| `suppliers`            | Supplier records                           |
| `journal_entries`      | Journal entry headers                      |
| `journal_entry_items`  | Journal entry line items (debit/credit)    |
| `invoices`             | Sales and purchase invoices                |
| `invoice_items`        | Invoice line items                         |
| `payments`             | Payment transactions                       |
| `expenses`             | Expense records                            |
| `roles`                | Role definitions (Spatie)                  |
| `permissions`          | Permission definitions (Spatie)            |
| `model_has_roles`      | User-role assignments                      |
| `model_has_permissions`| Direct user-permission assignments         |
| `role_has_permissions` | Role-permission mapping                    |

---

## License

This project is open-sourced software licensed under the [MIT License](LICENSE).
