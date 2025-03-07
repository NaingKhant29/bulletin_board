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

    /**
     * Store a comment for a specific post.
     *
     * @param int $postId The ID of the post to associate the comment with.
     * @param array $data The data of the comment, including content.
     * @return mixed
     */
    public function storeComment($postId, array $data)
    {
        return $this->commentDao->storeComment([
            'content' => $data['content'],
            'post_id' => $postId,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Delete a comment by its ID.
     *
     * @param int $id The ID of the comment to delete.
     * @return mixed
     */
    public function deleteComment($id)
    {
        return $this->commentDao->deleteComment($id);
    }
}
