@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-eye mr-2"></i>Audit Log Details</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('audit-logs.index') }}">Audit Log</a></li>
                    <li class="breadcrumb-item active">#{{ $auditLog->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-info card-outline">
            <div class="card-header">
                @php
                    $badgeClass = match($auditLog->action) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'secondary',
                    };
                @endphp
                <h3 class="card-title">
                    <span class="badge badge-{{ $badgeClass }}">{{ strtoupper($auditLog->action) }}</span>
                    {{ $auditLog->description }}
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th width="200">Time:</th><td>{{ $auditLog->created_at->format('d M Y, h:i:s A') }}</td></tr>
                    <tr><th>User:</th><td>{{ $auditLog->user?->name ?? 'System' }} ({{ $auditLog->user?->email ?? '—' }})</td></tr>
                    <tr><th>Action:</th><td>{{ ucfirst($auditLog->action) }}</td></tr>
                    <tr><th>Model Type:</th><td>{{ $auditLog->model_type }} #{{ $auditLog->model_id }}</td></tr>
                    <tr><th>IP Address:</th><td><code>{{ $auditLog->ip_address }}</code></td></tr>
                    <tr><th>User Agent:</th><td><small class="text-muted">{{ $auditLog->user_agent }}</small></td></tr>
                </table>

                @if($auditLog->old_values || $auditLog->new_values)
                <hr>
                <h5 class="mb-3">Changes</h5>
                <div class="row">
                    @if($auditLog->old_values)
                    <div class="col-md-6">
                        <h6 class="text-danger"><i class="fas fa-minus-circle mr-1"></i> Old Values</h6>
                        <pre class="bg-light p-3 rounded" style="font-size: 12px;">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                    @endif
                    @if($auditLog->new_values)
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="fas fa-plus-circle mr-1"></i> New Values</h6>
                        <pre class="bg-light p-3 rounded" style="font-size: 12px;">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('audit-logs.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Back</a>
            </div>
        </div>
    </div>
</section>
@endsection
