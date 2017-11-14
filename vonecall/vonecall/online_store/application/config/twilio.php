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
	                            
	$config['account_sid']   = 'AC2ead61c5cc7caeb15117920e526e8f1f';//'ACb362e131ec46888a9a1393651d1d387d';

	/**
	 * Auth Token
	 **/
	$config['auth_token']    = '9b019848a0c48ed885dd0dc9519b24ca';//'5bd827bd8301b30be00d2cde74af3806';

	/**
	 * API Version
	 **/
	$config['api_version']   = '2010-04-01';

	/**
	 * Twilio Phone Number
	 **/
	$config['number']        = '';
	
/* End of file twilio.php */
