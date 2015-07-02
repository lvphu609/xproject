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
    }

    function sendPushNotificationToGCM($registrationIds = array(), $message) {
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
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        $result = json_decode($result);
        return $result->success == 1;
    }



}