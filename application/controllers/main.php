<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function index()
    {
        $content = array();
        $page['mainContent'] = $this->load->view('main/index', $content, true);
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
            $this->load->model('tweet_model');
            	
            // check tweet from 1 hour cache
            $tweet = $this->tweet_model->getTweetCache($this->user, $searchQuery);
            if (!is_null($tweet)) {
                echo $tweet->tweet;
                exit;
            }
            else {
                // call twitter api to get result
                $url = 'https://api.twitter.com/1.1/search/tweets.json';
                $getField = '?q=' . urlencode($searchQuery . '+#' . $searchQuery) . '&geocode=' . urlencode($lat) . ',' .urlencode($lng)
                    . ',50km&result_type=recent';
                $requestMethod = 'GET';
                $twitter = new TwitterAPIExchange($settings);
                $response = $twitter->setGetfield($getField)
                    ->buildOauth($url, $requestMethod)
                    ->performRequest();

                // prepare the saved data
                $saveData = array(
                    'uid' => $this->user,
                    'search_text' => $searchQuery,
                    'tweet' => $response,
                    'created_at' => date('Y-m-d H:s:i')
                );
                
                $this->tweet_model->saveSearch($saveData);
            }
        }
    
        echo $response;
        exit;
    }
}