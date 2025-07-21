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
    <title>Student Login</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Student Login CSS File -->
    <link href="{{ asset('assets/css/studentlogin.css') }}" rel="stylesheet">
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
                        {{ __('Student Login') }}
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('student.login.post') }}" id="loginForm">
                        @csrf

                        <div class="mb-3">
                            <label for="admission_number" class="form-label">
                                <i class="fas fa-id-card me-2"></i>{{ __('Admission Number') }}
                            </label>
                            <input id="admission_number" type="text" 
                                   class="form-control @error('admission_number') is-invalid @enderror" 
                                   name="admission_number" required autofocus
                                   placeholder="Enter your admission number">

                            @error('admission_number')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label">
                                <i class="fas fa-phone me-2"></i>{{ __('Contact Number') }}
                            </label>
                            <input id="contact_number" type="text" 
                                   class="form-control @error('contact_number') is-invalid @enderror" 
                                   name="contact_number" required
                                   placeholder="Enter your contact number">

                            @error('contact_number')
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
                    </form>
                </div>
            </div>

            <div class="mt-3 text-center">
                <p class="mb-0">Are you an admin? 
                    <a href="{{ route('login') }}">
                        <i class="fas fa-user-shield me-1"></i>Login here
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