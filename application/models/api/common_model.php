<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model {

    function __construct()
    {
        parent::__construct();

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
    
    function checkTokenAccess(){
        $headers = $this->input->request_headers();
        if(!empty($headers['Token'])){
            $query = $this->db->get_where('accounts',array('access_token' => $headers['Token']));
            if($query->num_rows() == 1){
                return true;
            }
        }else{
            return  false;    
        }
    }

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
        
        //send mail
        $this->load->library('email');
        $this->config->load('email');
        
        $from_mail   = $this->config->item('smtp_user');
        $pass        = $this->config->item('smtp_pass');
        $full_name   = $this->config->item('full_name');
        $protocol    = $this->config->item('protocol');
        $host        = $this->config->item('smtp_host');
        $port        = $this->config->item('smtp_port');
        $mail_type   = $this->config->item('mailtype');
        $newline   = $this->config->item('newline');
       
       //set content
        $to_mail     = $mailData['mail_to'];
        $subject     = $mailData['subject'];
        $message     = $mailData['content'];
       
        $config = array(
            'protocol'  => $protocol,
            'host' => $host,
            'smtp_port' => $port,
            'smtp_user' => $from_mail,
            'smtp_pass' => $pass,
            'mail_type'  => $mail_type
        );
        
        $this->email->set_newline($newline);
        $this->email->from($from_mail, $full_name);
        $this->email->to($to_mail);
        $this->email->subject($subject);
        $this->email->message($message);
        
        // send mail
        $send = $this->email->send();
        
        if($send){
            return true;
        }

        return false;
    }

}