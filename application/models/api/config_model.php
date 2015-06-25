<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('file_model');
    }

    function createTypePost($data){
    	$temp = array(
    		'created_at' => getCurrentDate()
    	);

    	$record = array_merge($data,$temp);

    	$isInsert = $this->db->insert('type_posts',$record);
		
		if($isInsert){
			return true;
		}
		return false;
    }
    
    function deleteTypePostById($id){
    	$type_posts = $this->getTypePostById($id);
    	//delete file avatar
    	$isDeleteFile = $this->file_model->deleteFileById($type_posts['avatar']);

    	if($isDeleteFile){
	    	//delete type post
	    	$isDeleteType = $this->db->delete('type_posts',array('id' => $id));
	    	if($isDeleteType){
	    		return true;
	    	}
	    }
	    return false;
    }

    function getTypePostById($id){
    	$this->db->from('type_posts');
        $this->db->where('id',$id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0];
    }

}