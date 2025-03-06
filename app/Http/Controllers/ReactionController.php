<?php

namespace App\Http\Controllers;

use App\Interface\Service\Reaction\ReactionServiceInterface;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    protected $reactionService;

    public function __construct(ReactionServiceInterface $reactionService)
    {
        $this->reactionService = $reactionService;
    }

    /**
     * Store or update a user's reaction to a post.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'type' => 'required|string',
        ]);

        $reactionCounts = $this->reactionService->storeOrUpdateReaction($request->post_id, $request->type);

        return response()->json([
            'success' => true,
            'likeCount' => $reactionCounts['likeCount'],
            'loveCount' => $reactionCounts['loveCount'],
            'hahaCount' => $reactionCounts['hahaCount']
        ]);
    }
}
