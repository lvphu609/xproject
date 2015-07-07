<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ad_config extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->info_user = $this->session->userdata('user_login');

        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->load->model('ad_config/ad_config_model');
    }

    function config_post_types(){
        $data = array(
            'header_title' => $this->lang->line('header_title'),
            'page_header' => $this->lang->line('page_title_post_type'),
            'js_file_module' => array(
                'ad_config/assets/js/mod_config.js'
            ),
            'css_file_module' => array(
                'ad_config/assets/css/style.css'
            )
        );

        $this->load->view('templates/admin/header',$data);
        $this->load->view('templates/admin/container');
        $this->load->view('templates/admin/menu');
        $this->load->view('config_post_type');
        $this->load->view('templates/admin/footer');
    }
}


?>