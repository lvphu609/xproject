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
        $this->load->model(array('api/post','api/account','file_model'));
    }


    //get my notification
    function getMyNotifications($account_id,$page = null,$numberPerPage = null){
        $accountInfo = $this->account->getAccountById($account_id);

        $this->db->select('
            n.*,
            po.type_id
        ');
        $this->db->from('notifications as n');

        $this->db->join('posts as po', 'po.id = n.record_id');

        $this->db->join('type_posts as pot', 'pot.id = po.type_id', 'left');

        $this->db->where('n.is_delete', NULL);

        $this->db->where('n.recipient_id', $account_id);

        $this->db->order_by('created_at','DESC');

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
                    //post type
                    if(!empty($type['type_id'])) {
                        $type['post_type'] = $this->post->getTypePostById($type['type_id']);
                    }else{
                        $type['post_type'] = null;
                    }
                    //notify
                    if(!empty($type['record_id']) && !empty($type['type_of_notification']) && !empty($type['action'])){
                        $type['notify'] = $this->get_message_notification($type['record_id'],$type['type_of_notification'],$type['action']);
                    }else{
                        $type['notify'] = null;
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

    function get_message_notification($post_id,$type,$action){
        $postInfo = $this->post->getPostDetailById($post_id);
        $post_type = $postInfo->post_type;
        $account = $this->account->getAccountInfoById($postInfo->created_by);
        if($account){
            $data = new stdClass();
            $data->title = my_lang('notify_'.get_notify_action(get_notify_type($type),$action),array($account['full_name'], $post_type['name']));
            $data->created_at = $postInfo->created_at;
            $data->avatar = $post_type['avatar'];
            $data->post_id = $post_id;
            return $data;
        }
        return null;
    }

    function view(){
        try {
            $input = $this->input->post();
            $isUpdate = $this->db->update('notifications',
                array('read_at' => getCurrentDate()),
                array(
                    'id' => $input['id'],
                    'recipient_id' => $input['account_id'])
            );

            if ($isUpdate) {
                return true;
            }
            return false;
        }catch (ErrorException $e){
            return false;
        }
    }

    function getNewestMyNotification(){
        $input = $this->input->post();
        $account_id = $input['account_id'];
        $date_time = $input['date_time'];
        try {
            $this->db->select('
                n.*,
                po.type_id
            ');
            $this->db->from('notifications as n');

            $this->db->where("(n.created_at > '$date_time')");

            $this->db->join('posts as po', 'po.id = n.record_id');

            $this->db->join('type_posts as pot', 'pot.id = po.type_id', 'left');

            $this->db->where('n.is_delete', NULL);

            $this->db->where('n.recipient_id', $account_id);

            $this->db->order_by('n.created_at','DESC');

            $query = $this->db->get();

            if($query->num_rows() > 0 ){
                $result = $query->result_array();
                if(count($result)>0){
                    $arrTemp = array();
                    foreach($result as $key => $type){
                        //post type
                        if(!empty($type['type_id'])) {
                            $type['post_type'] = $this->post->getTypePostById($type['type_id']);
                        }else{
                            $type['post_type'] = null;
                        }
                        //notify
                        if(!empty($type['record_id']) && !empty($type['type_of_notification']) && !empty($type['action'])){
                            $type['notify'] = $this->get_message_notification($type['record_id'],$type['type_of_notification'],$type['action']);
                        }else{
                            $type['notify'] = null;
                        }
                        array_push($arrTemp,$type);
                    }
                    return $arrTemp;
                }
                return $result;
            }
        }catch(ErrorException $e){
            return null;
        }
    }

}