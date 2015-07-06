<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad_home extends MX_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->info_user   = $this->session->userdata('user_login');
        if(!$this->info_user){
            redirect(base_url('admin/login'));
        }

        $model = array('ad_home_model');
        $this->load->models($model);

    }

    public function index()
    {
        echo "hello";
    }



}