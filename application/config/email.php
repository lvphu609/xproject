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
$config['mailtype'] = 'text';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'ngohongle1994@gmail.com'; // change it to yours user
$config['smtp_pass'] = 'abc123$%^'; // change it to yours password
$config['wordwrap'] = TRUE;
$config['validate'] = TRUE;
$config['mailpath'] = '';
$config['mail_from'] ='ngohongle1994@gmail.com'; // change it to yours server mail


/* End of file email.php */
/* Location: ./application/config/email.php */