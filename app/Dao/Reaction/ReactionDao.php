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
        $userId = Auth::id();
        $existingReaction = Reaction::where('user_id', $userId)->where('post_id', $postId)->first();
    
        // If the user already reacted
        if ($existingReaction) {
            if ($existingReaction->type === $type) {
                // If the user clicks on the same reaction again, remove it
                $existingReaction->delete();
                return [
                    'removedReaction' => true,
                    'likeCount' => Reaction::where('post_id', $postId)->where('type', 'like')->count(),
                    'loveCount' => Reaction::where('post_id', $postId)->where('type', 'love')->count(),
                    'hahaCount' => Reaction::where('post_id', $postId)->where('type', 'haha')->count(),
                    'userReaction' => null // Reaction was removed
                ];
            } else {
                // If the user clicked a different reaction, update it
                $existingReaction->update(['type' => $type]);
            }
        } else {
            // If no reaction, create a new one
            Reaction::create(['user_id' => $userId, 'post_id' => $postId, 'type' => $type]);
        }
    
        return [
            'removedReaction' => false, // No reaction was removed
            'likeCount' => Reaction::where('post_id', $postId)->where('type', 'like')->count(),
            'loveCount' => Reaction::where('post_id', $postId)->where('type', 'love')->count(),
            'hahaCount' => Reaction::where('post_id', $postId)->where('type', 'haha')->count(),
            'userReaction' => $type // Return the current reaction type
        ];
    }
    
    
}
