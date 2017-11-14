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

//index =====================================================================================
$route['login'] 							= 'index/login';
$route['logout'] 							= 'index/logout';
$route['register'] 							= 'index/register';
$route['add-phone-number-by-sms'] 			= 'index/add_phone_number_by_sms';
$route['local-access-number'] 				= 'index/local_access_number';

//Manage Users ==============================================================================

	#### Distributor ####
$route['add-new-destributor'] 				= 'agent/add_new_destributor';
$route['destributor-manager'] 				= 'agent/destributor_manager';
$route['delete-destributor'] 				= 'agent/delete_destributor';
$route['destributor/profile/(:any)'] 		= 'agent/destributor_profile/$1';
$route['destributor/commission/(:any)'] 	= 'agent/destributor_commission/$1';
$route['destributor/delete-commission/(:any)'] 	= 'agent/account_rep_commission_delete/$1';
$route['destributor/change-destributor-status'] = 'agent/destributor_status';

	#### Sub-Distributors ####
$route['sub-destributor'] 					= 'agent/sub_destributor';

	#### Admin ####
$route['admin-manager']						= 'admin/manage_admin';
$route['add-new-admin']						= 'admin/add_admin';
$route['delete-admin']	 					= 'admin/delete';
$route['admin-change-password'] 			= 'admin/admin_change_password';
$route['change-admin-status']				= 'admin/admin_status';
$route['admin-profile/(:any)'] 				= 'admin/admin_profile/$1';
$route['admin-logs']	 					= 'admin/admin_logs';
$route['export-report']	 					= 'admin/admin_export_report';
$route['log-management']					= 'admin/admin_log_management';
$route['ryd-admin']							= 'admin/general_settings';
$route['banner-message']					= 'admin/banner_message';
$route['banner-message/(:any)']				= 'admin/banner_message/$1';
$route['general-settings']					= 'admin/general_settings';
$route['ppn-mode']							= 'admin/ppn_mode';
$route['pinless-mode']						= 'admin/pinless_mode';
$route['text-message']						= 'admin/text_message';
$route['store-header']						= 'admin/store_header';
$route['add-funds']							= 'admin/add_funds_by_admin';
$route['add-funds-to-distributor']			= 'admin/add_funds_to_dist_by_admin';
	
	#### Stores ####
$route['store-manager'] 					= 'agent/agent_account_manager';
$route['add-new-store'] 					= 'agent/add_new_agent';
$route['store/profile/(:any)'] 				= 'agent/profile/$1';
$route['store-delete'] 						= 'agent/delete';
$route['store/commission/(:any)'] 			= 'agent/commission_agent/$1';
$route['store/commission/(:any)/(:any)']	= 'agent/commission_agent/$1/$2';
$route['store/delete-commission/(:any)'] 	= 'agent/commission_delete/$1';

	#### Customers ####
$route['customer-manager'] 					= 'customer/customer_account_manager';
$route['delete-customer'] 					= 'customer/customer_delete';

// Products
$route['product'] 							= 'admin/product';
$route['product-type'] 						= 'admin/product_type';
$route['search-product'] 					= 'admin/search_product_by_key';
$route['search-product-by-list'] 			= 'admin/product';

// Uploads ==================================================================================
$route['pinless-rates'] 					= 'admin/pinless_rates';
$route['pinless-access-number']				= 'admin/pinless_access_number';
$route['export-access-record']				= 'admin/export_access_record';
$route['export-rates']						= 'admin/export_rates';
$route['products-upload']					= 'admin/products_upload';
$route['export-products']					= 'admin/export_products';
$route['product-logo-upload']				= 'admin/product_logo_upload';
$route['country-flag-upload']				= 'admin/country_flag_upload';
$route['calling-card-upload']				= 'admin/calling_card_upload';

// Calling cards
$route['card-batch']						= 'admin/calling_card_batch';
$route['delete-card-batch']					= 'admin/delete_calling_card_batch';
$route['calling-cards']						= 'admin/calling_cards';
$route['calling-cards-instructions']		= 'admin/calling_cards_instruction';
$route['upload-local-access']				= 'admin/calling_cards_upload_local_access';

// Promotion
$route['promotion']	 						= 'admin/promotion';
$route['promotion-message']					= 'admin/promotion_message';

//Portaone ==================================================================================
$route['portaone']							= 'portaone/index';
$route['portaone/new-customer']				= 'portaone/new_customer';
$route['portaone/new-account']				= 'portaone/new_account';
$route['portaone/add-line']					= 'portaone/add_alias';
$route['portaone/get-alias']				= 'portaone/get_alias';
$route['portaone/delete-alias']				= 'portaone/delete_alias';
$route['portaone/account-info']				= 'portaone/account_info';
$route['portaone/account-balance']			= 'portaone/account_balance';
$route['portaone/terminate-account']		= 'portaone/terminate_account';
$route['portaone/recharge-account']			= 'portaone/recharge_account';
$route['portaone/recharge-history']			= 'portaone/recharge_history';
$route['portaone/get-xdr']					= 'portaone/cdr_details';
$route['portaone/speed-dial']				= 'portaone/speed_dial';
$route['portaone/get-speed-dial']			= 'portaone/get_speed_dial';
$route['portaone/speed-dial-delete']		= 'portaone/delete_speed_dial';
$route['portaone/refund']					= 'portaone/refund';
$route['portaone/accounts-reports']			= 'portaone/account_reports';
$route['portaone/cdr-reports']				= 'portaone/cdr_reports';
$route['portaone/product-list']				= 'portaone/get_product_list';

// PPN ======================================================================================
$route['ppn']								= 'prepaynation/index';
$route['ppn/carrier-list']					= 'prepaynation/carrier_list';
$route['ppn/product-list']					= 'prepaynation/product_list';
$route['ppn/topup-recharge']				= 'prepaynation/topup_recharge';
$route['ppn/topup-pin']						= 'prepaynation/topup_pin';
$route['ppn/admin-balance']					= 'prepaynation/admin_balance';
$route['ppn/export']						= 'prepaynation/export_products';

// Testing ==================================================================================
$route['test-commission']					= 'testing/test_commission';

// Reports ==================================================================================
$route['reports/sales']						= 'admin/reports';
$route['reports/commission-report']			= 'admin/commission_report';
$route['reports/sub-distributor-report']	= 'admin/sub_distributor_report';
$route['reports/store-payment']				= 'admin/store_payments_report';
$route['reports/distributor-payment']		= 'admin/distributor_payments_report';
$route['reports/store-balance']				= 'admin/store_balance_report';
$route['reports/distributor-balance']		= 'admin/distributor_balance_report';

// Payments =================================================================================
$route['authorize-mode']					= 'admin/authorize_mode';
	
/*
//store =====================================================================================
$route['store-summary'] 					= 'agent/agent_summary';
$route['store-collection'] 					= 'agent/collection';
$route['store-update-enabled'] 				= 'agent/update_enabled';
$route['store/commission/(:any)'] 			= 'agent/commission/$1';
$route['store/delete-commission/(:any)'] 	= 'agent/commission_delete/$1';
$route['store/payment/(:any)'] 				= 'agent/payment/$1';
$route['store/balance-report/(:any)'] 		= 'agent/balance_report/$1';
$route['store/sale-query/(:any)'] 			= 'agent/sale_query/$1';

//rep =======================================================================================
$route['add-new-account-rep'] 				= 'agent/add_new_account_rep';
$route['account-rep-manager'] 				= 'agent/account_rep_manager';
$route['delete-account-rep'] 				= 'agent/delete_account_rep';
$route['account-rep/profile/(:any)'] 		= 'agent/account_rep_profile/$1';
$route['account-rep/commission/(:any)'] 	= 'agent/account_rep_commission/$1';
$route['account-rep/sale-report/(:any)'] 	= 'agent/account_rep_sale_report/$1';

//customer ==================================================================================
$route['payment']							= 'customer/payment';
$route['promotion']							= 'customer/promotion';
$route['promotion-wireless']				= 'customer/promotion_wireless';
$route['customer-account-manager'] 			= 'customer/customer_account_manager';
$route['sales-report'] 						= 'customer/sales_report';
$route['customer/profile/(:any)'] 			= 'customer/profile/$1';
$route['customer/call-details/(:any)'] 		= 'customer/call_details/$1';
$route['customer/add-modify-phone/(:any)'] 	= 'customer/add_modify_phone/$1';
$route['customer/add-modify-pin/(:any)'] 	= 'customer/add_modify_pin/$1';
$route['customer/payment-history/(:any)'] 	= 'customer/payment_history/$1';
$route['customer/recharge-account/(:any)'] 	= 'customer/recharge_account/$1';
$route['customer/speed-dial/(:any)'] 		= 'customer/speed_dial/$1';
$route['customer/view-pin/(:any)'] 			= 'customer/view_pin/$1';

//admin	=====================================================================================
$route['delete-employee'] 					= 'admin/delete';
$route['message'] 							= 'admin/message';
$route['rate'] 								= 'admin/rate';
$route['access-number'] 					= 'admin/access_number';
$route['access-number-dollar-phone'] 		= 'admin/access_number_dollar_phone';
$route['product'] 							= 'admin/product';
$route['didrate'] 							= 'admin/didrate';
$route['didrate/(:any)'] 					= 'admin/didrate_edit/$1';
$route['wireless-plan'] 					= 'admin/wireless_plan';
//$route['customer/profile/(:any)'] 		= 'customer/index/$1';
*/


/* End of file routes.php */
/* Location: ./application/config/routes.php */