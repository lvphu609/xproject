<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
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

    
    function createAccount($data){
    	$temp = array(
    		'created_at' => getCurrentDate()
    	);

    	$recordData = array_merge($data,$temp);

    	$isInsert = $this->db->insert('accounts',$recordData);
		
		if($isInsert){
			return true;
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
                    'account_id' => $result[0]['id']
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

}