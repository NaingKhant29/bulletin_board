<?php

namespace App\Interface\Service\Comment;

interface CommentServiceInterface
{
    public function storeComment($postId, array $data);
    public function deleteComment($id);
}
    