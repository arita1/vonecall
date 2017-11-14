<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	* Name:  Twilio
	*
	* Author: Ben Edmunds
	*		  ben.edmunds@gmail.com
	*         @benedmunds
	*
	* Location:
	*
	* Created:  03.29.2011
	*
	* Description:  Twilio configuration settings.
	*
	*
	*/

	/**
	 * Mode ("sandbox" or "prod")
	 **/
	$config['mode']   = 'prod';

	/**
	 * Account SID
	 **/
	$config['account_sid']   = 'AC96a8ebe69273bbb1426d0f55ddb83a7f';//'ACb362e131ec46888a9a1393651d1d387d';

	/**
	 * Auth Token
	 **/
	$config['auth_token']    = 'f7b72a20c75619b56d6d85d0a89f9ee8';//'5bd827bd8301b30be00d2cde74af3806';

	/**
	 * API Version
	 **/
	$config['api_version']   = '2010-04-01';

	/**
	 * Twilio Phone Number
	 **/
	$config['number']        = '';
	
/* End of file twilio.php */