<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LincHostel | Hostel Application Form</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

     <!-- Home Page CSS File -->
    <link href="{{ asset('assets/css/apply.css') }}" rel="stylesheet">
</head>
<body>

    <!-- ======= Hostel Application Section ======= -->
<section id="hostel-application-form" class="hostel-application-form">

  <div class="info-button-container" tabindex="0" aria-label="Hostel application info">
    <div class="info-button">i</div>
    <div class="info-tooltip" role="tooltip">
      STUDENTS register for hostel at the student affairs department and fill hostel application form for 2000 naira only.
            <br/><br/>
      <b>PAYMENT:</b> Students make payment online for a semester/year (may generate receipt online).
            <br/><br/>
      <b>RECEIPT:</b> Student obtains both transaction and receipt from Finance Department after payment approval.
            <br/><br/>
      <b>STUDENT AFFAIRS:</b> Student presents receipt and fills necessary information on the form.
            <br/><br/>
      <b>STUDENT AFFAIRS:</b> Student processes full documentation and collects hostel clearance form.
            <br/><br/>
      <b>HOSTEL PORTER:</b> Student submits clearance form, receives room allocation, and signs in.
            <br/><br/>
      <b>VACATION:</b> Students submit keys to the porter, sign out, and move out of the hostel.
    </div>
  </div>

  <a href="/"><img src="{{ asset('assets/img/favicon.ico') }}" alt="Lincoln Logo" style="height: 100px; width: 200px; margin-right: 20px; margin-bottom:20px; border-radius: 10px;"></a>
      
  <h2>Hostel Application Form</h2>

  @if (session('success'))
      <div class="alert success-alert">
          {{ session('success') }}
      </div>
  @endif

  @if (session('error'))
      <div class="alert alert-danger">
          {{ session('error') }}
      </div>
  @endif

  @if ($errors->any())
      <div class="alert alert-danger">
          <strong>Whoops! There were some problems with your input:</strong>
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <form id="hostelApplicationForm" action="{{ route('hostel.apply') }}" method="POST" enctype="multipart/form-data">
    @csrf

      <div class="form-group">
          <label for="academic_year">Academic Year</label>
          <input type="text" name="academic_year" id="academic_year" placeholder="e.g. 2024-2025" required>
      </div>

      <div class="form-group">
        <label for="passport_photo">Passport Photo</label>
        <input type="file" name="passport_photo" id="passport_photo" accept="image/*" required>
      </div>

      <div class="form-group">
        <label for="applicationform_receipt">Application Form Payment Receipt (#2000)</label>
        <input type="file" name="applicationform_receipt" id="applicationform_receipt" accept="image/*" required>
      </div>

      <div class="form-group">
        <label for="hostelfee_receipt">Hostel Fees Payment Receipt (#180,000 or #400,000)</label>
        <input type="file" name="hostelfee_receipt" id="hostelfee_receipt" accept="image/*" required>
      </div>

        <button type="button" class="submit-btn" data-bs-toggle="modal" data-bs-target="#paymentDetailsModal" style="margin-bottom: 20px;">
            View Account Details
        </button>
        
      <h3>Personal Information</h3>

      <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" name="name" id="name" required>
      </div>

      <div class="form-group">
          <label for="reg_number">Registration Number</label>
          <input type="text" name="reg_number" id="reg_number" required>
      </div>

      <div class="form-group">
          <label for="intake">Intake</label>
          <input type="text" name="intake" id="intake" required>
      </div>

      <div class="form-group">
          <label for="program">Program</label>
          <input type="text" name="program" id="program" required>
      </div>

      <div class="form-group">
          <label for="department">Department</label>
          <input type="text" name="department" id="department" required>
      </div>

      <h3>Hostel Accommodation Details</h3>

      <div class="form-group">
          <label for="hostel_allocated">Hostel Allocated (if any)</label>
          <input type="text" name="hostel_allocated" id="hostel_allocated">
      </div>

      <div class="form-group">
          <label for="medical_condition">Any Medical Condition</label>
          <input type="text" name="medical_condition" id="medical_condition">
      </div>

      <div class="form-group">
          <label for="emergency_contact">Emergency Contact</label>
          <input type="text" name="emergency_contact" id="emergency_contact" required>
      </div>

      <h3>Declaration</h3>

      <div class="form-group declaration">
          <p>I, <input type="text" name="declaration_name" placeholder="Your Full Name" required>declare that the information provided above is true to the best of my knowledge. I agree to abide by the hostel rules and regulations set by the institute.</p>
      </div>

      <div class="form-group">
          <label for="applicant_signature">Applicant Signature (Type your full name)</label>
          <input type="text" name="applicant_signature" id="applicant_signature" required>
      </div>

      <div class="form-group">
          <label for="date">Date</label>
          <input type="date" name="date" id="date" required>
      </div>

      <div class="form-group">
          <label for="guardian_signature">Parent/Guardian Signature (Type their full name)</label>
          <input type="text" name="guardian_signature" id="guardian_signature" required>
      </div>

      <div class="form-group">
          <label for="guardian_date">Guardian's Date</label>
          <input type="date" name="guardian_date" id="guardian_date" required>
      </div>

      <div class="form-group">
          <label for="amount_paid">Amount Paid (₦)</label>
          <input type="text" name="amount_paid" id="amount_paid" required>
      </div>

      <p class="note">Note: Ensure all information is accurate before submitting.</p>
      <p>After submission, your information and receipts will be checked and you will be notified via email or phone call if your application is successful.</p>
      <p>For any issues, please contact the hostel management office.</p>
      <p>Thank you for choosing LincHostel!</p>
      
      <button type="submit" class="submit-btn">Submit Application</button>
  </form>

</section>
<!-- ======= End Hostel Application Section ======= -->

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentDetailsModalLabel">
          <i class="fas fa-university me-2"></i> Bank Account Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <i class="fas fa-info-circle me-2"></i>
          Please make your payment to the account details below and upload your payment receipt through the form.
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th><i class="fas fa-university me-1"></i>Bank Name</th>
                <th><i class="fas fa-hashtag me-1"></i>Account Number</th>
                <th><i class="fas fa-user me-1"></i>Account Name</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>EcoBank Nigeria PLC</td>
                <td><code>3680086084</code></td>
                <td>Lincoln Logistics Service Limited</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="alert alert-warning mt-3">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Important:</strong> After making your payment, kindly upload your payment receipt using the form below for verification.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<!-- Theme Management Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme in localStorage
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    // Apply the theme
    document.body.setAttribute('data-theme', savedTheme);
    
    // Listen for system theme changes if no user preference is saved
    if (window.matchMedia && !localStorage.getItem('theme')) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        document.body.setAttribute('data-theme', mediaQuery.matches ? 'dark' : 'light');
        
        mediaQuery.addEventListener('change', function(e) {
            if (!localStorage.getItem('theme')) {
                document.body.setAttribute('data-theme', e.matches ? 'dark' : 'light');
            }
        });
    }
});
</script>

</body>
</html>