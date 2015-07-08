<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/common_model');
        $this->load->model('api/account');
    }

    function createPost($data, $post_id = null)
    {
        try {
            //create post
            if (empty($post_id)) {
                $temp = array(
                    'created_at' => getCurrentDate(),
                    'status' => 0
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
            'type_id' => 1,
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
        $accountInfo = $this->account->getAccountById($account_id);

        $this->db->select('
            po.*
        ');
        $this->db->from('posts as po');
        $this->db->join('type_posts as pot', 'pot.id = po.type_id', 'left');

        //check account type
        if($accountInfo['account_type'] != 2){
            $this->db->where('po.created_by', $account_id);
        }else{
            $this->db->where('(po.created_by = '.$account_id.' OR po.picked_by = '.$account_id.')');
        }

        $this->db->where('po.is_delete', NULL);

        $status = $this->input->post('status');
        if(empty($status)){
            $this->db->where('po.status < 2');
        }else{
            $this->db->where('po.status', $status);
        }

        $this->db->order_by('po.created_at', 'DESC');

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
        try {
            $this->load->model('file_model');
            $accountInfo = $this->account->getAccountById($account_id);

            $this->db->select('
                po.*
            ');
            $this->db->from('posts as po');
            $this->db->join('type_posts as pot', 'pot.id = po.type_id', 'left');

            //check account type
            if($accountInfo['account_type'] != 2){
                $this->db->where('po.created_by', $account_id);
            }else{
                $this->db->where('(po.created_by = '.$account_id.' OR po.picked_by = '.$account_id.')');
            }

            $this->db->where('po.created_at >', $created_at);

            $status = $this->input->post('status');
            if(empty($status)){
                $this->db->where('po.status < 2');
            }else{
                $this->db->where('po.status', $status);
            }

            $this->db->where('po.is_delete', NULL);
            $this->db->order_by('po.is_emergency', 'DESC');
            $this->db->order_by('po.created_at', 'DESC');

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
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
        }catch(ErrorException $e){
            return null;
        }
    }

    function searchPost($account){
        try {
            $input = $this->input->post();
            $LAT_HERE = $input['location_lat'];
            $LONG_HERE = $input['location_lng'];
            $RADIUS = 10.0;

            //paging
            $limit = "";
            $query_newest = "";
            $numberPerPage = DEFIND_PER_PAGE_DEFAULT;

            if ($this->input->post('row_per_page')) {
                $numberPerPage = $this->input->post('row_per_page');
            }

            if (!empty($input['page'])) {
                $begin = ($input['page'] - 1) * $numberPerPage;
                $limit = "LIMIT $numberPerPage OFFSET  $begin";
            }

            //get newest posts > created_at
            if(!empty($input['created_at'])){
                $created_at = $input['created_at'];
                $query_newest = "AND z.created_at > '$created_at' ";
                $limit = "";
            }

            $query = $this->db->query("
                SELECT z.id, z.type_id, z.content, z.is_emergency,
                      z.created_by, z.location_lat, z.location_lng, z.location_name,  z.created_at, x.name,
                      z.updated_at, z.is_delete, z.picked_by, z.picked_at, z.completed_at, z.status,
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
                  AND z.is_delete IS NULL
                  AND z.created_by <> ".$account['id']."
                  AND z.status <> 2
                  ".$query_newest."
                  AND x.name LIKE '%" . $input['query'] . "%'
                  ORDER BY distance_in_km, z.created_at DESC
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

    function postSearchTotalPage($account){
        try {
            $input = $this->input->post();
            $LAT_HERE = $input['location_lat'];
            $LONG_HERE = $input['location_lng'];
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
                  AND z.is_delete IS NULL
                  AND z.created_by <> ".$account['id']."
                  AND z.status <> 2
                  AND x.name LIKE '%" . $input['query'] . "%'

                  ORDER BY distance_in_km, z.created_at DESC
                ");

            $result = $query->result_array();
            return ceil(count($result) / $row_per_page);
        }
        catch(ErrorException $e){
            return null;
        }
    }

    function deletePostById($id,$account_id){
        $isDelete = $this->db->update('posts',array('is_delete' => 1),array('id' => $id,'created_by' => $account_id));
        if($isDelete){
            return true;
        }
        return false;
    }

    function getPostIdForPushNotify($array){
        try{
            $this->db->select('*');
            $this->db->where($array);
            $query = $this->db->get('posts');
            $result = $query->result_object();
            return $result;
        }catch(ErrorException $e){
            return null;
        }
    }

    function getPostInfoById($id)
    {
        try{
            /*$query = $this->db->query("
                SELECT p.created_at AS created_at, a.full_name AS full_name, t.name AS type_post_name
                FROM posts AS p, accounts AS a, type_posts AS t
                WHERE p.created_by = a.id AND p.type_id = t.id AND p.id =".$id
            );*/

            /*$this->db->select('p.*');
            $this->db->from('posts as p');
            $this->db->where('p.id',$id);
            $this->db->join('');
            $query = $this->db->get();
            return $query->result_array();*/

            $this->db->select('*');
            $this->db->from('posts as p');
            $this->db->where('p.id',$id);
            $this->db->join('type_posts as pot','pot.id = p.type_id', 'left');
            $query = $this->db->get();


            if($query->num_rows() > 0 ){
                $result = $query->result_object();
                if(count($result)>0){
                    $arrTemp = array();

                        if(!empty($result[0] -> type_id)) {
                            $result[0] -> post_type = $this->getTypePostById($result[0] -> type_id);
                        }else{
                            $result[0] -> post_type = null;
                        }
                    return $result[0];
                }
                return null;
            }
        }catch(ErrorException $e){
            return null;
        }
    }

    function getPostDetailById($id)
    {
        try{
            $this->db->select('p.*');
            $this->db->from('posts as p');
            $this->db->where('p.id',$id);
            $this->db->join('type_posts as pot','pot.id = p.type_id', 'left');
            $query = $this->db->get();


            if($query->num_rows() > 0 ){
                $result = $query->result_object();
                if(count($result)>0){
                    $arrTemp = array();

                    if(!empty($result[0] -> type_id)) {
                        $result[0] -> post_type = $this->getTypePostById($result[0] -> type_id);
                    }else{
                        $result[0] -> post_type = null;
                    }

                    if(!empty($result[0] -> created_by)){
                        $result[0] -> normal_account = $this->account->getAccountInfoById($result[0] -> created_by);
                    }else{
                        $result[0] -> normal_account = null;
                    }

                    if(!empty($result[0] -> picked_by)){
                        $result[0] -> provider_account = $this->account->getAccountInfoById($result[0] -> picked_by);
                    }else{
                        $result[0] -> provider_account = null;
                    }
                    return $result[0];
                }
                return null;
            }
        }catch(ErrorException $e){
            return null;
        }
    }


    function pick($post_id,$account){
        try{
            $data = array(
                'picked_at' => getCurrentDate(),
                'picked_by' => $account['id'],
                'status' => 1
            );
            $isUpdate = $this->db->update('posts',$data,array('id' => $post_id));
            if($isUpdate){
                return true;
            }else{
                return false;
            }
        }catch (ErrorException $e){
            return false;
        }
    }

    function destroy($id){
        try {
            $data = array(
                'status' => 0,
                'picked_by' => null,
                'picked_at' => null,
                'completed_at' => null
            );
            $isUpdate = $this->db->update('posts', $data, array('id' => $id));
            if ($isUpdate) {
                return true;
            } else {
                return false;
            }
        }catch (ErrorException $e){
            return false;
        }
    }

    function complete($id){
        try{
            $data = array(
                'status' => 2,
                'completed_at' => getCurrentDate()
            );
            $isUpdate = $this->db->update('posts', $data, array('id' => $id));
            if ($isUpdate) {
                return true;
            } else {
                return false;
            }
        }catch (ErrorException $e){
            return false;
        }
    }

    function getPostsOfProvider($account_id,$page = null,$numberPerPage = null)
    {
        $this->load->model('file_model');
        try {
            $this->db->select('
            po.*
        ');
            $this->db->from('posts as po');
            $this->db->join('type_posts as pot', 'pot.id = po.type_id', 'left');
            $this->db->where('(po.created_by = '.$account_id.' OR po.picked_by = '.$account_id.')');
            $this->db->where('po.is_delete', NULL);
            //$this->db->or_where('po.picked_by', $account_id);

            $status = $this->input->post('status');
            if(empty($status)){
                $this->db->where('po.status < 2');
            }else{
                $this->db->where('po.status', $status);
            }

            $this->db->order_by('po.created_at', 'DESC');


            if ($page !== null) {
                $begin = ($page - 1) * $numberPerPage;
                $this->db->limit($numberPerPage, $begin);
            }
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
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
        }catch (ErrorException $e){
            return null;
        }
    }
    function getNewestProviderPosts($input){
        $account_id = $input['account_id'];
        $created_at = $input['created_at'];
        try {
            $this->load->model('file_model');
            $this->db->select('
            po.*
        ');
            $this->db->from('posts as po');
            $this->db->join('type_posts as pot', 'pot.id = po.type_id', 'left');
            $this->db->where('(po.picked_by = '.$account_id.' OR po.created_by = '.$account_id.')');
            //$this->db->or_where('po.created_by',$account_id);
            $this->db->where('po.created_at >', $created_at);
            $this->db->where('po.is_delete', NULL);

            $status = $this->input->post('status');
            if(empty($status)){
                $this->db->where('po.status < 2');
            }else{
                $this->db->where('po.status', $status);
            }

            $this->db->order_by('po.is_emergency', 'DESC');
            $this->db->order_by('po.created_at', 'DESC');

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
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
        }catch (ErrorException $e){
            return null;
        }
    }

    function picks($array_post_id,$account){
        try {
            $data = array(
                'picked_at' => getCurrentDate(),
                'picked_by' => $account['id'],
                'status' => 1
            );
            $error = array();
            for ($i = 0; $i < count($array_post_id); $i++) {
                    $isUpdate = $this->db->update('posts', $data, array('id' => (int)$array_post_id[$i]));
                    if ($isUpdate == false) {
                        array_push($error, $array_post_id[$i]);
                    }
            }
            if(count($error)>1){
                return $error;
            }else{
                return true;
            }
        }catch (ErrorException $e){
            return false;
        }
    }
}