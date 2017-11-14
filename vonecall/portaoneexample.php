<?php
/* Template Name: Portaone */
$numberLogin = '8871831051';
	
	$hostname ="98.158.159.101"; //98.158.159.101
	
	$wsdl_url_session  = "https://$hostname/wsdl/SessionAdminService.wsdl";
	$wsdl_url_account  = "https://$hostname/wsdl/AccountAdminService.wsdl";
	
	$session_client = new SoapClient($wsdl_url_session, array('trace' => true, "exceptions" => 0, "cache_wsdl" => 0));
	//$session_id = $session_client->login("vonecall","q0r@x@!ryd");
	$session_id = $session_client->login("vonecall","vc@@!!557Ee1");
	
	//echo	'<h2>Request</h2><pre>' . htmlspecialchars($session_client->__getLastRequest(), ENT_QUOTES) . '</pre>';
	//echo	'<h2>Response</h2><pre>' . htmlspecialchars($session_client->__getLastResponse(), ENT_QUOTES) . '</pre>';
	
	$auth_info = new SoapHeader(
	        "http://schemas.portaone.com/soap",
	        "auth_info",
	        new SoapVar(
	            array('session_id' => $session_id),
	            SOAP_ENC_OBJECT
	        )
	);
	
	$account_client = new SoapClient($wsdl_url_account, array('trace' => true, "exceptions" => 0, "cache_wsdl" => 0));
	$account_client->__setSoapHeaders($auth_info);
	
	$AddAccountRequest = array("account_info" => array(
									'i_customer' 		=> 34,
									'billing_model' 	=> '-1',
									'i_product' 		=> 27,
									'activation_date' 	=> date('Y-m-d'),
									'id' 				=> 'ani'.$numberLogin,
									'balance' 			=> '0',
									'opening_balance' 	=> '1',
									'i_time_zone' 		=> '260',
									'i_lang' 			=> 'en',
									'login' 			=> $numberLogin,
									'password'			=> 123456,
									'h323_password' 	=> 123456,
									'blocked' 			=> 'N'
								)
							);
							
	$response = $account_client->add_account($AddAccountRequest);
	echo '</pre>';
	echo	'<h2>Request</h2><pre>' . htmlspecialchars($account_client->__getLastRequest(), ENT_QUOTES) . '</pre>';
	echo	'<h2>Response</h2><pre>' . htmlspecialchars($account_client->__getLastResponse(), ENT_QUOTES) . '</pre>';

	echo '<h2>My Call Response</h2><pre>Your New Account ID is : ';
	print_r($response->i_account);

?>