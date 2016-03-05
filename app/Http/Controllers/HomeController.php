<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Categories;
use App\Topic as Topic;
use Illuminate\Support\Facades\DB as DB;
use App\User;
use App\Users_follow;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     *
    */
    public function welcome()
    {
        $topic = new \App\Topic();
        $topics = $topic->recentlyCreated();

        $categories = new \App\Categories();
        $categories = $categories->all();

        return view('welcome',compact('topics','categories'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories = Categories::all();

        $user = User::find(Auth::user()->id);

        /*echo $user;
        echo $user->uuid;*/

        if(empty($user->displayname)){
            return redirect()->action('ProfileController@createName');
        }

        $followFeed = new Users_follow();
        $topics = $followFeed->getFeed(Auth::user()->uuid);
//        print_r($topics);

        return view('home',compact('categories','topics'));
    }


    public function getFeedCate(Request $request)
    {
        $slug   =   $request->slug;
        $topic  =   new Topic();
        $topics =   $topic->getFeed($slug);
        return view('html.feed-list',compact('topics','slug'));
    }
}
