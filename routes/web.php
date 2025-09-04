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

Route::get('/setup-departments', function () {
    try {
        // Check if departments table exists
        if (!Schema::hasTable('departments')) {
            return response()->json([
                'success' => false,
                'error' => 'Departments table does not exist'
            ]);
        }
        
        // Get actual table structure
        $columns = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'departments'");
        
        return response()->json([
            'success' => true,
            'message' => 'Departments table structure',
            'columns' => $columns
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('/create-departments', function () {
    try {
        // Check if departments table exists
        if (!Schema::hasTable('departments')) {
            return response()->json([
                'success' => false,
                'error' => 'Departments table does not exist'
            ]);
        }
        
        // Create sample departments with correct columns
        $departments = [
            [
                'name' => 'Computer Science',
                'code' => 'CS',
                'description' => 'Handles computer science related complaints',
                'email' => 'cs@abu.edu.ng',
                'phone' => '08012345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Electrical Engineering',
                'code' => 'EE',
                'description' => 'Handles electrical engineering complaints',
                'email' => 'ee@abu.edu.ng',
                'phone' => '08012345679',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mechanical Engineering',
                'code' => 'ME',
                'description' => 'Handles mechanical engineering complaints',
                'email' => 'me@abu.edu.ng',
                'phone' => '08012345680',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'General Administration',
                'code' => 'GA',
                'description' => 'Handles general administrative complaints',
                'email' => 'admin@abu.edu.ng',
                'phone' => '08012345681',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        // Insert departments
        foreach ($departments as $dept) {
            DB::table('departments')->insertOrIgnore($dept);
        }
        
        $deptCount = DB::table('departments')->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Departments created successfully',
            'departments_count' => $deptCount
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('/fix-departments', function () {
    try {
        // Check if departments table exists
        if (!Schema::hasTable('departments')) {
            return response()->json([
                'success' => false,
                'error' => 'Departments table does not exist'
            ]);
        }
        
        // Check current departments
        $existingDepts = DB::table('departments')->get();
        
        // Create sample departments with correct columns
        $departments = [
            [
                'name' => 'Computer Science',
                'code' => 'CS',
                'description' => 'Handles computer science related complaints',
                'email' => 'cs@abu.edu.ng',
                'phone' => '08012345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Electrical Engineering',
                'code' => 'EE',
                'description' => 'Handles electrical engineering complaints',
                'email' => 'ee@abu.edu.ng',
                'phone' => '08012345679',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mechanical Engineering',
                'code' => 'ME',
                'description' => 'Handles mechanical engineering complaints',
                'email' => 'me@abu.edu.ng',
                'phone' => '08012345680',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'General Administration',
                'code' => 'GA',
                'description' => 'Handles general administrative complaints',
                'email' => 'admin@abu.edu.ng',
                'phone' => '08012345681',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        // Insert departments
        $inserted = 0;
        foreach ($departments as $dept) {
            try {
                DB::table('departments')->insertOrIgnore($dept);
                $inserted++;
            } catch (Exception $e) {
                // Log the error but continue
                error_log("Failed to insert department: " . $e->getMessage());
            }
        }
        
        $deptCount = DB::table('departments')->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Departments fix completed',
            'existing_departments' => $existingDepts,
            'departments_inserted' => $inserted,
            'total_departments' => $deptCount,
            'departments' => DB::table('departments')->get()
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

Route::get('/check-complaints-table', function () {
    try {
        // Check if complaints table exists
        if (!Schema::hasTable('complaints')) {
            return response()->json([
                'success' => false,
                'error' => 'Complaints table does not exist'
            ]);
        }
        
        // Get actual table structure
        $columns = DB::select("SELECT column_name, data_type, is_nullable, column_default FROM information_schema.columns WHERE table_name = 'complaints' ORDER BY ordinal_position");
        
        // Check current complaints count
        $complaintCount = DB::table('complaints')->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Complaints table structure',
            'columns' => $columns,
            'current_complaints_count' => $complaintCount
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('/setup-complete-system', function () {
    try {
        // 1. Setup Complaint Categories
        if (Schema::hasTable('complaint_categories')) {
            $categories = [
                [
                    'name' => 'academic',
                    'display_name' => 'Academic Issues',
                    'description' => 'Complaints related to academic matters',
                    'color' => '#3B82F6',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'facility',
                    'display_name' => 'Facility Problems',
                    'description' => 'Complaints about campus facilities',
                    'color' => '#EF4444',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'administrative',
                    'display_name' => 'Administrative Issues',
                    'description' => 'Complaints about administrative processes',
                    'color' => '#10B981',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'technical',
                    'display_name' => 'Technical Problems',
                    'description' => 'IT and technical related complaints',
                    'color' => '#F59E0B',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
            
            foreach ($categories as $cat) {
                DB::table('complaint_categories')->insertOrIgnore($cat);
            }
        }
        
        // 2. Setup Complaint Statuses
        if (Schema::hasTable('complaint_statuses')) {
            $statuses = [
                [
                    'name' => 'pending',
                    'display_name' => 'Pending',
                    'description' => 'Complaint is waiting for review',
                    'color' => '#F59E0B',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'in_progress',
                    'display_name' => 'In Progress',
                    'description' => 'Complaint is being processed',
                    'color' => '#3B82F6',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'resolved',
                    'display_name' => 'Resolved',
                    'description' => 'Complaint has been resolved',
                    'color' => '#10B981',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'rejected',
                    'display_name' => 'Rejected',
                    'description' => 'Complaint has been rejected',
                    'color' => '#EF4444',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
            
            foreach ($statuses as $status) {
                DB::table('complaint_statuses')->insertOrIgnore($status);
            }
        }
        
        // 3. Create Sample Students
        if (Schema::hasTable('users') && Schema::hasTable('roles')) {
            $studentRole = DB::table('roles')->where('name', 'user')->first();
            if ($studentRole) {
                $students = [
                    [
                        'name' => 'John Doe',
                        'email' => 'john.doe@student.abu.edu.ng',
                        'password' => Hash::make('student123'),
                        'role_id' => $studentRole->id,
                        'student_id' => 'STU001',
                        'department' => 'Computer Science',
                        'phone' => '08012345690',
                        'address' => 'Student Hostel A, Room 101',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name' => 'Jane Smith',
                        'email' => 'jane.smith@student.abu.edu.ng',
                        'password' => Hash::make('student123'),
                        'role_id' => $studentRole->id,
                        'student_id' => 'STU002',
                        'department' => 'Electrical Engineering',
                        'phone' => '08012345691',
                        'address' => 'Student Hostel B, Room 205',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name' => 'Mike Johnson',
                        'email' => 'mike.johnson@student.abu.edu.ng',
                        'password' => Hash::make('student123'),
                        'role_id' => $studentRole->id,
                        'student_id' => 'STU003',
                        'department' => 'Mechanical Engineering',
                        'phone' => '08012345692',
                        'address' => 'Student Hostel C, Room 310',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ];
                
                foreach ($students as $student) {
                    DB::table('users')->insertOrIgnore($student);
                }
            }
        }
        
        // 4. Create Sample Staff Members
        if (Schema::hasTable('users') && Schema::hasTable('roles') && Schema::hasTable('departments') && Schema::hasTable('staff')) {
            $staffRole = DB::table('roles')->where('name', 'staff')->first();
            $csDept = DB::table('departments')->where('code', 'CS')->first();
            $eeDept = DB::table('departments')->where('code', 'EE')->first();
            
            if ($staffRole && $csDept && $eeDept) {
                // Create staff users
                $staffUsers = [
                    [
                        'name' => 'Dr. Sarah Wilson',
                        'email' => 'sarah.wilson@abu.edu.ng',
                        'password' => Hash::make('staff123'),
                        'role_id' => $staffRole->id,
                        'phone' => '08012345670',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name' => 'Prof. David Brown',
                        'email' => 'david.brown@abu.edu.ng',
                        'password' => Hash::make('staff123'),
                        'role_id' => $staffRole->id,
                        'phone' => '08012345671',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ];
                
                foreach ($staffUsers as $staffUser) {
                    $userId = DB::table('users')->insertGetId($staffUser);
                    
                    // Create staff records
                    if ($staffUser['name'] === 'Dr. Sarah Wilson') {
                        DB::table('staff')->insertOrIgnore([
                            'user_id' => $userId,
                            'department_id' => $csDept->id,
                            'position' => 'Head of Department',
                            'employee_id' => 'STAFF001',
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    } else {
                        DB::table('staff')->insertOrIgnore([
                            'user_id' => $userId,
                            'department_id' => $eeDept->id,
                            'position' => 'Lecturer',
                            'employee_id' => 'STAFF002',
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }
        
        // 5. Create Sample Complaints
        if (Schema::hasTable('complaints') && Schema::hasTable('users') && Schema::hasTable('complaint_categories') && Schema::hasTable('complaint_statuses')) {
            $studentUser = DB::table('users')->where('email', 'john.doe@student.abu.edu.ng')->first();
            $csDept = DB::table('departments')->where('code', 'CS')->first();
            $academicCategory = DB::table('complaint_categories')->where('name', 'academic')->first();
            $pendingStatus = DB::table('complaint_statuses')->where('name', 'pending')->first();
            
            if ($studentUser && $csDept && $academicCategory && $pendingStatus) {
                $complaints = [
                    [
                        'user_id' => $studentUser->id,
                        'category_id' => $academicCategory->id,
                        'status_id' => $pendingStatus->id,
                        'department_id' => $csDept->id,
                        'title' => 'Late Assignment Submission',
                        'description' => 'I was unable to submit my programming assignment on time due to technical issues with the submission portal. The system was down for maintenance during the submission period.',
                        'location' => 'Computer Science Department',
                        'priority' => 'medium',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'user_id' => $studentUser->id,
                        'category_id' => $academicCategory->id,
                        'status_id' => $pendingStatus->id,
                        'department_id' => $csDept->id,
                        'title' => 'Course Registration Problem',
                        'description' => 'I am unable to register for CS401 Advanced Programming course. The system shows the course is full but I have all prerequisites completed.',
                        'location' => 'Academic Affairs Office',
                        'priority' => 'high',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ];
                
                foreach ($complaints as $complaint) {
                    DB::table('complaints')->insertOrIgnore($complaint);
                }
            }
        }
        
        // Get counts
        $deptCount = DB::table('departments')->count();
        $categoryCount = DB::table('complaint_categories')->count();
        $statusCount = DB::table('complaint_statuses')->count();
        $studentCount = DB::table('users')->where('role_id', DB::table('roles')->where('name', 'user')->value('id'))->count();
        $staffCount = DB::table('users')->where('role_id', DB::table('roles')->where('name', 'staff')->value('id'))->count();
        $complaintCount = DB::table('complaints')->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Complete system setup completed successfully!',
            'summary' => [
                'departments' => $deptCount,
                'complaint_categories' => $categoryCount,
                'complaint_statuses' => $statusCount,
                'students' => $studentCount,
                'staff' => $staffCount,
                'complaints' => $complaintCount
            ],
            'login_credentials' => [
                'admin' => 'admin@abu.edu.ng / admin123',
                'students' => [
                    'john.doe@student.abu.edu.ng / student123',
                    'jane.smith@student.abu.edu.ng / student123',
                    'mike.johnson@student.abu.edu.ng / student123'
                ],
                'staff' => [
                    'sarah.wilson@abu.edu.ng / staff123',
                    'david.brown@abu.edu.ng / staff123'
                ]
            ]
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
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
