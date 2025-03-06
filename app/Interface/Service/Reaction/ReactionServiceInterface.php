<?php

namespace App\Interface\Service\Reaction;

interface ReactionServiceInterface
{
    public function storeOrUpdateReaction($postId, $type);
}
