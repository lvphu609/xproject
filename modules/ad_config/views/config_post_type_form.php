<?php
    $name = "";
    $description = "";
    $avatar = "";
    $id= "";
    $base64 ="";
    $avatar_id="";
    if(!empty($post_type)) {
        if (count($post_type) > 0) {
            $name = $post_type->name;
            $description = $post_type->description;
            $avatar = $post_type->avatar;
            $id = $post_type->id;
            $avatar_id = $post_type->avatar_id;
            $data = file_get_contents($avatar);
            $base64 = 'data:image/png;base64,' . base64_encode($data);
        }
    }
?>
<!-- /.row -->
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-right">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-right">
                            <button type="submit" form="form-category" data-toggle="tooltip" title="Lưu" class="btn btn-primary btn-save-post-type" data-original-title="Save"><i class="fa fa-save"></i></button>
                            <a href="<?php echo base_url('admin/config/post_types'); ?>" data-toggle="tooltip" title="Hủy" class="btn btn-default" data-original-title="Cancel"><i class="fa fa-reply"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
                <div class="row input-form-item">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="input_title" class="col-sm-2 control-label">Tên thể loại:</label>
                            <div class="col-sm-10 <?php //echo !empty(form_error('name')) ? 'has-error' : ''; ?>">
                                <input name="name" type="text" class="form-control" id="input_title" placeholder="Tên thể loại" autofocus value="<?php
                                    /*if(!empty(form_error('name')))
                                        echo set_value('name');
                                    else{
                                        echo (set_value('name') != "") ? set_value('name') : $name;
                                    }*/
                                echo $name;
                                ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row input-form-item">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="input_title" class="col-sm-2 control-label">Mô tả:</label>
                            <div class="col-sm-10  <?php // echo !empty(form_error('description')) ? 'has-error' : ''; ?>">
                                <textarea rows="4" ="" name="description" id="box-content-article" class="form-control"><?php
                                    /*if(!empty(form_error('description')))
                                        echo set_value('description');
                                    else{
                                        echo (set_value('description') != "") ? set_value('description') : $description;
                                    }*/
                                echo $description;
                                ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row input-form-item">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="input_title" class="col-sm-2 control-label"> Hình ảnh:</label>
                            <div class="col-sm-10  <?php //echo !empty(form_error('avatar')) ? 'has-error' : ''; ?>">
                                <img class="avatar-post-type" src="<?php
                                   /* if(!empty(form_error('avatar')))
                                        echo set_value('avatar');
                                    else{
                                        echo (set_value('avatar') != "") ? set_value('avatar') : $avatar;
                                    }*/
                                echo $avatar;
                                ?>" data-toggle="modal" data-target="#myModal" width="100" height="100">
                                <input type="hidden" id="img_url" value="<?php echo $avatar; ?><?php //echo !empty(set_value('avatar')) ? set_value('avatar') : ''; ?>">
                                <input type="hidden" id="img_base64" name="avatar" value="<?php echo $base64;//echo !empty(set_value('avatar')) ? set_value('avatar') : $base64; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <input name="id" type="hidden" value="<?php echo $id; ?>">
                <input name="avatar_id" type="hidden" value="<?php echo $avatar_id; ?>">
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<!-- /.row -->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Hình ảnh</h4>
            </div>
            <div class="modal-body">
                <div class="image-editor">
                    <div class="cropit-image-preview"></div>
                    <input type="range" class="cropit-image-zoom-input">
                    <div class="input-file-custom">
                        <a class="btn btn-success form-control" href="javascript:;">
                            Chọn hình...
                            <input class="form-control cropit-image-input" id="inputId" type="file" name="file_source" size="40" onchange="$(&quot;#upload-file-info&quot;).html($(this).val());">
                        </a>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary export">Lưu</button>
            </div>
        </div>
    </div>
</div>



