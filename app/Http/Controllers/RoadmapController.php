<?php

namespace App\Http\Controllers;

use App\Models\Roadmap;
use App\Models\RoadmapComment;
use App\Models\RoadmapCommentReply;
use App\Models\RoadmapUpvote;
use App\Models\Status;
use App\Models\Tag;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoadmapController extends Controller
{
    //
    public function filteredRoadmaps($status_ids, $filter_upvotes) {
        if (! is_array($status_ids)) {
            $status_ids = explode(',', $status_ids);
        }
        $query = Roadmap::whereIn('status_id', $status_ids)
                        ->select(['*'])
                        ->addSelect([
                            'status' => Status::select('status_name')
                                           ->whereColumn('statuses.id', 'roadmaps.status_id')
                                           ->limit(1),
                            'upvotes_count' => RoadmapUpvote::selectRaw('Count(DISTINCT user_id)')
                                ->whereColumn('roadmap_id', 'roadmaps.id'),
                            'comments_count' => RoadmapComment::selectRaw('COUNT(user_id)')
                                ->whereColumn('roadmap_id', 'roadmaps.id'),
                            'upvotted' => RoadmapUpvote::selectRaw('IF(COUNT(*) > 0, 1, 0)')
                                ->whereColumn('roadmap_id', 'roadmaps.id')
                                ->where('user_id', Auth::id())
                        ]);

        if($filter_upvotes == 1) {
            $query->orderBy('upvotes_count', 'desc');
        }
        $query->orderBy('created_at', 'desc');
        $roadmaps = $query->get();
        // Add Tags
        $roadmaps->each(function ($roadmap) {
            $tags = Tag::where('roadmap_id', $roadmap->id)
                        ->pluck('name')
                        ->toArray();
            $roadmap->tags = $tags;
        });
        
        return response()->json([
            'roadmaps' => $roadmaps
        ]);
    }

    public function selectedRoadmap($roadmap_id) {
        $roadmap = Roadmap::where('id', $roadmap_id)
                        ->select(['*'])
                        ->addSelect([
                            'status' => Status::select('status_name')
                                           ->whereColumn('statuses.id', 'roadmaps.status_id')
                                           ->limit(1),
                            'upvotes_count' => RoadmapUpvote::selectRaw('Count(DISTINCT user_id)')
                                ->whereColumn('roadmap_id', 'roadmaps.id'),
                            'comments_count' => RoadmapComment::selectRaw('COUNT(user_id)')
                                ->whereColumn('roadmap_id', 'roadmaps.id'),
                            'upvotted' => RoadmapUpvote::selectRaw('IF(COUNT(*) > 0, 1, 0)')
                                ->whereColumn('roadmap_id', 'roadmaps.id')
                                ->where('user_id', Auth::id())
                        ])
                        ->firstOrFail();
        $tags = Tag::where('roadmap_id', $roadmap->id)
                    ->pluck('name')
                    ->toArray();
        $roadmap->tags = $tags;

        $comments = RoadmapComment::where('roadmap_id', $roadmap_id)
                    ->join('users', 'roadmap_comments.user_id', '=', 'users.id')
                    ->orderBy('created_at', 'desc')
                    ->get([
                        'roadmap_comments.*',
                        'users.name'
                    ]);
        $comments->each(function ($comment) {
            $replies = RoadmapCommentReply::where('comment_id', $comment->id)
                        ->join('users', 'roadmap_comment_replies.user_id', '=', 'users.id')
                        ->orderBy('created_at', 'asc')
                        ->get([
                            'roadmap_comment_replies.*',
                            'users.name'
                        ]);
            $comment->replies = $replies;
        });
        return response()->json([
            'roadmap' => $roadmap,
            'comments' => $comments
        ]);

    }

    public function liked(Request $req) {
        $roadmap_id = $req->roadmap_id;
        $user_id = Auth::id();
        $upvotted = RoadmapUpvote::where('roadmap_id', $roadmap_id)
            ->where('user_id', $user_id)
            ->exists();
        if($upvotted) {
            RoadmapUpvote::where('roadmap_id', $roadmap_id)
                ->where('user_id', $user_id)
                ->delete();
        }
        else {
            RoadmapUpvote::insert([
                'roadmap_id' => $roadmap_id,
                'user_id' => $user_id
            ]);
        }
    }

    public function addComment(Request $req) {
        $roadmap_id = $req->roadmap_id;
        $user_id = Auth::id();
        $content = $req->content;
        RoadmapComment::insert([
            'roadmap_id' => $roadmap_id, 
            'user_id' => $user_id,
            'content' => $content,
            'created_at' => now()
        ]);
    }

    public function editComment(Request $req){
        $comment_id = $req->comment_id;
        $content = $req->content;
        $user_id = Auth::id();
        
        RoadmapComment::where('id', $comment_id)
            ->where('user_id', $user_id)
            ->update([
                'content' => $content,
                'edited' => 1,
                'updated_at' => now()
            ]);
    }

    public function deleteComment(Request $req) {
        $comment_id = $req->comment_id;
        $user_id = Auth::id();
        RoadmapComment::where('id', $comment_id)
            ->where('user_id', $user_id)
            ->delete();
    }

    public function addReply(Request $req) {
        $comment_id = $req->comment_id;
        $user_id = Auth::id();
        $content = $req->content;
        RoadmapCommentReply::insert([
            'comment_id' => $comment_id,
            'user_id' => $user_id,
            'content' => $content,
            'created_at' => now()
        ]);
    }

    public function editReply(Request $req) {
        $reply_id = $req->reply_id;
        $content = $req->content;
        $user_id = Auth::id();
        RoadmapCommentReply::where('id', $reply_id)
            ->where('user_id', $user_id)
            ->update([
                'content' => $content,
                'edited' => 1,
                'updated_at' => now()
            ]);
    }

    public function deleteReply(Request $req) {
        $reply_id = $req->reply_id;
        $user_id = Auth::id();
        RoadmapCommentReply::where('id', $reply_id)
            ->where('user_id', $user_id)
            ->delete();
    }
}
