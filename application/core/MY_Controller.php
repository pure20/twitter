<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';

class MY_Controller extends CI_Controller
{
    public $user;
    public function __construct()
    {
        parent::__construct();
        @session_start();
        $this->user = $this->input->cookie(COOKIE_USER, true);
        if (!$this->user) {
            $userData = array(
                'name'   => COOKIE_USER,
                'value'  => session_id(),
                'expire' => 3600,
            );
            
            $this->input->set_cookie($userData);
        }
        
        //$this->load->vars($this->global);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */