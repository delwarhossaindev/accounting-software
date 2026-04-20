<p align="center">
  <img src="https://img.icons8.com/3d-fluency/94/accounting.png" alt="Accounting Software Logo" width="80"/>
</p>

<h1 align="center">💰 অ্যাকাউন্টিং সফটওয়্যার</h1>

<p align="center">
  একটি পূর্ণাঙ্গ <strong>ডাবল-এন্ট্রি অ্যাকাউন্টিং + ইনভেন্টরি + ইনভয়েসিং</strong> অ্যাপ্লিকেশন যা <strong>Laravel 10</strong>, <strong>AdminLTE 3</strong> এবং <strong>Spatie Laravel Permission</strong> দিয়ে তৈরি।
  <br/>
  Small & Medium Business (SME) এর জন্য উপযুক্ত — Quotation থেকে শুরু করে Invoice, Stock, Return, Reports, PDF সবকিছু এক জায়গায়।
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10"/>
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/AdminLTE-3-007bff?style=for-the-badge" alt="AdminLTE 3"/>
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License"/>
  <img src="https://img.shields.io/badge/i18n-EN%2FBN-ff69b4?style=for-the-badge" alt="Bilingual"/>
</p>

---

## 📑 সূচিপত্র

- [✨ বৈশিষ্ট্যসমূহ](#-বৈশিষ্ট্যসমূহ)
- [🛠️ প্রযুক্তি](#️-প্রযুক্তি)
- [📋 প্রয়োজনীয়তা](#-প্রয়োজনীয়তা)
- [🚀 ইনস্টলেশন](#-ইনস্টলেশন)
- [⚙️ কনফিগারেশন](#️-কনফিগারেশন)
- [🔑 ডিফল্ট লগইন](#-ডিফল্ট-লগইন)
- [📖 মডিউলসমূহ](#-মডিউলসমূহ)
- [🗄️ ডাটাবেস স্কিমা](#️-ডাটাবেস-স্কিমা)
- [🌐 Bilingual সাপোর্ট](#-bilingual-সাপোর্ট)
- [🎨 UI ফিচার](#-ui-ফিচার)
- [📧 ইমেইল সেটআপ](#-ইমেইল-সেটআপ)
- [📄 লাইসেন্স](#-লাইসেন্স)

---

## ✨ বৈশিষ্ট্যসমূহ

### 📚 Core Accounting
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 📒 **Chart of Accounts** | অ্যাকাউন্ট + হায়ারার্কিক্যাল Account Groups |
| 📝 **Journal Entries** | ডাবল-এন্ট্রি, ৬টি voucher type (Journal, Receipt, Payment, Contra, Sales, Purchase) |
| 👥 **Customers & Suppliers** | Opening balance, দায়/প্রাপ্য auto-calculate |
| 🧾 **Sales & Purchase Invoices** | Multi-item, tax, discount, payment tracking সহ |
| 💳 **Payments** | Customer received + Supplier paid |
| 💸 **Expenses** | Categorized business expenses |
| 🧾 **Tax Rates (VAT/GST)** | Multiple rates, default rate, auto-calculation in invoice |

### 📦 Inventory & Sales Operations
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 📦 **Product Management** | SKU, purchase/sale price, stock, reorder level, category |
| 📊 **Stock Movements** | সব in/out history — invoice, adjustment, return |
| 🔗 **Auto Stock Control** | Sales → stock reduce, Purchase → stock increase |
| 📋 **Quotations** | Full CRUD + **one-click Convert to Invoice** |
| 🔄 **Credit / Debit Notes** | Sales/Purchase return with auto stock reversal |

### 🏢 Company & Branches
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 🏢 **Company Settings** | Logo, address, TIN, BIN, currency, fiscal year, footer |
| 🏬 **Multi-Branches** | Head Office + multiple branches, per-invoice branch tagging |
| 📄 **Professional Invoice PDF** | JM International style — logo, branches, serial no, warranty, amount-in-words |

### 📈 Financial Reports
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| ⚖️ **Trial Balance** | As-of-date filter |
| 📊 **Income Statement** | Date range P&L |
| 🏦 **Balance Sheet** | Assets, Liabilities, Equity snapshot |
| ⏳ **Aged Receivables** | Current / 1-30 / 31-60 / 61-90 / 90+ buckets |
| ⏳ **Aged Payables** | Same bucket breakdown for suppliers |
| 🖨️ **PDF Export** | সব report PDF export সহ Bengali font সাপোর্ট |

### 🔐 Security & Administration
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 🔐 **User/Role/Permission** | Spatie Laravel Permission — granular RBAC |
| 💾 **Audit Log** | প্রতিটি Create/Update/Delete track — user, IP, old vs new JSON |
| 📧 **Email Invoice** | কাস্টমারকে সরাসরি HTML invoice পাঠান |
| 🔑 **Default Roles** | Admin / Accountant / Viewer |

### 🎨 UX
| বৈশিষ্ট্য | বর্ণনা |
|---------|-------------|
| 🌙 **Dark / Light Mode** | Navbar toggle, localStorage সেভ |
| 🌐 **Bilingual** | English + বাংলা (instant switch) |
| 🔔 **Toast Notifications** | Success/error/warning — Toastr |
| ✨ **Modern Glassmorphism Login** | Gradient background + animated shapes |
| 🎯 **Select2 Dropdowns** | সবখানে searchable |

---

## 🛠️ প্রযুক্তি

| স্তর | প্রযুক্তি |
|-------|-----------|
| 🔧 Backend | Laravel 10, PHP 8.1+ |
| 🎨 Frontend | Blade, AdminLTE 3, Bootstrap 4, Alpine.js |
| 🗄️ Database | MySQL / MariaDB |
| 🔒 Auth | Laravel Breeze, Laravel Sanctum |
| 🛡️ RBAC | Spatie Laravel Permission |
| 📄 PDF | mPDF 8.3, TCPDF 6.11 (Bengali font support) |
| 📧 Mail | Laravel Mail (SMTP) |
| ⚡ Build | Vite 5 |
| 🎯 Extras | Select2, Toastr, ApexCharts, DataTables |

---

## 📋 প্রয়োজনীয়তা

- PHP >= 8.1
- Composer
- MySQL 5.7+ or MariaDB
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

# ৫. Migrate + Seed (Admin user, permissions, default accounts)
php artisan migrate --seed

# ৬. Storage link (logo upload এর জন্য)
php artisan storage:link

# ৭. Run
php artisan serve
```

🌐 `http://localhost:8000` এ প্রবেশ করুন

> 💡 **WAMP/XAMPP:** Virtual host কে `public/` directory তে point করুন।

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

# ইমেইল (Invoice email পাঠানোর জন্য)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS="no-reply@company.com"
MAIL_FROM_NAME="${APP_NAME}"
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
- **Journal Entries** (`/journals`) — 6 voucher types, auto-numbered, debit=credit validation
- **Tax Rates** (`/tax-rates`) — VAT/GST rates with default flag

### 📦 Inventory
- **Products** (`/products`) — SKU (auto-generated), unit, prices, reorder level
- **Stock Adjustments** — Manual in/out/exact adjustment via modal
- **Stock Report** (`/products/stock-report`) — Total value, low/out of stock counts
- **Movement History** (`/products/{id}/movements`) — Full audit trail per product

### 🧾 Sales & Purchase
- **Quotations** (`/quotations`) — Subject, valid-until, terms, **convert to invoice**
- **Sales Invoices** (`/invoices?type=sales`) — Product dropdown, serial no, warranty, tax auto-calc, email to customer
- **Purchase Bills** (`/invoices?type=purchase`) — Same but stock increases
- **Credit Notes** (`/credit-debit-notes?type=credit`) — Sales return → stock IN
- **Debit Notes** (`/credit-debit-notes?type=debit`) — Purchase return → stock OUT

### 💳 Transactions & Contacts
- **Payments** (`/payments?type=received|made`) — Link to invoice, methods, reference
- **Expenses** (`/expenses`) — Category, linked account, supplier
- **Customers** (`/customers`) — Contact, opening balance, total due (auto)
- **Suppliers** (`/suppliers`) — Contact, total payable (auto)

### 📈 Reports (`/reports/*`)
- Trial Balance, Income Statement, Balance Sheet
- Aged Receivables (5 buckets)
- Aged Payables (5 buckets)
- All exportable as PDF

### 🏢 Company (`/company-settings` & `/branches`)
- Company profile with logo upload
- Multiple branches management with Head Office flag
- PDF footer + Terms & Conditions

### 🔐 Administration
- **Audit Log** (`/audit-logs`) — Filter by action/model/user, old vs new side-by-side
- **Users** (`/settings/users`) — Create/edit with role assignment
- **Roles** (`/settings/roles`) — Custom roles with permission sets
- **Permissions** (`/settings/permissions`) — 48 granular permissions across 12 modules

---

## 🗄️ ডাটাবেস স্কিমা

**মূল টেবিল (২০+):**

```
users, roles, permissions, model_has_roles, model_has_permissions, role_has_permissions
company_settings, branches, audit_logs
accounts, account_groups, journal_entries, journal_entry_items
customers, suppliers
products, stock_movements
tax_rates
quotations, quotation_items
invoices, invoice_items
credit_debit_notes, credit_debit_note_items
payments, expenses
```

**বিশেষ সম্পর্ক:**
- 🔗 `invoice_items.product_id` → auto stock movement
- 🔄 `quotations.converted_invoice_id` → link after conversion
- 📎 `credit_debit_notes.invoice_id` → optional original invoice link
- 🏬 `invoices.branch_id` → branch tagging per invoice

---

## 🌐 Bilingual সাপোর্ট

- 🇬🇧 English (default)
- 🇧🇩 বাংলা (complete translation)

Navbar এ 🌐 language switcher dropdown — session-based, instant switch। Translation files: [lang/en/messages.php](lang/en/messages.php) & [lang/bn/messages.php](lang/bn/messages.php)।

PDF এ বাংলা রেন্ডার হয় **SolaimanLipi** ফন্টের মাধ্যমে।

---

## 🎨 UI ফিচার

### 🌙 Dark / Light Mode
Navbar এর 🌙/☀️ আইকনে ক্লিক করে টগল। Sidebar, cards, forms সবকিছু automatic adapt। Preference localStorage এ save।

### 🎭 Modern Login
Glassmorphism card + animated gradient background + floating blurred shapes + demo credentials displayed।

### 📊 Dashboard
- Time-based gradient welcome hero
- Gradient stat cards (Cash, Bank, Receivable, Payable)
- ApexCharts দিয়ে interactive charts
- Real-time KPIs

### 🖼️ PDF Template (JM International Style)
প্রতিটা invoice PDF এ:
- Logo + company name + address + contact (header)
- Two-column invoice meta (Invoice #, Customer, Address, Mobile | Date, Branch, P.O., Req No, Sold By, Print Time)
- Items table with Serial No + Warranty
- "IN WORDS" amount in English
- Customer + Authorized signatures
- Footer note + all branches listed

---

## 📧 ইমেইল সেটআপ

Invoice কে customer এর ইমেইলে পাঠানোর জন্য:

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

Invoice show পেজে **"Email to Customer"** বাটন আসবে (যদি customer এর email থাকে)। পাঠানোর সাথে সাথে draft → sent status এ মুভ।

---

## 🎯 ডিফল্ট Roles & Permissions

Seeder ৩টা role তৈরি করে:

| Role | Access |
|------|--------|
| 👑 **Admin** | সব ১২টা module এর সব permission |
| 💼 **Accountant** | Settings (Users/Roles/Permissions) ছাড়া সব |
| 👁️ **Viewer** | শুধু view permission |

১২টা module × ৪টা action (view/create/edit/delete) = **৪৮টা permission**

---

## 📂 Directory Structure

```
accounting-software/
├── app/
│   ├── Http/Controllers/     (23+ controllers)
│   ├── Models/                (20+ models)
│   ├── Traits/Auditable.php  (auto-log trait)
│   └── Mail/InvoiceMail.php  (email mailable)
├── database/migrations/       (20+ migrations)
├── resources/
│   ├── views/                 (70+ blade files)
│   └── css/app.css
├── lang/
│   ├── en/messages.php
│   └── bn/messages.php
├── public/
│   ├── css/theme.css         (custom theme + dark mode)
│   └── storage/              (logo uploads — via symlink)
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
