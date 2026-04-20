<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Accounting Software') }}</title>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Source+Sans+Pro:wght@300;400;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- AdminLTE 3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Custom Theme -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v={{ time() }}">

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<!-- Page Loader -->
<div class="page-loader" id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">{{ __('messages.home') }}</a>
            </li>
        </ul>

        <!-- Right -->
        <ul class="navbar-nav ml-auto">
            <!-- Dark Mode Toggle -->
            <li class="nav-item">
                <a class="nav-link" href="#" id="darkModeToggle" title="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </a>
            </li>

            <!-- Language Switcher -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-globe mr-1"></i>
                    {{ app()->getLocale() === 'bn' ? 'বাংলা' : 'English' }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                        🇬🇧 English
                    </a>
                    <a href="{{ route('lang.switch', 'bn') }}" class="dropdown-item {{ app()->getLocale() === 'bn' ? 'active' : '' }}">
                        🇧🇩 বাংলা
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle mr-1"></i> {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('messages.logout') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="brand-link">
            <i class="fas fa-calculator brand-image ml-3 mt-1" style="font-size: 1.5rem; opacity: .8;"></i>
            <span class="brand-text font-weight-light"><strong>Accounting</strong> Software</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ __('messages.dashboard') }}</p>
                        </a>
                    </li>

                    <li class="nav-header">{{ __('messages.accounting') }}</li>

                    <li class="nav-item">
                        <a href="{{ route('accounts.index') }}" class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calculator"></i>
                            <p>{{ __('messages.chart_of_accounts') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('account-groups.index') }}" class="nav-link {{ request()->routeIs('account-groups.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>{{ __('messages.account_groups') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('journals.index') }}" class="nav-link {{ request()->routeIs('journals.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>{{ __('messages.journal_entries') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('tax-rates.index') }}" class="nav-link {{ request()->routeIs('tax-rates.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-percent"></i>
                            <p>{{ __('messages.tax_rates') }}</p>
                        </a>
                    </li>

                    <li class="nav-header">{{ __('messages.inventory') }}</li>

                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>{{ __('messages.products') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('products.stock-report') }}" class="nav-link {{ request()->routeIs('products.stock-report') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>{{ __('messages.stock_report') }}</p>
                        </a>
                    </li>

                    <li class="nav-header">{{ __('messages.sales_purchase') }}</li>

                    <li class="nav-item">
                        <a href="{{ route('quotations.index') }}" class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-signature"></i>
                            <p>{{ __('messages.quotations') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('invoices.index', ['type' => 'sales']) }}" class="nav-link {{ request()->routeIs('invoices.*') && request('type') == 'sales' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>{{ __('messages.sales_invoices') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('invoices.index', ['type' => 'purchase']) }}" class="nav-link {{ request()->routeIs('invoices.*') && request('type') == 'purchase' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>{{ __('messages.purchase_bills') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('credit-debit-notes.index', ['type' => 'credit']) }}" class="nav-link {{ request()->routeIs('credit-debit-notes.*') && request('type') != 'debit' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-undo"></i>
                            <p>{{ __('messages.credit_notes') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('credit-debit-notes.index', ['type' => 'debit']) }}" class="nav-link {{ request()->routeIs('credit-debit-notes.*') && request('type') == 'debit' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-redo"></i>
                            <p>{{ __('messages.debit_notes') }}</p>
                        </a>
                    </li>

                    <li class="nav-header">{{ __('messages.transactions') }}</li>

                    <li class="nav-item">
                        <a href="{{ route('payments.index', ['type' => 'received']) }}" class="nav-link {{ request()->routeIs('payments.*') && request('type') == 'received' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>{{ __('messages.payment_received') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('payments.index', ['type' => 'made']) }}" class="nav-link {{ request()->routeIs('payments.*') && request('type') == 'made' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>{{ __('messages.payment_made') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>{{ __('messages.expenses') }}</p>
                        </a>
                    </li>

                    <li class="nav-header">{{ __('messages.contacts') }}</li>

                    <li class="nav-item">
                        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('messages.customers') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>{{ __('messages.suppliers') }}</p>
                        </a>
                    </li>

                    <li class="nav-header">{{ __('messages.reports_header') }}</li>

                    <li class="nav-item">
                        <a href="{{ route('reports.trial-balance') }}" class="nav-link {{ request()->routeIs('reports.trial-balance') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-balance-scale"></i>
                            <p>{{ __('messages.trial_balance') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.income-statement') }}" class="nav-link {{ request()->routeIs('reports.income-statement') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>{{ __('messages.income_statement') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.balance-sheet') }}" class="nav-link {{ request()->routeIs('reports.balance-sheet') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>{{ __('messages.balance_sheet') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.aged-receivables') }}" class="nav-link {{ request()->routeIs('reports.aged-receivables') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hourglass-half"></i>
                            <p>{{ __('messages.aged_receivables') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.aged-payables') }}" class="nav-link {{ request()->routeIs('reports.aged-payables') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hourglass-end"></i>
                            <p>{{ __('messages.aged_payables') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('audit-logs.index') }}" class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>{{ __('messages.audit_log') }}</p>
                        </a>
                    </li>

                    <li class="nav-header">{{ __('messages.company') }}</li>

                    <li class="nav-item">
                        <a href="{{ route('company-settings.edit') }}" class="nav-link {{ request()->routeIs('company-settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>{{ __('messages.company_settings') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('branches.index') }}" class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-store"></i>
                            <p>{{ __('messages.branches') }}</p>
                        </a>
                    </li>

                    @canany(['settings.users.view', 'settings.roles.view', 'settings.permissions.view'])
                        <li class="nav-header">{{ __('messages.settings_header') }}</li>

                        <li class="nav-item {{ request()->routeIs('settings.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>
                                    {{ __('messages.administrator') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('settings.users.view')
                                    <li class="nav-item">
                                        <a href="{{ route('settings.users.index') }}" class="nav-link {{ request()->routeIs('settings.users.*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('messages.users') }}</p>
                                        </a>
                                    </li>
                                @endcan

                                @can('settings.roles.view')
                                    <li class="nav-item">
                                        <a href="{{ route('settings.roles.index') }}" class="nav-link {{ request()->routeIs('settings.roles.*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('messages.roles') }}</p>
                                        </a>
                                    </li>
                                @endcan

                                @can('settings.permissions.view')
                                    <li class="nav-item">
                                        <a href="{{ route('settings.permissions.index') }}" class="nav-link {{ request()->routeIs('settings.permissions.*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('messages.permissions') }}</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} Accounting Software.</strong> All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE 3 -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Auto-initialize Select2 for all select elements (except with .no-select2 class)
    $(function() {
        $('select:not(.no-select2)').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });

    // Dark Mode Toggle
    $(function() {
        const body = document.body;
        const toggle = document.getElementById('darkModeToggle');
        const icon = toggle ? toggle.querySelector('i') : null;

        const setTheme = (isDark) => {
            if (isDark) {
                body.classList.add('dark-mode');
                if (icon) { icon.classList.remove('fa-moon'); icon.classList.add('fa-sun'); }
            } else {
                body.classList.remove('dark-mode');
                if (icon) { icon.classList.remove('fa-sun'); icon.classList.add('fa-moon'); }
            }
        };

        // Load saved preference
        const savedDark = localStorage.getItem('darkMode') === 'true';
        setTheme(savedDark);

        if (toggle) {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const isDark = !body.classList.contains('dark-mode');
                localStorage.setItem('darkMode', isDark);
                setTheme(isDark);
            });
        }
    });
</script>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "newestOnTop": true,
    };

    @if(session('success'))
        toastr.success(@json(session('success')));
    @endif

    @if(session('error'))
        toastr.error(@json(session('error')));
    @endif

    @if(session('warning'))
        toastr.warning(@json(session('warning')));
    @endif

    @if(session('info'))
        toastr.info(@json(session('info')));
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error(@json($error));
        @endforeach
    @endif
</script>

@stack('scripts')
</body>
</html>
