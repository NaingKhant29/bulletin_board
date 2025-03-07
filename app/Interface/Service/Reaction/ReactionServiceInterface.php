<?php

namespace App\Interface\Service\Reaction;

interface ReactionServiceInterface
{
    /**
     * @param int $postId
     * @param string $type
     * @return array
     */
    public function storeOrUpdateReaction($postId, $type);
}
