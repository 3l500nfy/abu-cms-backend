<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Complaint;
use App\Models\Role;
use App\Models\ComplaintCategory;
use App\Models\ComplaintStatus;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample students
        $studentRole = Role::where('name', 'student')->first();
        $staffRole = Role::where('name', 'staff')->first();
        
        $students = [
            ['name' => 'John Doe', 'email' => 'john@student.com', 'student_id' => 'STU001'],
            ['name' => 'Jane Smith', 'email' => 'jane@student.com', 'student_id' => 'STU002'],
            ['name' => 'Mike Johnson', 'email' => 'mike@student.com', 'student_id' => 'STU003'],
        ];

        foreach ($students as $studentData) {
            User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('password123'),
                    'student_id' => $studentData['student_id'],
                    'role_id' => $studentRole->id,
                ]
            );
        }

        // Create sample staff
        $staff = [
            ['name' => 'Dr. Sarah Wilson', 'email' => 'sarah@staff.com', 'department' => 'Computer Science'],
            ['name' => 'Prof. Robert Brown', 'email' => 'robert@staff.com', 'department' => 'Engineering'],
        ];

        foreach ($staff as $staffData) {
            User::firstOrCreate(
                ['email' => $staffData['email']],
                [
                    'name' => $staffData['name'],
                    'password' => Hash::make('password123'),
                    'department' => $staffData['department'],
                    'role_id' => $staffRole->id,
                ]
            );
        }

        // Get categories and statuses
        $categories = ComplaintCategory::all();
        $statuses = ComplaintStatus::all();

        // Create sample complaints only if they don't exist
        $complaints = [
            [
                'title' => 'Broken Air Conditioning in Library',
                'description' => 'The air conditioning unit in the main library is not working properly. It\'s very hot and uncomfortable for studying.',
                'category' => 'facilities',
                'status' => 'pending',
                'priority' => 'high',
                'location' => 'Main Library, 2nd Floor',
                'user_email' => 'john@student.com'
            ],
            [
                'title' => 'Slow Internet Connection in Computer Lab',
                'description' => 'Internet connection is extremely slow in the computer lab. It takes forever to load web pages and download files.',
                'category' => 'technology',
                'status' => 'assigned',
                'priority' => 'medium',
                'location' => 'Computer Lab A, Building 3',
                'user_email' => 'jane@student.com'
            ],
            [
                'title' => 'Leaking Roof in Lecture Hall',
                'description' => 'There is a leak in the roof of lecture hall 101. Water is dripping during rainy days and damaging the equipment.',
                'category' => 'facilities',
                'status' => 'in_progress',
                'priority' => 'urgent',
                'location' => 'Lecture Hall 101, Building 1',
                'user_email' => 'mike@student.com'
            ],
            [
                'title' => 'Unsafe Electrical Wiring',
                'description' => 'I noticed exposed electrical wiring in the cafeteria. This is a serious safety hazard that needs immediate attention.',
                'category' => 'facilities',
                'status' => 'resolved',
                'priority' => 'urgent',
                'location' => 'Cafeteria, Ground Floor',
                'user_email' => 'john@student.com'
            ],
            [
                'title' => 'Missing Course Materials',
                'description' => 'The course materials for Advanced Mathematics are not available in the library. Students cannot complete their assignments.',
                'category' => 'academic',
                'status' => 'pending',
                'priority' => 'medium',
                'location' => 'Library, Reference Section',
                'user_email' => 'jane@student.com'
            ],
            [
                'title' => 'Broken Projector in Room 205',
                'description' => 'The projector in room 205 is not working. It shows a blue screen and makes loud noises when turned on.',
                'category' => 'technology',
                'status' => 'assigned',
                'priority' => 'high',
                'location' => 'Room 205, Building 2',
                'user_email' => 'mike@student.com'
            ],
            [
                'title' => 'Dirty Restrooms',
                'description' => 'The restrooms in the student center are very dirty and lack proper maintenance. This is affecting student health.',
                'category' => 'facilities',
                'status' => 'in_progress',
                'priority' => 'medium',
                'location' => 'Student Center, Ground Floor',
                'user_email' => 'john@student.com'
            ],
            [
                'title' => 'Parking Lot Security Issue',
                'description' => 'There have been several car break-ins in the student parking lot. Security cameras are not working properly.',
                'category' => 'security',
                'status' => 'pending',
                'priority' => 'high',
                'location' => 'Student Parking Lot A',
                'user_email' => 'jane@student.com'
            ]
        ];

        $createdCount = 0;
        foreach ($complaints as $complaintData) {
            $user = User::where('email', $complaintData['user_email'])->first();
            $category = $categories->where('name', $complaintData['category'])->first();
            $status = $statuses->where('name', $complaintData['status'])->first();

            // Check if complaint already exists
            $existingComplaint = Complaint::where('title', $complaintData['title'])
                ->where('user_id', $user->id)
                ->first();

            if (!$existingComplaint) {
                Complaint::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'status_id' => $status->id,
                    'title' => $complaintData['title'],
                    'description' => $complaintData['description'],
                    'location' => $complaintData['location'],
                    'priority' => $complaintData['priority'],
                    'resolved_at' => $complaintData['status'] === 'resolved' ? now() : null,
                    'resolution_notes' => $complaintData['status'] === 'resolved' ? 'Issue has been resolved by maintenance team.' : null,
                ]);
                $createdCount++;
            }
        }

        echo "Sample data created successfully!\n";
        echo "Complaints created: " . $createdCount . "\n";
    }
}
