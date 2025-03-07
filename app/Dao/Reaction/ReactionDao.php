<?php

namespace App\Dao\Reaction;

use App\Interface\Dao\Reaction\ReactionDaoInterface;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class ReactionDao implements ReactionDaoInterface
{
    /**
     * Store or update a reaction for a post and return reaction counts.
     *
     * @param int $postId The post ID.
     * @param string $type The reaction type ('like', 'love', 'haha').
     * @return array Reaction counts ('likeCount', 'loveCount', 'hahaCount').
     */
    public function storeOrUpdateReaction($postId, $type): array
    {
        // Update or create the reaction
        $reaction = Reaction::updateOrCreate(
            ['user_id' => Auth::id(), 'post_id' => $postId],
            ['type' => $type]
        );

        // Get the reaction counts
        $likeCount = Reaction::where('post_id', $postId)->where('type', 'like')->count();
        $loveCount = Reaction::where('post_id', $postId)->where('type', 'love')->count();
        $hahaCount = Reaction::where('post_id', $postId)->where('type', 'haha')->count();

        return [
            'likeCount' => $likeCount,
            'loveCount' => $loveCount,
            'hahaCount' => $hahaCount,
        ];
    }
}
