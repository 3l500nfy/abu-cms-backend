<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffRole = Role::where('name', 'staff')->first();
        if (!$staffRole) {
            $staffRole = Role::create(['name' => 'staff', 'display_name' => 'Staff Member']);
        }

        $departments = Department::all();

        $staffData = [
            [
                'name' => 'John IT Manager',
                'email' => 'it.manager@abu.edu.ng',
                'student_id' => 'EMP001',
                'department_code' => 'IT',
                'position' => 'IT Manager',
                'employee_id' => 'EMP001'
            ],
            [
                'name' => 'Sarah Facilities Head',
                'email' => 'facilities.head@abu.edu.ng',
                'student_id' => 'EMP002',
                'department_code' => 'FM',
                'position' => 'Facilities Manager',
                'employee_id' => 'EMP002'
            ],
            [
                'name' => 'Michael Student Affairs',
                'email' => 'student.affairs@abu.edu.ng',
                'student_id' => 'EMP003',
                'department_code' => 'SA',
                'position' => 'Student Affairs Officer',
                'employee_id' => 'EMP003'
            ],
            [
                'name' => 'Lisa Academic Coordinator',
                'email' => 'academic.coord@abu.edu.ng',
                'student_id' => 'EMP004',
                'department_code' => 'AA',
                'position' => 'Academic Coordinator',
                'employee_id' => 'EMP004'
            ],
            [
                'name' => 'David Security Chief',
                'email' => 'security.chief@abu.edu.ng',
                'student_id' => 'EMP005',
                'department_code' => 'SEC',
                'position' => 'Security Chief',
                'employee_id' => 'EMP005'
            ]
        ];

        foreach ($staffData as $staff) {
            $department = $departments->where('code', $staff['department_code'])->first();
            
            if ($department) {
                $user = User::firstOrCreate(
                    ['email' => $staff['email']],
                    [
                        'name' => $staff['name'],
                        'email' => $staff['email'],
                        'student_id' => $staff['student_id'],
                        'password' => Hash::make('password123'),
                        'role_id' => $staffRole->id
                    ]
                );

                Staff::firstOrCreate(
                    ['employee_id' => $staff['employee_id']],
                    [
                        'user_id' => $user->id,
                        'department_id' => $department->id,
                        'position' => $staff['position'],
                        'employee_id' => $staff['employee_id'],
                        'is_active' => true
                    ]
                );
            }
        }
    }
}
