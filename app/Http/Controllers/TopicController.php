<?php

namespace App\Http\Controllers;


use App\IpLogger;
use App\Notification;
use App\Tags;
use Auth;
use App\Topic as Topic;
use App\Events\UserReply as UserReply;
use App\TopicReply as TopicReply;
use App\Users_follow;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tag;
use Webpatser\Uuid\Uuid;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon as Carbon;

use Illuminate\Contracts\Filesystem\Filesystem;
use Redis;


//SEO
use SEOMeta;
use OpenGraph;
use Twitter;
use SEO;

class TopicController extends Controller
{

    //Check User follow Status
    public function userFollowStatus(Request $request)
    {
        if(Auth::user()) {
            $uf = new Users_follow();
            return $uf->getUserFollowstatus(Auth::user()->uuid, $request->data);
        }
    }


    //Follow user
    public function followUser(Request $request)
    {
        $uf = new Users_follow();
        return $uf->followUser(Auth::user()->uuid, $request->data);
    }


    public function feedFollowStatus(Request $request)
    {

    }

    //Follow categories
    public function follow_cate(Request $request)
    {
        if(Auth::user())
        {
            $uf = new Users_follow();
            return $uf->followFeed(Auth::user()->uuid,$request->data);
        }
        else{
            echo "unauthorized";
        }
    }

    //Reply to topic
    public function replyTopic(Request $request)
    {
        if(Auth::user())
        {
            $reply = new TopicReply();
            $reply->topic_uuid  =   $request->uuid;
            $reply->uid         =   Auth::user()->uuid;
            $reply->body        =   clean($request->data);
            $reply->save();

            $replyObj =TopicReply::find($reply->id);

            $notification = new Notification();
            $notification->store(3,$request->topics_uid,
                                Auth::user()->uuid,
                                $request->topics_uid,
                                'reply');
            event(new \App\Events\TopicReplyEvent($request->uuid,$request->topics_uid,$replyObj));

        }
        else
        {
            echo "unauthorized";
        }

    }


    //Create the view for topic reply
    public function replyTopicView(Request $request)
    {
        $data = $request->replyReq;
        return view('html.topic-reply',compact('data'));
    }


    //Tag landing page
    public function tag($tag)
    {

        SEOMeta::setTitle($tag);
//        SEOMeta::setDescription(str_limit($body,152));


        OpenGraph::setTitle($tag);
//        OpenGraph::setDescription($body);

        $topic = new Topic();
        $topics = $topic->getTagTopic($tag);

        return view('tag',compact('topics'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {


    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->uuid) {
            if ($request->data) {
                $json = $request->data;

                $topicUUID = rand(0, 10) . str_random(12) . rand(0, 10);
                $topicSlug = str_slug($json['title'], "-") . '-' . $topicUUID;
                if(!empty($json['tags']))
                    $taglist = implode(",", $json['tags']);
                else
                    $taglist = null;

                $topic              = new Topic;
                $topic->uuid        = $topicUUID;
                $topic->uid         = Auth::user()->uuid;
                $topic->topic       = clean($json['title']);
                $topic->body        = clean($json['body']);
                $topic->category    = $json['categories'];
                $topic->slug        = $topicSlug;
                $topic->tags        = $taglist;
                $topic->save();

                $tag_data = array();
                $count=0;
                foreach($json['tags'] as $tag)
                {
                    $tag_data[$count] = array(  'topic_uuid'=>$topicUUID,
                                                'title'=>clean($tag)
                                                );
                    $count++;
                }
                Tags::insert($tag_data);

                $topicEvents = Topic::find($topic->id);

                event(new \App\Events\TopicPostEvent($topicEvents));

                $data = array("slug"=>$topicSlug,
                              "author"=>Auth::user()->displayname);

                return $data;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  char  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($displayname,$slug)
    {

        DB::connection()->enableQueryLog();

        $topic = new Topic();
        $topic = $topic->getTopic($slug);

        if(empty($topic)){
            return "not found".$topic;
        }else{

            $is_user = null;

            /**
             * Performance and redis check
            $log = DB::getQueryLog();
            print_r($log);
             */

            $dt = Carbon::parse($topic->topic_created_at);

            $title      = $topic->topic;
            $body       = $topic->body;
            $username   = $topic->displayname;
            $user_fname = $topic->firstname;
            $slug       = $topic->topic_slug;
            $uuid       = $topic->topic_uuid;
            $topics_uid = $topic->topics_uid;
            $user_descs = $topic->description;
            $tags       = explode(',', $topic->tags);
            $created_at = $dt->diffForHumans();

            SEOMeta::setTitle($title);
            SEOMeta::setDescription(str_limit($body,152));


            OpenGraph::setTitle($title);
            OpenGraph::setDescription($body);
            /*OpenGraph::setUrl('http://current.url.com');
            OpenGraph::addProperty('type', 'articles');*/

            $topic = new Topic();
            $topic_replies = $topic->getReplies($uuid);

            //Check if this is the owner
            if(!empty(Auth::user()->uuid))
            {
                if(Auth::user()->uuid == $topics_uid){
                    $is_user = 'true';
                }
            }

            return view('pages.topic.topic',
                compact('title','body',
                        'username',
                        'slug',
                        'uuid',
                        'is_user',
                        'topics_uid',
                        'user_descs',
                        'tags',
                        'user_fname',
                        'created_at','topic_replies'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
