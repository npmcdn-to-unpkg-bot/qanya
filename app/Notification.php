<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Notification extends Model
{
    protected $table = 'notification';


    public function store($type,$to_follow_uuid,$user,$body='follow')
    {
        $notification = new Notification();

        //type 1 is cate
        //type 2 is follow
        //type 3 is notify author

        $notification->type         = $type;
        $notification->recipient    = $to_follow_uuid;
        $notification->sender       = $user;
        $notification->body         = $body;
        $notification->save();
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
