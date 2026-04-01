<p align="center">
  <img src="https://img.icons8.com/3d-fluency/94/accounting.png" alt="Accounting Software Logo" width="80"/>
</p>

<h1 align="center">💰 Accounting Software</h1>

<p align="center">
  A full-featured double-entry accounting application built with <strong>Laravel 10</strong>, <strong>AdminLTE 3</strong>, and <strong>Spatie Laravel Permission</strong>.
  <br/>
  Manage your chart of accounts, journal entries, invoices, payments, expenses, and generate financial reports — all from a clean, responsive dashboard.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10"/>
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/AdminLTE-3-007bff?style=for-the-badge" alt="AdminLTE 3"/>
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License"/>
</p>

---

## 📑 Table of Contents

- [✨ Features](#-features)
- [🛠️ Tech Stack](#️-tech-stack)
- [📋 Requirements](#-requirements)
- [🚀 Installation](#-installation)
- [⚙️ Configuration](#️-configuration)
- [📖 Usage Guide](#-usage-guide)
  - [📊 Dashboard](#-dashboard)
  - [📒 Chart of Accounts](#-chart-of-accounts)
  - [📝 Journal Entries](#-journal-entries)
  - [👥 Customers & Suppliers](#-customers--suppliers)
  - [🧾 Invoices](#-invoices-sales--purchase)
  - [💳 Payments](#-payments)
  - [💸 Expenses](#-expenses)
  - [📈 Reports](#-reports)
  - [🖨️ PDF Export](#️-pdf-export)
  - [🔐 User Management & Roles](#-user-management--roles)
- [🔌 API Reference](#-api-reference)
- [🗄️ Database Schema](#️-database-schema)
- [📄 License](#-license)

---

## ✨ Features

| Feature | Description |
|---------|-------------|
| 📚 **Double-Entry Bookkeeping** | Every transaction records balanced debits and credits |
| 📒 **Chart of Accounts** | Hierarchical account groups — Asset, Liability, Equity, Income, Expense |
| 📝 **Journal Entries** | Multiple voucher types: Journal, Receipt, Payment, Contra, Sales, Purchase |
| 🧾 **Sales & Purchase Invoices** | Line-item invoices with tax, discount, and payment tracking |
| 👥 **Customer & Supplier Management** | Contact details, opening balances, and due tracking |
| 💳 **Payments** | Record receipts from customers and payments to suppliers |
| 💸 **Expense Tracking** | Categorize and record business expenses |
| 📈 **Financial Reports** | Trial Balance, Income Statement, Balance Sheet |
| 🖨️ **PDF Export** | Generate PDFs for invoices, reports, and lists (Bengali font support) |
| 🔐 **Role-Based Access Control** | Manage users, roles, and granular permissions |
| 📊 **Responsive Dashboard** | Real-time KPIs, charts, recent activity, and overdue alerts |
| ✉️ **Email Verification** | Secure user registration with email verification |

---

## 🛠️ Tech Stack

| Layer | Technology | Badge |
|-------|-----------|-------|
| 🔧 Backend | Laravel 10, PHP 8.1+ | ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white) ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) |
| 🎨 Frontend | Blade, AdminLTE 3, Bootstrap 4, Alpine.js | ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=flat-square&logo=bootstrap&logoColor=white) ![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=flat-square&logo=alpine.js&logoColor=black) |
| 🗄️ Database | MySQL / MariaDB | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) |
| 🔒 Auth | Laravel Breeze, Laravel Sanctum | ![Sanctum](https://img.shields.io/badge/Sanctum-FF2D20?style=flat-square&logo=laravel&logoColor=white) |
| 🛡️ RBAC | Spatie Laravel Permission | ![Spatie](https://img.shields.io/badge/Spatie-197593?style=flat-square) |
| 📄 PDF | mPDF 8.3, TCPDF 6.11 | ![PDF](https://img.shields.io/badge/mPDF-CC0000?style=flat-square) |
| ⚡ Build Tool | Vite 5 | ![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat-square&logo=vite&logoColor=white) |

---

## 📋 Requirements

| Requirement | Version |
|-------------|---------|
| 🐘 PHP | >= 8.1 |
| 📦 Composer | Latest |
| 🐬 MySQL | 5.7+ or MariaDB |
| 💚 Node.js | Latest LTS |
| 📦 npm | Latest |
| 🌐 Web Server | Apache or Nginx (URL rewriting enabled) |

---

## 🚀 Installation

```bash
# 1️⃣ Clone the repository
git clone <repository-url> accounting-software
cd accounting-software

# 2️⃣ Install PHP dependencies
composer install

# 3️⃣ Copy environment file
cp .env.example .env

# 4️⃣ Generate application key
php artisan key:generate

# 5️⃣ Create a MySQL database
mysql -u root -p -e "CREATE DATABASE accounting_db"

# 6️⃣ Configure your .env file (see Configuration section)

# 7️⃣ Run database migrations
php artisan migrate

# 8️⃣ Install frontend dependencies and build assets
npm install
npm run build

# 9️⃣ Start the development server
php artisan serve
```

🌐 The application will be available at `http://localhost:8000`

> 💡 **WAMP/XAMPP Users:** Point your virtual host to the `public/` directory and access via `http://localhost/accounting-software/public`.

---

## ⚙️ Configuration

Edit the `.env` file with your settings:

```env
APP_NAME="Accounting Software"
APP_URL=http://localhost:8000

# 🗄️ Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=accounting_db
DB_USERNAME=root
DB_PASSWORD=your_password

# ✉️ Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

---

## 📖 Usage Guide

### 📊 Dashboard

After logging in, the dashboard (`/dashboard`) provides a complete overview:

<table>
  <tr>
    <td>💵 <strong>Cash in Hand & Bank</strong></td>
    <td>Current balances from accounts 1001 and 1002</td>
  </tr>
  <tr>
    <td>📥 <strong>Total Receivable</strong></td>
    <td>Outstanding customer balances</td>
  </tr>
  <tr>
    <td>📤 <strong>Total Payable</strong></td>
    <td>Outstanding supplier balances</td>
  </tr>
  <tr>
    <td>📊 <strong>Income vs Expense</strong></td>
    <td>Monthly comparison for the last 6 months</td>
  </tr>
  <tr>
    <td>🕐 <strong>Recent Transactions</strong></td>
    <td>Latest invoices, payments, and expenses</td>
  </tr>
  <tr>
    <td>⚠️ <strong>Overdue Invoices</strong></td>
    <td>Invoices past their due date</td>
  </tr>
  <tr>
    <td>📋 <strong>Invoice Status</strong></td>
    <td>Draft, Sent, Paid, Partial, Overdue, Cancelled</td>
  </tr>
  <tr>
    <td>🏷️ <strong>Top Expenses</strong></td>
    <td>Highest spending categories</td>
  </tr>
</table>

---

### 📒 Chart of Accounts

> Manage your general ledger accounts at `/accounts`

| Action | Route | Description |
|--------|-------|-------------|
| 📋 List | `GET /accounts` | View all accounts with balances |
| ➕ Create | `GET /accounts/create` | Add a new account |
| ✏️ Edit | `GET /accounts/{id}/edit` | Modify account details |
| 🗑️ Delete | `DELETE /accounts/{id}` | Remove an account |
| 📖 Ledger | `GET /accounts/{id}/ledger` | See all transactions for an account |

**📌 Account Fields:**

| Field | Description |
|-------|-------------|
| 🔢 Code | Unique account code (e.g., `1001` for Cash) |
| 📛 Name | Account name |
| 📂 Type | Asset, Liability, Equity, Income, or Expense |
| 🗂️ Group | Parent account group for hierarchy |
| 💰 Opening Balance | Starting balance |
| 🔘 Status | Active or Inactive |

---

### 📝 Journal Entries

> Create double-entry journal transactions at `/journals`

| Action | Route | Description |
|--------|-------|-------------|
| 📋 List | `GET /journals` | View all journal entries |
| ➕ Create | `GET /journals/create` | Create a new journal entry |
| 👁️ View | `GET /journals/{id}` | View entry details with line items |
| 🗑️ Delete | `DELETE /journals/{id}` | Remove a journal entry |

**🏷️ Voucher Types & Auto-Generated Prefixes:**

| Type | Prefix | Icon |
|------|--------|------|
| Journal | `JOR-` | 📓 |
| Receipt | `REC-` | 📥 |
| Payment | `PAY-` | 📤 |
| Contra | `CON-` | 🔄 |
| Sales | `SAL-` | 🛒 |
| Purchase | `PUR-` | 📦 |

> ⚠️ Voucher numbers are auto-generated. **Debits must equal credits** — the system validates this before saving.

---

### 👥 Customers & Suppliers

> Manage business contacts at `/customers` and `/suppliers`

| Action | 👤 Customers Route | 🏭 Suppliers Route |
|--------|-------------------|-------------------|
| 📋 List | `GET /customers` | `GET /suppliers` |
| ➕ Create | `GET /customers/create` | `GET /suppliers/create` |
| ✏️ Edit | `GET /customers/{id}/edit` | `GET /suppliers/{id}/edit` |
| 🗑️ Delete | `DELETE /customers/{id}` | `DELETE /suppliers/{id}` |

**📌 Fields:** Name, Email, Phone, Address, Opening Balance, Status (Active/Inactive)

> 🔄 The system automatically calculates **Total Due** for each customer and **Total Payable** for each supplier based on linked invoices and payments.

---

### 🧾 Invoices (Sales & Purchase)

> Create and manage invoices at `/invoices`

| Action | Route | Description |
|--------|-------|-------------|
| 📋 Sales List | `GET /invoices?type=sales` | All sales invoices |
| 📋 Purchase List | `GET /invoices?type=purchase` | All purchase bills |
| ➕ Create Sales | `GET /invoices/create?type=sales` | New sales invoice |
| ➕ Create Purchase | `GET /invoices/create?type=purchase` | New purchase bill |
| 👁️ View | `GET /invoices/{id}` | Invoice details |
| 🗑️ Delete | `DELETE /invoices/{id}` | Remove invoice |

**✅ Invoice Features:**
- 🔢 Auto-generated numbers: `INV-` (sales), `BILL-` (purchase)
- 📝 Line items with quantity, unit price, and amount
- 🧮 Tax and discount calculation
- 📅 Due date tracking
- 🔄 Status flow: `Draft` → `Sent` → `Paid` / `Partial` / `Overdue` / `Cancelled`
- 💰 Automatic paid/due amount tracking

---

### 💳 Payments

> Record money received and paid at `/payments`

| Action | Route | Description |
|--------|-------|-------------|
| 📥 Received List | `GET /payments?type=received` | Payments from customers |
| 📤 Made List | `GET /payments?type=made` | Payments to suppliers |
| ➕ Create Received | `GET /payments/create?type=received` | Record customer payment |
| ➕ Create Made | `GET /payments/create?type=made` | Record supplier payment |
| 🗑️ Delete | `DELETE /payments/{id}` | Remove payment |

**📌 Payment Fields:**
- 🔢 Auto-generated number: `RCV-` (received), `PAY-` (made)
- 🔗 Link to customer/supplier and optionally to an invoice
- 💳 Payment method (Cash, Cheque, Bank Transfer, etc.)
- 📎 Reference number and notes

---

### 💸 Expenses

> Track business expenses at `/expenses`

| Action | Route | Description |
|--------|-------|-------------|
| 📋 List | `GET /expenses` | View all expenses |
| ➕ Create | `GET /expenses/create` | Record a new expense |
| ✏️ Edit | `GET /expenses/{id}/edit` | Modify expense |
| 🗑️ Delete | `DELETE /expenses/{id}` | Remove expense |

**📌 Expense Fields:**
- 🔢 Auto-generated number: `EXP-`
- 📒 Expense account (linked to chart of accounts)
- 🏭 Optional supplier link
- 🏷️ Category, payment method, reference, and description

---

### 📈 Reports

> Generate financial reports at `/reports`

| Report | Route | Parameters |
|--------|-------|-----------|
| ⚖️ Trial Balance | `GET /reports/trial-balance?date=YYYY-MM-DD` | 📅 Date |
| 📊 Income Statement | `GET /reports/income-statement?start_date=&end_date=` | 📅 Date range |
| 🏦 Balance Sheet | `GET /reports/balance-sheet?start_date=&end_date=` | 📅 Date range |

<details>
<summary>📖 Report Descriptions</summary>

- ⚖️ **Trial Balance** — Lists all accounts with their debit and credit balances as of a given date
- 📊 **Income Statement** — Shows revenue and expenses over a period to calculate net profit/loss
- 🏦 **Balance Sheet** — Snapshot of assets, liabilities, and equity at a point in time

</details>

---

### 🖨️ PDF Export

> Generate PDF documents for printing or sharing

| Document | Route | Icon |
|----------|-------|------|
| Invoice | `GET /pdf/invoice/{id}` | 🧾 |
| Journal Entry | `GET /pdf/journal/{id}` | 📝 |
| Customers List | `GET /pdf/customers` | 👥 |
| Suppliers List | `GET /pdf/suppliers` | 🏭 |
| Expenses List | `GET /pdf/expenses` | 💸 |
| Trial Balance | `GET /pdf/trial-balance` | ⚖️ |
| Income Statement | `GET /pdf/income-statement` | 📊 |
| Balance Sheet | `GET /pdf/balance-sheet` | 🏦 |

> 🇧🇩 PDFs support **Bengali text rendering** via the SolaimanLipi font.

---

### 🔐 User Management & Roles

> Manage users, roles, and permissions under `/settings` (requires appropriate permissions)

#### 👤 Users (`/settings/users`)
- ➕ Create, ✏️ edit, and 🗑️ delete user accounts
- 🏷️ Assign roles to users
- ✉️ Email verification required for new accounts

#### 🛡️ Roles (`/settings/roles`)
- ➕ Create custom roles (e.g., Admin, Accountant, Viewer)
- 🔗 Assign permissions to each role

#### 🔑 Permissions (`/settings/permissions`)
- 🎯 Granular permission system
- 📛 Permission naming pattern: `module.action.resource`

**📌 Available Permission Groups:**

| Module | Permissions |
|--------|------------|
| 👤 `settings.users` | `view` · `create` · `edit` · `delete` |
| 🛡️ `settings.roles` | `view` · `create` · `edit` · `delete` |
| 🔑 `settings.permissions` | `view` · `create` · `edit` · `delete` |

---

## 🔌 API Reference

The application includes a Sanctum-based API endpoint:

```http
GET /api/user
Authorization: Bearer <token>
```

> 🔐 Returns the authenticated user's details. Requires a valid **Sanctum token**.

All other functionality is accessed through the web routes listed above.

---

## 🗄️ Database Schema

```
┌─────────────────────┐     ┌──────────────────────┐     ┌─────────────────┐
│      users          │     │   journal_entries     │     │    accounts     │
│  (Authentication)   │────▶│  (Vouchers/Entries)   │◀────│  (Chart of A/C) │
└─────────────────────┘     └──────────────────────┘     └─────────────────┘
         │                           │                           │
         │                  ┌────────┴────────┐         ┌───────┴───────┐
         │                  │ journal_entry_   │         │ account_      │
         │                  │ items (Dr/Cr)    │         │ groups        │
         │                  └─────────────────┘         └───────────────┘
         │
    ┌────┴─────┐     ┌──────────────┐     ┌──────────────┐
    │ invoices │────▶│ invoice_items│     │  payments    │
    └────┬─────┘     └──────────────┘     └──────┬───────┘
         │                                        │
    ┌────┴─────┐     ┌──────────────┐            │
    │customers │     │  suppliers   │◀───────────┘
    └──────────┘     └──────┬───────┘
                            │
                     ┌──────┴───────┐
                     │   expenses   │
                     └──────────────┘
```

| Table | Description | Icon |
|-------|-------------|------|
| `users` | Application users | 👤 |
| `accounts` | Chart of accounts | 📒 |
| `account_groups` | Account group hierarchy | 🗂️ |
| `customers` | Customer records | 👥 |
| `suppliers` | Supplier records | 🏭 |
| `journal_entries` | Journal entry headers | 📝 |
| `journal_entry_items` | Journal entry line items (debit/credit) | 📄 |
| `invoices` | Sales and purchase invoices | 🧾 |
| `invoice_items` | Invoice line items | 📋 |
| `payments` | Payment transactions | 💳 |
| `expenses` | Expense records | 💸 |
| `roles` | Role definitions (Spatie) | 🛡️ |
| `permissions` | Permission definitions (Spatie) | 🔑 |
| `model_has_roles` | User-role assignments | 🔗 |
| `model_has_permissions` | Direct user-permission assignments | 🔗 |
| `role_has_permissions` | Role-permission mapping | 🔗 |

---

<p align="center">
  📄 This project is open-sourced software licensed under the <a href="LICENSE">MIT License</a>.
</p>

<p align="center">
  Made with ❤️ using Laravel
</p>
