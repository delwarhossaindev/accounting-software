@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-user-edit mr-2"></i>Edit User</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('settings.users.index') }}" class="btn btn-secondary float-sm-right">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">User Details</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.users.update', $user) }}">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>New Password <small class="text-muted">(leave blank to keep)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Roles</label>
                        <select name="roles[]" class="form-control" multiple>
                            @php
                                $selected = collect(old('roles', $user->roles->pluck('name')->all()));
                            @endphp
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" @selected($selected->contains($role->name))>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Hold Ctrl (Windows) to select multiple roles.</small>
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

