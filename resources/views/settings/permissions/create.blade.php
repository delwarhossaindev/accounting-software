@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1><i class="fas fa-plus-circle mr-2"></i>Create Permission</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('settings.permissions.index') }}" class="btn btn-secondary float-sm-right">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Permission Details</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.permissions.store') }}">
                    @csrf

                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" value="{{ old('name') }}" class="form-control" placeholder="e.g. settings.users.view" required>
                    </div>

                    <button class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

