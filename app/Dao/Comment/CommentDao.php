<?php

namespace App\Dao\Comment;

use App\Interface\Dao\Comment\CommentDaoInterface;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentDao implements CommentDaoInterface
{
    /**
     * @param array $data
     * @return Comment
     */
    public function storeComment(array $data): Comment
    {
        return Comment::create($data);
    }

    /**
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
}
