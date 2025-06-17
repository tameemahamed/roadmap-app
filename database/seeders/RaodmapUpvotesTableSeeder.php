<?php

namespace Database\Seeders;

use App\Models\RoadmapUpvote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RaodmapUpvotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for($i=0;$i<100;$i++) {
            $user_id = rand(7,15);
            $roadmap_id = rand(1, 17);
            $already_exists = RoadmapUpvote::where('user_id',$user_id)
                                ->where('roadmap_id', $roadmap_id)
                                ->exists();
            if($already_exists) continue;
            RoadmapUpvote::insert([
                'user_id' => $user_id,
                'roadmap_id' => $roadmap_id
            ]);
        }
    }
}
