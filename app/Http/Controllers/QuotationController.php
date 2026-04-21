<?php

namespace App\Http\Controllers;

use App\Mail\QuotationMail;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Repositories\Contracts\BranchRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\QuotationRepositoryInterface;
use App\Repositories\Contracts\TaxRateRepositoryInterface;
use App\Services\QuotationPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class QuotationController extends Controller
{
    public function __construct(
        private QuotationRepositoryInterface $quotations,
        private InvoiceRepositoryInterface $invoices,
        private CustomerRepositoryInterface $customers,
        private ProductRepositoryInterface $products,
        private BranchRepositoryInterface $branches,
        private TaxRateRepositoryInterface $taxRates,
    ) {}

    public function index()
    {
        $quotations = $this->quotations->query()
            ->with('customer', 'branch')
            ->latest('date')
            ->get();
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = $this->customers->active();
        $products = $this->products->active();
        $branches = $this->branches->activeOrdered();
        $taxRates = $this->taxRates->active();
        $quotationNo = Quotation::generateQuotationNo();

        return view('quotations.create', compact('customers', 'products', 'branches', 'taxRates', 'quotationNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'nullable|exists:branches,id',
            'subject' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.warranty' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $validated['tax'] ?? 0;
            $discount = $validated['discount'] ?? 0;
            $total = $subtotal + $tax - $discount;

            $quotation = $this->quotations->create([
                'quotation_no' => Quotation::generateQuotationNo(),
                'date' => $validated['date'],
                'valid_until' => $validated['valid_until'] ?? null,
                'customer_id' => $validated['customer_id'],
                'branch_id' => $validated['branch_id'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
                'user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $quotation->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'warranty' => $item['warranty'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('items.product', 'customer', 'branch', 'convertedInvoice');
        return view('quotations.show', compact('quotation'));
    }

    public function destroy(Quotation $quotation)
    {
        if ($quotation->status === 'converted') {
            return back()->with('error', 'Cannot delete a quotation that has been converted to an invoice.');
        }

        $this->quotations->delete($quotation);
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
        ]);

        if ($quotation->status === 'converted') {
            return back()->with('error', 'Cannot change status of a converted quotation.');
        }

        $this->quotations->update($quotation, ['status' => $validated['status']]);
        return back()->with('success', 'Status updated successfully.');
    }

    public function sendEmail(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            Mail::to($validated['email'])
                ->send(new QuotationMail($quotation, $validated['message'] ?? ''));

            if ($quotation->status === 'draft') {
                $this->quotations->update($quotation, ['status' => 'sent']);
            }

            return back()->with('success', 'Quotation emailed to ' . $validated['email']);
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function whatsappLink(Quotation $quotation)
    {
        $phone = $this->normalizePhone($quotation->customer?->phone);
        if (!$phone) {
            return back()->with('error', 'Customer phone number is missing or invalid.');
        }

        $pdfUrl = URL::temporarySignedRoute(
            'quotations.public-pdf',
            now()->addDays(7),
            ['quotation' => $quotation->id]
        );

        $company = \App\Models\CompanySetting::current();
        $customer = $quotation->customer?->name ?? 'Customer';
        $currency = $company->currency_symbol ?? '৳';

        $lines = [
            "Hello {$customer},",
            "",
            "Here is your quotation from *{$company->name}*:",
            "",
            "📄 Quotation: *{$quotation->quotation_no}*",
            "📅 Date: " . $quotation->date->format('d M Y'),
        ];
        if ($quotation->valid_until) {
            $lines[] = "⏳ Valid Until: " . $quotation->valid_until->format('d M Y');
        }
        if ($quotation->subject) {
            $lines[] = "📌 Subject: {$quotation->subject}";
        }
        $lines[] = "💰 Total: {$currency} " . number_format($quotation->total, 2);
        $lines[] = "";
        $lines[] = "View / Download PDF:";
        $lines[] = $pdfUrl;
        $lines[] = "";
        $lines[] = "Please let us know if you have any questions.";
        $lines[] = "";
        $lines[] = "Thank you,";
        $lines[] = $company->name;

        $text = implode("\n", $lines);
        $waUrl = 'https://wa.me/' . $phone . '?text=' . rawurlencode($text);

        if ($quotation->status === 'draft') {
            $this->quotations->update($quotation, ['status' => 'sent']);
        }

        return redirect()->away($waUrl);
    }

    public function publicPdf(Quotation $quotation, QuotationPdfService $service, Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Link expired or invalid.');
        }

        $pdf = $service->render($quotation);
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $service->filename($quotation) . '"',
        ]);
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (!$phone) return null;
        $digits = preg_replace('/\D+/', '', $phone);
        if (!$digits) return null;

        // If starts with 0, assume BD and prefix 880
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = '880' . substr($digits, 1);
        }

        return $digits;
    }

    public function convertToInvoice(Quotation $quotation)
    {
        if ($quotation->status === 'converted') {
            return back()->with('error', 'This quotation has already been converted.');
        }

        $quotation->load('items');

        $invoice = DB::transaction(function () use ($quotation) {
            $invoice = $this->invoices->create([
                'invoice_no' => Invoice::generateInvoiceNo('sales'),
                'type' => 'sales',
                'date' => now(),
                'due_date' => now()->addDays(15),
                'customer_id' => $quotation->customer_id,
                'branch_id' => $quotation->branch_id,
                'subtotal' => $quotation->subtotal,
                'tax' => $quotation->tax,
                'discount' => $quotation->discount,
                'total' => $quotation->total,
                'paid' => 0,
                'due' => $quotation->total,
                'status' => 'draft',
                'notes' => "Converted from Quotation {$quotation->quotation_no}",
                'user_id' => auth()->id(),
            ]);

            foreach ($quotation->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'warranty' => $item->warranty,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'amount' => $item->amount,
                ]);
            }

            $this->quotations->update($quotation, [
                'status' => 'converted',
                'converted_invoice_id' => $invoice->id,
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Quotation converted to invoice.');
    }
}
