<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo base_url('admin'); ?>">X-Project</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right hidden-xs hidden-sm hidden-md">
        <li class="dropdown">
            <a class="dropdown-toggle user-profile" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                    <a href="<?php echo base_url('admin/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> <?php echo $this->lang->line('logout_text'); ?></a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                    </div>
                    <!-- /input-group -->
                </li>
                <li>
                    <a href="<?php echo base_url('admin'); ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-cog fa-fw"></i> <?php echo $this->lang->line('menu_text_config'); ?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?php echo base_url('admin/config/post_types'); ?>"><i class="fa fa-list-alt fa-fw"></i> <?php echo $this->lang->line('menu_text_post_types'); ?></a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li class="hidden-lg">
                    <a href="<?php echo base_url('admin/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> <?php echo $this->lang->line('logout_text'); ?></a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header"><?php echo !empty($page_header) ? $page_header : ''; ?></h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>