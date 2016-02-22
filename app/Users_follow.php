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
            ->orderby('topics.created_at','desc')
            ->where('users_follow.uuid',$uuid)
            ->join('topics', 'users_follow.obj_id', '=', 'topics.categories')
            ->join('users','users.uuid','=','topics.uid')
            ->get();
        return $topics;
    }

    public function getUserFollowstatus($user,$to_follow_uuid)
    {
        $topics = DB::table('users_follow')
                    ->where('users_follow.uuid',$user)
                    ->where('users_follow.obj_id',$to_follow_uuid)
                    ->count();
        return $topics;
    }

    public function followUser($user,$to_follow_uuid)
    {
        if($this->getUserFollowstatus($user,$to_follow_uuid) == 0)
        {
            $uf = new Users_follow();
            $uf->uuid           = $user;
            $uf->follow_type    = 2;
            $uf->obj_id         = $to_follow_uuid;
            $uf->save();

            event(new \App\Events\FollowUserEvent($user,$to_follow_uuid));

            return 1;
        }
        else
        {
            DB::table('users_follow')
                ->where('uuid',$user )
                ->where('obj_id',$to_follow_uuid )
                ->delete();
            return 0;
        }

    }

    /**
     * Get all of the posts for the user.
     */
    public function posts()
    {
        return $this->hasMany('App\Topics');
    }
}
