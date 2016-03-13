<?php

namespace App\Http\Controllers;


use App\IpLogger;
use App\Notification;
use App\ReplyInReply;
use App\Tags;
use App\Topic_actions;
use App\TopicImages;
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


    public function postReplyInReply(Request $request)
    {
        $rir = new ReplyInReply();
        $rir->reply_id      = $request->reply_id;
        $rir->topic_uuid    = $request->topics_uuid;
        $rir->user_uuid     = $request->uuid;
        $rir->body          = $request->data;
        $rir->save();

    }


    //Format the replies in reply
    public function replyInReplyList(Request $request)
    {
        $rir = new ReplyInReply();
        $data= $rir->getReplyInReply($request->reply_id);
      /*  $fb_data = $request->data;
        $userList = [];
        $count = 0;
        foreach ($fb_data as $reply) {
            $userList[$count] = $reply['user_uuid'];
            $count++;
        }

        $userdata = DB::table('users')
                ->wherein('users.uuid',$userList)->get();
        echo is_array($userdata);
        echo is_array($fb_data);*/

//        $data = array_combine($fb_data, $userdata);
//        print_r($data);
        return view('html.reply-in-reply',compact('data'));
    }

    public function getPostImages(Request $request)
    {
        $html = null;
        $topic = new Topic();
        $images= $topic->postImages($request->uuid);

        if(!empty($images)) {
            $html = "<div class='row'>";
            foreach ($images as $image) {

                $html .= "<div><img src=\"$image->filename\" class=\"img-rounded img-fluid col-xs-12 col-sm-4\" style='max-width: 470px'></div>";
            }
            $html .= "</div>";
            return $html;
        }

    }



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

            //Add to notification
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
        $topic = new Topic();

        $reply_id   =   $request->replyReq['data']['id'];
        $data       =   $topic->getSingleReply($reply_id);

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

        return view('tag',compact('topics','tag'));
    }


    //Return tag buttons to view
    public function getTagButton(Request $request)
    {
        $data = $request->data;
        return view('html.tag-buttons',compact('data'));
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
                $topic->body        = preg_replace('/(<[^>]+) style=".*?"/i', '$1', clean($json['body']));
                $topic->text        = clean($json['text']);
                $topic->category    = $json['categories'];
                $topic->slug        = $topicSlug;
                $topic->tags        = $taglist;
                $topic->save();

                $tag_data = array();
                $count=0;

                if(!empty($json['images']))
                {
                    //Insert images in another table
                    foreach($json['images'] as $image)
                    {
                        $img_data[$count] = array(  'topic_uuid'=>$topicUUID,
                            'user_uuid'=>Auth::user()->uuid,
                            'filename'=>$image,
                            'created_at'=> date("Y-m-d H:i:s")
                        );
                        $count++;
                    }
                    TopicImages::insert($img_data);
                }

                if(!empty($json['tags']))
                {
                    //Insert tags in another table
                    foreach($json['tags'] as $tag)
                    {
                        $tag_data[$count] = array(  'topic_uuid'=>$topicUUID,
                                                    'title'=>clean($tag),
                                                    'created_at'=> date("Y-m-d H:i:s")
                                                    );
                        $count++;
                    }
                    Tags::insert($tag_data);
                }

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
             */
            $log = DB::getQueryLog();
            print_r($log);


            $dt = Carbon::parse($topic->topic_created_at);
            $topic_id   = $topic->id;
            $title      = $topic->topic;
            $body       = $topic->body;
            $username   = $topic->displayname;
            $user_fname = $topic->firstname;
            $slug       = $topic->topic_slug;
            $uuid       = $topic->topic_uuid;
            $topics_uid = $topic->topics_uid;
            $user_descs = $topic->description;
            $poster_img = $topic->profile_img;
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
                compact('topic_id',
                        'title',
                        'body',
                        'username',
                        'slug',
                        'uuid',
                        'is_user',
                        'topics_uid',
                        'user_descs',
                        'tags',
                        'poster_img',
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
    public function update(Request $request)
    {
        $data =  $request->data;
        print_r($data);

        $topic = Topic::find($data['topic_id']);

        print_r($topic);

        $topic->body = $data['body'];
        $topic->text = $data['text'];;

        $topic->save();

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
