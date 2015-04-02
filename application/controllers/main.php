<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function index()
    {
        $content = array();
        $page['main_content'] = $this->load->view('main/index', $content, true);
        $this->load->view('layout', $page);
    }
    
    public function getTweet()
    {
        $settings = array(
            'oauth_access_token' => "40403946-aEWpOAybpm5v6OdzXPX5KkqjS8DCrgx4i9wmUuIDE",
            'oauth_access_token_secret' => "zRjdX4SQRgYPbX1KiuUZKNaN1vYYXKXqizCyQ4Jd8",
            'consumer_key' => "2Aq4W7KZpgtyV9GYlpavQ",
            'consumer_secret' => "LJNAPR8fesSH40rDTnkXNm1fF4erop14owRZ0SFbstE"
        );
    
        $response = null;
        $lat = $this->input->post('lat', true);
        $lng = $this->input->post('lng', true);
        $searchQuery = $this->input->post('address', true);
        if ($searchQuery) {
            // call twitter api to get result
            $url = 'https://api.twitter.com/1.1/search/tweets.json';
            $getField = '?q=' . $searchQuery . '+#' . $searchQuery . '&geocode=' . $lat . ',' .$lng
            . ',50km&result_type=recent';
            $requestMethod = 'GET';
            $twitter = new TwitterAPIExchange($settings);
            $response = $twitter->setGetfield($getField)
                ->buildOauth($url, $requestMethod)
                ->performRequest();

        }
    
        echo $response;
        exit;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */