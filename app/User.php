<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname', 'uuid', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     *  Incremenet the number of posts by user
     */
    public function incrementPost($uuid)
    {
        DB::table('users')->where('uuid',$uuid)->increment('posts');
    }

    /**
     * Get all of the posts for the user.
     */
    public function posts()
    {
        return $this->hasMany('App\Topics');
    }
}
