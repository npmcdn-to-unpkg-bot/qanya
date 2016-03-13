<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReplyInReply extends Model
{
    protected $table = 'reply_in_reply';


    public function getReplyInReply($reply_id)
    {
        return $replies = DB::table('reply_in_reply')
            ->select(
                'reply_in_reply.body',
                'reply_in_reply.created_at',
                'users.firstname',
                'users.profile_img',
                'users.displayname',
                'users.description'
            )
            ->join('users', 'reply_in_reply.user_uuid', '=', 'users.uuid')
            ->where('reply_in_reply.reply_id',$reply_id)
            ->get();
    }
}
