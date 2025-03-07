<?php

namespace App\Interface\Dao\Reaction;

interface ReactionDaoInterface
{
    /**
     * @param int $postId
     * @param string $type
     * @return array
     */
    public function storeOrUpdateReaction($postId, $type);
}
