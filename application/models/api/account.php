<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //logout
    function logout_account($access_token)
    {
        $query = $this->db->get_where('accounts',array('access_token' => $access_token));      
        if($query->num_rows()==1){
            $this->db->where('access_token', $access_token);
            if($this->db->update('accounts', array('access_token' => NULL)))
                return true;
        }
        return false;
    }

    
    function createAccount($data){
    	$temp = array(
    		'created_at' => getCurrentDate()
    	);

    	$recordData = array_merge($data,$temp);

    	$isInsert = $this->db->insert('accounts',$recordData);
		
		if($isInsert){
			return true;
		}
		return false;
    }

    function checkAccount($input){
        $query = $this->db->get_where('accounts',array(
            'username' => trim($input['username']),
            'password' => trim($input['password'])
        ));

        if($query->num_rows()==1){
            $access_token = md5(uniqid().time().md5(trim($input['username'])));
            $this->db->where(array(
                'username' => trim($input['username']),
                'password' => trim($input['password'])
            ));
            $isUpdate = $this->db->update('accounts',array('access_token' => $access_token));
            if($isUpdate){
                return array('access_token' => $access_token);
            }
        }
        return false;
    }
}