<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class Notifications extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('api/notification','api/common_model'));
        $this->load->helper(array('form', 'url'));

        /*validation--------------*/
        $this->load->library('form_validation');
        $this->lang->load('api_common','vn');
        //custom html of message validation
        $this->form_validation->set_error_delimiters('', '');

        //check token-----------------------------------------
        $checkToken = $this->common_model->checkAccessToken();
        if(!$checkToken['status']){
            $this->response($checkToken['res'], HEADER_SUCCESS);
        }else{
            $this->account_info = $checkToken['account'];
        }
    }

    /**
     * get list notifications
     * @method: Post
     * @link access: http://domain/xproject/api/notifications/get_my_notifications
     *
     * parram
     * @token   string has
     *
     * @account_id int
     * @page   int
     *
     * @row_per_page  allow null
     *
     * @return array
     */
    function get_my_notifications_post(){
        $status = API_FAILURE;
        $message = API_ERROR;
        $results = null;
        $validation = null;
        $row_per_page = DEFIND_PER_PAGE_DEFAULT;

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
            if($this->input->post('row_per_page')){
                $row_per_page = $this->input->post('row_per_page');
            }

            $listNotification = $this->notification->getMyNotifications($this->input->post('account_id'),$this->input->post('page'),$row_per_page);
            $message = '';
            $status = API_SUCCESS;
            $results = $listNotification;
        }

        $data = array(
            API_STATUS => $status,
            API_MESSAGE => $message,
            API_RESULTS => $results,
            API_VALIDATION => $validation,
            API_PAGINATION => array(
                API_PAGE => $this->input->post('page'),
                API_ROW_PER_PAGE => count($results),
                API_TOTAL_PAGE => ceil($this->notification->countAllMyNotifications($this->input->post('account_id'))/$row_per_page)
            )
        );
        $this->response($data, HEADER_SUCCESS);
    }


}
