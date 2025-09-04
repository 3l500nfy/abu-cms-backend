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

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Server is responding',
        'timestamp' => date('c'),
        'php_version' => PHP_VERSION
    ]);
});

Route::get('/status', function () {
    return response()->json([
        'server' => 'ABU CMS Backend',
        'status' => 'running',
        'php_version' => PHP_VERSION,
        'timestamp' => date('c'),
        'database' => [
            'connection' => config('database.default'),
            'host' => config('database.connections.pgsql.host'),
            'database' => config('database.connections.pgsql.database'),
            'port' => config('database.connections.pgsql.port')
        ]
    ]);
});

Route::get('/simple', function () {
    return 'This route works without any Laravel dependencies!';
});

Route::get('/raw', function () {
    // Bypass Laravel completely
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'Raw PHP response',
        'php_version' => PHP_VERSION,
        'time' => date('c'),
        'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
    ]);
    exit;
});

Route::get('/phpinfo', function () {
    // Show PHP info without Laravel
    phpinfo();
    exit;
});

Route::get('/check', function () {
    // Simple health check
    return 'PHP is working!';
});

Route::get('/error-test', function () {
    try {
        // Try to access Laravel services
        $app = app();
        $config = config('app.name');
        return "Laravel is working! App name: " . ($config ?? 'Not set');
    } catch (Exception $e) {
        return "Laravel error: " . $e->getMessage();
    }
});

Route::get('/db-status', function () {
    try {
        // Check if users table exists
        $userCount = DB::table('users')->count();
        $tableExists = Schema::hasTable('users');
        
        // Check other important tables
        $rolesTable = Schema::hasTable('roles');
        $rolesCount = $rolesTable ? DB::table('roles')->count() : 0;
        
        return response()->json([
            'database_connected' => true,
            'users_table_exists' => $tableExists,
            'user_count' => $userCount,
            'roles_table_exists' => $rolesTable,
            'roles_count' => $rolesCount,
            'message' => $userCount > 0 ? 'Users found' : 'No users in database'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'database_connected' => false,
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('/setup-db', function () {
    try {
        // Check if we can run artisan commands
        if (!class_exists('Artisan')) {
            return response()->json([
                'success' => false,
                'error' => 'Artisan class not available'
            ]);
        }
        
        // Run migrations
        $migrationResult = Artisan::call('migrate', ['--force' => true]);
        
        // Run seeders
        $seederResult = Artisan::call('db:seed', ['--force' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Database setup completed',
            'migration_result' => $migrationResult,
            'seeder_result' => $seederResult
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

Route::get('/create-admin', function () {
    try {
        // Create admin user manually
        $adminUser = DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@abu.edu.ng',
            'password' => Hash::make('admin123'),
            'role_id' => 1, // Assuming admin role ID is 1
            'phone' => '1234567890',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Admin user created successfully',
            'user' => 'admin@abu.edu.ng / admin123'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('/setup-roles', function () {
    try {
        // Check if roles table exists
        if (!Schema::hasTable('roles')) {
            return response()->json([
                'success' => false,
                'error' => 'Roles table does not exist'
            ]);
        }
        
        // Create basic roles
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'staff', 'display_name' => 'Staff Member', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user', 'display_name' => 'Regular User', 'created_at' => now(), 'updated_at' => now()]
        ];
        
        // Insert roles
        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role);
        }
        
        // Get role IDs
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        $staffRole = DB::table('roles')->where('name', 'staff')->first();
        
        return response()->json([
            'success' => true,
            'message' => 'Roles created successfully',
            'roles' => [
                'admin' => $adminRole ? $adminRole->id : 'Not found',
                'staff' => $staffRole ? $staffRole->id : 'Not found'
            ]
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
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
