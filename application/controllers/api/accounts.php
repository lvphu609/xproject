
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class accounts extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/account');
        $this->load->model('file_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->lang->load('api_account','vn');
    }


    /* 
        username
        password
        confirm_password
        email
        full_name
        date_of_birth
        gender
        identity_card_id
        phone_number
        blood_group_id
        blood_group_rh_id
        avatar
        address
        contact_name
        contact_phone
    */

    function create_post(){
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*custom html of message validation*/
        $this->form_validation->set_error_delimiters('', '');

        /*custom lang of the form validation*/
        $set_message = array(
            'required'=>$this->lang->line('is_required'),
            'valid_email'=>$this->lang->line('is_valid_email'),
            'matches'=>$this->lang->line('matches_field')
        );
        $this->form_validation->set_message($set_message);
        
        /*Set the form validation rules*/
        $rules = array(
            array('field'=>'username', 'label'=>'lang:username', 'rules'=>'required'),
            array('field'=>'password', 'label'=>'lang:password', 'rules'=>'required'),
            array('field'=>'confirm_password', 'label'=>'lang:confirm_password', 'rules'=>'required|matches[password]'),
            array('field'=>'email', 'label'=>'lang:email', 'rules'=>'required|valid_email'),
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
            $message = "error";
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
                'password' => md5($dataInput['password']),
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
        $status = 'success';
        $message = '';
        $results = null;

        /*Set the form validation rules*/
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = validation_errors();
        }
        else
        {
            $value = $this->input->post();
            $is_access_token=$this->account->check_account($value['email'],md5($value['password']));
            if($is_access_token['is_access_token'] != '')
            {
                $results = $is_access_token;
            }
            else
            {
                $status = 'failure';
                $message = 'email or password isn\'t correct';
            }
        }
        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
        );
        $this->response($data, HEADER_SUCCESS);
    }

    function logout_post()
    {
        $status = 'failure';
        $message = '';
        if(isset($_GET['is_access_token'])) {
            $value = $this->input->get('is_access_token');
            $status = $this->account->logout_account($value);
            if ($status == 'success')
                $message = 'You have been logout';
        }
        $data = array(
            'status' => $status,
            'message' => $message
        );
        $this->response($data, HEADER_SUCCESS);
    }
}
