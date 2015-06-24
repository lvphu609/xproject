
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class Accounts extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        // var_dump(phpinfo()); die();
        $this->load->model(array('api/account','file_model','api/common_model'));
        $this->load->helper(array('form', 'url'));

        /*validation--------------*/
        $this->load->library('form_validation');
        $this->lang->load('api_account','vn');
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

    /*
    * Create account
    *  
    */
    function create_post(){
        //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;
        
        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'username', 'label'=>'lang:username', 'rules'=>'required|min_length[5]|is_unique[accounts.username]'),
            array('field'=>'password', 'label'=>'lang:password', 'rules'=>'required'),
            array('field'=>'confirm_password', 'label'=>'lang:confirm_password', 'rules'=>'required|matches[password]'),
            array('field'=>'email', 'label'=>'lang:email', 'rules'=>'required|valid_email|is_unique[accounts.email]'),
            array('field'=>'full_name', 'label'=>'lang:full_name', 'rules'=>'required'),
            array('field'=>'date_of_birth', 'label'=>'lang:date_of_birth', 'rules'=>'required|callback_date_valid'),
            array('field'=>'gender', 'label'=>'lang:gender', 'rules'=>'required'),
            array('field'=>'identity_card_id', 'label'=>'lang:identity_card_id', 'rules'=>'required'),
            array('field'=>'phone_number', 'label'=>'lang:phone_number', 'rules'=>'required'),
            array('field'=>'blood_group_id', 'label'=>'lang:blood_group_id', 'rules'=>'required'),
            array('field'=>'blood_group_rh_id', 'label'=>'lang:blood_group_rh_id', 'rules'=>'required'),
            array('field'=>'avatar', 'label'=>'lang:avatar', 'rules'=>'required')
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
                'avatar' => $this->form_validation->error('avatar')
            );
        }
        //validate success
        else{
            //call model save account data
            $file_id = $this->file_model->do_upload('accounts',TRUE);
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
                'address' => $dataInput['address'],
                'contact_name' => $dataInput['contact_name'],
                'contact_phone' => $dataInput['contact_phone']
            ); 
            //save record account
            $isInsert = $this->account->createAccount($accountRecord);
            
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

    function login_post()
    {
        //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'username', 'label'=>'lang:username', 'rules'=>'trim|required|min_length[5]'),
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
                $results = $checkLogin['access_token'];
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

    function logout_post()
    {
        //initialize
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;
        
        $headers = $this->input->request_headers();

        if(!empty($headers['Token'])){
            $isLogout = $this->account->logout_account($headers['Token']);
            if($isLogout){
                $message = $this->lang->line('user_logout_success');
            }else{
                $status = 'failure';
                $message = $this->lang->line('user_logout_failure'); 
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
                if(!$this->account->checkExistRessetPasswordKey($email)){
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
                    }else{
                        
                    }
                }else{
                    $status = 'failure';
                    $message = $this->lang->line('send_mail_forgot_password_exist_key');
                }

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

    function reset_password_post()
    {
        /*$status = 'success';
        $message = '';
        $results = null;

        // Set the form validation rules
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('key_code', 'Key Code', 'trim|required');

        // Check if the form passed its validation 
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = validation_errors();
        } else {
            $value = $this->input->post();
            if(!$this->account->reset_password($value['key_code'],$value['password']))
            {
                $status = 'failure';
                $message = 'error';
            }
        }
        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
        );
        $this->response($data, HEADER_SUCCESS);*/

        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'code', 'label'=>'lang:key_code', 'rules'=>'required'),
            array('field'=>'password', 'label'=>'lang:password', 'rules'=>'required'),
            array('field'=>'confirm_password', 'label'=>'lang:confirm_password', 'rules'=>'required|matches[password]')
        );
        $this->form_validation->set_rules($rules);
       
        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = "error";
            $validation = array(
                'code' => $this->form_validation->error('code'),
                'password' => $this->form_validation->error('password'),
                'confirm_password' => $this->form_validation->error('confirm_password')
            );
        }
        //validate success
        else{
            //check exist forgot_password_code and email
            $updateToken = $this->account->updateTokenResetPass($this->input->post());

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

}
