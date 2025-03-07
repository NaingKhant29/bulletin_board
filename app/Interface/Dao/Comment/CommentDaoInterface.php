<?php

namespace App\Interface\Dao\Comment;

interface CommentDaoInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function storeComment(array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteComment($id);
}
