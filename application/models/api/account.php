<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('file_model');
    }

    //logout
    function logout_account($access_token)
    {
        $query = $this->db->get_where('tokens',array('access_token' => $access_token));
        if($query->num_rows()==1){
            if($this->db->delete('tokens', array('access_token' => $access_token)))
                return true;
        }
        return false;
    }

    
    function createAccount($data,$account_id = null){
         try {
             //create account
             if (empty($account_id)) {
                 $temp = array(
                     'created_at' => getCurrentDate()
                 );

                 $recordData = array_merge($data, $temp);

                 $isInsert = $this->db->insert('accounts', $recordData);

                 if ($isInsert) {
                     return true;
                 }
             } //update account
             else {
                 $temp = array(
                     'updated_at' => getCurrentDate()
                 );

                 $recordData = array_merge($data, $temp);

                 //delete file avatar
                 if (!empty($recordData['avatar'])) {
                     $account = $this->getAccountById($account_id);

                     $this->file_model->deleteFileById($account['avatar']);
                 }

                 $isUpdate = $this->db->update('accounts', $recordData, array('id' => $account_id));

                 if ($isUpdate) {
                     return true;
                 }
             }
         }catch (ErrorException $e){
            return false;
        }
		return false;
    }

    function checkAccount($input){
        $query = $this->db->get_where('accounts',array(
            'username' => trim($input['username']),
            'password' => trim($input['password'])
        ));

        if($query->num_rows()==1){
            $result = $query->result_array();

            $access_token = md5(uniqid().time().md5($result[0]['email']));
            $isCreateToken = $this->db->insert('tokens',array(
                'access_token' => $access_token,
                'email' => $result[0]['email'],
                'account_id' => $result[0]['id'],
                'access_token' => $access_token,
                'access_token_start_at' => getCurrentDate()
            ));
            if($isCreateToken){
                return array(
                    'access_token' => $access_token,
                    'account_id' => $result[0]['id'],
                    'account_type' => $result[0]['account_type']
                );
            }
        }
        return false;
    }

    function get_reset_password_key($email)
    {
        $key_code = uniqid();
        $query = $this->db->get_where('accounts',array('email' => $email));
        if($query->num_rows()==1){
            $result = $query->result_array();
            $isCreateCode = $this->db->insert('tokens',array(
                'forgot_password_code' => $key_code,
                'email' => $email,
                'account_id' => $result[0]['id'],
                'access_token_start_at' => getCurrentDate()
            ));
            if($isCreateCode){
                return $key_code;
            }
        }
        return null;
    }

    function reset_password($reset_password_key,$password)
    {
        if($reset_password_key != NULL || $reset_password_key != '') {
            $query1 = $this->db->get_where('accounts', array('reset_password_key' => $reset_password_key));
            $this->db->where('reset_password_key', $reset_password_key);
            $query2 = $this->db->update('accounts', array('password' => md5($password), 'reset_password_key' => NULL));

            if ($query1->num_rows() == 1 && $query2) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        else{
            return FALSE;
        }
    }

    function generalHtmlForGotPassword($email){
        $data['code'] = $this->get_reset_password_key($email);

        $account = $this->getAccountByEmail($email);
        $data['full_name'] = $account['full_name'];
        $data['link_reset_password'] = 'link';
        $htmlContent = $this->load->view('email/forgot_password_tpl',$data,TRUE);
        return $htmlContent;
    }

    function checkExistRessetPasswordKey($email){
        $this->db->from('tokens');
        $this->db->where('email',$email);
        $this->db->where('forgot_password_code IS NOT NULL');
        $query = $this->db->get();
        if($query->num_rows() >= 1){
            return true;
        }
        return false;
    }

    function updateTokenResetPass($data){
        //check exist token
        $this->db->from('tokens');
        $this->db->where('email',$data['email']);
        $this->db->where('forgot_password_code',$data['code']);
        $query = $this->db->get();
        if($query->num_rows() == 1){
            //update password for account
            $result = $query->result_array();

            $this->db->where('id', $result[0]['account_id']);
            if($this->db->update('accounts', array('password' => $data['password']))){
                //delete token
                $this->db->delete('tokens',array('id' => $result[0]['id']));
                return null;    
            }
        }
        return $this->lang->line('token_not_exist');
    }

    function getAccountByEmail($email){
        $this->db->from('accounts');
        $this->db->where('email',$email);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0];
    }

    function getAccountById($id){
        $this->db->from('accounts');
        $this->db->where('id',$id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0];
    }

    function checkEmailUniqueToUpdateAccount($account_id,$email){
        $this->db->select('*');
        $this->db->from('accounts');
        $this->db->where(array(
            'email' => $email,
            'id <>' => $account_id
        ));
        $query = $this->db->get();
        return $query->num_rows() === 0;
    }

    function getAccountInfoById($id){
        $this->db->select(
            'id, username, email, full_name,
            date_of_birth, gender, identity_card_id,
            phone_number, blood_group_id, blood_group_rh_id,
            avatar, address, updated_at,
            contact_name, contact_phone'
        );
        $this->db->from('accounts');
        $this->db->where('id',$id);
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result)>0){
            $account = $result[0];
            $account['avatar'] = $this->file_model->getLinkFileById($account['avatar']);
            return $account;
        }
        return false;
    }

    function changePassword($oldPass,$newPass,$account_id){
        //check old pass
        $query = $this->db->get_where('accounts',array('password' => $oldPass, 'id' => $account_id));
        $data = array(
            'old_password' => false,
            'update' => false
        );
        if($query->num_rows()==1) {
            $data['old_password'] = true;
            $isUpdate = $this->db->update('accounts', array('password' => $newPass), array('id' => $account_id));
            if ($isUpdate) {
                $data['update'] = true;
            }
        }
        return $data;
    }



    /*
     *     $this->db->select("DATE_FORMAT( date, '%d.%m.%Y' ) as date_human",  FALSE );
    $this->db->select("DATE_FORMAT( date, '%H:%i') as time_human",      FALSE );*/

    function getAccountIdByLocation($location, $RADIUS = 10.0){
        $LAT_HERE = $location['location_lat'];
        $LONG_HERE = $location['location_lng'];
        try {
            $query = $this->db->query("
                SELECT  id,
                    p.distance_unit
                             * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                             * COS(RADIANS(z.location_lat))
                             * COS(RADIANS(p.longpoint) - RADIANS(z.location_lng))
                             + SIN(RADIANS(p.latpoint))
                             * SIN(RADIANS(z.location_lat)))) AS distance_in_km
                  FROM accounts AS z
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
                  AND z.account_type = 2
                  ORDER BY distance_in_km
            ");
            $result = $query->result_array();
            return $result;
        }catch (ErrorException $e){
            return null;
        }
    }

    function storeLocationById($account_id,$location){
        try{
            $location_lat = $location['location_lat'];
            $location_lng = $location['location_lng'];
            $data =array(
                'location_lat' => $location_lat,
                'location_lng' => $location_lng
            );
            $isUpdate = $this->db->update('accounts', $data, array('id' => (int)$account_id));
            if($isUpdate){
                return true;
            }else{
                return false;
            }
        }catch (ErrorException $e){
            return false;
        }
    }

    function getLocationById($account_id){
        $this->db->select('id,location_lat,location_lng');
        $this->db->where('id', (int)$account_id);
        $query = $this->db->get('accounts');
        return $query->result_object();
    }
}