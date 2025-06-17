<?php

namespace Database\Seeders;

use App\Models\Roadmap;
use App\Models\RoadmapComment;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoadmapCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for($i=0;$i<60;$i++) {
            $user_id = rand(7,15);
            $roadmap_id = rand(1, 17);
            $already_exists = RoadmapComment::where('user_id',$user_id)
                                ->where('roadmap_id', $roadmap_id)
                                ->exists();
            if($already_exists) continue;
            
            $content = fake()->paragraph(2);
            
            $roadmap = Roadmap::find($roadmap_id);
            $current_time = Carbon::now();
            $created_at = fake()->dateTimeBetween(
                $roadmap->created_at, 
                $current_time
            )->format('Y-m-d H:i:s');
            RoadmapComment::insert([
                'user_id' => $user_id,
                'roadmap_id' => $roadmap_id,
                'content' => $content,
                'created_at' => $created_at,
                'updated_at' => $created_at
            ]);
        }
    }
}
