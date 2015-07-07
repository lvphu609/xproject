<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad_dashboard extends MX_Controller {
    public function __construct()
    {
        parent::__construct();

    }
    public function index(){
          $data = array(
              'css_file_module' => array(
                'ad_dashboard/assets/css/style.css'
              )
          );
          $this->load->view('dashboard_view',$data, false);
    }
}
