<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>


<?php /*
<?php echo form_open($this->uri->uri_string()); ?>
<table>
	<?php if ($use_username) { ?>
	<tr>
		<td><?php echo form_label('Username', $username['id']); ?></td>
		<td><?php echo form_input($username); ?></td>
		<td style="color: red;"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td><?php echo form_label('Email Address', $email['id']); ?></td>
		<td><?php echo form_input($email); ?></td>
		<td style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label('Password', $password['id']); ?></td>
		<td><?php echo form_password($password); ?></td>
		<td style="color: red;"><?php echo form_error($password['name']); ?></td>
	</tr>
	<tr>
		<td><?php echo form_label('Confirm Password', $confirm_password['id']); ?></td>
		<td><?php echo form_password($confirm_password); ?></td>
		<td style="color: red;"><?php echo form_error($confirm_password['name']); ?></td>
	</tr>

	<?php if ($captcha_registration) {
		if ($use_recaptcha) { ?>
	<tr>
		<td colspan="2">
			<div id="recaptcha_image"></div>
		</td>
		<td>
			<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="recaptcha_only_if_image">Enter the words above</div>
			<div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
		</td>
		<td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
		<td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
		<?php echo $recaptcha_html; ?>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="3">
			<p>Enter the code exactly as it appears:</p>
			<?php echo $captcha_html; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
		<td><?php echo form_input($captcha); ?></td>
		<td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
	</tr>
	<?php }
	} ?>
</table>
<?php echo form_submit('register', 'Register'); ?>
<?php echo form_close(); ?>
*/ ?>





<?php
$resources = base_url().'resources/';
$url = base_url().'index.php/';
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo $resources ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $resources ?>css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="<?php echo $resources ?>css/captcha.css">
    <script type="text/javascript" src="<?php echo $resources ?>js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $resources ?>js/bootstrap.min.js"></script>
    <title></title>
</head>
<body>
  
  
<?php echo form_open($this->uri->uri_string()); ?>  
  <div class="row vertical-offset-100">
     <div class="col-md-4 col-md-offset-4">
       <div class="panel panel-default">
         <div class="panel-heading">
           <div class="avatar"></div>
         </div>
         <div class="panel-body">
           <form id="tsms-login-form" method="post" accept-charset="UTF-8" role="form" action="<?php echo base_url().'index.php/login/check_login' ?>">
             <fieldset>
               <div class="form-group">
                  <?php echo form_input($username,'','class="form-control" placeholder="User name" type="text"'); ?>
                 <div style="color: red;">
                    <?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?>
                  </div>
               </div>
               <div class="form-group">
                 <?php echo form_input($email,'','class="form-control" placeholder="Email" type="text"'); ?>
                  <div style="color: red;">
                    <?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?>
                  </div>
               </div>
               <div class="form-group">
                  <?php echo form_password($password,'','class="form-control" placeholder="Password"'); ?>
                  <div style="color: red;">
                    <?php echo form_error($password['name']); ?>
                  </div>
               </div>
               <div class="form-group">
                <?php echo form_password($confirm_password,'','class="form-control" placeholder="Confirm Password"'); ?>
                  <div style="color: red;">
                    <?php echo form_error($confirm_password['name']); ?>
                  </div>
                </div>
               <div class="form-group">
   
                <?php  if ($captcha_registration) {
                      if ($use_recaptcha) { ?>
                    <tr>
                      <td colspan="2">
                        <div id="recaptcha_image"></div>
                      </td>
                      <td>
                        <a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
                        <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
                        <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="recaptcha_only_if_image">Enter the words above</div>
                        <div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
                      </td>
                      <td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
                      <td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
                      <?php echo $recaptcha_html; ?>
                    </tr>
                    <?php } else { ?>
                    <tr>
                      <td colspan="3">
                        <p>Enter the code exactly as it appears:</p>
                        <?php echo $captcha_html; ?>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
                      <td><?php echo form_input($captcha); ?></td>
                      <td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
                    </tr>
                    <?php }
                    } ?>
                 
                 
                 <?php /*
                 <script type="text/javascript">
                    var RecaptchaOptions = {
                      theme : 'custom',
                      custom_theme_widget: 'recaptcha_widget'
                    };
                    </script>
                    <div id="recaptcha_widget" style="display:none" class="recaptcha_widget col-lg-offset-3">
                      <div id="recaptcha_image"></div>
                      <div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect. Please try again.</div>

                      <div class="recaptcha_input">
                        <label class="recaptcha_only_if_image" for="recaptcha_response_field">Nhap ma bao mat vao o ben duoi:</label>
                        <label class="recaptcha_only_if_audio" for="recaptcha_response_field">Enter the numbers you hear:</label>

                        <input type="text" id="recaptcha_response_field" name="recaptcha_response_field">
                      </div>

                      <ul class="recaptcha_options">
                        <li>
                          <a href="javascript:Recaptcha.reload()">
                            <i class="icon-refresh">
                              <div class="icon-captcha"></div>
                            </i>
                            <span class="captcha_hide">Get another CAPTCHA</span>
                          </a>
                        </li>
                        <li class="recaptcha_only_if_image">
                          <a href="javascript:Recaptcha.switch_type('audio')">
                            <i class="icon-volume-up">
                              <div class="icon-captcha"></div>
                            </i><span class="captcha_hide"> Get an audio CAPTCHA</span>
                          </a>
                        </li>
                        <li class="recaptcha_only_if_audio">
                          <a href="javascript:Recaptcha.switch_type('image')">
                            <i class="icon-picture">
                              <div class="icon-captcha"></div>
                            </i><span class="captcha_hide"> Get an image CAPTCHA</span>
                          </a>
                        </li>
                        <li>
                          <a href="javascript:Recaptcha.showhelp()">
                            <i class="icon-question-sign">
                              <div class="icon-captcha"></div>
                            </i><span class="captcha_hide"> Help</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                   

                    <script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6LflJe8SAAAAACAAX6nx4elELOpmVGAe7S_2ee7S"></script>
                    <noscript>
                      <iframe src="http://www.google.com/recaptcha/api/noscript?k=6LflJe8SAAAAAH8c-ws0Lps8rSijEKJFElWzuSu7" height="300" width="500" frameborder="0"></iframe><br>
                      <textarea name="recaptcha_challenge_field"></textarea>
                      <input type="hidden" name="recaptcha_response_field" value="manual_challenge">  
                    </noscript>
                 */ ?>
               </div>
               <?php echo form_error($captcha['name']); ?>
               <input class="btn btn-lg btn-success btn-block" type="submit" value="đăng ký">
             </fieldset>
          </form>     
         </div>
     </div>
   </div>
	</div>
<?php echo form_close(); ?>  
  
  
  
  
  
</body>
</html>  