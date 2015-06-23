
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
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');

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
}
