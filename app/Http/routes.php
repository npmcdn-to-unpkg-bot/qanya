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
    $topic = new \App\Topic();
    $topics = $topic->recentlyCreated();
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

    //Tags
    Route::get('/tag/{tag}','TopicController@tag');
    Route::post('/api/previewImage','PhotoController@preview');
    Route::post('/api/postTopic','TopicController@store');

    //Users
    Route::get('/create-name','ProfileController@createName');
    Route::post('/check-name','ProfileController@checkName');
    Route::post('/register-name','ProfileController@registerName');
    Route::post('/getNotification','ProfileController@getNotification');
    Route::post('/ackNotification','ProfileController@ackNotification');
    Route::post('/list-notification','ProfileController@listNotification');
    Route::post('/upload-profileImage','ProfileController@profileImage');
    Route::post('/ip-logger','ProfileController@ipLogger');
    Route::post('/api/updateUserGeo','ProfileController@updateCityCountry');


    //Topic Controller
    Route::post('/postTopic','TopicController@store');
    Route::get('/{displayname}/{slug}','TopicController@show');
    Route::post('/feedFollowStatus','TopicController@userFollowStatus');
    Route::post('/follow-cate','TopicController@follow_cate');
    Route::post('/replyTopic','TopicController@replyTopic');
    Route::post('/userFollowStatus','TopicController@userFollowStatus');
    Route::post('/followUser','TopicController@followUser');
    Route::get('/replyView','TopicController@replyTopicView');


    //Profile page
    Route::get('/{displayname}','ProfileController@show');
    Route::post('/user/update-description', 'ProfileController@updateDesc');


});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
