<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reaction;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'type' => 'required|string',
        ]);

        $reaction = Reaction::updateOrCreate(
            ['user_id' => Auth::id(), 'post_id' => $request->post_id],
            ['type' => $request->type]
        );

        return response()->json(['success' => true, 'reaction' => $reaction]);
    }

    public function index($post_id) {
        $post = Post::with('reactions.user')->findOrFail($post_id);
        return response()->json($post->reactions);
    }
}
