<?php


// Required if your environment does not handle autoloading
require 'vendor/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;
define('ACCOUNT_SID','ACd856fc188be27974f0034b987d1739f2');
define('ACCOUNT_TOKEN','7b10af16e43e3b01423f101fdde648ef');

class twilio{

	private $sid;
	private $token;
	private $client;

	function __construct(){
		

	}
// Your Account SID and Auth Token from twilio.com/console

	public function sms( $to , $body){
		$sid = ACCOUNT_SID;
		$token = ACCOUNT_TOKEN;
		$client = new Client($sid, $token);
		// Use the client to do fun stuff like send text messages!
			$client->messages->create(
   		 // the number you'd like to send the message to
		    $to,
		    array(
        // A Twilio phone number you purchased at twilio.com/console
		        'from' => '+14138315626 ',
		        // the body of the text message you'd like to send
		        'body' => $body
		    )
		);
			if($client){
				return true;
			}else{
				return false;
			}
	 
	}// method close
}// class close




?>