<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// Admin routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/complaints', [AdminController::class, 'complaints'])->name('admin.complaints');
    Route::post('/admin/complaints/{id}/status', [AdminController::class, 'updateComplaintStatus'])->name('admin.complaints.update-status');
    Route::delete('/admin/complaints/{id}', [AdminController::class, 'deleteComplaint'])->name('admin.complaints.delete');
    
    // User management routes
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/admin/users/{id}/password', [AdminController::class, 'changePassword'])->name('admin.users.password');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Staff management routes
    Route::get('/admin/staff/add', [AdminController::class, 'showAddStaff'])->name('admin.staff.add');
    Route::post('/admin/staff/add', [AdminController::class, 'addStaff'])->name('admin.staff.store');
    
    // Department management routes
    Route::get('/admin/departments', [App\Http\Controllers\Admin\DepartmentController::class, 'index'])->name('admin.departments');
    Route::get('/admin/departments/create', [App\Http\Controllers\Admin\DepartmentController::class, 'create'])->name('admin.departments.create');
    Route::post('/admin/departments', [App\Http\Controllers\Admin\DepartmentController::class, 'store'])->name('admin.departments.store');
    Route::get('/admin/departments/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'show'])->name('admin.departments.show');
    Route::post('/admin/complaints/{id}/assign', [App\Http\Controllers\Admin\DepartmentController::class, 'assignComplaint'])->name('admin.complaints.assign');
    Route::get('/admin/departments/{id}/staff', [App\Http\Controllers\Admin\DepartmentController::class, 'getDepartmentStaff'])->name('admin.departments.staff');
    Route::get('/admin/departments/unassigned-complaints', [App\Http\Controllers\Admin\DepartmentController::class, 'getUnassignedComplaints'])->name('admin.departments.unassigned');
    
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

// Department staff routes
Route::get('/department/login', function() {
    return view('department.login');
})->name('department.login');

Route::post('/department/login', [App\Http\Controllers\Department\DashboardController::class, 'login'])->name('department.login.post');

Route::middleware('auth')->group(function () {
    Route::get('/department/dashboard', [App\Http\Controllers\Department\DashboardController::class, 'index'])->name('department.dashboard');
    Route::get('/department/complaints', [App\Http\Controllers\Department\DashboardController::class, 'complaints'])->name('department.complaints');
    Route::post('/department/complaints/{id}/status', [App\Http\Controllers\Department\DashboardController::class, 'updateStatus'])->name('department.complaints.update-status');
    Route::post('/department/logout', [App\Http\Controllers\Department\DashboardController::class, 'logout'])->name('department.logout');
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'message' => 'ABU CMS Backend is running']);
});

Route::get('/test', function () {
    return 'Simple test route working!';
});

Route::get('/debug', function () {
    return response()->json([
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'app_key_set' => !empty(config('app.key')),
        'database_connection' => config('database.default'),
        'time' => now()->toISOString()
    ]);
});

Route::get('/', function () {
    try {
        return redirect('/admin/login');
    } catch (\Exception $e) {
        return response('ABU CMS Backend is running. <a href="/admin/login">Go to Admin Login</a>', 200);
    }
});
