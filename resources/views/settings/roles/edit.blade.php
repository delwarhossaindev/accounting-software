@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-edit mr-2"></i>Edit Role</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('settings.roles.index') }}" class="btn btn-secondary float-sm-right">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Role Details</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.roles.update', $role) }}">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" value="{{ old('name', $role->name) }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Permissions</label>
                        @php
                            $selected = collect(old('permissions', $role->permissions->pluck('name')->all()));
                        @endphp
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-4">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                            @checked($selected->contains($permission->name))>
                                        <label class="custom-control-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

