<?php

namespace App\Repositories\News;

use App\Models\News;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;

class NewEloquentRepository extends EloquentRepository implements NewRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getModel()
    {
        return News::class;
    }

    /**
     * Get total news's comment
     *
     * @param $newId
     * @return int
     */
    public function getTotalComment($newId)
    {
        return DB::table("comments")
            ->where("new_id", $newId)
            ->where("parent_id", 0)
            ->count();
    }
}
