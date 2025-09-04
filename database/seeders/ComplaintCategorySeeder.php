<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'academic',
                'display_name' => 'Academic Issues',
                'description' => 'Complaints related to academic matters, courses, and education',
                'color' => '#3B82F6'
            ],
            [
                'name' => 'administrative',
                'display_name' => 'Administrative Issues',
                'description' => 'Complaints related to administrative processes and procedures',
                'color' => '#10B981'
            ],
            [
                'name' => 'facilities',
                'display_name' => 'Facilities & Infrastructure',
                'description' => 'Complaints about campus facilities, buildings, and infrastructure',
                'color' => '#F59E0B'
            ],
            [
                'name' => 'hostel',
                'display_name' => 'Hostel & Accommodation',
                'description' => 'Complaints about student housing and accommodation',
                'color' => '#8B5CF6'
            ],
            [
                'name' => 'security',
                'display_name' => 'Security & Safety',
                'description' => 'Complaints about campus security and safety concerns',
                'color' => '#EF4444'
            ],
            [
                'name' => 'technology',
                'display_name' => 'Technology & IT',
                'description' => 'Complaints about IT services, internet, and technology issues',
                'color' => '#06B6D4'
            ],
            [
                'name' => 'transportation',
                'display_name' => 'Transportation',
                'description' => 'Complaints about campus transportation and parking',
                'color' => '#84CC16'
            ],
            [
                'name' => 'other',
                'display_name' => 'Other',
                'description' => 'Other complaints not covered by other categories',
                'color' => '#6B7280'
            ]
        ];

        foreach ($categories as $category) {
            \App\Models\ComplaintCategory::create($category);
        }
    }
}
