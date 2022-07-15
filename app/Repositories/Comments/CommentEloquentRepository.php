<?php

namespace App\Repositories\Comments;

use App\Models\Comment;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;

class CommentEloquentRepository extends EloquentRepository implements CommentRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return Comment::class;
    }

    /**
     * Get all comment by news id
     *
     * @param $newsId
     * @return \Illuminate\Support\Collection
     */
    public function getAllParentCommentByNewsId($newsId)
    {
        return DB::table("comments")
            ->where("new_id", $newsId)
            ->where("parent_id", 0)
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.*', 'users.name', 'users.avatar')
            ->get();
    }

    /**
     * get total children comment by parent comment id
     *
     * @param $id
     * @return int
     */
    public function getTotalChildrenCommentByParentCommentId($id){
        return DB::table("comments")
            ->where("parent_id", $id)
            ->count();
    }
    /**
     * Edit comment
     *
     * @param $comment
     * @param $newsId
     * @return int
     */
    public function updateComment($comment,$id)
    {
        return DB::table("comments")
            ->where("id", $id)
            ->update(['content' => $comment]);
    }

    /**
     * Get all reply comment by parent id
     *
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getAllReplyCommentByParentId($id) {
        return DB::table("comments")
            ->where("parent_id", $id)
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.*', 'users.name', 'users.avatar')
            ->get();
    }
}
