<p align="center">
  <img src="https://img.icons8.com/3d-fluency/94/accounting.png" alt="Accounting Software Logo" width="80"/>
</p>

<h1 align="center">💰 অ্যাকাউন্টিং সফটওয়্যার</h1>

<p align="center">
  একটি পূর্ণাঙ্গ <strong>ডাবল-এন্ট্রি অ্যাকাউন্টিং + ইনভেন্টরি + ইনভয়েসিং</strong> অ্যাপ্লিকেশন যা <strong>Laravel 10</strong>, <strong>AdminLTE 3</strong> এবং <strong>Spatie Laravel Permission</strong> দিয়ে তৈরি।
  <br/>
  Small & Medium Business (SME)-এর জন্য উপযুক্ত — Quotation থেকে শুরু করে Invoice, Stock, Return, Bank Reconciliation, Reports, PDF, Email, WhatsApp — সবকিছু এক জায়গায়।
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10"/>
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+"/>
  <img src="https://img.shields.io/badge/MySQL%20%2F%20SQLite-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="Database"/>
  <img src="https://img.shields.io/badge/AdminLTE-3-007bff?style=for-the-badge" alt="AdminLTE 3"/>
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License"/>
  <img src="https://img.shields.io/badge/i18n-EN%2FBN-ff69b4?style=for-the-badge" alt="Bilingual"/>
  <img src="https://img.shields.io/badge/2FA-enabled-00b894?style=for-the-badge" alt="2FA"/>
</p>

---

## 📑 সূচিপত্র

- [✨ বৈশিষ্ট্যসমূহ](#-বৈশিষ্ট্যসমূহ)
- [🏗️ Architecture](#️-architecture)
- [🛠️ প্রযুক্তি](#️-প্রযুক্তি)
- [📋 প্রয়োজনীয়তা](#-প্রয়োজনীয়তা)
- [🚀 ইনস্টলেশন](#-ইনস্টলেশন)
- [⚙️ কনফিগারেশন](#️-কনফিগারেশন)
- [🔑 ডিফল্ট লগইন](#-ডিফল্ট-লগইন)
- [📖 মডিউলসমূহ](#-মডিউলসমূহ)
- [⏰ Scheduler & Console Commands](#-scheduler--console-commands)
- [🗄️ ডাটাবেস স্কিমা](#️-ডাটাবেস-স্কিমা)
- [🌐 Bilingual সাপোর্ট](#-bilingual-সাপোর্ট)
- [🎨 UI ফিচার](#-ui-ফিচার)
- [📧 ইমেইল সেটআপ](#-ইমেইল-সেটআপ)
- [💬 WhatsApp Integration](#-whatsapp-integration)
- [🔐 Two-Factor Authentication](#-two-factor-authentication)
- [📂 Directory Structure](#-directory-structure)
- [📄 লাইসেন্স](#-লাইসেন্স)

---

## ✨ বৈশিষ্ট্যসমূহ

### 📚 Core Accounting
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 📒 **Chart of Accounts** | অ্যাকাউন্ট + হায়ারার্কিক্যাল Account Groups |
| 📝 **Journal Entries** | ডাবল-এন্ট্রি, ৬টি voucher type (Journal, Receipt, Payment, Contra, Sales, Purchase) |
| ⚡ **Auto Journal Posting** | Invoice/Payment/Expense/Credit-Debit Note save হলে **automatically balanced journal entry create হয়** (GL posting) |
| 👥 **Customers & Suppliers** | Opening balance, দায়/প্রাপ্য auto-calculate |
| 🧾 **Sales & Purchase Invoices** | Multi-item, tax, discount, payment tracking সহ |
| 💳 **Payments** | Customer received + Supplier paid |
| 💸 **Expenses** | Categorized business expenses |
| 🧾 **Tax Rates (VAT/GST)** | Multiple rates, default rate, auto-calculation in invoice |
| 🔁 **Recurring Invoices & Expenses** | Daily/Weekly/Monthly/Quarterly/Yearly schedules, auto-generate via scheduler |

### 📦 Inventory & Sales Operations
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 📦 **Product Management** | SKU, purchase/sale price, stock, reorder level, category |
| 📊 **Stock Movements** | সব in/out history — invoice, adjustment, return |
| 🔗 **Auto Stock Control** | Sales → stock reduce, Purchase → stock increase |
| 📋 **Quotations** | Full CRUD + **Convert to Invoice** + **PDF + Email + WhatsApp share** |
| 🔄 **Credit / Debit Notes** | Sales/Purchase return with auto stock reversal |

### 🏦 Banking & Reconciliation
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 🏦 **Bank Reconciliation** | Statement CSV import + manual line add + match/ignore |
| 📊 **Statement vs Book Compare** | Live balance diff display per account per period |
| 📋 **Reconciliation History** | প্রতিটা finalized reconciliation-এর record |

### 🏢 Company & Branches
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 🏢 **Company Settings** | Logo, address, TIN, BIN, currency, fiscal year, footer |
| 🏬 **Multi-Branches** | Head Office + multiple branches, per-invoice branch tagging |
| 📄 **Professional Invoice PDF** | Logo, branches, serial no, warranty, amount-in-words |
| 📄 **Quotation PDF** | Same professional style, attached to emails & shared via WhatsApp |

### 📈 Financial Reports
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| ⚖️ **Trial Balance** | As-of-date filter |
| 📊 **Income Statement** | Date range P&L |
| 🏦 **Balance Sheet** | Assets, Liabilities, Equity snapshot |
| 💧 **Cashflow Statement** | Operating / Investing / Financing (auto-classified) |
| ⏳ **Aged Receivables** | Current / 1-30 / 31-60 / 61-90 / 90+ buckets |
| ⏳ **Aged Payables** | Same bucket breakdown for suppliers |
| 🖨️ **PDF Export** | সব report PDF export সহ Bengali font সাপোর্ট |

### 📥 Data Import / Export
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 📂 **CSV Import** | Customers, Suppliers, Products, Chart of Accounts |
| 📥 **Template Download** | প্রতিটা entity-এর জন্য ready-to-use CSV sample |
| 💾 **Automated Backup** | Daily DB + storage zip to `storage/app/backups`, 30-day retention |

### 🔐 Security & Administration
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 🔐 **User/Role/Permission** | Spatie Laravel Permission — granular RBAC |
| 🛡️ **Two-Factor Authentication (2FA)** | Email-based OTP at sign-in (opt-in per user) |
| 💾 **Audit Log** | প্রতিটি Create/Update/Delete track — user, IP, old vs new JSON |
| 📧 **Email Invoice & Quotation** | কাস্টমারকে সরাসরি HTML email + PDF attachment |
| 💬 **WhatsApp Share** | Quotation PDF-এর signed public link সহ pre-filled WhatsApp message |
| ⏰ **Overdue Invoice Reminders** | 7/14/30 days past due → auto email to customer |
| 🔑 **Default Roles** | Admin / Accountant / Viewer |

### 🎨 UX
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 📱 **Fully Responsive** | Mobile/Tablet/Desktop — সব screen-এ optimized |
| 🌙 **Dark / Light Mode** | Navbar toggle, localStorage সেভ |
| 🌐 **Bilingual** | English + বাংলা (instant switch) |
| 🔔 **Toast Notifications** | Success/error/warning — Toastr |
| ✨ **Modern Glassmorphism Login** | Gradient background + animated shapes |
| 🎯 **Select2 Dropdowns** | সবখানে searchable |
| 🧭 **Sidebar Scroll Memory** | Page reload-এর পরেও active menu visible থাকে |

---

## 🏗️ Architecture

This app follows **clean separation of concerns** for maintainability:

```
Request ─► Route ─► Controller ─► Repository (interface) ─► Eloquent Model ─► Database
                                        │
                                        └─► Service (e.g. JournalPostingService, QuotationPdfService)

                     Observer ◄───── Model Event (created/updated/deleted)
                         │
                         └─► Service (auto journal posting, audit logging, etc.)
```

- **Repository Pattern** — ২১টা repository (interface + implementation) রয়েছে [app/Repositories/](app/Repositories/)-এ। Controllers Eloquent-কে সরাসরি ব্যবহার না করে interface inject করে — testable + swappable।
- **Observers** — [InvoiceObserver](app/Observers/InvoiceObserver.php), [PaymentObserver](app/Observers/PaymentObserver.php), [ExpenseObserver](app/Observers/ExpenseObserver.php), [CreditDebitNoteObserver](app/Observers/CreditDebitNoteObserver.php) — model event-এ auto journal posting trigger করে।
- **Services** — [JournalPostingService](app/Services/JournalPostingService.php), [QuotationPdfService](app/Services/QuotationPdfService.php) — reusable domain logic।
- **Auditable Trait** — [app/Traits/Auditable.php](app/Traits/Auditable.php) — যেকোনো model-এ `use Auditable` করলে auto audit log হয়।
- **Signed URLs** — WhatsApp-share PDF public link 7-day expiry সহ cryptographically signed।

---

## 🛠️ প্রযুক্তি

| স্তর | প্রযুক্তি |
|-------|-----------|
| 🔧 Backend | Laravel 10, PHP 8.1+ |
| 🎨 Frontend | Blade, AdminLTE 3, Bootstrap 4, Alpine.js |
| 🗄️ Database | MySQL / MariaDB / SQLite |
| 🔒 Auth | Laravel Breeze + custom email-OTP 2FA |
| 🛡️ RBAC | Spatie Laravel Permission |
| 📄 PDF | mPDF 8.3, TCPDF 6.11 (Bengali font support) |
| 📧 Mail | Laravel Mail (SMTP) + queued notifications |
| 💬 WhatsApp | `wa.me` deep-linking (no paid API needed) |
| 🧩 Arch. | Repository Pattern + Observers + Services |
| ⚡ Build | Vite 5 |
| 🎯 Extras | Select2, Toastr, ApexCharts, DataTables |

---

## 📋 প্রয়োজনীয়তা

- PHP >= 8.1
- Composer
- MySQL 5.7+ / MariaDB / SQLite
- Node.js & npm
- Apache/Nginx (URL rewriting)

---

## 🚀 ইনস্টলেশন

```bash
# ১. Clone
git clone <repository-url> accounting-software
cd accounting-software

# ২. Dependencies
composer install
npm install && npm run build

# ৩. Environment
cp .env.example .env
php artisan key:generate

# ৪. Database
mysql -u root -p -e "CREATE DATABASE accounting_db"
# .env এ DB credentials configure করুন
# SQLite চাইলে: touch database/database.sqlite; set DB_CONNECTION=sqlite

# ৫. Migrate + Seed (Admin user, permissions, accounts, demo data)
php artisan migrate --seed

# ৬. Storage link (logo upload এর জন্য)
php artisan storage:link

# ৭. Run
php artisan serve
```

🌐 `http://localhost:8000` এ প্রবেশ করুন

> 💡 **WAMP/XAMPP:** Virtual host কে `public/` directory-তে point করুন।

> ⏰ **Scheduler (production):** নিচের cron entry যোগ করুন যাতে recurring invoices, overdue reminders, backup, ও mark-overdue auto চলে:
> ```cron
> * * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
> ```

---

## ⚙️ কনফিগারেশন

`.env` ফাইলে:

```env
APP_NAME="Accounting Software"
APP_URL=http://localhost:8000
APP_LOCALE=en      # or 'bn' for Bangla default

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=accounting_db
DB_USERNAME=root
DB_PASSWORD=

# ইমেইল (Invoice/Quotation email + 2FA OTP + Overdue reminders)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@company.com"
MAIL_FROM_NAME="${APP_NAME}"

# Queue (recommended: database in production)
QUEUE_CONNECTION=database
```

---

## 🔑 ডিফল্ট লগইন

Seeder চালানোর পর:

```
📧 Email:    admin@admin.com
🔒 Password: password
```

Admin role সব permission সহ automatically assign হয়ে যাবে।

---

## 📖 মডিউলসমূহ

### 📊 Dashboard (`/dashboard`)
- 💵 Cash in Hand, Cash at Bank
- 📥 Total Receivable, 📤 Total Payable
- 📊 Income vs Expense — Last 6 months chart
- 🧾 Recent invoices, payments, expenses
- ⚠️ Overdue invoices alert
- 🌅 Time-based greeting (Good Morning/Afternoon/Evening)

### 📒 Accounting
- **Chart of Accounts** (`/accounts`) — 5 types, opening balance, ledger view
- **Account Groups** (`/account-groups`) — hierarchical parent-child
- **Journal Entries** (`/journals`) — 6 voucher types; auto-posted entries দেখা যায় `is_auto_posted` flag দিয়ে
- **Tax Rates** (`/tax-rates`) — VAT/GST rates with default flag

### 📦 Inventory
- **Products** (`/products`) — SKU (auto-generated), unit, prices, reorder level
- **Stock Adjustments** — Manual in/out/exact adjustment via modal
- **Stock Report** (`/products/stock-report`) — Total value, low/out of stock counts
- **Movement History** (`/products/{id}/movements`) — Full audit trail per product

### 🧾 Sales & Purchase
- **Quotations** (`/quotations`) — Subject, valid-until, terms, **Convert to Invoice**, **PDF**, **Email (attached PDF)**, **WhatsApp share (signed public PDF link)**
- **Sales Invoices** (`/invoices?type=sales`) — Product dropdown, serial no, warranty, tax auto-calc, email to customer
- **Purchase Bills** (`/invoices?type=purchase`) — Same but stock increases
- **Credit Notes** (`/credit-debit-notes?type=credit`) — Sales return → stock IN
- **Debit Notes** (`/credit-debit-notes?type=debit`) — Purchase return → stock OUT

### 🔁 Recurring
- **Recurring Invoices** (`/recurring-invoices`) — Items, frequency, start/end, auto-generate
- **Recurring Expenses** (`/recurring-expenses`) — Monthly rent, salary, utilities auto-create

### 💳 Transactions & Contacts
- **Payments** (`/payments?type=received|made`) — Link to invoice, methods, reference
- **Expenses** (`/expenses`) — Category, linked account, supplier
- **Customers** (`/customers`) — Contact, opening balance, total due (auto)
- **Suppliers** (`/suppliers`) — Contact, total payable (auto)

### 🏦 Banking
- **Bank Reconciliation** (`/bank-reconciliation`) — Per-account period view, statement import, match/ignore/finalize
- Statement CSV format: `date, description, reference, debit, credit, balance`

### 📈 Reports (`/reports/*`)
- Trial Balance, Income Statement, Balance Sheet
- **Cashflow Statement** (Operating/Investing/Financing)
- Aged Receivables (5 buckets), Aged Payables (5 buckets)
- All exportable as PDF

### 📂 Data (`/imports`)
- CSV import for Customers, Suppliers, Products, Accounts
- Template download per entity
- Idempotent (re-import updates by email/SKU/code)

### 🏢 Company (`/company-settings` & `/branches`)
- Company profile with logo upload
- Multiple branches management with Head Office flag
- PDF footer + Terms & Conditions

### 🔐 Administration
- **Audit Log** (`/audit-logs`) — Filter by action/model/user, old vs new side-by-side
- **Users** (`/settings/users`) — Create/edit with role assignment
- **Roles** (`/settings/roles`) — Custom roles with permission sets
- **Permissions** (`/settings/permissions`) — Granular permissions across modules
- **2FA toggle** — Profile page থেকে enable/disable

---

## ⏰ Scheduler & Console Commands

| Command | Schedule | Purpose |
|---------|----------|---------|
| `recurring:run` | Daily 02:00 | Recurring invoices/expenses auto-generate |
| `invoices:mark-overdue` | Daily 00:10 | Past-due unpaid invoices → `overdue` status |
| `invoices:overdue-reminders` | Daily 08:00 | Email reminders at 7/14/30 days past due |
| `backup:run --only-db` | Daily 01:00 | DB backup zip → `storage/app/backups` |
| `journal:backfill {--fresh}` | Manual | Existing Invoice/Payment/Expense/Note-এর journal entry backfill |

Manual runs:
```bash
php artisan recurring:run
php artisan backup:run                   # DB + storage
php artisan backup:run --only-db         # DB only
php artisan journal:backfill --fresh     # Reset + repost all auto journals
php artisan invoices:overdue-reminders --days=7,14
```

---

## 🗄️ ডাটাবেস স্কিমা

**মূল টেবিল (৩০+):**

```
users, roles, permissions, model_has_roles, model_has_permissions, role_has_permissions
company_settings, branches, audit_logs
accounts, account_groups
journal_entries, journal_entry_items         # includes source_type / source_id / is_auto_posted
customers, suppliers
products, stock_movements
tax_rates
quotations, quotation_items
invoices, invoice_items
credit_debit_notes, credit_debit_note_items
payments, expenses
recurring_invoices, recurring_expenses
bank_statement_lines, bank_reconciliations
jobs, failed_jobs
```

**বিশেষ সম্পর্ক:**
- ⚡ `journal_entries.source_type + source_id` → polymorphic link back to Invoice/Payment/Expense/Note
- 🔗 `invoice_items.product_id` → auto stock movement
- 🔄 `quotations.converted_invoice_id` → link after conversion
- 📎 `credit_debit_notes.invoice_id` → optional original invoice link
- 🏬 `invoices.branch_id` → branch tagging per invoice
- 🏦 `bank_statement_lines.journal_entry_id` → match reference after reconciliation

---

## 🌐 Bilingual সাপোর্ট

- 🇬🇧 English (default)
- 🇧🇩 বাংলা (complete translation)

Navbar-এ 🌐 language switcher dropdown — session-based, instant switch। Translation files: [lang/en/messages.php](lang/en/messages.php) & [lang/bn/messages.php](lang/bn/messages.php)।

PDF-এ বাংলা রেন্ডার হয় **SolaimanLipi** ফন্টের মাধ্যমে।

---

## 🎨 UI ফিচার

### 📱 Responsive
সব screen সাইজের জন্য optimized — Desktop, Laptop, Tablet, Mobile Landscape, Small Mobile। Table মোবাইলে auto horizontal-scroll, DataTables controls স্ট্যাক হয়।

### 🌙 Dark / Light Mode
Navbar-এর 🌙/☀️ আইকনে ক্লিক করে টগল। Sidebar, cards, forms সবকিছু automatic adapt। Preference localStorage-এ save।

### 🎭 Modern Login
Glassmorphism card + animated gradient background + floating blurred shapes + demo credentials displayed।

### 📊 Dashboard
- Time-based gradient welcome hero
- Gradient stat cards (Cash, Bank, Receivable, Payable)
- ApexCharts দিয়ে interactive charts
- Real-time KPIs

### 🖼️ PDF Templates
প্রতিটা Invoice ও Quotation PDF-এ:
- Logo + company name + address + contact (header)
- Two-column meta table
- Items table with Serial No + Warranty
- "IN WORDS" amount in English
- Customer + Authorized signatures
- Footer note + all branches listed

---

## 📧 ইমেইল সেটআপ

Invoice/Quotation/2FA/Overdue reminder — সব ইমেইলের জন্য:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@company.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> **Gmail:** [App Password তৈরি করুন](https://myaccount.google.com/apppasswords) (2-Step verification অন থাকতে হবে)

- **Invoice page-এ** "Email to Customer" button
- **Quotation page-এ** "Email" button (PDF auto-attach)
- **Overdue reminders** auto email হবে scheduler থেকে

---

## 💬 WhatsApp Integration

Quotation page-এ **WhatsApp** button ক্লিক করলে:
1. Customer-এর phone number auto-normalize হয় (`01711-111111` → `8801711111111`)
2. `wa.me/<phone>?text=...` URL generate হয় pre-filled message সহ
3. Message-এ **signed public PDF link** (7-day expiry) included থাকে
4. ইউজারের WhatsApp Web/App open হয় — শুধু Send করলেই হয়

```
Hello রহিম ট্রেডার্স,

Here is your quotation from *ডেমো ট্রেডিং কোম্পানি*:

📄 Quotation: *QT-000001*
📅 Date: 08 Jan 2026
💰 Total: ৳ 5,775.00

View / Download PDF:
https://yourapp.com/quotations/1/public-pdf?expires=...&signature=...
```

> কোনো paid WhatsApp Business API লাগে না। Cloud API চাইলে `WhatsAppService` class add করে `whatsappLink()` method-কে API call-এ swap করা যায়।

---

## 🔐 Two-Factor Authentication

- প্রতিটা user নিজের profile (`/profile`) থেকে 2FA enable/disable করতে পারে
- Enabled হলে login-এর পর email-এ 6-digit OTP যাবে (10-min validity)
- OTP verify করার পর session-এ `two_factor_passed` flag সেট হয় — পরবর্তী page access-এ আর জিজ্ঞেস করবে না
- Middleware: [EnforceTwoFactor](app/Http/Middleware/EnforceTwoFactor.php) (`2fa` alias) — protected routes-এ apply করা

---

## 🎯 ডিফল্ট Roles & Permissions

Seeder ৩টা role তৈরি করে:

| Role | Access |
|------|--------|
| 👑 **Admin** | সব module-এর সব permission |
| 💼 **Accountant** | Settings (Users/Roles/Permissions) ছাড়া সব |
| 👁️ **Viewer** | শুধু view permission |

প্রতিটা module × view/create/edit/delete = granular RBAC

---

## 📂 Directory Structure

```
accounting-software/
├── app/
│   ├── Console/Commands/        (backup, recurring, overdue, backfill)
│   ├── Http/
│   │   ├── Controllers/         (25+ controllers)
│   │   └── Middleware/EnforceTwoFactor.php
│   ├── Mail/                    (InvoiceMail, QuotationMail)
│   ├── Models/                  (25+ models)
│   ├── Notifications/           (InvoiceOverdue, TwoFactorCode)
│   ├── Observers/               (Invoice/Payment/Expense/CreditDebitNote — auto journal posting)
│   ├── Providers/               (AppServiceProvider, RepositoryServiceProvider)
│   ├── Repositories/            (Contracts + 21 implementations)
│   ├── Services/                (JournalPostingService, QuotationPdfService)
│   └── Traits/Auditable.php     (auto audit-log trait)
├── database/
│   ├── migrations/              (30+ migrations)
│   └── seeders/                 (10 seeders — roles, accounts, demo data, products, quotations, etc.)
├── resources/
│   ├── views/                   (80+ blade files)
│   │   ├── emails/              (invoice, quotation)
│   │   ├── pdf/                 (invoice, quotation)
│   │   ├── bank-reconciliation/
│   │   ├── recurring-invoices/
│   │   ├── recurring-expenses/
│   │   ├── imports/
│   │   └── auth/two-factor.blade.php
│   └── css/app.css
├── lang/
│   ├── en/messages.php
│   └── bn/messages.php
├── public/
│   ├── css/theme.css            (custom theme + dark mode + responsive)
│   └── storage/                 (logo uploads — via symlink)
├── storage/
│   ├── app/backups/             (automated DB backups)
│   └── fonts/SolaimanLipi.ttf   (Bangla font for PDF)
└── routes/web.php
```

---

## 📄 লাইসেন্স

<p align="center">
  📄 <a href="LICENSE">MIT License</a>
</p>

<p align="center">
  Laravel দিয়ে ❤️ সহকারে তৈরি · Bangladesh 🇧🇩
</p>
