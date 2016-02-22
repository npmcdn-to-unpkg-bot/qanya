<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    //Only authenticated users
    public function __construct()
    {
//        $this->middleware('auth');
    }


    /**
     * Displayname section
     *
     */
    public function createName()
    {
        return view('pages.createName');
    }

    public function checkName(Request $request)
    {
        $user = User::where('displayname',$request->name)->count();
        return $user;
    }

    public function registerName(Request $request)
    {
        //Not sure if this is smart putting @ here, but we will see
        User::where('uuid',Auth::user()->uuid)
            ->update(['displayname'=> '@'.$request->displayname]);
        return redirect('/home');
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
        $user = User::where('displayname',$displayname)->first();
        $is_user = 'false';

        $topics =   DB::table('topics')
            ->where('topics.uid', $user->uuid)
            ->join('users','users.uuid','=','topics.uid')
            ->get();

        if(!empty(Auth::user()->uuid))
        {
            if(Auth::user()->uuid == $user->uuid){
                $is_user = 'true';
            }
        }else{
            $is_user = 'false';
        }
        return view('profile.index',
                compact('user','is_user','topics'));
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
