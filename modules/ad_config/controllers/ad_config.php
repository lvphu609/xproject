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

    function get_post_type_list(){
        echo 'get_post_type_list';
    }
}


?>