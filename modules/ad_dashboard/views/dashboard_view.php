<?php
if(isset($js_file_module) && count($js_file_module)){
    foreach($js_file_module as $file)
        echo '<script type="text/javascript" src="'.base_url('modules/'.$file).'"></script>';
}
if(isset($css_file_module) && count($css_file_module)){
    foreach($css_file_module as $file)
        echo '<link rel="stylesheet" href="'.base_url('modules/'.$file).'">';
}

$dot_icon = base_url('resources/img/icon/pagedot_green2x.png');
?>
<div id="dashboard" class="content no-padding">
    <div class="row">

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="small-box bg-aqua">
                <div class="inner das-inner">
                    <div class="col-lg-12">
                        <img class="sub-menu-item-icon" src="<?php echo $dot_icon; ?>">
                        <a class="das-link addNewTsmsData " href="#">Loại yêu cầu.</a>
                    </div>
                </div>
                <div class="icon">
                    <img class="icon-bottom" src="<?php echo base_url('resources/img/icon/icn_check_white.png'); ?>">
                </div>
                <a  href="#" class="small-box-footer das-title">
                    CẤU HÌNH  <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->

        <?php /*
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="small-box bg-green">
                <div class="inner das-inner">
                    <div class="col-lg-12">
                        <img class="sub-menu-item-icon" src="<?php echo $dot_icon; ?>">
                        <a class="das-link addNewTsmsData " href="#">Loại yêu cầu.</a>
                    </div>
                </div>
                <div class="icon">
                    <img class="icon-bottom" src="<?php echo base_url('resources/img/icon/icn_site_white.png'); ?>">
                </div>
                <a  href="#" class="small-box-footer das-title">
                    CẤU HÌNH  <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="small-box bg-yellow">
                <div class="inner das-inner">
                    <div class="col-lg-12">
                        <img class="sub-menu-item-icon" src="<?php echo $dot_icon; ?>">
                        <a class="das-link addNewTsmsData " href="#">Loại yêu cầu.</a>
                    </div>
                </div>
                <div class="icon">
                    <img class="icon-bottom" src="<?php echo base_url('resources/img/icon/icn_employee_white.png'); ?>">
                </div>
                <a  href="#" class="small-box-footer das-title">
                    CẤU HÌNH  <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="small-box bg-red">
                <div class="inner das-inner">
                    <div class="col-lg-12">
                        <img class="sub-menu-item-icon" src="<?php echo $dot_icon; ?>">
                        <a class="das-link addNewTsmsData " href="#">Loại yêu cầu.</a>
                    </div>
                </div>
                <div class="icon">
                    <img class="icon-bottom" src="<?php echo base_url('resources/img/icon/icn_user_white.png'); ?>">
                </div>
                <a  href="#" class="small-box-footer das-title">
                    CẤU HÌNH  <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="small-box bg-blue">
                <div class="inner das-inner">
                    <div class="col-lg-12">
                        <img class="sub-menu-item-icon" src="<?php echo $dot_icon; ?>">
                        <a class="das-link addNewTsmsData " href="#">Loại yêu cầu.</a>
                    </div>
                </div>
                <div class="icon">
                    <img class="icon-bottom" src="<?php echo base_url('resources/img/icon/icn_category_white.png'); ?>">
                </div>
                <a  href="#" class="small-box-footer das-title">
                    CẤU HÌNH  <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="small-box bg-purple">
                <div class="inner das-inner">
                    <div class="col-lg-12">
                        <img class="sub-menu-item-icon" src="<?php echo $dot_icon; ?>">
                        <a class="das-link addNewTsmsData " href="#">Loại yêu cầu.</a>
                    </div>
                </div>
                <div class="icon">
                    <img class="icon-bottom" src="<?php echo base_url('resources/img/icon/icn_useravatar_white.png'); ?>">
                </div>
                <a  href="#" class="small-box-footer das-title">
                    CẤU HÌNH  <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
        */ ?>
    </div>
</div>


