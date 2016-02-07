<?php

namespace App\Http\Controllers;

use Auth;
use App\Topic as Topic;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon as Carbon;

use Illuminate\Contracts\Filesystem\Filesystem;


class TopicController extends Controller
{
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
        $topicUUID = rand(0,10).str_random(12).rand(0,10);
        $topicSlug = str_slug($request->postTitle, "-").'-'.$topicUUID;

        if($request->file('image'))
        {
            $image = $request->file('image');
            $s3 = new S3Client([
                'version' => 'latest',
                'region'  => 'Singapore'
            ]);
            try {
                $s3->putObject([
                    'Bucket' => 'qanya',
                    'Key'    => 'my-object',
                    'Body'   => fopen('/path/to/file', 'r'),
                    'ACL'    => 'public-read',
                ]);
            } catch (Aws\Exception\S3Exception $e) {
                echo "There was an error uploading the file.\n";
            }
        }
        $topic              =   new Topic;
        $topic->uuid        =   $topicUUID;
        $topic->uid         =   Auth::user()->uuid;
        $topic->topic       =   $request->postTitle;
        $topic->body        =   $request->postBody;
        $topic->categories  =   1;
        $topic->slug        =   $topicSlug;
        $topic->save();

        return redirect('/'.$topicSlug);
    }

    /**
     * Display the specified resource.
     *
     * @param  char  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $topic = DB::table('topics')
            ->join('users', 'topics.uid', '=', 'users.uuid')
            ->where('topics.slug',$slug)
//                ->select('topic','topics.body')
            ->first();

        if(empty($topic)){
            return "not found".$topic;

        }else{

            $dt = Carbon::parse($topic->created_at);

            $title      = $topic->topic;
            $body       = $topic->body;
            $username   = $topic->name;
            $created_at = $dt->diffForHumans();

            return view('pages.topic.topic',
                compact('title','body','username','created_at'));
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
