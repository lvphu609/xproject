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
    /*
     * The post is important
     * */
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

    /*
     * Get post by location
     *
     * query search location http://www.plumislandmedia.net/mysql/haversine-mysql-nearest-loc/
     * */
    function getPostByLocation($location, $RADIUS = 10.0){
        $LAT_HERE = $location['location_lat'];
        $LONG_HERE = $location['location_lng'];

        $query = $this->db->query("
            SELECT *,
                p.distance_unit
                         * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                         * COS(RADIANS(z.location_lat))
                         * COS(RADIANS(p.longpoint) - RADIANS(z.location_lng))
                         + SIN(RADIANS(p.latpoint))
                         * SIN(RADIANS(z.location_lat)))) AS distance_in_km
              FROM posts AS z
              JOIN (
                SELECT  $LAT_HERE  AS latpoint,  $LONG_HERE AS longpoint,
                $RADIUS  AS radius,      111.045 AS distance_unit
                ) AS p ON 1=1
              WHERE z.location_lat
              BETWEEN p.latpoint  - (p.radius / p.distance_unit)
              AND p.latpoint  + (p.radius / p.distance_unit)
              AND z.location_lng
              BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
              AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
              ORDER BY distance_in_km
        ");
        $result = $query->result_array();
        return $result;
    }

    /*
    * Get post by account_id
    *
    * */

    function getMyPosts($account_id)
    {
        $query = $this->db->get_where('posts',array('account_id' => $account_id));
        return $query->result_array();
    }

}