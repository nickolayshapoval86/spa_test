<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int id
 * @property string reddit_id
 * @property string headline
 * @property string content
 * @property int user_id
 * @property bool is_deleted

 * @property-read User user
 */
class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['reddit_id', 'headline', 'content', 'user_id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
