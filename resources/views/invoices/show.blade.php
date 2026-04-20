@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-file-invoice mr-2"></i>{{ $invoice->invoice_no }}</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    @if($invoice->type === 'sales' && $invoice->customer?->email)
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#emailModal">
                        <i class="fas fa-envelope mr-1"></i> Email to Customer
                    </button>
                    @endif
                    <a href="{{ route('pdf.invoice', $invoice) }}" class="btn btn-secondary btn-sm" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </a>
                    <a href="{{ route('invoices.index', ['type' => $invoice->type]) }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Invoice Details --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Invoice Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <strong class="text-muted d-block text-sm">Type</strong>
                        <span class="badge badge-{{ $invoice->type == 'sales' ? 'primary' : 'warning' }} text-capitalize">{{ $invoice->type }}</span>
                    </div>
                    <div class="col-md-2">
                        <strong class="text-muted d-block text-sm">Date</strong>
                        {{ $invoice->date->format('d M Y') }}
                    </div>
                    <div class="col-md-2">
                        <strong class="text-muted d-block text-sm">Due Date</strong>
                        {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong class="text-muted d-block text-sm">{{ $invoice->type == 'sales' ? 'Customer' : 'Supplier' }}</strong>
                        <strong>
                            @if($invoice->type == 'sales')
                                {{ $invoice->customer->name ?? '-' }}
                            @else
                                {{ $invoice->supplier->name ?? '-' }}
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3 text-right">
                        <strong class="text-muted d-block text-sm">Status</strong>
                        @php
                            $badge = match($invoice->status) {
                                'paid' => 'success', 'partial' => 'warning', 'sent' => 'info',
                                'overdue' => 'danger', 'cancelled' => 'dark', default => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $badge }} px-3 py-2" style="font-size: 14px;">{{ ucfirst($invoice->status) }}</span>
                    </div>
                </div>
                @if($invoice->notes)
                <hr>
                <strong class="text-muted text-sm">Notes:</strong>
                <p class="mb-0">{{ $invoice->notes }}</p>
                @endif
            </div>
        </div>

        {{-- Items --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Items</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th width="50%">Description</th>
                            <th class="text-center" width="15%">Quantity</th>
                            <th class="text-right" width="17%">Unit Price (&#2547;)</th>
                            <th class="text-right" width="18%">Amount (&#2547;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Summary --}}
        <div class="row">
            <div class="col-md-6 offset-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calculator mr-1"></i> Summary</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table m-0">
                            <tr>
                                <td>Subtotal</td>
                                <td class="text-right">&#2547; {{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>
                            @if($invoice->tax > 0)
                            <tr>
                                <td>Tax</td>
                                <td class="text-right">&#2547; {{ number_format($invoice->tax, 2) }}</td>
                            </tr>
                            @endif
                            @if($invoice->discount > 0)
                            <tr>
                                <td>Discount</td>
                                <td class="text-right text-danger">- &#2547; {{ number_format($invoice->discount, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="bg-light">
                                <th>Total</th>
                                <th class="text-right">&#2547; {{ number_format($invoice->total, 2) }}</th>
                            </tr>
                            <tr>
                                <td>Paid</td>
                                <td class="text-right text-success font-weight-bold">&#2547; {{ number_format($invoice->paid, 2) }}</td>
                            </tr>
                            <tr class="thead-dark">
                                <th class="text-white">Amount Due</th>
                                <th class="text-right text-white">&#2547; {{ number_format($invoice->due, 2) }}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payments --}}
        @if($invoice->payments->count() > 0)
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-money-check-alt text-success mr-1"></i> Payments Received</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped m-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Method</th>
                            <th class="text-right">Amount (&#2547;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->date->format('d M Y') }}</td>
                            <td>{{ $payment->reference ?? '-' }}</td>
                            <td><span class="badge badge-light text-capitalize">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span></td>
                            <td class="text-right text-success font-weight-bold">&#2547; {{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</section>

{{-- Email Modal --}}
@if($invoice->type === 'sales' && $invoice->customer?->email)
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('invoices.email', $invoice) }}" method="POST">
                @csrf
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white"><i class="fas fa-envelope mr-2"></i>Email Invoice to Customer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Recipient Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ $invoice->customer->email }}" required>
                    </div>
                    <div class="form-group">
                        <label>Additional Message (optional)</label>
                        <textarea name="message" rows="4" class="form-control" placeholder="Thank you for your business...">Dear {{ $invoice->customer->name }},&#10;&#10;Please find the attached invoice for your recent purchase. Kindly process the payment by the due date.&#10;&#10;Thank you!</textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Invoice details will be sent as a formatted HTML email. Draft invoices will automatically be marked as "sent".
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane mr-1"></i> Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
