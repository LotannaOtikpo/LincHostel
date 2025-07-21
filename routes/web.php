<?php

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\StudentsAuthController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\StudentComplaintController;
use App\Http\Controllers\HostelApplicationController;
use App\Http\Controllers\StudentsDashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/home')->name('welcome');
Route::view('/home', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/features', 'features')->name('features');
Route::view('/faq', 'faq')->name('faq');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| Hostel Application Form Routes
|--------------------------------------------------------------------------
*/
Route::get('/hostel/apply', [HostelApplicationController::class, 'create'])->name('apply');
Route::post('/hostel/apply', [HostelApplicationController::class, 'store'])->name('hostel.apply');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Admin)
|--------------------------------------------------------------------------
*/
Auth::routes([
    'register' => false,
    'verify'   => false,
]);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin-only Routes (with AdminMiddleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resources([
        'rooms'      => RoomController::class,
        'students'   => StudentController::class,
        'payments'   => PaymentController::class,
        'complaints' => ComplaintController::class,
        'visitors'   => VisitorController::class,
    ]);

    Route::post('/visitors/{visitor}/checkout', [VisitorController::class, 'checkout'])->name('visitors.checkout');
    Route::get('/students/{student}/profile', [StudentController::class, 'profile'])->name('students.profile');

    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::get('/announcements/{announcement}/download', [AnnouncementController::class, 'downloadAttachment'])->name('announcements.download');

    // Admin session status check route
    Route::get('/check-admin-session', function () {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return response()->json(['status' => 'ok']);
    });
});

/*
|--------------------------------------------------------------------------
| Student Login Routes (Unauthenticated)
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware('guest:student')->group(function () {
    Route::get('/login', [StudentsAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentsAuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Student-only Routes (with StudentAuth Middleware)
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware('student.auth')->group(function () {
    Route::get('/dashboard', [StudentsDashboardController::class, 'index'])->name('dashboard');
    Route::post('/payments', [StudentPaymentController::class, 'store'])->name('payments.store');
    Route::post('/complaints', [StudentComplaintController::class, 'store'])->name('complaints.store');

    // Student session status check route
    Route::get('/check-session', function () {
        if (!Auth::guard('student')->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return response()->json(['status' => 'ok']);
    });
});
