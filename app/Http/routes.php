<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $topics = App\Topic::where('flg',1)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
    return view('welcome',compact('topics'));
});



Route::get('fire', function () {
    event(new App\Events\EventName(10));
    return "event fired";
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});


Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
    Route::post('/getFeed','HomeController@getFeedCate');


    Route::post('/api/previewImage','PhotoController@preview');
    Route::post('/api/postTopic','TopicController@store');


    Route::get('/create-name','ProfileController@createName');
    Route::post('/check-name','ProfileController@checkName');
    Route::post('/register-name','ProfileController@registerName');


    //Topic Controller
    Route::post('/postTopic','TopicController@store');
    Route::get('/{displayname}/{slug}','TopicController@show');
    Route::post('/follow-cate','TopicController@follow_cate');
    Route::post('/replyTopic','TopicController@replyTopic');


    //Profile page
    Route::get('/{displayname}','ProfileController@show');
    Route::post('/user/update-description', 'ProfileController@updateDesc');


});
