@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-book mr-2"></i>New Journal Entry</h1></div>
            <div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('journals.index') }}">Journal Entries</a></li><li class="breadcrumb-item active">New</li></ol></div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('journals.store') }}" method="POST">
            @csrf

            {{-- Header --}}
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">Voucher Details</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Date <span class="text-danger">*</span></label><input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Voucher Type <span class="text-danger">*</span></label>
                            <select name="voucher_type" class="form-control" required>
                                <option value="">Select Type</option>
                                @foreach(['journal','receipt','payment','contra','sales','purchase'] as $vt)
                                <option value="{{ $vt }}" {{ old('voucher_type')==$vt?'selected':'' }}>{{ ucfirst($vt) }}</option>
                                @endforeach
                            </select>
                        </div></div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="form-group"><label>Narration</label><textarea name="narration" rows="2" class="form-control">{{ old('narration') }}</textarea></div>
                </div>
            </div>

            {{-- Line Items --}}
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-list mr-1"></i> Line Items</h3></div>
                <div class="card-body p-0">
                    <table class="table table-bordered m-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Account</th>
                                <th width="170">Debit (&#2547;)</th>
                                <th width="170">Credit (&#2547;)</th>
                                <th>Description</th>
                                <th width="60" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-tbody">
                            @for($i = 0; $i < 2; $i++)
                            <tr>
                                <td><select name="items[{{ $i }}][account_id]" class="form-control form-control-sm" required><option value="">Select Account</option>@foreach($accounts as $account)<option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>@endforeach</select></td>
                                <td><input type="number" step="0.01" name="items[{{ $i }}][debit]" class="form-control form-control-sm debit-input" value="0" onchange="calculateTotals()"></td>
                                <td><input type="number" step="0.01" name="items[{{ $i }}][credit]" class="form-control form-control-sm credit-input" value="0" onchange="calculateTotals()"></td>
                                <td><input type="text" name="items[{{ $i }}][description]" class="form-control form-control-sm"></td>
                                <td class="text-center"><button type="button" onclick="this.closest('tr').remove(); calculateTotals();" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td>
                            </tr>
                            @endfor
                        </tbody>
                        <tfoot class="thead-dark">
                            <tr>
                                <th class="text-right">Totals</th>
                                <th id="total-debit">0.00</th>
                                <th id="total-credit">0.00</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button type="button" onclick="addRow()" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Row</button>
                    <div id="balance-indicator"></div>
                </div>
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-1"></i> Save Journal Entry</button>
                <a href="{{ route('journals.index') }}" class="btn btn-default btn-lg ml-2">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
const accounts = @json($accounts->map(fn($a) => ['id'=>$a->id,'code'=>$a->code,'name'=>$a->name]));
let rowIndex = 2;

function buildOpts() {
    let o='<option value="">Select Account</option>';
    accounts.forEach(a => o+='<option value="'+a.id+'">'+a.code+' - '+a.name+'</option>');
    return o;
}

function addRow() {
    const tbody = document.getElementById('items-tbody');
    const tr = document.createElement('tr');
    tr.innerHTML =
        '<td><select name="items['+rowIndex+'][account_id]" class="form-control form-control-sm" required>'+buildOpts()+'</select></td>'+
        '<td><input type="number" step="0.01" name="items['+rowIndex+'][debit]" class="form-control form-control-sm debit-input" value="0" onchange="calculateTotals()"></td>'+
        '<td><input type="number" step="0.01" name="items['+rowIndex+'][credit]" class="form-control form-control-sm credit-input" value="0" onchange="calculateTotals()"></td>'+
        '<td><input type="text" name="items['+rowIndex+'][description]" class="form-control form-control-sm"></td>'+
        '<td class="text-center"><button type="button" onclick="this.closest(\'tr\').remove(); calculateTotals();" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td>';
    tbody.appendChild(tr);
    rowIndex++;
}

function calculateTotals() {
    let d=0,c=0;
    document.querySelectorAll('.debit-input').forEach(i=>d+=parseFloat(i.value)||0);
    document.querySelectorAll('.credit-input').forEach(i=>c+=parseFloat(i.value)||0);
    document.getElementById('total-debit').textContent=d.toFixed(2);
    document.getElementById('total-credit').textContent=c.toFixed(2);
    const ind=document.getElementById('balance-indicator');
    if(d===c && d>0) ind.innerHTML='<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Balanced</span>';
    else if(d>0||c>0) ind.innerHTML='<span class="badge badge-danger"><i class="fas fa-exclamation-triangle mr-1"></i>Difference: '+(d-c).toFixed(2)+'</span>';
    else ind.innerHTML='';
}
</script>
@endpush
