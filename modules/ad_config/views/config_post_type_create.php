<?php
    if(empty($post_type)){
        $action=base_url('admin/config/post_types/store');
    }else{
        $action=base_url('admin/config/post_types/update');
    }

    echo form_open($action, array('method' => 'post', 'id' => 'frm_post_type'));
        include('config_post_type_form.php');
    echo form_close();
?>
