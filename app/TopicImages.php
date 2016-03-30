<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicImages extends Model
{
    protected $table = 'topics_img';


    //Delete all images from specified topio uuid
    public function purgeImages($topic_uuid)
    {
        $this::where('topic_uuid', $topic_uuid)->delete();
    }
}
