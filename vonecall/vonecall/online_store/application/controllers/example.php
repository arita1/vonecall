<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Example usage of Authorize.net's
 * Advanced Integration Method (AIM)
 */
class Example extends CI_Controller
{
	
	private $error = array();
	private $data = array();
	private $agentID = 0;
	
	function __construct() {
		parent::__construct();
		
		$this->load->model('customer_model');  
		$this->load->model('payment_model');  
		$this->load->model('agent_model');
		$this->load->model('admin_model');
	
		
		//set agentID
		$this->agentID = (int)$this->session->userdata('store_userid');
	}
	
	// Example auth & capture of a credit card
	public function index()
	{
		// Authorize.net lib
		$this->load->library('authorize_net');

		$auth_net = array(
			'x_card_num'			=> '4111111111111111', // Visa
			'x_exp_date'			=> '12/22',
			'x_card_code'			=> '123',
			'x_description'			=> 'A test transaction',
			'x_amount'				=> '20',
			'x_first_name'			=> 'John',
			'x_last_name'			=> 'Doe',
			'x_address'				=> '123 Green St.',
			'x_city'				=> 'Lexington',
			'x_state'				=> 'KY',
			'x_zip'					=> '40502',
			'x_country'				=> 'US',
			'x_phone'				=> '555-123-4567',
			'x_email'				=> 'test@example.com',
			'x_customer_ip'			=> $this->input->ip_address(),
			);
		$this->authorize_net->setData($auth_net);

		// Try to AUTH_CAPTURE
		if( $this->authorize_net->authorizeAndCapture() )
		{
			echo '<h2>Success!</h2>';
			echo '<p>Transaction ID: ' . $this->authorize_net->getTransactionId() . '</p>';
			echo '<p>Approval Code: ' . $this->authorize_net->getApprovalCode() . '</p>';
		}
		else
		{
			echo '<h2>Fail!</h2>';
			// Get error
			echo '<p>' . $this->authorize_net->getError() . '</p>';
			// Show debug data
			$this->authorize_net->debug();
		}
	}

	private function getMode( $mode, $username, $password ){
		$getMode     = $this->agent_model->getSettings(array('settingKey' => $mode));
		$getUsername = $this->agent_model->getSettings(array('settingKey' => $username));
		$getPassword = $this->agent_model->getSettings(array('settingKey' => $password));			
		
		$username = $getUsername->settingValue;
		$password = $getPassword->settingValue;
		if($getMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
		
		return array('mode' => $sandbox, 'username' => $username, 'password' => $password);		
	}

	private function getAccountDetailsByPortaone($phone, $loginSession){
		$getAccountRequest = array('login' => $phone);
		$api 	= new PortaOneWS("AccountAdminService.wsdl");
		$result = $api->getInfo($getAccountRequest, $loginSession);
		return $result->account_info;
	}
	
	// Pinless Private ==========
	private function portaoneSession(){
		// Get Pinless Mode
		$pinlessDetails = $this->getMode('pinlessMode', 'pinlessUsername', 'pinlessPassword');
		$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
		$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password']));
		
		// Set login session in Session variable
	    $this->session->set_userdata('portaoneSession', $loginSession);
		
		return $loginSession;
	}
	
	public function getAccountDetailsFromPortaOne()
	{
		require 'PortaOneWS.php';
		$loginSession = $this->portaoneSession();
			
		echo 'sess--';print_r($loginSession);
		$phone='2012337812';
		
		$getAccountResponse = $this->getAccountDetailsByPortaone($phone, $loginSession);	// Get Account details from Portaone 					
		echo "<pre>";print_r($getAccountResponse);die;
	}
	
	
	public function getAccountList()
	{
		require 'PortaOneWS.php';
		$loginSession = $this->portaoneSession();
			
		echo 'sess--';print_r($loginSession);
		$phone='6124231098';
		
		
		$getAccountListRequest = array('offset'=>0,'limit'=>10,'i_customer'=>'34','i_batch'=>'');
		$api 	= new PortaOneWS("AccountAdminService.wsdl");
		$result = $api->getAccountLists($getAccountListRequest, $loginSession);
					
		echo "<pre>";print_r($result);die;
	}



	
}

/* EOF */