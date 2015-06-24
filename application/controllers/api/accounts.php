
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class accounts extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/account');
        $this->load->model('file_model');
        /*Load the form validation helpers*/
        $this->load->helper(array('form', 'url'));
        /*Load the form validation helpers*/
        $this->load->library('form_validation');

    }

    function create_post(){
        $status = 'success';
        $message = '';
        $results = null;

        /*Set the form validation rules*/
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

         /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = validation_errors();
        }
        // $file_id = $this->file_model->do_upload('accounts');
        
        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => $results,
        );
        //=$this->_format_data_response(HEADER_SUCCESS," Create account success!");
        $this->response($data, HEADER_SUCCESS);
    }

    function test_post(){
        $data = $this->input->post();

        $this->response(array('data' => $data), HEADER_SUCCESS);
    }

    function login_post()
    {
        $status = 'success';
        $message = '';
        $results = null;

        /*Set the form validation rules*/
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == FALSE) {
            $status = 'failure';
            $message = validation_errors();
        }
        else
        {
            $value = $this->input->post();
            $is_access_token=$this->account->check_account($value['email'],md5($value['password']));
            if($is_access_token['is_access_token'] != null)
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

    function forgotPassword_post()
    {
        $this->load->library('email');
        $this->config->load('email');

        $status = 'failure';
        $message ='';

        if(isset($_GET['is_access_token'])) {
            $access_token = $this->input->get('is_access_token');
            $key_code = $this->account->reset_password_key($access_token);
            $value = $this->input->post();
            $mail_from = $this->config->item('mail_from');
            $mail_to = 'leB1204173@student.ctu.edu.vn';//$value['mail_to'];
            $mail_subject = 'test';//$value['mail_subject'];
            $mail_message = "hello my friend.\nKey Code: " . $key_code . ".\n Click here to reset your password: http://google.com.vn";//$value['mail_message'];

            $this->email->from($mail_from); // change it to yours
            $this->email->to($mail_to);// change it to yours
            $this->email->subject($mail_subject);
            $this->email->message($mail_message);

            if ($this->email->send()) {
                $status = 'success';
            } else {
                $message = 'error';
                //$message = $this->email->print_debugger();
            }
        }
        $data = array(
            'status' => $status,
            'message' => $message,
            'results' => null,
            'validation' => null,
        );


        $this->response($data, HEADER_SUCCESS);
    }

    function resetPassword_post()
    {
        $status = 'success';
        $message = '';
        $results = null;

        /*Set the form validation rules*/
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('key_code', 'Key Code', 'trim|required');

        /*Check if the form passed its validation */
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
        $this->response($data, HEADER_SUCCESS);
    }
}
