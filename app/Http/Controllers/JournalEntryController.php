<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\JournalEntryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function __construct(
        private JournalEntryRepositoryInterface $journals,
        private AccountRepositoryInterface $accounts,
    ) {}

    public function index()
    {
        $entries = $this->journals->query()
            ->with('items.account', 'user')
            ->latest('date')
            ->get();
        return view('journals.index', compact('entries'));
    }

    public function create()
    {
        $accounts = $this->accounts->activeOrdered();
        $voucherNo = JournalEntry::generateVoucherNo('journal');
        return view('journals.create', compact('accounts', 'voucherNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'voucher_type' => 'required|in:journal,receipt,payment,contra,sales,purchase',
            'narration' => 'nullable|string',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'nullable|numeric|min:0',
            'items.*.credit' => 'nullable|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        $totalDebit = collect($validated['items'])->sum('debit');
        $totalCredit = collect($validated['items'])->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withErrors(['items' => 'Total debit must equal total credit.'])->withInput();
        }

        DB::transaction(function () use ($validated, $totalDebit) {
            $entry = $this->journals->create([
                'voucher_no' => JournalEntry::generateVoucherNo($validated['voucher_type']),
                'date' => $validated['date'],
                'narration' => $validated['narration'],
                'voucher_type' => $validated['voucher_type'],
                'user_id' => auth()->id(),
                'total_amount' => $totalDebit,
            ]);

            foreach ($validated['items'] as $item) {
                $entry->items()->create([
                    'account_id' => $item['account_id'],
                    'debit' => $item['debit'] ?? 0,
                    'credit' => $item['credit'] ?? 0,
                    'description' => $item['description'] ?? null,
                ]);
            }
        });

        return redirect()->route('journals.index')->with('success', 'Journal entry created successfully.');
    }

    public function show(JournalEntry $journal)
    {
        $journal->load('items.account', 'user');
        return view('journals.show', compact('journal'));
    }

    public function destroy(JournalEntry $journal)
    {
        $this->journals->delete($journal);
        return redirect()->route('journals.index')->with('success', 'Journal entry deleted successfully.');
    }
}
