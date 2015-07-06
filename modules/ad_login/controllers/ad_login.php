<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ad_login extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('admin_login','vn');
    }

    function index(){

        $data = array(
            'header_title' => $this->lang->line('header_title'),
            'js_file_module' => array(
                'ad_login/assets/js/mod_login.js'
            ),
            'css_file_module' => array(
                'ad_login/assets/css/style.css'
            )
        );

        $this->load->view('templates/admin/header',$data);
        $this->load->view('templates/admin/container');
        $this->load->view('login_view');
        $this->load->view('templates/admin/footer');
    }


}


?>