<?php
/**
 * Created by PhpStorm.
 * User: Phu Le
 * Date: 6/29/2015
 * Time: 10:47 AM
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notify extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('api/post','api/account'));
    }

    /**
     * send notify to GCM
     * */
    function sendPushNotificationToGCM($registrationIds = array(), $message) {
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array
        (
            'registration_ids' 	=> $registrationIds,
            'data'			=> $message
        );

        $headers = array
        (
            'Authorization: key=' . DEFIND_GOOGLE_API_KEY_SERVER,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $url );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        $result = json_decode($result);
        return $result->success == 1;
    }

    /**
     * get reg_id by account_id
     * */
        function getRegId($account_id){
        $this->db->where('id',$account_id);
        $query = $this->db->get('accounts');
        $result = $query->result_array();
        return $result[0]['reg_id'];
    }

    /**
     * send notify for all province -> 10Km
     * */

    function send_notify_provinces($record){
        if(($key = array_search('', $record)) !== false) {
            unset($record[$key]);
        }
        $post_id = $this->post->getPostIdForPushNotify($record);
        $status = 'success';
        $message = 'insert post successfully!';
        $results = $this->post->getPostDetailById($post_id);
        $validation = '';
        //create object to send to gcm
        $message_to_send = new stdClass;
        $message_to_send->status = $status;
        $message_to_send->message = $message;
        $message_to_send->results = $results;
        $message_to_send->validation = $validation;
        //var_dump($message_to_send); die();
        $account_array = $this->account->getAccountIdByLocation($message_to_send->results, 10);
        //var_dump($account_array);die();
        $regId_array = array();
        if (count($account_array) > 0) {
            for ($i = 0; $i < count($account_array); $i++) {
                array_push($regId_array, $this->getRegId($account_array[$i]['id']));
            }
            //var_dump($regId_array); die();
                $is_send = $this->sendPushNotificationToGCM($regId_array, $message_to_send);
                if($is_send){
                    $status = 'success';
                    $message = 'insert post successfully!';
                }
        }
    }

    /**
     * send notify for user created post when province picked your post
     * */

    function send_notify_account($post_id_array){
        $status = 'success';
        $message = 'insert post successfully!';
        $results = null;
        $validation = '';
        for($i=0;$i<count($post_id_array);$i++) {
            $postInfo = $this->post->getPostDetailById($post_id_array[$i]);
            //var_dump($pickerInfo['full_name']); die();
            $message_to_send = new stdClass;
            $message_to_send->status = $status;
            $message_to_send->message = $message;
            $message_to_send->results = $postInfo;
            $message_to_send->validation = $validation;
            //var_dump($message_to_send);die();
            $regId_array = $this->getRegId($postInfo->created_by);
            //var_dump($regId_array);die();
            $this->sendPushNotificationToGCM(array($regId_array), $message_to_send);
        }
    }


}