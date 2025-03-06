<?php

namespace App\Interface\Dao\Comment;

interface CommentDaoInterface
{
    public function storeComment(array $data);
    public function deleteComment($id);
}
