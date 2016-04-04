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
use Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }


    /* Checking for the valid email verification*/
    public function verification()
    {

        $user =  User::find(Auth::user()->id);
        if($user->confirmed ==0)
        {
            return view('auth.verification');
        }
    }


    /**
     * Confirm verification
     */
    public function confirmVerification(Request $request)
    {
        $request->data;

        $user =  User::find(Auth::user()->id);
        if($user->confirmation_code == $request->data){

            //update the confirm status
            $user->confirmed = 1;
            $user->save();

            return 0;
        }else{
            return 1;
        }
    }


    //Create the confirmation code
    public function sendVerification()
    {
        $confirmation_code = str_random(30);

        $user =  User::find(Auth::user()->id);
        $user->confirmation_code = $confirmation_code;
        $user->save();

        $mail = new MailController();
        $mail->sendVerficationCode($confirmation_code);
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

        if(Auth::user())
        {
            //if email is not confirm
            if(Auth::user()->confirmed ==0)
            {
                return view('auth.verification');
            }

            //if displayname is not set
            if(empty(Auth::user()->displayname)){
                return redirect()->action('ProfileController@createName');
            }
        }

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
