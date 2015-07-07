
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

                    //push notify for province
                    /*$message_to_send = array(
                        'title' => 'Accident',
                        'content' => $input['content'],
                        'location_lat' => $input['location_lat'],
                        'location_lng' => $input['location_lng'],
                        'created_by' => $account['id'],
                        'location_name' => $this->common_model->getLocationNameByLatLng($input['location_lat'], $input['location_lng']),
                        'id' => $this->post->getPostIdForPushNotify($record)
                    );
                    $this->send_notify_provinces($message_to_send,$record);*/
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

                    //push notify for province
                    /*$account = $this->account_info;
                    if (empty($input['picked_by'])) {
                        $message_to_send = array(
                            'title' => 'Accident modify',
                            'content' => $input['content'],
                            'location_lat' => $input['location_lat'],
                            'location_lng' => $input['location_lng'],
                            'created_by' => $account['id'],
                            'location_name' => $this->common_model->getLocationNameByLatLng($input['location_lat'], $input['location_lng']),
                            'id' => $this->post->getPostIdForPushNotify($record)
                        );
                        $this->send_notify_provinces($message_to_send,$record);
                    } else {
                        //push notify for user create post
                        //$this->send_notify_users($record);
                    }*/
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
            $isDelete = $this->post->deletePostById($this->input->post('id'),$this->input->post('account_id'));
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
            $postInfo = $this->post->getPostInfoById($this->input->post('id'));
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

    /**
     * send notify for all province -> 10Km
     * */

    function send_notify_provinces($message,$record){
        //push notify for province
        $message_to_send = array(
            'title' => $message['title'],
            'content' => $message['content'],
            'location_lat' => $message['location_lat'],
            'location_lng' => $message['location_lng'],
            'created_by' => $message['id'],
            'location_name' => $this->common_model->getLocationNameByLatLng($message['location_lat'], $message['location_lng']),
            'id' => $this->post->getPostIdForPushNotify($record)
        );
        $account_array = $this->account->getAccountIdByLocation($record, 10);
        $regId_array = array();
        if (count($account_array) > 0) {
            for ($i = 0; $i < count($account_array); $i++) {
                array_push($regId_array, $this->notify->getRegId($account_array[$i]['created_by']));
            }
            //var_dump($regId_array); die();
            for ($i = 0; $i < count($account_array); $i++) {
                $this->notify->sendPushNotificationToGCM(array($regId_array[$i]), $message_to_send);
            }
        }
    }

    /**
     * send notify for user created post when province picked your post
     * */

    function send_notify_users($record){
        $pickerInfo = $this->account->getAccountInfoById($record['account_id']);
        //var_dump($pickerInfo['full_name']); die();
        $message_to_send = array(
            'title' => 'My post was picked!',
            'location_name' => $pickerInfo['full_name'].' has picked your post!',
            'location_lat' => $record['location_lat'],
            'location_lng' => $record['location_lng'],
            'created_by' => $record['account_id'],
            'id' => $record['id']
        );
        //var_dump($account_array);die();
        $regId_array = array($this->notify->getRegId($record['account_id']));
        $this->notify->sendPushNotificationToGCM($regId_array, $message_to_send);
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
            $postInfo = $this->post->getPostDetailById($this->input->post('id'));
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
            $pickStatus = $this->post->pick($this->input->post('id'),$this->account_info);
            if($pickStatus){
                $message = '';
                $status = API_SUCCESS;
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
            $post_info = $this->post->getPostDetailById($this->input->post('id'));
            if($account['id'] == $post_info->created_by || $account['id'] == $post_info->picked_by){
                $destroyStatus = $this->post->destroy($this->input->post('id'));
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
            $post_info = $this->post->getPostDetailById($this->input->post('id'));
            if($account['id'] == $post_info->created_by){
                $completeStatus = $this->post->complete($this->input->post('id'));
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

            $listPost = $this->post->getPostsOfProvider($this->input->post('account_id'),$this->input->post('page'),$row_per_page);
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

    /**url : http://domain/xproject/api/posts/get_provider_post_newest_by_time
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
            $listPost = $this->post->getNewestProviderPosts($this->input->post());
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


