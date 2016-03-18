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

    public function postImages($uuid)
    {
        return  DB::table('topics_img')->where('topic_uuid',$uuid)->limit(4)->get();
    }

    //Get the recently created topics
    public function recentlyCreated()
    {

        $time = date("Ymd");
        $results = Cache::remember('topic_posts_cache_'.$time,1,function() use ($time) {
            return $topic = $this->where('topics.flg',1)
                ->select(
                    'topics.topic',
                    'topics.type as topic_type',
                    'topics.body',
                    'topics.text',
                    'topics.uid as topics_uid',
                    'topics.slug as topic_slug',
                    'topics.tags',
                    'topics.comments',
                    'categories.name as cate_name',
                    'categories.slug as cate_slug',
                    'topics.uuid as topic_uuid',
                    'topics.created_at as topic_created_at',
                    'users.firstname',
                    'users.profile_img',
                    'users.displayname',
                    'users.description'
                )
                ->join('users', 'topics.uid', '=', 'users.uuid')
                ->join('categories', 'topics.category', '=', 'categories.id')
                ->orderBy('topics.created_at', 'desc')
                ->take(10)
                ->get();
        });

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
                                'topics.type as topic_type',
                                'topics.body',
                                'topics.text',
                                'topics.uid as topics_uid',
                                'topics.slug as topic_slug',
                                'topics.tags',
                                'topics.uuid as topic_uuid',
                                'topics.created_at as topic_created_at',
                                'categories.name as cate_name',
                                'categories.slug as cate_slug',
                                'users.firstname',
                                'users.profile_img',
                                'users.displayname',
                                'users.description'
                                )
                        ->join('topics', 'topics.category', '=', 'categories.id')
                        ->join('users','users.uuid','=','topics.uid')
                        ->where('categories.slug',$slug)
                        ->where('topics.flg',1)
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


    //Get topic channel
    public function getTopicChannel($category)
    {
//        $results = Cache::remember('topic_channel_cache_'.$category,1,function() use ($category){
            return $topic = DB::table('topics')
                ->select(
                    'topics.topic',
                    'topics.type as topic_type',
                    'topics.body',
                    'topics.text',
                    'topics.uid as topics_uid',
                    'topics.slug as topic_slug',
                    'topics.tags',
                    'topics.comments',
                    'categories.name as cate_name',
                    'categories.slug as cate_slug',
                    'topics.uuid as topic_uuid',
                    'topics.created_at as topic_created_at',
                    'users.firstname',
                    'users.profile_img',
                    'users.displayname',
                    'users.description'
                )
                ->join('users', 'topics.uid', '=', 'users.uuid')
                ->join('categories', 'topics.category', '=', 'categories.id')
                ->where('categories.slug',$category)
                ->where('topics.flg',1)
                ->get();
//        });
//        return $results;
    }

    //Get topic information
    public function getTopic($slug)
    {
        $results = Cache::remember('topic_posts_cache_'.$slug,1,function() use ($slug){
            return $topic = DB::table('topics')
                ->select(
                    'topics.id',
                    'topics.topic',
                    'topics.type as topic_type',
                    'topics.body',
                    'topics.text',
                    'topics.is_edited',
                    'topics.uid as topics_uid',
                    'topics.slug as topic_slug',
                    'topics.tags',
                    'topics.comments',
                    'categories.name as cate_name',
                    'categories.slug as cate_slug',
                    'topics.uuid as topic_uuid',
                    'topics.created_at as topic_created_at',
                    'topics.updated_at as topic_updated_at',
                    'users.firstname',
                    'users.profile_img',
                    'users.displayname',
                    'users.description'
                )
                ->join('users', 'topics.uid', '=', 'users.uuid')
                ->join('categories', 'topics.category', '=', 'categories.id')
                ->where('topics.slug',$slug)
                ->where('topics.flg',1)
                ->first();
        });
        return $results;
    }


    //Get topic list array (wherein)
    public function getTopicList($uuid_array)
    {
//        $results = Cache::remember('topic_posts_cache_'.$slug,1,function() use ($slug){
            return $topic = DB::table('topics')
                ->select(
                    'topics.topic',
                    'topics.body',
                    'topics.text',
                    'topics.uid as topics_uid',
                    'topics.slug as topic_slug',
                    'topics.tags',
                    'topics.uuid as topic_uuid',
                    'topics.created_at as topic_created_at',
                    'users.firstname',
                    'users.profile_img',
                    'users.displayname',
                    'users.description'
                )
                ->join('users', 'topics.uid', '=', 'users.uuid')
                ->wherein('topics.uuid',$uuid_array)
                ->where('topics.flg',1)
                ->get();
//        });
        return $results;
    }



    //Get topic listed by using tags
    public function getTagTopic($tag)
    {
        return $topic = DB::table('tags')
            ->select(
                'topics.id',
                'topics.topic',
                'topics.type as topic_type',
                'topics.body',
                'topics.text',
                'topics.is_edited',
                'topics.uid as topics_uid',
                'topics.slug as topic_slug',
                'topics.tags',
                'topics.comments',
                'categories.name as cate_name',
                'categories.slug as cate_slug',
                'topics.uuid as topic_uuid',
                'topics.created_at as topic_created_at',
                'topics.updated_at as topic_updated_at',
                'users.firstname',
                'users.profile_img',
                'users.displayname',
                'users.description'
            )
            ->join('topics', 'topics.uuid', '=', 'tags.topic_uuid')
            ->join('users', 'topics.uid', '=', 'users.uuid')
            ->join('categories', 'topics.category', '=', 'categories.id')
            ->where('tags.title',clean($tag))
            ->where('topics.flg',1)
            ->get();
    }
}
