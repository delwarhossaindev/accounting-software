@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-file-invoice mr-2"></i>Create {{ $type == 'sales' ? 'Sales Invoice' : 'Purchase Bill' }}</h1></div>
            <div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('invoices.index', ['type' => $type]) }}">{{ $type == 'sales' ? 'Invoices' : 'Bills' }}</a></li><li class="breadcrumb-item active">Create</li></ol></div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            {{-- Details --}}
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">Invoice Details</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group"><label>Date <span class="text-danger">*</span></label><input type="date" name="date" class="form-control no-select2" value="{{ old('date', date('Y-m-d')) }}" required></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label>Due Date <span class="text-danger">*</span></label><input type="date" name="due_date" class="form-control no-select2" value="{{ old('due_date') }}" required></div>
                        </div>
                        <div class="col-md-3">
                            @if($type == 'sales')
                            <div class="form-group"><label>Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id')==$customer->id?'selected':'' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <div class="form-group"><label>Supplier <span class="text-danger">*</span></label>
                                <select name="supplier_id" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id')==$supplier->id?'selected':'' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label>Branch</label>
                                <select name="branch_id" class="form-control">
                                    <option value="">— No Branch —</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id || ($branch->is_head_office && !old('branch_id')) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label>P.O. No</label><input type="text" name="po_no" class="form-control" value="{{ old('po_no') }}" placeholder="Purchase Order ref"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Req. No</label><input type="text" name="req_no" class="form-control" value="{{ old('req_no') }}" placeholder="Requisition no"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Sold By</label><input type="text" name="sold_by" class="form-control" value="{{ old('sold_by', Auth::user()->name) }}" placeholder="Salesperson name"></div>
                        </div>
                    </div>
                    <div class="form-group"><label>Notes</label><textarea name="notes" rows="2" class="form-control" placeholder="Optional notes...">{{ old('notes') }}</textarea></div>
                </div>
            </div>

            {{-- Items --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> Items</h3>
                    @if($type == 'sales')
                        <span class="badge badge-info float-right"><i class="fas fa-info-circle mr-1"></i> Selecting a product will auto-reduce stock</span>
                    @else
                        <span class="badge badge-success float-right"><i class="fas fa-info-circle mr-1"></i> Selecting a product will auto-increase stock</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered m-0" id="itemsTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="200">Product (optional)</th>
                                <th>Description + Serial No.</th>
                                <th width="110">Warranty</th>
                                <th width="80">Qty</th>
                                <th width="120">Unit Price</th>
                                <th width="120">Amount</th>
                                <th width="150">Account</th>
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

            {{-- Totals --}}
            <div class="row">
                <div class="col-md-5 offset-md-7">
                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table m-0">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-right font-weight-bold" id="subtotalDisplay">0.00</td>
                                    <input type="hidden" name="subtotal" id="subtotal" value="0">
                                </tr>
                                <tr>
                                    <td>
                                        <label for="tax_rate_id" class="mb-0">Tax Rate</label>
                                        <select id="tax_rate_id" class="form-control form-control-sm no-select2 mt-1" onchange="applyTaxRate()">
                                            <option value="0">No Tax</option>
                                            @foreach($taxRates as $tr)
                                                <option value="{{ $tr->rate }}" @selected($tr->is_default)>{{ $tr->name }} ({{ $tr->rate }}%)</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="tax" id="tax" class="form-control form-control-sm text-right" value="{{ old('tax', 0) }}" step="0.01" min="0" readonly style="background:#f4f6f9">
                                        <small class="text-muted" id="taxHint"></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="discount" class="mb-0">Discount</label></td>
                                    <td><input type="number" name="discount" id="discount" class="form-control form-control-sm text-right" value="{{ old('discount', 0) }}" step="0.01" min="0" oninput="calculateTotals()"></td>
                                </tr>
                                <tr class="thead-dark">
                                    <th class="text-white">Total</th>
                                    <th class="text-right text-white" id="totalDisplay">0.00</th>
                                    <input type="hidden" name="total" id="total" value="0">
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-1"></i> Save Invoice</button>
                <a href="{{ route('invoices.index', ['type' => $type]) }}" class="btn btn-default btn-lg ml-2">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
let rowIndex = 0;
const accounts = @json($accounts);
const products = @json($products);
const invoiceType = "{{ $type }}";

function addRow() {
    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    tr.id = 'row-' + rowIndex;

    let accOpts = '<option value="">Select</option>';
    accounts.forEach(a => accOpts += '<option value="'+a.id+'">'+a.name+'</option>');

    let prodOpts = '<option value="">— Manual Entry —</option>';
    products.forEach(p => {
        const priceAttr = invoiceType === 'sales' ? p.sale_price : p.purchase_price;
        prodOpts += '<option value="'+p.id+'" data-name="'+p.name+'" data-price="'+priceAttr+'" data-stock="'+p.current_stock+'" data-unit="'+p.unit+'">'
                   +p.sku+' — '+p.name+' ('+p.current_stock+' '+p.unit+')</option>';
    });

    tr.innerHTML =
        '<td><select name="items['+rowIndex+'][product_id]" class="form-control form-control-sm no-select2" onchange="fillProduct(this, '+rowIndex+')">'+prodOpts+'</select>'+
          '<small class="text-muted stock-hint" style="display:block;"></small></td>'+
        '<td><input type="text" name="items['+rowIndex+'][description]" class="form-control form-control-sm item-desc mb-1" placeholder="Item description" required>'+
          '<input type="text" name="items['+rowIndex+'][serial_no]" class="form-control form-control-sm item-serial" placeholder="SN: (optional)" style="font-size: 11px;"></td>'+
        '<td><input type="text" name="items['+rowIndex+'][warranty]" class="form-control form-control-sm" placeholder="e.g. 12 Months"></td>'+
        '<td><input type="number" name="items['+rowIndex+'][quantity]" class="form-control form-control-sm item-qty" value="1" min="0" step="any" oninput="calcRow('+rowIndex+')" required></td>'+
        '<td><input type="number" name="items['+rowIndex+'][unit_price]" class="form-control form-control-sm item-price" value="0" min="0" step="0.01" oninput="calcRow('+rowIndex+')" required></td>'+
        '<td><input type="number" name="items['+rowIndex+'][amount]" class="form-control form-control-sm item-amount" value="0.00" readonly style="background:#f4f6f9"></td>'+
        '<td><select name="items['+rowIndex+'][account_id]" class="form-control form-control-sm no-select2" required>'+accOpts+'</select></td>'+
        '<td class="text-center"><button type="button" onclick="removeRow('+rowIndex+')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td>';
    tbody.appendChild(tr);
    rowIndex++;
}

function fillProduct(select, i) {
    const row = document.getElementById('row-'+i);
    const opt = select.options[select.selectedIndex];
    const hint = row.querySelector('.stock-hint');

    if (!opt.value) {
        hint.textContent = '';
        return;
    }

    const name = opt.getAttribute('data-name');
    const price = parseFloat(opt.getAttribute('data-price')) || 0;
    const stock = parseFloat(opt.getAttribute('data-stock')) || 0;
    const unit = opt.getAttribute('data-unit');

    row.querySelector('.item-desc').value = name;
    row.querySelector('.item-price').value = price.toFixed(2);

    if (invoiceType === 'sales') {
        if (stock <= 0) {
            hint.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Out of stock</span>';
        } else {
            hint.innerHTML = '<span class="text-success"><i class="fas fa-box"></i> In stock: '+stock+' '+unit+'</span>';
        }
    } else {
        hint.innerHTML = '<span class="text-info"><i class="fas fa-box"></i> Current stock: '+stock+' '+unit+'</span>';
    }

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

function applyTaxRate() {
    var s=0; document.querySelectorAll('.item-amount').forEach(e=>s+=parseFloat(e.value)||0);
    var rate = parseFloat(document.getElementById('tax_rate_id').value) || 0;
    var tax = s * rate / 100;
    document.getElementById('tax').value = tax.toFixed(2);
    document.getElementById('taxHint').textContent = rate > 0 ? (rate + '% of ' + s.toFixed(2)) : '';
    calculateTotals();
}

function calculateTotals() {
    var s=0; document.querySelectorAll('.item-amount').forEach(e=>s+=parseFloat(e.value)||0);
    // Recalculate tax from current subtotal
    var rate = parseFloat(document.getElementById('tax_rate_id').value) || 0;
    var t = s * rate / 100;
    document.getElementById('tax').value = t.toFixed(2);
    if (rate > 0) document.getElementById('taxHint').textContent = rate + '% of ' + s.toFixed(2);
    var d=parseFloat(document.getElementById('discount').value)||0;
    document.getElementById('subtotal').value=s.toFixed(2);
    document.getElementById('subtotalDisplay').textContent=s.toFixed(2);
    document.getElementById('total').value=(s+t-d).toFixed(2);
    document.getElementById('totalDisplay').textContent=(s+t-d).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function(){ addRow(); applyTaxRate(); });
</script>
@endpush
