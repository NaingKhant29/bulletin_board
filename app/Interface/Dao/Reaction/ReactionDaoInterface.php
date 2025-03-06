<?php

namespace App\Interface\Dao\Reaction;

interface ReactionDaoInterface
{
    public function storeOrUpdateReaction($postId, $type);
}
