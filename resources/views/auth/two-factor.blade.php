@extends('layouts.guest')

@section('content')
<div class="container" style="max-width: 480px; margin-top: 8vh;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-shield-alt mr-2"></i>Two-Factor Verification</h3>
        </div>
        <div class="card-body">
            <p>We sent a 6-digit code to your registered email. Enter it below to continue.</p>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('2fa.verify') }}">
                @csrf
                <div class="form-group">
                    <label>Verification Code</label>
                    <input type="text" name="code" inputmode="numeric" autofocus class="form-control text-center" style="font-size:1.3rem; letter-spacing: 0.4em;" required maxlength="6">
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-check mr-1"></i>Verify</button>
            </form>

            <form method="POST" action="{{ route('2fa.resend') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-link btn-block"><i class="fas fa-redo mr-1"></i>Resend Code</button>
            </form>
        </div>
    </div>
</div>
@endsection
