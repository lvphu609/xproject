<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $this->lang->line('panel_name'); ?></h3>
            </div>
            <div class="panel-body">
                 <?php echo form_open(base_url('admin/auth'), array('method' => 'post', 'id' => 'frm_login')); ?>
                    <fieldset>
                        <div class="form-group <?php echo !empty(form_error('username')) ? 'has-error' : '' ?>">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('username_placeholder'); ?>" name="username" type="username" autofocus value="<?php echo !empty(set_value('username')) ? set_value('username') : ''; ?>">
                        </div>
                        <div class="form-group <?php echo !empty(form_error('password')) ? 'has-error' : '' ?>">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('password_placeholder'); ?>" id="password" type="password" value="">
                            <input type="hidden" id="password_md5" name="password">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="remember" type="checkbox" value="Remember Me"><?php echo $this->lang->line('remember_me'); ?>
                            </label>
                        </div>
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-warning fade in">
                                <?php echo $this->lang->line('login_failure'); ?>
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>
                        <?php endif;?>
                        <button type="submit" class="btn btn-lg btn-success btn-block btn-login"><?php echo $this->lang->line('button_login'); ?></button>
                    </fieldset>
                 <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


