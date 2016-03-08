<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TopicReply extends Model
{
    protected $table = 'topics_reply';

    //Get topic replies
    public function getUserReplies($user_uuid)
    {

        return $topic = DB::table('topics_reply')
            ->select('topics_reply.topic_uuid as topic_uuid',
                     'topics_reply.body',
                     'topics_reply.created_at as replycreated_at',
                     'topics.topic',
                     'topics.slug',
                     'users.firstname',
                     'users.displayname',
                     'users.profile_img',
                     'users.uuid')
            ->orderBy('topics_reply.created_at', 'desc')
            ->join('users', 'topics_reply.uid', '=', 'users.uuid')
            ->join('topics', 'topics.uuid', '=', 'topics_reply.topic_uuid')
            ->where('topics_reply.uid', $user_uuid)
            ->get();
    }
}
