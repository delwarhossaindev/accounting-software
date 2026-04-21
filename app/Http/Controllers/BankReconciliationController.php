<?php

namespace App\Http\Controllers;

use App\Models\BankStatementLine;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\BankReconciliationRepositoryInterface;
use App\Repositories\Contracts\BankStatementLineRepositoryInterface;
use App\Repositories\Contracts\JournalEntryRepositoryInterface;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;

class BankReconciliationController extends Controller
{
    public function __construct(
        private BankStatementLineRepositoryInterface $lines,
        private BankReconciliationRepositoryInterface $reconciliations,
        private AccountRepositoryInterface $accounts,
        private JournalEntryRepositoryInterface $journals,
    ) {}

    public function index(Request $request)
    {
        $bankAccounts = $this->accounts->query()
            ->whereIn('code', ['1001', '1002'])
            ->get();

        $accountId = $request->get('account_id') ?? $bankAccounts->first()?->id;
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date', now()->toDateString());

        $lines = $this->lines->forAccountPeriod((int) $accountId, $startDate, $endDate);

        $bookMovements = JournalEntryItem::with('journalEntry')
            ->whereHas('journalEntry', fn($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->where('account_id', $accountId)
            ->get();

        $bookDebit  = $bookMovements->sum('debit');
        $bookCredit = $bookMovements->sum('credit');
        $bookBalance = $bookDebit - $bookCredit;

        $stmtIn  = $lines->sum('credit');
        $stmtOut = $lines->sum('debit');
        $stmtNet = $stmtIn - $stmtOut;

        $matchedCount   = $lines->where('status', 'matched')->count();
        $unmatchedCount = $lines->where('status', 'unmatched')->count();

        $history = $this->reconciliations->historyForAccount((int) $accountId);

        return view('bank-reconciliation.index', compact(
            'bankAccounts', 'accountId', 'startDate', 'endDate',
            'lines', 'bookBalance', 'bookDebit', 'bookCredit',
            'stmtIn', 'stmtOut', 'stmtNet',
            'matchedCount', 'unmatchedCount', 'history'
        ));
    }

    public function storeLine(Request $request)
    {
        $data = $request->validate([
            'account_id'  => 'required|exists:accounts,id',
            'date'        => 'required|date',
            'description' => 'required|string',
            'reference'   => 'nullable|string',
            'debit'       => 'nullable|numeric|min:0',
            'credit'      => 'nullable|numeric|min:0',
        ]);
        $data['user_id'] = auth()->id();
        $data['debit'] = $data['debit'] ?? 0;
        $data['credit'] = $data['credit'] ?? 0;

        $this->lines->create($data);

        return back()->with('success', 'Statement line added.');
    }

    public function importStatement(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'file'       => 'required|file|max:5120',
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map(fn($h) => strtolower(trim($h)), $header);

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $assoc = array_combine($header, array_pad($row, count($header), null));
            if (empty($assoc['date'])) continue;

            $this->lines->create([
                'account_id'  => $request->account_id,
                'date'        => $assoc['date'],
                'description' => $assoc['description'] ?? 'Imported',
                'reference'   => $assoc['reference'] ?? null,
                'debit'       => (float)($assoc['debit'] ?? 0),
                'credit'      => (float)($assoc['credit'] ?? 0),
                'balance'     => isset($assoc['balance']) ? (float)$assoc['balance'] : null,
                'user_id'     => auth()->id(),
            ]);
            $count++;
        }
        fclose($handle);

        return back()->with('success', "Imported {$count} line(s).");
    }

    public function match(Request $request, BankStatementLine $line)
    {
        $request->validate([
            'journal_entry_id' => 'nullable|exists:journal_entries,id',
        ]);

        $this->lines->update($line, [
            'journal_entry_id' => $request->journal_entry_id,
            'status' => $request->journal_entry_id ? 'matched' : 'unmatched',
        ]);

        return back()->with('success', 'Line updated.');
    }

    public function ignore(BankStatementLine $line)
    {
        $this->lines->update($line, ['status' => 'ignored']);
        return back()->with('success', 'Line ignored.');
    }

    public function deleteLine(BankStatementLine $line)
    {
        $this->lines->delete($line);
        return back()->with('success', 'Line removed.');
    }

    public function finalize(Request $request)
    {
        $data = $request->validate([
            'account_id'        => 'required|exists:accounts,id',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date',
            'statement_opening' => 'required|numeric',
            'statement_closing' => 'required|numeric',
            'book_balance'      => 'required|numeric',
            'notes'             => 'nullable|string',
        ]);
        $data['difference'] = $data['statement_closing'] - $data['book_balance'];
        $data['status'] = 'completed';
        $data['user_id'] = auth()->id();

        $this->reconciliations->create($data);

        return back()->with('success', 'Reconciliation finalized.');
    }
}
