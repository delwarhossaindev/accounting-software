<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'received');
        $payments = Payment::with(['customer', 'supplier', 'account', 'invoice'])
            ->where('type', $type)
            ->latest('date')
            ->get();

        return view('payments.index', compact('payments', 'type'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'received');
        $customers = Customer::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();
        $accounts = Account::where('is_active', true)->whereIn('type', ['asset'])->orderBy('code')->get();
        $invoices = Invoice::where('status', '!=', 'paid')->where('status', '!=', 'cancelled')->get();
        $paymentNo = Payment::generatePaymentNo($type);

        return view('payments.create', compact('type', 'customers', 'suppliers', 'accounts', 'invoices', 'paymentNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:received,made',
            'date' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $payment = Payment::create([
                'payment_no' => Payment::generatePaymentNo($validated['type']),
                'type' => $validated['type'],
                'date' => $validated['date'],
                'customer_id' => $validated['customer_id'],
                'supplier_id' => $validated['supplier_id'],
                'invoice_id' => $validated['invoice_id'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference' => $validated['reference'],
                'notes' => $validated['notes'],
                'user_id' => auth()->id(),
            ]);

            if ($validated['invoice_id']) {
                $invoice = Invoice::find($validated['invoice_id']);
                $invoice->paid += $validated['amount'];
                $invoice->due = $invoice->total - $invoice->paid;
                $invoice->status = $invoice->due <= 0 ? 'paid' : 'partial';
                $invoice->save();
            }
        });

        return redirect()->route('payments.index', ['type' => $validated['type']])->with('success', 'Payment recorded successfully.');
    }

    public function destroy(Payment $payment)
    {
        $type = $payment->type;
        DB::transaction(function () use ($payment) {
            if ($payment->invoice_id) {
                $invoice = $payment->invoice;
                $invoice->paid -= $payment->amount;
                $invoice->due = $invoice->total - $invoice->paid;
                $invoice->status = $invoice->due >= $invoice->total ? 'draft' : 'partial';
                $invoice->save();
            }
            $payment->delete();
        });

        return redirect()->route('payments.index', ['type' => $type])->with('success', 'Payment deleted successfully.');
    }
}
