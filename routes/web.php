<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorCtrl;
use App\Http\Controllers\Doctor\ScheduleController as DocScheduleCtrl;
use App\Http\Controllers\Admin\AdminHealthCheckController;
use App\Http\Controllers\Admin\AdminPackageBookingController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Doctor\MessageController as DocMessageCtrl;
use App\Http\Controllers\Admin\NewsController as AdminNewsCtrl;
use App\Http\Controllers\Patient\AppointmentController as PtnAppoinmentCtrl;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn() => Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => app()->version(),
    'phpVersion' => PHP_VERSION,
]))->name('welcome');

Route::get('/book-appointment', fn() => Inertia::render('AppointmentBooking', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => app()->version(),
    'phpVersion' => PHP_VERSION,
]))->name('appointment.booking');

Route::get('/news/{id}', fn($id) => Inertia::render('NewsDetail', ['id' => $id]))->name('news.detail');

Route::get('/news-all', fn() => Inertia::render('NewsAll'))->name('news.all');

Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');


require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','verified'])->group(function() {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return Inertia::render('Admin/Dashboard');
        } elseif ($user->role === 'doctor') {
            return Inertia::render('Doctor/Dashboard');
        } else {
            return Inertia::render('Patient/Dashboard');
        }
    })->name('dashboard');

    // Patient Routes
    Route::middleware('role:patient')->group(function() {
        Route::get('/patient/book-appointment', fn() => Inertia::render('Patient/BookAppointment'))->name('patient.book-appointment');
        Route::get('/patient/health-packages', fn() => Inertia::render('Patient/HealthPackages'))->name('patient.health-packages');
        Route::get('/patient/medical-records', fn() => Inertia::render('Patient/MedicalRecords'))->name('patient.medical-records');
        Route::get('/patient/emergency', fn() => Inertia::render('Patient/Emergency'))->name('patient.emergency');
        Route::get('/patient/health-tips', fn() => Inertia::render('Patient/HealthTips'))->name('patient.health-tips');
        Route::get('/patient/profile', fn() => Inertia::render('Patient/Profile'))->name('patient.profile');
        Route::resource('patient/appointments', PtnAppoinmentCtrl::class, ['as' => 'patient']);
        Route::get('patient/appointments/{appointment}/download-pdf', [PtnAppoinmentCtrl::class, 'downloadPdf'])->name('patient.appointments.download-pdf');
        Route::get('patient/package-bookings/{package_booking}/download-pdf', [PtnAppoinmentCtrl::class, 'downloadPackagePdf'])->name('patient.package-bookings.download-pdf');
    });

    //Admin Routes
    Route::middleware('role:admin')->group(function(){

        Route::get('/admin/doctors', fn() => Inertia::render('Admin/Doctors/Index'))->name('admin.doctors.index');

        Route::get('/admin/doctors/list', [AdminDoctorCtrl::class, 'index'])->name('admin.doctors.list');

        Route::post('/admin/doctors',[AdminDoctorCtrl::class, 'store'])->name('admin.doctors.store');

        Route::put('/admin/doctors/{doctor}',[AdminDoctorCtrl::class, 'update'])->name('admin.doctors.update');

        Route::delete('/admin/doctors/{doctor}',[AdminDoctorCtrl::class, 'destroy'])->name('admin.doctors.destroy');

        Route::get('/admin/schedules', fn() => Inertia::render('Admin/Schedules/Index'))->name('admin.schedules.index');
        Route::get('/admin/schedules/list', [AdminScheduleController::class,'index'])->name('admin.schedules.list');
        Route::post('/admin/schedules/mention', [AdminScheduleController::class, 'mention'])->name('admin.schedules.mention');

        Route::resource('/admin/health-checks', AdminHealthCheckController::class, ['as' => 'admin']);

        Route::resource('/admin/package-bookings', AdminPackageBookingController::class, ['as' => 'admin']);
        Route::get('/admin/package-bookings/{package_booking}/download-pdf', [AdminPackageBookingController::class, 'downloadPdf'])->name('admin.package-bookings.download-pdf');

        Route::get('/admin/appointments', [AppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::get('/admin/appointments/{appointment}', [AppointmentController::class, 'show'])->name('admin.appointments.show');
        Route::put('/admin/appointments/{appointment}', [AppointmentController::class, 'update'])->name('admin.appointments.update');

        Route::resource('/admin/news', AdminNewsCtrl::class, ['as' => 'admin']);
        Route::post('/admin/news/upload-image', [AdminNewsCtrl::class, 'uploadImage'])->name('admin.news.upload-image');
    });

    //Doctors Routes

    Route::middleware(['auth','verified','role:doctor'])->group(function(){

        Route::get('/doctor', fn() => Inertia::render('Doctor/Dashboard'))->name('doctor.home');
        
        Route::get('/doctor/schedules', fn()=> Inertia::render('Doctor/Schedules/Index'))->name('doctor.schedules');

        Route::get('/doctor/schedules/list', [DocScheduleCtrl::class, 'index']);
        Route::post('/doctor/schedules', [DocScheduleCtrl::class, 'store']);
        Route::put('/doctor/schedules/{schedule}', [DocScheduleCtrl::class, 'update']);
        Route::delete('/doctor/schedules/{schedule}', [DocScheduleCtrl::class, 'destroy']);

        Route::get('/doctor/messages', fn() => Inertia::render('Doctor/Messages'))->name('doctor.messages');
        Route::get('/doctor/messages/api', [DocMessageCtrl::class, 'index'])->name('doctor.messages.index');
    });

});
