<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ad_login extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->info_user = $this->session->userdata('user_login');
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->load->model('ad_login/ad_login_model');
    }
    function index($validation = false){
        if(!empty($this->info_user)){
            redirect(base_url('admin'));
        }

        $data = array(
            'header_title' => $this->lang->line('header_title'),
            'js_file_module' => array(
                'ad_login/assets/js/mod_login.js'
            ),
            'css_file_module' => array(
                'ad_login/assets/css/style.css'
            )
        );

        if($validation){
            $data['error'] = true;
        }

        $this->load->view('templates/admin/header',$data);
        $this->load->view('templates/admin/container');
        $this->load->view('login_view');
        $this->load->view('templates/admin/footer');
    }

    function check_login(){
        if(!empty($this->info_user)){
            redirect(base_url('admin'));
        }

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'username', 'label'=>'lang:username', 'rules'=>'trim|required'),
            array('field'=>'password', 'label'=>'lang:password', 'rules'=>'trim|required'),
        );
        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        }
        else{
            $input = $this->input->post();
            $checkLogin = $this->ad_login_model->checkLogin();
            if($checkLogin){
                redirect(base_url('admin'));
            }else{
                $this->index(true);
            }
        }
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url('admin/auth'));
    }

}


?>