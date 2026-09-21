<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\HistoryController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicle;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BookingController as AdminBooking;
use App\Http\Controllers\Admin\InspectorController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ChecklistItemController;
use App\Http\Controllers\Admin\TariffController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomer;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Inspector\DashboardController as InspectorDashboard;
use App\Http\Controllers\Inspector\InspectionController;
use App\Http\Controllers\Transaction\TransactionController;

/*
|----------------------------------------------------------------------
| PUBLIC ROUTES
|----------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [HomeController::class, 'layanan'])->name('layanan');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
Route::get('/paket/{package}', [HomeController::class, 'showPackage'])->name('paket.show');

/*
|----------------------------------------------------------------------
| AUTH ROUTES (Customer)
|----------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login',   [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register',[RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

/*
|----------------------------------------------------------------------
| BOOKING ROUTES (wajib login customer)
|----------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/booking',           [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking',          [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/success/{booking}', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/booking/slots',     [BookingController::class, 'getSlots'])->name('booking.slots');
});

/*
|----------------------------------------------------------------------
| CUSTOMER PORTAL ROUTES
|----------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard',             [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::get('/riwayat',               [HistoryController::class, 'index'])->name('history');
    Route::get('/riwayat/{booking}',     [HistoryController::class, 'show'])->name('history.show');
    Route::get('/laporan/{booking}',     [HistoryController::class, 'report'])->name('history.report');
    Route::post('/laporan/{booking}/sign',[HistoryController::class, 'sign'])->name('history.sign');
    Route::get('/profil',                [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil',                [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/kendaraan',             [CustomerVehicle::class, 'index'])->name('vehicles.index');
    Route::get('/kendaraan/tambah',      [CustomerVehicle::class, 'create'])->name('vehicles.create');
    Route::post('/kendaraan',            [CustomerVehicle::class, 'store'])->name('vehicles.store');
    Route::get('/kendaraan/{vehicle}/edit', [CustomerVehicle::class, 'edit'])->name('vehicles.edit');
    Route::put('/kendaraan/{vehicle}',   [CustomerVehicle::class, 'update'])->name('vehicles.update');
    Route::delete('/kendaraan/{vehicle}',[CustomerVehicle::class, 'destroy'])->name('vehicles.destroy');
});

/*
|----------------------------------------------------------------------
| ADMIN ROUTES
|----------------------------------------------------------------------
*/
// Admin login — tanpa middleware guest agar selalu bisa diakses
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AuthenticatedSessionController::class, 'adminCreate'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'adminStore'])->name('login.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('/bookings',                    [AdminBooking::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}',          [AdminBooking::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm', [AdminBooking::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/assign',  [AdminBooking::class, 'assign'])->name('bookings.assign');
    Route::post('/bookings/{booking}/status',  [AdminBooking::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{booking}/cancel',  [AdminBooking::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/create',             [AdminBooking::class, 'create'])->name('bookings.create');
    Route::post('/bookings',                   [AdminBooking::class, 'store'])->name('bookings.store');

    // Inspectors (CMS)
    Route::resource('/inspectors', InspectorController::class);

    // Customers (view only)
    Route::get('/customers',         [AdminCustomer::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}',  [AdminCustomer::class, 'show'])->name('customers.show');

    // Packages (CMS)
    Route::resource('/packages', PackageController::class);
    Route::post('/packages/{package}/toggle', [PackageController::class, 'toggle'])->name('packages.toggle');

    // Checklist Items (CMS)
    Route::resource('/checklist-items', ChecklistItemController::class);

    // Tariffs (CMS)
    Route::resource('/tariffs', TariffController::class);
    Route::post('/tariffs/{tariff}/toggle', [TariffController::class, 'toggle'])->name('tariffs.toggle');

    // CMS Contents
    Route::get('/cms',            [CmsController::class, 'index'])->name('cms.index');
    Route::get('/cms/{group}',    [CmsController::class, 'group'])->name('cms.group');
    Route::put('/cms/{content}',  [CmsController::class, 'update'])->name('cms.update');

    // Reports
    Route::get('/reports',        [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

/*
|----------------------------------------------------------------------
| INSPECTOR ROUTES
|----------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:inspector'])->prefix('inspector')->name('inspector.')->group(function () {
    Route::get('/dashboard',                        [InspectorDashboard::class, 'index'])->name('dashboard');
    Route::get('/tugas',                            [InspectionController::class, 'index'])->name('tasks.index');
    Route::get('/tugas/{booking}',                  [InspectionController::class, 'show'])->name('tasks.show');
    Route::get('/tugas/{booking}/inspeksi',         [InspectionController::class, 'form'])->name('tasks.form');
    Route::post('/tugas/{booking}/inspeksi',        [InspectionController::class, 'store'])->name('tasks.store');
    Route::put('/tugas/{booking}/inspeksi',         [InspectionController::class, 'update'])->name('tasks.update');
    Route::post('/tugas/{booking}/verifikasi',      [InspectionController::class, 'verify'])->name('tasks.verify');
    Route::post('/tugas/{booking}/foto',            [InspectionController::class, 'uploadPhoto'])->name('tasks.photo');
});

/*
|----------------------------------------------------------------------
| TRANSACTION ROUTES (admin + inspector access)
|----------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,inspector'])->prefix('transaksi')->name('transaction.')->group(function () {
    Route::get('/{booking}',           [TransactionController::class, 'show'])->name('show');
    Route::post('/{booking}',          [TransactionController::class, 'store'])->name('store');
    Route::post('/{booking}/item',     [TransactionController::class, 'addItem'])->name('item.add');
    Route::delete('/{booking}/item/{item}', [TransactionController::class, 'removeItem'])->name('item.remove');
    Route::post('/{booking}/payment',  [TransactionController::class, 'payment'])->name('payment');
});
