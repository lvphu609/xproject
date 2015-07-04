<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ad_login extends MX_Controller
{
    public function __construct()
    {
    parent::__construct();
    }

    function index(){
       $this->load->view('login_view');
    }


}


?>