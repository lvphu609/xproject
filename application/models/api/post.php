<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function createPost($data)
    {
        $isInsert = $this->db->insert('posts', $data);
        if ($isInsert)
            return TRUE;
        else
            return FALSE;
    }

    function getIdByToken($access_token)
    {
        $this->db->where('access_token',$access_token);
        $query = $this->db->get('accounts');
        $result = $query->result_array();
        return $result;
    }

}