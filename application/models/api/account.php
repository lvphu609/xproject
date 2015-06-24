<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class account extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //check account when user login
    function check_account($email,$password)
    {
        $data = array(
            'is_access_token' => null
        );
        $array = array('email' => $email,'password' => $password);
        $query=$this->db->get_where('accounts',$array);

        if($query->num_rows()==1)
        {
            $data = array(
                'is_access_token' => uniqid()
            );
        }
            $this->db->where('email', $email);
            $result = $this->db->update('accounts', $data);
        if($result)
            return $data['is_access_token'];
        else
            return null;
    }
    //logout
    function logout_account($is_access_token)
    {
        $data = array(
            'is_access_token' => ''
        );
        //check is_access_token available?
        $query = $this->db->get_where('accounts',array('is_access_token' => $is_access_token));
        $this->db->where('is_access_token', $is_access_token);
        $this->db->update('accounts', $data);

        if($query->num_rows()==1)
            return 'success';
        else
            return 'failure';
    }

    function reset_password_key($is_access_token)
    {
        $key_code = uniqid();
        $query = $this->db->get_where('accounts',array('is_access_token' => $is_access_token));
        $this->db->where('is_access_token', $is_access_token);
        $this->db->update('accounts', array('reset_password_key' => $key_code));

        if($query->num_rows()==1)
            return $key_code;
        else
            return 'failure';
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

}