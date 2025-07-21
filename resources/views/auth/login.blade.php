<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        (function() {
        // Get saved theme from localStorage or detect system preference
        const savedTheme = localStorage.getItem('theme');
        let theme = 'light'; // default
        
        if (savedTheme) {
            theme = savedTheme;
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            theme = 'dark';
        }
        
        // Apply theme immediately to prevent flash
        document.documentElement.setAttribute('data-theme', theme);
        document.body = document.body || document.createElement('body');
        document.body.setAttribute('data-theme', theme);
        
        // Also set a class on html for immediate CSS targeting
        document.documentElement.className = theme + '-theme';
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Admin Login CSS File -->
    <link href="{{ asset('assets/css/adminlogin.css') }}" rel="stylesheet">
</head>
<body>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <!-- Logo on the left -->
                    <a href="/">
                        <img src="{{ asset('assets/img/favicon.ico') }}" alt="Lincoln Logo" style="height: 70px; width: 150px; border-radius: 10px;">
                    </a>

                    <!-- Title in the center -->
                    <div class="mx-auto text-center">
                        <i class="fas fa-user-shield me-2"></i>
                        {{ __('Admin Login') }}
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>{{ __('Email Address') }}
                            </label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autofocus
                                   placeholder="Enter your email address">

                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>{{ __('Password') }}
                            </label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required
                                   placeholder="Enter your password">

                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger" id="loginBtn">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="mt-3 text-center">
                                <a href="{{ route('password.request') }}">
                                    <i class="fas fa-key me-1"></i>{{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="mt-3 text-center">
                <p class="mb-0">Are you a student? 
                    <a href="{{ route('student.login') }}">
                        <i class="fas fa-graduation-cap me-1"></i>Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to login button
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        
        loginForm.addEventListener('submit', function() {
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<span class="me-2"></span>Logging in...';
        });
    });
</script>

</body>
</html>