@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-file-signature mr-2"></i>Create Quotation</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Quotations</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('quotations.store') }}" method="POST">
            @csrf

            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">Quotation Details</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group"><label>Date <span class="text-danger">*</span></label><input type="date" name="date" class="form-control no-select2" value="{{ old('date', date('Y-m-d')) }}" required></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label>Valid Until</label><input type="date" name="valid_until" class="form-control no-select2" value="{{ old('valid_until', date('Y-m-d', strtotime('+30 days'))) }}"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label>Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id')==$customer->id?'selected':'' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                    <div class="form-group"><label>Subject</label><input type="text" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="e.g. CCTV Installation Quote"></div>
                    <div class="form-group"><label>Notes</label><textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-list mr-1"></i> Items</h3></div>
                <div class="card-body p-0">
                    <table class="table table-bordered m-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="200">Product (optional)</th>
                                <th>Description</th>
                                <th width="110">Warranty</th>
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
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-file-contract mr-1"></i> Terms & Conditions</h3></div>
                        <div class="card-body">
                            <textarea name="terms" rows="5" class="form-control" placeholder="Payment terms, delivery schedule, etc.">{{ old('terms') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table m-0">
                                <tr><td>Subtotal</td><td class="text-right font-weight-bold" id="subtotalDisplay">0.00</td></tr>
                                <tr>
                                    <td>
                                        <label class="mb-0">Tax Rate</label>
                                        <select id="tax_rate_id" class="form-control form-control-sm no-select2 mt-1" onchange="calculateTotals()">
                                            <option value="0">No Tax</option>
                                            @foreach($taxRates as $tr)
                                                <option value="{{ $tr->rate }}" @selected($tr->is_default)>{{ $tr->name }} ({{ $tr->rate }}%)</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        <input type="number" name="tax" id="tax" class="form-control form-control-sm text-right" value="0" step="0.01" readonly style="background:#f4f6f9">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="mb-0">Discount</label></td>
                                    <td><input type="number" name="discount" id="discount" class="form-control form-control-sm text-right" value="0" step="0.01" min="0" oninput="calculateTotals()"></td>
                                </tr>
                                <tr class="thead-dark">
                                    <th class="text-white">Total</th>
                                    <th class="text-right text-white" id="totalDisplay">0.00</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-1"></i> Save Quotation</button>
                <a href="{{ route('quotations.index') }}" class="btn btn-default btn-lg ml-2">Cancel</a>
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

    let prodOpts = '<option value="">— Manual Entry —</option>';
    products.forEach(p => {
        prodOpts += '<option value="'+p.id+'" data-name="'+p.name+'" data-price="'+p.sale_price+'">'+p.sku+' — '+p.name+'</option>';
    });

    tr.innerHTML =
        '<td><select name="items['+rowIndex+'][product_id]" class="form-control form-control-sm no-select2" onchange="fillProduct(this, '+rowIndex+')">'+prodOpts+'</select></td>'+
        '<td><input type="text" name="items['+rowIndex+'][description]" class="form-control form-control-sm item-desc" required></td>'+
        '<td><input type="text" name="items['+rowIndex+'][warranty]" class="form-control form-control-sm" placeholder="e.g. 12 Months"></td>'+
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
    var rate = parseFloat(document.getElementById('tax_rate_id').value) || 0;
    var t = s * rate / 100;
    document.getElementById('tax').value = t.toFixed(2);
    var d=parseFloat(document.getElementById('discount').value)||0;
    document.getElementById('subtotalDisplay').textContent=s.toFixed(2);
    document.getElementById('totalDisplay').textContent=(s+t-d).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function(){ addRow(); calculateTotals(); });
</script>
@endpush
