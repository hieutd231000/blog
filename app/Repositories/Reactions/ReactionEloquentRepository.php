<?php

namespace App\Repositories\Reactions;

use App\Models\Reaction;
use App\Repositories\Eloquent\EloquentRepository;

class ReactionEloquentRepository extends EloquentRepository implements ReactionRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return Reaction::class;
    }
}
