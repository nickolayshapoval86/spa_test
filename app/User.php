<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany('App\Post');
    }
}
