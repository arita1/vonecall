<?php
class Admin extends CI_Controller {
	
	private $error = array();
	private $data = array();
	
	function __construct() {
		parent::__construct();
		$this->roleuser->checkLogin();
		$this->load->library('form_validation');
		$this->load->helper(array('form','format'));
		$this->load->model('admin_model');  
		$this->load->model('agent_model');
		$this->load->model('customer_model'); 
		$this->load->model('payment_model'); 
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		
		//assign message
		if ($this->session->userdata('message')) {
			$this->data['message'] = $this->session->userdata('message');
			$this->session->unset_userdata('message');
		}
		//assign warning
		if ($this->session->userdata('warning')) {
			$this->data['warning'] = $this->session->userdata('warning');
			$this->session->unset_userdata('warning');
		}		
    }    
    
	public function admin_change_password() {
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['oldPassword']) < 1) {
				$this->error['oldPassword'] = 'Old password is required!';
			} elseif (!$this->admin_model->checkPassword(md5($_POST['oldPassword']))) {
				$this->error['oldPassword'] = 'Old password wrong!';
			}
			if (strlen($_POST['password']) < 1) {
				$this->error['password'] = 'New password is required!';
			} elseif ($_POST['password'] != $_POST['passwordConfirm']) {
				$this->error['passwordConfirm'] = 'The new password and confirmation password do not match!';
			}
			if (!$this->error) {
				//change password
				$this->admin_model->update($this->session->userdata('userid'), array( 'password' => md5($_POST['password']) ));
				$this->admin_model->logout();
				redirect('login');
			}
			$this->data += $_POST;
		}
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] = 'admin';
		$this->load->view('admin_change_password', $this->data);
	}
	public function admin_profile(){
		$info = $this->getAdminInfo();
			
		if (isset($_POST) && count($_POST) > 0) {
			//process submit
			if ($this->validateDistFrom($_POST, $this->session->userdata('rep_userid'))) {
				$data = array(
					'company_name'	=> trim($_POST['company_name']),
					'firstName'		=> trim($_POST['firstName']),
					'lastName'		=> trim($_POST['lastName']),
					'email'			=> trim($_POST['email']),
					'phone'			=> trim($_POST['phone']),
					'cellPhone'		=> trim($_POST['cellphone']),
					'address'		=> trim($_POST['address']),
					'city'			=> trim($_POST['city']),
					'stateID'		=> trim($_POST['state']),
					'zipCode'		=> trim($_POST['zip']),
					'address2'		=> trim($_POST['address2']),
				);
				
				if($_POST['password']) 
					$data = array_merge($data, array('password' => md5($_POST['password'])));
				
				$this->agent_model->update($this->session->userdata('rep_userid'), $data);
				
				//redirect to list page
				$this->session->set_userdata('message', 'You has been update profile successfully!');
				redirect(site_url('profile'));
			}
			$this->data += $_POST;
		} else {			
			$this->data['company_name']	= $info->company_name;
			$this->data['firstName']	= $info->firstName;
			$this->data['lastName']		= $info->lastName;
			$this->data['email']		= $info->email;
			$this->data['phone']		= $info->phone;
			$this->data['cellphone']	= $info->cellPhone;
			$this->data['address']		= $info->address;
			$this->data['city']			= $info->city;
			$this->data['state']		= $info->stateID;
			$this->data['zip']			= $info->zipCode;
			$this->data['address2']		= $info->address2;	
			$this->data['password']		= $info->password;
			$this->data['balance']		= $info->balance;			
		}
		//assign error
		$this->data['error'] = $this->error;				
				
		//option_max_store_commission
		$max_store_commission_array = array();
		$max_store_commission_array['0'] = '-- Select --';
		$maxCommission = $this->config->item('max_commission');
		for ($i=10;$i<=$maxCommission;$i++) {
			$max_store_commission_array[$i] = $i.'%';
		}
		$this->data['option_max_store_commission'] = $max_store_commission_array;
		
		//state
		$option_state = array();
		$option_state[''] = '-- Select --';
		$states = $this->customer_model->getStates();
		foreach ($states as $item) {
			$option_state[$item->stateID] = $item->stateName;
		}
		$this->data['option_state'] 	= $option_state;
		
		$this->data['current_page'] 	= 'admin';
		$this->load->view('admin_profile', $this->data);
	}
		
	public function manage_employee() {
		$current = 1;
		$total = 0;
		$where = array();
		if (isset($_POST) && count($_POST) > 0) {
			switch ($_POST['search_key']) {
				case "search_by_employee_login_id":
					$where['a.username'] = $_POST['search_value'];
					break;
				case "search_by_employee_lastName":
					$where = "a.firstName like '{$_POST['search_value']}' OR a.lastName like'{$_POST['search_value']}'";
					break;
				default:
					
			}
			$this->data[$_POST['search_key']] = $_POST['search_value'];
			
			$total = $this->admin_model->getTotalAdmins($where);
			
			//start export excel
			if ($_POST['excel'] == 1) {
				header("Content-type: application/x-msdownload");
				header("Content-Disposition: attachment; filename=".date('Ymd')."_customer_account.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$results = $this->admin_model->getAdmins($where, $total, 0);
				print $this->load->view('manage_employee_excel', array('results'=>$results), true); die; 
			}
			//end export excel
			
			$current = $_POST['page'] > 1 ? $_POST['page'] : 1;
			$offset = ($current - 1) * $this->config->item('num_per_page');
			$results = $this->admin_model->getAdmins($where, $this->config->item('num_per_page'), $offset);
			
			if ($_POST['submit_type'] == 1 && $this->validateForm($_POST)) {
				//add employee
				$data = array(
					'adminTypeID'	=> $_POST['adminType'],
					'firstName'		=> $_POST['firstName'],
					'lastName'		=> $_POST['lastName'],
					'username'		=> $_POST['username'],
					'password'		=> $_POST['password'],
					'createdDate'	=> date('Y-m-d H:i:s')
				);
				$this->admin_model->add($data);
				redirect('manage-employee');
			}
			$this->data += $_POST;
		} else {
			$total = $this->admin_model->getTotalAdmins($where);
			$results = $this->admin_model->getAdmins($where, $this->config->item('num_per_page'), 0);
		}
		
		$paging_data = array(
			'limit' => $this->config->item('num_per_page'),
			'current' => $current,
			'total' => $total
		);
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
		
		if ($this->session->userdata('message')) {
			$this->data['message'] = $this->session->userdata('message');
			$this->session->unset_userdata('message');
		}
		
		//asign results
		$this->data['results'] = $results;
		
		//payment method
		$option_admin_type = array();
		$option_admin_type[''] = '-- Select --';
		$admin_types = $this->admin_model->getAdminTypes();
		foreach ($admin_types as $item) {
			$option_admin_type[$item->adminTypeID] = $item->adminTypeName;
		}
		$this->data['option_admin_type'] = $option_admin_type;
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] = 'admin';
		$this->load->view('manage_employee', $this->data);
	}
	public function delete() {
		$data = array('success'=>0,'text'=>'Please try again!');
		if ($_POST['id'] == $this->session->userdata('userid')) {
			$data = array('success'=>0,'text'=>'You can not delete this employee!');
		} elseif ($this->admin_model->delete($_POST['id'])) {
			$data = array('success'=>1,'text'=>'You was deleted successfull!');
		}
		echo json_encode($data);
	}
	
	// Payment =========================================================================
	public function admin_payment(){
		$info = $this->getAdminInfo();
		
		if (isset($_POST) && count($_POST) > 0) {
			
			//payment by stored card Start ====
			if ( strlen(trim($_POST['savedCard'])) > 0 ) {
				if (strlen(trim($_POST['chargedAmount'])) < 1) {
					$this->error['chargedAmount'] = $this->lang->line('msg_required_amount');
				} elseif (!is_numeric(trim($_POST['chargedAmount'])) || (float)$_POST['chargedAmount']<=0) {
					$this->error['chargedAmount'] = $this->lang->line('msg_invalid_amount');
				}
				if(!$this->error){
					$transactionID  = NULL;
					
					$profileDetails = $this->payment_model->getAgentPaymentProfiles(array('profileID'=>$_POST['savedCard']));
					
					$transactionID  = $this->profilePayment($profileDetails[0], $_POST['chargedAmount'], 'AuthCapture');
					if (!$this->error) { 
						$chargedDiscount = 0;
						$distCommission  = 0;
						$createdDate 		  = date('Y-m-d H:i:s');
						//insert to payment
						$data = array(
							'agentID'				=> (int)$this->session->userdata('rep_userid'),
							'transactionID'			=> $transactionID,
							'chargedAmount'			=> $_POST['chargedAmount'],
							'chargedDiscount'		=> $chargedDiscount,
							'paymentMethodID'		=> 1,
							'enteredBy'				=> $this->session->userdata('rep_username').' - '.$this->session->userdata('rep_usertype'),
							'ipAddress'				=> $this->input->ip_address(),
							'paidTo'				=> 'System Admin',
							'comment'				=> '',//'payment with credit card',
							'accountRepID'			=> $info->parentAgentID,
							'accountRepCommission'	=> $distCommission,
							'collectedByCompany'	=> 1,
							'dateCollectedByCompany'=> $createdDate,
							'createdDate'			=> $createdDate
						);
						$this->payment_model->addAgentPayment($data);
						
						//update balance
						$totalCredit = (float)$_POST['chargedAmount'] + (float)$chargedDiscount;
						$this->agent_model->updateBalance((int)$this->session->userdata('rep_userid'), $totalCredit);
						
						//reload page
						$this->session->set_userdata('message', 'You has been make payment successfully!');
						redirect(site_url('payment'));
					}					
				}
			}
			//Payment by stored card END ======
						
			if ($this->validatePaymentForm($_POST)) {				
				$transaction = NULL;
				$transaction = $this->creditcard($_POST, 'authorize_and_capture');
				if (!$this->error) {
					$accountRepCommission = 0;
					$createdDate = date('Y-m-d H:i:s');
					//insert to payment
					$data = array(
						'agentID'				=> (int)$this->session->userdata('rep_userid'),
						'transactionID'			=> $transaction['transactionID'],
						'chargedAmount'			=> $_POST['chargedAmount'],
						'paymentMethodID'		=> 1,
						'enteredBy'				=> $this->session->userdata('rep_username').' - '.$this->session->userdata('rep_usertype'),
						'ipAddress'				=> $this->input->ip_address(),
						'paidTo'				=> 'System Admin',
						'accountRepCommission'	=> $accountRepCommission,
						'collectedByCompany'	=> 1,
						'dateCollectedByCompany'=> $createdDate,
						'accountRepID'			=> $info->parentAgentID,
						'comment'				=> $_POST['comment'],
						'createdDate'			=> $createdDate
					);
					$this->payment_model->addAgentPayment($data);
					
					//update balance
					$this->agent_model->updateBalance((int)$this->session->userdata('rep_userid'), (float)$_POST['chargedAmount']);
					
					// Store Card details on server #CIM#
					if($_POST['saveCard']){
						$authProfileID = null;
						if($info->authProfile)
							$authProfileID = $info->authProfile; 
						
						$agentPaymentProfile = $this->storeCreditCard($_POST, $authProfileID);	
						
						// Save card profile ==================
						$data = array('agentID'				=> (int)$this->session->userdata('rep_userid'),
									  'cimCardNumber'		=> $transaction['cardNumber'],
									  'cimProfileID'		=> $agentPaymentProfile['profile'],
								      'cimShippingProfileID'=> $agentPaymentProfile['shippingProfile'],
									  'cimPaymentProfileID'	=> $agentPaymentProfile['paymentProfile'],
									  'profile_DateTime'	=> date('Y-m-d H:i:s'));
						if(!$this->error){
							$this->payment_model->addCardDetails($data);
						}
												
						// Update Profile For Agent ======
						$this->agent_model->update((int)$this->session->userdata('rep_userid'), array('authProfile' => $agentPaymentProfile['profile']));
					}
					
					//reload page
					$this->session->set_userdata('message', 'You has been make payment successfully!');
					redirect(site_url('payment'));
				}				
			}
			$this->data += $_POST;
			$this->data['error'] = $this->error;
		}
		//state
		$option_state = array();
		$option_state[''] = '-- Select --';
		$states = $this->customer_model->getStates();
		foreach ($states as $item) {
			$option_state[$item->stateID] = $item->stateName;
		}
		$this->data['option_state'] = $option_state;
		
		//month array
		$this->data['option_month'] = array(''=>'-- Month --','01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
		
		//year array
		$year_array = array();
		$year_array[''] = '-- Year --';
		for ($i=(int)date('Y');$i<(int)date('Y')+10;$i++) {
			$year_array[$i] = $i;
		}
		$this->data['option_year'] = $year_array;
		
		// Payment Profiles
		$paymentProfiles   = array();
		$profile_array[''] = '-- Select --';
		$profiles 	 	   = $this->payment_model->getAgentPaymentProfiles(array('agentID'=>$this->session->userdata('rep_userid')));
		foreach ($profiles as $item) {
			$profile_array[$item->profileID] = $item->cimCardNumber;
		}
		$this->data['paymentProfiles'] = $profile_array;
		
		$this->data['balance'] = $info->balance;
		$this->data['error']   = $this->error;
		$this->data['current_page'] = 'payment';
		$this->load->view('payment', $this->data);
		
	}
	public function admin_payment_adjust(){
		$info = $this->getAdminInfo();
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['store']) < 1) {
				$this->error['store'] = 'Store is required!';
			} 
			if (strlen(trim($_POST['chargedAmount'])) < 1) {
				$this->error['chargedAmount'] = 'Amount is required!';
			} elseif (!is_numeric(trim($_POST['chargedAmount'])) || (float)$_POST['chargedAmount']<=0) {
				$this->error['chargedAmount'] = 'Amount is invalid!';
			}
			if($info->balance < $_POST['chargedAmount']){
				$this->error['credit_error'] = 'You have no enough credit for this transaction!';
			}
			if(!$this->error){
				
				$createdDate = date('Y-m-d H:i:s');	
				//insert to payment
				$data = array(
					'agentID'				=> $_POST['store'],
					'transactionID'			=> 123456,
					'chargedAmount'			=> $_POST['chargedAmount'],
					'paymentMethodID'		=> 22,	// payment Adjustment by DISTRIBUTOR
					'enteredBy'				=> $this->session->userdata('rep_username').' - '.$this->session->userdata('rep_usertype'),
					'ipAddress'				=> $this->input->ip_address(),
					'paidTo'				=> 'Store',
					'accountRepCommission'	=> 0,
					'collectedByCompany'	=> 1,
					'dateCollectedByCompany'=> $createdDate,
					'accountRepID'			=> $this->session->userdata('rep_userid'),
					'comment'				=> $_POST['comment'],
					'createdDate'			=> $createdDate
				);
				$this->payment_model->addAgentPayment($data);
				
				$amount = -$_POST['chargedAmount'];
				//update balance for DISTRIBUTOR
				$this->agent_model->updateBalance((int)$this->session->userdata('rep_userid'), (float)$amount);
				
				//update balance for STORE
				$this->agent_model->updateBalance((int)$_POST['store'], (float)$_POST['chargedAmount']);
				
				//reload page
				$this->session->set_userdata('message', 'Payment successfully added to store balance!');
				redirect(site_url('payment-adjust'));
			}
			$this->data += $_POST;
		}
		//Get All Stores
		$where   = "parentAgentID = ".(int)$this->session->userdata('rep_userid');
		$results = $this->agent_model->getAgents($where, 2, null, null);	
		$storeArray = array();
		$storeArray[''] = '-- Store --';
		foreach($results as $item){
			$storeArray[$item->agentID] = $item->company_name; 
		}
		$this->data['option_store'] = $storeArray;
		
		$this->data['balance'] = $info->balance;
		$this->data['error']   = $this->error;
		$this->data['current_page'] = 'payment';
		$this->load->view('payment_adjust', $this->data);
	}	
	
	// Private Functions ===============================================================
	private function getAdminInfo() {
		$id = (int)$this->session->userdata('rep_userid');
		//agent info
		$info = $this->agent_model->getAgent($id);
		if (!is_numeric($id)|| !$info) {
			show_error('Store you requested was not found.', 404, 'Store is not found!');
		}
		$agentPayment			= $this->agent_model->getTotalAgentPayment($id, '2014-01-01 00:00:00', date('Y-m-d H:i:s'));
		$agentSale 				= $this->agent_model->getTotalAgentSale($id, '2014-01-01 00:00:00', date('Y-m-d H:i:s'));
		$info->TotalSale 		= format_price($agentSale->Sale);
		$info->TotalPayment 	= format_price($agentPayment->Payment);
		$info->TotalCommission 	= format_price($agentSale->Commission);
		
		$this->data['agentID'] = $id;
		$this->data['info'] = $info;
		
		return $info;
	}
	
	private function validateForm($data) {
		if (strlen(trim($data['adminType'])) < 1) {
			$this->error['adminType'] = 'User type is required!';
		}
		if (strlen(trim($data['firstName'])) < 1) {
			$this->error['firstName'] = 'First name is required!';
		} elseif (strlen(trim($data['firstName'])) > 15) {
			$this->error['firstName'] = 'Maximum 15 character!';
		}
		if (strlen(trim($data['lastName'])) < 1) {
			$this->error['lastName'] = 'Last name is required!';
		} elseif (strlen(trim($data['lastName'])) > 25) {
			$this->error['lastName'] = 'Maximum 25 character!';
		}
		if (strlen(trim($data['username'])) < 1) {
			$this->error['username'] = 'User name is required!';
		} elseif ($this->admin_model->checkUserExist($data['username'])) {
			$this->error['username'] = 'User name is aready existed!';
		}
		if (strlen(trim($data['password'])) < 1) {
			$this->error['password'] = 'The password is required!';
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateDistFrom($data, $agentID=0) {
		if (strlen(trim($data['firstName'])) < 1) {
			$this->error['firstName'] = 'First name is required!';
		} elseif (strlen(trim($data['firstName'])) > 15) {
			$this->error['firstName'] = 'Maximum 15 character!';
		}
		if (strlen(trim($data['lastName'])) < 1) {
			$this->error['lastName'] = 'Last name is required!';
		} elseif (strlen(trim($data['lastName'])) > 25) {
			$this->error['lastName'] = 'Maximum 25 character!';
		}
		if (strlen(trim($data['phone'])) > 0 && !is_numeric(trim($data['phone']))) {
			$this->error['phone'] = 'Invalid phone number!';
		}
		if (strlen(trim($data['cellphone'])) > 0 && !is_numeric(trim($data['cellphone']))) {
			$this->error['cellphone'] = 'Invalid cellphone number!';
		}
		if (strlen(trim($data['email'])) < 1) {
			$this->error['email'] = 'Your Email is required!';
		} elseif (!$this->form_validation->valid_email($data['email'])) {
			$this->error['email'] = 'Your Email is invalid!';
		} elseif ($this->agent_model->checkAgentEmailExist($data['email'], $agentID)) {
			$this->error['email'] = 'Email is already existed!';
		}
		
		if ( strlen(trim($data['address'])) < 1 ) {
			$this->error['address'] = 'Address required!';
		}
		if ( strlen(trim($data['city'])) < 1 ) {
			$this->error['city'] = 'City required!';
		}
		if ( strlen(trim($data['state'])) < 1 ) {
			$this->error['state'] = 'State required!';
		}
		if ( strlen(trim($data['zip'])) < 1 ) {
			$this->error['zip'] = 'Zip required!';
		}
		if ( strlen(trim($data['company_name'])) < 1 ) {
			$this->error['company_name'] = 'Company name required!';
		}
		
		if ($agentID==0) {
			if (strlen(trim($data['agentLoginID'])) < 1) {
				$this->error['agentLoginID'] = 'User name is required!';
			} elseif ($this->agent_model->checkAgentExist($data['agentLoginID'], $agentID)) {
				$this->error['agentLoginID'] = 'User name is already existed!';
			}
			if (strlen(trim($data['agentPassword'])) < 1) {
				$this->error['agentPassword'] = 'The password is required!';
			} elseif (trim($data['agentPassword']) != trim($data['agentPasswordConfirm'])) {
				$this->error['agentPasswordConfirm'] = 'The new password and confirmation password do not match!';
			}
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validatePaymentForm($data) {
		
		$errornumber = '';
		$errortext = '';
		$this->load->helper(array('creditcard'));
		if (strlen(trim($data['card_number'])) < 1) {
			$this->error['card_number'] = 'Card Number is required!';
		} 
		if (strlen(trim($data['card_exp_month'])) < 1 || strlen(trim($data['card_exp_year'])) < 1) {
			$this->error['card_exp'] = 'Card Expiry is required!';
		}
		/*
		if (strlen(trim($data['card_name'])) < 1 || strlen(trim($data['card_name'])) > 15) {
			$this->error['card_name'] = 'Name on Credit Card is required and maximum 15 character!';
		}
		if (strlen(trim($data['card_address'])) < 1) {
			$this->error['card_address'] = 'Address is required!';
		}
		if (strlen(trim($data['card_city'])) < 1) {
			$this->error['card_city'] = 'City is required!';
		}
		if (strlen(trim($data['card_zipcode'])) < 1) {
			$this->error['card_zipcode'] = 'Zipcode is required!';
		}
		if (strlen(trim($data['card_state'])) < 1) {
			$this->error['card_state'] = 'State is required!';
		}
		*/
		if (strlen(trim($data['card_cvv'])) < 1) {
			$this->error['card_cvv'] = 'CVV Code is required!';
		}
		
		if (strlen(trim($data['chargedAmount'])) < 1) {
			$this->error['chargedAmount'] = 'Amount is required!';
		} elseif (!is_numeric(trim($data['chargedAmount'])) || (float)$data['chargedAmount']<=0) {
			$this->error['chargedAmount'] = 'Amount is invalid!';
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function creditcard($data, $type='') {
		require(APPPATH.'libraries/anet_php_sdk/AuthorizeNet.php');
		
		$getAuthorizeMode   = $this->admin_model->getSettings(array('settingKey' => 'authrizeMode'));
		$getAuthorizeLogin  = $this->admin_model->getSettings(array('settingKey' => 'authrizeLogin'));
		$getAuthorizeSecret = $this->admin_model->getSettings(array('settingKey' => 'authrizeSecret'));		
		
		
		$authLoginID = $getAuthorizeLogin->settingValue;
		$authKey     = $getAuthorizeSecret->settingValue;
		if($getAuthorizeMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
		/****
		$param = $this->config->item('payment');		
		$transaction = new AuthorizeNetAIM($param['AUTHORIZENET_API_LOGIN_ID'],$param['AUTHORIZENET_TRANSACTION_KEY']);
		****/
		$transaction = new AuthorizeNetAIM($authLoginID, $authKey);
		$transaction->setSandbox($sandbox);
		$info = array(
			'amount' 		=> $data['chargedAmount'],
			'card_num' 		=> $data['card_number'],
			'exp_date' 		=> $data['card_exp_month'].substr($data['card_exp_year'], -2),
			'card_code' 	=> $data['card_cvv'],
			'description' 	=> 'from 011com',
			'first_name' 	=> $data['card_name'],
			'last_name' 	=> $data['card_name'],
			'address' 		=> $data['card_address'],
			'city' 			=> $data['card_city'],
			'state' 		=> $data['card_state'],
			'zip' 			=> $data['card_zipcode'],
			'cust_id' 		=> '',
			'customer_ip' 	=> '',
			'invoice_num' 	=> ''
		);
		$transaction->setFields($info);
		
		if ($type=='authorize') {
			$response = $transaction->authorizeOnly();
			$ret = $response->authorization_code;
		} elseif ($type=='authorize_and_capture') {
			$response = $transaction->authorizeAndCapture();
			//$ret = $response->transaction_id;
			$ret = array('transactionID' => $response->transaction_id, 'cardNumber' => $response->card_type.substr($response->account_number, -4));
		} elseif ($type=='capture') {
			$response = $transaction->captureOnly($data['auth_code']);
			$ret = $response->transaction_id;
		} else {
			$this->error['auth_error'] = 'AuthorizeNet type of request is not found!';
			$ret = false;
		}
		if (!$response->approved) {
			$this->error['auth_error'] = $response->response_reason_text;
			$ret = false;
		}
		return $ret;
	}
	private function storeCreditCard($data, $authProfileID=''){
		
		$info = $this->getAdminInfo();	
		
		$getAuthorizeMode   = $this->admin_model->getSettings(array('settingKey' => 'authrizeMode'));
		$getAuthorizeLogin  = $this->admin_model->getSettings(array('settingKey' => 'authrizeLogin'));
		$getAuthorizeSecret = $this->admin_model->getSettings(array('settingKey' => 'authrizeSecret'));			
		
		$authLoginID = $getAuthorizeLogin->settingValue;
		$authKey     = $getAuthorizeSecret->settingValue;
		if($getAuthorizeMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
			
		$transaction = new AuthorizeNetCIM($authLoginID,$authKey);
		$transaction->setSandbox($sandbox);
		
		// Create Profile =======
		$profileId    = null;
		$profileError = null;
		
		//$deteleProfile = $transaction->deleteCustomerProfile(29545777);echo '<pre>';print_r($deteleProfile);die;
		if($authProfileID){
			$profileId = $authProfileID;
		}else{
			$response  = $transaction->createCustomerProfile(array('email' => $info->email));//echo 'Profile'.'<pre>';print_r($response);
			if($response->xml->messages->resultCode=='Ok'){
				$profileId = $response->xml->customerProfileId;
			}else{
				$profileError .= $response->xml->messages->message->text;
			}	
		}
		
		if($profileId){
			// Create Shipping profile Start ============
			$shippingProfileId   = 0;
			$shippingProfileData = array(
									'firstName' => $info->firstName,
									'lastName'  => $info->lastName,
									'company' 	=> $info->company_name, 
									'address'	=> $info->address, 
									'city' 		=> $info->city, 
									'state' 	=> $info->stateID, 
									'zip' 		=> $info->zipCode, 
									);
			$shippingProfileResponse = $transaction->createCustomerShippingAddress($profileId, $shippingProfileData);//echo 'shipping'.'<pre>';print_r($shippingProfileResponse);
			if($shippingProfileResponse->xml->messages->resultCode=='Ok'){
				$shippingProfileId = $shippingProfileResponse->xml->customerAddressId;
			}
			/*else{
				$profileError .= ($profileError)?' AND '.$shippingProfileResponse->xml->messages->message->text : $shippingProfileResponse->xml->messages->message->text;
			}*/
						
			// Create Payment profile Start =========
			$paymentProfileId   = 0;
			$paymentProfileData = array(
						'billTo' => array('billTo' => array('firstName'   => $info->firstName,
															'lastName'    => $info->lastName,
															'phoneNumber' => $info->phone
														 )
										),
						'payment' => array('creditCard' => array('creditCard' => 
															     array('creditCard' => 
															     		array('cardNumber'     => $data['card_number'],
															     		      'expirationDate' => $data['card_exp_month'].substr($data['card_exp_year'], -2),
															     		      'cardCode'	   => $data['card_cvv']
																		)
																 )
														    )
										)
							);							
			$paymentProfileResponse = $transaction->createCustomerPaymentProfile($profileId, $paymentProfileData);//echo 'payment'.'<pre>';print_r($paymentProfileResponse);
			if($paymentProfileResponse->xml->messages->resultCode=='Ok'){
				$paymentProfileId = $paymentProfileResponse->xml->customerPaymentProfileId;
			}else{
				$profileError .= ($profileError)?' AND '.$paymentProfileResponse->xml->messages->message->text : $paymentProfileResponse->xml->message->resultCode->text;
			}
			
			if($profileError){
				$this->session->set_userdata('warning', 'Card not store on for now because '.$profileError);
				$this->error['profileError'] = 'Card not store on for now because '.$profileError;	
			}				
			
			$return = array('profile' => $profileId, 'shippingProfile' => $shippingProfileId, 'paymentProfile' => $paymentProfileId);			
		}else{
			$return = false;
		}
		
		return $return;
	}
	private function profilePayment($data, $amount, $type=''){
		require(APPPATH.'libraries/anet_php_sdk/AuthorizeNet.php');
		
		$getAuthorizeMode   = $this->admin_model->getSettings(array('settingKey' => 'authrizeMode'));
		$getAuthorizeLogin  = $this->admin_model->getSettings(array('settingKey' => 'authrizeLogin'));
		$getAuthorizeSecret = $this->admin_model->getSettings(array('settingKey' => 'authrizeSecret'));		
		
		
		$authLoginID = $getAuthorizeLogin->settingValue;
		$authKey     = $getAuthorizeSecret->settingValue;
		if($getAuthorizeMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
		
		$transaction = new AuthorizeNetCIM($authLoginID, $authKey);
		$transaction->setSandbox($sandbox);
		
		$paymentData = array(
							'amount' 					=> $amount,
							'customerProfileId' 		=> $data->cimProfileID,
							'customerPaymentProfileId'  => $data->cimPaymentProfileID, 
							'customerShippingAddressId' => $data->cimShippingProfileID
							);
		
		$response = $transaction->createCustomerProfileTransaction($type, $paymentData);
		
		if($response->xml->messages->message->code=='I00001'){
			$response = explode(",", $response->xml->directResponse);
			$responseCode 		  = $response[0]; // 1 = Approved 2 = Declined 3 = Error
			$responseReasonCode   = $response[2]; // See http://www.authorize.net/support/AIM_guide.pdf
			$responseReasonText   = $response[3];
			$approvalCode 		  = $response[4]; // Authorization code
			$transId 			  = $response[6]; // Transaction ID
			$ret = $transId;
		}else{
			$this->error['auth_error'] = $response->xml->messages->message->text;
			$ret = false;
		}
		
		return $ret;
	}
}

?>