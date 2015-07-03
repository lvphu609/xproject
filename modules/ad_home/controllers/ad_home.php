<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad_home extends MX_Controller {
    public function __construct()
    {
        parent::__construct();
        $model = array('ad_home_model');
        $this->load->models($model);
        $this->load->library('session');
        $this->info_user   = $this->session->userdata('user_login');
        if(!$this->info_user){
            redirect(base_url('admin/ad_login'));
        }else{
            $user_session=$this->info_user;
            $isActive = $this->tsms_common_model->checkUserActive($user_session['id']);
            if($isActive==false){
                $this->tsms_common_model->logout();
            }
        }
        $this->lang->load('api_common','vn');
    }

    public function index()
    {
        $user_session=$this->info_user;
        $page_title = array(
            'title' => $this->lang->line(),
            'num_new_supervisor' => $this->common_model->countNewSupervisor(),
            'number_of_scanned_tsms_today' => $this->common_model->count_all_scanned_tsms_data(),
            'session_username' => $user_session['username'],
            'session_userlogin' => $user_session['username']
        );

        $this->load->view('templates/header', $page_title);
        $this->load->view('templates/left_menu');
        $this->load->view('templates/container');
        $this->load->view('home_view');
        $this->load->view('templates/footer');
    }

}