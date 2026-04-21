@csrf
<div class="row">
    <div class="col-md-4 form-group">
        <label>Name *</label>
        <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-2 form-group">
        <label>Type *</label>
        <select name="type" class="form-control" required>
            <option value="sales" @selected(old('type', $item->type ?? '') === 'sales')>Sales</option>
            <option value="purchase" @selected(old('type', $item->type ?? '') === 'purchase')>Purchase</option>
        </select>
    </div>
    <div class="col-md-3 form-group">
        <label>Customer</label>
        <select name="customer_id" class="form-control">
            <option value="">— none —</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}" @selected(old('customer_id', $item->customer_id ?? '') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group">
        <label>Supplier</label>
        <select name="supplier_id" class="form-control">
            <option value="">— none —</option>
            @foreach($suppliers as $s)
                <option value="{{ $s->id }}" @selected(old('supplier_id', $item->supplier_id ?? '') == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-3 form-group">
        <label>Frequency *</label>
        <select name="frequency" class="form-control" required>
            @foreach(['daily','weekly','monthly','quarterly','yearly'] as $f)
                <option value="{{ $f }}" @selected(old('frequency', $item->frequency ?? 'monthly') === $f)>{{ ucfirst($f) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group">
        <label>Start Date *</label>
        <input type="date" name="start_date" value="{{ old('start_date', isset($item) ? $item->start_date?->format('Y-m-d') : '') }}" class="form-control" required>
    </div>
    <div class="col-md-3 form-group">
        <label>End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date', isset($item) ? $item->end_date?->format('Y-m-d') : '') }}" class="form-control">
    </div>
    <div class="col-md-3 form-group">
        <label>Branch</label>
        <select name="branch_id" class="form-control">
            <option value="">— none —</option>
            @foreach($branches as $b)
                <option value="{{ $b->id }}" @selected(old('branch_id', $item->branch_id ?? '') == $b->id)>{{ $b->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-3 form-group">
        <label>Tax Rate (%)</label>
        <input type="number" step="0.01" name="tax_rate" value="{{ old('tax_rate', $item->tax_rate ?? 0) }}" class="form-control">
    </div>
    <div class="col-md-3 form-group">
        <label>Discount</label>
        <input type="number" step="0.01" name="discount" value="{{ old('discount', $item->discount ?? 0) }}" class="form-control">
    </div>
    @if(isset($item))
    <div class="col-md-3 form-group">
        <label>Active</label><br>
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active))>
    </div>
    @endif
</div>

<div class="form-group">
    <label>Notes</label>
    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $item->notes ?? '') }}</textarea>
</div>

<h5 class="mt-3">Items</h5>
<table class="table table-sm table-bordered" id="itemsTable">
    <thead>
        <tr>
            <th>Description *</th>
            <th style="width:120px">Quantity *</th>
            <th style="width:140px">Unit Price *</th>
            <th style="width:60px"></th>
        </tr>
    </thead>
    <tbody>
        @php $existing = old('items', isset($item) ? ($item->items ?? []) : [['description'=>'','quantity'=>1,'unit_price'=>0]]) @endphp
        @foreach($existing as $idx => $it)
        <tr>
            <td><input name="items[{{ $idx }}][description]" value="{{ $it['description'] ?? '' }}" class="form-control" required></td>
            <td><input type="number" step="0.01" name="items[{{ $idx }}][quantity]" value="{{ $it['quantity'] ?? 1 }}" class="form-control" required></td>
            <td><input type="number" step="0.01" name="items[{{ $idx }}][unit_price]" value="{{ $it['unit_price'] ?? 0 }}" class="form-control" required></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button></td>
        </tr>
        @endforeach
    </tbody>
</table>
<button type="button" id="addRow" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus mr-1"></i>Add Row</button>

<div class="mt-3">
    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Save</button>
    <a href="{{ route('recurring-invoices.index') }}" class="btn btn-secondary">Cancel</a>
</div>

@push('scripts')
<script>
$(function() {
    let idx = $('#itemsTable tbody tr').length;
    $('#addRow').on('click', function() {
        $('#itemsTable tbody').append(
            `<tr>
                <td><input name="items[${idx}][description]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="items[${idx}][quantity]" value="1" class="form-control" required></td>
                <td><input type="number" step="0.01" name="items[${idx}][unit_price]" value="0" class="form-control" required></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button></td>
            </tr>`
        );
        idx++;
    });
    $(document).on('click', '.remove-row', function() {
        if ($('#itemsTable tbody tr').length > 1) $(this).closest('tr').remove();
    });
});
</script>
@endpush
