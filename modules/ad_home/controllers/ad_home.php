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
        $data = array(
            'header_title' => $this->lang->line('header_title'),
            'page_header' => $this->lang->line('page_title_dashboard'),
            'js_file_module' => array(
                'ad_login/assets/js/mod_login.js'
            ),
            'css_file_module' => array(
                'ad_login/assets/css/style.css'
            )
        );

        $this->load->view('templates/admin/header',$data);
        $this->load->view('templates/admin/container');
        $this->load->view('templates/admin/menu');
        $this->load->view('home_view');
        $this->load->view('templates/admin/footer');
    }



}