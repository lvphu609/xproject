<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ad_config extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->info_user = $this->session->userdata('user_login');
        if(!$this->info_user){
            redirect(base_url('admin/login'));
        }

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
            'header_title' => $this->lang->line('config_header_title'),
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
        $data = array(
            'header_title' => $this->lang->line('config_header_title'),
            'page_header' => $this->lang->line('page_title_post_type_create'),
            'js_file_module' => array(
                'ad_config/assets/js/mod_config.js'
            ),
            'css_file_module' => array(
                'ad_config/assets/css/style.css',
                'ad_config/assets/css/config_post_type.css',
            ),
            'js_file' => array(
                'js/jquery.cropit.js'
            )
        );

        $this->load->view('templates/admin/header',$data);
        $this->load->view('templates/admin/container');
        $this->load->view('templates/admin/menu');
        $this->load->view('config_post_type_create',$data);
        $this->load->view('templates/admin/footer');
    }


    function store_post_type(){
        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'name', 'label'=>'lang:post_type_name', 'rules'=>'trim|required'),
            array('field'=>'description', 'label'=>'lang:post_type_description', 'rules'=>'trim|required'),
            array('field'=>'avatar', 'label'=>'lang:avatar', 'rules'=>'trim|required')
        );
        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $this->create_post_type();
        }
        else{
            $isCreate = $this->ad_config_model->createPostType();
            if($isCreate){
                redirect(base_url('admin/config/post_types'));
            }else{
                $this->create_post_type();
            }
        }
    }

    function edit_post_type($id){
        $data = array(
            'header_title' => $this->lang->line('config_header_title'),
            'page_header' => $this->lang->line('page_title_post_type_create'),
            'js_file_module' => array(
                'ad_config/assets/js/mod_config.js'
            ),
            'css_file_module' => array(
                'ad_config/assets/css/style.css',
                'ad_config/assets/css/config_post_type.css',
            ),
            'js_file' => array(
                'js/jquery.cropit.js'
            ),
            'post_type' => $this->ad_config_model->getPostTypeById($id)
        );

        $this->load->view('templates/admin/header',$data);
        $this->load->view('templates/admin/container');
        $this->load->view('templates/admin/menu');
        $this->load->view('config_post_type_edit',$data);
        $this->load->view('templates/admin/footer');
    }

    function update_post_type(){
        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'name', 'label'=>'lang:post_type_name', 'rules'=>'trim|required'),
            array('field'=>'description', 'label'=>'lang:post_type_description', 'rules'=>'trim|required'),
            array('field'=>'avatar', 'label'=>'lang:avatar', 'rules'=>'trim|required')
        );
        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $id = $this->input->post('id');
            $this->edit_post_type($id);
        }
        else{
            $id = $this->input->post('id');
            $isUpdate = $this->ad_config_model->updatePostType();
            if($isUpdate){
                redirect(base_url('admin/config/post_types'));
            }else{
                $this->edit_post_type($id);
            }
        }
    }

    public function delete_post_type(){
        $id = $this->input->post('id');
        $isDelete = $this->ad_config_model->delPostType($id);
        $message = "";
        $status = WEB_ADMIM_FAILURE;
        if(!is_array($isDelete)){
            $status = WEB_ADMIM_SUCCESS;
        }else{
            $message ='<div class="alert alert-warning" role="alert">
                        <strong>Cảnh báo!</strong>Loại yêu cầu <strong>'. $isDelete['title'] .'</strong> đang được sử dụng.
                    </div>';
        }

        $result = array(
            WEB_ADMIM_STATUS => $status,
            WEB_ADMIM_RESULTS => $id,
            WEB_ADMIM_MESSAGE => $message
        );
        header('Content-Type: application/x-json; charset=utf-8');
        echo (json_encode($result));
    }

}


?>