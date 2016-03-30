<?php

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

    Route::get('/', 'HomeController@welcome');
    Route::get('/home', 'HomeController@index');
    Route::post('/getFeed','HomeController@getFeedCate');

    //Tags
    Route::get('/tag/{tag}','TopicController@tag');
    Route::post('/api/previewImage','PhotoController@preview');
    Route::post('/api/postTopic','TopicController@store');
    Route::post('/getTagButton','TopicController@getTagButton');


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

    //Channel Controller
    Route::get('/channel/{channel_name}','ChannelController@index');

    //Topic Controller
    Route::post('/postTopic','TopicController@store');
    Route::post('/api/updateTopicContent','TopicController@update');
    Route::get('/{displayname}/{category}','TopicController@show');
    Route::post('/feedFollowStatus','TopicController@userFollowStatus');
    Route::post('/follow-cate','TopicController@follow_cate');
    Route::post('/replyTopic','TopicController@replyTopic');
    Route::post('/userFollowStatus','TopicController@userFollowStatus');
    Route::post('/followUser','TopicController@followUser');
    Route::post('/upvote','TopicController@upvote');
    Route::post('/dwonvote','TopicController@downvote');
    Route::get('/replyView','TopicController@replyTopicView');
    Route::post('/getPostImages','TopicController@getPostImages');
    Route::post('/replyInReply','TopicController@postReplyInReply');
    Route::post('/replyInReplyList','TopicController@replyInReplyList');
    Route::post('/removeTopic','TopicController@destroy');
    Route::post('/retrieve-review','TopicController@getReview');



    //Profile page
    Route::get('/{displayname}'             ,   'ProfileController@show');
    Route::post('/user/update-description'  ,   'ProfileController@updateDesc');
    Route::post('/user/getHistory'          ,   'ProfileController@getHistory');
    Route::post('/user/getBookmark'         ,   'ProfileController@getBookmark');
    Route::post('/getPostedPhotos'          ,   'ProfileController@getPostedPhotos');

});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
