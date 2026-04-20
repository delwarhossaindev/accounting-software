<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Accounting Software</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Animated shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 20s infinite ease-in-out;
        }
        .shape-1 { width: 400px; height: 400px; background: #6366f1; top: -100px; left: -100px; }
        .shape-2 { width: 300px; height: 300px; background: #ec4899; bottom: -80px; right: -80px; animation-delay: -5s; }
        .shape-3 { width: 250px; height: 250px; background: #10b981; top: 50%; left: 70%; animation-delay: -10s; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            padding: 40px 36px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo .icon-wrap {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
            margin-bottom: 16px;
        }

        .login-logo .icon-wrap i {
            font-size: 32px;
            color: #fff;
        }

        .login-logo h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e293b;
        }

        .login-logo p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 0.9rem;
        }

        .demo-box {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(139, 92, 246, 0.08));
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 22px;
            font-size: 13px;
        }

        .demo-box .demo-title {
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .demo-box .demo-row {
            display: flex;
            gap: 6px;
            color: #475569;
            margin-bottom: 3px;
        }

        .demo-box .demo-row strong { color: #1e293b; min-width: 70px; }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-weight: 500;
            font-size: 0.9rem;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        }

        .row-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            font-size: 0.9rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            cursor: pointer;
        }

        .remember input {
            accent-color: #6366f1;
            width: 16px;
            height: 16px;
        }

        .forgot-link {
            color: #6366f1;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-signin {
            width: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
        }

        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(99, 102, 241, 0.5);
        }

        .btn-signin:active { transform: translateY(0); }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #64748b;
            font-size: 0.9rem;
        }

        .signup-link a {
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
        }

        .signup-link a:hover { text-decoration: underline; }

        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 0.88rem;
        }

        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .alert-danger { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }

        @media (max-width: 480px) {
            .login-card { padding: 30px 24px; }
            .login-logo h1 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <div class="icon-wrap"><i class="fas fa-calculator"></i></div>
                <h1>Welcome Back</h1>
                <p>Sign in to continue to Accounting Software</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="demo-box">
                <div class="demo-title"><i class="fas fa-info-circle"></i> Demo Credentials</div>
                <div class="demo-row"><strong>Email:</strong> admin@admin.com</div>
                <div class="demo-row"><strong>Password:</strong> password</div>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="row-between">
                    <label class="remember">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-signin">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </form>

            <div class="signup-link">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>
        </div>
    </div>
</body>
</html>
