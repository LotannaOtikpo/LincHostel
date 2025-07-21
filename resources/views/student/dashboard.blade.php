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
        
        // Also set a class on html for immediate CSS targeting
        document.documentElement.className = theme + '-theme';
        
        // Store for later use
        window.__INITIAL_THEME__ = theme;
      })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LincHostel | Student Dashboard</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Student Dashboard CSS File -->
    <link href="{{ asset('assets/css/student.css') }}" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-building me-2"></i>LincHostel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
            </ul>
            
            <!-- Announcement Notification Dropdown -->
            <ul class="navbar-nav me-3">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        @if($unreadAnnouncements > 0)
                            <span class="badge bg-danger position-absolute top-1 start-100 translate-middle">{{ $unreadAnnouncements }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="notificationsDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        <li class="dropdown-header py-2 px-3 fw-bold border-bottom">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </li>
                        @forelse($latestAnnouncements as $announcement)
                            <li>
                                <a 
                                    href="#" 
                                    class="dropdown-item py-2 px-3 text-wrap text-break"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#announcementModal"
                                    data-title="{{ $announcement->title }}"
                                    data-description="{{ $announcement->description }}"
                                    data-has-attachment="{{ $announcement->hasAttachment() ? '1' : '0' }}"
                                    data-attachment-name="{{ $announcement->attachment_original_name ?? '' }}"
                                    data-attachment-type="{{ $announcement->attachment_type ?? '' }}"
                                    data-attachment-url="{{ $announcement->hasAttachment() ? route('announcements.download', $announcement) : '' }}"
                                >
                                    <div class="d-flex flex-column">
                                        <strong class="mb-1">{{ $announcement->title }}</strong>
                                        <small class="text-muted text-wrap">{{ Str::limit($announcement->description, 100) }}</small>
                                        @if($announcement->hasAttachment())
                                            <div class="mt-1 d-flex align-items-center">
                                                <i class="{{ $announcement->getAttachmentTypeIcon() }} text-secondary me-1"></i>
                                                <small class="text-secondary">{{ Str::limit($announcement->attachment_original_name, 20) }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-0"></li>
                        @empty
                            <li class="dropdown-item text-center text-muted py-3">
                                <i class="fas fa-inbox me-2"></i>No announcements
                            </li>
                        @endforelse
                    </ul>
                </li>
            </ul>

            <!-- Enhanced Dark Mode Toggle -->
            <div class="me-3">
                <button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode">
                    <i class="fas fa-sun" id="themeIcon"></i>
                    <span id="themeText">Light</span>
                </button>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Welcome Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-graduate me-2"></i>Student Dashboard
                </div>
                <div class="card-body text-center">
                    @php
                        date_default_timezone_set('Africa/Lagos'); // Set to Nigerian time

                        $hour = date('H');
                        if ($hour >= 0 && $hour < 12) {
                            $greeting = 'Good morning';
                        } elseif ($hour >= 12 && $hour < 17) {
                            $greeting = 'Good afternoon';
                        } else {
                            $greeting = 'Good evening';
                        }
                    @endphp
                    <h5>{{ $greeting }}, {{ $student->full_name }}! 👋 </h5>
                    <p class="text-muted">Here's your personalized dashboard with all your details and available options.</p>
                </div>
            </div>

            <!-- Student Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-id-card me-2"></i>Student Information
                </div>
                <div class="card-body row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-hashtag me-2"></i>Admission Number:</strong> {{ $student->admission_number }}</p>
                        <p><strong><i class="fas fa-user me-2"></i>Full Name:</strong> {{ $student->full_name }}</p>
                        <p><strong><i class="fas fa-venus-mars me-2"></i>Gender:</strong> {{ $student->gender }}</p>
                        <p><strong><i class="fas fa-map-marker-alt me-2"></i>Address:</strong> {{ $student->address }}</p>
                        <p><strong><i class="fas fa-graduation-cap me-2"></i>Department:</strong> {{ $student->department }}</p>
                        <p><strong><i class="fas fa-calendar-alt me-2"></i>Semester:</strong> Semester {{ $student->semester }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-door-open me-2"></i>Room Number:</strong> {{ $student->room->room_number ?? 'Not assigned' }}</p>
                        <p><strong><i class="fas fa-calendar-check me-2"></i>Check-in Date:</strong> {{ $student->check_in_date->format('M d, Y') }}</p>
                        <p><strong><i class="fas fa-calendar-times me-2"></i>Expected Check-out:</strong> {{ $student->expected_check_out_date->format('M d, Y') }}</p>
                        <p><strong><i class="fas fa-info-circle me-2"></i>Status:</strong>
                            <span class="badge {{ $student->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                <i class="fas {{ $student->status == 'active' ? 'fa-check' : 'fa-times' }} me-1"></i>
                                {{ ucfirst($student->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Students in This Room -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-users me-2"></i>Students in your Room ({{ $room->students->count() }})</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Check-in Date</th>
                                <th>Expected Check-out Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($room->students as $student)
                                <tr>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ $student->check_in_date->format('M d, Y') }}</td>
                                    <td>{{ $student->expected_check_out_date->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No students in your room</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="row g-4">
                <!-- Payment -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-credit-card me-2"></i>Make Payment
                        </div>
                        <div class="card-body">

                            @if(session('payment_success'))
                                <div class="alert alert-success auto-dismiss">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('payment_success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger auto-dismiss">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('student.payments.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-money-bill-wave me-1"></i>Amount
                                    </label>
                                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-payment me-1"></i>Payment Method
                                    </label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="credit_card">💳 Credit Card</option>
                                        <option value="bank_transfer">🏦 Bank Transfer</option>
                                        <option value="other">📱 Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-receipt me-1"></i>Payment Receipt
                                    </label>
                                    <input type="file" name="receipt" class="form-control" required>
                                    <small class="text-muted">📎 JPG, PNG, PDF (Max: 2MB)</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-sticky-note me-1"></i>Notes (Optional)
                                    </label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Add any additional notes..."></textarea>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i>Submit Payment
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#paymentDetailsModal">
                                        <i class="fas fa-info-circle me-1"></i>Account Details
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Complaint -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="fas fa-exclamation-triangle me-2"></i>Make Complaint
                        </div>
                        <div class="card-body">
                            @if(session('complaint_success'))
                                <div class="alert alert-success auto-dismiss">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('complaint_success') }}
                                </div>
                            @endif
                            <form action="{{ route('student.complaints.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-heading me-1"></i>Subject
                                    </label>
                                    <input type="text" name="subject" class="form-control" placeholder="Brief description of your issue..." required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-align-left me-1"></i>Description
                                    </label>
                                    <textarea name="description" class="form-control" rows="4" placeholder="Please provide detailed information about your complaint..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Submit Complaint
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="card mt-5">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i>Payment History
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-receipt me-1"></i>Receipt #</th>
                                        <th><i class="fas fa-money-bill-wave me-1"></i>Amount</th>
                                        <th><i class="fas fa-calendar me-1"></i>Date</th>
                                        <th><i class="fas fa-info-circle me-1"></i>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->receipt_number }}</td>
                                            <td>₦{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($payment->status == 'completed') bg-success
                                                    @elseif($payment->status == 'pending') bg-warning text-dark
                                                    @else bg-danger @endif">
                                                    <i class="fas 
                                                        @if($payment->status == 'completed') fa-check
                                                        @elseif($payment->status == 'pending') fa-clock
                                                        @else fa-times @endif me-1"></i>
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No payment history found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Complaint History -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-list me-2"></i>Complaint History
                </div>
                <div class="card-body">
                    @if($complaints->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-heading me-1"></i>Subject</th>
                                        <th><i class="fas fa-calendar me-1"></i>Date</th>
                                        <th><i class="fas fa-info-circle me-1"></i>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                        <tr>
                                            <td>{{ Str::limit($complaint->subject, 30) }}</td>
                                            <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($complaint->status == 'resolved') bg-success
                                                    @elseif($complaint->status == 'submitted') bg-secondary
                                                    @else bg-warning text-dark @endif">
                                                    <i class="fas 
                                                        @if($complaint->status == 'resolved') fa-check
                                                        @elseif($complaint->status == 'submitted') fa-paper-plane
                                                        @else fa-clock @endif me-1"></i>
                                                    {{ ucfirst($complaint->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No complaints found.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentDetailsModalLabel">
                    <i class="fas fa-university me-2"></i>Bank Account Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Please make your payment to the account details below and upload your payment receipt through the form.
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-university me-1"></i>Bank Name</th>
                                <th><i class="fas fa-hashtag me-1"></i>Account Number</th>
                                <th><i class="fas fa-user me-1"></i>Account Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>🏦 EcoBank Nigeria PLC</td>
                                <td><code>3680086084</code></td>
                                <td>Lincoln Logistics Service Limited</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> After making your payment, kindly upload your payment receipt using the "Make Payment" form on your dashboard for verification.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Details Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="announcementModalLabel">
            <i class="fas fa-bullhorn me-2"></i>Announcement
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
      </div>
      <div class="modal-body">
        <div id="announcementModalBody">
          Announcement description will appear here.
        </div>
        <div id="attachmentContainer" class="mt-3 pt-3 border-top" style="display: none;">
          <h6><i class="fas fa-paperclip me-2"></i>Attachment</h6>
          <div class="d-flex align-items-center">
            <i id="attachmentIcon" class="fas fa-file me-2 fs-4"></i>
            <div>
              <div id="attachmentName" class="fw-bold"></div>
              <a id="attachmentDownloadLink" href="#" class="btn btn-sm btn-primary mt-2">
                <i class="fas fa-download me-1"></i>Download
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Enhanced Theme Toggle and Announcement Modal JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Enhanced Theme Management - No more flash!
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

    // Announcement Modal Management
    const announcementModal = document.getElementById('announcementModal');
    
    if (announcementModal) {
        announcementModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const title = button.getAttribute('data-title');
            const description = button.getAttribute('data-description');
            const hasAttachment = button.getAttribute('data-has-attachment') === '1';
            const attachmentName = button.getAttribute('data-attachment-name');
            const attachmentType = button.getAttribute('data-attachment-type');
            const attachmentUrl = button.getAttribute('data-attachment-url');

            const modalTitle = announcementModal.querySelector('.modal-title');
            const modalBody = announcementModal.querySelector('#announcementModalBody');
            const attachmentContainer = document.getElementById('attachmentContainer');
            const attachmentIcon = document.getElementById('attachmentIcon');
            const attachmentNameElement = document.getElementById('attachmentName');
            const attachmentDownloadLink = document.getElementById('attachmentDownloadLink');

            modalTitle.innerHTML = '<i class="fas fa-bullhorn me-2"></i>' + title;
            modalBody.textContent = description;

            // Handle attachment display
            if (hasAttachment && attachmentName) {
                attachmentContainer.style.display = 'block';
                attachmentNameElement.textContent = attachmentName;
                attachmentDownloadLink.href = attachmentUrl;
                
                // Set appropriate icon based on file type
                if (attachmentType) {
                    attachmentIcon.className = getFileIcon(attachmentType);
                } else {
                    attachmentIcon.className = 'fas fa-file me-2 fs-4';
                }
            } else {
                attachmentContainer.style.display = 'none';
            }
        });
    }

    // Function to determine file icon based on MIME type
    function getFileIcon(mimeType) {
        if (mimeType.startsWith('image/')) {
            return 'fas fa-file-image me-2 fs-4 text-info';
        } else if (mimeType === 'application/pdf') {
            return 'fas fa-file-pdf me-2 fs-4 text-danger';
        } else if (mimeType === 'application/msword' || mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            return 'fas fa-file-word me-2 fs-4 text-primary';
        } else if (mimeType === 'application/vnd.ms-excel' || mimeType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            return 'fas fa-file-excel me-2 fs-4 text-success';
        } else {
            return 'fas fa-file me-2 fs-4 text-secondary';
        }
    }

    // Enhanced form validation feedback
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
                submitBtn.disabled = true;
            }
        });
    });

    // Auto-hide alerts after 5 seconds - ONLY for alerts with auto-dismiss class
    const autoDismissAlerts = document.querySelectorAll('.alert.auto-dismiss');
    autoDismissAlerts.forEach(alert => {
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
</script>

<script>
    // Check session status every 60 seconds
    setInterval(() => {
        fetch("{{ url('/check-session') }}")
        .then(response => {
            if (response.status === 401) {
                window.location.href = "{{ route('student.login') }}";
            }
        });
    }, 60000); // every 60 seconds
</script>

</body>
</html>
