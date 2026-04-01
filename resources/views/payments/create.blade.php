@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-money-check-alt mr-2"></i>Record {{ $type == 'received' ? 'Payment Received' : 'Payment Made' }}</h1></div>
            <div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('payments.index', ['type' => $type]) }}">Payments</a></li><li class="breadcrumb-item active">Record</li></ol></div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row"><div class="col-lg-10">
            <div class="card card-{{ $type == 'received' ? 'success' : 'danger' }} card-outline">
                <div class="card-header"><h3 class="card-title">Payment Details</h3></div>
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4"><div class="form-group"><label for="date">Date <span class="text-danger">*</span></label><input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}">@error('date')<span class="invalid-feedback">{{ $message }}</span>@enderror</div></div>
                            <div class="col-md-4">
                                @if($type == 'received')
                                <div class="form-group"><label for="customer_id">Customer</label><select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror"><option value="">Select Customer</option>@foreach($customers as $customer)<option value="{{ $customer->id }}" {{ old('customer_id')==$customer->id?'selected':'' }}>{{ $customer->name }}</option>@endforeach</select>@error('customer_id')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
                                @else
                                <div class="form-group"><label for="supplier_id">Supplier</label><select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror"><option value="">Select Supplier</option>@foreach($suppliers as $supplier)<option value="{{ $supplier->id }}" {{ old('supplier_id')==$supplier->id?'selected':'' }}>{{ $supplier->name }}</option>@endforeach</select>@error('supplier_id')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
                                @endif
                            </div>
                            <div class="col-md-4"><div class="form-group"><label for="invoice_id">Invoice (Optional)</label><select name="invoice_id" id="invoice_id" class="form-control"><option value="">No Invoice</option>@foreach($invoices as $invoice)<option value="{{ $invoice->id }}" {{ old('invoice_id')==$invoice->id?'selected':'' }}>{{ $invoice->invoice_no }}</option>@endforeach</select></div></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><div class="form-group"><label for="account_id">Account <span class="text-danger">*</span></label><select name="account_id" id="account_id" class="form-control @error('account_id') is-invalid @enderror"><option value="">Select Account</option>@foreach($accounts as $account)<option value="{{ $account->id }}" {{ old('account_id')==$account->id?'selected':'' }}>{{ $account->name }}</option>@endforeach</select>@error('account_id')<span class="invalid-feedback">{{ $message }}</span>@enderror</div></div>
                            <div class="col-md-4"><div class="form-group"><label for="amount">Amount <span class="text-danger">*</span></label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">&#2547;</span></div><input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" step="0.01" min="0">@error('amount')<span class="invalid-feedback">{{ $message }}</span>@enderror</div></div></div>
                            <div class="col-md-4"><div class="form-group"><label for="payment_method">Payment Method</label><select name="payment_method" id="payment_method" class="form-control"><option value="">Select Method</option><option value="cash" {{ old('payment_method')=='cash'?'selected':'' }}>Cash</option><option value="bank_transfer" {{ old('payment_method')=='bank_transfer'?'selected':'' }}>Bank Transfer</option><option value="cheque" {{ old('payment_method')=='cheque'?'selected':'' }}>Cheque</option><option value="card" {{ old('payment_method')=='card'?'selected':'' }}>Card</option></select></div></div>
                        </div>
                        <div class="form-group"><label for="reference">Reference</label><input type="text" name="reference" id="reference" class="form-control" value="{{ old('reference') }}"></div>
                        <div class="form-group"><label for="notes">Notes</label><textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes') }}</textarea></div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-{{ $type == 'received' ? 'success' : 'danger' }}"><i class="fas fa-save mr-1"></i> Record Payment</button>
                        <a href="{{ route('payments.index', ['type' => $type]) }}" class="btn btn-default ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div></div>
    </div>
</section>
@endsection
