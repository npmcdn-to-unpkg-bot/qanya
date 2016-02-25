<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Users_follow extends Model
{
    protected $table = 'users_follow';

    public function getFeed($uuid)
    {
        $topics = DB::table('users_follow')
            ->select(
                'topics.topic',
                'topics.body',
                'topics.uid as topics_uid',
                'topics.slug as topic_slug',
                'topics.tags',
                'topics.uuid as topic_uuid',
                'topics.created_at as topic_created_at',
                'users.firstname',
                'users.displayname',
                'users.description'
            )
            ->orderby('topics.created_at','desc')
            ->where('users_follow.uuid',$uuid)
            ->join('topics', 'users_follow.obj_id', '=', 'topics.category')
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

            $notification = new Notification();
            $notification->store(2,$to_follow_uuid,$user,'follow');

            event(new \App\Events\FollowUserEvent($user,$to_follow_uuid));

            return 1;
        }
        else
        {
            DB::table('users_follow')
                ->where('uuid',$user )
                ->where('obj_id',$to_follow_uuid )
                ->delete();

            DB::table('notification')
                ->where('recipient',$to_follow_uuid )
                ->where('sender',$user )
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
