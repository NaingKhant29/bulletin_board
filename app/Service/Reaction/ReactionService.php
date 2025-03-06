<?php
// app/Service/Reaction/ReactionService.php

namespace App\Service\Reaction;


use App\Interface\Dao\Reaction\ReactionDaoInterface;
use App\Interface\Service\Reaction\ReactionServiceInterface;

class ReactionService implements ReactionServiceInterface
{
    protected $reactionDao;

    public function __construct(ReactionDaoInterface $reactionDao)
    {
        $this->reactionDao = $reactionDao;
    }

    public function storeOrUpdateReaction($postId, $type)
    {
        return $this->reactionDao->storeOrUpdateReaction($postId, $type);
    }
}
