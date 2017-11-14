<?php
class Store extends CI_Controller {
	
	private $error = array();
	private $data = array();
	private $agentID = 0;
	
	function __construct() {
		parent::__construct();
		$this->roleuser->checkLogin();
		$this->load->helper(array('form','format'));
		$this->load->library('form_validation');
		$this->load->model('customer_model');  
		$this->load->model('payment_model');  
		$this->load->model('agent_model');
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
		
		//set agentID
		$this->agentID = (int)$this->session->userdata('store_userid');
	}
	public function index() {
		redirect(site_url('sale'));
	}
	public function payment() { 
		$info = $this->getAgentInfo();

		if (isset($_POST) && count($_POST) > 0) {
			
			if ( strlen(trim($_POST['savedCard'])) > 0 ) {

				if (strlen(trim($_POST['amount'])) < 1) {
					$this->error['amount'] = $this->lang->line('msg_required_amount');
				} elseif (!is_numeric(trim($_POST['amount'])) || (float)$_POST['amount']<=0) {
					$this->error['amount'] = $this->lang->line('msg_invalid_amount');
				}

				if(!$this->error){
					$transactionID  = NULL;
					
					$profileDetails = $this->payment_model->getAgentPaymentProfiles(array('profileID'=>$_POST['savedCard']));
				
					
					
					$transactionID  = $this->profilePayment($profileDetails[0], $_POST['amount'], 'AuthCapture');
					

					if (!$this->error) {
						$chargedDiscount = 0;
						$distCommission  = 0;
						$createdDate 		  = date('Y-m-d H:i:s');
						//insert to payment
						$data = array(
							'agentID'				=> $this->session->userdata('store_userid'),
							'transactionID'			=> $transactionID,
							'chargedAmount'			=> $_POST['amount'],
							'chargedDiscount'		=> $chargedDiscount,
							'paymentMethodID'		=> $this->config->item('payment_credit_card'),
							'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
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
						$totalCredit = (float)$_POST['amount'] + (float)$chargedDiscount;
						
						$this->agent_model->updateBalance($this->session->userdata('store_userid'), $totalCredit);
						echo "first";
						print_r($totalCredit);
					die();
						//reload page
						$this->session->set_userdata('message', $this->lang->line('msg_success_add_payment'));
						redirect(site_url('payment'));
					}					
				}
			}

			if ($this->validatePayment($_POST)) {
				$transaction = NULL;
				
				$transaction = $this->creditcard($_POST, 'authorize_and_capture');
			
				if (!$this->error) {
					$chargedDiscount 	  = 0;
					$accountRepCommission = 0;
					$createdDate 		  = date('Y-m-d H:i:s');
					//insert to payment
					$data = array(
						'agentID'				=> $this->session->userdata('store_userid'),
						'transactionID'			=> $transaction['transactionID'],
						'chargedAmount'			=> $_POST['amount'],
						'chargedDiscount'		=> $chargedDiscount,
						'paymentMethodID'		=> $this->config->item('payment_credit_card'),
						'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
						'ipAddress'				=> $this->input->ip_address(),
						'paidTo'				=> 'System Admin',
						'comment'				=> '',//'payment with credit card',
						'accountRepID'			=> $info->parentAgentID,
						'accountRepCommission'	=> $accountRepCommission,
						'collectedByCompany'	=> 1,
						'dateCollectedByCompany'=> $createdDate,
						'createdDate'			=> $createdDate
					);

					 $this->payment_model->addAgentPayment($data);
					
					// Store Card details on server #CIM#
					if($_POST['saveCard']){

						$authProfileID = null;
						if($info->authProfile)
							$authProfileID = $info->authProfile; 
						
						$agentPaymentProfile = $this->storeCreditCard($_POST, $authProfileID);	
					
						// Save card profile ==================
						$data = array('agentID'				=> $this->session->userdata('store_userid'),
									  'cimCardNumber'		=> $transaction['cardNumber'],
									  'cimProfileID'		=> $agentPaymentProfile['profile'],
								      'cimShippingProfileID'=> $agentPaymentProfile['shippingProfile'],
									  'cimPaymentProfileID'	=> $agentPaymentProfile['paymentProfile'],
									  'profile_DateTime'	=> date('Y-m-d H:i:s'),
									  'isDeleted'			=> 0 ,// added by arita
								);


						if(!$this->error && $agentPaymentProfile['profile'])
							
						$this->payment_model->addPaymentProfile($data);
					
							// Code added by arita for making card having paymentprofileId active
							$this->payment_model->makecard_status_active($data);
						
						// Update Profile For Agent ======
						$this->agent_model->update($this->session->userdata('store_userid'), array('authProfile' => $agentPaymentProfile['profile']));
					}					
					
					//update balance
					$totalCredit = (float)$_POST['amount'] + (float)$chargedDiscount;
					
					$this->agent_model->updateBalance($this->session->userdata('store_userid'), $totalCredit);
					echo "second";
					print_r($totalCredit);
					die();
					//reload page
					$this->session->set_userdata('message', $this->lang->line('msg_success_add_payment'));
					redirect(site_url('payment'));
				}
			}
			$this->data += $_POST;
		}
		
		$this->data['error'] = $this->error;
		
		//state
		$option_state = array();
		$option_state[''] = $this->lang->line('_select_');
		$states = $this->customer_model->getStates();
		foreach ($states as $item) {
			$option_state[$item->stateID] = $item->stateName;
		}
		$this->data['option_state'] = $option_state;
		
		//card_type array
		$option_card_type = array();
		$option_card_type[''] = $this->lang->line('_select_');
		$card_types = $this->customer_model->getCreditCardTypes();
		foreach ($card_types as $item) {
			$option_card_type[$item->creditCardTypeID] = $item->creditCardType;
		}
		$this->data['option_card_type'] = $option_card_type;
		
		//month array
		$this->data['option_month'] = array(
			''	 => $this->lang->line('_month_'),
			'01' => $this->lang->line('january'),
			'02' => $this->lang->line('february'),
			'03' => $this->lang->line('march'),
			'04' => $this->lang->line('april'),
			'05' => $this->lang->line('may'),
			'06' => $this->lang->line('june'),
			'07' => $this->lang->line('july'),
			'08' => $this->lang->line('august'),
			'09' => $this->lang->line('september'),
			'10' => $this->lang->line('october'),
			'11' => $this->lang->line('november'),
			'12' => $this->lang->line('december')
		);
		
		//year array
		$year_array = array();
		$year_array[''] = $this->lang->line('_year_');
		for ($i=(int)date('Y');$i<(int)date('Y')+10;$i++) {
			$year_array[$i] = $i;
		}
		$this->data['option_year'] = $year_array;
		// Payment Profiles
		 $agentID=$info->agentID;// code changes  done by ARITA SINGHI
		$paymentProfiles   = array();
		$profile_array[''] = $this->lang->line('_select_');
		$profiles 	 	   = $this->payment_model->getAgentPaymentProfiles(array('agentID'=>$agentID));
		
		foreach ($profiles as $item) {
			$profile_array[$item->profileID] = $item->cimCardNumber;
		}
		$this->data['paymentProfiles'] = $profile_array;
	
		$this->data['current_page'] = 'payment';
		$this->load->view('payment', $this->data);
	}
	
	// balance
	public function store_balance(){
		$info = $this->getAgentInfo();
		$this->data['current_balance'] = $info->balance;
		$this->data['current_page'] = 'payment';
		$this->load->view('store_balance', $this->data); 		
	}
	
	// refund
	public function refund(){
		$info = $this->getAgentInfo();
		$generalSetting = $this->agent_model->getSettings(array('settingKey' => 'rydEmail'));
		
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen(trim($_POST['refundAmount'])) < 1) {
				$this->error['refundAmount'] = $this->lang->line('msg_required_amount');
			} elseif (!is_numeric(trim($_POST['refundAmount'])) || (float)$_POST['refundAmount']<=0) {
				$this->error['refundAmount'] = $this->lang->line('msg_invalid_amount');
			}	
			if (strlen(trim($_POST['refundReason'])) < 1) {
				$this->error['refundReason'] = 'Reason required!';
			} 
			
			if(!$this->error){
				$distName = '';
				if($info->parentAgentID){
					$distributorInfo = $this->getDistributorInfo($info->parentAgentID);
					$distName = $distributorInfo->firstName.' '.$distributorInfo->lastName;
				}
				$subject = 'Store refund request';
				$amount  = $_POST['refundAmount'];
				$reason  = $_POST['refundReason']; 
				$message = "Hello RYD, <br><br>
							Store $info->company_name want to refund amount from account. Please review details. <br><br>
							<table>
								<tr colspan='2'>
									<td> <b> <h2> Refund details </h2> </b> </td>									
								</tr>
								<tr>
									<td> Refund amount: </td>
									<td> $amount </td>
								</tr>
								<tr>
									<td> Reason: </td>
									<td> $reason </td>
								</tr>
							</table>
							<table>
								<tr colspan='2'>
									<td> <b> <h2> Store details </h2> </b> </td>									
								</tr>
								<tr>
									<td> Distributor: </td>
									<td> $distName </td>
								</tr>
								<tr>
									<td> Store: </td>
									<td> $info->company_name </td>
								</tr>								
								<tr>
									<td> Owner Name: </td>
									<td> $info->firstName $info->lastName </td>
								</tr>
								<tr>
									<td> Phone: </td>
									<td> $info->phone </td>
								</tr>
								<tr>
									<td> Cell Phone: </td>
									<td> $info->cellPhone </td>
								</tr> 
								<tr>
									<td> Email: </td>
									<td> $info->email </td>
								</tr>								
							</table> <br><br>
							Vonecall Support Team<br>
							Regards
							";
				$emailResult = $this->sendText($message, $subject, $generalSetting->settingValue);
				if(!$this->error){
					$this->session->set_userdata('message', 'Refund request successfully send. RYD will contact with you with in 2 business day.');
					redirect('refund');
				}
			}
			$this->data += $_POST;
		}
		
		$this->data['error'] 			= $this->error;
		$this->data['current_balance'] 	= $info->balance;
		$this->data['current_page'] 	= 'payment';
		$this->load->view('refund', $this->data); 
		//	
	}
	
	// Update password
	public function change_password(){
		$info = $this->getAgentInfo();
		
		if (isset($_POST) && count($_POST) > 0) {			
			if (strlen(trim($_POST['currentPassword'])) < 1) {
				$this->error['currentPassword'] = 'Current password required!';
			} elseif ($info->password != md5($_POST['currentPassword'])) {
				$this->error['currentPassword'] = 'Current password is not correct!';
			}
			if (strlen(trim($_POST['agentPassword'])) < 1) {
				$this->error['agentPassword'] = 'New password required!';
			}			
			if (strlen(trim($_POST['agentPasswordConfirm'])) < 1) {
				$this->error['agentPasswordConfirm'] = 'Confirm password required!';
			}elseif($_POST['agentPasswordConfirm'] != $_POST['agentPassword']){
				$this->error['agentPasswordConfirm'] = 'Confirm password is not matched!';
			}
			
			if(!$this->error){
				$data = array('password' => md5($_POST['agentPassword']));
				
				$this->agent_model->update($this->agentID, $data);
				
				//assign message
				$this->session->set_userdata('message', 'Password Successfully Updated!');
				redirect(site_url('update-password'));				
			}
			$this->data += $_POST;
		}
		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'admin';
		$this->load->view('change_password', $this->data); 		
	}
	
	/**** REMOVE SAVED CARDS CODE ADDED BY ARITA SINGHI ***************/

  public  function remove(){
		
		$info 				 = $this->getAgentInfo();
		$agentID 			 = $info->agentID;// to get agent id in session.
		$this->data['data']  = $this->payment_model->get_saved_card_info($agentID);
  		$this->data['info']  = $info;
		$this->data['error'] = $this->error;
		$this->data['current_page'] = 'payment';
		$this->load->view('remove_saved_card', $this->data); 	
   }



/*** Delete card (set status deactive)**********/
public function delete_card(){

	 $info 		= $this->getAgentInfo();
	 $agentID 	= $info->agentID;
	 $data 		= $this->payment_model->deactive_saved_card($agentID);
	return $data;
}

	// Reports =======================================================================
	public function sales_report(){
		$info = $this->getAgentInfo();
		
		if (isset($_POST) && count($_POST) > 0) {
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
			$product   = $_POST['product'];
							
			$storeSaleReport = $this->payment_model->getStoreSalesReport($this->agentID, $product, $from_date, $to_date);
					
			$this->data['results']  		= $storeSaleReport;
			$this->data['result_from_date'] = $from_date;
			$this->data['result_to_date']   = $to_date;			
			$this->data += $_POST;
		}
		
		// Product List==== 
		$productTypes = array('Calling Card' => 'Calling Card', 'Pinless' => 'PINLESS', 'Rtr' => 'Topup RTR', 'Pin' => 'Topup PIN');
		$option_product = array();
		$option_product['0'] = '-- ALL --';
		foreach($productTypes as $key=>$item){
			$option_product[$key] = $item;
		}
		$this->data['option_product']	= $option_product;
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'report';
		$this->load->view('reports/sales_report', $this->data); 	
	}
	public function commission_report(){
		$info = $this->getAgentInfo();
		
		if (isset($_POST) && count($_POST) > 0) {
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
			$product   = $_POST['product'];
			
			$storeCommissionReport 			= $this->payment_model->getStoreSalesReport($this->agentID, $product, $from_date, $to_date);
			$this->data['results'] 			= $storeCommissionReport;
			$this->data['result_from_date'] = $from_date;
			$this->data['result_to_date']   = $to_date;		
			$this->data += $_POST;
		}
		
		// Product List==== 
		$productTypes = array('Calling Card' => 'Calling Card', 'Pinless' => 'PINLESS', 'Rtr' => 'Topup RTR', 'Pin' => 'Topup PIN');
		$option_product = array();
		$option_product['0'] = '-- ALL --';
		foreach($productTypes as $key=>$item){
			$option_product[$key] = $item;
		}
		$this->data['option_product']	= $option_product;
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'report';
		$this->load->view('reports/commission_report', $this->data); 
	}
	public function product_list(){
		
		$this->data['results'] = $this->agent_model->getCommissions($this->agentID);
		
		if (isset($_POST) && count($_POST) > 0) {			
			$searchKey 	= $_POST['productName'];
			$where 		= "vp.vproductName LIKE '%".$searchKey."%'";		
			$this->data['results'] = $this->agent_model->getCommissions($this->agentID, $where);
			
			$this->data += $_POST;						
			
		}
		
		// Product Names
		$option_productName = array('' => '-- Product --');
		$products 			= $this->agent_model->getCommissions($this->agentID);
		$productSort = $this->agent_model->unique_sort($products, 'vproductName');		
		foreach ($productSort as $key=>$value) {
			$option_productName[$value] = $value;
		}
		$this->data['option_productName'] = $option_productName;
		
		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'report';
		$this->load->view('reports/product_list', $this->data); 
	}
	public function export_product_list(){
		$info = $this->getAgentInfo();
		$results = $this->agent_model->getCommissions($this->agentID);
		
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
										  'Product Type' 			 	 => 'Product Category', 
										  'Total Commission (%)'		 => 'Total Commission (%)'										 
										 )
					);
	    	}						
			fputcsv($outstream, array('Product Name'		 => $item->vproductName, 
									  'Product Sku Name' 	 => $item->vproductSkuName, 
									  'Product Type' 		 => $item->vproductCategory, 
									  'Total Commission (%)' => $item->commissionRate									  
									 )
					);
			$row++;				        
	    }		
	    fclose($outstream);
		exit();
	}
	public function payment_report(){
		$info = $this->getAgentInfo();
		
		if (isset($_POST) && count($_POST) > 0) {
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
			//$product   = $_POST['product'];
							
			$storePaymentReport = $this->payment_model->getStorePaymentReport($this->agentID, 'p.transactionID IS NOT NULL' ,$from_date, $to_date);
			$this->data['results']  		= $storePaymentReport;
			$this->data['result_from_date'] = $from_date;
			$this->data['result_to_date']   = $to_date;			
			$this->data += $_POST;
		}
		
		// Product List==== 
		$productTypes = array('Calling Card' => 'Calling Card', 'Pinless' => 'PINLESS', 'Rtr' => 'Topup RTR', 'Pin' => 'Topup PIN');
		$option_product = array();
		$option_product['0'] = '-- ALL --';
		foreach($productTypes as $key=>$item){
			$option_product[$key] = $item;
		}
		$this->data['option_product']	= $option_product;
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'report';
		$this->load->view('reports/payment_report', $this->data); 	
	}
		
	private function promotionPayment($customerID, $amount, $productID, $createdDate) {
		//check promotion
		$totalPayment = $this->payment_model->checkExistPaymentCustomer($customerID);
		$promotion = $this->customer_model->getPromotion($productID, $createdDate);
		if ($totalPayment==1 && $promotion) {
			$promotionAmount = $promotion->amount + ($promotion->percentage / 100 * $amount);
			if ($promotionAmount > 0) {
				$dataPayment = array(
					'customerID'			=> $customerID,
					'seqNo'					=> $this->payment_model->getSeqNo($customerID),
					'paymentMethodID'		=> $this->config->item('payment_promotion'),
					'chargedAmount'			=> $promotionAmount,
					'creditCardTypeID'		=> '',
					'creditCardNumber'		=> '',
					'creditSecureCode'		=> '',
					'creditExpireMonth'		=> '',
					'creditExpireYear'		=> '',
					'sameNameInd'			=> 0,
					'cardHolderFirstName'	=> '',
					'cardHolderLastName'	=> '',
					'cardHolderAddress'		=> '',
					'cardHolderCity'		=> '',
					'cardHolderStateID'		=> '',
					'cardHolderZipCode'		=> '',
					'transactionID'			=> '',
					'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
					'chargedBy'				=> 'STORE SITE',
					'comment'				=> 'promotion',
					'createdDate'			=> $createdDate
				);
				$this->payment_model->add($dataPayment);
				
				//update balance to oracle DB
				$this->customer_model->updateBalance($customerID, $promotionAmount);
			}
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
		

		$transaction = new AuthorizeNetAIM($authLoginID, $authKey);
		$transaction->setSandbox($sandbox);
		
		$info = array(
			'amount' 		=> $data['amount'] ,
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

				
			$ret = array('transactionID' => $response->transaction_id, 'cardNumber' => $response->card_type.substr($response->account_number, -4));

		} elseif ($type=='capture') {
			$response = $transaction->captureOnly($data['auth_code']);
			$ret = $response->transaction_id;
		} else {
			$this->error['card_number'] = $this->lang->line('msg_authorize_type_not_found');
			$ret = false;
		}
		
		if (!$response->approved) {
			$this->error['card_number'] = $response->response_reason_text;
			$ret = false;
		}
		
		return $ret;
	}
	private function storeCreditCard($data, $profileID=''){
		$info = $this->getAgentInfo();
		//require(APPPATH.'libraries/anet_php_sdk/AuthorizeNet.php');
		
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
		
		// Create Profile =======
		$profileId 	  = 0;
		$profileError = null;
		
		if($profileID){
			$profileId = $profileID;
		}else{
			//$response  = $transaction->createCustomerProfile(array('email' => $info->email));
			$response  = $transaction->createCustomerProfile(array('merchantCustomerId' => time().rand(1,150)));	// Added on 14-jan
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
	private function validatePayment($data) { 
		
		$errornumber = '';
		$errortext = '';
		$this->load->helper(array('creditcard'));
		/*if (strlen(trim($data['card_type'])) < 1) {
			$this->error['card_type'] = $this->lang->line('msg_required_card_type');
		}*/
		if (strlen(trim($data['card_number'])) < 1) {
			$this->error['card_number'] = $this->lang->line('msg_required_card_num');
		} /*elseif (!checkCreditCard ($data['card_number'], $data['card_type'], $errornumber, $errortext)) {
			$this->error['card_number'] = $errortext;
		}*/
		if (strlen(trim($data['card_exp_month'])) < 1 || strlen(trim($data['card_exp_year'])) < 1) {
			$this->error['card_exp'] = $this->lang->line('msg_required_card_exp');
		}
		
		if (strlen(trim($data['card_cvv'])) < 1) {
			$this->error['card_cvv'] = $this->lang->line('msg_required_cvv');
		}
		
		if (strlen(trim($data['amount'])) < 1) {
			$this->error['amount'] = $this->lang->line('msg_required_amount');
		} elseif (!is_numeric(trim($data['amount'])) || (float)$data['amount']<=0) {
			$this->error['amount'] = $this->lang->line('msg_invalid_amount');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function getAgentInfo() {
		//agent info
		$info = $this->agent_model->getAgent($this->agentID);
		if (!$info) {
			show_error($this->lang->line('msg_content_store_not_found'), 404, $this->lang->line('msg_title_store_not_found'));
		}
		$this->data['info'] = $info;
		
		//time_period array
		$this->data['option_time_period'] = array(
			'today' 	=> $this->lang->line('today'), 
			'yesterday'	=> $this->lang->line('yesterday'), 
			'month' 	=> $this->lang->line('this_month'), 
			'year' 		=> $this->lang->line('year_to_date')
		);
		return $info;
	}
	private function getDistributorInfo($id) {
		//agent info
		$info = $this->agent_model->getAgent($id);
		return $info;
	}
	private function sendText ($message, $subject = "", $email_to = "") {
		require 'class.phpmailer.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->SMTPAuth 	= true;    						  // turn on SMTP authentication
		$mail->SMTPSecure 	= "tls";
		$mail->Host 		= $this->config->item('smtp_host');  // specify main and backup server
		$mail->Port 		= $this->config->item('smtp_port');  // SMTP Port
		$mail->Username 	= $this->config->item('smtp_user');  // SMTP username
		$mail->Password 	= $this->config->item('smtp_pass');  // SMTP password			
		$mail->From 		= $this->config->item('smtp_from');  // From Email
		$mail->FromName 	= $this->config->item('site_name');	 // From Name
		$mail->AddAddress($email_to);							 // Receiver Email			
		$mail->WordWrap 	= 50;                                // set word wrap to 50 characters
		$mail->IsHTML(true);                                   	 // set email format to HTML
		
		$mail->Subject = $subject;
		$mail->Body    = $message;						
		if(!$mail->Send())
		{
		   $this->error['email_error'] = 'Message could not be sent: '.$mail->ErrorInfo;
		   return false;
		}			
		return true;		  	
    }



  







}
?>