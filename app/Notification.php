<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Notification extends Model
{
    protected $table = 'notification';


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
