<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class File extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('file_model');
	}

	public function show($id,$style = ""){
		$file = $this->file_model->getFileById($id);
		if(!empty($file)){
			header('Content-Type: '.$file->file_type);
			if(!empty($style)){
				readfile(base_url($file->file_path).'/'.$file->id.'/'.$style.'/'.$file->file_name);
			}else{
				readfile(base_url($file->file_path).'/'.$file->id.'/original/'.$file->file_name);
			}
			// var_dump(base_url($file->file_path).'/'.$file->id.'/'.$style.'/'.$file->file_name);
		}
	}
}