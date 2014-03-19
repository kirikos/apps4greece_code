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

$route['thessaloniki_appsforgreece/register_validation']="app_register_validation";
$route['thessaloniki_appsforgreece/login']="app_login";
$route['thessaloniki_appsforgreece/admin_login']="app_admin_login";
$route['thessaloniki_appsforgreece/admin_logout']="app_admin_logout";
$route['thessaloniki_appsforgreece/add_article']="app_add_article";
$route['thessaloniki_appsforgreece/save_article']="app_save_article";
$route['thessaloniki_appsforgreece/admin_console']="app_admin_events";
$route['thessaloniki_appsforgreece/more_events']="app_admin_more_events";
$route['thessaloniki_appsforgreece/upload']="app_event_upload";
$route['thessaloniki_appsforgreece/delete_photo']="app_delete_photo";
$route['thessaloniki_appsforgreece/send_notification']="app_send_notification";
$route['thessaloniki_appsforgreece/mobile_get_news']="app_mobile_news";
$route['thessaloniki_appsforgreece/mobile_get_more_news']="app_mobile_more_news";
$route['thessaloniki_appsforgreece/mobile_get_event']="app_mobile_get_event";
$route['thessaloniki_appsforgreece/mobile_events']="app_mobile_events";
$route['thessaloniki_appsforgreece/mobile_more_events']="app_mobile_more_events";
$route['thessaloniki_appsforgreece/mobile_citizens_notifications']="app_mobile_citizens_notification_event";
$route['thessaloniki_appsforgreece/mobile_officials_notifications']="app_mobile_officials_notification_event";
$route['thessaloniki_appsforgreece/show_articles']="app_admin_articles";
$route['thessaloniki_appsforgreece/more_articles']="app_admin_more_articles";
$route['thessaloniki_appsforgreece/delete_aricle']="app_delete_article";
$route['default_controller'] = "welcome";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */