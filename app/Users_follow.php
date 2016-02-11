<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Users_follow extends Model
{
    protected $table = 'users_follow';

    public function getFeed($uuid)
    {
        $topics = DB::table('users_follow')
            ->where('users_follow.uuid',$uuid)
            ->join('topics', 'users_follow.obj_id', '=', 'topics.categories')
            ->join('users','users.uuid','=','topics.uid')
            ->get();
        return $topics;
    }
}
