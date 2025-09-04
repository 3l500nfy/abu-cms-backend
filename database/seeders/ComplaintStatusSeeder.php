<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'pending',
                'display_name' => 'Pending',
                'description' => 'Complaint submitted and waiting for review',
                'color' => '#6B7280'
            ],
            [
                'name' => 'assigned',
                'display_name' => 'Assigned',
                'description' => 'Complaint has been assigned to a staff member',
                'color' => '#3B82F6'
            ],
            [
                'name' => 'in_progress',
                'display_name' => 'In Progress',
                'description' => 'Work on the complaint is currently in progress',
                'color' => '#F59E0B'
            ],
            [
                'name' => 'resolved',
                'display_name' => 'Resolved',
                'description' => 'Complaint has been resolved successfully',
                'color' => '#10B981'
            ],
            [
                'name' => 'closed',
                'display_name' => 'Closed',
                'description' => 'Complaint has been closed',
                'color' => '#374151'
            ]
        ];

        foreach ($statuses as $status) {
            \App\Models\ComplaintStatus::create($status);
        }
    }
}
