<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReceivable = Invoice::where('type', 'sales')->where('status', '!=', 'paid')->sum('due');
        $totalPayable = Invoice::where('type', 'purchase')->where('status', '!=', 'paid')->sum('due');
        $totalIncome = Account::where('type', 'income')->get()->sum('balance');
        $totalExpense = Expense::sum('amount');
        $recentInvoices = Invoice::with(['customer', 'supplier'])->where('type', 'sales')->latest()->take(5)->get();
        $recentPayments = Payment::with(['customer', 'supplier', 'account'])->latest()->take(5)->get();
        $recentExpenses = Expense::with(['account'])->latest()->take(5)->get();
        $customerCount = Customer::count();
        $supplierCount = Supplier::count();

        // Cash & Bank balances
        $cashInHand = Account::where('code', '1001')->first()?->balance ?? 0;
        $cashAtBank = Account::where('code', '1002')->first()?->balance ?? 0;

        // Counts
        $totalInvoices = Invoice::where('type', 'sales')->count();
        $totalBills = Invoice::where('type', 'purchase')->count();
        $overdueInvoices = Invoice::where('type', 'sales')->where('status', 'overdue')->count();

        // Monthly income vs expense (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('Y-m');
            $monthLabel = $date->format('M Y');

            $monthIncome = Payment::where('type', 'received')
                ->whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');

            $monthExpense = Expense::whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');

            $monthlyData[] = [
                'month' => $monthLabel,
                'income' => (float) $monthIncome,
                'expense' => (float) $monthExpense,
            ];
        }

        // Top 5 expense categories
        $topExpenses = Expense::with('account')
            ->select('account_id', DB::raw('SUM(amount) as total'))
            ->groupBy('account_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Invoice status breakdown
        $invoiceStatusCounts = Invoice::where('type', 'sales')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard', compact(
            'totalReceivable', 'totalPayable', 'totalIncome', 'totalExpense',
            'recentInvoices', 'recentPayments', 'recentExpenses',
            'customerCount', 'supplierCount',
            'cashInHand', 'cashAtBank',
            'totalInvoices', 'totalBills', 'overdueInvoices',
            'monthlyData', 'topExpenses', 'invoiceStatusCounts'
        ));
    }
}
