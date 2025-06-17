<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Tags for Roadmap 1 (Website Redesign)
        Tag::insert([
            [
                'name' => 'Web Design',
                'roadmap_id' => 1
            ],
            [
                'name' => 'UI/UX Modernization',
                'roadmap_id' => 1
            ],
            [
                'name' => 'Frontend Overhaul',
                'roadmap_id' => 1
            ],
            [
                'name' => 'Responsive Layout',
                'roadmap_id' => 1
            ]
        ]);

        // Tags for Roadmap 2 (Mobile App Launch)
        Tag::insert([
            [
                'name' => 'Mobile Development',
                'roadmap_id' => 2
            ],
            [
                'name' => 'Cross-Platform',
                'roadmap_id' => 2
            ],
            [
                'name' => 'iOS/Android',
                'roadmap_id' => 2
            ],
            [
                'name' => 'App Deployment',
                'roadmap_id' => 2
            ]
        ]);

        for($i=0;$i<70;$i++) {
            $roadmap_id = rand(3, 17);
            $tag_name = fake()->words(rand(1,4), true);
            Tag::insert([
                'name' => $tag_name,
                'roadmap_id' => $roadmap_id
            ]);
        }
    }
}
