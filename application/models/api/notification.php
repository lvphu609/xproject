<?php
/**
 * Created by PhpStorm.
 * User: Phu Le
 * Date: 6/29/2015
 * Time: 10:47 AM
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('api/post','api/account'));
    }


    //get my notification
    function getMyNotifications($account_id,$page = null,$numberPerPage = null){
        $this->load->model('file_model');
        $accountInfo = $this->account->getAccountById($account_id);

        $this->db->select('
            n.*
        ');
        $this->db->from('notifications as n');

        $this->db->join('posts as po', 'po.id = n.record_id');

        $this->db->join('type_posts as pot', 'pot.id = po.type_id', 'left');

        $this->db->where('n.is_delete', NULL);

        if ($page !== null)
        {
            $begin = ($page - 1)*$numberPerPage;
            $this->db->limit($numberPerPage, $begin);
        }
        $query = $this->db->get();

        if($query->num_rows() > 0 ){
            $result = $query->result_array();
            if(count($result)>0){
                $arrTemp = array();
                foreach($result as $key => $type){
                    if(!empty($type['type_id'])) {
                        $type['post_type'] = $this->post->getTypePostById($type['type_id']);
                    }else{
                        $type['post_type'] = null;
                    }
                    array_push($arrTemp,$type);
                }
                return $arrTemp;
            }
            return $result;
        }
    }

    //count all notification
    function countAllMyNotifications($account_id){
        $this->db->where('recipient_id', $account_id);
        $this->db->where('is_delete', null);
        $this->db->from('notifications');
        return $this->db->count_all_results();
    }


    function save_notification($sender_id, $recipient_id, $record_id, $type_of_notification, $action){
        $record_notify = array(
            'sender_id' => $sender_id,
            'recipient_id' => $recipient_id,
            'record_id' => $record_id,
            'type_of_notification' => $type_of_notification,
            'action' => $action,
            'created_at' => getCurrentDate()
        );
        $isInsert = $this->db->insert('notifications',$record_notify);
        if($isInsert){
            return $this->db->insert_id();
        }
        return null;
    }

    function get_message_notification($account_id, $post_detail){
        $post_type = $post_detail->post_type;
        $account = $this->account->getAccountInfoById($account_id);
        if($account){
            $data = new stdClass();
            $data->title = my_lang('notify_create_post',array($account['full_name'], $post_type['name']));
            $data->created_at = $post_detail->created_at;
            $data->avatar = $post_type['avatar'];
            return $data;
        }
        return null;
    }

}