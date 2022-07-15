<?php

namespace App\Repositories\User_Reactions;

use App\Models\User_Reaction;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;

class UserReactionEloquentRepository extends EloquentRepository implements UserReactionRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return User_Reaction::class;
    }

    /**
     * Check reaction exist
     *
     * @param $data
     * @return bool
     */
    public function checkExists($data) {
        $reaction = DB::table("user_reactions")
            ->where("user_id", $data["user_id"])
            ->where("new_id", $data["new_id"])
            ->where("reaction_icon", $data["reaction_icon"])
            ->where("comment_id", $data["comment_id"])
            ->first();
        return $reaction === null;
    }

    /**
     * Get all reaction in db
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllReaction() {
        return DB::table("user_reactions")
            ->join('reactions', 'user_reactions.reaction_icon', '=', 'reactions.id')
            ->select('user_reactions.*', 'reactions.icon')
            ->get();
    }
}
