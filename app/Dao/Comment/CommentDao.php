<?php

namespace App\Dao\Comment;

use App\Interface\Dao\Comment\CommentDaoInterface;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentDao implements CommentDaoInterface
{
    public function storeComment(array $data)
    {
        return Comment::create($data);
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        if (!$comment || Auth::id() !== $comment->user_id) {
            return false;
        }
        return $comment->delete();
    }
}
