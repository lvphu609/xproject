<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class account extends CI_Model {

    function __construct()
    {
        parent::__construct();

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
}