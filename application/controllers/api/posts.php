
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

    function create_post()
    {
        $status = 'success';
        $message = '';
        $results = null;
        $validation = null;

        /*Set the form validation rules*/
        $this->form_validation->set_rules('content', 'Content', 'required|max_length[255]');

        /*Check if the form passed its validation */
        if ($this->form_validation->run() == TRUE) {
            $status = 'failure';
            $message = 'error';
            $validation = array(
                'content' => $this->form_validation->error('content'),
            );
        } else {
            $type_id = $this->input->get('type_id');
            $notice = $this->input->get('content');
            $location_lat = $this->input->get('location_lat');
            $location_lng = $this->input->get('location_lng');
            $headers = $this->input->request_headers();
            if (!empty($headers['Token'])) {
                $result = $this->post->getIdByToken($headers['Token']);
                if ($result != NULL) {
                    $create_by = $result[0]['id'];
                    //var_dump($result[0]['id']); die();
                    $values = array(
                        'type_id' => (int)$type_id,
                        'content' => $notice,
                        'created_at' => getCurrentDate(),
                        'updated_at' => getCurrentDate(),
                        'location_lat' => $location_lat,
                        'location_lng' => $location_lng,
                        'created_by' => (int)$create_by
                    );
                    if ($this->post->createPost($values)) {
                        $message = 'insert successfully!';
                    } else {
                        $status = 'failure';
                        $message = 'insert error';
                    }
                } else {
                    $status = 'failure';
                    $message = 'error';
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
}
