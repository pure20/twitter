<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class History extends MY_Controller
{
    public function index()
    {
        $this->load->model('tweet_model');
		    $content['savedSearch'] = $this->tweet_model->getSavedSearch($this->user);
        $page['mainContent'] = $this->load->view('history/index', $content, true);
        $this->load->view('layout', $page);
    }
}