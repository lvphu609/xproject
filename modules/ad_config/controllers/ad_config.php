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

        //paging----
        $page = 1;
        $search = "";
        if(isset($_GET['page'])){
            if(!empty($_GET['page'])&&$_GET['page']!=""&&$_GET['page']!=null&&  is_numeric($_GET['page'])){
                $page = intval($_GET['page']);
            }
        }

        if(isset($_GET['search'])){
            if(!empty($_GET['search'])&&$_GET['search']!=""){
                $search = trim($_GET['search']);
            }
        }


        $this->load->library('my_paging');
        $config['base_url'] = base_url('admin/config/post_types?search='.$search.'&');
        $config['total_rows'] = $this->ad_config_model->countRecord('type_posts',$search);
        $config['per_page'] = DEFIND_ADMIN_PER_PAGE_DEFAULT;
        $config['cur_page'] =$page;
        $this->my_paging->initialize($config);
        $pagination = $this->my_paging->create_links();


        $data = array(
            'header_title' => $this->lang->line('header_title'),
            'page_header' => $this->lang->line('page_title_post_type'),
            'js_file_module' => array(
                'ad_config/assets/js/mod_config.js'
            ),
            'css_file_module' => array(
                'ad_config/assets/css/style.css'
            ),
            'post_type_list' => $this->ad_config_model->postTypeList(DEFIND_ADMIN_PER_PAGE_DEFAULT,$page,$search),
            'pagination' => $pagination,
            'search' => $search
        );

        $this->load->view('templates/admin/header',$data);
        $this->load->view('templates/admin/container');
        $this->load->view('templates/admin/menu');
        $this->load->view('config_post_type',$data);
        $this->load->view('templates/admin/footer');
    }

    function create_post_type(){

    }

}


?>