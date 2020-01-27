<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int id
 * @property string username

 * @property-read Post posts
 */
class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username'];

    public $timestamps = false;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
