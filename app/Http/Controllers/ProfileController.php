<?php

namespace App\Http\Controllers;

use App\IpLogger;
use App\Notification;
use App\TopicReply;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    //Only authenticated users
    public function __construct()
    {
//        $this->middleware('auth');
    }


    public function getPostedPhotos(Request $request)
    {

        if($request->data)
        {
            return DB::table('topics')
                        ->select(
                            'topics.topic',
                            'topics.type as topic_type',
                            'topics.uid as topics_uid',
                            'topics.slug as topic_slug',
                            'topics.tags',
                            'topics.uuid as topic_uuid',
                            'topics.created_at as topic_created_at',
                            'topics_img.filename',
                            'users.firstname',
                            'users.profile_img',
                            'users.displayname'
                        )
                        ->join('users', 'topics.uid', '=', 'users.uuid')
                        ->join('topics_img', 'topics_img.topic_uuid', '=', 'topics.uuid')
                        ->where('user_uuid',$request->data)
                        ->limit(10)
                        ->get();
        }
    }


    //Get user bookmarks
    public function getBookmark(Request $request)
    {
        if($request->data) {
            $topicList = [];
            $count = 0;
            $bookmarks = $request->data;
            foreach ($bookmarks as $data => $item) {
                $topicList[$count] = $data;
                $count++;
            }

            $topics = new Topic();
            return $topics = $topics->getTopicList($topicList);
        }
    }


    //Get user history
    public function getHistory(Request $request)
    {
        if($request->data) {
            $topicList = [];
            $count = 0;
            $history = $request->data;
            foreach ($history as $data => $item) {
                $topicList[$count] = $data;
                $count++;
            }

            $topics = new Topic();
            return $topics = $topics->getTopicList($topicList);
        }
    }


    //Log ip acitivities
    public function ipLogger(Request $request)
    {
        $ipLogger = new IpLogger();
        $ipLogger->obj_id       = $request->topics_uid;
        $ipLogger->action       = $request->action;
        $ipLogger->user_uuid    = $request->uuid;
        $ipLogger->ip           = isset($request->geoResponse['data']['ip'])?$request->geoResponse['data']['ip']:null;
        $ipLogger->hostname     = isset($request->geoResponse['data']['hostname'])?$request->geoResponse['data']['hostname']:null;
        $ipLogger->org          = isset($request->geoResponse['data']['org'])?$request->geoResponse['data']['org']:null;
        $ipLogger->city         = isset($request->geoResponse['data']['city'])?$request->geoResponse['data']['city']:null;
        $ipLogger->region       = isset($request->geoResponse['data']['region'])?$request->geoResponse['data']['region']:null;
        $ipLogger->country      = isset($request->geoResponse['data']['country'])?$request->geoResponse['data']['country']:null;
        $ipLogger->loc          = isset($request->geoResponse['data']['loc'])?$request->geoResponse['data']['loc']:null;
        $ipLogger->postal       = isset($request->geoResponse['data']['postal'])?$request->geoResponse['data']['postal']:null;
        $ipLogger->save();
    }


    //Update city and country
    public function updateCityCountry(Request $request)
    {
        if(Auth::user())
        {
            User::where('uuid',Auth::user()->uuid)
                ->update([
                            'current_country'   => $request->geo_city,
                            'current_city'      => $request->geo_country,
                         ]);
        }
    }

    //Update profile image
    public function profileImage(Request $request)
    {
        User::where('uuid',Auth::user()->uuid)
            ->update(['profile_img'=> $request->img]);
        return $request->img;
    }

    //List Notification
    public function listNotification()
    {
        $notification = new Notification();
        return $notification->listNotification(Auth::user()->uuid);
    }

    /**
     * Acknowledge notification
     */
    public function ackNotification()
    {
        $notification = new Notification();
        $notification->ackNotification();
        return $notification->countNotification(Auth::user()->uuid);
    }

    /**
     * Get user notification
     *
     */
    public function getNotification()
    {
        $notification = new Notification();
        return $notification->countNotification(Auth::user()->uuid);
    }

    /*
     * View - Displayname section
     */
    public function createName()
    {
        return view('pages.createName');
    }


    //Check username during the registration
    public function checkName(Request $request)
    {
        $username = '@'.strtolower( str_replace(' ', '',$request->name));
        $user = User::where('displayname',$username)->count();
        return $user;
    }

    public function registerName(Request $request)
    {
        //Not sure if this is smart putting @ here, but we will see
        $username = '@'.strtolower( str_replace(' ', '',$request->displayname));
        User::where('uuid',Auth::user()->uuid)
            ->update(['displayname'=> $username]);

        return redirect('/suggest-explore');
    }


    public function suggestExplore(Request $request)
    {
        echo "test";
    }
    /** end displayname section */



    public function updateDesc(Request $request)
    {
        User::where('uuid',Auth::user()->uuid)
            ->update(['description'=> $request->name]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($displayname)
    {

        $categories = new \App\Categories();
        $categories = $categories->all();
        
        //Show displayname
        $user = User::where('displayname',$displayname)->first();
        $is_user = 'false';

        //Show topics created by user
        $topic = new Topic();
        $topics = $topic->getUserTopic($user->uuid);

        //Get user replies
        $userReplies = new TopicReply();
        $userReplies = $userReplies->getUserReplies($user->uuid);

        if(!empty(Auth::user()->uuid))
        {
            if(Auth::user()->uuid == $user->uuid){
                $is_user = 'TRUE';
            }
        }else{
            $is_user = 'FALSE';
        }
        return view('profile.index',
                compact('user','is_user','topics','userReplies','categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
