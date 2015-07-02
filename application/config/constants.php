<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');



define('HEADER_SUCCESS',    								    200); // when the request is successful
define('HEADER_PARAMETER_MISSING_INVALID', 						400); // when required params are invalid or missing
define('HEADER_UNAUTHORIZED', 									401); // when the provided authentication details doesnt have access to a resource
define('HEADER_FORBIDDEN',										403); // when authentication details not provided to access resource
define('HEADER_NOT_FOUND',										404); // when resource is not found

define('HEADER_INTERNAL_SERVER_ERROR', 						500); // when something unexpected happened on the server

define('TYPE_PARAM_EMAIL',                  'EMAIL');
define('TYPE_PARAM_DATE', 				    'DATE');
define('TYPE_PARAM_DATETIME', 				'DATETIME');
define('TYPE_PARAM_TIME', 				    'TIME');
define('TYPE_PARAM_NUMBER', 				'NUMBER');
define('TYPE_PARAM_TEXT', 					'TEXT');

define('TYPE_PARAM_LIMIT_20', 					20);
define('TYPE_PARAM_LIMIT_30', 					30);
define('TYPE_PARAM_LIMIT_50', 					50);
define('TYPE_PARAM_LIMIT_100', 					100);
define('TYPE_PARAM_LIMIT_150', 					150);
define('TYPE_PARAM_LIMIT_200', 					200);
define('TYPE_PARAM_LIMIT_250', 					250);
define('TYPE_PARAM_LIMIT_500', 					500);


define('ROW_PER_PAGE', 					10);


define('POST_QUALITY_STATE_EXCESS',         'Excess');
define('POST_QUALITY_STATE_EXPIRED',        'Expired');
define('POST_QUALITY_STATE_SPOILED',        'Spoiled');

define('POST_PRICE_STATE_PAY',              'Pay');
define('POST_PRICE_STATE_DONATE',           'Donate');
define('POST_PRICE_STATE_CHARGE',           'Charge');

define('POST_PRICE_TYPE_UNIT',              'Unit');
define('POST_PRICE_TYPE_TOTAL',             'Total');

define('POST_TRANSPORT_STATE_PICKED_UP',    'Picked up');
define('POST_TRANSPORT_STATE_DROPPED_OFF',  'Dropped off');
define('POST_TRANSPORT_STATE_EITHER',       'Either');

define('POST_STATUS_POSTED_MATCHED',        'Active'); //user define
define('POST_STATUS_POSTED',                'Posted');
define('POST_STATUS_MATCHED',               'Matched');
define('POST_STATUS_COMPLETED',             'Completed');


/* End of file constants.php */
/* Location: ./application/config/constants.php */

/*App ID for pushing notify*/
//define('DEFINE_ANDROID_APP_ID','AIzaSyBLlt3QBbXR4K-ie8kfW2NCRUgpv3m-Xs4');
define('DEFIND_GOOGLE_API_KEY','AIzaSyBLlt3QBbXR4K-ie8kfW2NCRUgpv3m-Xs4');
define('DEFIND_GOOGLE_API_KEY_SERVER','AIzaSyDptqMgUz_req_mH6IbI6liMEknGfEE3Xs');

define('DEFIND_PER_PAGE_DEFAULT',10);

//api results define string
define('API_STATUS','status');
define('API_MESSAGE','message');
define('API_RESULTS','results');
define('API_VALIDATION','validation');
define('API_PAGINATION','pagination');
define('API_PAGE','page');
define('API_ROW_PER_PAGE','row_per_page');
define('API_TOTAL_PAGE','total_page');
define('API_SUCCESS','success');
define('API_FAILURE','failure');
define('API_ERROR','error');