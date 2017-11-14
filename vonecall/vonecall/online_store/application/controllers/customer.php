<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
class Customer extends CI_Controller {
	
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
		
		//set agentID
		$this->agentID = (int)$this->session->userdata('store_userid');
	}
	public function index() {
		redirect(site_url('sale'));
	}
	
	public function home() {
		$info = $this->getAgentInfo();
		//$this->data['commission_rate']	= $this->agent_model->getCommissions($this->agentID);
		
		$message = $this->customer_model->getMessage(array('messageType' => 'Promotion'));
		$banner  = $this->customer_model->getMessage(array('messageType' => 'store-banner'));
		$this->data['promotion_message'] = $message?$message->message:'';
		$this->data['banner_message']    = $banner?$banner->message:'<h1 style="color:#FF6600; font-weight: bold;">Offer Coming Soon!<h1>';
		
		$this->data['current_page'] = 'home';
		$this->load->view('home', $this->data);
	}

	public function email_us($emailTo) {
		$info = $this->getAgentInfo();
		
		if (isset($_POST) && count($_POST) > 0) {
			if(strlen($_POST['subject']) < 1){
				$this->error['subject'] = 'Subject recuired!';
			}
			if(strlen($_POST['message']) < 1){
				$this->error['message'] = 'Message recuired!';
			}
			
			if(!$this->error){
				$subject = $_POST['subject'];
				$message = $_POST['message'];
				$parentDetails = $this->getParentDistInfo($info->parentAgentID);
				if($emailTo == 1){
					// Email to Distributor  					
					$subject = "Store query ";
					$message = "Dear $parentDetails->firstName $parentDetails->lastName. <br><br>
							   Store $info->company_name ($info->loginID) submit query to you. Please review store query.
							   <br><br>
							   <table>
							   	<tr>
							   		<td> Store Name: </td>
							   		<td> $info->company_name </td>
							   	</tr>
							   	<tr>
							   		<td> Owner: </td>
							   		<td> $info->firstName  $info->lastName </td>
							   	</tr>
							   	<tr>
							   		<td> Email: </td>
							   		<td> $info->email </td>
							   	</tr>
							   	<tr>
							   		<td> Contact number: </td>
							   		<td> $info->cellPhone </td>
							   	</tr>
							   	<tr>
							   		<td> Subject: </td>
							   		<td> $subject </td>
							   	</tr>
							   	<tr>
							   		<td> Owner: </td>
							   		<td> $message </td>
							   	</tr>
							   </table>
							   
							   <br><br>
							   Regards<br>
							   Vonecall Team";
							   
					$this->sendEmail($message, $subject, $parentDetails->email);					
					?>
					<script>
						alert('Thankyou for submitting a query.');						
						window.parent.location.reload();
						parent.jQuery.fn.colorbox.close();
					</script>
					<?php
				}else{
					// Email to Admin
					$getMode 	= $this->agent_model->getSettings(array('settingKey' => 'rydEmail'));
					$adminEmail = $getMode->settingValue;
					$subject = "Store query ";
					$message = "Dear Admin <br><br>
							   Store $info->company_name ($info->loginID) submit query to you. Please review store query.
							   <br><br>
							   <table>
							   	<tr>
							   		<td> Store Name: </td>
							   		<td> $info->company_name </td>
							   	</tr>
							   	<tr>
							   		<td> Owner: </td>
							   		<td> $info->firstName  $info->lastName </td>
							   	</tr>
							   	<tr>
							   		<td> Distributor: </td>
							   		<td> $parentDetails->firstName $parentDetails->lastName </td>
							   	</tr>
							   	<tr>
							   		<td> Email: </td>
							   		<td> $info->email </td>
							   	</tr>
							   	<tr>
							   		<td> Contact number: </td>
							   		<td> $info->cellPhone </td>
							   	</tr>
							   	<tr>
							   		<td> Subject: </td>
							   		<td> $subject </td>
							   	</tr>
							   	<tr>
							   		<td> Owner: </td>
							   		<td> $message </td>
							   	</tr>
							   </table>
							   
							   <br><br>
							   Regards<br>
							   Vonecall Team";
							   
					$this->sendEmail($message, $subject, $adminEmail);					
					?>
					<script>	
						alert('Thankyou for submitting a query.');					
						window.parent.location.reload();
						parent.jQuery.fn.colorbox.close();
					</script>
					<?php
				}
			}
			$this->data += $_POST;
		}
		if($emailTo==1){
			$email='distributor';
		}else{
			$email='admin';
		}
		
		$this->data['error']        = $this->error;
		$this->data['emailTo']      = $emailTo;
		$this->data['current_page'] = 'home';
		$this->load->view('popup_email_us', $this->data);
	}
	
	public function commission_rates(){
		$num_per_page = 20;
		$paging_data = array(
			'limit'   => $num_per_page,
			'current' => (isset($_POST['page']) && $_POST['page'] > 1) ? $_POST['page'] : 1,
			'total'   => $this->agent_model->getTotalProductsByAgent($this->agentID)
		);
		$this->data['paging']  = $this->paging->do_paging_customer($paging_data);
		$offset 			   = ($paging_data['current'] - 1) * $num_per_page;
		
		$this->data['results'] = $this->agent_model->getAllProductsByAgent($this->agentID, $num_per_page, $offset);
				
		$this->load->view('popup_commission_rates', $this->data);
	}		
	
	public function country_product($countryCode){
		
		$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'RTR', 'vp.vproductCountryCode' => $countryCode));
		
		$results  = $this->customer_model->array_unique_by_key($products, "ppnProductID");
		$this->data['results'] = $results;
		
		$template = 'step_2';	
		$this->data['current_page'] = 'topup';
		$this->load->view('topup/'.$template, $this->data);
	}
        
        // TopUp Start ============================================================================	
	public function topup($_phone='', $_areacode='1') {
		$info = $this->getAgentInfo();
		$template = 'step_1';
		//areacode
		$option_areacode     = array();
		$option_areacode[''] = '-- Country -- ';
		$countries = $this->customer_model->getRTRCountries(array('ac.agentID' =>  $this->agentID));
		
		$results   = $this->customer_model->array_unique_by_key($countries, "CountryCodeIso");
		
		$this->data['countries']    = $results;
		$this->data['current_page'] = 'topup';
		$this->load->view('topup/'.$template, $this->data);
	}
        
	public function topup_recharge($productID){
		$info 	  = $this->getAgentInfo();	
		$results  =  $this->agent_model->getProductDetailsBySKU(array('vp.ppnProductID' => $productID));
		
		$getProductsByAgent = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'RTR'));	
		if(isset($getProductsByAgent) && count($getProductsByAgent) > 0){
			$results  = $this->agent_model->getProductDetailsBySKU(array('vp.ppnProductID' => $productID, 'vp.productTypeID'=>$getProductsByAgent[0]->productID));		
		}
		
		$template = 'topup_recharge';
		
		if (isset($_POST) && count($_POST) > 0) {
			if(strlen($_POST['phoneNumber']) < 1){
				$this->error['phoneNumber'] = 'Phone number recuired!';
			}
			if(strlen($_POST['amount']) < 1){
				$this->error['amount'] = 'Amount recuired!';
			}
			if(strlen($_POST['senderPhone']) < 1){
				$this->error['senderPhone'] = 'Sender phone recuired!';
			}
			
			$skuID  = '';
			$rechargeAmount = '';
			if(isset($_POST['skuID'])){
				$skuID  = $_POST['skuID'];
				$rechargeAmount = $_POST['amount'];
			}else{
				$explodeSku = explode('-', $_POST['amount']);
				$skuID  = $explodeSku[1];
				$rechargeAmount = $explodeSku[0];;
			}
			
			// Check Account Balance ====
			if ($info->balance < $rechargeAmount) {
				$this->error['amount'] = $this->lang->line('msg_credit_not_enough');
				$this->data['warning'] = $this->lang->line('msg_credit_not_enough');
			}

			if(!$this->error){
								
				//call request purchaseRtr2
				$purchaseRtr2Param = array(
					'skuId' 		=> $skuID,
					'amount' 		=> $rechargeAmount,
					'mobile' 		=> $_POST['countryCode'].$_POST['phoneNumber'],
					'corelationId' 	=> '',
					'senderMobile' 	=> $_POST['senderPhone'],
					'storeId' 		=> ''
				);
				
				require 'PrepaynationWS.php';
				
				// getPPN Mode
				$topupDetails = $this->getMode('ppnMode', 'ppnUsername', 'ppnPassword'); 
				$api   		  = new PrepaynationWS($topupDetails['username'], $topupDetails['password'], $topupDetails['mode']);
				$purchaseRtr2 = $api->purchaseRtr2($purchaseRtr2Param); 	// Call PurchaseRTR method
				
				if (!isset($purchaseRtr2->error)) {
					$phone    		= $_POST['phoneNumber'];
					$amount   		= $purchaseRtr2->invoice->faceValueAmount;
					$operator 		= $purchaseRtr2->invoice->cards->card->name;
					$createdDate	= date('Y-m-d H:i:s');
					
					// Get Product Details by SKU					
					$getCommission = $this->agent_model->getCommissionByProductSKU($this->agentID, $skuID);
					
					// calculate RYD Commission
					$rydCommission = 0;	
					if($getCommission)			
						$rydCommission = $rechargeAmount * $getCommission->vproductAdminCommission / 100;
									
					//calculate store commission
					$storeCommission = 0;
					if ($getCommission) {
						$storeCommission = $rechargeAmount * $getCommission->commissionRate / 100;
					}
					
					//calculate Distributor commission
					$distCommission = $rechargeAmount * ($getCommission->vproducttotalCommission - ($getCommission->commissionRate + $getCommission->vproductAdminCommission)) / 100;
					
					// Create new customer account ======================================			
					/*$checkCustomer = $this->customer_model->checkUserExist($phone);
					if(!$checkCustomer){
						$firstName	  = isset($_POST['firstName'])?$_POST['firstName']:'';
						$lastName	  = isset($_POST['lastName'])?$_POST['lastName']:'';
						$createdDate  = date('Y-m-d H:i:s');
						$randomSubsId = trim('04156'.substr(number_format(time() * rand(),0,'',''),0,10));
						$subscriberId = preg_replace('/[\s]+/','',$randomSubsId);
						$data = array(
							'firstName'			 => $firstName,
							'lastName'			 => $lastName,
							'phone'				 => $phone,
							'loginID'			 => $phone,
							'password'			 => date('mdyHi'),
							'agentID'			 => $this->agentID,
							'createdDate'		 => $createdDate,
							'subscriberID'  	 => $subscriberId,
							'customerProduct'	 => 'rtr',
							'customerProductSKU' => $skuID
						);
						$customerID = $this->customer_model->add($data);	
					}*/
					
					// Create new customer account ======================================			
					$customerDetails = $this->customer_model->getCustomerByPhone($phone);
					if($customerDetails){
						$customerID = $checkCustomer->customerID;
					}else{
						$firstName	  = isset($_POST['firstName'])?$_POST['firstName']:'';
						$lastName	  = isset($_POST['lastName'])?$_POST['lastName']:'';
						$createdDate  = date('Y-m-d H:i:s');
						$randomSubsId = trim('04156'.substr(number_format(time() * rand(),0,'',''),0,10));
						$subscriberId = preg_replace('/[\s]+/','',$randomSubsId);
						$data = array(
							'firstName'			 => $firstName,
							'lastName'			 => $lastName,
							'phone'				 => $phone,
							'loginID'			 => $phone,
							'password'			 => date('mdyHi'),
							'agentID'			 => $this->agentID,
							'createdDate'		 => $createdDate,
							'subscriberID'  	 => $subscriberId,
							'customerProduct'	 => 'rtr',
							'customerProductSKU' => $skuID
						);
						$customerID = $this->customer_model->add($data);
					}
					
					//Check Subdist Parent Account
					$getParentDistributor = $this->getParentDistInfo($info->parentAgentID);
					$parentAgentID = $info->parentAgentID;
					if($getParentDistributor->agentTypeID == 4){
						$parentAgentID = $getParentDistributor->parentAgentID;
					}
					
					//insert to payment =================================================
					$dataPayment = array(
						'customerID'			=> $customerID,
						'seqNo'					=> $this->payment_model->getSeqNo($customerID),
						'paymentMethodID'		=> 2,
						'chargedAmount'			=> $amount,
						'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
						'chargedBy'				=> 'STORE SITE',
						'productID'				=> $getCommission->vproductID,
						'agentID'				=> $this->agentID,
						'storeCommission'		=> $storeCommission,
						'accountRepID'			=> $parentAgentID,
						'accountRepCommission'	=> $distCommission,
						'comment'				=> 'InvoiceNumber: '.$purchaseRtr2->invoice->invoiceNumber,
						'createdDate'			=> $createdDate,
						'adminCommission'		=> $rydCommission
					);
					$this->payment_model->add($dataPayment);
					
					//update balance agent
					$totalCredit = -$amount + $storeCommission;
					//$totalCredit = -$amount;
					$this->agent_model->updateBalance($this->agentID, $totalCredit);
					
					//done
					$template 					   = 'topup_recharge_success';
					$this->data['message'] 		   = 'The recharge request has been completed successfully!';
					$this->data['storeCommission'] = $storeCommission;
					$this->data['operator'] 	   = $operator;
					$this->data['skuId'] 		   = $skuID;
					$this->data['amount'] 		   = $amount;
					$this->data['senderNumber']    = $_POST['senderPhone'];	
					$this->data['areacode']    	   = $_POST['countryCode'];	
					$this->data['phone']   		   = $_POST['phoneNumber'];
					$this->data['success'] 		   = TRUE;
				} elseif (isset($purchaseRtr2->error)) {
					$this->data['warning'] = $purchaseRtr2->error;
				} else {
					$this->data['warning'] = 'ERROR: ';
				}
			}
			
			$this->data += $_POST;
			
		}		
		
		$this->data['results'] = $results[0];
		
		// Recharge Amount
		$amount   = array();
		$amount[''] = "-- Amount --";
		if(count($results) > 1){ 		
			foreach($results as $item){
				$amount[$item->vproductDenomination.'-'.$item->vproductSkuID] = '$'.$item->vproductDenomination.' - '.$item->vproductSkuName;
			}
			$this->data['recharge_amount'] = $amount;
		}else{ 
			if($results[0]->vproductDenomination ==0 || (round($results[0]->vproductMinAmount) == round($results[0]->vproductmaxAmount) )){
					
				if (is_float($results[0]->vproductMinAmount) && is_float($results[0]->vproductmaxAmount)) {
				    $minAmount = round($results[0]->vproductMinAmount, 2);
					$maxAmount = round($results[0]->vproductmaxAmount, 2);
				} else {
				   	$minAmount = ($results[0]->vproductMinAmount);
					$maxAmount = ($results[0]->vproductmaxAmount);
				}
				
				$this->data['rechargeAmount'] = "$$minAmount  - $$maxAmount";
				$this->data['skuID'] = $results[0]->vproductSkuID;
			}		
		}
		
		$this->data['error']		= $this->error;
		$this->data['current_page'] = 'topup';
		$this->load->view('topup/'.$template, $this->data);	
	}
	
	public function topup_usa_rtr($id='') {
		$template = 'usa_rtr_step_1';		
		$info 	  = $this->getAgentInfo();				
		$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'RTR', 'vp.vproductCountryCode' => 'US'));
		$carriers = $this->customer_model->array_unique_by_key($products, "ppnProductID");
		$this->data['carriers'] = $carriers; 
		
		$this->data['current_page'] = 'topup';
		$this->load->view('topup/'.$template, $this->data);
	}
	public function topup_usa_rtr_recharge($productID){
		$info 	  = $this->getAgentInfo();		
		$results  = $this->agent_model->getProductDetailsBySKU(array('vp.ppnProductID' => $productID));		
		//echo '<pre>';print_r($results);
		$getProductsByAgent = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'RTR', 'vp.vproductCountryCode' => 'US'));	
		if(isset($getProductsByAgent) && count($getProductsByAgent) > 0){
			$results  = $this->agent_model->getProductDetailsBySKU(array('vp.ppnProductID' => $productID, 'vp.productTypeID'=>$getProductsByAgent[0]->productID));		
		}
		//echo '<pre>';print_r($getProductsByAgent);die; 
		$template = 'usa_rtr_recharge';   // Set template
		 
		if (isset($_POST) && count($_POST) > 0) {
			if (!is_numeric($_POST['phoneNumber']) || strlen($_POST['phoneNumber']) != $results[0]->vLocalPhoneNumberLength) {
				$this->error['phoneNumber'] = 'Mobile Number is invalid!';
			}			
			if(strlen($_POST['amount']) < 1){
				$this->error['amount'] = 'Amount recuired!';
			}
			
			// Check Account Balance ====
			$skuID  = '';
			$rechargeAmount = '';
			if(isset($_POST['skuID'])){
				$skuID  = $_POST['skuID'];
				$rechargeAmount = $_POST['amount'];
			}else{
				$explodeSku = explode('-', $_POST['amount']);
				$skuID  = $explodeSku[1];
				$rechargeAmount = $explodeSku[0];;
			}
			
			if ($info->balance < $rechargeAmount) {
				$this->error['amount'] = $this->lang->line('msg_credit_not_enough');
				$this->data['warning'] = $this->lang->line('msg_credit_not_enough');
			}
			
			if (!$this->error) {				
				
				//call request purchaseRtr2
				$purchaseRtr2Param = array(
					'skuId' 		=> $skuID,
					'amount' 		=> $rechargeAmount,
					'mobile' 		=> $_POST['countryCode'].$_POST['phoneNumber'],
					'corelationId' 	=> '',
					'senderMobile' 	=> $_POST['senderPhone'],
					'storeId' 		=> ''
				);
				require 'PrepaynationWS.php';
				
				// getPPN Mode
				$topupDetails = $this->getMode('ppnMode', 'ppnUsername', 'ppnPassword'); 
				
				$param = $this->config->item('prepaynation');
				$api   = new PrepaynationWS($topupDetails['username'], $topupDetails['password'], $topupDetails['mode']);
				
				$purchaseRtr2 = $api->purchaseRtr2($purchaseRtr2Param);		// Call Purchase RTR method
				
				if (!isset($purchaseRtr2->error)) {
					$phone    		= $_POST['phoneNumber'];
					$amount   		= $purchaseRtr2->invoice->faceValueAmount;
					$operator 		= $purchaseRtr2->invoice->cards->card->name;
					$createdDate	= date('Y-m-d H:i:s');
					
					// Get Product Details by SKU					
					$getCommission = $this->agent_model->getCommissionByProductSKU($this->agentID, $skuID);
					
					// calculate RYD Commission
					$rydCommission = 0;	
					if($getCommission)			
						$rydCommission = $rechargeAmount * $getCommission->vproductAdminCommission / 100;
									
					//calculate store commission
					$storeCommission = 0;
					if ($getCommission) {
						$storeCommission = $rechargeAmount * $getCommission->commissionRate / 100;
					}
					
					//calculate Distributor commission
					$distCommission = $rechargeAmount * ($getCommission->vproducttotalCommission - ($getCommission->commissionRate + $getCommission->vproductAdminCommission)) / 100;
					
					// Create new customer account ======================================			
					$customerDetails = $this->customer_model->getCustomerByPhone($phone);
					if($customerDetails){			// Customer Exist
						if($customerDetails->phoneEmailID){ 				// If Customer Exist with phone email
							$phoneEmail = $customerDetails->phoneEmailID;
						} else { 											// If Customer Exist without phone email
							$phoneEmail = $this->getPhoneEmail($phone);
							$data 		= array('phoneEmailID' => $phoneEmail?addslashes($phoneEmail):'' );
							$UpdateEmail = $this->customer_model->update( $customerDetails->customerID, $data );
						}
						$customerID = $customerDetails->customerID;
					} else {						// New Customer
						$phoneEmail   = $this->getPhoneEmail($phone);	
						$firstName	  = isset($_POST['firstName'])?$_POST['firstName']:'';
						$lastName	  = isset($_POST['lastName'])?$_POST['lastName']:'';
						$createdDate  = date('Y-m-d H:i:s');
						$randomSubsId = trim('04156'.substr(number_format(time() * rand(),0,'',''),0,10));
						$subscriberId = preg_replace('/[\s]+/','',$randomSubsId);
						$data = array(
							'firstName'			 => $firstName,
							'lastName'			 => $lastName,
							'phone'				 => $phone,
							'loginID'			 => $phone,
							'password'			 => date('mdyHi'),
							'agentID'			 => $this->agentID,
							'createdDate'		 => $createdDate,
							'subscriberID'  	 => $subscriberId,
							'customerProduct'	 => 'rtr',
							'customerProductSKU' => $skuID,
							'phoneEmailID'		 => $phoneEmail?addslashes($phoneEmail):''
							
						);
						$customerID = $this->customer_model->add($data);
					}					
					
					//Check Subdist Parent Account
					$getParentDistributor = $this->getParentDistInfo($info->parentAgentID);
					$parentAgentID = $info->parentAgentID;
					if($getParentDistributor->agentTypeID == 4){
						$parentAgentID = $getParentDistributor->parentAgentID;
					}
									
					//insert to payment
					$dataPayment = array(
						'customerID'			=> $customerID,
						'seqNo'					=> $this->payment_model->getSeqNo($customerID),
						'paymentMethodID'		=> $this->config->item('payment_cash'),
						'chargedAmount'			=> $rechargeAmount,
						'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
						'chargedBy'				=> 'STORE SITE',
						'productID'				=> $getCommission->vproductID,
						'agentID'				=> $this->agentID,
						'storeCommission'		=> $storeCommission,
						'accountRepID'			=> $parentAgentID,
						'accountRepCommission'	=> $distCommission,
						'comment'				=> 'InvoiceNumber: '.$purchaseRtr2->invoice->invoiceNumber,
						'createdDate'			=> $createdDate,
						'adminCommission'		=> $rydCommission
					);
					$this->payment_model->add($dataPayment);
					
					//update balance agent
					$totalCredit = -$rechargeAmount + $storeCommission;
					$this->agent_model->updateBalance($this->agentID, $totalCredit);
					
					// Text Send to Distributor's cell number
					$textMessage = "Vonecall Topup have recharged successfully with amount $rechargeAmount";
					$subject	 = "Vonecall USA RTR";
					if($phoneEmail);
						$this->sendText($textMessage, $subject, $phoneEmail);
					
					//done
					$template 					   = 'usa_rtr_recharge_success';
					$this->data['message'] 		   = 'The recharge request has been completed successfully!';
					$this->data['amount'] 		   = $rechargeAmount;
					$this->data['operator'] 	   = $operator;
					$this->data['amount'] 		   = $rechargeAmount;
					$this->data['senderNumber']    = $_POST['senderPhone'];	
					$this->data['areacode']    	   = $_POST['countryCode'];	
					$this->data['phone']   		   = $_POST['phoneNumber'];
					$this->data['success'] 		   = TRUE;
				} elseif (isset($purchaseRtr2->error)) {
					$this->data['warning'] = $purchaseRtr2->error;
				} else {
					$this->data['warning'] = 'ERROR: ';
				}				
			}
			$this->data += $_POST;
		}
		
		// Recharge Amount
		$amount   = array();
		$amount[''] = "-- Amount --";
		if(count($results) > 1){			
			foreach($results as $item){
				$amount[$item->vproductDenomination.'-'.$item->vproductSkuID] = '$'.$item->vproductDenomination.' - '.$item->vproductSkuName;
			}
			$this->data['recharge_amount'] = $amount;
		}else{ 
			if($results[0]->vproductDenomination ==0){
				$minAmount = $results[0]->vproductMinAmount;
				$maxAmount = $results[0]->vproductmaxAmount;
				$this->data['rechargeAmount'] = "$$minAmount  - $$maxAmount";
				$this->data['skuID'] = $results[0]->vproductSkuID;
			}		
		}
		
		$this->data['results'] 		= $results[0];
		$this->data['error']		= $this->error;
		$this->data['current_page'] = 'topup';
		$this->load->view('topup/'.$template, $this->data);	
	}
	
	public function topup_usa_pin($id='') {
		$template = 'usa_pin_step_1';
		
		$info = $this->getAgentInfo(); 
		
		$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Pin', 'vp.vproductCountryCode' => 'US'));
		$carriers = $this->customer_model->array_unique_by_key($products, "ppnProductID");
		$this->data['carriers'] = $carriers; 
		
		$this->data['current_page'] = 'topup';
		$this->load->view('topup/'.$template, $this->data);
	}
	public function topup_usa_pin_recharge($productID){
		$info 	  = $this->getAgentInfo();		
		$results  = $this->agent_model->getProductDetailsBySKU(array('vp.ppnProductID' => $productID));
		
		$getProductsByAgent = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Pin', 'vp.vproductCountryCode' => 'US'));	
		if(isset($getProductsByAgent) && count($getProductsByAgent) > 0){
			$results  = $this->agent_model->getProductDetailsBySKU(array('vp.ppnProductID' => $productID, 'vp.productTypeID'=>$getProductsByAgent[0]->productID));		
		}
		//echo '<pre>';print_r($results);die;
		$template = 'usa_pin_recharge';
		
		if (isset($_POST) && count($_POST) > 0) {
			$skuID  		= '';
			$rechargeAmount = '';
			if(isset($_POST['skuID'])){
				$skuID  		= $_POST['skuID'];
				$rechargeAmount = $_POST['amount'];
			}else{
				$explodeSku = explode('-', $_POST['amount']);
				$skuID  		= $explodeSku[1];
				$rechargeAmount = $explodeSku[0];;
			}
			
			if (strlen($_POST['amount']) < 1) {
				$this->error['amount'] = 'Please select amount!';
			}			
			if (!$this->error) {
				if ($info->balance < $rechargeAmount) {
					$this->error['amount'] = $this->lang->line('msg_credit_not_enough');
					$this->data['warning'] = $this->lang->line('msg_credit_not_enough');
				}
			}
			if (!$this->error) {				
							 
				//call request purchasePin
				$purchasePinParam = array(
					'skuId' 		=> $skuID,
					'quantity' 		=> '1',
					'corelationId' 	=> $_POST['recipientPhone']
				);
				
				require 'PrepaynationWS.php';
				
				// getPPN Mode
				$topupDetails = $this->getMode('ppnMode', 'ppnUsername', 'ppnPassword'); 				
				$api   		  = new PrepaynationWS($topupDetails['username'], $topupDetails['password'], $topupDetails['mode']);
				$purchasePin  = $api->purchasePin($purchasePinParam);
				
				if (!isset($purchasePin->error)) {
					$phone 		= $_POST['recipientPhone'];
					$amount 	= $purchasePin->invoice->faceValueAmount;
					$pinNumber 	= isset($purchasePin->invoice->cards->card->pins->pin->pinNumber) ? $purchasePin->invoice->cards->card->pins->pin->pinNumber : '';
					
					//create new customer (if not exist)
					$createdDate   = date('Y-m-d H:i:s');
					
					// Get Product Details by SKU					
					$getCommission = $this->agent_model->getCommissionByProductSKU($this->agentID, $skuID);
					
					// calculate RYD Commission
					$rydCommission = 0;	
					if($getCommission)			
						$rydCommission = $_POST['amount'] * $getCommission->vproductAdminCommission / 100;
									
					//calculate store commission
					$storeCommission = 0;
					if ($getCommission) {
						$storeCommission = $_POST['amount'] * $getCommission->commissionRate / 100;
					}
					
					//calculate Distributor commission
					$distCommission = $_POST['amount'] * ($getCommission->vproducttotalCommission - ($getCommission->commissionRate + $getCommission->vproductAdminCommission)) / 100;
					
					// Create new customer account ======================================			
					$customerDetails = $this->customer_model->getCustomerByPhone($phone);
					
					if($customerDetails){			// Customer Exist
						$phoneEmail = $customerDetails->phoneEmailID;
						if($customerDetails->phoneEmailID){ 				// If Customer Exist with phone email
							$phoneEmail = $customerDetails->phoneEmailID;
						} else { 											// If Customer Exist without phone email
							$phoneEmail = $this->getPhoneEmail($phone);
							$data 		= array('phoneEmailID' => $phoneEmail?addslashes($phoneEmail):'' );
							$UpdateEmail = $this->customer_model->update( $customerDetails->customerID, $data );
						}
						$customerID = $customerDetails->customerID;
					} else {						// New Customer
						$phoneEmail   = $this->getPhoneEmail($phone);	
						$firstName	  = isset($_POST['firstName'])?$_POST['firstName']:'';
						$lastName	  = isset($_POST['lastName'])?$_POST['lastName']:'';
						$createdDate  = date('Y-m-d H:i:s');
						$randomSubsId = trim('04156'.substr(number_format(time() * rand(),0,'',''),0,10));
						$subscriberId = preg_replace('/[\s]+/','',$randomSubsId);
						$data = array(
							'firstName'			 => $firstName,
							'lastName'			 => $lastName,
							'phone'				 => $phone,
							'loginID'			 => $phone,
							'password'			 => date('mdyHi'),
							'agentID'			 => $this->agentID,
							'createdDate'		 => $createdDate,
							'subscriberID'  	 => $subscriberId,
							'customerProduct'	 => 'pin',
							'customerProductSKU' => $skuID,
							'phoneEmailID'		 => $phoneEmail?addslashes($phoneEmail):''
							
						);
						$customerID = $this->customer_model->add($data);
					}
										
					//Check Subdist Parent Account
					$getParentDistributor = $this->getParentDistInfo($info->parentAgentID);
					$parentAgentID = $info->parentAgentID;
					if($getParentDistributor->agentTypeID == 4){
						$parentAgentID = $getParentDistributor->parentAgentID;
					}
					
					//insert to payment
					$dataPayment = array(
						'customerID'			=> $customerID,
						'seqNo'					=> $this->payment_model->getSeqNo($customerID),
						'paymentMethodID'		=> $this->config->item('payment_cash'),
						'chargedAmount'			=> $amount,
						'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
						'chargedBy'				=> 'STORE SITE',
						'productID'				=> $getCommission->vproductID,
						'agentID'				=> $this->agentID,
						'storeCommission'		=> $storeCommission,
						'accountRepID'			=> $parentAgentID,
						'accountRepCommission'	=> $distCommission,
						'comment'				=> 'InvoiceNumber: '.$purchasePin->invoice->invoiceNumber,
						'createdDate'			=> $createdDate,
						'adminCommission'		=> $rydCommission
					);
					$this->payment_model->add($dataPayment);
					
					// Add USA Pin Activity
					$dataPinActivity = array('customerID' 	=> $customerID,
											 'agentID'		=> $this->agentID,
											 'pin_number'	=> $pinNumber,
											 'createdDate'	=> date('Y-m-d H:i:s'));
					$this->payment_model->addPinActivity($dataPinActivity);
					
					//update balance agent
					$totalCredit = -$amount + $storeCommission;
					$this->agent_model->updateBalance($this->agentID, $totalCredit);
					
					// Text Send to Distributor's cell number
					$textMessage = "Vonecall Pin created successfully. Your PIN number is $pinNumber";
					$subject	 = "Vonecall USA PIN";
					if($phoneEmail);
						$this->sendText($textMessage, $subject, $phoneEmail);
					
					//done
					$template = 'usa_pin_recharge_success';
					$this->data['pinNumber']    = $pinNumber;
					$this->data['instructions'] = '';
					$this->data['phone']    	= $_POST['recipientPhone'];
				} elseif (isset($purchasePin->error)) {
					$this->data['warning'] = $purchasePin->error;
				} else {
					$this->data['warning'] = 'ERROR: ';
				}
			}
			
			$this->data += $_POST;
			$this->data['error'] = $this->error;
		}
		
		// Recharge Amount
		$amount   = array();
		$amount[''] = "-- Amount --";
		if(count($results) > 1){			
			foreach($results as $item){
				$amount[$item->vproductDenomination.'-'.$item->vproductSkuID] = '$'.$item->vproductDenomination.' - '.$item->vproductSkuName;
			}
			$this->data['recharge_amount'] = $amount;
		}else{ 
			if($results[0]->vproductDenomination ==0 || ($results[0]->vproductMinAmount == $results[0]->vproductmaxAmount)){
				$minAmount = $results[0]->vproductMinAmount;
				$maxAmount = $results[0]->vproductmaxAmount;
				$this->data['rechargeAmount'] = "$$minAmount  - $$maxAmount";
				$this->data['skuID'] = $results[0]->vproductSkuID;
			}		
		}
		
		$this->data['results'] 		= $results[0];
		$this->data['error']		= $this->error;
		$this->data['current_page'] = 'topup';
		$this->load->view('topup/'.$template, $this->data);	
	}
	public function usa_pin_activity(){
		$info = $this->getAgentInfo();
		
		if (isset($_POST) && count($_POST) > 0) {
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
							
			$storeUsaPinReport = $this->payment_model->getUsaPinActivity($this->agentID, $from_date, $to_date);
			$this->data['results']  		= $storeUsaPinReport;
			$this->data['result_from_date'] = $from_date;
			$this->data['result_to_date']   = $to_date;			
			$this->data += $_POST;
		}
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'report';
		$this->load->view('reports/usa_pin_activity', $this->data); 
	}
	// TopUp END ==============================================================================
	
	// Pinless Start ==========================================================================
	public function pinless(){
		$info     		= $this->getAgentInfo($this->agentID);
		$getCommissions = $this->agent_model->getCommissionByAgent($this->agentID);
		if(!empty($getCommissions)){
			$getPromotion 	= $this->agent_model->getPromotions(array('pr.productID' => $getCommissions->productID));
		}else{
			$getPromotion 	= $this->agent_model->getPromotions(array());
		}
		$template = 'pinless.php';
		require 'PortaOneWS.php';
		if(isset($_POST) && count($_POST) > 0){
			if(!is_numeric($_POST['phone_1']) || strlen($_POST['phone_1']) != 3 ||
			   !is_numeric($_POST['phone_2']) || strlen($_POST['phone_2']) != 3 ||
			   !is_numeric($_POST['phone_3']) || strlen($_POST['phone_3']) != 4){
				$this->error['phone_number'] = $this->lang->line('msg_invalid_phone');
			}
			
			if(!$this->error){
				$phone 		= trim($_POST['phone_1']) . trim($_POST['phone_2']) . trim($_POST['phone_3']);
				$createdDate= date('Y-m-d H:i:s');
				//$customer 	= $this->customer_model->getCustomerByPhone($phone);
				
				// get Login Session ===============
				if ($this->session->userdata('portaoneSession')){
					$loginSession = $this->session->userdata('portaoneSession');
				} else {
					$loginSession = $this->portaoneSession();
				}				
				$getAccountResponse = $this->getAccountDetailsByPortaone($phone, $loginSession);	// Get Account details from Portaone 
				
				if ($getAccountResponse && $getAccountResponse->i_account) {
					
					//$customerID 		   = $customer->customerID;
					$this->data['login']   = $phone;
					$this->data['balance'] = $getAccountResponse->balance;
					$template = 'account_info';
				} else {					
					$this->data['promoAmount'] = $getPromotion[0]->amount;
					$this->data['login']   = $phone;
					$template = 'account_confirmation';
				}
			}
			$this->data += $_POST;
		}
		
		//access Language
		$option_language = array();
		$language 		 = array('English' => 'English', 'Spanish' => 'Spanish' );
		foreach($language as $key=>$item){
			$option_language[$key] = $item;
		}
		$this->data['option_language'] = $option_language;
		
		$this->data['error'] 		= $this->error;	
		$this->data['current_page'] = 'pinless';
		$this->load->view('pinless/'.$template, $this->data);
	}
	public function create_new_account(){
		$info = $this->getAgentInfo($this->agentID);
		require 'PortaOneWS.php';
		if(isset($_POST) && count($_POST) > 0){
			
			if(strlen($_POST['newBalance']) < 1 ){
				$this->error['newBalance'] = 'Amount recuired!';
			}elseif($_POST['newBalance'] < 2){
				$this->error['newBalance'] = 'Please enter minimum recharge amount $2';
			}			
			
			if ($info->balance < $_POST['newBalance']) {
				$this->error['newBalance'] = $this->lang->line('msg_credit_not_enough');
				$this->data['warning'] 	   = $this->lang->line('msg_credit_not_enough');
			}
			
			if(!$this->error){
				$portaAuth 	  = $this->config->item('portaone');
				$phone 		  = trim($_POST['phone']);
				// get Login Session ===============
				if ($this->session->userdata('portaoneSession')){
					$loginSession = $this->session->userdata('portaoneSession');
				} else {
					$loginSession = $this->portaoneSession();
				}
				
				$getAccountResponse = $this->getAccountDetailsByPortaone($phone, $loginSession);	// Get Account details from Portaone 
				$rechargeAmount = $_POST['newBalance'];
				
				// Check Customer account ======================================			
				$customerDetails = $this->customer_model->getCustomerByPhone($phone);
				if($customerDetails){		

				// Customer Exist
					if($customerDetails->phoneEmailID){ 				// If Customer Exist with phone email
						$phoneEmail = $customerDetails->phoneEmailID;
					} else { 											// If Customer Exist without phone email
						$phoneEmail = $this->getPhoneEmail($phone);
						$data 		= array('phoneEmailID' => $phoneEmail?addslashes($phoneEmail):'' );
						$UpdateEmail = $this->customer_model->update( $customerDetails->customerID, $data );
					}
					$customerID = $customerDetails->customerID;
				} else {

				// New Customer
					$phoneEmail   = $this->getPhoneEmail($phone);					
					$firstName	  = isset($_POST['firstName'])?$_POST['firstName']:'';
					$lastName	  = isset($_POST['lastName'])?$_POST['lastName']:'';
					$createdDate  = date('Y-m-d H:i:s');
					$randomSubsId = trim('04156'.substr(number_format(time() * rand(),0,'',''),0,10));
					$subscriberId = preg_replace('/[\s]+/','',$randomSubsId);
					$data = array(
						'firstName'		=> $firstName,
						'lastName'		=> $lastName,
						'phone'			=> $phone,
						'loginID'		=> $phone,
						'password'		=> date('mdyHi'),
						'agentID'		=> $this->agentID,
						'createdDate'	=> $createdDate,
						'subscriberID'  => $subscriberId,
						'customerProduct'=> 'pinless',
						'phoneEmailID'	=> $phoneEmail?addslashes($phoneEmail):''
					);					
					$customerID = $this->customer_model->add($data);	
				}						
				
				$api = new PortaOneWS("AccountAdminService.wsdl");
					
				$updateAccountRequest = array('i_account' => $getAccountResponse->i_account,										
											  'action' 	  => 'Manual payment',
										 	  'amount'	  => $rechargeAmount																				
								);			
				$result = $api->rechargeAccount($updateAccountRequest, $loginSession);
				
				//if($_POST['openingBalance']==2){				
					// Get Pinless product ======
					$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Pinless'));
					
					// Get Product Details by ProductID					
					$getCommission = $this->agent_model->getCommissionByProductID($this->agentID, $products[0]->vproductID);
					
					// calculate RYD Commission
					$rydCommission = 0;	
					if($getCommission)			
						$rydCommission = $_POST['newBalance'] * $getCommission->vproductAdminCommission / 100;
									
					//calculate store commission
					$storeCommission = 0;
					if ($getCommission) {
						$storeCommission = $_POST['newBalance'] * $getCommission->commissionRate / 100;
					}
					
					//calculate Distributor commission
					$distCommission = $_POST['newBalance'] * ($getCommission->vproducttotalCommission - ($getCommission->commissionRate + $getCommission->vproductAdminCommission)) / 100;
					
					//Check Subdist Parent Account
					$getParentDistributor = $this->getParentDistInfo($info->parentAgentID);
					$parentAgentID = $info->parentAgentID;
					if($getParentDistributor->agentTypeID == 4){
						$parentAgentID = $getParentDistributor->parentAgentID;
					}
					
					//insert to payment
					$dataPayment = array(
						'customerID'			=> $customerID,
						'seqNo'					=> $this->payment_model->getSeqNo($customerID),
						'paymentMethodID'		=> $this->config->item('payment_cash'),
						'chargedAmount'			=> $_POST['newBalance'],
						'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
						'chargedBy'				=> 'STORE SITE',
						'productID'				=> $getCommission->vproductID,
						'agentID'				=> $this->agentID,
						'storeCommission'		=> $storeCommission,
						'accountRepID'			=> $parentAgentID,
						'accountRepCommission'	=> $distCommission,
						'comment'				=> 'Pinless: ',
						'createdDate'			=> date('Y-m-d H:i:s'),
						'adminCommission'		=> $rydCommission
					);				
					$this->payment_model->add($dataPayment);
					
					//update balance agent
					$totalCredit = -$_POST['newBalance'] + $storeCommission;
					$this->agent_model->updateBalance($this->agentID, $totalCredit);
					
				//}
				if(!$getAccountResponse->error && $getAccountResponse && !empty($getAccountResponse)){
					
					$getAccountDetails = $getAccountResponse;
				}else{
					
					$addAccountResponse = $this->addNewPortaoneAccount($phone, $loginSession, $portaAuth, $rechargeAmount);
					
					if($addAccountResponse->i_account){
						
						$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
					}					
				}
				
				/** Get Access Number and send in text **/
				$areaCode 		 = substr($phone,0,3);
				$language		 = $_POST['accessLanguage'];
				$message		 = '';
				$getAccessNumber = $this->customer_model->getAccessNumberForPinless("AccessNumber like '%$areaCode%' AND access_lang like '%$language%'");
				
				if($getAccessNumber)
					$message = sprintf($this->config->item('access_sms_message'), $rechargeAmount, format_phone_number($getAccessNumber->AccessNumber));
				else{
					$getAccessNumber = $this->customer_model->getAccessNumberForPinless("city like '%default%' AND access_lang like '%$language%'");
					$message = sprintf($this->config->item('access_sms_message'), $rechargeAmount, format_phone_number($getAccessNumber->AccessNumber));
				}				
				/** Get Access Number and send in text END**/
				
				// Send Text to Pinless Customer =========================
				$subject	 = "Pinless Activation";
				if($phoneEmail);
					$this->sendText($message, $subject, $phoneEmail);
				
				$this->session->set_userdata('message', 'You have added pinless account successfully!');
				redirect(site_url('pinless-success/'.$getAccountDetails->login));
			}
			$this->data['login'] 	   = trim($_POST['phone']);
			//$this->data['promoAmount'] = $_POST['promotionAmount'];
			$this->data += $_POST;
		}
		
		//access Language
		$option_language = array();
		$language 		 = array('English' => 'English', 'Spanish' => 'Spanish' );
		foreach($language as $key=>$item){
			$option_language[$key] = $item;
		}
		$this->data['option_language'] = $option_language;
		
		$template 					= 'account_confirmation';
		$this->data['error'] 		= $this->error;	
		$this->data['current_page'] = 'pinless';
		$this->load->view('pinless/'.$template, $this->data);		
	}
	public function pinless_account_success($phone){
		require 'PortaOneWS.php';
		$info = $this->getAgentInfo($this->agentID);
		
		$portaAuth = $this->config->item('portaone');
				
		// get Login Session ===============
		if ($this->session->userdata('portaoneSession')){
			$loginSession = $this->session->userdata('portaoneSession');
		} else {
			$loginSession = $this->portaoneSession();
		}
		
		$getAccountResponse    = $this->getAccountDetailsByPortaone($phone, $loginSession);
		$this->data['login']   = $phone;
		$this->data['balance'] = $getAccountResponse->balance;
		$template = 'account_info';
		
		$this->data['error'] 		= $this->error;	
		$this->data['current_page'] = 'pinless';
		$this->load->view('pinless/'.$template, $this->data);
	}
	
	public function pinless_alias($phone){
		
		require 'PortaOneWS.php';
		$portaAuth = $this->config->item('portaone');
		// get Login Session ===============
		if ($this->session->userdata('portaoneSession')){
			$loginSession = $this->session->userdata('portaoneSession');
		} else {
			$loginSession = $this->portaoneSession();
		}
		
		$api 		  = new PortaOneWS("AccountAdminService.wsdl");
		$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
		if(isset($_POST) && count($_POST) > 0){
			if(!is_numeric($_POST['phoneNumber']) || strlen($_POST['phoneNumber']) != 10){
				$this->error['phoneNumber'] = $this->lang->line('msg_invalid_phone');
			}
						
			if(!$this->error){
				$AddAliasRequest = array("alias_info" => array(
										'i_master_account'  => $getAccountDetails->i_account,					
										'id' 				=> 'ani1'.$_POST['phoneNumber'],
										'blocked' 			=> 'N',
									)
								);
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->addAlias($AddAliasRequest, $loginSession);
				
				if($result->error){
					$this->data['warning'] = $result->error;
				}else{
					$this->session->set_userdata('message', 'Associated Number Seccessfully added.');
					redirect(site_url('pinless-manage/'.$phone));
				}
			}	
			$this->data += $_POST;
		}
		
		$getAliasRequest   = array( 'i_master_account'  => $getAccountDetails->i_account );
		$result = $api->getAliasList($getAliasRequest, $loginSession);
		
		$this->data['results'] 			= $result->alias_list;
		$this->data['phone'] 			= $phone;	
		$this->data['error'] 			= $this->error;	
		$this->data['current_page'] 	= 'pinless';
		$this->data['sub_current_page'] = 'pinless_alias';
		$this->load->view('pinless/_pinless', $this->data);
	}
	public function delete_alias(){
		require 'PortaOneWS.php';
		
		$data = array('success'=>0,'text'=>'Please try again!');
				
		if(isset($_POST) && count($_POST) > 0){
			$portaAuth 		= $this->config->item('portaone');
			// get Login Session ===============
			if ($this->session->userdata('portaoneSession')){
				$loginSession = $this->session->userdata('portaoneSession');
			} else {
				$loginSession = $this->portaoneSession();
			}
			
			$getParentAccount = $this->getAccountDetailsByPortaone($_POST['parentAccount'], $loginSession);
			$deleteAlias = array( 'alias_info' => array( 
										'i_master_account' 	=> $getParentAccount->i_account,
										'i_account' 		=> $_POST['id'],
										'id'				=> $_POST['aliasID']						
									) 
								);
			
			$api 	= new PortaOneWS("AccountAdminService.wsdl");
			$result = $api->deleteAlias($deleteAlias, $loginSession);
			
			if(!$result->error){
				$this->session->set_userdata('message', 'You have deleted associated number successfully!');
				$data = array('success'=>1);
			}else{ $data = array('success'=>0,'text'=>$result->error); }						
		}
		echo json_encode($data);	
	}
	
	public function pinless_speed_dial($phone){
		require 'PortaOneWS.php';
		$portaAuth = $this->config->item('portaone');
		// get Login Session ===============
		if ($this->session->userdata('portaoneSession')){
			$loginSession = $this->session->userdata('portaoneSession');
		} else {
			$loginSession = $this->portaoneSession();
		}
		
		$api 		  = new PortaOneWS("AccountAdminService.wsdl");
		$getParentAccount = $this->getAccountDetailsByPortaone($phone, $loginSession);
		
		// get speed dial ==============================================
		$getPhoneBook = array( 'offset' 			  => 0, 
							   'limit' 				  => NULL, 
							   'i_account' 			  => $getParentAccount->i_account, 
							   'phone_number_pattern' => NULL
						);
		$result = $api->getSpeeddial($getPhoneBook, $loginSession);
		
		if(isset($_POST) && count($_POST) > 0){
			if(!is_numeric($_POST['phoneNumber'])|| strlen($_POST['phoneNumber']) != 10){
				$this->error['phoneNumber'] = $this->lang->line('msg_invalid_phone');
			}
			if(strlen($_POST['contactType']) < 1){
				$this->error['contactType'] = 'Contact type required!';
			}
			
			if(!$this->error){
				if($result){
					$i = $result->phonebook_rec_list[0]->dial_id+1;
				}else{
					$i=1;
				}
				$addPhoneBook = array( 'phonebook_rec_info' => array('i_account' 	=> $getParentAccount->i_account, 
																	 'phone_number' => '1'.$_POST['phoneNumber'],
																	 'phone_type'	=> $_POST['contactType'],
																	 'name'			=> $_POST['contactName'],
																	 'dial_id'		=> $i
																	)
								);								
				$addResult = $api->addSpeeddial($addPhoneBook, $loginSession);				
				if($addResult->error){
					$this->data['warning'] = ($addResult->error) ? $addResult->error : 'Invalid Account ID';
				}else{
					$this->session->set_userdata('message', 'Speed dial added successfully!');
					redirect(site_url('speed-dial/'.$phone));
				}
			}	
			$this->data += $_POST;
		}
		
		// Contact Type
		$contactType   = array('' => '-- Contact Type --', 'work' => 'Work', 'home' => 'Home', 'mobile' => 'Mobile', 'other' => 'Other');
		$option_type = array();
		foreach ($contactType as $key => $value) {
			$option_type[$key] = $value;
		}
		
		$this->data['option_type'] 		= $option_type;
		$this->data['results'] 			= $result->phonebook_rec_list;
		$this->data['phone'] 			= $phone;	
		$this->data['error'] 			= $this->error;	
		$this->data['current_page'] 	= 'pinless';
		$this->data['sub_current_page'] = 'pinless_speed_dial';
		$this->load->view('pinless/_pinless', $this->data);
	}
	public function delete_speed_dial(){
		require 'PortaOneWS.php';
		
		$data = array('success'=>0,'text'=>'Please try again!');
				
		if(isset($_POST) && count($_POST) > 0){
			$portaAuth = $this->config->item('portaone');
			// get Login Session ===============
			if ($this->session->userdata('portaoneSession')){
				$loginSession = $this->session->userdata('portaoneSession');
			} else {
				$loginSession = $this->portaoneSession();
			}
				
			$deletePhoneBook = array( 'i_account_phonebook' => $_POST['id']);
			
			$api 	= new PortaOneWS("AccountAdminService.wsdl");
			$result = $api->deleteSpeeddial($deletePhoneBook, $loginSession);
			
			if(!$result->error){
				$this->session->set_userdata('message', 'You have deleted Speed dial successfully!');
				$data = array('success'=>1);
			}else{ $data = array('success'=>0,'text'=>$result->error); }						
		}
		echo json_encode($data);		 
	}
	
	public function calling_history($phone){
		require 'PortaOneWS.php';		
		$portaAuth 		= $this->config->item('portaone');
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['from_date'])) < 1) {
				$this->error['from_date'] = 'From date is required!';
			}
			if (strlen(trim($_POST['to_date'])) < 1) {
				$this->error['to_date'] = 'To date is required!';
			}
									
			if(!$this->error){
				// get Login Session ===============
				if ($this->session->userdata('portaoneSession')){
					$loginSession = $this->session->userdata('portaoneSession');
				} else {
					$loginSession = $this->portaoneSession();
				}
				
				$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
				$xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
									 'i_service' => 3,
									 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
									 'to_date'	 => date('Y-m-d 11:59:59', strtotime($_POST['to_date']))																			
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
		
		$this->data['phone'] 			= $phone;		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'pinless';
		$this->data['sub_current_page'] = 'calling_history';	
		$this->load->view('pinless/_pinless', $this->data);
	}
	public function transaction_history($phone){
		require 'PortaOneWS.php';		
		$portaAuth = $this->config->item('portaone');
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['from_date'])) < 1) {
				$this->error['from_date'] = 'From date is required!';
			}
			if (strlen(trim($_POST['to_date'])) < 1) {
				$this->error['to_date'] = 'To date is required!';
			}
									
			if(!$this->error){
				// get Login Session ===============
				if ($this->session->userdata('portaoneSession')){
					$loginSession = $this->session->userdata('portaoneSession');
				} else {
					$loginSession = $this->portaoneSession();
				}
				
				$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
				$xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
									 'i_service' => 2,					// Service ID 2 (Recharge / Transaction History)
									 'limit'	 => NULL,
									 'offset'	 => NULL,			
									 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
									 'to_date'	 => date('Y-m-d 11:59:59', strtotime($_POST['to_date'])),									 													
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
		
		$this->data['phone'] 			= $phone;		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'pinless';
		$this->data['sub_current_page'] = 'pinless_transaction_history';	
		$this->load->view('pinless/_pinless', $this->data);
	}
	public function pinless_recharge($phone){
		$info = $this->getAgentInfo();
		require 'PortaOneWS.php';
		$portaAuth = $this->config->item('portaone');
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['amount'])) < 1) {
				$this->error['amount'] = 'Valid Amount for recharge is required!';
			}
			
			if ($info->balance < $_POST['amount']) {
				$this->error['warning'] = $this->lang->line('msg_credit_not_enough');
				$this->data['warning'] = $this->lang->line('msg_credit_not_enough');
			}
			
			if(!$this->error){
				// get Login Session ===============
				if ($this->session->userdata('portaoneSession')){
					$loginSession = $this->session->userdata('portaoneSession');
				} else {
					$loginSession = $this->portaoneSession();
				}
				
				$api 		  	  = new PortaOneWS("AccountAdminService.wsdl");
				$getParentAccount = $this->getAccountDetailsByPortaone($phone, $loginSession);
				$currentBalance   = $getParentAccount->balance;				// Get Current balance
				$customerDetails  = $this->customer_model->getCustomerByPhone($phone);			// Get Customer Details
				//echo '<pre>';
				///echo $this->db->last_query();
				//print_r($customerDetails);die;
				
				if(!empty($customerDetails)){
					
					$updateAccountRequest = array('i_account' => $getParentAccount->i_account,										
											  'action' 	  => 'Manual payment',
										 	  'amount'	  => $_POST['amount']																				
								);
			
				$result = $api->rechargeAccount($updateAccountRequest, $loginSession);

				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$createdDate  = date('Y-m-d H:i:s');
						
					// Get Pinless product ======
					$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Pinless'));
					
					// Get Product Details by ProductID					
					$getCommission = $this->agent_model->getCommissionByProductID($this->agentID, $products[0]->vproductID);
					
					// calculate RYD Commission
					$rydCommission = 0;	
					if($getCommission)			
						$rydCommission = $_POST['amount'] * $getCommission->vproductAdminCommission / 100;
									
					//calculate store commission
					$storeCommission = 0;
					if ($getCommission) {
						$storeCommission = $_POST['amount'] * $getCommission->commissionRate / 100;
					}
					
					//calculate Distributor commission
					$distCommission = $_POST['amount'] * ($getCommission->vproducttotalCommission - ($getCommission->commissionRate + $getCommission->vproductAdminCommission)) / 100;
					
					//Check Subdist Parent Account
					$getParentDistributor = $this->getParentDistInfo($info->parentAgentID);
					$parentAgentID = $info->parentAgentID;
					if($getParentDistributor->agentTypeID == 4){
						$parentAgentID = $getParentDistributor->parentAgentID;
					}
														
					//insert to payment
					$dataPayment = array(
						'customerID'			=> $customerDetails->customerID,
						'seqNo'					=> $this->payment_model->getSeqNo($customerDetails->customerID),
						'paymentMethodID'		=> $this->config->item('payment_cash'),
						'chargedAmount'			=> $_POST['amount'],
						'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
						'chargedBy'				=> 'STORE SITE',
						'productID'				=> $getCommission->vproductID,
						'agentID'				=> $this->agentID,
						'storeCommission'		=> $storeCommission,
						'accountRepID'			=> $parentAgentID,
						'accountRepCommission'	=> $distCommission,
						'comment'				=> 'Pinless: ',
						'createdDate'			=> $createdDate,
						'adminCommission'		=> $rydCommission
					);				
					$this->payment_model->add($dataPayment);
					
					//update balance agent
					$totalCredit = -$_POST['amount'] + $storeCommission;
					$this->agent_model->updateBalance($this->agentID, $totalCredit);
					
					// Text Send to Distributor's cell number
					$rechargeAmount = $_POST['amount'];
					$newBalance  = $currentBalance+$rechargeAmount;
				
				    
					
					// Get Access Number and send in text 
					$areaCode 		 = substr($phone,0,3);
					//$language		 = $_POST['accessLanguage'];
					$message		 = '';
					$getAccessNumber = $this->customer_model->getAccessNumberForPinless("AccessNumber like '%$areaCode%' AND access_lang like '%English%'");
					
					
					if($getAccessNumber){ // Your Pinless Num Recharge of $$rechargeAmount is successful.
						$localAcessNumber=format_phone_number($getAccessNumber->AccessNumber);
			        	$textMessage = "Your Pinless Num Recharge of $$rechargeAmount is successful.Current balance is  $$newBalance. Dial 1-206-641-9081,1-612-800-6840,1-614-569-2880,1-678-263-3359, or $localAcessNumber & enter destination # when prompted. Thank you.	";
					//$message = sprintf($this->config->item('access_sms_message'), $rechargeAmount, format_phone_number($getAccessNumber->AccessNumber));
					}else{
						$getAccessNumber = $this->customer_model->getAccessNumberForPinless("city like '%default%' AND access_lang like '%English%'");
						$localAcessNumber=format_phone_number($getAccessNumber->AccessNumber);
						$textMessage = "Your Pinless Num Recharge of $$rechargeAmount is successful.Current balance is  $$newBalance. Dial 1-206-641-9081,1-612-800-6840,1-614-569-2880,1-678-263-3359, or $localAcessNumber & enter destination # when prompted. Thank you.	";
					//$message = sprintf($this->config->item('access_sms_message'), $rechargeAmount, format_phone_number($getAccessNumber->AccessNumber));
					}
					// * 'Your $%s Pinless is added successfully. Dial 1-206-641-9081, 1-612-800-6840, 1-614-569-2880, 1-678-263-3359, 1-%s & enter destination # when prompted. Thank you.';
					
					
					
				
					
					$subject	 = "Pinless Recharge";
					
					
					if($customerDetails->phoneEmailID){
						//echo "if";
						//echo $customerDetails->phoneEmailID;
						//die;
						$this->sendText($textMessage, $subject, $customerDetails->phoneEmailID);
					} else { 										// If Customer Exist without phone email
						//echo "else";die;
						$phoneEmail = $this->getPhoneEmail($phone);
						$data 		= array('phoneEmailID' => $phoneEmail?addslashes($phoneEmail):'' );
						$UpdateEmail = $this->customer_model->update( $customerDetails->customerID, $data );
						
						$this->sendText($textMessage, $subject, $phoneEmail);
					}
					$this->session->set_userdata('message', 'Account Recharge Successfully with amount $'.$_POST['amount']);
					redirect(site_url('pinless-recharge/'.$phone));
				}
					
					
					
				}else{
					
					$this->data['warning']="Customer with this phone number is not found in database !" ;
				}
				
				
			}
			$this->data += $_POST;
		}

		$this->data['phone']			= $phone;
		$this->data['error']			= $this->error;
		$this->data['current_page'] 	= 'pinless';
		$this->data['sub_current_page'] = 'pinless_account_recharge';	
		$this->load->view('pinless/_pinless', $this->data);
	} 

	public function pinless_access_number() {
		$this->data['results'] = $this->customer_model->getAccessNumbers();
		$this->data['current_page'] = 'pinless_access_number';
		$this->load->view('pinless/pinless_access_number', $this->data);
	}
	public function pinless_rate() {
		$where = array();
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['country']) > 0) {
				$where[] = "destination like '{$_POST['country']}%'";
			}
			/*if (strlen($_POST['countryCode']) > 0) {
				$where[] = "countryCode = '{$_POST['countryCode']}'";
			}*/
			$this->data += $_POST;
		} else {
			$this->data['balance'] = 5;
		}
		$num_per_page = 20;//$this->config->item('num_per_page');
		$paging_data = array(
			'limit' => $num_per_page,
			'current' => (isset($_POST['page']) && $_POST['page'] > 1) ? $_POST['page'] : 1,
			'total' => $this->customer_model->getTotalRates(implode(' AND ', $where))
		);
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
		$offset = ($paging_data['current'] - 1) * $num_per_page;
		$this->data['results'] = $this->customer_model->getRates(implode(' AND ', $where), $num_per_page, $offset);
			
		$option_country = array(''=>$this->lang->line('_all_'));
		$rates = $this->customer_model->getRates(array('terminationDate' => NULL));
		foreach ($rates as $item) {
			$option_country[trim($item->destination)] = trim($item->destination);
		}
		$this->data['option_country'] = $option_country;
		
		//option balance
		$this->data['option_balance'] = array('5'=>'5', '10'=>'10', '15'=>'15', '20'=>'20', '30'=>'30');
		
		//$this->load->view('popup_rate', $this->data);
		$this->data['current_page'] = 'pinless_rate';
		$this->load->view('pinless/pinless_rate', $this->data);
	}
	public function pinless_calling_history(){
		require 'PortaOneWS.php';		
		$portaAuth 		= $this->config->item('portaone');
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['from_date'])) < 1) {
				$this->error['from_date'] = 'From date is required!';
			}
			if (strlen(trim($_POST['to_date'])) < 1) {
				$this->error['to_date'] = 'To date is required!';
			}
			if(!is_numeric($_POST['phone_1']) || strlen($_POST['phone_1']) != 3 ||
			   !is_numeric($_POST['phone_2']) || strlen($_POST['phone_2']) != 3 ||
			   !is_numeric($_POST['phone_3']) || strlen($_POST['phone_3']) != 4){
				$this->error['phone_number'] = $this->lang->line('msg_invalid_phone');
			}
									
			if(!$this->error){
				$phone			= trim($_POST['phone_1'].$_POST['phone_2'].$_POST['phone_3']);
				
				// get Login Session ===============
				if ($this->session->userdata('portaoneSession')){
					$loginSession = $this->session->userdata('portaoneSession');
				} else {
					$loginSession = $this->portaoneSession();
				}
				
				$customer = $this->customer_model->getCustomerByPhone($phone);
				if ($customer) {
					$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
					$xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
										 'i_service' => 3,
										 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
										 'to_date'	 => date('Y-m-d 11:59:59', strtotime($_POST['to_date']))																			
									);
		
					$api 	= new PortaOneWS("AccountAdminService.wsdl");
					$result = $api->getxCdr($xcdrRequest, $loginSession);
					
					if($result->error){
						$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
					}else{
						$this->data['cdr_details'] = $result->xdr_list;
					}
				} else {					
					$this->data['warning'] = 'This phone number is not found in our database';
				}				
				
			}
			$this->data += $_POST;
		}
		
		$this->data['phone'] 			= $phone;		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'pinless_calling_history';
		$this->load->view('pinless/pinless_calling_history', $this->data);
	}
	
	public function pinless_activity(){
		$info = $this->getAgentInfo();
		$five_days_ago = date('Y-m-d', strtotime('-5 days', strtotime(date('Y-m-d'))));
		
		$from_date = date('Y-m-d', strtotime( $five_days_ago) );
		$to_date   = date('Y-m-d', strtotime( date('Y-m-d') ));
		$product   = 'Pinless';
		$storeSaleReport = $this->payment_model->getStoreSalesActivity($this->agentID, $product, $from_date, $to_date);
		
		$this->data['results'] 		= $storeSaleReport;		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'report';
		$this->load->view('reports/pinless_activity', $this->data); 
	}
	public function pinless_refund($customerId='', $seq=''){
		require 'PortaOneWS.php';
		$info = $this->getAgentInfo();
		$five_days_ago 	= date('Y-m-d', strtotime('-5 days', strtotime(date('Y-m-d'))));
		$paymentDetails = $this->payment_model->getPaymentActivity($customerId, $seq);
		
		// get Login Session and Current Balance from Portaone
		if ($this->session->userdata('portaoneSession')){
			$loginSession = $this->session->userdata('portaoneSession');
		} else {
			$loginSession = $this->portaoneSession();
		}
				
		if(isset($_POST) && count($_POST)>0 ){
			
			if (strlen(trim($_POST['refundReason'])) < 1) {
				$this->error['refundReason'] = 'Reason is required!';
			}
			if ($_POST['current_balance'] < $_POST['refund_amount']) {
				$this->error['warning'] = 'This amount has been partially used and can not be refunded';
				$this->data['warning']  = 'This amount has been partially used and can not be refunded';
			}
			
			if(!$this->error){
				$updateAccountRequest = array('i_account' => $_POST['i_account'],										
											  'action' 	  => 'Refund',
										 	  'amount'	  => $paymentDetails->chargedAmount																				
								);
				$api  	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->rechargeAccount($updateAccountRequest, $loginSession);
				
				if($result->error){
					$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
				}else{
					$data = array(	'storeID' 				=> $this->agentID,
								  	'customerID'			=> $customerId,
									'phoneNumber'			=> $paymentDetails->phoneNumber,
									'refundAmount' 			=> $paymentDetails->chargedAmount,
									'refundReason' 			=> $_POST['refundReason'],
									'otherReason'			=> $_POST['other_reason'],
									'refundStoreCommission' => $paymentDetails->storeCommission,
									'refundDistCommission' 	=> $paymentDetails->accountRepCommission,
									'refundAdminCommission' => $paymentDetails->adminCommission,
									'dateTime' 				=> date('Y-m-d')
							);
					$this->admin_model->addPinlessRefund($data);
					
					// Update Commissions
					//$dataUpdate = array('storeCommission' => 0, 'accountRepCommission' => 0, 'adminCommission' => 0, 'isRefund' => 1);
					$dataUpdate = array('storeCommission' 		=> 0, 
										'accountRepCommission'  => 0,
										'adminCommission' 		=> 0, 
										//'chargedAmount'			=> 0,
										'isRefund' 				=> 1
								);
					$this->payment_model->update($paymentDetails->payment_id, $dataUpdate);
					
					//update balance agent
					$refundCredit = -$paymentDetails->storeCommission + $paymentDetails->chargedAmount;
					$this->agent_model->updateBalance($this->agentID, $refundCredit);
					
					$this->session->set_userdata('message', 'Refund request successfully done and the amount refunded back to the store');
					redirect('pinless-activity');						
				}		// If else API response
			}		// if error
			
			$this->data += $_POST;
			
		}		// if POST	
		
		// Reason array
		$reasons = array('' => 'Select Reason',
						 'Wrong Number' 	=> 'Wrong Number',
						 'Wrong Amount' 	=> 'Wrong Amount',
						 'Duplicate Entry' 	=> 'Duplicate Entry',
						 'Does not Connect' => 'Does not Connect',
						 'Bad Call Quality' => 'Bad Call Quality',
						 'Didn\'t pay'		=> 'Didn\'t pay',
						 'Changed his mind' => 'Changed his mind',
						 'Didn\'t get text message' => 'Didn\'t get text message',
						 'Didn\'t like rates'		=> 'Didn\'t like rates',
						 'yes'				=> 'Other'
						 );
		$option_reasons = array();
		foreach($reasons as $key=>$item){
			$option_reasons[$key] = $item;
		}
		$this->data['option_reasons']	= $option_reasons;
		
		// Get Account response By Portaone	
		$getAccountResponse = $this->getAccountDetailsByPortaone($paymentDetails->phoneNumber, $loginSession);
		
		$this->data['results']   		= $paymentDetails;
		$this->data['balance'] 	 		= $getAccountResponse->balance;
		$this->data['i_account'] 		= $getAccountResponse->i_account;		
		$this->data['customerId'] 		= $customerId;
		$this->data['seq'] 				= $seq;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'report';
		$this->load->view('reports/pinless_refund', $this->data); 
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
	private function addNewPortaoneAccount($phone, $loginSession, $portaAuth, $openingAmount){
		$password = substr(uniqid('', true), -5);		// Generate Random password
		$AddAccountRequest = array("account_info" => array(
									'i_customer' 		=> $portaAuth['customerID'],
									'billing_model' 	=> '-1',
									'i_product' 		=> $portaAuth['product'],
									'activation_date' 	=> date('Y-m-d'),
									'id' 				=> 'ani'.'1'.$phone,
									'balance' 			=> '0',
									'opening_balance' 	=> $openingAmount,
									'i_time_zone' 		=> '260',
									'i_lang' 			=> 'en',
									'login' 			=> $phone,
									'password'			=> $password,
									'h323_password' 	=> $password,
									'blocked' 			=> 'N'
								)
							);
		
		$api 	= new PortaOneWS("AccountAdminService.wsdl");
		$result = $api->addAccount($AddAccountRequest, $loginSession);
		return $result;
	}
	private function getAccountDetailsByPortaone($phone, $loginSession){
		$getAccountRequest = array('login' => $phone);
		$api 	= new PortaOneWS("AccountAdminService.wsdl");
		$result = $api->getInfo($getAccountRequest, $loginSession);
		return $result->account_info;
	}
		
	// Pinless END  ===========================================================================
	
	/*=========================================================================================
	 * CALLING CARDS
	 =========================================================================================*/
	public function buy_calling_card($batchID=''){
	 	$template = 'buy_card';
		$info = $this->getAgentInfo($this->agentID);
		
		// Get calling card Instruction
		$getInstruction		 = $this->agent_model->getSettings(array('settingKey' => 'ccardInstruction'));
	
		$getDisclaimer  	 = $this->agent_model->getSettings(array('settingKey' => 'ccardDisclaimer'));
		$getTollFree  		 = $this->agent_model->getSettings(array('settingKey' => 'ccardTollFree'));
		$getCustomerService  = $this->agent_model->getSettings(array('settingKey' => 'ccardCustomerService'));
		
		if(isset($_POST) && count($_POST) > 0){
			
			if(trim($_POST['email']) != ''){
				if(isset($_POST['notify_by_email']) && strlen(trim($_POST['email'])) < 1 ){
					$this->error['email'] = 'Email required for email notify';
				}elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				    $this->error['email'] = 'Invalid Email';
				}	
			}
						
			if(isset($_POST['notify_by_text']) && strlen(trim($_POST['phone_number'])) < 1){
				$this->error['phone_number'] = 'Phone required for text notify';
			} elseif (strlen(trim($_POST['phone_number'])) > 0 && !is_numeric(trim($_POST['phone_number']))) {
				$this->error['phone_number'] = 'Invalid phone number!';
			}
					
			if ($info->balance < $_POST['total_amount']) {
				$this->error['cardAmount'] = $this->lang->line('msg_credit_not_enough');
				$this->data['warning'] 	   = $this->lang->line('msg_credit_not_enough');
			}
			
			if(!$this->error){				
				
				//$getBatcheDetails = $this->customer_model->getCallingCardDetails("callingCardBatchName like '%$batchName%'", array('callingCardPurchaseDate' => NULL, 'callingCardPurchaseStore' => NULL));
				$getBatcheDetails = $this->customer_model->getCallingCardDetails(array('callingCardBatchName' =>  $_POST['card_batch'],'callingCardPurchaseDate' => NULL, 'callingCardPurchaseStore' => NULL));
				$quantity   = $_POST['quantity'];
				$cardAmount = $getBatcheDetails->batchAmount;
				$amount		= $_POST['total_amount'];
				
				$pinDetails 		= '';
				$pinDetailsforText 	= '';
				$cardIDs 			= array();
				if($_POST['quantity']>1){						// If Quantity More then 1
					$id = $getBatcheDetails->callingCardID + 1;	
					
					// Set first Card details in message
					$pinDetails .= "<tr>";
						$pinDetails .= "<td> $getBatcheDetails->callingCardPin </td>";
						$pinDetails .= "<td> ".format_price($getBatcheDetails->batchAmount)."</td>";
					$pinDetails .= "</tr>";
					
					// PIN For Text
					if($pinDetailsforText){
						$pinDetailsforText .= ', '.$getBatcheDetails->callingCardPin;
					}else{
						$pinDetailsforText .= $getBatcheDetails->callingCardPin;
					}
					// get first card IDs
					$cardIDs[] = $getBatcheDetails->callingCardID;
									
					for($i=1; $i < $_POST['quantity']; $i++){
						$getCardDetailsByID = $this->customer_model->getCallingCardDetails(array('callingCardID' => $id,'callingCardPurchaseDate' => NULL, 'callingCardPurchaseStore' => NULL));
						
						// Set other Card details in message
						$pinDetails .= "<tr>";
							$pinDetails .= "<td> $getCardDetailsByID->callingCardPin </td>";
							$pinDetails .= "<td> ".format_price($getCardDetailsByID->batchAmount)."</td>";
						$pinDetails .= "</tr>";
						
						// PIN For Text
						if($pinDetailsforText){
							$pinDetailsforText .= ', '.$getCardDetailsByID->callingCardPin;
						}else{
							$pinDetailsforText .= $getCardDetailsByID->callingCardPin;
						}
						
						// get all cards IDs
						$cardIDs[] = $getCardDetailsByID->callingCardID;
						$id++;
					}
										
				}else{						// If Quantity is 1
					$pinDetails .= "<tr>";
						$pinDetails .= "<td> $getBatcheDetails->callingCardPin </td>";
						$pinDetails .= "<td> ".format_price($getBatcheDetails->batchAmount)."</td>";
					$pinDetails .= "</tr>";
					
					// PIN For Text
					$pinDetailsforText .= $getBatcheDetails->callingCardPin;
					
					// get all cards IDs
					$cardIDs[] = $getBatcheDetails->callingCardID;
				}
				
				
				
				/*$textMessage  = "Thank you for Purchasing VC Calling Card. Your #PINs are $pinDetailsforText with amount $$cardAmount / card.
								Your local access number is ".$_POST['local_access_number'];*/
				$textMessage  = 'Thank you for your Purchase of $'.$cardAmount.' Pin# '.$pinDetailsforText.' Calling Card. Local Access# '.format_phone_number($_POST['local_access_number']).', Toll Free# '.format_phone_number($getTollFree->settingValue).', Customer Service# '.format_phone_number($getCustomerService->settingValue);
				$emailMessage = 'Thank you for purchasing Vonecall Calling Card.  Please see below for your PIN and Access Numbers. <br><br>
								<table width="100%" cellpadding="3" cellspacing="3" style="border: 1px solid #ccc">
									<tr>
										<td width="25%" style="border: 1px solid #ccc;"> Store Name: '.$info->firstName.' '.$info->lastName.'</td> 
										<td style="border: 1px solid #ccc;"> Transaction Date: '.date('m/d/Y').' </td>						
									</tr>
									<tr>
										<td style="border: 1px solid #ccc;"> <h3>Pin Number</h3> </td> 
										<td style="border: 1px solid #ccc;"> '.$pinDetailsforText.' </td>
									</tr>
									<tr>
										<td style="border: 1px solid #ccc;"> Denomination </td>	
										<td style="border: 1px solid #ccc;"> $'.$cardAmount.'</td>
									</tr>
									<tr>
										<td colspan="2" align="center" style="border: 1px solid #ccc;"> <b>Access Numbers</b> </td>
									</tr>
									<tr>
										<td style="border: 1px solid #ccc;"> Access Number</td> 
										<td style="border: 1px solid #ccc;">'.format_phone_number($_POST['local_access_number']).'</td>
									</tr>
									<tr>
										<td style="border: 1px solid #ccc;"> Toll Free </td> 
										<td style="border: 1px solid #ccc;">'.format_phone_number($getTollFree->settingValue).'</td>
									</tr>
									<tr>
										<td style="border: 1px solid #ccc;"> Customer Service </td> 
										<td style="border: 1px solid #ccc;">'.format_phone_number($getCustomerService->settingValue).'</td>
									</tr>
									<tr>
										<td style="border: 1px solid #ccc;">Instruction:</td>
										<td style="border: 1px solid #ccc;">'.$getInstruction->settingValue.'</td>
									</tr>
									<tr>
										<td style="border: 1px solid #ccc;">Disclaimer:</td>
										<td style="border: 1px solid #ccc;">'.$getDisclaimer->settingValue.'</td>
									</tr>
								</table>
								<br><br>
								Thanks <br>
								Vonecall team';
				
				// Update Card Status ========================
				$data = array(  'callingCardPurchaseDate' 		=> date('Y-m-d H:i:s'),
								'callingCardPurchaseStore' 		=> $this->agentID,
								'callingCardPurchaseStoreName' 	=> $info->company_name,
								'callingCardLocalAccess'		=> $_POST['local_access_number']);
				
				foreach($cardIDs as $key => $id){
					$this->customer_model->updateCallingCard($id, $data);
				}				
				// Update Card Status END ====================
				
				// Check Customer account ======================================
				$customerID = '';	
				if(isset($_POST['notify_by_text'])){		
					$customerDetails = $this->customer_model->getCustomerByPhone($_POST['phone_number']);
					if($customerDetails){			// Customer Exist
						if($customerDetails->phoneEmailID){ 				// If Customer Exist with phone email
							$phoneEmail = $customerDetails->phoneEmailID;
						} else { 											// If Customer Exist without phone email
							$phoneEmail  = $this->getPhoneEmail($phone);
							$data 		 = array('phoneEmailID' => $phoneEmail?addslashes($phoneEmail):'' );
							$UpdateEmail = $this->customer_model->update( $customerDetails->customerID, $data );
						}
						$customerID = $customerDetails->customerID;
					} else {						// New Customer
						$phoneEmail   = $this->getPhoneEmail($phone);					
						$firstName	  = isset($_POST['firstName'])?$_POST['firstName']:'';
						$lastName	  = isset($_POST['lastName'])?$_POST['lastName']:'';
						$createdDate  = date('Y-m-d H:i:s');
						$randomSubsId = trim('04156'.substr(number_format(time() * rand(),0,'',''),0,10));
						$subscriberId = preg_replace('/[\s]+/','',$randomSubsId);
						$data = array(
							'firstName'		=> $firstName,
							'lastName'		=> $lastName,
							'phone'			=> $phone,
							'loginID'		=> $phone,
							'password'		=> date('mdyHi'),
							'agentID'		=> $this->agentID,
							'createdDate'	=> $createdDate,
							'subscriberID'  => $subscriberId,
							'customerProduct'=> 'calling_card',
							'phoneEmailID'	=> $phoneEmail?addslashes($phoneEmail):''
						);					
						$customerID = $this->customer_model->add($data);	
					}				
				}
				//==================================
				//===== Calculate Commission========
				//==================================
				
				// Get Pinless product ======
				$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Calling Card'));
				
				// Get Product Details by ProductID					
				$getCommission = $this->agent_model->getCommissionByProductID($this->agentID, $products[0]->vproductID);
				
				// calculate RYD Commission
				$rydCommission = 0;	
				if($getCommission)			
					$rydCommission = $amount * $getCommission->vproductAdminCommission / 100;
								
				//calculate store commission
				$storeCommission = 0;
				if ($getCommission) {
					$storeCommission = $amount * $getCommission->commissionRate / 100;
				}
				
				//calculate Distributor commission
				$distCommission = $amount * ($getCommission->vproducttotalCommission - ($getCommission->commissionRate + $getCommission->vproductAdminCommission)) / 100;
				
				//Check Subdist Parent Account
				$getParentDistributor = $this->getParentDistInfo($info->parentAgentID);
				$parentAgentID = $info->parentAgentID;
				if($getParentDistributor->agentTypeID == 4){
					$parentAgentID = $getParentDistributor->parentAgentID;
				}
				
				//insert to payment
				$dataPayment = array(
					'customerID'			=> $this->agentID,
					'seqNo'					=> $this->payment_model->getSeqNo($this->agentID),
					'paymentMethodID'		=> $this->config->item('payment_cash'),
					'chargedAmount'			=> $amount,
					'enteredBy'				=> $this->session->userdata('store_username').' - '.$this->session->userdata('store_usertype'),
					'chargedBy'				=> 'STORE SITE',
					'productID'				=> $getCommission->vproductID,
					'agentID'				=> $this->agentID,
					'storeCommission'		=> $storeCommission,
					'accountRepID'			=> $parentAgentID,
					'accountRepCommission'	=> $distCommission,
					'comment'				=> 'Calling Card: ',
					'createdDate'			=> date('Y-m-d H:i:s'),
					'adminCommission'		=> $rydCommission
				);				
				$this->payment_model->add($dataPayment);
				
				//update balance agent
				$totalCredit = -$amount + $storeCommission;
				$this->agent_model->updateBalance($this->agentID, $totalCredit);
				
				$subject	 = "Vonecall Calling Card";
				// Text Send to Customer's cell number
				$emailWithText = '';
				if($_POST['notify_by_text']){
					if($phoneEmail);
						$this->sendText($textMessage, $subject, $phoneEmail);
						$emailWithText = TRUE;
				}
				
				// Send Email to Customer's Email =========================				
				if($_POST['notify_by_email'])
					$this->sendEmail($emailMessage, $subject, $_POST['email'], $emailWithText);
				
				// SET Cards IDs in Session
				$this->session->set_userdata('cardID', $cardIDs);
				
				redirect(site_url('calling-card-success'));
			}
			
			$this->data += $_POST;	
		}
		
		//Batch
		$option_batch 	  = array();
		$getBatches 	  = $this->customer_model->getCardBatches();
		foreach ($getBatches as $item) {
			$option_batch[$item->batchID] = format_price($item->batchAmount);
		}
		$this->data['option_batch'] = $option_batch;
		
		//Local Access Numbers
		$option_accessNumber = array();
		$getAccessNumbers    = $this->customer_model->getCallingCardAccessNumbers();
		foreach ($getAccessNumbers as $item) {
			$option_accessNumber[$item->accessNumber] = format_phone_number($item->accessNumber);
		}
		$this->data['option_accessNumber'] = $option_accessNumber;
		
		// Get Valid products with Allowed Commission
		$validCommission = '';
		$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Calling Card'));
		if($products)
			$validCommission=TRUE;
		
		$this->data['tollFree']			= $getTollFree?$getTollFree->settingValue:'9999999999';
		$this->data['customerService'] 	= $getCustomerService?$getCustomerService->settingValue:'8888888888';
		
		$this->data['validCommission'] = $validCommission;
		$this->data['batches'] 		= $getBatches;
		$this->data['error'] 		= $this->error;	
		$this->data['current_page'] = 'calling_card';
		$this->load->view('calling_card/'.$template, $this->data);
	 }
	public function calling_card_success($id=''){
		$info = $this->getAgentInfo($this->agentID);
		
		// Get calling card Instruction
		$getInstruction		 = $this->agent_model->getSettings(array('settingKey' => 'ccardInstruction'));
		$getDisclaimer  	 = $this->agent_model->getSettings(array('settingKey' => 'ccardDisclaimer'));
		$getTollFree  		 = $this->agent_model->getSettings(array('settingKey' => 'ccardTollFree'));
		$getCustomerService  = $this->agent_model->getSettings(array('settingKey' => 'ccardCustomerService'));
		
		$cardDetailsByID = array();
		foreach($this->session->userdata['cardID'] as $key => $id){
			$cardDetailsByID[] = $this->customer_model->getCallingCardDetails(array('callingCardID' => $id));
		}
		
		$this->data['info'] 			= $info;
		$this->data['results'] 			= $cardDetailsByID;
		$this->data['instruction']		= $getInstruction?$getInstruction->settingValue:'Instruction for Calling cards';
		$this->data['disclaimer'] 		= $getDisclaimer?$getDisclaimer->settingValue:'Disclaimer for Calling cards';
		$this->data['tollFree']			= $getTollFree?$getTollFree->settingValue:'9999999999';
		$this->data['customerService'] 	= $getCustomerService?$getCustomerService->settingValue:'8888888888';
		$this->data['current_page'] 	= 'calling_card';
		$template = 'calling_card_success';
		$this->load->view('calling_card/'.$template, $this->data);
	}
	 
	public function calling_card_history(){
		require 'PortaOneWS.php';		
		$portaAuth 		= $this->config->item('portaone');
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen(trim($_POST['from_date'])) < 1) {
				$this->error['from_date'] = 'From date is required!';
			}
			if (strlen(trim($_POST['to_date'])) < 1) {
				$this->error['to_date'] = 'To date is required!';
			}
			if(!is_numeric($_POST['card_pin']) || strlen($_POST['card_pin']) < 0 ){
				$this->error['card_pin'] = 'Invalid card pin!';
			}
									
			if(!$this->error){
				$pin = trim($_POST['card_pin']);
				
				// get Login Session
				if ($this->session->userdata('portaoneSession')){
					$loginSession = $this->session->userdata('portaoneSession');
				} else {
					$loginSession = $this->portaoneSession();
				}
				
				$getPINRecord = $this->customer_model->getCallingCardDetails(array('callingCardPin' => $pin));
				
				if ($getPINRecord) {
					$getAccountDetails = $this->getAccountDetailsByPortaone($pin, $loginSession);
					$xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
										 'i_service' => 3,
										 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
										 'to_date'	 => date('Y-m-d 11:59:59', strtotime($_POST['to_date']))																			
									);
		
					$api 	= new PortaOneWS("AccountAdminService.wsdl");
					$result = $api->getxCdr($xcdrRequest, $loginSession);
					if($result->error){
						$this->data['warning'] = ($result->error) ? $result->error : 'Invalid Account ID';
					}else{
						$this->data['cdr_details'] = $result->xdr_list;
					}
				} else {					
					$this->data['warning'] = 'This PIN number is not found in our database';
				}			
			}
			$this->data += $_POST;
		}
		
		$this->data['phone'] 			= $phone;		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'calling_card';
		$this->load->view('calling_card/calling_card_history', $this->data);
	}
	public function calling_card_rate_sheet(){
		$where = array();
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['country']) > 0) {
				$where[] = "destination like '{$_POST['country']}%'";
			}
			/*if (strlen($_POST['countryCode']) > 0) {
				$where[] = "countryCode = '{$_POST['countryCode']}'";
			}*/
			$this->data += $_POST;
		} else {
			$this->data['balance'] = 5;
		}
		$num_per_page = 20;//$this->config->item('num_per_page');
		$paging_data = array(
			'limit' => $num_per_page,
			'current' => (isset($_POST['page']) && $_POST['page'] > 1) ? $_POST['page'] : 1,
			'total' => $this->customer_model->getTotalRates(implode(' AND ', $where))
		);
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
		$offset = ($paging_data['current'] - 1) * $num_per_page;
		$this->data['results'] = $this->customer_model->getRates(implode(' AND ', $where), $num_per_page, $offset);
			
		$option_country = array(''=>$this->lang->line('_all_'));
		$rates = $this->customer_model->getRates(array('terminationDate' => NULL));
		foreach ($rates as $item) {
			$option_country[trim($item->destination)] = trim($item->destination);
		}
		$this->data['option_country'] = $option_country;
		
		//option balance
		$this->data['option_balance'] = array('5'=>'5', '10'=>'10', '15'=>'15', '20'=>'20', '30'=>'30');
		
		//$this->load->view('popup_rate', $this->data);
		$this->data['current_page'] = 'calling_card';
		$this->load->view('calling_card/calling_card_rate', $this->data);
	}
	/*=========================================================================================
	 CALLING CARDS END
	=========================================================================================*/
	
	public function calculate_agent() {
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
		$response = $this->agent_model->getAgentSaleQuery($this->agentID, $from_date, $to_date);
		if ($response) {
			$response->success 		= 1;
			$response->Sale 		= format_price($response->Sale);
			$response->Promotion 	= format_price($response->Promotion);
			$response->Payment 		= format_price($response->Payment);
			$response->Commission 	= format_price($response->Commission);
		} else {
			$response = array('success' => 0, 'text' => $this->lang->line('msg_data_not_found'));
		}
		echo json_encode($response);
	}

	public function get_products_by_country($id){
		$products      = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'RTR', 'vp.vproductCountryCode' => $id));
		$getLogoResult = $this->customer_model->array_unique_by_key($products, "ppnProductID");
		$ret = "";
		$url = $this->config->item('base_url');
		
		foreach ($getLogoResult as $item) {
			$urlRecharge = site_url('topup-recharge/'.$item->ppnProductID);			
			$ret .= "<div class=\"operatorBox\" >
					   <a href=\"$urlRecharge\">
				         <img src=\"$url/systemadmin/public/uploads/product_logo/$item->logoName\" alt=\"$item->vproductVendor\" width=\"150\" height=\"90\" />
				    	 <span>  $item->vproductVendor </span>
				       </a>	
				    </div>";			
		}
		echo $ret;
	}
	
	public function get_country_flag($countryCode){		
		$countryDetails = $this->customer_model->getCountries(array('CountryCodeIso' => $countryCode));
		$countryFlag = $countryDetails[0]->countryFlag;
		$countryName = $countryDetails[0]->CountryName;
		$url = $this->config->item('base_url');
		$ret = "<div class=\"countryFlag\"><img src=\"$url/systemadmin/public/uploads/country_flag/$countryFlag\" alt=\"$countryName\" width=\"200\" /></div>";			
		echo $ret;
	}
	
	public function get_amount_by_carrier($carrier){
		$getCarrier = explode('-', $carrier); 
		$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'RTR', 'vp.vproductVendor' => $getCarrier[0]));
		$amountArray = array('10'=>'$10', '20'=>'$20', '50'=>'$50');
		$ret = "<option value=\"\">-- Amount --</option>";
		foreach ($amountArray as $key=>$item) {
			$ret .= "<option value=\"{$key}\" >{$item}</option>";
		}
		echo $ret;
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
	private function getParentDistInfo($distID) {
		//agent info
		$info = $this->agent_model->getAgent($distID);		
		return $info;
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
	
	// Data24X7 ==============
	private function getPhoneEmail($phonenum){			
		$textSettings = $this->getMode('textMode', 'textUsername', 'textPassword');
		$userName = $textSettings['username'];
		$password = $textSettings['password'];
		
		if ($phonenum[0] != "1") $phonenum = "1" . $phonenum;
		$url 	= "https://api.data24-7.com/v/2.0?api=T&user=$userName&pass=$password&p1=$phonenum";
		//return '6619933305@tmomail.net';
		//echo $url;
		//echo '<pre>';
		//print_r($result);die;
		$result = simplexml_load_file($url) or die("feed not loading");
		
		if($result->results->result->status=='OK'){
			return $result->results->result->sms_address;
		}else{
			$this->error['cellphone'] = 'Invalid Cellphone number';
		}
	}
	// Send Text ================
	private function sendText ($message, $subject = "", $text_to = "", $mobile="") {
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
		$mail->AddAddress($text_to);							 // Receiver Email			
		$mail->WordWrap 	= 50;                                // set word wrap to 50 characters
		//$mail->IsHTML(true);                                   	 // set email format to HTML
		
		$mail->Subject = $subject;
		$mail->Body    = $message;						
		if(!$mail->Send())
		{
			
		   //$this->error['email_error'] = 'Message could not be sent: '.$mail->ErrorInfo;
		   $this->error['email_error'];die;
		   return false;
		}			
		return true;		  	
    }

	// Send Email ================
	private function sendEmail ($message, $subject, $emailTo, $emailWithText='') {
		if($emailWithText=='')
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
		$mail->AddAddress($emailTo);							 // Receiver Email			
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
    
    public function test_portaone()
	{
		require 'PortaOneWS.php';
		$loginSession = $this->portaoneSession();
			
		echo 'sess--';print_r($loginSession);
		$phone='2012337812';
		
		$getAccountResponse = $this->getAccountDetailsByPortaone($phone, $loginSession);	// Get Account details from Portaone 					
		echo "<pre>";print_r($getAccountResponse);die;
	}
	

	public function test_sms()
	{
		
			$textSettings = $this->getMode('textMode', 'textUsername', 'textPassword');
		$userName = $textSettings['username'];
		$password = $textSettings['password'];
		$phonenum='6619933305';
		if ($phonenum[0] != "1") $phonenum = "1" . $phonenum;
		$url 	= "https://api.data24-7.com/v/2.0?api=T&user=$userName&pass=$password&p1=$phonenum";
		//return '6619933305@tmomail.net';
		//echo $url;
		//echo '<pre>';
		//print_r($result);die;
		$result = simplexml_load_file($url) or die("feed not loading");
		
		if($result->results->result->status=='OK'){
			 $getPhoneEmail=$result->results->result->sms_address;
		}else{
			echo 'Invalid Cellphone number';
		}
		
		echo $getPhoneEmail;
		$a=$this->sendText('Hello Vonecall','sms test',$getPhoneEmail);		
		echo $a;
		if($a===true){echo 'sent';}else{echo 'not sent';}
	}
 
	
}
?>
