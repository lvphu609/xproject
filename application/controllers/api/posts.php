
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class Posts extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('api/post','api/common_model'));
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

        //check token-----------------------------------------
        $checkToken = $this->common_model->checkAccessToken();
        if(!$checkToken['status']){
            $this->response($checkToken['res'], HEADER_SUCCESS);
        }else{
            $this->account_info = $checkToken['account'];
        }
    }

    /**url : http://domain/xproject/api/posts/create
    *header
    * @token  string has
    *
    *@param
    *  @type_id   int
    *  @content   string
     * @location_lat   string
     * @location_lng   string
    *
    *@response  object
    * */
    function create_post()
    {
        $status = 'failure';
        $message = 'insert error';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'type_id', 'label'=>'lang:type_id', 'rules'=>'required'),
            /*array('field'=>'content', 'label'=>'lang:content', 'rules'=>'required'),*/
            array('field'=>'location_lat', 'label'=>'lang:location_lat', 'rules'=>'required'),
            array('field'=>'location_lng', 'label'=>'lang:location_lng', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = 'validation';
            $validation = array(
                'type_id' => $this->form_validation->error('type_id'),
                /*'content' => $this->form_validation->error('content'),*/
                'location_lat' => $this->form_validation->error('location_lat'),
                'location_lng' => $this->form_validation->error('location_lng')
            );
        } else {
            $input = $this->input->post();
            $account = $this->account_info;
            $record = array(
                'type_id' => $input['type_id'],
                'content' => $input['content'],
                'location_lat' => $input['location_lat'],
                'location_lng' => $input['location_lng'],
                'created_by' => $account['id'],
                'location_name' =>  $this->common_model->getLocationNameByLatLng($input['location_lat'],$input['location_lng'])
            );
            if ($this->post->createPost($record)) {
                $status = 'success';
                $message = 'insert post successfully!';
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

    //the posts is important
    /**url : http://domain/xproject/api/posts/emergency
    *header
    * @token  string has
    *
    *@param
     * @location_lat   string
     * @location_lng   string
    *
    *@response  object
    * */
    function emergency_post(){
        $status = 'failure';
        $message = 'error';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'location_lat', 'label'=>'lang:location_lat', 'rules'=>'required'),
            array('field'=>'location_lng', 'label'=>'lang:location_lng', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = 'validation';
            $validation = array(
                'location_lat' => $this->form_validation->error('location_lat'),
                'location_lng' => $this->form_validation->error('location_lng')
            );
        } else {
            $input = $this->input->post();
            $isSavePost = $this->post->saveEmergency($this->account_info, $input);
            if ($isSavePost) {
                $status = 'success';
                $message = '';
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

    //the posts is important
    /**url : http://domain/xproject/api/posts/get_post_list_by_location
    *header
    * @token  string has
    *
    *@param
     * @location_lat   string
     * @location_lng   string
    *
    *@response  object
    * */

    function get_post_list_by_location_post(){
        $status = 'failure';
        $message = 'error';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'location_lat', 'label'=>'lang:location_lat', 'rules'=>'required'),
            array('field'=>'location_lng', 'label'=>'lang:location_lng', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = 'validation';
            $validation = array(
                'location_lat' => $this->form_validation->error('location_lat'),
                'location_lng' => $this->form_validation->error('location_lng')
            );
        } else {
            $input = $this->input->post();
            $listPost = $this->post->getPostByLocation($input);
            $message = '';
            $status = 'success';
            $results = $listPost;
        }

        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
            'validation' => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }

    /**url : http://domain/xproject/api/posts/get_my_posts_post
     *header
     * @token  string has
     *
     *@param
     * @account_id   string
     * @page   int
     * @row_per_page  int
     *
     *@response  object
     * */

    function get_my_posts_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'account_id', 'label'=>'lang:account_id', 'rules'=>'required'),
            array('field'=>'page', 'label'=>'lang:page', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = 'validation';
            $validation = array(
                'account_id' => $this->form_validation->error('account_id'),
                'page' => $this->form_validation->error('page')
            );
        } else {
            $row_per_page = DEFIND_PER_PAGE_DEFAULT;

            if($this->input->post('row_per_page')){
                $row_per_page = $this->input->post('row_per_page');
            }

            $listPost = $this->post->getMyPosts($this->input->post('account_id'),$this->input->post('page'),$row_per_page);
            $message = '';
            $status = API_SUCCESS;
            $results = $listPost;
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation,
            API_PAGINATION => array(
                API_PAGE => $this->input->post('page'),
                API_ROW_PER_PAGE => count($results),
                API_TOTAL_PAGE => ceil($this->post->countAllPost($this->input->post('account_id'))/$row_per_page)
            )
        );
        $this->response($data, HEADER_SUCCESS);
    }

    /**url : http://domain/xproject/api/posts/get_newest_post_by_time
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @account_id   string
     * @created_at   date time
     *
     *@response  object
     * */

    function get_my_post_newest_by_time_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'account_id', 'label'=>'lang:account_id', 'rules'=>'required'),
            array('field'=>'created_at', 'label'=>'lang:created_at', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'account_id' => $this->form_validation->error('account_id'),
                'created_at' => $this->form_validation->error('created_at')
            );
        } else {
            $listPost = $this->post->getNewestMyPosts($this->input->post());
            $message = '';
            $status = API_SUCCESS;
            $results = $listPost;
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }



    /**url : http://domain/xproject/api/posts/search
    * @method: POST
    *header
    * @token  string has
    *
    *@param
    * @lat   string
    * @lng   string
    * @key   string       allow null
    * @page  int          allow null
    * @row_per_page  int  allow null
    *
    *@response  object
    * */

    function search_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'lat', 'label'=>'lang:lat', 'rules'=>'required'),
            array('field'=>'lng', 'label'=>'lang:lng', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'lat' => $this->form_validation->error('lat'),
                'lng' => $this->form_validation->error('lng')
            );
        } else {
            $listPost = $this->post->searchPost();
            $message = '';
            $status = API_SUCCESS;
            $results = $listPost;
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation,
            API_PAGINATION => array(
                API_PAGE => $this->input->post('page'),
                API_ROW_PER_PAGE => count($results),
                API_TOTAL_PAGE => $this->post->postSearchTotalPage()
            )
        );
        $this->response($data, HEADER_SUCCESS);
    }


    /**url : http://domain/xproject/api/posts/delete
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @post_id   int
     *
     *@response  object
     * */

    function delete_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'post_id', 'label'=>'lang:post_id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'post_id' => $this->form_validation->error('post_id')
            );
        } else {
            $listPost = null; //$this->post->searchPost();
            $message = '';
            $status = API_SUCCESS;
            $results = $listPost;
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }


}


