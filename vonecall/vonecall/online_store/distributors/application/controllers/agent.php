<?php
class Agent extends CI_Controller {
	
	private $error = array();
	private $data = array();
	
	function __construct() {
		parent::__construct();
		$this->roleuser->checkLogin();
		$this->load->helper(array('form','format'));
		$this->load->library('form_validation');
		$this->load->model('customer_model');  
		$this->load->model('agent_model');  
		$this->load->model('payment_model');
		$this->load->model('admin_model');  
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
    
	public function home() {
		$agentID = $this->session->userdata('rep_userid');
		$info    = $this->getAgentInfo($agentID);
		$message = $this->agent_model->getMessage(array('messageType' => 'Promotion'));
		$bannerText = $this->agent_model->getMessage(array('messageType' => 'Dist-Banner'));
		
		$this->data['promotion_message'] = $message?$message->message:'';
		$this->data['banner_message']    = $bannerText?$bannerText->message:'';
		$this->data['info'] = $info;
		
		//time_period array
		$this->data['option_time_period'] = array(
			'today' 	=> 'Today', 
			'yesterday'	=> 'Yesterday', 
			'month' 	=> 'This Month', 
			'year' 		=> 'Year to Date'
		);
		
		$this->data['current_page'] = 'home';
		$this->load->view('home', $this->data);
	}

	public function commission_rate() {
		$agentID = $this->session->userdata('rep_userid');
		$num_per_page = 20;
		$paging_data = array(
			'limit'   => $num_per_page,
			'current' => (isset($_POST['page']) && $_POST['page'] > 1) ? $_POST['page'] : 1,
			'total'   => $this->admin_model->getTotalProductsByAgent($agentID)
		);
		$this->data['paging']  = $this->paging->do_paging_customer($paging_data);
		$offset 			   = ($paging_data['current'] - 1) * $num_per_page;
		$res = $this->admin_model->getAllProductsByAgent($agentID, $where='', $num_per_page, $offset);
		$this->data['results'] = $this->admin_model->getAllProductsByAgent($agentID, $where='', $num_per_page, $offset);
				
		$this->load->view('popup_commission_rates', $this->data);
	}
	
	// Store ========================================================================
	public function add_new_agent() {
		$info = $this->getAccountRepInfo();
		if (isset($_POST) && count($_POST) > 0) {
			//process submit
			if ($this->validateAgentFrom($_POST)) {				
				$phoneEmail = $this->getPhoneEmail(trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))));
				
				if(!$this->error){
					$createdDate = date('Y-m-d H:i:s');
					$authProfile = $this->getAuthProfile($_POST['email']);
					$data = array(
						'company_name'		=> trim($_POST['storeName']),
						'firstName'			=> trim($_POST['firstName']),
						'lastName'			=> trim($_POST['lastName']),
						'phone'				=> trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['phone']))),
						'cellPhone'			=> trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))),
						'address'			=> trim($_POST['address']),
						'city'				=> trim($_POST['city']),
						'zipCode'			=> trim($_POST['zipCode']),
						'stateID'			=> trim($_POST['state']),
						'email'				=> trim($_POST['email']),
						'note'				=> trim($_POST['note']),
						'loginID'			=> trim($_POST['agentLoginID']),
						'password'			=> trim(md5($_POST['agentPassword'])),
						'securityCode'		=> (string)time(),//substr(time(), -7),
						'agentTypeID'		=> 2,
						'parentAgentID'		=> (int)$this->session->userdata('rep_userid'),
						'status'			=> 1,
						'createdDate'		=> $createdDate,
						'authProfile'		=> $authProfile?$authProfile:'',
						'phoneEmailID'		=> addslashes($phoneEmail)
					);
					$agentID = $this->agent_model->add($data);
					
					// Text Send to Distributor's cell number
					$textMessage = "Congrats! You have joined vonecall.com as a store";
					$subject	 = "New Store on Vonecall";
					$this->sendText($textMessage, $subject, $phoneEmail);
					
					$this->session->set_userdata('message', 'You have added a new store successfully!');
					redirect('store/commission/'.$agentID);
				}				
			}
			$this->data += $_POST;
		}
		//assign error
		$this->data['error'] = $this->error;
		
		//option_max_store_commission
		$max_store_commission_array = array();
		//$max_store_commission_array['0'] = '-- Select --';
		for ($i=1;$i<=$info->maxStoreCommission;$i++) {
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
		$this->data['option_state'] = $option_state;
		
		$this->data['current_page'] = 'agent';  
		$this->load->view('add_new_agent', $this->data);
	}
    public function agent_account_manager() {
		if (isset($_POST) && count($_POST) > 0) {
			$where = "parentAgentID = ".(int)$this->session->userdata('rep_userid');
			switch ($_POST['search_key']) {
				case "search_by_agent_code":
					$where .= " AND SUBSTRING(securityCode,4,7) = '{$_POST['search_value']}'";
					break;
				case "search_by_agent_login_id":
					$where .= " AND loginID = '{$_POST['search_value']}'";
					break;
				case "search_by_agent_lastName":
					$where .= " AND (firstName like '{$_POST['search_value']}' OR lastName like '{$_POST['search_value']}')";
					break;
				case "search_by_agent_email":
					$where .= " AND email = '{$_POST['search_value']}'";
					break;
			}
			
			//start export excel
			if ($_POST['excel'] == 1) {
				header("Content-type: application/x-msdownload");
				header("Content-Disposition: attachment; filename=".date('Ymd')."_customer_account.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$results = $this->agent_model->getAgents($where, 2);
				print $this->load->view('agent_account_manager_excel', array('results'=>$results), true); die; 
			}
			//end export excel
			
			$paging_data = array(
				'limit' => $this->config->item('num_per_page'),
				'current' => $_POST['page'] > 1 ? $_POST['page'] : 1,
				'total' => $this->agent_model->getTotalAgents($where, 2)
			);
			$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
			$offset = ($paging_data['current'] - 1) * $this->config->item('num_per_page');
			$results = $this->agent_model->getAgents($where, 2, $this->config->item('num_per_page'), $offset);
			$this->data[$_POST['search_key']] = $_POST['search_value'];
			$this->data['results'] = $results;
			
			$this->data += $_POST;
		}
		
		$this->data['current_page'] = 'agent';
		$this->load->view('agent_account_manager', $this->data);
	}
	public function delete() { 
		$data = array('success'=>0,'text'=>'Please try again!');
		if ($this->agent_model->delete($_POST['id'])) {
			
			// Delete Commission Records ======
			$this->agent_model->deleteCommissionByAgent(array('agentID' => $_POST['id']));
			
			// Delete Payment Records ======
			$this->payment_model->deleteAgentPaymentByAgentID(array('agentID' => $_POST['id']));
			
			// delete authorize.net Profile
			if($info->authProfile){
				$this->deleteAuthProfile($info->authProfile);
				$this->payment_model->deletePaymentProfile(array('agentID' => $_POST['id']));
			}
			
			// Delete Customers =====
			$this->customer_model->deleteCustomers(array('agentID' => $_POST['id']));
			
			$data = array('success'=>1,'text'=>'You have deleted the distributor account successfully!');
		}
		echo json_encode($data);
	}
	public function profile($agentID) {
		$info = $this->getAgentInfo($agentID);
		$repInfo = $this->getAccountRepInfo();
		if (isset($_POST) && count($_POST) > 0) {
			//process submit
			if ($this->validateAgentFrom($_POST, $agentID)) {
				$data = array(
					'company_name'		=> trim($_POST['storeName']),
					'firstName'			=> trim($_POST['firstName']),
					'lastName'			=> trim($_POST['lastName']),
					'phone'				=> trim( preg_replace('/[^0-9]+/','', strip_tags($_POST['phone'])) ),
					//'cellPhone'			=> trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))),
					'address'			=> trim($_POST['address']),
					'city'				=> trim($_POST['city']),
					'zipCode'			=> trim($_POST['zipCode']),
					'stateID'			=> trim($_POST['state']),
					'email'				=> trim($_POST['email']),
					//'password'			=> md5($_POST['password']),
					'note'				=> trim($_POST['note'])
				);
				
				if($_POST['password']) 
					$data = array_merge($data, array('password' => md5($_POST['password'])));
				
				if($info->cellPhone == preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))){ 
					$data = array_merge($data, array('cellPhone' => trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))) ));
				}else{;
					$phoneEmail = $this->getPhoneEmail(trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))));
					$data = array_merge($data, array('cellPhone' => trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))), 'phoneEmailID' => addslashes($phoneEmail) ));
				}
				
				if(!$this->error){
					$this->agent_model->update($agentID, $data);
				
					//redirect to list page
					$this->session->set_userdata('message', 'You have updated store profile successfully!');
					redirect(site_url('store/profile/'.$agentID));	
				}				
			}
			$this->data += $_POST;
		} else {
			$this->data['firstName']		= $info->firstName;
			$this->data['lastName'] 		= $info->lastName;
			$this->data['phone'] 			= $info->phone;
			$this->data['cellphone']		= $info->cellPhone;
			$this->data['address'] 			= $info->address;
			$this->data['city'] 			= $info->city;
			$this->data['state'] 			= $info->stateID;
			$this->data['zipCode'] 			= $info->zipCode;
			$this->data['email'] 			= $info->email;
			$this->data['password'] 		= $info->password;
			$this->data['note'] 			= $info->note;
			$this->data['storeName']		= $info->company_name;
		}
		//assign error
		$this->data['error'] = $this->error;
		
		//state
		$option_state = array();
		$option_state[''] = '-- Select --';
		$states = $this->customer_model->getStates();
		foreach ($states as $item) {
			$option_state[$item->stateID] = $item->stateName;
		}
		$this->data['option_state'] = $option_state;
		
		$this->data['current_page'] = 'agent';
		$this->data['sub_current_page'] = 'profile';
		$this->load->view('agent/_agent', $this->data);
	}
	public function commission($agentID, $productType='') {
		$info = $this->getAgentInfo($agentID);
		
		$page = 1;
		if(isset($_POST['page']) && $_POST['page'] > 1){
			$page = $_POST['page'];
		}
		
		if (isset($_POST) && count($_POST) > 0 && !isset($_POST['page'])) {
			//validate form
			if (!$this->error) {
				//process submit
				$cnt = 1;
				$cntUpdate = 1;
				foreach($_POST['storeCommission'] as $key=>$value){					
					$checkCommission = $this->agent_model->checkCommissionExistByVProducts($agentID, $key);
					$data = array(
						'agentID'			=> $agentID,
						'productID'			=> trim($_POST['productList'][$key]),
						'commissionRate'	=> trim($value)!=''?trim($value):0,
						'enteredBy'			=> $this->session->userdata('username').' - '.$this->session->userdata('usertype'),
						'note'				=> '',
						'createdDate'		=> date('Y-m-d H:i:s'),
						'vproductID'		=> $key
					);
					if($checkCommission){ 
						$this->agent_model->updateCommission($checkCommission->ID, $data);
						$this->session->set_userdata('message', ' You have updated commission successfully!');
						$cntUpdate++;
					}else {
						$this->agent_model->addCommission($data);
						$this->session->set_userdata('message', ' You have added commission successfully!');
						$cnt++;
					}	
				
				}
				$cnt++;			
				redirect(site_url('store/commission/'.$agentID));
			}
			$this->data += $_POST;
		}
		//assign error
		$this->data['error'] = $this->error;
		
		//assign list commissionRateHistory
		$this->data['results'] = $this->agent_model->getCommissions($agentID);
		
		// All Products================================		
		$infoParent = $this->getParentAgentInfo($info->parentAgentID);		// Check Parent Agent Type
		
		// get Product by category
		if($productType == 'rtr'){
			$productCategory = 'Rtr';
		}elseif($productType == 'pin'){
			$productCategory = 'Pin';
		}elseif($productType == 'calling-card'){
			$productCategory = 'Calling Card';
		}else{
			$productCategory = 'Pinless';
		}
		
		if($infoParent->agentTypeID==4){
			$getTotalProducts = $this->admin_model->getTotalProductsByAgent($infoParent->parentAgentID, array('vp.vproductCategory' => $productCategory));
			//$getAllProducts = $this->admin_model->getAllProductsByAgent($infoParent->parentAgentID, array('vp.vproductCategory' => $productCategory));
			if($getTotalProducts > 300){
				$limit = 200;
				$paging_data = array(
					'limit'   => $limit,
					'current' => $page,
					'total'   => $getTotalProducts
				);
				$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
				$offset 			  = ($paging_data['current'] - 1) * $limit;
				$getAllProducts   	  = $this->admin_model->getAllProductsByAgent($infoParent->parentAgentID, array('vp.vproductCategory' => $productCategory), $limit, $offset);
			}else{
				$getAllProducts   	  = $this->admin_model->getAllProductsByAgent($infoParent->parentAgentID, array('vp.vproductCategory' => $productCategory));
			}
		}else{
			$getTotalProducts = $this->admin_model->getTotalProductsByAgent($info->parentAgentID, array('vp.vproductCategory' => $productCategory));
			
			if($getTotalProducts > 300){
				$limit = 200;
				$paging_data = array(
					'limit'   => $limit,
					'current' => $page,
					'total'   => $getTotalProducts
				);
				$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
				$offset 			  = ($paging_data['current'] - 1) * $limit;
				$getAllProducts   	  = $this->admin_model->getAllProductsByAgent($info->parentAgentID, array('vp.vproductCategory' => $productCategory), $limit, $offset);
			}else{
				$getAllProducts   	  = $this->admin_model->getAllProductsByAgent($info->parentAgentID, array('vp.vproductCategory' => $productCategory));
			}
			
		}
			
		$this->data['getAllProducts'] = $getAllProducts;
		$this->data['productCat'] 	  = $productType;
		// All Products END ================================
		
		//option_max_store_commission
		$max_store_commission_array 	 = array();
		$max_store_commission_array['0'] = '-- Select --';
		$this->data['option_max_store_commission'] = $max_store_commission_array;
		
		$this->data['current_page'] 	= 'agent';
		$this->data['sub_current_page'] = 'commission';
		$this->load->view('agent/_agent', $this->data);
	}
	public function commission_delete($agentID) {
		$data = array('success'=>0,'text'=>'Delete fail! Please try again!');
		$this->agent_model->deleteCommission($_POST['id']);
		$this->session->set_userdata('message', 'You have deleted commission successfully!');
		$data = array('success'=>1);
		echo json_encode($data);
	}
	public function reset_commission(){
		$data = array('success'=>0,'text'=>'Commission reset process fail! Please try again!');
		$this->agent_model->deleteCommissionByAgent( array('agentID' => $_POST['id']) );
		$this->session->set_userdata('message', 'You have reset store commission successfully!');
		$data = array('success'=>1, 'text' => 'You have reset store commission successfully!');
		echo json_encode($data);
	}
	
	
	public function payment($agentID) {
		//get data to agent info
		$info 	 = $this->getAgentInfo($agentID);
		$repInfo = $this->getAccountRepInfo();
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
							'agentID'				=> $agentID,
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
						$this->agent_model->updateBalance($agentID, $totalCredit);
						
						//reload page
						$this->session->set_userdata('message', $this->lang->line('msg_success_add_payment'));
						redirect(site_url('store/payment/'.$agentID));
					}					
				}
			}
			//Payment by stored card END ======
			
			// Payment by new card =====
			if ($this->validateRechargeAccount($_POST)) { 
				$transaction    = NULL;
				$dataCreditcard = $_POST;					
				$transaction    = $this->creditcard($dataCreditcard, 'authorize_and_capture');
				if (!$this->error) {
					$accountRepCommission = 0;
					$createdDate = date('Y-m-d H:i:s');
					//insert to payment
					$data = array(
						'agentID'				=> $agentID,
						'transactionID'			=> $transaction['transactionID'],
						'chargedAmount'			=> $_POST['chargedAmount'],
						'paymentMethodID'		=> 1, 	// Credit card
						'enteredBy'				=> $this->session->userdata('rep_username').' - '.$this->session->userdata('rep_usertype'),
						'ipAddress'				=> $this->input->ip_address(),
						'paidTo'				=> 'System Admin',
						'accountRepCommission'	=> $accountRepCommission,
						'collectedByCompany'	=> 1,
						'dateCollectedByCompany'=> $createdDate,
						'accountRepID'			=> $this->session->userdata('rep_userid'),
						'comment'				=> $_POST['comment'],
						'createdDate'			=> $createdDate
					);
					$this->payment_model->addAgentPayment($data);
					
					//update balance
					$this->agent_model->updateBalance($agentID, (float)$_POST['chargedAmount']);
					
					// Store Card details on server #CIM#
					if($_POST['saveCard']){
						$authProfileID = null;
						if($info->authProfile)
							$authProfileID = $info->authProfile; 
						
						$agentPaymentProfile = $this->storeCreditCard($_POST, $agentID, $authProfileID);	
						
						// Save card profile ==================
						$data = array('agentID'				=> $agentID,
									  'cimCardNumber'		=> $transaction['cardName'],
									  'cimProfileID'		=> $agentPaymentProfile['profile'],
								      'cimShippingProfileID'=> $agentPaymentProfile['shippingProfile'],
									  'cimPaymentProfileID'	=> $agentPaymentProfile['paymentProfile'],
									  'profile_DateTime'	=> date('Y-m-d H:i:s'));
						
						if(!$this->error){
							$this->payment_model->addCardDetails($data);
						}						
						
						// Update Profile For Agent ======
						$this->agent_model->update($agentID, array('authProfile' => $agentPaymentProfile['profile']));
					}
					
					//reload page
					$this->session->set_userdata('message', 'You have make payment successfully!');
					redirect(site_url('store/payment/'.$agentID));
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
		$profiles 	 	   = $this->payment_model->getAgentPaymentProfiles(array('agentID'=>$agentID));
		foreach ($profiles as $item) {
			$profile_array[$item->profileID] = $item->cimCardNumber;
		}
		$this->data['paymentProfiles'] = $profile_array;
		
		$this->data['current_page'] 	= 'agent';
		$this->data['sub_current_page'] = 'payment';
		$this->load->view('agent/_agent', $this->data);
	}
	
	public function payment_receipt($paymentID) {
		//get payment detail
		$this->data['payment'] = $this->payment_model->getAgentPayment($paymentID);
		$this->load->view('agent/payment_receipt', $this->data);
	}
	
	public function delete_payment($agentID) {
		$data = array('success'=>0, 'text'=>'You can not delete this payment!');
		//get agent info
		$agentInfo = $this->getAgentInfo($agentID);
		//get payment detail
		$payment = $this->payment_model->getAgentPayment($_POST['paymentID']);
		//enteredby
		$enteredBy = $this->session->userdata('rep_username').' - '.$this->session->userdata('rep_usertype');
		if ($agentInfo && $payment && !$payment->deleted && $payment->accountRepID == $this->session->userdata('rep_userid') && $payment->enteredBy == $enteredBy) {
			if ($agentInfo->balance >= $payment->chargedAmount) {
				//insert to payment
				$chargedAmount = -($payment->chargedAmount);
				$accountRepCommission = -($payment->accountRepCommission);
				$data = array(
					'agentID'				=> $agentID,
					'chargedAmount'			=> $chargedAmount,
					'paymentMethodID'		=> $payment->paymentMethodID,
					'enteredBy'				=> $enteredBy,
					'ipAddress'				=> $this->input->ip_address(),
					'paidTo'				=> $payment->paidTo,
					'comment'				=> 'Delete Payment Above',
					'accountRepID'			=> $payment->accountRepID,
					'accountRepCommission'	=> $accountRepCommission,
					'collectedByCompany'	=> 0,
					'dateCollectedByCompany'=> NULL,
					'deleted'				=> 1,
					'createdDate'			=> date('Y-m-d H:i:s')
				);
				$this->payment_model->addAgentPayment($data);
				
				//update balance
				$this->agent_model->updateBalance($agentID, $chargedAmount);
				
				//update payment to deleted
				$this->payment_model->updateAgentPayment($_POST['paymentID'], array('deleted'=>1));
				
				$data = array('success'=>1);
				$this->session->set_userdata('message', 'You have deleted payment successfully!');
			} else {
				$data = array('success'=>0, 'text'=>'You can not delele this payment. Store balance is not enough to deduct.');
			}
		}
		echo json_encode($data);
	}
	
	public function update_collected_by_company() {
		$data = array('success'=>0);
		if (isset($_POST['paymentID']) && isset($_POST['collectedByCompany'])) {
			$dateCollectedByCompany = NULL;
			$textCollectedByCompany = 'No';
			if ($_POST['collectedByCompany'] == 1) {
				$dateCollectedByCompany = date('Y-m-d H:i:s');
				$textCollectedByCompany = 'Yes';
			}
			$this->payment_model->updateAgentPayment($_POST['paymentID'], array('collectedByCompany'=>$_POST['collectedByCompany'], 'dateCollectedByCompany'=>$dateCollectedByCompany));
			$data = array(
				'success'=>1,
				'textCollectedByCompany'=>$textCollectedByCompany, 
				'dateCollectedByCompany'=>$dateCollectedByCompany?date('m/d/Y H:i:s A', strtotime($dateCollectedByCompany)):''
			);
		}
		echo json_encode($data);
	}
	
	public function sale_query($agentID) {
		$info = $this->getAgentInfo($agentID);
		
		if (isset($_POST) && count($_POST) > 0) {
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date = date('Y-m-d', strtotime($_POST['to_date']));
			if ($_POST['button_type'] == 'period') {
				switch ($_POST['time_period']) {
					case "today":
						$from_date = date('Y-m-d');
						$to_date = date('Y-m-d');
						break;
					case "yesterday":
						$from_date = date("Y-m-d", strtotime("yesterday"));
						$to_date = date("Y-m-d", strtotime("yesterday"));
						break;
					case "month":
						$from_date = date('Y-m').'-01';
						$to_date = date('Y-m-d');
						break;
					case "year":
						$from_date = date('Y').'-01-01';
						$to_date = date('Y-m-d');
						break;
					default:
				}
			}
			
			$paging_data = array(
				'limit' => $this->config->item('num_per_page'),
				'current' => $_POST['page'] > 1 ? $_POST['page'] : 1,
				'total' => $this->payment_model->getTotalStoreSaleQuery($agentID, $from_date, $to_date)
			);
			$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
			$offset = ($paging_data['current'] - 1) * $this->config->item('num_per_page');
			
			$this->data['results'] = $this->payment_model->getStoreSaleQuery($agentID, $from_date, $to_date, $this->config->item('num_per_page'), $offset);
			$this->data['results_from_date'] = $from_date;
			$this->data['results_to_date'] = $to_date;
			$this->data += $_POST;
		}
		//assign error
		$this->data['error'] 	= $this->error;
		
		//$this->data['option_time_period'] = array('' => '-- All --', 'today' => 'Today', 'yesterday' => 'Yesterday', 'month' => 'This Month', 'year' => 'Year to Date');
		
		$this->data['current_page'] 	= 'agent';
		$this->data['sub_current_page'] = 'sale_query';
		$this->load->view('agent/_agent', $this->data);
	}
	
	//Account Rep ===========================================================================
	public function calculate_agent($agentID) {
		$from_date = date('Y-m-d 00:00:00');
		$to_date = date('Y-m-d 23:59:59');
		switch ($_POST['time_period']) {
			case "today":
				$from_date = date('Y-m-d 00:00:00');
				$to_date = date('Y-m-d H:i:s');
				break;
			case "yesterday":
				$from_date = date("Y-m-d", strtotime("yesterday")).' 00:00:00';
				$to_date = date("Y-m-d", strtotime("yesterday")).' 23:59:59';
				break;
			case "month":
				$from_date = date('Y-m').'-01 00:00:00';
				$to_date = date('Y-m-d H:i:s');
				break;
			case "year":
				$from_date = date('Y').'-01-01 00:00:00';
				$to_date = date('Y-m-d H:i:s');
				break;
			default:
				
		}
		$response = $this->agent_model->getAgentSaleQuery($agentID, $from_date, $to_date);
		if ($response) {
			$response->success = 1;
			$response->Sale = format_price($response->Sale);
			$response->Promotion = format_price($response->Promotion);
			$response->Payment = format_price($response->Payment);
			$response->Commission = format_price($response->Commission);
		} else {
			$response = array('success'=>0,'text'=>'Data not found!');
		}
		echo json_encode($response);
	}
	
	//SUB-Distributors ======================================================================
	public function sub_distributor_account_manager(){
		if (isset($_POST) && count($_POST) > 0) {
			$where = array();
			$where['parentAgentID'] = (int)$this->session->userdata('rep_userid');
			switch ($_POST['search_key']) {
				case "search_by_agent_code":
					$where = "SUBSTRING(securityCode,4,7) = '{$_POST['search_value']}'";
					break;
				case "search_by_agent_login_id":
					$where['loginID'] = $_POST['search_value'];
					break;
				case "search_by_agent_lastName":
					$where = "(firstName like '{$_POST['search_value']}' OR lastName like '{$_POST['search_value']}')";
					break;
				case "search_by_agent_email":
					$where['email'] = $_POST['search_value'];
					break;
			}
			
			//start export excel
			if ($_POST['excel'] == 1) {
				header("Content-type: application/x-msdownload");
				header("Content-Disposition: attachment; filename=".date('Ymd')."_customer_account.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$results = $this->agent_model->getAgents($where, 1);
				print $this->load->view('agent_account_manager_excel', array('results'=>$results), true); die; 
			}
			//end export excel
			
			$paging_data = array(
				'limit' => $this->config->item('num_per_page'),
				'current' => $_POST['page'] > 1 ? $_POST['page'] : 1,
				'total' => $this->agent_model->getTotalAgents($where, 4)
			);
			$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
			$offset 			  = ($paging_data['current'] - 1) * $this->config->item('num_per_page');
			$results 			  = $this->agent_model->getAgents($where, 4, $this->config->item('num_per_page'), $offset);
			$this->data[$_POST['search_key']] = $_POST['search_value'];
			
			$mergeResult = array();
			foreach($results as $repID){
				$totalSubAgent = $this->agent_model->getTotalAgents(array('parentAgentID'=>$repID->agentID), 4);
				$repID->totalAgents = $totalSubAgent;
				$mergeResult[] = $repID;
			}
			
			$this->data['results'] = $mergeResult;
			
			$this->data += $_POST;
		}
		
		$this->data['current_page'] = 'subDis';
		$this->load->view('sub_distributor_manager', $this->data);
	}
	public function add_new_distributor(){
		$info = $this->getAccountRepInfo();
		if (isset($_POST) && count($_POST) > 0) {
			//process submit
			if ($this->validateDistFrom($_POST)) {
				
				$phoneEmail = $this->getPhoneEmail(trim( preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone'])) ));
				
				if(!$this->error){
					$createdDate = date('Y-m-d H:i:s');
					$authProfile = $this->getAuthProfile($_POST['email']);
					$data = array(
						'company_name'		=> trim($_POST['company_name']),
						'firstName'			=> trim($_POST['firstName']),
						'lastName'			=> trim($_POST['lastName']),
						'phone'				=> trim( preg_replace('/[^0-9]+/','', strip_tags($_POST['phone'])) ),
						'cellPhone'			=> trim( preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone'])) ),
						'email'				=> trim($_POST['email']),
						'address'			=> trim($_POST['address']),
						'city'				=> trim($_POST['city']),
						'stateID'			=> trim($_POST['state']),
						'zipCode'			=> trim($_POST['zip']),
						'address2'			=> trim($_POST['address2']),
						'loginID'			=> trim($_POST['agentLoginID']),
						'password'			=> trim(md5($_POST['agentPassword'])),
						'securityCode'		=> (string)time(),
						'agentTypeID'		=> 4,		// Agent Type 4 = Subdistributor
						'parentAgentID'		=> (int)$this->session->userdata('rep_userid'),
						'status'			=> 1,
						'createdDate'		=> $createdDate,
						'authProfile'		=> $authProfile?$authProfile:'',
						'phoneEmailID'		=> addslashes($phoneEmail)
					);
					$agentID = $this->agent_model->add($data);
					
					// Text Send to Distributor's cell number
					$textMessage = "Congrats! You have joined vonecall.com as a store";
					$subject	 = "New Store on Vonecall";
					$this->sendText($textMessage, $subject, $phoneEmail);
					
					$this->session->set_userdata('message', 'You have added new distributor successfully!');
					redirect(site_url('distributor/profile/'.$agentID));
				}				
			}
			$this->data += $_POST;
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
		$this->data['option_state'] = $option_state;
		
		$this->data['current_page'] = 'subDis';  
		$this->load->view('add_new_account_subdist', $this->data);
	}
	public function distributor_profile($agentID) {
		$info = $this->getAgentInfo($agentID);
			
		if (isset($_POST) && count($_POST) > 0) {
			//process submit
			if ($this->validateDistFrom($_POST, $agentID)) {
				$data = array(
					'company_name'	=> trim($_POST['company_name']),
					'firstName'		=> trim($_POST['firstName']),
					'lastName'		=> trim($_POST['lastName']),
					'email'			=> trim($_POST['email']),
					'phone'			=> trim(  preg_replace('/[^0-9]+/','', strip_tags($_POST['phone']))  ),
					//'cellPhone'		=> trim($_POST['cellphone']),
					'address'		=> trim($_POST['address']),
					'city'			=> trim($_POST['city']),
					'stateID'		=> trim($_POST['state']),
					'zipCode'		=> trim($_POST['zip']),
					'address2'		=> trim($_POST['address2']),
				);
				
				if($_POST['password']) 
					$data = array_merge($data, array('password' => md5($_POST['password'])));
				
				if($info->cellPhone == preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))){ 
					$data = array_merge($data, array('cellPhone' => trim( preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))) ));
				}else{
					$phoneEmail = $this->getPhoneEmail(trim( preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone'])) ));
					$data = array_merge($data, array('cellPhone' => trim( preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone'])) ), 'phoneEmailID' => addslashes($phoneEmail) ));
				}
				
				if(!$this->error){
					$this->agent_model->update($agentID, $data);
					
					//redirect to list page
					$this->session->set_userdata('message', 'You have updated profile successfully!');
					redirect(site_url('distributor/profile/'.$agentID));
				}			
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
		
		$this->data['current_page'] 	= 'subDis';
		$this->data['sub_current_page'] = 'profile';
		$this->load->view('distributor/_distributor', $this->data);
	}
	public function delete_distributor() {
		$info = $this->getAgentInfo($_POST['id']);
		$data = array('success'=>0,'text'=>'Please try again!');
		$totalSubAgent = $this->agent_model->getTotalAgents(array('parentAgentID'=>$_POST['id']), 2);
		if ($totalSubAgent > 0) {
			$data = array('success'=>0,'text'=>'Error: This Distributor has respect to the Store, Please remove Store out of this account rep before!');
		} else {
			$this->agent_model->delete($_POST['id']);
			
			// Delete Commission Records ======
			$this->agent_model->deleteCommissionByAgent(array('agentID' => $_POST['id']));
			
			// Delete Payment Records ======
			$this->payment_model->deleteAgentPaymentByAgentID(array('agentID' => $_POST['id']));
			
			// delete authorize.net Profile
			if($info->authProfile){
				$this->deleteAuthProfile($info->authProfile);
				$this->payment_model->deletePaymentProfile(array('agentID' => $_POST['id']));
			}
			
			// Delete Customers =====
			//$this->customer_model->deleteCustomersByAgent(array('accountRepID' => $_POST['id']));
			
			$data = array('success'=>1,'text'=>'You have deleted distributor successfully!');
			
			// Report 
			$this->adminReport('Deleted distributor '.$info->firstName.'`s Profile');
		}
		echo json_encode($data);
	}
	
	public function sub_distributor_store_account_manager(){
		if (isset($_POST) && count($_POST) > 0) {
			$where = array();
			/*$where['parentAgentID'] = (int)$this->session->userdata('rep_userid');
			switch ($_POST['search_key']) {				
				case "search_by_sub_dist":
					$where['parentAgentID'] = $_POST['agent'];
					break;				
			}*/
			if (strlen(trim($_POST['agent'])) < 1) {
				$this->error['agent'] = 'Distributor is required!';
			} 
			if(!$this->error){
				$where['parentAgentID'] = $_POST['agent'];
				$paging_data = array(
					'limit' => $this->config->item('num_per_page'),
					'current' => $_POST['page'] > 1 ? $_POST['page'] : 1,
					'total' => $this->agent_model->getTotalAgents($where, 2)
				);
				$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
				$offset 			  = ($paging_data['current'] - 1) * $this->config->item('num_per_page');
				$results 			  = $this->agent_model->getAgents($where, 2, $this->config->item('num_per_page'), $offset);
				//$this->data[$_POST['search_key']] = $_POST['search_value'];
			}
			
			$this->data['results'] = $results;
			$this->data += $_POST;
			
		}
		
		//Stores
		$option_subdist = array();
		$option_subdist[''] = '-- All --';
		$agents = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')), 4);
		foreach ($agents as $item) {
			$option_subdist[$item->agentID] = $item->company_name;
		}
		$this->data['option_sub_dist'] = $option_subdist;
		
		$this->data['current_page'] = 'subDis';
		$this->load->view('sub_distributor_store_manager', $this->data);
	}
	public function sub_distributor_store_profile($agentID){
		$info = $this->getAgentInfo($agentID);
			
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
		
		//assign error
		$this->data['error'] = $this->error;				
				
		//state
		$option_state = array();
		$option_state[''] = '-- Select --';
		$states = $this->customer_model->getStates();
		foreach ($states as $item) {
			$option_state[$item->stateID] = $item->stateName;
		}
		$this->data['option_state'] 	= $option_state;
		
		$this->data['current_page'] 	= 'subDis';
		$this->load->view('sub_distributor_store_profile', $this->data);
	}
	//SUB-Distributors END ==================================================================
	
	private function validateAgentFrom($data, $agentID=0) {
		if (strlen(trim($data['storeName'])) < 1) {
			$this->error['storeName'] = 'Store name is required!';
		} 
		if (strlen(trim($data['firstName'])) < 1) {
			//$this->error['firstName'] = 'First name is required!';
		} elseif (strlen(trim($data['firstName'])) > 15) {
			$this->error['firstName'] = 'Maximum 15 character!';
		}
		if (strlen(trim($data['lastName'])) < 1) {
			//$this->error['lastName'] = 'Last name is required!';
		} elseif (strlen(trim($data['lastName'])) > 25) {
			$this->error['lastName'] = 'Maximum 25 character!';
		}
		if (strlen(trim($data['phone'])) > 0 && !is_numeric(trim(  preg_replace('/[^0-9]+/','', strip_tags($data['phone'])) ))) {
			$this->error['phone'] = 'Invalid phone number!';
		}
		if (strlen(trim($data['cellphone'])) > 0 && !is_numeric(  preg_replace('/[^0-9]+/','', strip_tags($data['cellphone'])) )) {
			$this->error['cellphone'] = 'Invalid cell phone number!';
		}
		if (strlen(trim($data['email'])) < 1) {
			$this->error['email'] = 'Your Email is required!';
		} elseif (!$this->form_validation->valid_email($data['email'])) {
			$this->error['email'] = 'Your Email is invalid!';
		}elseif ($this->agent_model->checkAgentEmailExist($data['email'], $agentID)) {
			$this->error['email'] = 'Email is already existed!';
		}
		
		if(!$data['edit']){
			if (isset($data['password'])) {
				if (strlen(trim($data['password'])) < 1) {
					$this->error['password'] = 'Store password is required!';
				}
			}
		}
		if ($agentID==0) {
			if (strlen(trim($data['agentLoginID'])) < 1) {
				$this->error['agentLoginID'] = 'User name is required!';
			} elseif ($this->agent_model->checkAgentExist($data['agentLoginID'], $agentID)) {
				$this->error['agentLoginID'] = 'User name is aready existed!';
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
		if (strlen(trim($data['phone'])) < 1) {
			$this->error['phone'] = 'Phone number is required!';
		} elseif (strlen(trim($data['phone'])) > 0 && !is_numeric(trim(  preg_replace('/[^0-9]+/','', strip_tags($data['phone'])) ))) {
			$this->error['phone'] = 'Invalid phone number!';
		}
		if (strlen(trim($data['cellphone'])) < 1) {
			$this->error['cellphone'] = 'Cellphone number is required!';
		} elseif (strlen(trim($data['cellphone'])) > 0 && !is_numeric(trim(  preg_replace('/[^0-9]+/','', strip_tags($data['cellphone'])) ))) {
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
	private function getAccountRepInfo() {
		//time_period array
		$this->data['option_time_period'] = array('today' => 'Today', 'yesterday' => 'Yesterday', 'month' => 'This Month', 'year' => 'Year to Date');
		
		//agent info
		$info = $this->agent_model->getAgent((int)$this->session->userdata('rep_userid'));
		if (!$info) {
			$this->admin_model->logout();
			redirect('login');
		}
		$this->data['repinfo'] = $info;
		
		return $info;
	}
	private function getAgentInfo($id) {
		//time_period array
		$this->data['option_time_period'] = array('today' => 'Today', 'yesterday' => 'Yesterday', 'month' => 'This Month', 'year' => 'Year to Date');
		
		//agent info
		$info = $this->agent_model->getAgent((int)$id);
		if (!is_numeric($id)|| !$info) {
			show_error('Store you requested was not found.', 404, 'Store is not found!');
		}
		$agentPayment			= $this->agent_model->getTotalAgentPayment($id, '2014-01-01 00:00:00', date('Y-m-d H:i:s'));
		$agentSale 				= $this->agent_model->getTotalAgentSale($id, '2014-01-01 00:00:00', date('Y-m-d H:i:s'));
		//echo '<pre>';print_r($agentSale);die;
 		$info->TotalSale 		= format_price($agentSale->Sale);
		$info->TotalPayment 	= format_price($agentPayment->Payment);
		$info->TotalCommission 	= format_price($agentSale->Commission);
		
		$this->data['agentID'] 	= $id;
		$this->data['info'] 	= $info;
		
		return $info;
	}
	private function getParentAgentInfo($id){
		$info = $this->agent_model->getAgent((int)$id);
		$this->data['distInfo'] = $info;
		return $info;
	}
	public function get_max_store_commission($agentID=0, $productID=0) {
		$max = 0;
		$commission = $this->agent_model->getCommission((int)$agentID, (int)$productID);
		if ($commission) {
			$max = $commission->maxStoreCommission;
		}
		$ret = '<option value="">-- Select --</option>';
		for ($i=1;$i<=$max;$i++) {
			$ret .= '<option value="'.$i.'">'.$i.'%</option>';
		}
		echo $ret;
		/*
		$ret = 10;
		$info = $this->agent_model->getAgent((int)$agentID);
		if ($info && $info->maxStoreCommission > 0) {
			$ret = $info->maxStoreCommission;
		}
		echo json_encode($ret);
		*/
	}
	
	// Reports ========================================================
	public function sales_report() {
		$info = $this->getAccountRepInfo();
		if (isset($_POST) && count($_POST) > 0) {
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date = date('Y-m-d', strtotime($_POST['to_date']));
			$agentID = $_POST['agent'];
			if ($_POST['button_type'] == 'period') {
				$agentID = 0;
				switch ($_POST['time_period']) {
					case "today":
						$from_date = date('Y-m-d');
						$to_date = date('Y-m-d');
						break;
					case "yesterday":
						$from_date = date("Y-m-d", strtotime("yesterday"));
						$to_date = date("Y-m-d", strtotime("yesterday"));
						break;
					case "month":
						$from_date = date('Y-m').'-01';
						$to_date = date('Y-m-d');
						break;
					case "year":
						$from_date = date('Y').'-01-01';
						$to_date = date('Y-m-d');
						break;
					default:
						$from_date = '2012-01-01';
						$to_date = date('Y-m-d');
				}
			}
			
			// Get All Stores ====
			$getAllStores = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')));
			
			if($info->agentTypeID == 4){	
				if($agentID){
					$storeSaleReport = $this->payment_model->getStorePaymentReportByDist($info->parentAgentID, $agentID, $from_date, $to_date);
				}else{
					//get Report====
					$allStoresReports = array();
					foreach($getAllStores as $item){
						$allStoresReports[] = $this->payment_model->getStorePaymentReportByDist($info->parentAgentID, $item->agentID, $from_date, $to_date);						
					}				
					
					//change final multi array to single array
					$final_array =array();
					foreach ($allStoresReports as $val) {
					    foreach($val as $val2) {
					        $final_array[] = $val2;
					     }
					 }																		
					
					if(count($final_array) > 0){		## IF Record Found
						$storeSaleReport = $this->subvalsort($final_array,'createdDate', 'DESC');	
					}else{								## if no records
						//$storeSaleReport = $allStoresReports;
						if(count($allStoresReports[0]) > 0)								## if no records
							$storeSaleReport = $allStoresReports;
						else {
							$storeSaleReport = array( );
						}
					}//echo '<pre>';print_r($allStoresReports);die;
					
				}							
			}else{
				//Check Valid Store Report =======
				$storeSaleReport = $this->payment_model->getStorePaymentReportByDist($this->session->userdata('rep_userid'), $agentID, $from_date, $to_date);
				$allStoreSaleReport = array();
				foreach($storeSaleReport as $items){
					$allStoreSaleReport[] = $items->agentID; 	
				}
				
				//Check Valid Store Report =======
				$allStoresDist = array();
				foreach($getAllStores as $item){
					$allStoresDist[] = $item->agentID; 	
				}	
				
				##### Filter Parent distributor's store reports and sub-dist's store reports #####
				$allSalesReport = array();
				foreach($allStoresDist as $key => $value){
					if(in_array($value, $allStoreSaleReport)){
						$allSalesReport[] = $this->payment_model->getStorePaymentReportByDist($this->session->userdata('rep_userid'), $value, $from_date, $to_date);
					}
				}
				
				##### change final multi array to single array #####				
				$final_array =array();
				foreach ($allSalesReport as $val) {
				    foreach($val as $val2) {
				        $final_array[] = $val2;
				     }
				}
				
				if(count($final_array) > 0){
					$storeSaleReport = $this->subvalsort($final_array, 'createdDate', 'DESC');	
				}else{
					$storeSaleReport = $allSalesReport;
				}						
			}	
						
			$this->data['store_payments']   = $storeSaleReport;
			$this->data['result_from_date'] = $from_date;
			$this->data['result_to_date']   = $to_date;			
			$this->data += $_POST;
		}
		$this->data['error'] = $this->error;
		
		//Stores
		$option_stores = array();
		$option_stores['0'] = '-- All --';
		$agents = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')), 2);
		foreach ($agents as $item) {
			$option_stores[$item->agentID] = $item->company_name;
		}
		$this->data['option_agent'] = $option_stores;
		
		//time_period
		$this->data['option_time_period'] = array('' => '-- All --', 'today' => 'Today', 'yesterday' => 'Yesterday', 'month' => 'This Month', 'year' => 'Year to Date');
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'sales_report';
		
		$this->load->view('reports/_reports', $this->data);
	}
	public function commission_report() {
		$info = $this->getAccountRepInfo();
		if (isset($_POST) && count($_POST) > 0) {
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date = date('Y-m-d', strtotime($_POST['to_date']));
			$agentID = $_POST['agent'];
			if ($_POST['button_type'] == 'period') {
				$agentID = 0;
				switch ($_POST['time_period']) {
					case "today":
						$from_date = date('Y-m-d');
						$to_date = date('Y-m-d');
						break;
					case "yesterday":
						$from_date = date("Y-m-d", strtotime("yesterday"));
						$to_date = date("Y-m-d", strtotime("yesterday"));
						break;
					case "month":
						$from_date = date('Y-m').'-01';
						$to_date = date('Y-m-d');
						break;
					case "year":
						$from_date = date('Y').'-01-01';
						$to_date = date('Y-m-d');
						break;
					default:
						$from_date = '2012-01-01';
						$to_date = date('Y-m-d');
				}
			}
			
			$commissionReports = $this->payment_model->getStorePaymentReportByDist($this->session->userdata('rep_userid'), $agentID, $from_date, $to_date);
			$this->data['commission_reports'] = $commissionReports;
			$this->data['result_from_date']   = $from_date;
			$this->data['result_to_date']     = $to_date;		
			$this->data += $_POST;
		}
		$this->data['error'] = $this->error;
			
		//Stores
		$option_stores = array();
		$option_stores['0'] = '-- All --';
		$agents = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')), 2);
		foreach ($agents as $item) {
			$option_stores[$item->agentID] = $item->company_name;
		}
		$this->data['option_agent'] = $option_stores;
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'commission_report';
		
		$this->load->view('reports/_reports', $this->data);
	}
	public function balance_report() {		
		$info = $this->getAccountRepInfo();
		if (isset($_POST) && count($_POST) > 0) {
			/*if ( strlen(trim($_POST['agent'])) < 1 ) {
				$this->error['agent'] = 'Store required!';
			}*/
			if(!$this->error){
				//$info 	 = $this->getAgentInfo($_POST['agent']);
				//$from_date = date('Y-m-d 00:00:00', strtotime($_POST['from_date']));
				//$to_date   = date('Y-m-d 23:59:59', strtotime($_POST['to_date']));
				if($_POST['agent']<1){
					$allAgents = array();
					$getAllAgentsByDist = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')));
					foreach($getAllAgentsByDist as $item){
						$allAgents[$item->agentID] = $item->company_name;
					}
					
					/*
					//get Report
					$allAgentsDetails = array();
					foreach($allAgents as $key=>$item){
						$allAgentsDetails[] = $this->agent_model->getAgentBalanceQuery($key, $from_date, $to_date);	
					}*/
					$this->data['details'] = $getAllAgentsByDist;
				}else{
					//$this->data['details'] = $this->agent_model->getAgentBalanceQuery($_POST['agent'], $from_date, $to_date);
					$this->data['details'] = $this->agent_model->getAgents(array( 'agentID' => $_POST['agent'] ));	
				}				
			}					
			$this->data += $_POST;
		}
		//assign error
		$this->data['error'] 	= $this->error;
		
		//month array
		$this->data['option_month'] = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
		
		//year array
		$year_array = array();
		$year_array[''] = '-- Year --';
		for ($i=1990;$i<=(int)date('Y');$i++) {
			$year_array[$i] = $i;
		}
		$this->data['option_year'] = $year_array;
		
		//Stores
		$option_stores = array();
		$option_stores[''] = '-- All --';
		$agents = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')), 2);
		foreach ($agents as $item) {
			$option_stores[$item->agentID] = $item->company_name;
		}
		$this->data['option_agent'] = $option_stores;
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'balance_report';
		$this->load->view('reports/_reports', $this->data);
	}
	public function sub_distributor_report(){
		$info = $this->getAccountRepInfo();
		if (isset($_POST) && count($_POST) > 0) {
			/*if (strlen(trim($_POST['sub_dist'])) < 1) {
				$this->error['sub_dist'] = 'Distributor Required!';
			}*/
			
			if(!$this->error){
				$from_date = date('Y-m-d', strtotime($_POST['from_date']));
				$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
				$agentID   = $_POST['agent'];
				$parentID  = $_POST['sub_dist'];
				
				if($parentID){		######## IF Report Search By SELECTED Sub Distributor ##########
					$getStoresBySubDist = $this->getParentAgentInfo($parentID);
					
					if($getStoresBySubDist->agentTypeID==4)
						$parentID = $getStoresBySubDist->parentAgentID;
					
					if($agentID){			######## IF Report Search By SELECTED Sub Distributor and Selected Store ##########
						$storeSaleReport = $this->payment_model->getStorePaymentReportByDist($parentID, $agentID, $from_date, $to_date);
						$this->data['store_payments']   = $storeSaleReport;	
					}else{					######## IF Report Search By ALL Sub Distributor and ALL Stores ##########
						// Get All Stores ====
						$allStoreBySubDist = array();
						$getAllStores = $this->agent_model->getAgents(array('parentAgentID' => $getStoresBySubDist->agentID));
						foreach($getAllStores as $item){
							$allStoreBySubDist[$item->agentID]= $item->company_name;
						}
						
						//get Report====
						$allStoresReports = array();
						foreach($allStoreBySubDist as $agent_id=>$companyName){
							$allStoresReports[] = $this->payment_model->getStorePaymentReportByDist($parentID, $agent_id, $from_date, $to_date);
						}
						
						//change final multi array to single array
						$final_array =array();
						foreach ($allStoresReports as $val) {
						    foreach($val as $val2) {
						        $final_array[] = $val2;
						     }
						 }																		
						
						if(count($final_array) > 0){		## IF Record Found
							$storeSaleReport = $this->subvalsort($final_array,'createdDate', 'DESC');	
						}else{								## if no records
							if(count($allStoresReports[0]) > 0)								## if no records
							$storeSaleReport = $allStoresReports;
							else {
								$storeSaleReport = array( );
							}
						}
						
						$this->data['store_payments'] = $storeSaleReport;					
					}						
				}else{					######## IF Report Search By ALL Sub Distributor ##########
					$getAllSubDistByDist = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')), 4);
					
					// Get All Sub-Distributor
					$allSubDist = array();
					foreach($getAllSubDistByDist as $item){
						$allSubDist[$item->agentID] = $item->company_name;
					}
					
					// get All Stores By Subdistributor
					$allStoreBySubDist = array();
					foreach($allSubDist as $key => $item){
						$getAllStores = $this->agent_model->getAgents(array('parentAgentID' => $key));
						foreach($getAllStores as $items){
							$allStoreBySubDist[$items->agentID] = $items->parentAgentID;
						}
						
					}
					
					//get Report
					$allStoresReports = array();
					foreach($allStoreBySubDist as $agent_id=>$parentAgent){
						$allStoresReports[] = $this->payment_model->getStorePaymentReportByDist($this->session->userdata('rep_userid'), $agent_id, $from_date, $to_date);
					}
					
					//change final multi array to single array
					$final_array =array();
					foreach ($allStoresReports as $val) {
					    foreach($val as $val2) {
					        $final_array[] = $val2;
					     }
					 }																		
					
					if(count($final_array) > 0){		## IF Record Found
						$storeSaleReport = $this->subvalsort($final_array,'createdDate', 'DESC');	
					}else{
						if(count($allStoresReports[0]) > 0)								## if no records
							$storeSaleReport = $allStoresReports;
						else {
							$storeSaleReport = array( );
						}
					}
					
					$this->data['store_payments'] = $storeSaleReport;
					//$this->data['allStoreReports'] = $allStoresReports;					
				}				
				$this->data['result_from_date'] = $from_date;
				$this->data['result_to_date']   = $to_date;		
			}
				
			$this->data += $_POST;
		}
		$this->data['error'] = $this->error;
		
		//Stores
		$option_stores = array();
		$option_stores[''] = '-- All --';
		$this->data['option_agent'] = $option_stores;
			
		// Get all Sub-distributors ======
		$option_subdist = array();
		$option_subdist[''] = '-- All --';
		$agents = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')), 4);
		foreach ($agents as $item) {
			$option_subdist[$item->agentID] = $item->company_name;
		}
		$this->data['option_subdist'] = $option_subdist;
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'subdist_report';
		
		$this->load->view('reports/_reports', $this->data);
	}
	public function popup_access_number() {
		$this->data['results'] = $this->admin_model->getAccessNumbers();
		$this->load->view('popup_access_number', $this->data);
	}	
	public function popup_pinless_rate() {
		$where = array();
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['country']) > 0) {
				$where[] = "destination like '{$_POST['country']}%'";
			}			
			$this->data += $_POST;
		} else {
			$this->data['balance'] = 5;
		}
		$num_per_page = 20;//$this->config->item('num_per_page');
		$paging_data = array(
			'limit' => $num_per_page,
			'current' => (isset($_POST['page']) && $_POST['page'] > 1) ? $_POST['page'] : 1,
			'total' => $this->admin_model->getTotalRates(implode(' AND ', $where))
		);
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
		$offset = ($paging_data['current'] - 1) * $num_per_page;
		$this->data['results'] = $this->admin_model->getRates(implode(' AND ', $where), $num_per_page, $offset);
			
		$option_country = array(''=> '-- All --');
		$rates = $this->admin_model->getRates(array('terminationDate' => NULL));
		foreach ($rates as $item) {
			$option_country[trim($item->destination)] = trim($item->destination);
		}
		$this->data['option_country'] = $option_country;
		
		//option balance
		$this->data['option_balance'] = array('5'=>'5', '10'=>'10', '15'=>'15', '20'=>'20', '30'=>'30');
		
		$this->load->view('popup_rate', $this->data);
	}
	public function commission_rate_report() {
		$info    = $this->getAccountRepInfo();
		$agentID = $this->session->userdata('rep_userid');
		
		$num_per_page = 50;
		$paging_data = array(
			'limit'   => $num_per_page,
			'current' => (isset($_POST['page']) && $_POST['page'] > 1) ? $_POST['page'] : 1,
			'total'   => $this->admin_model->getTotalProductsByAgent($agentID)
		);
		$this->data['paging']  = $this->paging->do_paging_customer($paging_data);
		$offset 			   = ($paging_data['current'] - 1) * $num_per_page;
		$res = $this->admin_model->getAllProductsByAgent($agentID, $where='', $num_per_page, $offset);
		
		if (isset($_POST) && count($_POST) > 0) {
			if($_POST['page'] ==''){
				$searchKey 	= $_POST['productName'];
				$where 		= "vp.vproductName LIKE '%".$searchKey."%'";
				$getAllProducts = $this->admin_model->getAllProductsByAgent($agentID, $where, null, null);
				
				$this->data['results'] = $getAllProducts;
				
				$this->data += $_POST;	
			}else{
				$this->data['results'] = $this->admin_model->getAllProductsByAgent($agentID, $where='', $num_per_page, $offset);
			}			
			
		}else{			
			$this->data['results'] = $this->admin_model->getAllProductsByAgent($agentID, $where='', $num_per_page, $offset);
		}
		
		
		// Product Names
		$option_productName = array('' => '-- Product --');
		$products 			= $this->admin_model->getAllProductsByAgent($agentID, $where='');
		$productSort = $this->admin_model->unique_sort($products, 'vproductName');		
		foreach ($productSort as $key=>$value) {
			$option_productName[$value] = $value;
		}
		$this->data['option_productName'] = $option_productName;
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'commission_rates';
		
		$this->load->view('reports/_reports', $this->data);		
	}
	public function export_product_list(){
		$info    = $this->getAccountRepInfo();
		$agentID = $this->session->userdata('rep_userid');
		
		$results = $this->admin_model->getAllProductsByAgent($agentID, $where='', null, null);
		
		header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename=product_list.csv');
	    header('Pragma: no-cache');
	    header("Expires: 0");
	
	    $outstream = fopen("php://output", "w");
		$row = 1;
	    foreach($results as $item)
	    {
	    	if($row==1){
	    		fputcsv($outstream, array('Product Name'				 => 'Product Name',
	    								  'Product Sku Name'			 => 'Product Sku Name',  
										  'Product List' 				 => 'Product List', 
										  'Product Type' 				 => 'Product Type',
										  'Product Category' 			 => 'Product Category', 
										  'Total Commission (%)'		 => 'Total Commission (%)',
										  'Minimum Store Commission (%)' => 'Minimum Store Commission (%)',
										  'Maximum Store Commission (%)' => 'Maximum Store Commission (%)'
										 )
					);
	    	}						
			fputcsv($outstream, array('Product Name'				 => $item->vproductName, 
									  'Product Sku Name' 			 => $item->vproductSkuName, 
									  'Product List' 				 => $item->productName, 
									  'Product Type' 				 => $item->vproductType,
									  'Product Category' 			 => $item->vproductCategory, 
									  'Total Commission (%)'		 => $item->vproducttotalCommission - $item->vproductAdminCommission,
									  'Minimum Store Commission (%)' => $item->vproductMinStoreCommission,
									  'Maximum Store Commission (%)' => $item->vproductMaxStoreCommission
									 )
					);
			$row++;				        
	    }		
	    fclose($outstream);
		exit();
	}
	
	public function get_store_by_subdist($distID){
		
		$agents = $this->agent_model->getAgents(array('parentAgentID' => $distID), 2);
		$ret = "<option value=\"\">-- All --</option>";
		foreach ($agents as $item) {
			$ret .= "<option value=\"{$item->agentID}\" >{$item->company_name}</option>";
		}
		echo $ret;
	}
	public function change_password() {
		if (isset($_POST) && count($_POST) > 0) {
			$this->load->model('admin_model');
			if (strlen($_POST['oldPassword']) < 1) {
				$this->error['oldPassword'] = 'Old password is required!';
			} elseif (!$this->admin_model->checkPassword(md5( $_POST['oldPassword'] ))) {
				$this->error['oldPassword'] = 'Old password wrong!';
			}
			if (strlen($_POST['password']) < 1) {
				$this->error['password'] = 'New password is required!';
			} elseif ($_POST['password'] != $_POST['passwordConfirm']) {
				$this->error['passwordConfirm'] = 'The new password and confirmation password do not match!';
			}
			if (!$this->error) {
				//change password
				$this->admin_model->update($this->session->userdata('rep_userid'), array('password' => md5( $_POST['password'] )));
				$this->admin_model->logout();
				redirect('login');
			}
			$this->data += $_POST;
		}
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] = 'admin';
		$this->load->view('change_password', $this->data);
	}
	
	public function account_payable() {
		if (isset($_POST) && count($_POST) > 0) {
			$where = '';
			switch ($_POST['button_type']) {
				case "search_agent":
					if ($_POST['agent'] > 0) {
						$where = "p.agentID = {$_POST['agent']}";
						$info = $this->agent_model->getAgent((int)$_POST['agent']);
						$this->data['results_loginID']		= $info?$info->loginID:'';
						$this->data['results_uncollected']	= $this->payment_model->getTotalAgentPaymentByCond("$where AND (p.collectedByCompany = 0 OR p.collectedByCompany IS NULL)");
						$this->data['results_collected']	= $this->payment_model->getTotalAgentPaymentByCond("$where AND p.collectedByCompany = 1");
					} else {
						$this->data['results_loginID']		= 'All Store';
						$this->data['results_uncollected']	= $this->payment_model->getTotalAgentPaymentByCond("(p.collectedByCompany = 0 OR p.collectedByCompany IS NULL)");
						$this->data['results_collected']	= $this->payment_model->getTotalAgentPaymentByCond("p.collectedByCompany = 1");
					}
					
					break;
				case "search_all_uncollected":
					$where = "(p.collectedByCompany = 0 OR p.collectedByCompany IS NULL)";
					break;
				case "search_all_collected":
					$where = "(p.collectedByCompany = 1)";
					break;
				case "search_all_payment":
					
					break;
				default:
			}
			$this->data['results']	= $this->payment_model->getAgentPaymentByCond($where);
			
			$this->data += $_POST;
		}
		//assign error
		$this->data['error'] 	= $this->error;
		
		$this->data['total_payments']	= $this->payment_model->getTotalAgentPaymentByCond();
		$this->data['total_uncollected']= $this->payment_model->getTotalAgentPaymentByCond("(p.collectedByCompany = 0 OR p.collectedByCompany IS NULL)");
		$this->data['total_collected']	= $this->payment_model->getTotalAgentPaymentByCond("(p.collectedByCompany = 1)");
		
		//agents
		$option_agent = array();
		$option_agent['0'] = '-- All --';
		$agents = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')), 2);
		foreach ($agents as $item) {
			$option_agent[$item->agentID] = $item->loginID;
		}
		$this->data['option_agent'] = $option_agent;
		
		//collected_by_company
		$this->data['option_collected_by_company'] = array('1' => 'Yes', '0' => 'No');
		
		$this->data['current_page'] = 'account_payable';
		$this->load->view('account_payable', $this->data);
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
		
		$transaction = new AuthorizeNetAIM($authLoginID, $authKey);
		$transaction->setSandbox($sandbox);
		
		$info = array(
			'amount' 		=> $data['chargedAmount'],
			'card_num' 		=> $data['card_number'],
			'exp_date' 		=> $data['card_exp_month'].substr($data['card_exp_year'], -2),
			'card_code' 	=> $data['card_cvv'],
			'description' 	=> 'from vonecallcom',
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
			$ret = array('transactionID' => $response->transaction_id, 'cardName' => $response->card_type.'-'.substr($response->account_number, -4));
		} elseif ($type=='capture') {
			$response = $transaction->captureOnly($data['auth_code']);
			$ret = $response->transaction_id;
		} else {
			$this->error['card_valid'] = 'AuthorizeNet type of request is not found!';
			$ret = false;
		}
		if (!$response->approved) {
			$this->error['card_valid'] = $response->response_reason_text;
			$ret = false;
		}
		return $ret;
	}
	private function storeCreditCard($data, $agentID, $authProfileID=''){
		
		$info  = $this->getAgentInfo($agentID);
		
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
		$profileId 	  = null;
		$profileError = null;
		
		//$deteleProfile = $transaction->deleteCustomerProfile(29545777);echo '<pre>';print_r($deteleProfile);die;
		if($authProfileID){
			$profileId = $authProfileID;
		}else{
			$response  = $transaction->createCustomerProfile(array('email' => $info->email));
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
			$shippingProfileResponse = $transaction->createCustomerShippingAddress($profileId, $shippingProfileData);
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
			$paymentProfileResponse = $transaction->createCustomerPaymentProfile($profileId, $paymentProfileData);
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
			$this->error['savedCard'] = $response->xml->messages->message->text;
			$ret = false;
		}
		
		return $ret;
	}
	// Delete Agent/distributor Authorize Profile Details
	public function deleteAuthProfile($profileID){
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
		
		$response  = $transaction->deleteCustomerProfile($profileID);
		if($response->xml->messages->resultCode=='Ok'){
			return true;
		}else{
			return false;
		}
	}
	public function getAuthProfile($email){
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
		//20294317
		$response  = $transaction->createCustomerProfile(array('email' => $email));
		if($response->xml->messages->resultCode=='Ok'){
			$profileId = $response->xml->customerProfileId;
		}else{
			$profileId = filter_var($response->xml->messages->message->text, FILTER_SANITIZE_NUMBER_INT);
		}
		
		if(is_numeric($profileId)) {
			return $profileId;
		}else{
			return false;
		}
	}
	
	private function validateRechargeAccount($data) {
		
		$errornumber = '';
		$errortext   = '';
		$this->load->helper(array('creditcard'));
		
		if (strlen(trim($data['card_number'])) < 1) {
			$this->error['card_number'] = 'Card Number is required!';
		} 
		if (strlen(trim($data['card_exp_month'])) < 1 || strlen(trim($data['card_exp_year'])) < 1) {
			$this->error['card_exp'] = 'Card Expiry is required!';
		}		
		if (strlen(trim($data['card_name'])) < 1 || strlen(trim($data['card_name'])) > 40) {
			$this->error['card_name'] = 'Name on Credit Card is required and maximum 40 character!';
		}				
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
	public function calculate_stores() {
		$from_date = date('Y-m-d 00:00:00');
		$to_date   = date('Y-m-d 23:59:59');
		switch ($_POST['time_period']) {
			case "today":
				$from_date = date('Y-m-d 00:00:00');
				$to_date   = date('Y-m-d H:i:s');
				break;
			case "yesterday":
				$from_date = date("Y-m-d", strtotime("yesterday")).' 00:00:00';
				$to_date = date("Y-m-d", strtotime("yesterday")).' 23:59:59';
				break;
			case "month":
				$from_date = date('Y-m').'-01 00:00:00';
				$to_date = date('Y-m-d H:i:s');
				break;
			case "year":
				$from_date = date('Y').'-01-01 00:00:00';
				$to_date = date('Y-m-d H:i:s');
				break;
			default:
				
		}
		$info = $this->getAccountRepInfo();
		
		if($info->agentTypeID == 4){
			// Get All Stores ====
			$getAllStores = $this->agent_model->getAgents(array('parentAgentID' => $this->session->userdata('rep_userid')));
					
			//get Report====
			$allStoresReports = array('Sale' => '', 'Commission' => '');
			foreach($getAllStores as $item){
				$totalSale = $this->agent_model->getTotalAgentSaleByDist($info->parentAgentID, $from_date, $to_date, $item->agentID);
				$allStoresReports['Sale'] += $totalSale->Sale;
				$allStoresReports['Commission'] += $totalSale->Commission;		
			}	
					
			$response =(object) $allStoresReports;				
		}else{
			$response 	= $this->agent_model->getTotalAgentSaleByDist($this->session->userdata('rep_userid'), $from_date, $to_date);				
		}	
		
		if ($response) {
			$response->success 		= 1;
			$response->Sale 		= $response->Sale?format_price($response->Sale):format_price(0);
			$response->Commission 	= $response->Commission?format_price($response->Commission):format_price(0);
		} else {
			$response = array('success' => 0, 'text' => 'The Data you have requested was not found in the system!');
		}
		echo json_encode($response);
	}


	//== Array Sort ==//
	private function subvalsort($a,$subkey, $orderBy='' ) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v->$subkey);
		}
		if($orderBy)
			arsort($b);
		else
			asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
	}
	
	// Data24X7 ==============
	private function getPhoneEmail($phonenum){
		$getTextUsername = $this->admin_model->getSettings(array('settingKey' => 'textUsername'));
		$getTextpassword = $this->admin_model->getSettings(array('settingKey' => 'textPassword'));
		
		$userName = $getTextUsername->settingValue;
		$password = $getTextpassword->settingValue;
		
		if ($phonenum[0] != "1") $phonenum = "1" . $phonenum;
		$url 	= "https://api.data24-7.com/v/2.0?api=T&user=$userName&pass=$password&p1=$phonenum";
		$result = simplexml_load_file($url) or die("feed not loading");
		
		if($result->results->result->status=='OK'){
			return $result->results->result->sms_address;
		}else{
			$this->error['cellphone'] = 'Invalid Cellphone number';
		}	
	}
	private function sendText ($message, $subject = "", $text_to = "", $mobile="") {
		require 'class.phpmailer.php';
		
		$mail = new PHPMailer();
		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->SMTPAuth 	= true;    						  // turn on SMTP authentication
		$mail->SMTPSecure 	= "tls";
		$mail->Host 		= $this->config->item('ses_smtp_host');  // specify main and backup server
		$mail->Port 		= $this->config->item('ses_smtp_port');  // SMTP Port
		$mail->Username 	= $this->config->item('ses_smtp_user');  // SMTP username
		$mail->Password 	= $this->config->item('ses_smtp_pass');  // SMTP password			
		$mail->From 		= $this->config->item('ses_smtp_from');  // From Email
		$mail->FromName 	= $this->config->item('site_name');	 	 // From Name
		$mail->AddAddress($text_to);							 	 // Receiver Email			
		$mail->WordWrap 	= 50;                                	 // set word wrap to 50 characters
		//$mail->IsHTML(true);                                   	 // set email format to HTML
		
		$mail->Subject = $subject;
		$mail->Body    = $message;						
		if(!$mail->Send())
		{
		   echo "Message could not be sent. <p>";
		   echo "Mailer Error: " . $mail->ErrorInfo;
		   exit;
		}
		return true;	
    }

}

?>