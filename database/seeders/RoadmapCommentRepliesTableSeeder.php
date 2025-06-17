<?php

namespace Database\Seeders;

use App\Models\RoadmapComment;
use App\Models\RoadmapCommentReply;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoadmapCommentRepliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $comments = RoadmapComment::select('id', 'created_at')->get()->keyBy('id');
        $comment_IDs = $comments->pluck('id')->toArray();
        for($i=0;$i<300;$i++) {
            $user_id = rand(7,15);
            $comment_id = $comment_IDs[array_rand($comment_IDs)];
            $comment_created_at = $comments->get($comment_id);
            $current_time = Carbon::now();
            $created_at = fake()->dateTimeBetween($comment_created_at, $current_time)->format('Y-m-d H:i:s');
            RoadmapCommentReply::insert([
                'comment_id' => $comment_id,
                'user_id' => $user_id,
                'content' => fake()->paragraph(1),
                'created_at' => $created_at,
                'updated_at' => $created_at
            ]);
        }
    }
}
