<?php

namespace App\Dao\Comment;

use App\Interface\Dao\Comment\CommentDaoInterface;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentDao implements CommentDaoInterface
{
    /**
     * Store a new comment.
     *
     * @param array $data
     * @return Comment
     */
    public function storeComment(array $data): Comment
    {
        return Comment::create($data);
    }

    /**
     * Delete a comment by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteComment($id): bool
    {
        $comment = Comment::find($id);

        if (!$comment || Auth::id() !== $comment->user_id) {
            return false;
        }

        return $comment->delete();
    }

    /**
     * Get all comments for a specific post.
     *
     * @param int $postId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCommentsByPostId($postId)
    {
        return Comment::where('post_id', $postId)
                      ->orderBy('created_at', 'asc')
                      ->with('user') // eager load user relation
                      ->get();
    }
}
