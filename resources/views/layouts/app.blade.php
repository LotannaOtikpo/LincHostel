<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
      // IMMEDIATE THEME APPLICATION - Runs before any CSS
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
        
        // Also set a class on html for immediate CSS targeting
        document.documentElement.className = theme + '-theme';
        
        // Store for later use
        window.__INITIAL_THEME__ = theme;
      })();
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>LincHostel | Admin Dashboard</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap 5.3.2 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Admin Dashboard CSS File -->
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="fas fa-building me-2"></i>LincHostel
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('rooms.index') }}">
                                        <i class="fas fa-door-open me-1"></i>Rooms
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('students.index') }}">
                                        <i class="fas fa-user-graduate me-1"></i>Students
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('payments.index') }}">
                                        <i class="fas fa-credit-card me-1"></i>Payments
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('complaints.index') }}">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Complaints
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('visitors.index') }}">
                                    <i class="fas fa-users me-1"></i>Visitors
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i>{{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i>{{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- Dark Mode Toggle -->
                            <li class="nav-item me-3">
                                <button class="theme-toggle nav-link border-0 bg-transparent" id="themeToggle" type="button" aria-label="Toggle dark mode">
                                    <i class="fas fa-sun" id="themeIcon"></i>
                                    <span id="themeText">Light</span>
                                </button>
                            </li>

                            <!-- Notification Bell -->
                            <li class="nav-item dropdown">
                                <a id="notificationDropdown" class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bell"></i>
                                    @php
                                        $totalUnread = $unreadComplaints + $unreadPayments + $unreadVisitors + $unreadSystemAlerts;
                                    @endphp
                                    @if($totalUnread > 0)
                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                            {{ $totalUnread }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px;">
                                    <h6 class="dropdown-header">
                                        <i class="fas fa-bell me-2"></i>Notifications
                                    </h6>

                                    @if($unreadComplaints > 0)
                                        <a href="{{ route('complaints.index') }}" class="dropdown-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-exclamation-triangle me-2"></i>Complaints</span>
                                            <span class="badge bg-primary rounded-pill">{{ $unreadComplaints }}</span>
                                        </a>
                                    @endif

                                    @if($unreadPayments > 0)
                                        <a href="{{ route('payments.index') }}" class="dropdown-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-credit-card me-2"></i>Payments</span>
                                            <span class="badge bg-primary rounded-pill">{{ $unreadPayments }}</span>
                                        </a>
                                    @endif

                                    @if($unreadVisitors > 0)
                                        <a href="{{ route('visitors.index') }}" class="dropdown-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-users me-2"></i>Visitors</span>
                                            <span class="badge bg-primary rounded-pill">{{ $unreadVisitors }}</span>
                                        </a>
                                    @endif

                                    @if($unreadSystemAlerts > 0)
                                        <a href="{{ route('system_alerts.index') }}" class="dropdown-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-exclamation-circle me-2"></i>System Alerts</span>
                                            <span class="badge bg-primary rounded-pill">{{ $unreadSystemAlerts }}</span>
                                        </a>
                                    @endif

                                    @if($totalUnread == 0)
                                        <span class="dropdown-item text-center text-muted">
                                            <i class="fas fa-inbox me-2"></i>No new notifications
                                        </span>
                                    @endif
                                </div>
                            </li>

                            <!-- User Dropdown -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle"
                                   href="#" role="button" data-bs-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
        // Enhanced Theme Management - No more flash!
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const themeText = document.getElementById('themeText');
            const body = document.body;

            // Get the theme that was already applied by the early script
            const currentTheme = window.__INITIAL_THEME__ || localStorage.getItem('theme') || 'light';
            
            // Update the UI to match the current theme (no flash since theme is already applied)
            updateThemeUI(currentTheme);

            // Theme toggle event listener
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    // Add transition class
                    body.classList.add('theme-switching');
                    
                    setTimeout(() => {
                        setTheme(newTheme);
                        body.classList.remove('theme-switching');
                        body.classList.add('theme-transition');
                        
                        setTimeout(() => {
                            body.classList.remove('theme-transition');
                        }, 300);
                    }, 50);
                });

                // Keyboard navigation for theme toggle
                themeToggle.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        themeToggle.click();
                    }
                });
            }

            function setTheme(theme) {
                // Apply to both html and body for maximum compatibility
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.className = theme + '-theme';
                body.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                
                updateThemeUI(theme);
            }

            function updateThemeUI(theme) {
                if (themeIcon && themeText && themeToggle) {
                    if (theme === 'dark') {
                        themeIcon.className = 'fas fa-moon';
                        themeText.textContent = 'Dark';
                        themeToggle.setAttribute('aria-label', 'Switch to light mode');
                    } else {
                        themeIcon.className = 'fas fa-sun';
                        themeText.textContent = 'Light';
                        themeToggle.setAttribute('aria-label', 'Switch to dark mode');
                    }
                }
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });

            // Add smooth scroll behavior for better UX
            document.documentElement.style.scrollBehavior = 'smooth';

            // Add focus management for modals
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('shown.bs.modal', function() {
                    const firstInput = modal.querySelector('input, button, textarea, select');
                    if (firstInput) {
                        firstInput.focus();
                    }
                });
            });
        });

        // System theme detection (only if no saved preference)
        if (window.matchMedia && !localStorage.getItem('theme')) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            // Listen for system theme changes
            mediaQuery.addEventListener('change', function(e) {
                // Only apply if no user preference is saved
                if (!localStorage.getItem('theme')) {
                    const systemTheme = e.matches ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', systemTheme);
                    document.documentElement.className = systemTheme + '-theme';
                    document.body.setAttribute('data-theme', systemTheme);
                }
            });
        }

        // Bootstrap tooltips initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Check admin session status every 60 seconds
    setInterval(() => {
        fetch("{{ url('/check-session') }}")
        .then(response => {
            if (response.status === 401) {
                window.location.href = "{{ route('login') }}";
            }
        });
    }, 60000); // every 60 seconds
</script>

</body>
</html>
