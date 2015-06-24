<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/
  $config['mailtype'] = 'html';
  $config['charset'] = 'utf-8';
  $config['newline'] = "\r\n";

  $config['smtp_host'] = 'smtp.googlemail.com';
  $config['smtp_user'] = 'devs@innoria.com';
  $config['smtp_pass'] = 'Hoinguoi#di';
  $config['smtp_port'] = '465';

  $config['protocol']		= 'smtp';
  $config['smtp_crypto']	= 'ssl';

  $config['full_name'] = '【X-Project】no-reply email';

/* End of file email.php */
/* Location: ./application/config/email.php */