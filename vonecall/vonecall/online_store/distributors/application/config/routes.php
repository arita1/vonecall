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
$route['login'] 							= 'index/login';
$route['logout'] 							= 'index/logout';
$route['home'] 								= 'agent/home';
$route['commission-rate'] 					= 'agent/commission_rate';

//store
$route['add-new-store'] 					= 'agent/add_new_agent';
$route['store-account-manager'] 			= 'agent/agent_account_manager';
$route['store-summary'] 					= 'agent/agent_summary';
$route['store-delete'] 						= 'agent/delete';
$route['store/profile/(:any)'] 				= 'agent/profile/$1';
$route['store/commission/(:any)'] 			= 'agent/commission/$1';
$route['store/commission/(:any)/(:any)'] 	= 'agent/commission/$1/$2';
$route['store/payment/(:any)'] 				= 'agent/payment/$1';
$route['store/balance-report/(:any)'] 		= 'agent/balance_report/$1';
$route['store/sale-query/(:any)'] 			= 'agent/sale_query/$1';
$route['store/delete-payment/(:any)'] 		= 'agent/delete_payment/$1';
$route['store/receipt/(:any)'] 				= 'agent/payment_receipt/$1';
$route['store/reset-commission']			= 'agent/reset_commission';

//Sub-Distributor
$route['sub-distributor-account-manager'] 	= 'agent/sub_distributor_account_manager';
$route['add-new-distributor'] 				= 'agent/add_new_distributor';
$route['distributor/profile/(:any)']		= 'agent/distributor_profile/$1';
$route['distributor/delete-distributor']	= 'agent/delete_distributor';

$route['sub-distributor-stores'] 			= 'agent/sub_distributor_store_account_manager';
$route['sub-store/profile/(:any)']			= 'agent/sub_distributor_store_profile/$1';

//general
$route['reports'] 							= 'agent/sales_report';
$route['commission-report'] 				= 'agent/commission_report';
$route['stores']			 				= 'agent/stores';
$route['product-list'] 						= 'agent/product_list';
$route['balance-report']					= 'agent/balance_report';
$route['subdist-report']					= 'agent/sub_distributor_report';
$route['get-store-by-subdist/(:any)'] 		= 'agent/get_store_by_subdist/$1';
$route['popup-rate'] 						= 'agent/popup_pinless_rate';
$route['popup-access-number'] 				= 'agent/popup_access_number';
$route['payment']		 					= 'admin/admin_payment';
$route['product-list']						= 'agent/commission_rate_report';
$route['export-products']					= 'agent/export_product_list';

//admin
$route['change-password'] 					= 'agent/change_password';
$route['profile']		 					= 'admin/admin_profile';
$route['payment-adjust'] 					= 'admin/admin_payment_adjust';

// Return to Admin ==========================================================
$route['return-to-admin']					= 'index/return_to_admin';


/* End of file routes.php */
/* Location: ./application/config/routes.php */