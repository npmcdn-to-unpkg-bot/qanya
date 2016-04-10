<?php
use Illuminate\Support\Facades\Request;
use Facebook\Facebook;
/**
 * facebook interaction stuff
 */
class FacebookHelper
{
    public static function fb_init()
    {
        $fb = new \Facebook\Facebook([
            'app_id' => '182388651773669',
            'app_secret' => '3b63ec9b8ba39d8a70c9b6bcc588dfae',
            'default_graph_version' => 'v2.4',
            'default_access_token' => '931376120263856|flD4cke-zXTOcKq6xIc60SVy7QM', // optional
        ]);
        return $fb;
    }
}
