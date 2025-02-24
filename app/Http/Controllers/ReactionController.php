<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    /**
     * Store or update a user's reaction to a post.
     *
     * @param \Illuminate\Http\Request $request The request object containing post ID and reaction type.
     * @return \Illuminate\Http\JsonResponse A JSON response with updated reaction counts.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'type' => 'required|string',
        ]);

        $reaction = Reaction::updateOrCreate(
            ['user_id' => Auth::id(), 'post_id' => $request->post_id],
            ['type' => $request->type]
        );

        $likeCount = Reaction::where('post_id', $request->post_id)->where('type', 'like')->count();
        $loveCount = Reaction::where('post_id', $request->post_id)->where('type', 'love')->count();
        $hahaCount = Reaction::where('post_id', $request->post_id)->where('type', 'haha')->count();

        return response()->json([
            'success' => true,
            'likeCount' => $likeCount,
            'loveCount' => $loveCount,
            'hahaCount' => $hahaCount
        ]);
    }
}
