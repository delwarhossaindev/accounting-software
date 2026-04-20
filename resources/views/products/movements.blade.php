@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-history mr-2"></i>Stock Movements</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $product->name }} ({{ $product->sku }}) — Current Stock:
                    <span class="badge badge-success">{{ rtrim(rtrim(number_format($product->current_stock, 2), '0'), '.') }} {{ $product->unit }}</span>
                </h3>
            </div>
            <div class="card-body">
                <table id="movementsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Unit Price</th>
                            <th>Reference</th>
                            <th>User</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->date->format('d M Y') }}</td>
                            <td>
                                @php
                                    $typeClass = match($movement->type) {
                                        'in' => 'success',
                                        'out' => 'danger',
                                        default => 'info',
                                    };
                                    $typeIcon = match($movement->type) {
                                        'in' => 'fa-arrow-down',
                                        'out' => 'fa-arrow-up',
                                        default => 'fa-sliders-h',
                                    };
                                @endphp
                                <span class="badge badge-{{ $typeClass }}">
                                    <i class="fas {{ $typeIcon }} mr-1"></i>{{ ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td class="text-right font-weight-bold">{{ rtrim(rtrim(number_format($movement->quantity, 2), '0'), '.') }} {{ $product->unit }}</td>
                            <td class="text-right">&#2547; {{ number_format($movement->unit_price, 2) }}</td>
                            <td><span class="badge badge-light">{{ str_replace('_', ' ', $movement->reference_type ?? '—') }}</span></td>
                            <td>{{ $movement->user?->name ?? '—' }}</td>
                            <td class="text-muted">{{ $movement->notes ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No stock movements yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(function(){
    $('#movementsTable').DataTable({ responsive: true, order: [[0, 'desc']] });
});
</script>
@endpush
