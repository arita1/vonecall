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

//index =====================================================================
$route['login'] 							= 'index/login';
$route['logout'] 							= 'index/logout';
$route['terms-and-conditions'] 				= 'index/terms_and_conditions';
$route['privacy-policy'] 					= 'index/privacy_policy';
$route['home'] 								= 'customer/home';
$route['email-us/(:any)']					= 'customer/email_us/$1';

//Popup =====================================================================
$route['popup-rate'] 						= 'index/rate';
$route['popup-access-number'] 				= 'index/access_number';
$route['popup-commission-rate'] 			= 'customer/commission_rates';

//topup =====================================================================
$route['topup']								= 'customer/topup';
$route['country-product/(:any)']			= 'customer/country_product/$1';
$route['topup-recharge/(:any)']				= 'customer/topup_recharge/$1';

$route['topup-usa-rtr']						= 'customer/topup_usa_rtr';
$route['topup-usa-rtr-recharge/(:any)']		= 'customer/topup_usa_rtr_recharge/$1';

$route['topup-usa-pin']						= 'customer/topup_usa_pin';
$route['topup-usa-pin-recharge/(:any)']		= 'customer/topup_usa_pin_recharge/$1';

$route['topup/(:any)/(:any)']				= 'customer/topup/$1/$2';
$route['topup-commission-rate']				= 'customer/topup_commission_rate';


$route['get-product-by-country/(:any)']		= 'customer/get_products_by_country/$1';
$route['get-country-flag/(:any)']			= 'customer/get_country_flag/$1';

// Pinless ==================================================================
$route['pinless']							= 'customer/pinless';
$route['pinless-success/(:any)']			= 'customer/pinless_account_success/$1';
$route['pinless-manage/(:any)']				= 'customer/pinless_alias/$1';
$route['speed-dial/(:any)']					= 'customer/pinless_speed_dial/$1';
$route['calling-history/(:any)']			= 'customer/calling_history/$1';
$route['pinless-recharge/(:any)']			= 'customer/pinless_recharge/$1';
$route['transaction-history/(:any)']		= 'customer/transaction_history/$1';
$route['pinless-rate'] 						= 'customer/pinless_rate';
$route['pinless-access-number']				= 'customer/pinless_access_number';
$route['pinless-call-history']				= 'customer/pinless_calling_history';
$route['pinless-activity']					= 'customer/pinless_activity';
$route['pinless-refund']					= 'customer/pinless_refund';
$route['pinless-refund/(:any)/(:any)']		= 'customer/pinless_refund/$1/$2';
$route['usa-pin-activity']					= 'customer/usa_pin_activity';

// Calling Card =============================================================
$route['buy-calling-card']					= 'customer/buy_calling_card';
$route['buy-calling-card/(:any)']			= 'customer/buy_calling_card/$1';
$route['calling-card-success']				= 'customer/calling_card_success';
$route['calling-card-success/(:any)']		= 'customer/calling_card_success/$1';
$route['calling-card-history']				= 'customer/calling_card_history';
$route['calling-card-rate-sheet']			= 'customer/calling_card_rate_sheet';

//Payment ===================================================================
$route['payment']							= 'store/payment';
$route['balance']							= 'store/store_balance';
$route['refund']							= 'store/refund';
$route['remove']							= 'store/remove';
//Report ===================================================================
$route['sales-report']						= 'store/sales_report';
$route['commission-report']					= 'store/commission_report';
$route['product-list']						= 'store/product_list';
$route['export-list']						= 'store/export_product_list';
$route['payment-report']					= 'store/payment_report';

// Admin ====================================================================
$route['update-password']					= 'store/change_password';

// Return to Admin ==========================================================
$route['return-to-admin']					= 'index/return_to_admin';

//online store landing page

$route['vonecall-store69']					= 'frontend/online_store';
$route['vonecall-rates']					= 'frontend/rates';
$route['vonecall-access-numbers']			= 'frontend/access_numbers';
$route['vonecall-how-it-works']			= 'frontend/how_it_works';
$route['vonecall-faq']			= 'frontend/faq';
$route['vonecall-terms']			= 'frontend/terms';
$route['vonecall-contact-us']			= 'frontend/contact_us';
$route['vonecall-services']			= 'frontend/services';
$route['vonecall-login']			= 'frontend/online_store_login';
$route['vonecall-register']			= 'frontend/online_store_register'; 
$route['vonecall-top-ups']			= 'frontend/all_top_ups';
$route['vonecall-my-account']			= 'frontend/my_account';
$route['vonecall-user-login']			= 'frontend/new_login';
$route['vonecall-user-login/(:any)']			= 'frontend/new_login/$1';
$route['vonecall-user-logout']			= 'frontend/new_user_logout';
$route['vonecall-about-us']			= 'frontend/about_us';
$route['vonecall-update-password']			= 'frontend/update_old_password';
$route['vonecall-update-password/(:any)']			= 'frontend/update_old_password/$1';
$route['vonecall-forgot-password']			= 'frontend/forgot_password';
$route['vonecall-forgot-password/(:any)']			= 'frontend/forgot_password/$1';
$route['vonecall-pinless-account']			= 'frontend/pinless_account';
$route['vonecall-recharge-phone']			= 'frontend/recharge_phone';
$route['vonecall-recharge-phone/(:any)']			= 'frontend/recharge_phone/$1';
$route['vonecall-charge-wallet']			= 'frontend/charge_wallet';
$route['vonecall-transaction-history']			= 'frontend/transaction_history';
$route['vonecall-speed-dails']			= 'frontend/speedDails';
$route['vonecall-speed-dails/(:any)']					= 'frontend/speedDails/$1';
$route['vonecall-numbers-alias']			= 'frontend/aliasNumbers';
$route['vonecall-numbers-alias/(:any)']					= 'frontend/aliasNumbers/$1';
$route['vonecall-calling-history']			= 'frontend/callingHistory';
$route['vonecall-calling-history/(:any)']= 'frontend/callingHistory/$1';
$route['vonecall-mobile-top-ups']= 'frontend/mobile_top_ups';
$route['vonecall-topup-usa-rtr']						= 'frontend/topup_usa_rtr';
$route['vonecall-topup-usa-rtr-recharge/(:any)']		= 'frontend/topup_usa_rtr_recharge/$1';
/* End of file routes.php */
/* Location: ./application/config/routes.php */
$route['__test']			= 'customer/__test';
