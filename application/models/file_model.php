<?php
class File_model extends CI_Model{
  var $original_path;
 
  function __construct(){
    parent::__construct();
    $this->load->library('image_lib');
  }
 
  function do_upload($model, $base64 = false){

    if (!file_exists(APPPATH.'../uploads/'.$model))
    {
        mkdir(APPPATH.'../uploads/'.$model, 0777, true);
    }

    $tempFolder = uniqid(getCurrentDate());

    if (!file_exists(APPPATH.'../uploads/temp/'.$tempFolder))
    {
        mkdir(APPPATH.'../uploads/temp/'.$tempFolder, 0777, true);
    }
    
    if(!$base64){
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
            'file_ext' => $image_data['file_ext'],
            'file_size' => $image_data['file_size'],
            'image_width' => $image_data['image_width'],
            'image_height' => $image_data['image_height'],
            'file_path' => 'uploads/'.$model.'/',
            'created_at' => getCurrentDate(),
            'updated_at' => NULL
        );

    }else{
        $file_rest = $this->saveImage('uploads/temp/'.$tempFolder,$this->input->post('avatar'));
        $file_path = $file_rest['path'];

        $image_data['full_path'] = $file_path;
        $imageSize = getimagesize($file_path);
        $image_record = array(
            'file_name' =>$file_rest['name'],
            'file_type' => $imageSize['mime'],
            'file_ext' => pathinfo($file_path, PATHINFO_EXTENSION),
            'file_size' => filesize($file_path),
            'image_width' => $imageSize[0],
            'image_height' => $imageSize[1],
            'file_path' => 'uploads/'.$model.'/',
            'created_at' => getCurrentDate(),
            'updated_at' => NULL
        );
    }

    

    

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

    function saveImage($directory,$base64img){
        $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
        $data = base64_decode($base64img);
        $file_name = uniqid().'.jpg';
        $file = $directory . '/'.$file_name;
        file_put_contents($file, $data);
        return array(
            'path' => $file,
            'name' => $file_name
        );
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