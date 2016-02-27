<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cache;
use Illuminate\Http\Response;

class Topic extends Model
{

    protected $table = 'topics';

    //Get the recently created topics
    public function recentlyCreated()
    {
        $time = date("Ymd");
        $results = Cache::remember('topic_posts_cache_'.$time,1,function() use ($time) {
            return $topic = $this->where('flg', 1)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        });
        return $results;
    }


    //Get topic replies
    public function getReplies($topic_uuid)
    {

        return $topic = DB::table('topics_reply')
            ->select('topics_reply.topic_uuid as topic_uuid',
                     'topics_reply.body',
                     'topics_reply.created_at as replycreated_at',
                     'users.*'
            )
            ->orderBy('topics_reply.created_at', 'desc')
            ->join('users', 'topics_reply.uid', '=', 'users.uuid')
            ->where('topics_reply.topic_uuid', $topic_uuid)
            ->limit(10)
            ->get();
    }

    //Get topic information
    public function getTopic($slug)
    {
        $results = Cache::remember('topic_posts_cache_'.$slug,1,function() use ($slug){
            return $topic = DB::table('topics')
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
                ->join('users', 'topics.uid', '=', 'users.uuid')
                ->where('topics.slug',$slug)
                ->first();
        });
        return $results;
    }



    public function getTagTopic($tag)
    {
        return $topic = DB::table('tags')
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
            ->join('topics', 'topics.uuid', '=', 'tags.topic_uuid')
            ->join('users', 'topics.uid', '=', 'users.uuid')
            ->where('tags.title',$tag)
            ->get();
    }
}
