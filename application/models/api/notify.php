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
        $this->load->model(array('api/post','api/account','api/notification',));
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

    function send_notify_provinces($post_id){
        //create object to send to gcm
        $message_to_send = new stdClass();
        $message_to_send->data = new stdClass();
        $message_to_send->data->results = new stdClass();

        $message_to_send->data->status = API_SUCCESS;
        $message_to_send->data->message = '';
        $message_to_send->data->results = $this->post->getPostDetailById($post_id);
        $message_to_send->data->validation = null;

        $account_array = $this->account->getAccountIdByLocation($message_to_send->data->results, 10);
        $regId_array = array();
        if (count($account_array) > 0) {
            for ($i = 0; $i < count($account_array); $i++) {
                array_push($regId_array, $this->getRegId($account_array[$i]['id']));
                $this->notification->save_notification(
                    $message_to_send->data->results->created_by,
                    $account_array[$i]['id'],
                    $post_id,
                    1,  //type of notification 1 is posts
                    1  //acction create post
                );
            }
            $message_to_send->data->results->notify = $this->notification->get_message_notification($message_to_send->data->results,1,1);

            $this->sendPushNotificationToGCM($regId_array, $message_to_send);
        }
    }

    /**
     * send notify for user created post when province picked your post
     * */

    function send_notify_account($arrPostId,$type,$action){
        for($i=0; $i<count($arrPostId); $i++) {
            $postInfo = $this->post->getPostDetailById($arrPostId[$i]);
            $message_to_send = new stdClass();
            $message_to_send->data = new stdClass();
            $message_to_send->data->results = new stdClass();

            $message_to_send->data->status = API_SUCCESS;
            $message_to_send->data->message = '';
            $message_to_send->data->results = $postInfo;
            $message_to_send->data->validation = null;

            $message_to_send->data->results->notify = $this->notification->get_message_notification($postInfo,$type,$action);

            $regId_array = $this->getRegId($postInfo->created_by);

            $this->notification->save_notification(
                $postInfo->picked_by,
                $postInfo->created_by,
                $arrPostId[$i],
                1,  //type of notification 1 is posts
                2  //acction create post
            );
            $this->sendPushNotificationToGCM(array($regId_array), $message_to_send);
        }
    }


}