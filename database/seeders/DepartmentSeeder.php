<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'Handles all IT-related complaints and technical issues',
                'email' => 'it@abu.edu.ng',
                'phone' => '+234-123-456-7890',
                'is_active' => true
            ],
            [
                'name' => 'Facilities Management',
                'code' => 'FM',
                'description' => 'Manages building maintenance, repairs, and facility issues',
                'email' => 'facilities@abu.edu.ng',
                'phone' => '+234-123-456-7891',
                'is_active' => true
            ],
            [
                'name' => 'Student Affairs',
                'code' => 'SA',
                'description' => 'Handles student-related issues and welfare',
                'email' => 'studentaffairs@abu.edu.ng',
                'phone' => '+234-123-456-7892',
                'is_active' => true
            ],
            [
                'name' => 'Academic Affairs',
                'code' => 'AA',
                'description' => 'Manages academic-related complaints and curriculum issues',
                'email' => 'academic@abu.edu.ng',
                'phone' => '+234-123-456-7893',
                'is_active' => true
            ],
            [
                'name' => 'Security',
                'code' => 'SEC',
                'description' => 'Handles security-related complaints and safety issues',
                'email' => 'security@abu.edu.ng',
                'phone' => '+234-123-456-7894',
                'is_active' => true
            ]
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
