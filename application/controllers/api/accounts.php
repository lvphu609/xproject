
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class Accounts extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('api/account','file_model','api/common_model'));
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
            'is_unique'=>$this->lang->line('is_unique'),
            'integer'=>$this->lang->line('account_type_int')
        );
        $this->form_validation->set_message($set_message);

    }

    /*url : http://domain/xproject/api/accounts/create
     * @method POST
     * param
     *
     *  @username             string
     *  @password             string md5
     *  @confirm_password     string md5
     *  @email                string
     *  @full_name            string
     *  @date_of_birth        string
     *  @gender               string
     *  @identity_card_id     string
     *  @phone_number         string
     *  @blood_group_id       int
     *  @blood_group_rh_id    int
     *  @avatar               string base64
     *  @address              string
     *  @contact_name         string
     *  @contact_phone        string
     *  @account_type         int
     *@response  object
     * */

    function create_post()
    {
        //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*create account--------------------------------------------------------------------------*/
        $input = $this->input->post();
        if(empty($input['id']))
        {
            /*Set the form validation rules*/
            $rules = array(
                array('field' => 'username', 'label' => 'lang:username', 'rules' => 'required|min_length[5]|is_unique[accounts.username]'),
                array('field' => 'password', 'label' => 'lang:password', 'rules' => 'required'),
                array('field' => 'confirm_password', 'label' => 'lang:confirm_password', 'rules' => 'required|matches[password]'),
                array('field' => 'email', 'label' => 'lang:email', 'rules' => 'required|valid_email|is_unique[accounts.email]'),
                array('field' => 'full_name', 'label' => 'lang:full_name', 'rules' => 'required'),
                array('field' => 'date_of_birth', 'label' => 'lang:date_of_birth', 'rules' => 'required|callback_date_valid'),
                array('field' => 'gender', 'label' => 'lang:gender', 'rules' => 'required'),
                array('field' => 'identity_card_id', 'label' => 'lang:identity_card_id', 'rules' => 'required'),
                array('field' => 'phone_number', 'label' => 'lang:phone_number', 'rules' => 'required'),
                array('field' => 'blood_group_id', 'label' => 'lang:blood_group_id', 'rules' => 'required'),
                array('field' => 'blood_group_rh_id', 'label' => 'lang:blood_group_rh_id', 'rules' => 'required'),
                array('field' => 'avatar', 'label' => 'lang:avatar', 'rules' => 'required'),
                array('field' => 'account_type', 'label' => 'lang:account_type', 'rules' => 'required|integer'),
                /*array('field' => 'android_id', 'label' => 'lang:android_id', 'rules' => 'required')*/
            );

            /* if (empty($_FILES['avatar']['name']))
             {
                 $this->form_validation->set_rules('avatar', 'lang:avatar', 'required');
             }*/

            $this->form_validation->set_rules($rules);

            /*Check if the form passed its validation */
            if ($this->form_validation->run() == FALSE) {
                $status = 'failure';
                $message = '';
                $validation = array(
                    'username' => $this->form_validation->error('username'),
                    'password' => $this->form_validation->error('password'),
                    'confirm_password' => $this->form_validation->error('confirm_password'),
                    'email' => $this->form_validation->error('email'),
                    'full_name' => $this->form_validation->error('full_name'),
                    'date_of_birth' => $this->form_validation->error('date_of_birth'),
                    'gender' => $this->form_validation->error('gender'),
                    'identity_card_id' => $this->form_validation->error('identity_card_id'),
                    'phone_number' => $this->form_validation->error('phone_number'),
                    'blood_group_id' => $this->form_validation->error('blood_group_id'),
                    'blood_group_rh_id' => $this->form_validation->error('blood_group_rh_id'),
                    'avatar' => $this->form_validation->error('avatar'),
                    'account_type' => $this->form_validation->error('account_type'),
                    /*'android_id' => $this->form_validation->error('android_id')*/
                );
            } //validate success
            else {
                //call model save account data
                $file_id = $this->file_model->do_upload('accounts', TRUE);
                $dataInput = $this->input->post();

                $accountRecord = array(
                    'username' => $dataInput['username'],
                    'password' => trim($dataInput['password']),
                    'email' => $dataInput['email'],
                    'full_name' => $dataInput['full_name'],
                    'date_of_birth' => $dataInput['date_of_birth'],
                    'gender' => $dataInput['gender'],
                    'identity_card_id' => $dataInput['identity_card_id'],
                    'phone_number' => $dataInput['phone_number'],
                    'blood_group_id' => $dataInput['blood_group_id'],
                    'blood_group_rh_id' => $dataInput['blood_group_rh_id'],
                    'avatar' => $file_id,
                    'address' => !empty($dataInput['address']) ? $dataInput['address'] : "",
                    'contact_name' => !empty($dataInput['contact_name']) ? $dataInput['contact_name'] : "",
                    'contact_phone' => !empty($dataInput['contact_phone']) ? $dataInput['contact_phone'] : "",
                    'account_type' => $dataInput['account_type'],
                    /*'android_id' => $dataInput['android_id']*/
                );
                //save record account
                $isInsert = $this->account->createAccount($accountRecord);

                if (!$isInsert) {
                    //delete avatar
                    $this->file_model->deleteFileById($file_id);

                    $status = 'failure';
                    $message = "insert error";
                }
            }
        }//end create account

        /*update account--------------------------------------------------------------------------*/
        else{
            $this->checkToken();
            /*Set the form validation rules*/
            $rules = array(
                array('field' => 'email', 'label' => 'lang:email', 'rules' => 'required|valid_email|callback_check_email_unique'),
                array('field' => 'full_name', 'label' => 'lang:full_name', 'rules' => 'required'),
                array('field' => 'date_of_birth', 'label' => 'lang:date_of_birth', 'rules' => 'required|callback_date_valid'),
                array('field' => 'gender', 'label' => 'lang:gender', 'rules' => 'required'),
                array('field' => 'identity_card_id', 'label' => 'lang:identity_card_id', 'rules' => 'required'),
                array('field' => 'phone_number', 'label' => 'lang:phone_number', 'rules' => 'required'),
                array('field' => 'blood_group_id', 'label' => 'lang:blood_group_id', 'rules' => 'required'),
                array('field' => 'blood_group_rh_id', 'label' => 'lang:blood_group_rh_id', 'rules' => 'required'),
            );

            $this->form_validation->set_rules($rules);

            /*Check if the form passed its validation */
            if ($this->form_validation->run() == FALSE) {
                $status = 'failure';
                $message = API_VALIDATION;
                $validation = array(
                    'email' => $this->form_validation->error('email'),
                    'full_name' => $this->form_validation->error('full_name'),
                    'date_of_birth' => $this->form_validation->error('date_of_birth'),
                    'gender' => $this->form_validation->error('gender'),
                    'identity_card_id' => $this->form_validation->error('identity_card_id'),
                    'phone_number' => $this->form_validation->error('phone_number'),
                    'blood_group_id' => $this->form_validation->error('blood_group_id'),
                    'blood_group_rh_id' => $this->form_validation->error('blood_group_rh_id'),
                );
            } //validate success
            else {
                //call model save account data
                $dataInput = $this->input->post();
                $accountRecord = array(
                    'email' => $dataInput['email'],
                    'full_name' => $dataInput['full_name'],
                    'date_of_birth' => $dataInput['date_of_birth'],
                    'gender' => $dataInput['gender'],
                    'identity_card_id' => $dataInput['identity_card_id'],
                    'phone_number' => $dataInput['phone_number'],
                    'blood_group_id' => $dataInput['blood_group_id'],
                    'blood_group_rh_id' => $dataInput['blood_group_rh_id'],
                    'address' => !empty($dataInput['address']) ? $dataInput['address'] : "",
                    'contact_name' => !empty($dataInput['contact_name']) ? $dataInput['contact_name'] : "",
                    'contact_phone' => !empty($dataInput['contact_phone']) ? $dataInput['contact_phone'] : ""
                );
                if(!empty($dataInput['avatar'])) {
                    $file_id = $this->file_model->do_upload('accounts', TRUE);
                    $accountRecord['avatar'] = $file_id;
                }
                //save record account
                $isUpdate = $this->account->createAccount($accountRecord,$dataInput['id']);

                if (!$isUpdate) {
                    //delete avatar
                    $this->file_model->deleteFileById($file_id);
                    $status = 'failure';
                    $message = "insert error";
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

    /**
        * Validate dd/mm/yyyy
    */
    public function date_valid($date)
    {
        $parts = explode("-", $date);
        if (count($parts) == 3) {      
          if (checkdate($parts[1], $parts[0], $parts[2]))
          {
            return TRUE;
          }
        }
        $this->form_validation->set_message('date_valid', $this->lang->line('date_valid'));
        return false;
    }

    /**
     * Check email validate
     *
     */
    public function check_email_unique(){
        $account_id = $this->input->post('id');
        $email = $this->input->post('email');
        $check = $this->account->checkEmailUniqueToUpdateAccount($account_id,$email);
        return $check;
    }


    /**url : http://domain/xproject/api/accounts/login
     *@param
     *  @username   string
     *  @password   string md5
     *
     *@response  object
     * */
   /* function login_post()
    {
        //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

       // Set the form validation rules
        $rules = array(
            array('field'=>'username', 'label'=>'lang:username', 'rules'=>'trim|required'),
            array('field'=>'password', 'label'=>'lang:password', 'rules'=>'trim|required'),
            array('field'=>'reg_id', 'label'=>'lang:reg_id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);
       
        //Check if the form passed its validation
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = "error";
            $validation = array(
                'username' => $this->form_validation->error('username'),
                'password' => $this->form_validation->error('password'),
                'reg_id' => $this->form_validation->error('reg_id')
            );
        }
        //validate success
        else{

            $checkLogin = $this->account->checkAccount();
            
            if($checkLogin == false){
                $status = 'failure';
                $message = $this->lang->line('login_failure');
            }else{
                $results = $checkLogin;
            }
        }

        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
            'validation' => $validation
        );

        $this->response($data, HEADER_SUCCESS);
        
    }*/

    /**url : http://domain/xproject/api/accounts/login
     *@param
     *  @username   string
     *  @password   string md5
     *
     *@response  object
     * */
    function login_post()
    {
        //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'username', 'label'=>'lang:username', 'rules'=>'trim|required'),
            array('field'=>'password', 'label'=>'lang:password', 'rules'=>'trim|required'),
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = "error";
            $validation = array(
                'username' => $this->form_validation->error('username'),
                'password' => $this->form_validation->error('password')
            );
        }
        //validate success
        else{

            $checkLogin = $this->account->checkAccount($this->input->post());

            if($checkLogin == false){
                $status = 'failure';
                $message = $this->lang->line('login_failure');
            }else{
                $results = $checkLogin;
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



    /*url : http://domain/xproject/api/accounts/logout
     * @method POST
     * @param
     *  <--- header  token --->
     *  @token  string has
     *
     *@response  object
     * */
    function logout_post()
    {
        //initialize
        $status = 'failure';
        $message = $this->lang->line('user_logout_failure');
        $results = null;
        $validation = null;
        
        $headers = $this->input->request_headers();

        if(!empty($headers['Token'])){
            $isLogout = $this->account->logout_account($headers['Token']);
            if($isLogout){
                $status = 'success';
                $message = $this->lang->line('user_logout_success');
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

    function get_list_account_get(){

        $status = 'success';
        $message = '';
        $results = null;
        $validation = array(
            'check_token' => $this->common_model->checkTokenAccess()
        );



        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
            'validation' => $validation
        );
        $this->response($data, HEADER_SUCCESS);
    }


    /*url : http://domain/xproject/api/accounts/forgot_password
     * @method POST
     * @param
     *  @email    string
     *
     *@response  object
     * */
    function forgot_password_post()
    {
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'email', 'label'=>'lang:email', 'rules'=>'required|valid_email'),
        );
        $this->form_validation->set_rules($rules);
       
        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = "error";
            $validation = array(
                'email' => $this->form_validation->error('email')
            );
        }
        //validate success
        else{
            //check exist email
            $rules = array(
                array('field'=>'email', 'label'=>'lang:email', 'rules'=>'is_unique[accounts.email]'),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                //email exist --> send key to mail
                $email = $this->input->post('email');

                //check reset_password_key exist
                //if(!$this->account->checkExistRessetPasswordKey($email)){
                    $content = $this->account->generalHtmlForGotPassword($email);

                    $dataSend = array(
                        'mail_to' => $email,
                        'subject' => $this->lang->line('forgot_password_subject'),
                        'content' => $content
                    );

                    $sendMail = $this->common_model->sendMail($dataSend);
                    
                    if(!$sendMail){
                        $status = 'failure';
                        $message = $this->lang->line('send_mail_forgot_password_fail');
                    }
                    
                /*}else{
                    $status = 'failure';
                    $message = $this->lang->line('send_mail_forgot_password_exist_key');
                }*/

            }else{
                $status = 'failure';
                $message = $this->lang->line('email_not_exist');
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


    /*url : http://domain/xproject/api/accounts/reset_password
     * @method POST
     * param
     *
     *  @code        string
     *  @password    string md5
     *  @confirm_password   string md5
     *  @email   string md5
     *
     *@response  object
     * */
    function reset_password_post()
    {
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'code', 'label'=>'lang:key_code', 'rules'=>'required'),
            array('field'=>'password', 'label'=>'lang:password', 'rules'=>'required'),
            array('field'=>'confirm_password', 'label'=>'lang:confirm_password', 'rules'=>'required|matches[password]'),
            array('field'=>'email', 'label'=>'lang:email', 'rules'=>'required')
        );
        $this->form_validation->set_rules($rules);
       
        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = "error";
            $validation = array(
                'code' => $this->form_validation->error('code'),
                'password' => $this->form_validation->error('password'),
                'confirm_password' => $this->form_validation->error('confirm_password'),
                'email' => $this->form_validation->error('email')
            );
        }
        //validate success
        else{
            //check exist forgot_password_code and email
            $input = $this->input->post();
            $updateToken = $this->account->updateTokenResetPass($input);

            if(!empty($updateToken)){
                $status = 'failure';
                $message = $updateToken;
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

    /**url : http://domain/xproject/api/accounts/get_account_info_by_id()
     * @method POST
     * param
     *
     *  @id        int
     *
     * header
     *
     * @token    string has
     *
     *@response  object
     * */
    function get_account_info_by_id_post()
    {
        $this->checkToken();

        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'id', 'label'=>'lang:id', 'rules'=>'required')
        );
        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = "error";
            $validation = array(
                'id' => $this->form_validation->error('id')
            );
        }
        //validate success
        else{
            $status = API_SUCCESS;
            $id = $this->input->post('id');
            $results = $this->account->getAccountInfoById($id);
        }

        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
            'validation' => $validation
        );
        $this->response($data, HEADER_SUCCESS);

    }

    function checkToken(){
        //check token-----------------------------------------
        $checkToken = $this->common_model->checkAccessToken();
        if(!$checkToken['status']){
            $this->response($checkToken['res'], HEADER_SUCCESS);
        }else{
            $this->account_info = $checkToken['account'];
        }
    }

    /**url : http://domain/xproject/api/accounts/change_password
     * @method POST
     * param
     *
     *
     * @id        int
     * @old_password        string md5
     * @new_password        string md5
     * @confirm_password        string md5
     *
     * header
     *
     * @token    string has
     *
     *@response  object
     * */
    function change_password_post(){
        $this->checkToken();

        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'id', 'label'=>'lang:id', 'rules'=>'required'),
            array('field' => 'old_password', 'label' => 'lang:old_password', 'rules' => 'required'),
            array('field' => 'new_password', 'label' => 'lang:new_password', 'rules' => 'required'),
            array('field' => 'confirm_password', 'label' => 'lang:confirm_password', 'rules' => 'required|matches[new_password]')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'id' => $this->form_validation->error('id'),
                'old_password' => $this->form_validation->error('old_password'),
                'new_password' => $this->form_validation->error('new_password'),
                'confirm_password' => $this->form_validation->error('confirm_password')
            );
        } else {
            $input = $this->input->post();
            $isUpdate = $this->account->changePassword($input['old_password'],$input['new_password'],$input['id']);

            if(!$isUpdate['old_password']){
                $message = $this->lang->line('old_password_is_wrong');
            }else{
                if($isUpdate['update']) {
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

    /**url : http://domain/xproject/api/accounts/update_location
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @location_lat             string
     * @location_lng             string
     * @id int
     *
     *@response  object
     * */
    function update_location_post(){
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
            $this->checkToken();
            $account = $this->account_info;
            $input = $this->input->post();
            $result = $this->account->storeLocationById($account['id'],$input);
            if($result){
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

    /**url : http://domain/xproject/api/accounts/get_location_by_id
     * @method: POST
     *header
     * @token  string has
     *
     *@param
     * @account_id             string
     *
     *@response  object
     * */
    function get_location_by_id_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'account_id', 'label'=>'lang:account_id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'account_id' => $this->form_validation->error('account_id')
            );
        } else {
            $account_id = $this->input->post('account_id');
            $result = $this->account->getLocationById($account_id);
            if($result){
                $message = '';
                $status = API_SUCCESS;
                $results = $result;
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

    /**url : http://domain/xproject/api/accounts/update_reg_id
     * @method: POST
     *header
     * @token  string has
     *
     * @reg_id
     *
     *@response  object
     * */
    function update_reg_id_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'reg_id', 'label'=>'lang:reg_id', 'rules'=>'required')
        );

        $this->form_validation->set_rules($rules);

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $message = API_VALIDATION;
            $validation = array(
                'reg_id' => $this->form_validation->error('reg_id')
            );
        } else {
            $this->checkToken();
            $account = $this->account_info;
            $input = $this->input->post();
            $result = $this->account->storeRegId($account['id'],$input['reg_id']);
            if($result){
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
}
