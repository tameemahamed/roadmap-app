<?php

namespace Database\Seeders;

use App\Models\Roadmap;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoadmapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Roadmap::insert([
            [ 
                'title' => 'Website Redesign',
                'description' => 'Complete overhaul of public-facing website with modern UI/UX',
                'creator_user_id' => rand(2,6),
                'status_id' => rand(1,3),
                'preview_available_date' => fake()->optional(0.6)->dateTimeBetween('2025-01-01', '2026-12-31'),
                'rollout_start_date' => fake()->optional(0.4)->dateTimeBetween('2025-01-01', '2026-12-31'),
                'created_at' => fake()->dateTimeBetween('2025-01-01', Carbon::now())->format('Y-m-d H:i:s')
            ],
            [
                'title' => 'Mobile App Launch',
                'description' => 'New cross-platform application for iOS and Android',
                'creator_user_id' => rand(2,6),
                'status_id' => rand(1,3),
                'preview_available_date' => fake()->optional(0.6)->dateTimeBetween('2025-01-01', '2026-12-31'),
                'rollout_start_date' => fake()->optional(0.4)->dateTimeBetween('2025-01-01', '2026-12-31'),
                'created_at' => fake()->dateTimeBetween('2025-01-01', Carbon::now())->format('Y-m-d H:i:s')
            ]
        ]);
        for($i=0;$i<15;$i++) {
            Roadmap::insert([
                'title' => fake()->sentence(rand(3,5)),
                'description' => fake()->paragraph(3),
                'creator_user_id' => rand(2,6),
                'status_id' => rand(1,3),
                'preview_available_date' => fake()->optional(0.6)->dateTimeBetween('2025-01-01', '2026-12-31'), 
                'rollout_start_date' => fake()->optional(0.4)->dateTimeBetween('2025-01-01', '2026-12-31'),
                'created_at' => fake()->dateTimeBetween('2025-01-01', Carbon::now())->format('Y-m-d H:i:s')
            ]);
        }
    }
}
