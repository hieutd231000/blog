<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Reaction extends Model
{
    use HasFactory;

    protected $table = "user_reactions";

    protected $fillable = [
        "new_id",
        "user_id",
        "reaction_icon",
        "comment_id",
    ];

    protected $primaryKey = "id";

    /**
     * Relationship with news table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function news()
    {
        return $this->belongsTo(News::class, "new_id", "id");
    }

    /**
     * Relationship with users table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    /**
     * Relationship with reaction table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reaction()
    {
        return $this->belongsTo(Reaction::class, "reaction_icon", "id");
    }
}
