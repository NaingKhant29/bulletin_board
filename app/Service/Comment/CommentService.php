<?php

namespace App\Service\Comment;

use App\Interface\Dao\Comment\CommentDaoInterface;
use App\Interface\Service\Comment\CommentServiceInterface;
use Illuminate\Support\Facades\Auth;

class CommentService implements CommentServiceInterface
{
    protected $commentDao;

    public function __construct(CommentDaoInterface $commentDao)
    {
        $this->commentDao = $commentDao;
    }

    public function storeComment($postId, array $data)
    {
        return $this->commentDao->storeComment([
            'content' => $data['content'],
            'post_id' => $postId,
            'user_id' => Auth::id(),
        ]);
    }

    public function deleteComment($id)
    {
        return $this->commentDao->deleteComment($id);
    }
}
