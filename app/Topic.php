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

    public function dwnvoteTopic($topics_uuid)
    {
        $this->where('uuid',$topics_uuid)->increment('dwnvote');

        $count =DB::table('topics')
                ->select('dwnvote')
                ->where('uuid',$topics_uuid)
                ->first();
        return $count->upvote;
    }

    public function upvoteTopic($topics_uuid,$flg)
    {
        if($flg)
        {
            $this->where('uuid',$topics_uuid)->increment('upvote');
        }else{
            $this->where('uuid',$topics_uuid)->decrement('upvote');
        }

        $count =DB::table('topics')
                    ->select('upvote')
                    ->where('uuid',$topics_uuid)
                    ->first();
        return $count->upvote;
    }

    //Get the recently created topics
    public function recentlyCreated()
    {

        $time = date("Ymd");
        $results = Cache::remember('topic_posts_cache_'.$time,1,function() use ($time) {
            return $topic = $this->where('flg', 1)
                ->select(
                    'topics.topic',
                    'topics.body',
                    'topics.text',
                    'topics.uid as topics_uid',
                    'topics.slug as topic_slug',
                    'topics.tags',
                    /*'topics.upvote',
                    'topics.dwnvote',*/
                    'topics.comments',
                    'topics.uuid as topic_uuid',
                    'topics.created_at as topic_created_at',
                    'users.firstname',
                    'users.profile_img',
                    'users.displayname',
                    'users.description'
                )
                ->join('users', 'topics.uid', '=', 'users.uuid')
                ->orderBy('topics.created_at', 'desc')
                ->take(10)
                ->get();
        });
        $log = DB::getQueryLog();
        print_r($log);

        return $results;
    }


    //Get user topic
    public function getUserTopic($user_uuid)
    {
        $topics = $this
                ->where('topics.uid', $user_uuid)
                ->join('users','users.uuid','=','topics.uid')
                ->get();
        return $topics;
    }

    // Get feed from slug
    public function getFeed($slug)
    {
        $topics =   DB::table('categories')
                        ->select(
                                'topics.topic',
                                'topics.body',
                                'topics.text',
                                'topics.uid as topics_uid',
                                'topics.slug as topic_slug',
                                'topics.tags',
                                /*'topics.upvote',
                                'topics.dwnvote',
                                'topics.comments',*/
                                'topics.uuid as topic_uuid',
                                'topics.created_at as topic_created_at',
                                'users.firstname',
                                'users.profile_img',
                                'users.displayname',
                                'users.description'
                                )   
                        ->where('categories.slug',$slug)
                        ->join('topics', 'topics.category', '=', 'categories.id')
                        ->join('users','users.uuid','=','topics.uid')
                        ->get();
        return $topics;
    }


    //Return only one reply
    public function getSingleReply($reply_id)
    {
        return $topic = DB::table('topics_reply')
            ->select('topics_reply.topic_uuid as topic_uuid',
                'topics_reply.body',
                'topics_reply.created_at as replycreated_at',
                'users.firstname',
                'users.profile_img',
                'users.displayname',
                'users.description'
            )
            ->orderBy('topics_reply.created_at', 'desc')
            ->join('users', 'topics_reply.uid', '=', 'users.uuid')
            ->where('topics_reply.id', $reply_id)
            ->first();
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
                        'topics.text',
                        'topics.uid as topics_uid',
                        'topics.slug as topic_slug',
                        'topics.tags',
                        /*'topics.upvote',
                        'topics.dwnvote',
                        'topics.comments',*/
                        'topics.uuid as topic_uuid',
                        'topics.created_at as topic_created_at',
                        'users.firstname',
                        'users.profile_img',
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
                'topics.text',
                'topics.uid as topics_uid',
                'topics.slug as topic_slug',
                'topics.tags',
                /*'topics.upvote',
                'topics.dwnvote',
                'topics.comments',*/
                'topics.uuid as topic_uuid',
                'topics.created_at as topic_created_at',
                'users.firstname',
                'users.profile_img',
                'users.displayname',
                'users.description'
            )
            ->join('topics', 'topics.uuid', '=', 'tags.topic_uuid')
            ->join('users', 'topics.uid', '=', 'users.uuid')
            ->where('tags.title',clean($tag))
            ->get();
    }
}
