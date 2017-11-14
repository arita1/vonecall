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

$route['default_controller'] = "index";
$route['404_override'] = '';

//index
$route['login'] 					= 'index/login';
$route['logout'] 					= 'index/logout';
$route['home'] 	   					= "index/home";
$route['send-text-message'] 		= "index/send_text";

// Groups
$route['all-groups'] 				= "group/groups";
$route['delete-group'] 				= "group/delete_group";
$route['group-contacts'] 			= "group/group_contacts";
$route['group-contacts/(:any)'] 	= "group/group_contacts/$1";
$route['delete-group-contacts/(:any)'] 	= "group/delete_group_contacts/$1";

//Contacts
$route['contacts']	 				= "contact/all_contacts";
$route['add-contacts']	 			= "contact/contacts";
$route['add-contacts/(:any)']		= "contact/contacts/$1";
$route['contact-import']			= "contact/import_contacts";
$route['delete-contact'] 			= "contact/delete_contact";
$route['update-contact/(:any)']		= "contact/update_contact/$1";
$route['optout-contacts']			= "contact/optout_contact_list";
$route['optout-contact']			= "contact/optout_contact";
$route['optout-success']			= "contact/optout_success";

// Settings
$route['text-sender'] 				= "admin/text_sender";

 
/* End of file routes.php */
/* Location: ./application/config/routes.php */