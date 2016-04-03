<?php

namespace App\Http\Controllers;

use App\Topic;
use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
class MailController extends Controller
{

    public function welcome()
    {


    }


    /**
     * Send email to the psrson that post the topic
     * @param $topic_uuid
     * @param $replyObj
     * */
    public function notify_poster($topic_uuid,$replyObj)
    {
        //Get the name of the person who post the reply
        $sender = DB::table('topics_reply')
            ->join('users', 'topics_reply.uid', '=', 'users.uuid')
            ->where('users.uuid',$replyObj->uid)
            ->where('topics_reply.id',$replyObj->id)
            ->first();

        //Get the information about the topic so we can create the the link to direct back to topic
        $topic = new Topic();
        $topic = $topic->getUserInfoFromTopic($topic_uuid);

        Mail::send('emails.topic-reply', ['recipient'   => $topic,
            'replyObj'    => $replyObj,
            'sender'      => $sender,
            'topic'       => $topic], function ($m) use ($topic) {
            $m->from('hello@qanya.com', 'Qanya');
            $m->to($topic->email, $topic->firstname)
                ->subject("New response in ". strip_tags($topic->topic));
        });

    }



    /**
     * Send email to those who reply in the topic
     * @param $topic_uuid
     * @param $replyObj
     * */
    public function notifiy_reply($topic_uuid,$replyObj)
    {
        //Get the list of user that reply in this post
        $recipients = DB::table('topics_reply')
                ->join('users', 'topics_reply.uid', '=', 'users.uuid')
                ->where('topics_reply.topic_uuid',$topic_uuid)
                ->groupBy('email')
                ->get();

        //Get the name of the person who post the reply
        $sender = DB::table('topics_reply')
                    ->join('users', 'topics_reply.uid', '=', 'users.uuid')
                    ->where('users.uuid',$replyObj->uid)
                    ->where('topics_reply.id',$replyObj->id)
                    ->first();

        //Get the information about the topic so we can create the the link to direct back to topic
        $topic = new Topic();
        $topic = $topic->getUserInfoFromTopic($topic_uuid);


        foreach($recipients as $recipient) {

            //if the person that is receiving this is not the poster
            if($recipient->email != $topic->email) {
                Mail::send('emails.topic-reply', ['recipient' => $recipient,
                    'replyObj' => $replyObj,
                    'sender' => $sender,
                    'topic' => $topic], function ($m) use ($recipient, $topic) {
                    $m->from('hello@qanya.com', 'Qanya');
                    $m->to($recipient->email, $recipient->firstname)
                        ->subject("New response in " . strip_tags($topic->topic));
                });
            }
        }
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
    public function show($id)
    {
        //
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
