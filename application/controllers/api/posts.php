
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class Posts extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('api/post','api/common_model','api/notify'));
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
     *----------------------------create-----------------
     *@param
     *  @type_id   int
     *  @content   string
     *  @location_lat   string
     *  @location_lng   string
     *
     * ------------------------update-------------------
     * @id              int
     * @location_lat    string
     * @location_lng    string
     * @content         string
     *
    *@response  object
    * */
    function create_post()
    {

        $status = 'failure';
        $message = 'insert error';
        $results = null;
        $validation = null;

        $input = $this->input->post();
        /*insert post---------------------------------------------------------*/
        if(empty($input['id'])) {
            /*Set the form validation rules*/
            $rules = array(
                array('field' => 'type_id', 'label' => 'lang:type_id', 'rules' => 'required'),
                /*array('field'=>'content', 'label'=>'lang:content', 'rules'=>'required'),*/
                array('field' => 'location_lat', 'label' => 'lang:location_lat', 'rules' => 'required'),
                array('field' => 'location_lng', 'label' => 'lang:location_lng', 'rules' => 'required')
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
                    'location_name' => $this->common_model->getLocationNameByLatLng($input['location_lat'], $input['location_lng'])
                );
                if ($this->post->createPost($record)) {
                    $status = 'success';
                    $message = 'insert post successfully!';

                    //push notify for province when user create post
                    $this->notify->send_notify_provinces($record);
                }
            }
        }
        /*update post ------------------------------------------------------------------*/
        else{
            /*Set the form validation rules*/
            $rules = array(
                array('field' => 'location_lat', 'label' => 'lang:location_lat', 'rules' => 'required'),
                array('field' => 'location_lng', 'label' => 'lang:location_lng', 'rules' => 'required')
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
                $account = $this->account_info;
                $record = array(
                    'content' => $input['content'],
                    'location_lat' => $input['location_lat'],
                    'location_lng' => $input['location_lng'],
                    'location_name' => $this->common_model->getLocationNameByLatLng($input['location_lat'], $input['location_lng']),
                    'created_by' => $account['id']
                );

                $isUpdate = $this->post->createPost($record,$input['id']);
                if ($isUpdate) {
                    $status = 'success';
                    $message = 'Update post successfully.';

                    //push notify for province when user update my post
                    $data_send = array(
                        'id' => $input['id']
                    );
                    $this->notify->send_notify_provinces($data_send);
                } else{
                    $status = 'failure';
                    $message = 'update error';
                }
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
     * @completed int
     * @status int
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

    /**url : http://domain/xproject/api/posts/get_my_post_newest_by_time
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @account_id   string
     * @created_at   date time
     * @status int
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
            $input = $this->input->post();
            $listPost = $this->post->getNewestMyPosts($input);
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
     * @location_lat             string
     * @location_lng             string
     * @query                      string       allow null
    * @page  int          allow null
    * @row_per_page  int  allow null
    *
    *@response  object
    * */

    /**
     * search newest post
     * add parram
     * @created_at       string date time
    */

    function search_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
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
            $message = API_VALIDATION;
            $validation = array(
                'location_lat' => $this->form_validation->error('location_lat'),
                'location_lng' => $this->form_validation->error('location_lng')
            );
        } else {
            $listPost = $this->post->searchPost($this->account_info);
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
                API_TOTAL_PAGE => $this->post->postSearchTotalPage($this->account_info)
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
     * @id   int
     * @account_id int
     *@response  object
     * */

    function delete_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'id', 'label'=>'lang:id', 'rules'=>'required'),
            array('field'=>'account_id', 'label'=>'lang:account_id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'id' => $this->form_validation->error('id'),
                'account_id' => $this->form_validation->error('account_id')
            );
        } else {
            $input = $this->input->post();
            $isDelete = $this->post->deletePostById($input['id'],$input['account_id']);
            if($isDelete) {
                $status = API_SUCCESS;
                $message = API_SUCCESS;
            }
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }


    /**url : http://domain/xproject/api/posts/get_post_info_by_id
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @id   int
     *@response  object
     * */

    function get_post_info_by_id_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'id', 'label'=>'lang:id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'id' => $this->form_validation->error('id')
            );
        } else {
            $input = $this->input->post();
            $postInfo = $this->post->getPostInfoById($input['id']);
            $status = API_SUCCESS;
            $message = API_SUCCESS;
            $results = $postInfo;
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }


    /**url : http://domain/xproject/api/posts/get_post_detail_by_id
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @id   int
     *@response  object
     * */

    function get_post_detail_by_id_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'id', 'label'=>'lang:id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'id' => $this->form_validation->error('id')
            );
        } else {
            $input = $this->input->post();
            $postInfo = $this->post->getPostDetailById($input['id']);
            $status = API_SUCCESS;
            $message = API_SUCCESS;
            $results = $postInfo;
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }

    /**url : http://domain/xproject/api/posts/pick
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @id             string
     *
     *@response  object
     * */
    function pick_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'id', 'label'=>'lang:id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'id' => $this->form_validation->error('id')
            );
        } else {
            $input = $this->input->post('id');
            $pickStatus = $this->post->pick($input,$this->account_info);
            if($pickStatus){
                $message = '';
                $status = API_SUCCESS;

                //push notify for user create this post
                //$this->notify->
            }
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }

    /**url : http://domain/xproject/api/posts/destroy
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @id             string
     *
     *@response  object
     * */
    function destroy_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'id', 'label'=>'lang:id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'id' => $this->form_validation->error('id')
            );
        } else {
            $account = $this->account_info;
            $input = $this->input->post();
            $post_info = $this->post->getPostDetailById($input['id']);
            if($account['id'] == $post_info->created_by || $account['id'] == $post_info->picked_by){
                $destroyStatus = $this->post->destroy($input['id']);
                if($destroyStatus){
                    $message = '';
                    $status = API_SUCCESS;
                }
            }
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }

    /**url : http://domain/xproject/api/posts/complete
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @id   string
     *
     *@response  object
     * */

    function complete_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'id', 'label'=>'lang:id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'id' => $this->form_validation->error('id')
            );
        } else {
            $account = $this->account_info;
            $input = $this->input->post();
            $post_info = $this->post->getPostDetailById($input['id']);
            if($account['id'] == $post_info->created_by){
                $completeStatus = $this->post->complete($input['id']);
                if($completeStatus){
                    $message = '';
                    $status = API_SUCCESS;
                }
            }
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }

    /**url : http://domain/xproject/api/posts/get_posts_of_provider
     *header
     * @token  string has
     *
     *@param
     * @account_id   string
     * @page   int
     * @row_per_page  int
     * @completed int
     * @status int
     *
     *@response  object
     * */

    function get_posts_of_provider_post(){
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
            $input = $this->input->post();
            $listPost = $this->post->getPostsOfProvider($input['account_id'],$input['page'],$row_per_page);
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
                API_PAGE => $input['page'],
                API_ROW_PER_PAGE => count($results),
                API_TOTAL_PAGE => ceil($this->post->countAllPost($input['account_id'])/$row_per_page)
            )
        );
        $this->response($data, HEADER_SUCCESS);
    }

    /**url : http://domain/xproject/api/posts/get_provider_post_newest_by_time
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @account_id   string
     * @created_at   date time
     * @status int
     *
     *@response  object
     * */

    function get_provider_post_newest_by_time_post(){
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
            $input = $this->input->post();
            $listPost = $this->post->getNewestProviderPosts($input);
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

    /**url : http://domain/xproject/api/posts/picks
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @arr_post_id             json
     *
     *@response  object
     * */
    function picks_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;
        $json = $this->input->post('arr_post_id');
        if(!empty($json)){
            $array_post_id = json_decode($json,true);
            //var_dump($array_post_id); die();
            $pickStatus = $this->post->picks($array_post_id,$this->account_info);
            if(is_array($pickStatus)){
                $message = '';
                $status = API_FAILURE;
                $results = $pickStatus;
            }else{
                if($pickStatus == true){
                    $message='';
                    $status = API_SUCCESS;
                }
                else{
                    $message='';
                    $status = API_ERROR;
                }
            }
        }else{
            $message = '';
            $status = API_FAILURE;
            $validation = 'Find not found json post_id.';
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


