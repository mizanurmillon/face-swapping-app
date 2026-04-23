<?php

namespace Database\Seeders;

use App\Models\Tutorial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Tutorial::insert([
            [
                'title' => 'How to Use iFire Protection',
                'description' => 'Learn how to effectively use iFire Protection to safeguard your property from wildfires. This tutorial covers installation, maintenance, and best practices for optimal performance.',
                'video_url' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'iFire Protection Maintenance Guide',
                'description' => 'Keep your iFire Protection system in top condition with our maintenance guide. This tutorial provides step-by-step instructions for regular upkeep and troubleshooting common issues.',
                'video_url' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
