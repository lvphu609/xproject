
<!-- /.row -->
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-right">
                <div class="row btn-create-article">
                    <div class="col-lg-12">
                        <div class="pull-right">
                            <button type="submit" form="form-category" data-toggle="tooltip" title="Lưu" class="btn btn-primary btn-save-data-article" data-original-title="Save"><i class="fa fa-save"></i></button>
                            <a href="<?php echo base_url('admin/config/post_types'); ?>" data-toggle="tooltip" title="Hủy" class="btn btn-default" data-original-title="Cancel"><i class="fa fa-reply"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <form id="formPostType" method="post"
                <?php  if(empty($article)): ?>
                  action="<?php echo base_url(); ?>index.php/admin/article_store" xmlns="http://www.w3.org/1999/html">
                <?php else: ?>
                    action="<?php echo base_url(); ?>index.php/admin/article_update">
                <?php endif; ?>

                <div class="row input-form-item">
                    <div class="col-lg-12">
                        <div class="form-group title-article">
                            <label for="input_title" class="col-sm-2 control-label">Tên thể loại:</label>
                            <div class="col-sm-10">
                                <input name="title-article" type="text" class="form-control" id="input_title" placeholder="Tiêu đề"
                                       value=" <?php
                                       if(!empty($article)){
                                           echo $article['title'];
                                       }
                                       ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row input-form-item">
                    <div class="col-lg-12">
                        <div class="form-group title-article">
                            <label for="input_title" class="col-sm-2 control-label">Mô tả:</label>
                            <div class="col-sm-10">
                                <textarea rows="4" ="" name="box-content-article" id="box-content-article" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row input-form-item">
                    <div class="col-lg-12">
                        <div class="form-group title-article">
                            <label for="input_title" class="col-sm-2 control-label"> Hình ảnh:</label>
                            <div class="col-sm-10">
                                <img class="avatar-post-type" src="" data-toggle="modal" data-target="#myModal" width="100" height="100">
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!empty($article)): ?>
                    <input name="article-id" type="hidden" value="<?php echo $article['id']; ?>">
                <?php endif; ?>
            </form>
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
                    <input type="file" class="cropit-image-input">
                    <div class="cropit-image-preview"></div>
                    <input type="range" class="cropit-image-zoom-input">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary export">Lưu</button>
            </div>
        </div>
    </div>
</div>



