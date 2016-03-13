<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;

class ChannelController extends Controller
{
    public function index($category)
    {
        $categories = Categories::all();
        $topics = new Topic();
        $topics = $topics->getTopicChannel($category);
        $title = $category;
        return view('channel',compact('categories','topics','title'));
    }
}
