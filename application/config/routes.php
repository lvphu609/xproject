<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "ad_home";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */

// $route['receive'] = "comming_soon/action/receive";
// $route['checkmail'] = "comming_soon/action/check_mail";


$route['admin'] = "ad_home/ad_home";
$route['admin/login'] = "ad_login/ad_login";
$route['admin/auth'] = "ad_login/ad_login/check_login";
$route['admin/logout'] = "ad_login/ad_login/logout";
$route['admin/config/post_types'] = "ad_config/ad_config/config_post_types";
$route['admin/config/post_types/create'] = "ad_config/ad_config/create_post_type";
$route['admin/config/post_types/edit/(:any)'] = "ad_config/ad_config/edit_post_type/$1";
$route['admin/config/post_types/store'] = "ad_config/ad_config/store_post_type";
$route['admin/config/post_types/update'] = "ad_config/ad_config/update_post_type";
$route['admin/config/post_types/delete'] = "ad_config/ad_config/delete_post_type";





