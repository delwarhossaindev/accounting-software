@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1><i class="fas fa-file-csv mr-2"></i>Import Data (CSV)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            Upload a CSV file. The first row must be the header. Download the sample template to get the exact columns.
            Existing rows are matched by <strong>email</strong> (customers/suppliers), <strong>SKU</strong> (products), or <strong>code</strong> (accounts) and will be updated.
        </div>

        <div class="row">
            @foreach([
                'customers' => ['Customers', 'users', 'primary'],
                'suppliers' => ['Suppliers', 'building', 'warning'],
                'products'  => ['Products', 'boxes', 'success'],
                'accounts'  => ['Chart of Accounts', 'calculator', 'info'],
            ] as $key => [$label, $icon, $color])
            <div class="col-md-6 mb-3">
                <div class="card card-outline card-{{ $color }}">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-{{ $icon }} mr-1"></i>{{ $label }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('imports.template', $key) }}" class="btn btn-sm btn-outline-{{ $color }}">
                                <i class="fas fa-download mr-1"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('imports.store', $key) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="file" accept=".csv" class="custom-file-input" required>
                                    <label class="custom-file-label">Choose CSV file...</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-{{ $color }}" type="submit"><i class="fas fa-upload mr-1"></i> Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
$(function(){
    $('.custom-file-input').on('change', function(){
        $(this).next('.custom-file-label').text($(this)[0].files[0]?.name || 'Choose CSV file...');
    });
});
</script>
@endpush
