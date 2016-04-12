<?php

namespace App\Http\Controllers;


use App\IpLogger;
use App\Location;
use App\Notification;
use App\ReplyInReply;
use App\Reviews;
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
use Illuminate\Support\Facades\Redis as Redis;

use Mail;

use Illuminate\Support\Facades\Helpers;
//SEO
use SEOMeta;
use OpenGraph;
use Twitter;
use SEO;


class TopicController extends Controller
{

    //Facebook location search
    public function fbLocationSearch(Request $request)
    {
        $term = $request->term;
        $fb = \FacebookHelper::fb_init();
        $response       = $fb->get("/search?q=$term&type=place&center=15.8700,100.992&limit=5");
        return $fb_response    = $response->getDecodedBody();
    }


    //Get lcoation details from external id
    public function getLocation(Request $request)
    {
        return Location::where('external_id',$request->data)->first();
    }


    //Get the review template - where is_template equal to one
    public function getReview(Request $request)
    {
        return DB::table('reviews')
                ->where('is_template',1)
                ->where('topic_uuid',$request->data)->get();
    }



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
        return view('html.reply-in-reply',compact('data'));
    }



    public function getPostImages(Request $request)
    {
        $html = null;
        $topic = new Topic();
        return $images= $topic->postImages($request->uuid);
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


    public function getReplies(Request $request)
    {
        $topic = new Topic();
        return $topic_replies = $topic->getReplies($request->topic_uuid);
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


            //Reviews
            if($request->reviews)
            {
                $count=0;
                foreach($request->reviews as $review)
                {
                    $review_data[$count] = array( 'topic_uuid'      => $request->uuid,
                                                    'user_uuid'     => Auth::user()->uuid,
                                                    'criteria'      => $review['criteria'],
                                                    'scores'        => $review['scores'],
                                                    'topic_id'      => $reply->id,
                                                    'is_template'   => FALSE,
                                                    'created_at'    => date("Y-m-d H:i:s")
                                             );
                    $count++;
                }
                Reviews::insert($review_data);
            }


            $replyObj =TopicReply::find($reply->id);

            $topic = new Topic();
            $author = $topic->getUserInfoFromTopic($request->uuid);


            //Mail
            $mailer = new MailController();
            //Mail to poster
            $mailer->notify_poster($request->uuid,$replyObj);
            //Mail to ppl who reply
            $mailer->notifiy_reply($request->uuid,$replyObj);



            //Add to notification
            /*$notification = new Notification();
            $notification->store(3,$request->topics_uid,
                                Auth::user()->uuid,
                                $request->topics_uid,
                                'reply');
            event(new \App\Events\TopicReplyEvent($request->uuid,$request->topics_uid,$replyObj));*/

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

        $categories = new \App\Categories();
        $categories = $categories->all();

        SEOMeta::setTitle($tag);
//        SEOMeta::setDescription(str_limit($body,152));


        OpenGraph::setTitle($tag);
//        OpenGraph::setDescription($body);

        $topic = new Topic();
        $topics = $topic->getTagTopic($tag);

        return view('tag',compact('topics','tag','categories'));
    }


    //Return tag buttons to view
    public function getTagButton(Request $request)
    {
        $data = $request->data;
        return view('html.tag-buttons',compact('data'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //Default for number of images in the post
        $num_img = 0;
        $locationID = null;
        $taglist = null;


        if (Auth::user()->uuid) {
            if ($request->data) {
                $json = $request->data;

                $topicUUID = rand(0, 10) . str_random(12) . rand(0, 10);
                $topicSlug = str_slug($json['title'], "-") . '-' . $topicUUID;

                //Reviews
                if($json['reviews']!= 'false')
                {
                    $count=0;
                    foreach($json['reviews'] as $review)
                    {
                        //there is a bug that says name is empty sometime
                        if(!empty($review['name'])) {
                            $review_data[$count] = array('topic_uuid' => $topicUUID,
                                'user_uuid' => Auth::user()->uuid,
                                'criteria' => $review['name'],
                                'scores' => $review['rating'],
                                'is_template' => TRUE,
                                'created_at' => date("Y-m-d H:i:s")
                            );
                            $count++;
                        }
                    }
                    Reviews::insert($review_data);
                }


                //Location
                if(!empty($json['location']))
                {

                    $locationID = $json['location']['id'];

                    //Check location exists, if not create
                    $location_exist =
                        DB::table('locations')
                        ->where('external_id',$locationID)->count();

                    if($location_exist == 0)
                    {

                        $location = new Location();
                        $location->source       = 'facebook'; //hardcode for now
                        $location->external_id  = $locationID;
                        $location->name     = $json['location']['name'];
                        $location->category     = !empty($json['location']['category'])?$json['location']['category']:null;
                        $location->street       = !empty($json['location']['location']['street'])?$json['location']['location']['street']:null;
                        $location->city         = !empty($json['location']['location']['city'])?$json['location']['location']['city']:null;
                        $location->state        = !empty($json['location']['location']['state'])?$json['location']['location']['state']:null;
                        $location->country      = !empty($json['location']['location']['country'])?$json['location']['location']['country']:null;
                        $location->zip          = !empty($json['location']['location']['zip'])?$json['location']['location']['zip']:null;
                        $location->latitude     = $json['location']['location']['latitude'];
                        $location->longitude    = $json['location']['location']['longitude'];
                        $location->save();
                    }
                    //GEt the ID
                }


                //Tag list - to store in the topic table
                if(!empty($json['tags']))
                    $taglist = implode(",", $json['tags']);


                //Images
                if(!empty($json['images']))
                {
                    $count = 0;
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
                    $num_img = $count;
                    TopicImages::insert($img_data);
                }

                $topic              = new Topic;
                $topic->uuid        = $topicUUID;
                $topic->type        = $json['type'];
                $topic->uid         = Auth::user()->uuid;
                $topic->topic       = clean($json['title']);
                $topic->body        = preg_replace('/(<[^>]+) style=".*?"/i', '$1', clean($json['body']));
                $topic->text        = clean($json['text']);
                $topic->category    = $json['categories'];
                $topic->slug        = $topicSlug;
                $topic->num_img     = $num_img;
                $topic->tags        = $taglist;
                $topic->location_id  = $locationID;
                $topic->save();

                $tag_data = array();
                $count=0;


                //Tags - to store in tags table
                if(!empty($json['tags']))
                {

                    //Insert tags in another table
                    foreach($json['tags'] as $tag)
                    {
                        Redis::zadd('post:tag'.$tag, $topic->uuid, $topic->uuid);
                        Redis::sadd('post:' . $topic->uuid . ':tags' ,$tag);
                        //Master link of tags
                        Redis::sadd('post:tags',$tag);

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

                $data = array("slug"       => $topicSlug,
                              "author"     => Auth::user()->displayname,
                              "type"       => $json['type'],
                              "topic_uuid" => $topicUUID);

                return $data;
            }
        }else{
            return "not login";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  char  $slug
     */
    public function show($displayname,$slug)
    {

        $categories = new \App\Categories();
        $categories = $categories->all();

        DB::connection()->enableQueryLog();

        $topic = new Topic();
        $topic = $topic->getTopic($slug);

        /**
         * Performance and redis check
         */
      /*  $log = DB::getQueryLog();
        print_r($log);*/

        if(empty($topic)){
            return "not found".$topic;
        }else{

            //Increment page view and keep track of what popular in redis
            $storage = Redis::connection();
            if($storage->zScore('postViews','post:'.$topic->topic_uuid))
            {
                $storage->pipeline(function($pipe) use ($topic)
                {
                    $pipe->zIncrBy('postViews',1,'post:' . $topic->topic_uuid);
                    $pipe->incr('post:' . $topic->topic_uuid . ":views");
                });
            }
            else
            {
                $views = $storage->incr('post:' . $topic->topic_uuid . ":views");
                $storage->zIncrBy('postViews',1,'post:' . $topic->topic_uuid);
            }
            //Get post views from redis
            $views = $storage->get('post:' . $topic->topic_uuid . ":views");


            $is_user = null;

            $dt = Carbon::parse($topic->topic_created_at);
            $topic_id   = $topic->id;
            $title      = $topic->topic;
            $topic_type = $topic->topic_type;
            $body       = $topic->body;
            $is_edited  = $topic->is_edited;
            $username   = $topic->displayname;
            $user_fname = $topic->firstname;
            $cate_name  = $topic->cate_name;
            $slug       = $topic->topic_slug;
            $uuid       = $topic->topic_uuid;
            $topics_uid = $topic->topics_uid;
            $user_descs = $topic->description;
            $poster_img = $topic->profile_img;
            $topic_created_at = $topic->topic_created_at;
            $topic_updated_at = $topic->topic_updated_at;
            $topic_location = $topic->location_id;

            if(!empty($topic->tags))
                $tags       = explode(',', $topic->tags);
            else
                $tags = null;
            $created_at = $dt->diffForHumans();

            SEOMeta::setTitle($title);
            SEOMeta::setDescription(str_limit($body,152));

            $req = new Request();

            $url = $req->url();
            OpenGraph::setTitle($title);
            OpenGraph::setDescription($body);

            OpenGraph::setUrl($url);
            OpenGraph::addProperty('type', 'articles');
            OpenGraph::addProperty('locale:alternate', ['th-th', 'en-us']);


            /*$topic = new Topic();
            $topic_replies = $topic->getReplies($uuid);*/

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
                        'topic_type',
                        'username',
                        'slug',
                        'uuid','is_user','is_edited','topics_uid','user_descs',
                        'tags','poster_img','user_fname','cate_name','topic_updated_at',
                        'topic_created_at',
                        'views','topic_location',
//                        'topic_replies',
                        'categories'));
        }
    }


    /**
     * Update content
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        $data =  $request->data;

        $topic = Topic::find($data['topic_id']);

        //Images
        if(!empty($data['images']))
        {
            $topicImages = new TopicImages();
            //Clean all the existing images
            $topicImages->purgeImages($data['topic_uuid']);

            $num_img = 0;
            //Insert images in another table
            foreach($data['images'] as $image)
            {
                $img_data[$num_img] = array(  'topic_uuid'=>$data['topic_uuid'],
                                            'user_uuid'=>Auth::user()->uuid,
                                            'filename'=>$image,
                                            'created_at'=> date("Y-m-d H:i:s")
                                        );
                $num_img++;
            }
            TopicImages::insert($img_data);

        }

        $topic->is_edited = true;
        $topic->body = preg_replace('/(<[^>]+) style=".*?"/i', '$1', clean( trim($data['body']) ));
        $topic->text = $data['text'];
        $topic->num_img = $num_img;
        $topic->save();
    }


    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data =  $request->data;

        DB::table('topics')
        ->where('uid', $data['user_uuid'])
        ->where('uuid', $data['topic_uuid'])
        ->update(['flg' => 0]);


        DB::table('users')
        ->where('uuid', $data['user_uuid'])
        ->decrement('posts');
        
    }
}
