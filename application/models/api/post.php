<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function createPost($data)
    {
        $temp = array(
            'created_at' => getCurrentDate()
        );

        $recordData = array_merge($data,$temp);

        $isInsert = $this->db->insert('posts', $recordData);
        if ($isInsert)
            return TRUE;
        else
            return FALSE;
    }

}