<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/common_model');
    }

    function createPost($data, $post_id = null)
    {
        try {
            //create post
            if (empty($post_id)) {
                $temp = array(
                    'created_at' => getCurrentDate()
                );

                $recordData = array_merge($data, $temp);

                $isInsert = $this->db->insert('posts', $recordData);

                if ($isInsert) {
                    return true;
                }
            } //update posts
            else {
                $temp = array(
                    'updated_at' => getCurrentDate()
                );

                $recordData = array_merge($data, $temp);

                $isUpdate = $this->db->update('posts', $recordData, array('id' => $post_id,'created_by' => $recordData['created_by']));

                if ($isUpdate) {
                    return true;
                }
            }
        }catch (ErrorException $e){
            return false;
        }
        return false;

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
            'is_emergency' => 1,
            'location_name' => $this->common_model->getLocationNameByLatLng($input['location_lat'],$input['location_lng'])
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

    function getNewestMyPosts($input){
        $account_id = $input['account_id'];
        $created_at = $input['created_at'];

        $this->load->model('file_model');
        $this->db->select('
            po.*
        ');
        $this->db->from('posts as po');
        $this->db->join('type_posts as pot','pot.id = po.type_id', 'left');
        $this->db->where('po.created_by',$account_id);
        $this->db->where('po.created_at >',$created_at);
        $this->db->order_by('po.is_emergency','DESC');
        $this->db->order_by('po.created_at','DESC');

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

    function searchPost(){
        try {
            $input = $this->input->post();
            $LAT_HERE = $input['lat'];
            $LONG_HERE = $input['lng'];
            $RADIUS = 10.0;

            //paging
            $limit = "";

            $numberPerPage = DEFIND_PER_PAGE_DEFAULT;

            if ($this->input->post('row_per_page')) {
                $numberPerPage = $this->input->post('row_per_page');
            }

            if (!empty($input['page'])) {
                $begin = ($input['page'] - 1) * $numberPerPage;
                $limit = "LIMIT $numberPerPage OFFSET  $begin";
            }

            $query = $this->db->query("
                SELECT z.id, z.type_id, z.content, z.is_emergency, z.created_by, z.location_lat, z.location_lng, x.name,
                    p.distance_unit
                             * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                             * COS(RADIANS(z.location_lat))
                             * COS(RADIANS(p.longpoint) - RADIANS(z.location_lng))
                             + SIN(RADIANS(p.latpoint))
                             * SIN(RADIANS(z.location_lat)))) AS distance_in_km
                  FROM posts AS z
                  LEFT JOIN type_posts AS x ON x.id = z.type_id
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
                  AND x.name LIKE '%" . $input['query'] . "%'

                  ORDER BY distance_in_km, z.is_emergency, z.created_at DESC
                  " . $limit . "
                ");

            $result = $query->result_array();
            if (count($result) > 0) {
                $arrTemp = array();
                foreach ($result as $key => $type) {
                    if (!empty($type['type_id'])) {
                        $type['post_type'] = $this->getTypePostById($type['type_id']);
                    } else {
                        $type['post_type'] = null;
                    }
                    array_push($arrTemp, $type);
                }
                return $arrTemp;
            }
            return $result;
        }
        catch(Exception $e){
            return array();
        }
    }

    function postSearchTotalPage(){
        try {
            $input = $this->input->post();
            $LAT_HERE = $input['lat'];
            $LONG_HERE = $input['lng'];
            $RADIUS = 10.0;

            $row_per_page = DEFIND_PER_PAGE_DEFAULT;

            if ($this->input->post('row_per_page')) {
                $row_per_page = $this->input->post('row_per_page');
            }

            $query = $this->db->query("
                SELECT z.id, z.type_id, z.content, z.is_emergency, z.created_by, z.location_lat, z.location_lng, x.name,
                    p.distance_unit
                             * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                             * COS(RADIANS(z.location_lat))
                             * COS(RADIANS(p.longpoint) - RADIANS(z.location_lng))
                             + SIN(RADIANS(p.latpoint))
                             * SIN(RADIANS(z.location_lat)))) AS distance_in_km
                  FROM posts AS z
                  LEFT JOIN type_posts AS x ON x.id = z.type_id
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
                  AND x.name LIKE '%" . $input['query'] . "%'

                  ORDER BY distance_in_km, z.is_emergency, z.created_at DESC
                ");

            $result = $query->result_array();
            return ceil(count($result) / $row_per_page);
        }
        catch(ErrorException $e){
            return null;
        }
    }

}