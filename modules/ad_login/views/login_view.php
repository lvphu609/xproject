<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $this->lang->line('panel_name'); ?></h3>
            </div>
            <div class="panel-body">
                <form role="form">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('username_placeholder'); ?>" name="username" type="username" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="<?php echo $this->lang->line('password_placeholder'); ?>" name="password" type="password" value="">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="remember" type="checkbox" value="Remember Me"><?php echo $this->lang->line('remember_me'); ?>
                            </label>
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <a href="index.html" class="btn btn-lg btn-success btn-block btn-login"><?php echo $this->lang->line('button_login'); ?></a>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>


