<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Payment;
use App\Models\Quotation;
use App\Models\Supplier;
use App\Services\QuotationPdfService;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class PdfController extends Controller
{
    private function createPdf($title, $orientation = 'P')
    {
        $fontDir = storage_path('fonts');

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4' . ($orientation === 'L' ? '-L' : ''),
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'fontDir' => [$fontDir],
            'fontdata' => [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ] + (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'],
            'default_font' => 'solaimanlipi',
            'tempDir' => storage_path('app/mpdf'),
        ]);

        $mpdf->SetTitle($title);
        $mpdf->SetAuthor('Admin');
        $mpdf->SetCreator('Accounting Software');

        return $mpdf;
    }

    private function tableStyle()
    {
        return 'style="border-collapse: collapse; width: 100%;" cellpadding="5"';
    }

    private function thStyle($align = 'left')
    {
        return 'style="background-color: #343a40; color: #ffffff; font-weight: bold; text-align: ' . $align . '; border: 1px solid #454d55; font-size: 9px; padding: 6px;"';
    }

    private function tdStyle($align = 'left')
    {
        return 'style="border: 1px solid #dee2e6; text-align: ' . $align . '; font-size: 9px; padding: 5px;"';
    }

    private function headerHtml($title, $subtitle = '')
    {
        $date = now()->format('d M Y');
        $html = '<table width="100%"><tr>';
        $html .= '<td><h2 style="color: #343a40; margin: 0;">' . $title . '</h2>';
        if ($subtitle) {
            $html .= '<p style="color: #6c757d; font-size: 10px; margin: 2px 0 0 0;">' . $subtitle . '</p>';
        }
        $html .= '</td>';
        $html .= '<td style="text-align: right; color: #6c757d; font-size: 10px;">Generated: ' . $date . '</td>';
        $html .= '</tr></table><br>';
        return $html;
    }

    // ========== Quotation PDF ==========
    public function quotation(Quotation $quotation, QuotationPdfService $service)
    {
        $pdf = $service->render($quotation);
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $service->filename($quotation) . '"',
        ]);
    }

    // ========== Invoice PDF ==========
    public function invoice(Invoice $invoice)
    {
        $invoice->load('items.product', 'customer', 'supplier', 'branch', 'payments');
        $company = CompanySetting::current();
        $branches = Branch::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $mpdf = $this->createPdf('Invoice - ' . $invoice->invoice_no);

        $html = view('pdf.invoice', compact('invoice', 'company', 'branches'))->render();

        $mpdf->WriteHTML($html);
        return response($mpdf->Output($invoice->invoice_no . '.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $invoice->invoice_no . '.pdf"',
        ]);
    }

    // ========== Journal Entry PDF ==========
    public function journal(JournalEntry $journal)
    {
        $journal->load('items.account', 'user');
        $mpdf = $this->createPdf('Journal - ' . $journal->voucher_no);

        $html = $this->headerHtml($journal->voucher_no, ucfirst($journal->voucher_type) . ' Voucher');

        $html .= '<table width="100%" cellpadding="4"><tr>';
        $html .= '<td style="font-size: 10px;"><strong>Date:</strong> ' . $journal->date->format('d M Y') . '</td>';
        $html .= '<td style="font-size: 10px;"><strong>Type:</strong> ' . ucfirst($journal->voucher_type) . '</td>';
        $html .= '<td style="font-size: 10px;"><strong>Created By:</strong> ' . ($journal->user->name ?? '-') . '</td>';
        $html .= '</tr></table>';

        if ($journal->narration) {
            $html .= '<p style="font-size: 10px; color: #6c757d;"><strong>Narration:</strong> ' . $journal->narration . '</p>';
        }
        $html .= '<br>';

        $html .= '<table ' . $this->tableStyle() . '>';
        $html .= '<tr>';
        $html .= '<th ' . $this->thStyle() . ' width="15%">Code</th>';
        $html .= '<th ' . $this->thStyle() . ' width="30%">Account</th>';
        $html .= '<th ' . $this->thStyle('right') . ' width="20%">Debit</th>';
        $html .= '<th ' . $this->thStyle('right') . ' width="20%">Credit</th>';
        $html .= '<th ' . $this->thStyle() . ' width="15%">Description</th>';
        $html .= '</tr>';

        $totalDebit = 0;
        $totalCredit = 0;
        foreach ($journal->items as $item) {
            $totalDebit += $item->debit;
            $totalCredit += $item->credit;
            $html .= '<tr>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($item->account->code ?? '') . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($item->account->name ?? '') . '</td>';
            $html .= '<td ' . $this->tdStyle('right') . '>' . ($item->debit > 0 ? number_format($item->debit, 2) : '') . '</td>';
            $html .= '<td ' . $this->tdStyle('right') . '>' . ($item->credit > 0 ? number_format($item->credit, 2) : '') . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($item->description ?? '') . '</td>';
            $html .= '</tr>';
        }

        $html .= '<tr style="background-color: #343a40;">';
        $html .= '<td colspan="2" style="color: white; font-weight: bold; font-size: 10px; border: 1px solid #454d55; padding: 6px;">Total</td>';
        $html .= '<td style="color: white; font-weight: bold; text-align: right; font-size: 10px; border: 1px solid #454d55; padding: 6px;">' . number_format($totalDebit, 2) . '</td>';
        $html .= '<td style="color: white; font-weight: bold; text-align: right; font-size: 10px; border: 1px solid #454d55; padding: 6px;">' . number_format($totalCredit, 2) . '</td>';
        $html .= '<td style="border: 1px solid #454d55;"></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $mpdf->WriteHTML($html);
        return response($mpdf->Output($journal->voucher_no . '.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $journal->voucher_no . '.pdf"',
        ]);
    }

    // ========== Customer List PDF ==========
    public function customers()
    {
        $customers = Customer::latest()->get();
        $mpdf = $this->createPdf('Customers List', 'L');

        $html = $this->headerHtml('Customer List', 'Total: ' . $customers->count() . ' customers');
        $html .= '<table ' . $this->tableStyle() . '>';
        $html .= '<tr>';
        $html .= '<th ' . $this->thStyle() . ' width="5%">#</th>';
        $html .= '<th ' . $this->thStyle() . ' width="25%">Name</th>';
        $html .= '<th ' . $this->thStyle() . ' width="20%">Email</th>';
        $html .= '<th ' . $this->thStyle() . ' width="15%">Phone</th>';
        $html .= '<th ' . $this->thStyle() . ' width="20%">Address</th>';
        $html .= '<th ' . $this->thStyle('right') . ' width="15%">Opening Balance</th>';
        $html .= '</tr>';

        foreach ($customers as $i => $c) {
            $bg = $i % 2 == 0 ? '' : ' background-color: #f8f9fa;';
            $html .= '<tr style="' . $bg . '">';
            $html .= '<td ' . $this->tdStyle() . '>' . ($i + 1) . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . $c->name . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . $c->email . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . $c->phone . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($c->address ?? '-') . '</td>';
            $html .= '<td ' . $this->tdStyle('right') . '>' . number_format($c->opening_balance, 2) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $mpdf->WriteHTML($html);
        return response($mpdf->Output('customers.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="customers.pdf"',
        ]);
    }

    // ========== Supplier List PDF ==========
    public function suppliers()
    {
        $suppliers = Supplier::latest()->get();
        $mpdf = $this->createPdf('Suppliers List', 'L');

        $html = $this->headerHtml('Supplier List', 'Total: ' . $suppliers->count() . ' suppliers');
        $html .= '<table ' . $this->tableStyle() . '>';
        $html .= '<tr>';
        $html .= '<th ' . $this->thStyle() . ' width="5%">#</th>';
        $html .= '<th ' . $this->thStyle() . ' width="25%">Name</th>';
        $html .= '<th ' . $this->thStyle() . ' width="20%">Email</th>';
        $html .= '<th ' . $this->thStyle() . ' width="15%">Phone</th>';
        $html .= '<th ' . $this->thStyle() . ' width="20%">Address</th>';
        $html .= '<th ' . $this->thStyle('right') . ' width="15%">Opening Balance</th>';
        $html .= '</tr>';

        foreach ($suppliers as $i => $s) {
            $bg = $i % 2 == 0 ? '' : ' background-color: #f8f9fa;';
            $html .= '<tr style="' . $bg . '">';
            $html .= '<td ' . $this->tdStyle() . '>' . ($i + 1) . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . $s->name . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . $s->email . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . $s->phone . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($s->address ?? '-') . '</td>';
            $html .= '<td ' . $this->tdStyle('right') . '>' . number_format($s->opening_balance, 2) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $mpdf->WriteHTML($html);
        return response($mpdf->Output('suppliers.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="suppliers.pdf"',
        ]);
    }

    // ========== Expenses PDF ==========
    public function expenses()
    {
        $expenses = Expense::with(['account', 'supplier'])->latest('date')->get();
        $mpdf = $this->createPdf('Expenses Report', 'L');

        $html = $this->headerHtml('Expenses Report', 'Total: ' . $expenses->count() . ' expenses | Amount: ' . number_format($expenses->sum('amount'), 2));
        $html .= '<table ' . $this->tableStyle() . '>';
        $html .= '<tr>';
        $html .= '<th ' . $this->thStyle() . ' width="12%">Expense No</th>';
        $html .= '<th ' . $this->thStyle() . ' width="10%">Date</th>';
        $html .= '<th ' . $this->thStyle() . ' width="18%">Account</th>';
        $html .= '<th ' . $this->thStyle() . ' width="15%">Supplier</th>';
        $html .= '<th ' . $this->thStyle('right') . ' width="12%">Amount</th>';
        $html .= '<th ' . $this->thStyle() . ' width="10%">Category</th>';
        $html .= '<th ' . $this->thStyle() . ' width="23%">Description</th>';
        $html .= '</tr>';

        foreach ($expenses as $i => $e) {
            $bg = $i % 2 == 0 ? '' : ' background-color: #f8f9fa;';
            $html .= '<tr style="' . $bg . '">';
            $html .= '<td ' . $this->tdStyle() . '>' . $e->expense_no . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . $e->date->format('d M Y') . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($e->account->name ?? '-') . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($e->supplier->name ?? '-') . '</td>';
            $html .= '<td ' . $this->tdStyle('right') . '>' . number_format($e->amount, 2) . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($e->category ?? '-') . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ($e->description ?? '-') . '</td>';
            $html .= '</tr>';
        }

        $html .= '<tr style="background-color: #343a40;">';
        $html .= '<td colspan="4" style="color: white; font-weight: bold; font-size: 10px; border: 1px solid #454d55; padding: 6px;">Grand Total</td>';
        $html .= '<td style="color: white; font-weight: bold; text-align: right; font-size: 10px; border: 1px solid #454d55; padding: 6px;">' . number_format($expenses->sum('amount'), 2) . '</td>';
        $html .= '<td colspan="2" style="border: 1px solid #454d55;"></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $mpdf->WriteHTML($html);
        return response($mpdf->Output('expenses.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="expenses.pdf"',
        ]);
    }

    // ========== Trial Balance PDF ==========
    public function trialBalance(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $accounts = Account::all()->map(function ($account) use ($date) {
            $items = $account->journalEntryItems()
                ->whereHas('journalEntry', function ($q) use ($date) {
                    $q->where('date', '<=', $date);
                });

            $debit = $items->sum('debit') + (in_array($account->type, ['asset', 'expense']) ? $account->opening_balance : 0);
            $credit = $items->sum('credit') + (in_array($account->type, ['liability', 'equity', 'income']) ? $account->opening_balance : 0);

            return ['code' => $account->code, 'name' => $account->name, 'type' => $account->type, 'debit' => $debit, 'credit' => $credit];
        })->filter(fn($a) => $a['debit'] > 0 || $a['credit'] > 0);

        $totalDebit = $accounts->sum('debit');
        $totalCredit = $accounts->sum('credit');

        $mpdf = $this->createPdf('Trial Balance');

        $html = $this->headerHtml('Trial Balance', 'As of ' . date('d M Y', strtotime($date)));
        $html .= '<table ' . $this->tableStyle() . '>';
        $html .= '<tr>';
        $html .= '<th ' . $this->thStyle() . ' width="15%">Code</th>';
        $html .= '<th ' . $this->thStyle() . ' width="35%">Account Name</th>';
        $html .= '<th ' . $this->thStyle() . ' width="15%">Type</th>';
        $html .= '<th ' . $this->thStyle('right') . ' width="17.5%">Debit</th>';
        $html .= '<th ' . $this->thStyle('right') . ' width="17.5%">Credit</th>';
        $html .= '</tr>';

        foreach ($accounts as $i => $a) {
            $bg = $i % 2 == 0 ? '' : ' background-color: #f8f9fa;';
            $html .= '<tr style="' . $bg . '">';
            $html .= '<td ' . $this->tdStyle() . '>' . $a['code'] . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . $a['name'] . '</td>';
            $html .= '<td ' . $this->tdStyle() . '>' . ucfirst($a['type']) . '</td>';
            $html .= '<td ' . $this->tdStyle('right') . '>' . ($a['debit'] > 0 ? number_format($a['debit'], 2) : '') . '</td>';
            $html .= '<td ' . $this->tdStyle('right') . '>' . ($a['credit'] > 0 ? number_format($a['credit'], 2) : '') . '</td>';
            $html .= '</tr>';
        }

        $html .= '<tr style="background-color: #343a40;">';
        $html .= '<td colspan="3" style="color: white; font-weight: bold; font-size: 10px; border: 1px solid #454d55; padding: 6px;">Total</td>';
        $html .= '<td style="color: white; font-weight: bold; text-align: right; font-size: 10px; border: 1px solid #454d55; padding: 6px;">' . number_format($totalDebit, 2) . '</td>';
        $html .= '<td style="color: white; font-weight: bold; text-align: right; font-size: 10px; border: 1px solid #454d55; padding: 6px;">' . number_format($totalCredit, 2) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $mpdf->WriteHTML($html);
        return response($mpdf->Output('trial-balance.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="trial-balance.pdf"',
        ]);
    }

    // ========== Income Statement PDF ==========
    public function incomeStatement(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $getAccounts = function ($type) use ($startDate, $endDate) {
            return Account::where('type', $type)->get()->map(function ($account) use ($startDate, $endDate, $type) {
                $balance = $account->journalEntryItems()
                    ->whereHas('journalEntry', fn($q) => $q->whereBetween('date', [$startDate, $endDate]));
                $amount = $type == 'income'
                    ? $balance->sum('credit') - $balance->sum('debit')
                    : $balance->sum('debit') - $balance->sum('credit');
                return ['name' => $account->name, 'amount' => $amount];
            })->filter(fn($a) => $a['amount'] != 0);
        };

        $incomeAccounts = $getAccounts('income');
        $expenseAccounts = $getAccounts('expense');
        $totalIncome = $incomeAccounts->sum('amount');
        $totalExpenses = $expenseAccounts->sum('amount');
        $netIncome = $totalIncome - $totalExpenses;

        $mpdf = $this->createPdf('Income Statement');

        $html = $this->headerHtml('Income Statement (Profit & Loss)', date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate)));

        // Income
        $html .= '<h3 style="color: #28a745;">Income</h3>';
        $html .= '<table ' . $this->tableStyle() . '>';
        $html .= '<tr><th ' . $this->thStyle() . ' width="70%">Account</th><th ' . $this->thStyle('right') . ' width="30%">Amount</th></tr>';
        foreach ($incomeAccounts as $a) {
            $html .= '<tr><td ' . $this->tdStyle() . '>' . $a['name'] . '</td><td ' . $this->tdStyle('right') . '>' . number_format($a['amount'], 2) . '</td></tr>';
        }
        $html .= '<tr style="background-color: #d4edda;"><td style="font-weight: bold; padding: 6px; border: 1px solid #dee2e6; font-size: 10px;">Total Income</td><td style="font-weight: bold; text-align: right; padding: 6px; border: 1px solid #dee2e6; font-size: 10px;">' . number_format($totalIncome, 2) . '</td></tr>';
        $html .= '</table><br>';

        // Expenses
        $html .= '<h3 style="color: #dc3545;">Expenses</h3>';
        $html .= '<table ' . $this->tableStyle() . '>';
        $html .= '<tr><th ' . $this->thStyle() . ' width="70%">Account</th><th ' . $this->thStyle('right') . ' width="30%">Amount</th></tr>';
        foreach ($expenseAccounts as $a) {
            $html .= '<tr><td ' . $this->tdStyle() . '>' . $a['name'] . '</td><td ' . $this->tdStyle('right') . '>' . number_format($a['amount'], 2) . '</td></tr>';
        }
        $html .= '<tr style="background-color: #f8d7da;"><td style="font-weight: bold; padding: 6px; border: 1px solid #dee2e6; font-size: 10px;">Total Expenses</td><td style="font-weight: bold; text-align: right; padding: 6px; border: 1px solid #dee2e6; font-size: 10px;">' . number_format($totalExpenses, 2) . '</td></tr>';
        $html .= '</table><br>';

        // Net Income
        $color = $netIncome >= 0 ? '#28a745' : '#dc3545';
        $html .= '<table width="100%"><tr><td style="background-color: #343a40; color: white; font-weight: bold; font-size: 13px; padding: 10px;">Net Income: <span style="color: ' . $color . ';">' . number_format($netIncome, 2) . '</span></td></tr></table>';

        $mpdf->WriteHTML($html);
        return response($mpdf->Output('income-statement.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="income-statement.pdf"',
        ]);
    }

    // ========== Balance Sheet PDF ==========
    public function balanceSheet(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $getAccountsByType = function ($type) use ($date) {
            return Account::where('type', $type)->get()->map(function ($account) use ($date) {
                $items = $account->journalEntryItems()
                    ->whereHas('journalEntry', fn($q) => $q->where('date', '<=', $date));
                $debit = $items->sum('debit');
                $credit = $items->sum('credit');
                $balance = in_array($account->type, ['asset', 'expense'])
                    ? $account->opening_balance + $debit - $credit
                    : $account->opening_balance + $credit - $debit;
                return ['name' => $account->name, 'balance' => $balance];
            })->filter(fn($a) => $a['balance'] != 0);
        };

        $assets = $getAccountsByType('asset');
        $liabilities = $getAccountsByType('liability');
        $equity = $getAccountsByType('equity');
        $totalAssets = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalEquity = $equity->sum('balance');

        $mpdf = $this->createPdf('Balance Sheet');

        $html = $this->headerHtml('Balance Sheet', 'As of ' . date('d M Y', strtotime($date)));

        $sections = [
            ['title' => 'Assets', 'data' => $assets, 'total' => $totalAssets, 'color' => '#007bff', 'bg' => '#cce5ff'],
            ['title' => 'Liabilities', 'data' => $liabilities, 'total' => $totalLiabilities, 'color' => '#ffc107', 'bg' => '#fff3cd'],
            ['title' => 'Equity', 'data' => $equity, 'total' => $totalEquity, 'color' => '#6f42c1', 'bg' => '#e2d9f3'],
        ];

        foreach ($sections as $section) {
            $html .= '<h3 style="color: ' . $section['color'] . ';">' . $section['title'] . '</h3>';
            $html .= '<table ' . $this->tableStyle() . '>';
            $html .= '<tr><th ' . $this->thStyle() . ' width="70%">Account</th><th ' . $this->thStyle('right') . ' width="30%">Balance</th></tr>';
            foreach ($section['data'] as $a) {
                $html .= '<tr><td ' . $this->tdStyle() . '>' . $a['name'] . '</td><td ' . $this->tdStyle('right') . '>' . number_format($a['balance'], 2) . '</td></tr>';
            }
            $html .= '<tr style="background-color: ' . $section['bg'] . ';"><td style="font-weight: bold; padding: 6px; border: 1px solid #dee2e6; font-size: 10px;">Total ' . $section['title'] . '</td><td style="font-weight: bold; text-align: right; padding: 6px; border: 1px solid #dee2e6; font-size: 10px;">' . number_format($section['total'], 2) . '</td></tr>';
            $html .= '</table><br>';
        }

        $html .= '<table width="100%"><tr><td style="background-color: #343a40; color: white; font-weight: bold; font-size: 13px; padding: 10px;">Total Liabilities + Equity: ' . number_format($totalLiabilities + $totalEquity, 2) . '</td></tr></table>';

        $mpdf->WriteHTML($html);
        return response($mpdf->Output('balance-sheet.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="balance-sheet.pdf"',
        ]);
    }
}
