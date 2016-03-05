<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Notification extends Model
{
    protected $table = 'notification';


    public function store($type,
                          $to_follow_uuid,
                          $user,
                          $obj_id,
                          $body='follow')
    {
        $notification = new Notification();

        //type 1 is cate
        //type 2 is follow
        //type 3 is reply
        //type 4 is upvote

        $notification->type         = $type;
        $notification->recipient    = $to_follow_uuid;
        $notification->sender       = $user;
        $notification->body         = $body;
        $notification->obj_id       = $obj_id;
        $notification->save();
    }


    //List all user notification
    public function listNotification($user)
    {
        $notification = Notification::where('recipient',$user)
            ->select(
                'users.displayname',
                'users.firstname',
                'notification.body',
                'notification.created_at',
                'topics.topic',
                'topics.slug'
                )
            ->orderBy('notification.created_at', 'desc')
            ->join('users', 'notification.recipient', '=', 'users.uuid')
            ->join('topics', 'notification.obj_id', '=', 'topics.uuid')
            ->limit(15)
            ->get();
        return $notification;
    }


    //Count the number of usr notification
    public function countNotification($user)
    {
        $notification = Notification::where('recipient',$user)
                        ->where('read',0)
                        ->count();
        return $notification;
    }


    //Acknowledge notification
    public function ackNotification()
    {
        $notification = DB::table('notification')
            ->where('recipient',Auth::user()->uuid)
            ->update(['read' => 1]);
        return $notification;
    }
}
