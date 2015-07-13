<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/account');
    }

    function getListBloodGroup(){
    	$this->db->select(array('id','name'));
    	$this->db->from('blood_groups');
	    $query = $this->db->get();
	    $result = $query->result_array();
	    return $result;
    }

    function getListBloodGroupRh(){
    	$this->db->select(array('id','name'));
    	$this->db->from('blood_group_rh');
	    $query = $this->db->get();
	    $result = $query->result_array();
	    return $result;
    }
    
    /*function checkTokenAccess(){
        $headers = $this->input->request_headers();
        if(!empty($headers['Token'])){
            $query = $this->db->get_where('accounts',array('access_token' => $headers['Token']));
            if($query->num_rows() == 1){
                return true;
            }
        }else{
            return  false;
        }
    }*/

    /*
  | -------------------------------------------------------------------------
  | send mail 
  | -------------------------------------------------------------------------*/
    function sendMail($mailData)
    {
        /*
            $mailData = array(
                'mail_to' => 'example@gmail.com',
                'subject' => 'ABC',
                'content' => 'Content'
            );
        */
        try{
            //send mail
            $this->load->library('email');
            $this->config->load('email');

            $from_mail = $this->config->item('smtp_user');
            $pass = $this->config->item('smtp_pass');
            $full_name = $this->config->item('full_name');
            $protocol = $this->config->item('protocol');
            $host = $this->config->item('smtp_host');
            $port = $this->config->item('smtp_port');
            $mail_type = $this->config->item('mailtype');
            $newline = $this->config->item('newline');

            //set content
            $to_mail = $mailData['mail_to'];
            $subject = $mailData['subject'];
            $message = $mailData['content'];

            $config = array(
                'protocol' => $protocol,
                'host' => $host,
                'smtp_port' => $port,
                'smtp_user' => $from_mail,
                'smtp_pass' => $pass,
                'mail_type' => $mail_type
            );

            $this->email->set_newline($newline);
            $this->email->from($from_mail, $full_name);
            $this->email->to($to_mail);
            $this->email->subject($subject);
            $this->email->message($message);

            // send mail
            $send = $this->email->send();

            if ($send) {
                return true;
            }

            return false;
        }catch (Exception $e) {
            return false;
        }
    }

    function checkAccessToken(){
        $results = array(
            'status' => false,
            'res' => array(
                'status' => 'failure',
                'message' => $this->lang->line('access_token_is_not_exist'),
                'results' => null,
                'validation' => null
            )
        );

        $headers = $this->input->request_headers();
        if(!empty($headers['Token'])){
            $query = $this->db->get_where('tokens',array('access_token' => $headers['Token']));
            if($query->num_rows() == 1){
                $results['status'] = true;
                $results['res']['status'] = 'success';
                $results['res']['message'] = '';

                $temp = $query->result_array();
                $results['account'] = $this->account->getAccountById($temp[0]['account_id']);
            }
        }

        return $results;
    }

    function getLocationNameByLatLng($lat,$lng){
        try {
            $server_key = DEFIND_GOOGLE_API_KEY_SERVER;
            $json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&location_type=ROOFTOP&result_type=street_address&key=$server_key");
            $obj = json_decode($json);
            if(!empty($obj->results)){
                $result = $obj->results;
                if(count($result)>0) {
                    return $result[0]->formatted_address;
                }
            }
        }
        catch(Exception $e){
            return "";
        }
        return "";
    }
}