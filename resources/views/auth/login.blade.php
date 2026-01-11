<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - Zakat Beneficiaries</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap1.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/themefy_icon/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/font_awesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style1.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/colors/default.css') }}" />
    <style>
        .login-page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .login-logo {
            margin-bottom: 20px;
        }
        .login-logo img {
            max-height: 70px;
            width: auto;
            filter: brightness(0) invert(1);
        }
        .login-header h4 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .login-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-top: 8px;
            margin-bottom: 0;
        }
        .login-body {
            padding: 40px 35px;
        }
        .form-group-custom {
            margin-bottom: 25px;
        }
        .form-group-custom label {
            color: #333;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }
        .form-control-custom {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: #667eea;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-control-custom.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .remember-me {
            display: flex;
            align-items: center;
        }
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }
        .remember-me label {
            color: #666;
            font-size: 14px;
            margin: 0;
            cursor: pointer;
        }
        .forgot-password {
            color: #667eea;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .forgot-password:hover {
            color: #764ba2;
            text-decoration: none;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .alert-custom {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .alert-danger {
            background-color: #fee;
            border-color: #dc3545;
            color: #721c24;
        }
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 16px;
        }
        .input-icon-wrapper .form-control-custom {
            padding-left: 45px;
        }
        .footer-login {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .footer-login p {
            color: #999;
            font-size: 13px;
            margin: 0;
        }
    </style>
</head>
<body class="crm_body_bg">
    <div class="login-page-wrapper">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-logo">
                        <img src="{{ asset('img/gbdoelogo_white.png') }}" alt="Zakat Beneficiaries Logo">
                    </div>
                    <h4>Welcome Back</h4>
                    <p>Sign in to continue to your account</p>
                </div>
                <div class="login-body">
                    @if ($errors->any())
                    <div class="alert-custom alert-danger">
                        <ul class="mb-0" style="padding-left: 20px; margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li style="font-size: 13px;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group-custom">
                            <label for="email">Email Address</label>
                            <div class="input-icon-wrapper">
                                <i class="ti-email input-icon"></i>
                                <input 
                                    type="email" 
                                    id="email"
                                    class="form-control-custom @error('email') is-invalid @enderror" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    placeholder="Enter your email address" 
                                    required 
                                    autofocus
                                >
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group-custom">
                            <label for="password">Password</label>
                            <div class="input-icon-wrapper">
                                <i class="ti-lock input-icon"></i>
                                <input 
                                    type="password" 
                                    id="password"
                                    class="form-control-custom @error('password') is-invalid @enderror" 
                                    name="password" 
                                    placeholder="Enter your password" 
                                    required
                                >
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="remember-forgot">
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                            <a href="#" class="forgot-password">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" class="btn-login">
                            <i class="ti-arrow-right" style="margin-right: 8px;"></i>
                            Sign In
                        </button>
                    </form>
                    
                    <div class="footer-login">
                        <p>Powered By <a href="https://gbit.gov.pk" target="_blank" style="color: #667eea; text-decoration: none;">Information Technology Department GB</a> © {{ date('Y') }} All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html>
