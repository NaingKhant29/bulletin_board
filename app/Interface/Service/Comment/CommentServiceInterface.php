<?php

namespace App\Interface\Service\Comment;

interface CommentServiceInterface
{
    /**
     * @param int $postId
     * @param array $data
     * @return mixed
     */
    public function storeComment($postId, array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteComment($id);
}
