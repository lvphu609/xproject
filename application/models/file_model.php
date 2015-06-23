<?php
class File_model extends CI_Model{
  var $original_path;
 
  function __construct(){
    parent::__construct();
    $this->load->library('image_lib');
  }
 
  function do_upload($model){

    if (!file_exists(APPPATH.'../uploads/'.$model))
    {
        mkdir(APPPATH.'../uploads/'.$model, 0777, true);
    }

    $tempFolder = uniqid(getCurrentDate());

    if (!file_exists(APPPATH.'../uploads/temp/'.$tempFolder))
    {
        mkdir(APPPATH.'../uploads/temp/'.$tempFolder, 0777, true);
    }

    //upload file temp -----------------------------------------------------
    $config = array(
        'allowed_types'     => 'jpg|jpeg|gif|png', //only accept these file types
        'max_size'          => 2048, //2MB max
        'upload_path'       =>  realpath(APPPATH.'../uploads/temp/'.$tempFolder)//upload directory
    );
    $this->load->library('upload', $config);
    $this->upload->do_upload('avatar');
    $image_data = $this->upload->data();

    $image_record = array(
        'file_name' => $image_data['file_name'],
        'file_type' => $image_data['file_type'],
        'raw_name' => $image_data['raw_name'],
        'orig_name' => $image_data['orig_name'],
        'client_name' => $image_data['client_name'],
        'file_ext' => $image_data['file_ext'],
        'file_size' => $image_data['file_size'],
        'image_width' => $image_data['image_width'],
        'image_height' => $image_data['image_height'],
        'image_type' => $image_data['image_type'],
        'file_path' => 'uploads/'.$model.'/',
        'created_at' => getCurrentDate(),
        'updated_at' => NULL
    );

    // save file 
    $isInsert = $this->db->insert('files',$image_record);
    if($isInsert){
        $no = $this->db->insert_id();
    }else{
        $no = uniqid();
    }


    //upload original file-------------------------------------------------
    if (!file_exists(APPPATH.'../uploads/'.$model.'/'.$no.'/original'))
    {
        mkdir(APPPATH.'../uploads/'.$model.'/'.$no.'/original', 0777, true);
    }
    $this->original_path = realpath(APPPATH.'../uploads/'.$model.'/'.$no.'/original');
    
    $config = array(
        'source_image'      => $image_data['full_path'], //path to the uploaded image
        'new_image'         => $this->original_path, //path to
        'maintain_ratio'    => true,
        'width'             => null,
        'height'            => null
    );
 
    $this->image_lib->initialize($config);
    $this->image_lib->resize();

    //upload config-----------------------------------------------------------
    $upload_config = $this->config->item('images');
    if(!empty($upload_config[$model])){
  		foreach ($upload_config[$model] as $key => $size) {
	  		if (!file_exists(APPPATH.'../uploads/'.$model.'/'.$no.'/'.$key))
		    {
		        mkdir(APPPATH.'../uploads/'.$model.'/'.$no.'/'.$key, 0777, true);
		    }
		    $this->resized_path = realpath(APPPATH.'../uploads/'.$model.'/'.$no.'/'.$key);

		    //your desired config for the resize() function
		    $config = array(
			    'source_image'      => $image_data['full_path'], //path to the uploaded image
			    'new_image'         => $this->resized_path, //path to
			    'maintain_ratio'    => true,
			    'width'             => $size['width'],
			    'height'            => $size['height']
		    );
		 
		    $this->image_lib->initialize($config);
		    $this->image_lib->resize();
	  	}
  	}
    return $no;
  }

  function getFileById($id){
    $id = trim($id);
    if(is_numeric($id)){
      $this->db->where('id', $id);
      $query = $this->db->get('files');
      if(count($query->result()) >0){
        return $query->result()[0];  
      }
      return null;
    }
  }

}