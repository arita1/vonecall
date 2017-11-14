<?php
class Portaone extends CI_Controller {
	
	private $data = array();
	private $error = array();
	
	function __construct() {
		parent::__construct();
		$this->load->helper(array('form','format'));
		$this->load->model('admin_model');
		
		// Get Message
		if($this->session->userdata('message')){
			$this->data['message'] = $this->session->userdata('message');
			$this->session->unset_userdata('message');	
		}
				
	}
	public function index() {
		
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'portaone';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	
	// Accounts =======================================================================
	public function new_customer() {
		require 'PortaOneWS.php';
		
		$portaAuth 	 	= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['name'])) < 1) {
				$this->error['name'] = 'Customer Name is required!';
			}			
			if (strlen(trim($_POST['phone'])) < 1 || !is_numeric(trim($_POST['phone']))) {
				$this->error['phone'] = 'Valid Phone Number is required!';
			}
			
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$AddCustomerRequest = array("customer_info" => array(
								                'name' 				=> $_POST['name'],
								                'i_customer_type' 	=> 1,
								                'balance' 			=> 0,
								                'opening_balance'	=> 1,
								                'i_time_zone' 		=> 260,
								                'i_lang' 			=> 'pl',
								                'login' 			=> $_POST['phone'],
								                'blocked' 			=> 'N',
								                'iso_4217' 			=> 'USD',
								                'i_customer_class' 	=> 1 )
							        );
				
				$api    = new PortaOneWS("CustomerAdminService.wsdl");
				$result = $api->addCustomer($AddCustomerRequest, $loginSession);
				
				if (is_soap_fault($result)) {
				    trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
				}		
				$this->session->set_userdata('message', $result);
				redirect(site_url('portaone/new-customer'));
			}
			
			$this->data += $_POST;
			
		}
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'addcustomer';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	
	public function new_account() {
		
		require 'PortaOneWS.php';
		
		$portaAuth = $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['country'])) < 1) {
				$this->error['country'] = 'Country code is required!';
			}			
			if (strlen(trim($_POST['phone'])) != 10 || !is_numeric(trim($_POST['phone']))) {
				$this->error['phone'] = 'Valid Phone Number is required!';
			}			
			if (strlen(trim($_POST['password'])) < 1) {
				$this->error['password'] = 'Password is required!';
			}
			
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$AddAccountRequest = array("account_info" => array(
										'i_customer' 		=> $portaAuth['customerID'],
										'billing_model' 	=> '-1',
										'i_product' 		=> $portaAuth['product'],
										'activation_date' 	=> date('Y-m-d'),
										'id' 				=> 'ani'.$_POST['country'].$_POST['phone'],
										'balance' 			=> '0',
										'opening_balance' 	=> '1',
										'i_time_zone' 		=> '260',
										'i_lang' 			=> 'en',
										'login' 			=> $_POST['phone'],
										'password'			=> $_POST['password'],
										'h323_password' 	=> $_POST['password'],
										'blocked' 			=> 'N'
									)
								);
				
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->addAccount($AddAccountRequest, $loginSession);
				
				if($result->error){
					$this->data['warning'] = $result->error;
				}else{
					$this->session->set_userdata('message', 'Account Seccessfully added. Account ID = '.$result->i_account);
					redirect(site_url('portaone/new-account'));
				}
			}				
			
			$this->data += $_POST;
			
		}
		
		$this->data['error'] 			= $this->error;
		
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'addaccount';	
		$this->load->view('portaone/_portaone', $this->data);
	}		
	public function account_info() {
		require 'PortaOneWS.php';
		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
			
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$getAccountRequest = array('i_account' => $_POST['id']);
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getInfo($getAccountRequest, $loginSession);
				
				if($result->error){
					$this->data['warning'] = $result->error;
				}else{
					$this->data['id']			= $_POST['id'];
					$this->data['account_info'] = $result->account_info;
				}
			}
			$this->data += $_POST;
		}
		
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}
		
		$this->data['accountList'] 		= $accountList;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'getaccountinfo';	
		$this->load->view('portaone/_portaone', $this->data);
	}	
	public function account_balance() {
		
		require 'PortaOneWS.php';
		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
			
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$getAccountRequest = array('i_account' => $_POST['id']);
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getBalance($getAccountRequest, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$this->data['id']			= $_POST['id'];
					$this->data['account_balance'] = $result->account_info;
				}
			}
			$this->data += $_POST;
		}
		
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}
		
		$this->data['accountList'] 		= $accountList;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'getbalance';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	
	public function add_alias() {
		require 'PortaOneWS.php';
		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['country'])) < 1) {
				$this->error['country'] = 'Country code is required!';
			}			
			if (strlen(trim($_POST['phone'])) != 10 || !is_numeric(trim($_POST['phone']))) {
				$this->error['phone'] = 'Valid Phone Number is required!';
			}			
			if (strlen(trim($_POST['parentAccount'])) < 1) {
				$this->error['parentAccount'] = 'Parent account is required!';
			}
			
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$AddAliasRequest = array("alias_info" => array(
										'i_master_account'  => $_POST['parentAccount'],					
										'id' 				=> 'ani'.$_POST['country'].$_POST['phone'],
										'blocked' 			=> 'N',
									)
								);
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->addAlias($AddAliasRequest, $loginSession);
				
				if($result->error){
					$this->data['warning'] = $result->error;
				}else{
					$this->session->set_userdata('message', 'Account Seccessfully added. Account ID = '.$result->i_account);
					redirect(site_url('portaone/add-line'));
				}
			}		
			$this->data += $_POST;			
		}

		// Account List
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O' && !$accountDetails->i_master_account){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}	
		
		$this->data['accountList'] 		= $accountList;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'alias_add';	
		$this->load->view('portaone/_portaone', $this->data);
	}	
	public function get_alias(){
		require 'PortaOneWS.php';		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['parentAccount'])) < 1) {
				$this->error['parentAccount'] = 'Parent Account ID is required!';
			}			
						
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$getAliasRequest = array( 'i_master_account'  => $_POST['parentAccount'] );
				
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getAliasList($getAliasRequest, $loginSession);
				if($result->error){
					$this->data['warning'] = $result->error;
				}else{
					$this->data['alias_list'] = $result->alias_list;
				}
			}		
			$this->data += $_POST;			
		}

		// Account List
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O' && !$accountDetails->i_master_account){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}	
		
		$this->data['accountList'] 		= $accountList;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'alias_get';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	public function delete_alias() {
		require 'PortaOneWS.php';
		
		$data = array('success'=>0,'text'=>'Please try again!');
				
		if(isset($_POST) && count($_POST) > 0){
			$portaAuth 		= $this->config->item('portaone');
			$pinlessDetails = $this->getPinlessMode();
			$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");			
			$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password']));
				
			$deleteAlias = array( 'alias_info' => array( 
										'i_master_account' 	=> $_POST['parentAccount'],
										'i_account' 		=> $_POST['id'],
										'id'				=> $_POST['aliasID']						
									) 
								);
			
			$api 	= new PortaOneWS("AccountAdminService.wsdl");
			$result = $api->deleteAlias($deleteAlias, $loginSession);
			
			if(!$result->error){
				$this->session->set_userdata('message', 'You has been delete promotion history successfully!');
				$data = array('success'=>1);
			}						
		}
		echo json_encode($data);	
	}
	// Accounts END ===================================================================
	
	// Terminate Account ==============================================================
	public function terminate_account() {
		
		require 'PortaOneWS.php';
		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
			
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$terminateAccountRequest = array('i_account' => $_POST['id']);
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->terminateAccount($terminateAccountRequest, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{					
					$this->data['message'] = 'Account '.$_POST['id'].' Successfully Terminated';
				}
			}
			$this->data += $_POST;
		}
		
		// Account List
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}		
		$this->data['accountList'] 		= $accountList;
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'terminateAccount';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	
	// Recharge =======================================================================
	public function recharge_account() {
		
		require 'PortaOneWS.php';
		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
			if (strlen(trim($_POST['amount'])) < 1) {
				$this->error['amount'] = 'Valid Amount for recharge is required!';
			}
			
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$updateAccountRequest = array('i_account' => $_POST['id'],										
											  'action' 	  => 'Manual payment',
										 	  'amount'	  => $_POST['amount']																				
								);
			
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->rechargeAccount($updateAccountRequest, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$this->data['message'] = 'Account Recharge Successfully with amount $'.$_POST['amount'];
				}
			}
			$this->data += $_POST;
		}

		// Account List
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}		
		$this->data['accountList'] 		= $accountList;
		
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'rechargeaccount';	
		$this->load->view('portaone/_portaone', $this->data);
	}	
	public function recharge_history(){
		require 'PortaOneWS.php';
		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
		$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
						
			if(!$this->error){
								
				$xcdrRequest = array('i_account' => $_POST['id'], 
									 'i_service' => ($_POST['service']) ? $_POST['service'] : null,
									 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
									 'to_date'	 => date('Y-m-d H:i:s', strtotime($_POST['to_date']))																			
								);
	
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getxCdr($xcdrRequest, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$this->data['recharge_history'] = $result->xdr_list;
				}
			}
			$this->data += $_POST;
		}
		
		// Account List
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}		
		$this->data['accountList'] 		= $accountList;		
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'rechargehistory';	
		$this->load->view('portaone/_portaone', $this->data);
	}	
	public function refund(){
		
		require 'PortaOneWS.php';
		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
		$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
			if (strlen(trim($_POST['amount'])) < 1) {
				$this->error['amount'] = 'Refund Amount is required!';
			}
						
			if(!$this->error){
								
				$refundAccountRequest = array('i_account' => $_POST['id'],										
											  'action' 	  => 'Refund',
										 	  'amount'	  => $_POST['amount']																				
								);
			
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->rechargeAccount($refundAccountRequest, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$this->session->set_userdata('message', 'Account Refund Success with amount $'.$_POST['amount']);
					redirect(site_url('portaone/refund'));					
				}
			}
			$this->data += $_POST;
		}
		
		// Account List
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}		
		$this->data['accountList'] 		= $accountList;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'refund';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	
	// CDR ============================================================================
	public function cdr_details() {
		
		require 'PortaOneWS.php';
		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
		$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
						
			if(!$this->error){
								
				$xcdrRequest = array('i_account' => $_POST['id'], 
									 'i_service' => ($_POST['service']) ? $_POST['service'] : null,
									 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
									 'to_date'	 => date('Y-m-d H:i:s', strtotime($_POST['to_date']))																			
								);
	
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getxCdr($xcdrRequest, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$this->data['cdr_details'] = $result->xdr_list;
				}
			}
			$this->data += $_POST;
		}
		
		// Account List
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}		
		$this->data['accountList'] 		= $accountList;
				
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'xdr';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	
	// Speed dial =====================================================================
	public function speed_dial(){
			
		require 'PortaOneWS.php';		
		if(isset($_POST) && count($_POST) > 0){
					
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
			if (strlen(trim($_POST['contact_name'])) < 1) {
				$this->error['contact_name'] = 'Customer Name is required!';
			}
			if (strlen(trim($_POST['phone'])) < 1) {
				$this->error['phone'] = 'Phone number is required!';
			}
						
			if(!$this->error){
				$portaAuth 		= $this->config->item('portaone');
				$pinlessDetails = $this->getPinlessMode();
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$getSpeedDial = array( 'offset'    => 0, 
									   'limit'     => NULL, 
									   'i_account' => $_POST['id'], 
									   'phone_number_pattern' => NULL
								);
				
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getSpeeddial($getSpeedDial, $loginSession);
				if($result){
					$i = $result->phonebook_rec_list[0]->dial_id+1;
				}else{
					$i=1;
				}
				
				$addPhoneBook = array( 'phonebook_rec_info' => array('i_account' 	=> $_POST['id'], 
																	 'phone_number' => $_POST['phone'],
																	 'phone_type'	=> $_POST['contact_type'],
																	 'name'			=> $_POST['contact_name'],
																	 'dial_id'		=> $i
																	)
								);
								
				$result = $api->addSpeeddial($addPhoneBook, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$this->session->set_userdata('message', 'Speed dial added successfully!');
					redirect(site_url('portaone/speed-dial'));
				}
			}
		}
				
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}
		
		$this->data['accountList'] 		= $accountList;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'speed_dial';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	public function get_speed_dial(){
		
		require 'PortaOneWS.php';		
		if(isset($_POST) && count($_POST) > 0){
					
			if (strlen(trim($_POST['id'])) < 1) {
				$this->error['id'] = 'Account ID is required!';
			}
						
			if(!$this->error){
				$portaAuth 		= $this->config->item('portaone');
				$pinlessDetails = $this->getPinlessMode();
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$getPhoneBook = array( 'offset' => 0, 
									   'limit' => NULL, 
									   'i_account' => $_POST['id'], 
									   'phone_number_pattern' => NULL
								);
				
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getSpeeddial($getPhoneBook, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$this->data['speed_dials'] = $result->phonebook_rec_list;
				}
			}
			$this->data += $_POST; 
		}
		
		$accountList 	  = array();
		$accountList['']  = '-- Select --';
		
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[$accountDetails->i_account] = $accountDetails->id;
			}
		}
		
		$this->data['accountList'] 		= $accountList;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'get_speed_dial';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	public function delete_speed_dial(){
		require 'PortaOneWS.php';
		
		$data = array('success'=>0,'text'=>'Please try again!');
				
		if(isset($_POST) && count($_POST) > 0){
			$portaAuth 		= $this->config->item('portaone');
			$pinlessDetails = $this->getPinlessMode();
			$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
			$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password']));
				
			$deletePhoneBook = array( 'i_account_phonebook' => $_POST['id']);
			
			$api 	= new PortaOneWS("AccountAdminService.wsdl");
			$result = $api->deleteSpeeddial($deletePhoneBook, $loginSession);
			
			if(!$result->error){
				$this->session->set_userdata('message', 'You has been delete promotion history successfully!');
				$data = array('success'=>1);
			}						
		}
		echo json_encode($data);		 
	}
	
	public function get_product_list() {
		require 'PortaOneWS.php';		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		
		$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
		$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
		
		$data 	= array( 'offset' => 0, 'limit' 	=> NULL	);				
			
		$api 	= new PortaOneWS("ProductAdminService.wsdl");
		$result = $api->get_product_list($data, $loginSession);
			
		$this->data['results']			= $result->product_list;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'get_product_list';	
		$this->load->view('portaone/_portaone', $this->data);
	}
	
	// Portaone Reports ==========================================================================
	public function account_reports() {
		require 'PortaOneWS.php';		
		$accountList 	  = array();
				
		$getTotalAccounts = $this->getAccountList();
		foreach($getTotalAccounts as $accountDetails){
			if($accountDetails->bill_status=='O'){
				$accountList[] = $accountDetails;
			}
		}
		
		$this->data['accountList'] 		= $accountList;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'report_account';	
		$this->load->view('portaone/_portaoneReports', $this->data);
	}
	public function cdr_reports() {
		require 'PortaOneWS.php';		
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['from_date'])) < 1) {
				$this->error['from_date'] = 'From date is required!';
			}
			if (strlen(trim($_POST['to_date'])) < 1) {
				$this->error['to_date'] = 'To date is required!';
			}
									
			if(!$this->error){
				$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
				$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password'])); 
				
				$getAllCDR = array( 'i_customer'=> $portaAuth['customerID'], 
									'offset' 	=> 0, 
									'limit' 	=> NULL,
									'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
									'to_date'   => date('Y-m-d 11:59:59', strtotime($_POST['to_date'])),
									'i_service'	=> 3						
								);				
					
				$api 	= new PortaOneWS("CustomerAdminService.wsdl");
				$result = $api->getAllxCdr($getAllCDR, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$this->data['cdr_details'] = $result->xdr_list;
				}
			}
			$this->data += $_POST;
		}
				
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'portaone';
		$this->data['sub_current_page'] = 'report_cdr';	
		$this->load->view('portaone/_portaoneReports', $this->data);
	}
	
	// Private Functions ==========================================================================
	private function getAccountList(){
		
		//require 'PortaOneWS.php';
		$portaAuth 		= $this->config->item('portaone');
		$pinlessDetails = $this->getPinlessMode();
		
		$portaOne 		= new PortaOneWS("SessionAdminService.wsdl");
		$loginSession 	= $portaOne->getSessionID(array( "user" => $pinlessDetails['username'], "password" => $pinlessDetails['password']));
		
		//$getAccountList = array( 'offset' => null, 'limit'=> null, 'i_customer' => $portaAuth['customerID'], 'i_batch' => null );
		$getCustInfo = array('i_customer' => $portaAuth['customerID'],  'refnum'=> "", 'name' => "" , 'login' => "");
		$getAccountList = array( 'offset' => null, 'limit'=> null, 'i_customer' => $portaAuth['customerID'], 'i_batch' =>17 );
		
		
		/*
		$api 	= new PortaOneWS("CustomerAdminService.wsdl");
		$result = $api->getCustInfo($getCustInfo, $loginSession);
		print_r($result);
		exit();	
		*/
		
		$api 	= new PortaOneWS("AccountAdminService.wsdl");
		$result = $api->getAccountLists($getAccountList, $loginSession);
		//print_r($result);
		//exit();	
		return $result->account_list;
	}
	
	private function getPinlessMode(){
		$getPinlessMode     = $this->admin_model->getSettings(array('settingKey' => 'pinlessMode'));
		$getPinlessUsername = $this->admin_model->getSettings(array('settingKey' => 'pinlessUsername'));
		$getPinlessPassword = $this->admin_model->getSettings(array('settingKey' => 'pinlessPassword'));			
		
		$username = $getPinlessUsername->settingValue;
		$password = $getPinlessPassword->settingValue;
		if($getPinlessMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
		return array('sandbox'=>$sandbox, 'username' => $username, 'password' => $password);
	}
	
	public function get_payment_transaction_by_id(){
		//get_payment_transaction_by_id
		
	}
	
	
	
}
?>
