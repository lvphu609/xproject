<?php if (!defined('BASEPATH')) exit('No direct script access allowed');class Ad_config_model extends CI_Model{    public function __construct()    {        parent::__construct();        $this->load->model('file_model');    }    public function countRecord($table,$search = NULL){        $this->db->from($table);        if(!empty($search)){            if($search!=""){                if($table == 'type_posts') {                    $this->db->like('name', $search);                    $this->db->or_like('description', $search);                }            }        }        $total = $this->db->count_all_results();        return $total;    }    public function postTypeList($paging_limit,$page = NULL,$search,$data_filter = NULL){        $this->db->from('type_posts');        if ($page !== null)        {            $begin = ($page - 1)*$paging_limit;            $this->db->limit($paging_limit, $begin);        }        if(!empty($search)){            if($search != ""){                $this->db->like('name',$search);                $this->db->or_like('description',$search);            }        }        $query = $this->db->get();        $result = $query->result_array();        if(count($result) > 0){            foreach($result as $key => $value){                $result[$key]['avatar_link'] = $this->file_model->getLinkFileById($value['avatar']);            }        }        return $result;    }    }