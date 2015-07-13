
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class Config extends Rest_Controller
{
     function __construct()
    {
        parent::__construct();
        $this->load->model(array('api/config_model','file_model'));
        $this->load->helper(array('form', 'url'));

        /*validation--------------*/
        $this->load->library('form_validation');
        $this->lang->load('api_common','vn');
        //custom html of message validation
        $this->form_validation->set_error_delimiters('', '');

        // custom lang of the form validation
        $set_message = array(
            'required'=>$this->lang->line('is_required'),
            'valid_email'=>$this->lang->line('is_valid_email'),
            'matches'=>$this->lang->line('matches_field'),
            'min_length'=>$this->lang->line('min_length'),
            'is_unique'=>$this->lang->line('is_unique')
        );
        $this->form_validation->set_message($set_message);

    }

    /**
     * create type post
     * @method: Post
     * @link access: http://localhost/xproject/api/common/create_type_post
     *
     * @return array
     */
    function create_type_post_post(){
         //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;
        
        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'name', 'label'=>'lang:type_post_name', 'rules'=>'required'),
            array('field'=>'description', 'label'=>'lang:type_post_description', 'rules'=>'required'),
            array('field'=>'avatar', 'label'=>'lang:type_post_avatar', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);
       
        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = '';
            $validation = array(
                'name' => $this->form_validation->error('name'),
                'description' => $this->form_validation->error('description'),
                'avatar' => $this->form_validation->error('avatar')
            );
        }
        //validate success
        else{
            //call model save account data
            $file_id = $this->file_model->do_upload('type_posts',TRUE);
            $dataInput = $this->input->post();

            $typePostRecord = array(
                'name' => $dataInput['name'],
                'description' => $dataInput['description'],
                'avatar' => $file_id
            ); 
            //save record account
            $isInsert = $this->config_model->createTypePost($typePostRecord);
            
            if(!$isInsert){
                //delete avatar
                $this->file_model->deleteFileById($file_id);

                $status = 'failure';
                $message = "insert error";
            }
        }

        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
            'validation' => $validation
        );

        $this->response($data, HEADER_SUCCESS);
    }

    function delete_type_post_post(){
    	 //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

    	$id = $this->input->post('id');
    	$isDelete = $this->config_model->deleteTypePostById($id);
    	if(!$isDelete){
    		$status = 'failure';
    		$message = 'delete fail';
    	}

    	$data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
            'validation' => $validation
        );

        $this->response($data, HEADER_SUCCESS);
    }

    function get_list_type_post_get(){
        //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        $results = $this->config_model->getListTypePost();

        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
            'validation' => $validation
        );

        $this->response($data, HEADER_SUCCESS);
    }

    function get_post_type_emergency_get(){
        //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        $results = $this->config_model->getPostTypeEmergency();

        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
            'validation' => $validation
        );

        $this->response($data, HEADER_SUCCESS);
    }

    function test_post(){
        $this->load->model('api/notify');
        $this->load->model('api/post');
        $posts = $this->post->getMyPosts(60,1,100);
        $posts = array('data' => $posts);
        $status = $this->notify->sendPushNotificationToGCM(array('cQYbgQ9cg_M:APA91bGlI_HKSSYvoSTJKpFwdZak4h-jblX36oUKj-ekwkDS0T3Xf0S3VSyJqRGk_tfd17cHfn8vLHI-24Ml7KwugGgVD6Fo5_JEVLaEN8AyIoYjYzTeNwv05fzMiM_CrOYfp8iFIyUY'), $posts);
        $data = array(
            'status' => $status
        );

        $this->response($data, HEADER_SUCCESS);
    }
}