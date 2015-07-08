<?php
    echo form_open(base_url('admin/config/post_types/store'), array('method' => 'post', 'id' => 'frm_post_type'));
        include('config_post_type_form.php');
    echo form_close();
?>
