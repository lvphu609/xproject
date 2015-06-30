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

    function getMyPosts($account_id,$page = null,$numberPerPage = null)
    {
        $this->load->model('file_model');
        $this->db->select('
            po.*
        ');
        $this->db->from('posts as po');
        $this->db->join('type_posts as pot','pot.id = po.type_id', 'left');
        $this->db->where('po.created_by',$account_id);
        $this->db->order_by('po.is_emergency','DESC');
        $this->db->order_by('po.created_at','DESC');

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
                        $type['post_type'] = $this->getTypePostById($type['type_id']);
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

    function countAllPost($account_id){
        $this->db->where('created_by', $account_id);
        $this->db->from('posts');
        return $this->db->count_all_results();
    }

    function getTypePostById($id){
        $this->db->select('id, name, description, avatar');
        $this->db->from('type_posts');
        $this->db->where('id',$id);
        $query = $this->db->get();

        if($query->num_rows() > 0 ){
            $result = $query->result_array();
            if(count($result)>0){
                $arrTemp = array();
                foreach($result as $key => $type){
                    $type['avatar'] = $this->file_model->getLinkFileById($type['avatar'],'resized');
                    array_push($arrTemp,$type);
                }
                return $arrTemp[0];
            }

            return $result;
        }
        return array();
    }

    function getTypePostEmergency(){
        $this->db->select('id, name, description, avatar');

        return array(
            'id' => null,
            'name' => 'Emergency',
            'description' => 'Emergency',
            'avatar' => null
        );
    }

}