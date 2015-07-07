
    <!-- /.row -->
    <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form method="GET" active="<?php echo base_url(); ?>index.php/article_list">
                        <div class="input-group custom-search-form pull-left col-lg-4 col-md-6 col-sm-9 col-xs-12">
                            <input value="<?php echo !empty($search) ? $search: ''; ?>" type="text" class="form-control" placeholder="Search..." name="search">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <div class="pull-right col-lg-1 col-md-1 col-sm-3 col-xs-3">
                        <a href="<?php echo base_url(); ?>index.php/admin/article_create">
                            <button title="Thêm bài viết" type="button" class="btn btn-success btn-sm col-xs-12 button-add-article">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </a>
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php
                    if(!empty($pagination)){
                        echo $pagination;
                    }
                    ?>
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>Tên loại yêu cầu</th>
                                <th>Mô tả</th>
                                <th>Sửa/Xóa</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($post_type_list)>0): ?>
                                <?php foreach($post_type_list as $key => $row): ?>
                                    <tr class="art-<?php echo $row['id']; ?>">
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td class="text-center">
                                            <a title="Sửa bài viết" class="btn btn-success btn-xs" href="<?php echo base_url().'index.php/admin/article_edit?art='.$row['id'] ?>">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                            <button title="Xóa bài viết" type="button" data-id="<?php echo $row['id']; ?>" class="btn btn-danger btn-xs buttonDelete">
                                                <span class="glyphicon glyphicon-trash "></span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    if(!empty($pagination)){
                        echo $pagination;
                    }
                    ?>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
    </div>
    <!-- /.row -->


<div class="modal fade" id="modalDeleteItemArticle" tabindex="-3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header fontbold">Thông báo</div>
            <div class="modal-body">
                <i class="glyphicon warning glyphicon-warning-sign"></i>&nbsp;Bạn có muốn xóa bài viết đang chọn!
            </div>
            <div class="col-lg-12 messageAlert"></div>
            <div class="clear"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger btnConfirmDeleteArticle">Xóa</button>
                <button type="button" class="btn btn-sm btn-default btnCancelDeleteArticle" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>