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

}