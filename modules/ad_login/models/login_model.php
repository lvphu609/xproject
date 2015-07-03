<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $model = array('common_enum','common_model');
    $this->load->model($model);
    $this->load->library('session');
    $this->info_user = $this->session->userdata('user_login');     
  }

  public function test()
  {
    echo "testr";
  }
  public function check_login($username, $password)
  {
    $user = $this->isExistUserName($username);
    if ($user != null) {
      $password_database = $user->password;
      if (strcmp($password, $password_database) == 0) {
        $this->addUserInfoToSession($username);
        return $password_database;
      }
      return null;
    }
  }

  public function check_login_haspas($haspas_username, $haspas_password)
  {
    $user = $this->isExistUserName($haspas_username);
    if ($user != null) {
      $password_database = $user->password;
      $hash_password_database = md5('_pan' . $user->username . md5($password_database));
      if (strcmp($haspas_password, $hash_password_database) == 0) {
        $this->addUserInfoToSession($haspas_username);
        return $password_database;
      }
      return null;
    }
  }
  function isExistUserName($username)
  {

    $this->db->select(Common_enum::TBL_USER . '.' . Common_enum::USERNAME . ',' .
      Common_enum::TBL_USER . '.' . Common_enum::PASSWORD . ',' .
      Common_enum::TBL_USER . '.' . Common_enum::IS_ACTIVE
    );
    $this->db->from(Common_enum::TBL_USER);

    $this->db->where(Common_enum::TBL_USER . '.' . Common_enum::USERNAME . ' = ', $username);

    $this->db->where(Common_enum::TBL_USER . '.' . Common_enum::IS_ACTIVE . ' = ', 1);

    $query = $this->db->get();

    if ($query->num_rows() == 1) return $query->row();
    return null;
  }

  function  addUserInfoToSession($username)
  {

    $user_info = $this->getInfoUserByUserName($username);

    $session_array = array('user_login' => $user_info);

    $this->session->set_userdata($session_array);

    $this->updateUserLogInPermission($user_info[Common_enum::ID]);
  }

  function  getInfoUserByUserName($username)
  {

    $this->db->select(Common_enum::TBL_USER . '.' . Common_enum::ID . ',' .
      Common_enum::TBL_USER . '.' . Common_enum::USERNAME
    );

    $this->db->from(Common_enum::TBL_USER);

    $this->db->where(Common_enum::TBL_USER . '.' . Common_enum::USERNAME . ' = ', $username);
    
    $query = $this->db->get();

    $results = $query->result_array();

    return $results[0];
  }

  private function getRoleParent($role_id)
  {
    $this->db->select(Common_enum::ROLE_ID. ', GetRoleParent(' . Common_enum::ROLE_ID . ') as parent_ids ')
      ->from(Common_enum::TBL_ADMIN_ROLE)
      ->where(Common_enum::ROLE_ID, $role_id);

    $query = $this->db->get();
    $results = $query->result_array();
    return $results;
  }

  private function getPermissionResources($role_ids)
  {
    $this->db->distinct();
    $this->db->select(Common_enum::TBL_ADMIN_RESOURCE . '.' . Common_enum::RESOURCE_ID);
    $this->db->from(Common_enum::TBL_ADMIN_RULE);
    $this->db->join(Common_enum::TBL_FUNCTION, Common_enum::TBL_ADMIN_RULE . '.' . Common_enum::FUNCTION_ID . ' = ' . Common_enum::TBL_FUNCTION . '.' . Common_enum::ID, 'left');
    $this->db->join(Common_enum::TBL_ADMIN_RESOURCE, Common_enum::TBL_ADMIN_RESOURCE . '.' . Common_enum::FUNCTION_ID . ' = ' . Common_enum::TBL_FUNCTION . '.' . Common_enum::ID, 'right');
    $this->db->where_in(Common_enum::TBL_ADMIN_RULE . '.' . Common_enum::ROLE_ID, $role_ids);

    $query = $this->db->get();
    $results = $query->result_array();
    return $results;
  }

  private function getRoleByUserId($user_id)
  {
    $this->db->select('*')
      ->from(Common_enum::TBL_ADMIN_ROLE)
      ->where(Common_enum::ROLE_TYPE, Common_enum::USER)
      ->where(Common_enum::USER_ID, $user_id);
    $query = $this->db->get();
    $results = $query->result_array();
    return $results;
  }

  public function getUserPermission($user_id)
  {
    $result = array();

    $user_role = $this->getRoleByUserId($user_id);

    if (is_array($user_role) && count($user_role) > 0)
    {
      foreach($user_role as $ur)
      {
        $user_role_id = $ur[Common_enum::ROLE_ID];
        $roles = $this->getRoleParent($user_role_id);
        if (is_array($roles) && count($roles) > 0) {
          foreach($roles as $r)
          {
            $role_ids = $r[Common_enum::ROLE_ID] . ',' . $roles[0][Common_enum::PARENT_IDS];
            $resource_ids = $this->getPermissionResources(explode(',', $role_ids));



            if (is_array($resource_ids) && count($resource_ids) > 0) {
              foreach ($resource_ids as $row) {

                if (in_array($row[Common_enum::RESOURCE_ID], $result) == false)
                {
                  array_push($result, $row[Common_enum::RESOURCE_ID]);
                }
              }
            }
          }
        }
      }
    }
    return $result;
  }

  private  function getLastedUpdateDateOfRoleInTree($user_id, $lasted_update)
  {
    $result = null;
    $user_role = $this->getRoleByUserId($user_id);

    $role_ids = '';

    if (is_array($user_role) && count($user_role) > 0)
    {
      foreach($user_role as $ur)
      {
        $user_role_id = $ur[Common_enum::ROLE_ID];
        $va = $this->getRoleParent($user_role_id);
        if (is_array($va) && count($va) > 0)
        {
          foreach($va as $_va_)
          {
            $role_ids .= ','.$_va_[Common_enum::ROLE_ID] . ',' . $_va_[Common_enum::PARENT_IDS];
          }
        }
      }
      $role_ids = substr($role_ids, 1);

      $this->db->select(Common_enum::UPDATED_DATE)
        ->order_by(Common_enum::UPDATED_DATE, Common_enum::DESC)
        ->from(Common_enum::TBL_ADMIN_ROLE)
        ->where_in(Common_enum::TBL_ADMIN_ROLE . '.' . Common_enum::ROLE_ID, explode(',', $role_ids));
      $query = $this->db->get();
      $result =$query->row();

      $dd = strtotime(  $result->updated_date );
      $myDateTime = date('Y-m-d H:i:s', $dd);
      return $myDateTime;
    }
    return null;
  }

  public function checkUserLoginPermisionChanged($user_id, $lasted_update)
  {
    $new_lastest_update = $this->getLastedUpdateDateOfRoleInTree($user_id, $lasted_update);

    if ($new_lastest_update ==null)
    {
      $new_lastest_update = getCurrentDate();
    }

    if (strcmp($new_lastest_update, $lasted_update)>0)
    {
      $this->updateUserLogInPermission($user_id);
    }
  }

  private function updateUserLogInPermission($user_id)
  {
    $va = $this->getUserPermission($user_id);
    $va_session_array = array(Common_enum::SESSION_USER_LOGIN_PERMISSION => $va);
    $this->session->set_userdata($va_session_array);

    $lasted_update_session_array =  array(Common_enum::SESSION_USER_LOGIN_LASTED_UPDATED_PERMISSION => getCurrentDate());
    $this->session->set_userdata($lasted_update_session_array);

    $site_ids = $this->getSiteIdsByUserId($user_id);
    $sid = array();
    foreach($site_ids as $si)
    {
      array_push($sid, intval($si[Common_enum::SITE_ID]));
    }

    $site_ids_array = array(Common_enum::SESSION_USER_LOGIN_SITE_IDS => $sid);
    $this->session->set_userdata($site_ids_array);
  }

  private function getSiteIdsByUserId($user_id)
  {
    $this->db->select(Common_enum::SITE_ID)
      ->from(Common_enum::TBL_USER_SITE)
      ->where(Common_enum::USER_ID, $user_id);
    $query = $this->db->get();
    $results = $query->result_array();

    return $results;
  }

   public function change_pass($new_pas)
    {
        $user_session = $this->info_user;
        $created_date = getCurrentDate();
        $updated_date = $created_date;
        $array_data = array(
            'password'=> md5($user_session['username'].$new_pas.Common_enum::APP_TOKEN) ,
            'updated_date' => getCurrentDate()
        );
        $status = TRUE;
       
        $this->db->update(Common_enum::TBL_USER,$array_data,array(Common_enum::ID => $user_session['id']));

         if ($this->db->_error_message() != "") {
            $code_log = 'function change_pass';
            $content_log = $this->db->_error_message();
            $this->saveLog($code_log, $content_log);
            $status = FALSE;
        }
        return $status;
    }
    
    public function check_pass_user($old_pas)
    {
        $user_session = $this->info_user;
        $old_pas = md5($user_session['username'].$old_pas.Common_enum::APP_TOKEN);
        $pass_db = $this->common_model->getFieldById(Common_enum::TBL_USER,  Common_enum::PASSWORD, $user_session['id']);
        
      if(strcmp($old_pas, $pass_db) == 0){
         return true;
      }else{
        return false;
      }
    }
  
  
}