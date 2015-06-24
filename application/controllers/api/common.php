
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class Common extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/common_model','common_model');
    }

    /**
     * get list blood group
     * @method: Get
     * @link access: http://localhost/xproject/index.php/api/common/fetch_list_blood_group/format/json
     *
     * @return array
     */
    function fetch_list_blood_group_get(){
    	$list = $this->common_model->getListBloodGroup();
    	$results = array(
    		'status' => 'success',
    		'message' => '',
    		'results' => $list
    	);
    	$this->response($results, HEADER_SUCCESS);
    }

	/**
     * get list blood group rh
     * @method: Get
     * @link access: http://localhost/xproject/index.php/api/common/fetch_list_blood_group_rh/format/json
     *
     * @return array
     */
    function fetch_list_blood_group_rh_get(){
    	$list = $this->common_model->getListBloodGroupRh();
    	$results = array(
    		'status' => 'success',
    		'message' => '',
    		'results' => $list
    	);
    	$this->response($results, HEADER_SUCCESS);
    }

    /**
     * get list gender
     * @method: Get
     * @link access: http://localhost/xproject/index.php/api/common/fetch_list_gender/format/json
     *
     * @return array
     */
    function fetch_list_gender_get(){
    	$results = array(
    		'status' => 'success',
    		'message' => '',
    		'results' => getGenderData()
    	);
    	$this->response($results, HEADER_SUCCESS);
    }
}