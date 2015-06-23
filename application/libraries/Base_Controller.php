<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Base_Controller extends REST_Controller
{
	// Output formats
    private $output_formats = array(
		'json' 	=> 'application/json'
    );

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Ho_Chi_Minh');
	}

    protected function _format_data_response($code = null, $message = null, $data = null){
        /*foreach($data as $k=>$v)
        {
            switch($k)
            {
                case 'code':
                    $output_data['code'] = $v;
                    break;

                case 'message':
                    $output_data['message'] = $v;
                    break;

                case 'results':
                    $output_data['results'] = $v;
                    break;

                case 'errors':
                    $output_data['errors'] = $v;
                    break;

                default:
                    $output_data[$k] = $v;
            }
        }

        if(!isset($output_data['errors']))
            $output_data['errors'] = true;*/
        $output_data['code'] = $code;
        $output_data['message'] = $message;
        $output_data['data'] = $data;

        return $output_data;
    }

    protected function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

	private function _get_limit(){
		$limit = $this->input->get(LIMIT) ? $this->input->get('limit') : DEFAULT_LIMIT;
		if($limit > MAX_LIMIT) $limit = DEFAULT_LIMIT;

		return $limit;
	}


	private function _get_page()
    {
		$page = $this->input->get(PAGE) ? $this->input->get('page') : DEFAULT_PAGE;
		if($page <= 0) $page = DEFAULT_PAGE;

		return $page;
	}

	private function _get_simple_pagination($page, $limit)
    {
		// Check for valid and limit
        $page   = $page < 1 ? 1 : $page;
        $limit  = $limit < 1 ? 1 : $limit;
        $offset = (($page * $limit) - $limit);

		return array(LIMIT => $limit, OFFSET => $offset < 1 ? 0 : $offset, PAGE => $page);
	}

	/**
	 * @return array with keys: limit, offset, page
	 */
	protected function get_pagination()
    {
        $page       = $this->_get_page();
        $limit      = $this->_get_limit();
        $pagination = $this->_get_simple_pagination($page, $limit);

		return $pagination;
	}

    // check the image file type and dimension
    /**
    *
    * @param $var
    */
    protected function check_image($var)
    {
        $image_info     = getimagesize($_FILES[$var]["tmp_name"]);

        $image_width    = $image_info[0];
        $image_height   = $image_info[1];
        $image_type     = $image_info['mime'];

        if($image_type != 'image/jpeg' && $image_type != 'image/png' && $image_type != 'image/jpg')
        {
        	header("Content-Type: application/json");
	        header("Cache-Control: no-store");
	        header("HTTP/1.1 ". SSC_HEADER_PARAMETER_MISSING_INVALID);

            echo json_encode(array(STATUS_CODE=>SSC_HEADER_PARAMETER_MISSING_INVALID, MESSAGE=>IMAGE_TYPE_ERROR));
            die();
        }

        // if($image_width <= 1500 && $image_height <= 960)
        // {
            // echo json_encode(array('status_code'=>SNB_PARAMETER_MISSING_INVALID, 'message'=>IMAGE_DIMENSION_ERROR));
            // die();
        // }

        if(filesize($_FILES[$var]["tmp_name"]) > 3145728)
        {
        	header("Content-Type: application/json");
	        header("Cache-Control: no-store");
	        header("HTTP/1.1 ". SSC_HEADER_PARAMETER_MISSING_INVALID);

            echo json_encode(array(STATUS_CODE=>SSC_HEADER_PARAMETER_MISSING_INVALID, MESSAGE=>IMAGE_SIZE_ERROR));
            die();
        }

        return true;
    }

    // check email validation
    protected function validate_email($e) {
        return (bool)preg_match("`^[a-z0-9!#$%&'*+\/=?^_\`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_\`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$`i", trim($e));
    }

    // check ph number
    protected function validate_phone($phone) {
        if (strlen($phone) == 8 && is_numeric($phone)) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * Check password
     */
    public function validate_password($password)
    {
       if(strlen($password)<8)
        {
            return false;
        }
        else
        {
            return true;
        }

    }

    // check gender
    protected function valid_gender($g) {
        $gender = strtolower($g);

        if ($gender != 'male' && $gender != 'm' && $gender != 'female' && $gender != 'f') {
            return false;
        } else {
            return $gender;
        }
    }

     /**
     * Check dob
     */
    public function is_date($dob)
    {
        if ( preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $dob) ) {
                    list($year , $month , $day) = explode('-',$dob);
                    $flag=checkdate($month , $day , $year);

               return $flag;
            }
            else
            {
               return FALSE;
            }
    }

    public function is_datetime($dob)
    {
        if ( preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (\d{2}):(\d{2}):(\d{2})$/', $dob) ) {
            list($date, $time) =explode(' ',$dob);
            list($year , $month , $day) = explode('-',$date);
            $flag=checkdate($month , $day , $year);
            if($flag)
            {
                $datetime = date_create_from_format('Y-m-d H:i:s', $dob);
                if(!$datetime)
                    return false;
            }
            return $flag;
        }
        else
        {
            return FALSE;
        }
    }

    public function is_time($dob, $is_minute = true)
    {
        if($is_minute){
            if ( preg_match('/^(\d{2}):(\d{2}):(\d{2})$/', $dob) ) {

                $datetime = date_create_from_format('H:i:s', $dob);
                if(!$datetime)
                    return FALSE;
                return TRUE;
            }
        }
        else
        {
            if ( preg_match('/^(\d{2}):(\d{2})$/', $dob) ) {

                $datetime = date_create_from_format('H:i', $dob);
                if(!$datetime)
                    return FALSE;
                return TRUE;
            }
        }

    }

    // check dob for activeSG
    public function valid_dob_activeSG($dob) {

       $_age = floor( (strtotime(date('Y-m-d')) - strtotime($dob)) / 31556926);

        if ($_age>15 && $_age<121 ) {
            return True;
        } else {
            return False;
        }
    }

    // check dob for supplementary
    public function valid_dob_supplementary($dob) {

        $_age = floor( (strtotime(date('Y-m-d')) - strtotime($dob)) / 31556926);

        if ($_age>0 && $_age<16 ) {
            return True;
        } else {
            return False;
        }
    }

    // check nric
    public function valid_nric($nric) {

            $nric = strtoupper($nric);

            if ( preg_match('/^[ST][0-9]{7}[JZIHGFEDCBA]$/', $nric) ) 
             { // NRIC
                $check = "JZIHGFEDCBA";
             }
             else if ( preg_match('/^[FG][0-9]{7}[XWUTRQPNMLK]$/', $nric) ) 
             { // FIN
               $check = "XWUTRQPNMLK";
             } 
             else 
             {
                return false;
             }

              $total = $nric[1]*2
                + $nric[2]*7
                + $nric[3]*6
                + $nric[4]*5
                + $nric[5]*4
                + $nric[6]*3
                + $nric[7]*2;

              if ( $nric[0] == "T" OR $nric[0] == "G" ) 
              {
                // shift 4 places for after year 2000
                $total = $total + 4; 
              }

              if ( $nric[8] == $check[$total % 11] )
              {
                return TRUE;
              } else {
                return FALSE;
              }
    }


    protected function is_required($param = NULL, $rules = NULL, $checkIFNotNull = FALSE, $chekZero = TRUE)
    {
        if($param === NULL)
        {
            $this->invalid_params($rules);
        }

        foreach ($rules as $values)
        {
            $value = $values[0];

            if(!isset($param[$value]))
            {
                if(!$checkIFNotNull)//check if exist param
                    $this->invalid_params($value);
            }
            else
            {
                $val = trim($param[$value]);

                $type = isset($values[1])?$values[1]: null;
                $length = isset($values[2])?$values[2]: null;

                if(null != $type)
                {
                    switch($type){
                        case TYPE_PARAM_EMAIL:{
                            if(!$this->validate_email($param[$value]))
                                $this->invalid_params($value);
                            break;
                        }

                        case TYPE_PARAM_NUMBER:{
                            if(!is_numeric($param[$value]))
                                $this->invalid_params($value);
                            break;
                        }

                        case TYPE_PARAM_DATETIME:{
                            if(!$this->is_datetime($param[$value]))
                                $this->invalid_params($value);
                            break;
                        }

                        case TYPE_PARAM_DATE:{
                            if(!$this->is_date($param[$value]))
                                $this->invalid_params($value);
                            break;
                        }

                        case TYPE_PARAM_TIME:{
                            if(!$this->is_time($param[$value]))
                                $this->invalid_params($value);
                            break;
                        }



                        case TYPE_PARAM_TEXT:{
                            if(null!=$length){
                                if(strlen($val) > $length)
                                    $this->invalid_params($value .' Max length = '.$length);

                            }else{
                                if(is_null($val))
                                {
                                    $this->invalid_params($value);
                                }
                            }
                            break;
                        }
                        default:{
                            if(is_null($val))
                            {
                                $this->invalid_params($value);
                            }
                            break;
                        }

                    }


                }else{



                    if(is_null($val))
                    {
                        $this->invalid_params($value);
                    }

                    if($chekZero){
                        if(empty($val)){
                            $this->invalid_params($value);
                        }
                    }
                }



            }
        }
    }

    protected function invalid_params($paramName = NULL)
    {
    	header("Content-Type: application/json");
        header("Cache-Control: no-store");
        header("HTTP/1.1 ". HEADER_NOT_FOUND);
        // Output json and die
        echo json_encode($this->_format_data_response( HEADER_NOT_FOUND,
            $paramName ? "Invalid or missing parameters: $paramName" : "Invalid or missing parameters."));
        die;
    }

	protected function result_not_found($paramName = "Result"){
		header("Content-Type: application/json");
        header("Cache-Control: no-store");
        header("HTTP/1.1 ". HEADER_NOT_FOUND);

        // Output json and die
        echo json_encode(array('status_code'=>HEADER_NOT_FOUND, 'message' => "$paramName not found"));
        die;

	}

    protected function set_elasticache($key, $value)
    {
        $server_endpoint = ELASTICACHE_URL;
        $server_port     = ELASTICACHE_PORT;

        if (version_compare(PHP_VERSION, '5.4.0') < 0)
        {
            //PHP 5.3 with php-pecl-memcache
            $client = new Memcache;
            $client->connect($server_endpoint, $server_port);

            //If you need debug see $client->getExtendedStats();
            return $client->set($key, $value, ELASTICACHE_EXPIRY);
        }
        else
        {
            //PHP 5.4 with php54-pecl-memcached:
            $client = new Memcached;
            $client->addServer($server_endpoint, $server_port);

            //If you need debug see $client->getExtendedStats();
            return $client->set($key, $value, ELASTICACHE_EXPIRY);
        }
    }

    protected function get_elasticache($key)
    {
        $server_endpoint = ELASTICACHE_URL;
        $server_port     = ELASTICACHE_PORT;

        $client = new Memcache;
        $client->connect($server_endpoint, $server_port);

        // Returns FALSE on failure
        return $client->get($key);
    }

    protected function delete_elasticache($key)
    {
        $server_endpoint = ELASTICACHE_URL;
        $server_port     = ELASTICACHE_PORT;

        $client = new Memcache;
        $client->connect($server_endpoint, $server_port);

        // Returns TRUE on success or FALSE on failure.
        return $client->delete($key);
    }


    function dump($value)
    {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }


    /**
     * Require a client
     */
    public function require_client()
    {

        $this->load->model('account/account_model');
        if(!$this->account_model->find_client_by_secret(@$this->params('client_secret')))
        {
            header("Content-Type: application/json");
            header("Cache-Control: no-store");
            header("HTTP/1.1 ". SSC_HEADER_PARAMETER_MISSING_INVALID);
            
            // Output json and die                                  
            echo json_encode(array('status_code'=>SSC_HEADER_PARAMETER_MISSING_INVALID,'message' => 'Invalid oauth client credentials.'));  
            die;  
        }
    }

    /**
     * Require a client and an account
     */
     public function require_account(){
         
        $this->require_client();
        $this->load->model('account/account_model');
        if(!$this->account_model->check_accesstoken(@$this->params('access_token')))
        {
            header("Content-Type: application/json");
            header("Cache-Control: no-store");
            header("HTTP/1.1 ". SSC_HEADER_PARAMETER_MISSING_INVALID);
            
            // Output json and die                                  
            echo json_encode(array('status_code'=>SSC_HEADER_PARAMETER_MISSING_INVALID,'message' => 'Invalid oauth token credentials.'));  
            die;  
        }
    }

    /**
     * @return Array with profile_id and access_token: the new access token
     */
    protected function get_profile_id_and_token(){
        $accessToken = $this -> input -> get_request_header(X_AUTHORIZATION);
        $this -> load -> model('account/account_model');
        
        $data = $this -> account_model -> get_profileid_by_accesstoken($accessToken);
        $access_token   =   $this->account_model->generate_token();
        
        if($data){
            $editData=array();
            $editData['access_token']=$access_token;
            /**
             * This should be called from model, not controller since it's depending on whether this API will need to rotate access token
             */
            // $this->account_model->common_edit('access_token','account_id',$data->id,$editData);
            $result=array();
            $result['profile_id']=$data->profile_id;
            $result['access_token']=$accessToken;
    
            return $result;
        }else{
            header("Content-Type: application/json");
            header("Cache-Control: no-store");
            header("HTTP/1.1 " . SSC_HEADER_FORBIDDEN);

            // Output json and die
            echo json_encode(array('status_code' => SSC_HEADER_FORBIDDEN, 'message' => 'Invalid oauth token credentials.'));
            die ;
        }
    }
    
    /**
     * Get the profile id value
     */
    protected function get_profile_id(){
        $return = $this->get_profile_id_and_token();
        return $return['profile_id'];
    }

    /**
     * Get account id of the user currently accessing the API
     */
    public function get_account_id() {
        echo 'a';
    }

    /**
     * Get error message from database
     */
    public function get_message($code) {

        $this -> load -> model('common/common_config_model');
        $data=$this -> common_config_model -> get_message($code);

        return $data;
    }
    
    /**
     * Check whether the value == 'Y'
     * @return TRUE/FALSE
     */
    public function is_yes($value){
        if($value == YES_FLAG){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
     /**
     * Convert a comma separated string into an array
     * @param String $string
     * @return Array
     */
    protected function _convert_to_array($string){
        $array = explode(',', $string);
        foreach($array as $key => $value) {
            if (empty($array[$key])) {
                unset($array[$key]);
            }
        }
        $array = array_values($array);
        
        return $array;
    }


}
