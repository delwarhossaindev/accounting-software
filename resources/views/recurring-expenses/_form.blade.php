@csrf
<div class="row">
    <div class="col-md-4 form-group">
        <label>Name *</label>
        <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4 form-group">
        <label>Expense Account *</label>
        <select name="account_id" class="form-control" required>
            @foreach($accounts as $a)
                <option value="{{ $a->id }}" @selected(old('account_id', $item->account_id ?? '') == $a->id)>{{ $a->code }} — {{ $a->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group">
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
        <label>Amount *</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', $item->amount ?? 0) }}" class="form-control" required>
    </div>
</div>

<div class="row">
    <div class="col-md-4 form-group">
        <label>Category</label>
        <input type="text" name="category" value="{{ old('category', $item->category ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4 form-group">
        <label>Payment Method *</label>
        <select name="payment_method" class="form-control" required>
            @foreach(['cash','bank_transfer','cheque','credit'] as $m)
                <option value="{{ $m }}" @selected(old('payment_method', $item->payment_method ?? 'cash') === $m)>{{ ucfirst(str_replace('_', ' ', $m)) }}</option>
            @endforeach
        </select>
    </div>
    @if(isset($item))
    <div class="col-md-4 form-group">
        <label>Active</label><br>
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active))>
    </div>
    @endif
</div>

<div class="form-group">
    <label>Description</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description', $item->description ?? '') }}</textarea>
</div>

<div>
    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Save</button>
    <a href="{{ route('recurring-expenses.index') }}" class="btn btn-secondary">Cancel</a>
</div>
