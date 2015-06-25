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

    function saveEmergency($account, $input){
        $record = array(
            'location_lat' => $input['location_lat'],
            'location_lng' => $input['location_lng'],
            'created_by' => $account['id'],
            'created_at' => getCurrentDate(),
            'is_emergency' => 1
        );

        $isInsert = $this->db->insert('posts', $record);
        if ($isInsert)
            return TRUE;
        else
            return FALSE;
    }

}