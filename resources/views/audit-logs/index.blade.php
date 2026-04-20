@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-clipboard-list mr-2"></i>Audit Log</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Audit Log</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form method="GET" class="form-row">
                    <div class="col-md-3">
                        <select name="action" class="form-control form-control-sm">
                            <option value="">All Actions</option>
                            <option value="created" @selected(request('action') == 'created')>Created</option>
                            <option value="updated" @selected(request('action') == 'updated')>Updated</option>
                            <option value="deleted" @selected(request('action') == 'deleted')>Deleted</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="model_type" class="form-control form-control-sm">
                            <option value="">All Models</option>
                            @foreach($modelTypes as $type)
                                <option value="{{ $type }}" @selected(request('model_type') == $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="user_id" class="form-control form-control-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter mr-1"></i> Filter</button>
                        <a href="{{ route('audit-logs.index') }}" class="btn btn-default btn-sm">Reset</a>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th class="text-center">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td><small>{{ $log->created_at->format('d M Y, h:i A') }}</small></td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td>
                                @php
                                    $badgeClass = match($log->action) {
                                        'created' => 'success',
                                        'updated' => 'warning',
                                        'deleted' => 'danger',
                                        default => 'secondary',
                                    };
                                    $icon = match($log->action) {
                                        'created' => 'fa-plus',
                                        'updated' => 'fa-edit',
                                        'deleted' => 'fa-trash',
                                        default => 'fa-info',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}"><i class="fas {{ $icon }} mr-1"></i>{{ ucfirst($log->action) }}</span>
                            </td>
                            <td><span class="badge badge-light">{{ $log->model_type }} #{{ $log->model_id }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('audit-logs.show', $log) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No audit logs found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
            <div class="card-footer">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
