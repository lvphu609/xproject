<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->library('image_lib');
    }
 
    function do_upload($model, $base64 = false){

        if (!file_exists('uploads/'.$model)){
            mkdir('uploads/'.$model, 0777, true);
        }

        $tempFolder = uniqid(date("m-d-Y H_i_s"));

        if (!file_exists('uploads/temp/'.$tempFolder)){
            mkdir('uploads/temp/'.$tempFolder, 0777, true);
        }
        
        if(!$base64){
            //upload file temp -----------------------------------------------------
            $config = array(
                'allowed_types'     => 'jpg|jpeg|gif|png', //only accept these file types
                'max_size'          => 2048, //2MB max
                'upload_path'       =>  'uploads/temp/'.$tempFolder//upload directory
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
        if (!file_exists('uploads/'.$model.'/'.$no.'/original')){
            mkdir('uploads/'.$model.'/'.$no.'/original', 0777, true);
        }
        
        $config = array(
            'source_image'      => $image_data['full_path'], //path to the uploaded image
            'new_image'         => 'uploads/'.$model.'/'.$no.'/original', //path to
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
    	  		if (!file_exists('uploads/'.$model.'/'.$no.'/'.$key)){
    		        mkdir('uploads/'.$model.'/'.$no.'/'.$key, 0777, true);
    		    }
    		    $this->resized_path = 'uploads/'.$model.'/'.$no.'/'.$key;

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
        //remove the folder temp
        $this->deleteDir('uploads/temp/'.$tempFolder);

        return $no;
    }

    function saveImage($directory,$base64img){
        $base64img = preg_replace('#^data:image/[^;]+;base64,#', '', $base64img);
        $data = base64_decode($base64img);
        $file_name = uniqid().'.png';
        $file = $directory . '/'.$file_name;
        file_put_contents($file, $data);
        return array(
            'path' => $file,
            'name' => $file_name
        );
    }

    function getFileById($id){
        $this->db->where('id', trim($id));
        $query = $this->db->get('files');
        if(count($query->result()) >0){
            $result = $query->result();
            return $result[0];  
        }
        return null;
    }

    function deleteFileById($id){
        //get file info
        $file = $this->getFileById($id);

        //delete folder
        $this->deleteDir($file->file_path.$id);

        //delete file info in database
        $this->db->delete('files', array('id' => $id)); 
    }

    function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            // throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    function getLinkFileById($id,$option = ""){
        return base_url('file/show/'.$id.'/'.$option);
    }

    function do_update($model, $base64 = false, $fileId){

        if (!file_exists('uploads/'.$model)){
            mkdir('uploads/'.$model, 0777, true);
        }

        $tempFolder = uniqid(date("m-d-Y H_i_s"));

        if (!file_exists('uploads/temp/'.$tempFolder)){
            mkdir('uploads/temp/'.$tempFolder, 0777, true);
        }

        if(!$base64){
            //upload file temp -----------------------------------------------------
            $config = array(
                'allowed_types'     => 'jpg|jpeg|gif|png', //only accept these file types
                'max_size'          => 2048, //2MB max
                'upload_path'       =>  'uploads/temp/'.$tempFolder//upload directory
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
                'updated_at' => getCurrentDate()
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
                'updated_at' => getCurrentDate(),
            );
        }

        // save file
        $isUpdate = $this->db->update('files',$image_record,array('id' => $fileId));


        //upload original file-------------------------------------------------
        if (!file_exists('uploads/'.$model.'/'.$fileId.'/original')){
            mkdir('uploads/'.$model.'/'.$fileId.'/original', 0777, true);
        }

        $config = array(
            'source_image'      => $image_data['full_path'], //path to the uploaded image
            'new_image'         => 'uploads/'.$model.'/'.$fileId.'/original', //path to
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
                if (!file_exists('uploads/'.$model.'/'.$fileId.'/'.$key)){
                    mkdir('uploads/'.$model.'/'.$fileId.'/'.$key, 0777, true);
                }
                $this->resized_path = 'uploads/'.$model.'/'.$fileId.'/'.$key;

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
        //remove the folder temp
        $this->deleteDir('uploads/temp/'.$tempFolder);
        return $fileId;
    }
}