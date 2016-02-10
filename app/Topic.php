<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Redis;
use Cache;

class Topic extends Model
{

    protected $table = 'topics';



    public function getTopic($slug)
    {
        $results = Cache::remember('topic_posts_cache_'.$slug,1,function() use ($slug){
            return $topic = DB::table('topics')
                ->join('users', 'topics.uid', '=', 'users.uuid')
                ->where('topics.slug',$slug)
                ->first();
        });
        return $results;
    }
}
