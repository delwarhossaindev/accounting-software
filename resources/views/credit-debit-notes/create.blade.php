@extends('layouts.app')

@section('content')
@php
    $label = $type === 'credit' ? 'Credit Note' : 'Debit Note';
    $partyLabel = $type === 'credit' ? 'Customer' : 'Supplier';
    $description = $type === 'credit' ? 'Sales return — goods coming back IN to stock' : 'Purchase return — goods going OUT of stock';
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-plus-circle mr-2"></i>Create {{ $label }}</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('credit-debit-notes.index', ['type' => $type]) }}">{{ $label }}s</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('credit-debit-notes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i> {{ $description }}
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">{{ $label }} Details</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group"><label>Date <span class="text-danger">*</span></label><input type="date" name="date" class="form-control no-select2" value="{{ old('date', date('Y-m-d')) }}" required></div>
                        </div>
                        <div class="col-md-3">
                            @if($type === 'credit')
                            <div class="form-group"><label>Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <div class="form-group"><label>Supplier <span class="text-danger">*</span></label>
                                <select name="supplier_id" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id }}" {{ old('supplier_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label>Against Invoice (optional)</label>
                                <select name="invoice_id" class="form-control">
                                    <option value="">— No Reference —</option>
                                    @foreach($invoices as $inv)
                                        <option value="{{ $inv->id }}" {{ old('invoice_id')==$inv->id?'selected':'' }}>{{ $inv->invoice_no }} — ৳{{ number_format($inv->total, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label>Reason</label>
                                <input type="text" name="reason" class="form-control" value="{{ old('reason') }}" placeholder="e.g. Damaged item, Price adjustment">
                            </div>
                        </div>
                    </div>
                    <div class="form-group"><label>Notes</label><textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-list mr-1"></i> Items Returned</h3></div>
                <div class="card-body p-0">
                    <table class="table table-bordered m-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="200">Product</th>
                                <th>Description</th>
                                <th width="80">Qty</th>
                                <th width="130">Unit Price</th>
                                <th width="130">Amount</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody"></tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="button" onclick="addRow()" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Row</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 offset-md-7">
                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table m-0">
                                <tr><td>Subtotal</td><td class="text-right font-weight-bold" id="subtotalDisplay">0.00</td></tr>
                                <tr><td><label class="mb-0">Tax</label></td><td><input type="number" name="tax" id="tax" class="form-control form-control-sm text-right" value="0" step="0.01" min="0" oninput="calculateTotals()"></td></tr>
                                <tr class="thead-dark"><th class="text-white">Total</th><th class="text-right text-white" id="totalDisplay">0.00</th></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-1"></i> Save {{ $label }}</button>
                <a href="{{ route('credit-debit-notes.index', ['type' => $type]) }}" class="btn btn-default btn-lg ml-2">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
let rowIndex = 0;
const products = @json($products);

function addRow() {
    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    tr.id = 'row-' + rowIndex;

    let prodOpts = '<option value="">— Manual —</option>';
    products.forEach(p => {
        prodOpts += '<option value="'+p.id+'" data-name="'+p.name+'" data-price="'+p.sale_price+'">'+p.sku+' — '+p.name+'</option>';
    });

    tr.innerHTML =
        '<td><select name="items['+rowIndex+'][product_id]" class="form-control form-control-sm no-select2" onchange="fillProduct(this, '+rowIndex+')">'+prodOpts+'</select></td>'+
        '<td><input type="text" name="items['+rowIndex+'][description]" class="form-control form-control-sm item-desc" required></td>'+
        '<td><input type="number" name="items['+rowIndex+'][quantity]" class="form-control form-control-sm item-qty" value="1" min="0" step="any" oninput="calcRow('+rowIndex+')" required></td>'+
        '<td><input type="number" name="items['+rowIndex+'][unit_price]" class="form-control form-control-sm item-price" value="0" min="0" step="0.01" oninput="calcRow('+rowIndex+')" required></td>'+
        '<td><input type="number" class="form-control form-control-sm item-amount" value="0.00" readonly style="background:#f4f6f9"></td>'+
        '<td class="text-center"><button type="button" onclick="removeRow('+rowIndex+')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td>';
    tbody.appendChild(tr);
    rowIndex++;
}

function fillProduct(select, i) {
    const row = document.getElementById('row-'+i);
    const opt = select.options[select.selectedIndex];
    if (!opt.value) return;
    row.querySelector('.item-desc').value = opt.getAttribute('data-name');
    row.querySelector('.item-price').value = parseFloat(opt.getAttribute('data-price')).toFixed(2);
    calcRow(i);
}

function removeRow(i) { var r=document.getElementById('row-'+i); if(r){r.remove(); calculateTotals();} }
function calcRow(i) {
    var r=document.getElementById('row-'+i); if(!r)return;
    var q=parseFloat(r.querySelector('.item-qty').value)||0;
    var p=parseFloat(r.querySelector('.item-price').value)||0;
    r.querySelector('.item-amount').value=(q*p).toFixed(2);
    calculateTotals();
}
function calculateTotals() {
    var s=0; document.querySelectorAll('.item-amount').forEach(e=>s+=parseFloat(e.value)||0);
    var t=parseFloat(document.getElementById('tax').value)||0;
    document.getElementById('subtotalDisplay').textContent=s.toFixed(2);
    document.getElementById('totalDisplay').textContent=(s+t).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function(){ addRow(); });
</script>
@endpush
