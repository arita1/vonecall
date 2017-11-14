<?php
class Prepaynation extends CI_Controller {
	
	private $data = array();
	private $error = array();
	
	function __construct() {
		parent::__construct();
		$this->load->helper(array('form','format'));
		$this->load->model('admin_model');
		$this->load->model('customer_model');  
		$this->load->model('agent_model');  
		$this->load->model('payment_model'); 
		
		// Get Message
		if($this->session->userdata('message')){
			$this->data['message'] = $this->session->userdata('message');
			$this->session->unset_userdata('message');	
		}
				
	}
	public function index() {		
		$this->data['current_page'] 	= 'ppn';
		$this->data['sub_current_page'] = 'index_ppn';	
		$this->load->view('ppn/_ppn', $this->data);
	}
	public function carrier_list(){
		include 'PrepayNationWS.php';
		$this->data['carrierList'] 		= $this->get_carrier_list();
		$this->data['current_page'] 	= 'ppn';
		$this->data['sub_current_page'] = 'carrierlist';	
		$this->load->view('ppn/_ppn', $this->data);
	}
	public function product_list(){
		include 'PrepayNationWS.php';
		if(isset($_POST) && count($_POST) > 0){
					
			if (strlen(trim($_POST['carrierID'])) < 1) {
				$this->error['carrierID'] = 'Carrier is required!';
			}
						
			if(!$this->error){				
				//$ppnDetails = $this->config->item('prepaynation');	
				// getPPN Mode
				$getPPNMode     = $this->admin_model->getSettings(array('settingKey' => 'ppnMode'));
				$getPPNUsername = $this->admin_model->getSettings(array('settingKey' => 'ppnUsername'));
				$getPPNPassword = $this->admin_model->getSettings(array('settingKey' => 'ppnPassword'));			
				
				$username = $getPPNUsername->settingValue;
				$password = $getPPNPassword->settingValue;
				if($getPPNMode->settingValue=='test'){
					$sandbox = TRUE;
				}else{
					$sandbox = FALSE;
				}
				// getPPN Mode END	
				
				$api     = new PrepaynationWS($username, $password, $sandbox);						
				$carrier = array( 'carrierId' => $_POST['carrierID'] );
				$result  = $api->getSkuListByCarrier( $carrier );
				$this->data['products'] = $result;
			}
			$this->data += $_POST; 
		}
		
		$carrierList 	 = array();
		$carrierList[''] = '-- Select --';		
		$getcarrierList  = $this->get_carrier_list();
		foreach($getcarrierList as $items){
			$carrierList[$items->carrierId] = $items->carrierName;
		}		
		$this->data['carrierList'] 		= $carrierList;
		$this->data['current_page'] 	= 'ppn';
		$this->data['sub_current_page'] = 'productlist';	
		$this->load->view('ppn/_ppn', $this->data);
	}

	// RTR ====================================
	public function topup_recharge(){
		include 'PrepayNationWS.php';
		
		if(isset($_POST) && count($_POST) > 0){
			/*if (strlen(trim($_POST['carrierID'])) < 1) {
				$this->error['carrierID'] = 'Carrier is required!';
			}*/
			if (strlen(trim($_POST['product'])) < 1) {
				$this->error['product'] = 'Product is required!';
			}
			if (strlen(trim($_POST['rechargeAmount'])) < 1) {
				$this->error['rechargeAmount'] = 'Amount is required!';
			}
			if (strlen(trim($_POST['areaCode'])) < 1) {
				$this->error['areaCode'] = 'Area Code is required!';
			}
			if (strlen(trim($_POST['rechargeNumber'])) < 1) {
				$this->error['rechargeNumber'] = 'Recipient Mobile is required!';
			}
			if (strlen(trim($_POST['store'])) < 1) {
				$this->error['store'] = 'Store is required!';
			}
									
			if(!$this->error){
				
				// getPPN Mode
				$getPPNMode     = $this->admin_model->getSettings(array('settingKey' => 'ppnMode'));
				$getPPNUsername = $this->admin_model->getSettings(array('settingKey' => 'ppnUsername'));
				$getPPNPassword = $this->admin_model->getSettings(array('settingKey' => 'ppnPassword'));			
				
				$username = $getPPNUsername->settingValue;
				$password = $getPPNPassword->settingValue;
				if($getPPNMode->settingValue=='test'){
					$sandbox = TRUE;
				}else{
					$sandbox = FALSE;
				}
				// getPPN Mode END	
					
				$api = new PrepaynationWS($username, $password, $sandbox);
				
				$purchaseRtr2Param = array(
										'skuId' 		=> $_POST['product'],
										'amount'		=> $_POST['rechargeAmount'],
										'mobile' 		=> $_POST['areaCode'].$_POST['rechargeNumber'],
										'corelationId' 	=> '',
										'senderMobile' 	=> $_POST['senderNumber'],
										'storeId' 		=> ''
									);
						
				$purchaseRtr2 = $api->PurchaseRtr2($purchaseRtr2Param);
				
				if (!isset($purchaseRtr2->error)) {
					$getStore  		 = $this->getStoreInfo($_POST['store']);
					$getProductComm  = $this->agent_model->getCommissionByProductSKU( $_POST['store'],$sku=$_POST['product']);
										
					$phone    		= $_POST['rechargeNumber'];
					$amount   		= $purchaseRtr2->invoice->faceValueAmount;
					$operator 		= $purchaseRtr2->invoice->cards->card->name;
					$createdDate	= date('Y-m-d H:i:s');
					
					// calculate RYD Commission
					$rydCommission = 0;	
					if($getProductComm)			
						$rydCommission = $_POST['rechargeAmount'] * $getProductComm->vproductAdminCommission / 100;
									
					//calculate store commission
					$storeCommission = 0;
					if ($getProductComm) {
						$storeCommission = $_POST['rechargeAmount'] * $getProductComm->commissionRate / 100;
					}
					
					$distCommission = $_POST['rechargeAmount'] * ($getProductComm->vproducttotalCommission - ($getProductComm->commissionRate + $getProductComm->vproductAdminCommission)) / 100;
										
					//insert to payment table
					$dataPayment = array(
						'customerID'			=> $this->session->userdata('userid'),
						'seqNo'					=> $this->payment_model->getSeqNo($this->session->userdata('userid')),
						'paymentMethodID'		=> 2,
						'chargedAmount'			=> $amount,
						'enteredBy'				=> 'Admin-admin',
						'chargedBy'				=> 'ADMIN SITE',
						'productID'				=> $getProductComm->vproductID,
						'agentID'				=> $_POST['store'],
						'storeCommission'		=> $storeCommission,
						'accountRepID'			=> $getStore->parentAgentID,
						'accountRepCommission'	=> $distCommission,
						'comment'				=> 'Topup Testing ',
						'createdDate'			=> date('Y-m-d H:i:s'),
						'adminCommission'		=> $rydCommission
					);
					$this->payment_model->add($dataPayment);
					
					$this->session->set_userdata('message', 'Topup Recharge successfull!');
					redirect('ppn/topup-recharge');
				} elseif (isset($purchaseRtr2->error)) {
					$this->data['warning'] = $purchaseRtr2->error;
				} else {
					$this->data['warning'] = 'ERROR: ';
				}
			}
			$this->data += $_POST; 
		}
		
		//getTopupProducts
		$rtrProducts = $this->admin_model->getVProducts(array('vproductCategory' => 'Rtr'));
		$products 				= array();
		$products[''] 			= '-- Select --';
		foreach ($rtrProducts as $item) {
			$products[$item->vproductSkuID] = $item->vproductName;
		}
		$this->data['products'] = $products;
		
		// Stores
		$getAllStores = $this->agent_model->getAllAgents();
		$allStore     = array();
		$allStore[''] = '-- Select --';
		foreach ($getAllStores as $item) {
			$allStore[$item->agentID] = $item->firstName.' '.$item->lastName;
		}
		$this->data['allStore'] 	    = $allStore;
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'ppn';
		$this->data['sub_current_page'] = 'topup_recharge';	
		$this->load->view('ppn/_ppn', $this->data);
	}
	// RTR END ================================
	
	// PIN ====================================
	public function topup_pin(){
		include 'PrepayNationWS.php';
		
		if(isset($_POST) && count($_POST) > 0){
			/*if (strlen(trim($_POST['carrierID'])) < 1) {
				$this->error['carrierID'] = 'Carrier is required!';
			}*/
			if (strlen(trim($_POST['product'])) < 1) {
				$this->error['product'] = 'Product is required!';
			}
			if (strlen(trim($_POST['rechargeAmount'])) < 1) {
				$this->error['rechargeAmount'] = 'Amount is required!';
			}			
			if (strlen(trim($_POST['rechargeNumber'])) < 1) {
				$this->error['rechargeNumber'] = 'Recipient Mobile is required!';
			}
			if (strlen(trim($_POST['store'])) < 1) {
				$this->error['store'] = 'Store is required!';
			}
									
			if(!$this->error){
				
				// getPPN Mode
				$getPPNMode     = $this->admin_model->getSettings(array('settingKey' => 'ppnMode'));
				$getPPNUsername = $this->admin_model->getSettings(array('settingKey' => 'ppnUsername'));
				$getPPNPassword = $this->admin_model->getSettings(array('settingKey' => 'ppnPassword'));			
				
				$username = $getPPNUsername->settingValue;
				$password = $getPPNPassword->settingValue;
				if($getPPNMode->settingValue=='test'){
					$sandbox = TRUE;
				}else{
					$sandbox = FALSE;
				}
				// getPPN Mode END			
				$api = new PrepaynationWS($username, $password, $sandbox);
				
				$purchasePinParam = array(
										'skuId' 		=> $_POST['product'],
										'quantity' 		=> '1',
										'corelationId' 	=> $_POST['rechargeNumber']
									);
						
				$purchasePin = $api->purchasePin($purchasePinParam);
				
				if (!isset($purchasePin->error)) {
					$getStore  		= $this->getStoreInfo($_POST['store']);
					$getProductComm = $this->agent_model->getCommissionByProductSKU( $_POST['store'],$sku=$_POST['product']);
					
					$phone  	 = $_POST['rechargeNumber'];
					$amount 	 = $purchasePin->invoice->faceValueAmount;
					$pinNumber   = isset($purchasePin->invoice->cards->card->pins->pin->pinNumber) ? $purchasePin->invoice->cards->card->pins->pin->pinNumber : '';
					$createdDate = date('Y-m-d H:i:s');
														
					// calculate RYD Commission
					$rydCommission = 0;	
					if($getProductComm)			
						$rydCommission = $_POST['rechargeAmount'] * $getProductComm->vproductAdminCommission / 100;
									
					//calculate store commission
					$storeCommission = 0;
					if ($getProductComm) {
						$storeCommission = $_POST['rechargeAmount'] * $getProductComm->commissionRate / 100;
					}
					
					$distCommission = $_POST['rechargeAmount'] * ($getProductComm->vproducttotalCommission - ($getProductComm->commissionRate + $getProductComm->vproductAdminCommission)) / 100;
										
					//insert to payment table
					$dataPayment = array(
						'customerID'			=> $this->session->userdata('userid'),
						'seqNo'					=> $this->payment_model->getSeqNo($this->session->userdata('userid')),
						'paymentMethodID'		=> 2,
						'chargedAmount'			=> $amount,
						'enteredBy'				=> 'Admin-admin',
						'chargedBy'				=> 'ADMIN SITE',
						'productID'				=> $getProductComm->vproductID,
						'agentID'				=> $_POST['store'],
						'storeCommission'		=> $storeCommission,
						'accountRepID'			=> $getStore->parentAgentID,
						'accountRepCommission'	=> $distCommission,
						'comment'				=> 'Topup Testing ',
						'createdDate'			=> date('Y-m-d H:i:s'),
						'adminCommission'		=> $rydCommission
					);
					$this->payment_model->add($dataPayment);
					
					$this->session->set_userdata('message', 'Topup Recharge successfull!');
					redirect('ppn/topup-pin');
				} elseif (isset($purchasePin->error)) {
					$this->data['warning'] = $purchasePin->error;
				} else {
					$this->data['warning'] = 'ERROR: ';
				}
			}
			$this->data += $_POST; 
		}
		
		//getTopupProducts
		$rtrProducts = $this->admin_model->getVProducts(array('vproductCategory' => 'Pin'));
		$products 				= array();
		$products[''] 			= '-- Select --';
		foreach ($rtrProducts as $item) {
			$products[$item->vproductSkuID] = $item->vproductName;
		}
		$this->data['products'] = $products;
		
		//Product Type
		$allProduct     = array();
		$allProduct[''] = '-- Select --';
		$products = $this->admin_model->getProducts();
		foreach ($products as $item) {
			$allProduct[$item->productID] = $item->productName;
		}
		$this->data['productType'] = $allProduct;
		
		// Stores
		$getAllStores = $this->agent_model->getAllAgents();
		$allStore     = array();
		$allStore[''] = '-- Select --';
		foreach ($getAllStores as $item) {
			$allStore[$item->agentID] = $item->firstName.' '.$item->lastName;
		}
		$this->data['allStore'] 	    = $allStore;
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'ppn';
		$this->data['sub_current_page'] = 'topup_pin';	
		$this->load->view('ppn/_ppn', $this->data);
	}
	// PIN END ================================
		
	public function get_sku_list(){
		include 'PrepayNationWS.php';
		// getPPN Mode
		$getPPNMode     = $this->admin_model->getSettings(array('settingKey' => 'ppnMode'));
		$getPPNUsername = $this->admin_model->getSettings(array('settingKey' => 'ppnUsername'));
		$getPPNPassword = $this->admin_model->getSettings(array('settingKey' => 'ppnPassword'));			
		
		$username = $getPPNUsername->settingValue;
		$password = $getPPNPassword->settingValue;
		if($getPPNMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
		// getPPN Mode END			
		$api = new PrepaynationWS($username, $password, $sandbox);	
		
		$result = $api->getSkuList();
		foreach($result as $item){
			if($item->category=='Rtr' || $item->category=='Pin'){
				 $data =  array('skuId' 			=>$item->skuId
					      ,'productCode' 			=>$item->productCode
					      ,'productName' 			=>$item->productName
					      ,'denomination' 			=>$item->denomination
					      ,'minAmount' 				=>$item->minAmount
					      ,'maxAmount' 				=>$item->maxAmount
					      ,'discount' 				=>$item->discount
					      ,'category' 				=>$item->category
					      ,'isSalesTaxCharged' 		=>$item->isSalesTaxCharged
					      ,'exchangeRate' 			=>$item->exchangeRate
					      ,'bonusAmount' 			=>$item->bonusAmount
					      ,'currencyCode' 			=>$item->currencyCode
					      ,'carrierName' 			=>$item->carrierName
					      ,'countryCode' 			=>$item->countryCode
					      ,'localPhoneNumberLength' =>$item->localPhoneNumberLength
					      ,'internationalCodes' 	=>strtok($item->internationalCodes, ' ')
					      ,'allowDecimal' 			=>$item->allowDecimal
					      );
				$this->admin_model->addPpnSku($data);
				//echo '<pre>';print_r($data);
			}
		}
	}
	
	// Admin Balance FROM PPN
	public function admin_balance(){
		include 'PrepayNationWS.php';
		
		// getPPN Mode
		$getPPNMode     = $this->admin_model->getSettings(array('settingKey' => 'ppnMode'));
		$getPPNUsername = $this->admin_model->getSettings(array('settingKey' => 'ppnUsername'));
		$getPPNPassword = $this->admin_model->getSettings(array('settingKey' => 'ppnPassword'));			
		
		$username = $getPPNUsername->settingValue;
		$password = $getPPNPassword->settingValue;
		if($getPPNMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
		// getPPN Mode END			
		$api = new PrepaynationWS($username, $password, $sandbox);	
		
		$result  	= $api->get_balance();
		
		$this->data['result'] 			= $result;
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'ppn';
		$this->data['sub_current_page'] = 'admin_balance';	
		$this->load->view('ppn/_ppn', $this->data);
	}
	public function get_carrier_products($carrierID){
		include 'PrepayNationWS.php';
		$products = 0;
				
		// getPPN Mode
		$getPPNMode     = $this->admin_model->getSettings(array('settingKey' => 'ppnMode'));
		$getPPNUsername = $this->admin_model->getSettings(array('settingKey' => 'ppnUsername'));
		$getPPNPassword = $this->admin_model->getSettings(array('settingKey' => 'ppnPassword'));			
		
		$username = $getPPNUsername->settingValue;
		$password = $getPPNPassword->settingValue;
		if($getPPNMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
		// getPPN Mode END			
		$api = new PrepaynationWS($username, $password, $sandbox);	
		
		$carrier = array( 'carrierId' => $carrierID );
		$result  = $api->getSkuListByCarrier( $carrier );
		$ret = '<option value="">-- Select --</option>';
		
		if($result){
			if (is_array($result)) {
				foreach($result as $item){
					$ret .= '<option value="'.$item->skuId.'">'.$item->productName.'</option>';
				}
			}else{
				$ret .= '<option value="'.$result->skuId.'">'.$result->productName.'</option>';
			}
		}
					
		echo $ret;
	}
	
	//Export Products =========================
	public function export_products(){
		include 'PrepayNationWS.php';
		
		// getPPN Mode
		$getPPNMode     = $this->admin_model->getSettings(array('settingKey' => 'ppnMode'));
		$getPPNUsername = $this->admin_model->getSettings(array('settingKey' => 'ppnUsername'));
		$getPPNPassword = $this->admin_model->getSettings(array('settingKey' => 'ppnPassword'));			
		
		$username = $getPPNUsername->settingValue;
		$password = $getPPNPassword->settingValue;
		if($getPPNMode->settingValue=='test'){
			$sandbox = TRUE;
		}else{
			$sandbox = FALSE;
		}
		// getPPN Mode END			
		$api = new PrepaynationWS($username, $password, $sandbox);	
		
		$result 	= $api->getSkuList();
		header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename=ppn_products_'.time().'.csv');
	    header('Pragma: no-cache');
	    header("Expires: 0");
	
	    $outstream = fopen("php://output", "w");
		$row = 1;		
		foreach($result as $item){
			if($row==1){
				fputcsv($outstream, array('skuId' 			=> 'SkuId'
							      ,'productCode' 			=> 'Product Code'
							      ,'productName' 			=> 'Product Name'
							      ,'denomination' 			=> 'Denomination'
							      ,'minAmount' 				=> 'Min Amount'
							      ,'maxAmount' 				=> 'Max Amount'
							      ,'discount' 				=> 'Discount'
							      ,'category' 				=> 'Category'
							      ,'isSalesTaxCharged' 		=> 'isSalesTaxCharged'
							      ,'exchangeRate' 			=> 'Exchange Rate'
							      ,'bonusAmount' 			=> 'Bonus Amount'
							      ,'currencyCode' 			=> 'Currency Code'
							      ,'carrierName' 			=> 'Carrier Name'
							      ,'countryCode' 			=> 'Country Code'
							      ,'localPhoneNumberLength' => 'localPhoneNumberLength'
							      ,'internationalCodes' 	=> 'International Codes'
							      ,'allowDecimal' 			=> 'Allow Decimal'
							      )
				);
			}
			if($item->category=='Rtr' || $item->category=='Pin'){
				 fputcsv($outstream, array('skuId' 			=>$item->skuId
							      ,'productCode' 			=>$item->productCode
							      ,'productName' 			=>$item->productName
							      ,'denomination' 			=>$item->denomination
							      ,'minAmount' 				=>$item->minAmount
							      ,'maxAmount' 				=>$item->maxAmount
							      ,'discount' 				=>$item->discount
							      ,'category' 				=>$item->category
							      ,'isSalesTaxCharged' 		=>$item->isSalesTaxCharged
							      ,'exchangeRate' 			=>$item->exchangeRate
							      ,'bonusAmount' 			=>$item->bonusAmount
							      ,'currencyCode' 			=>$item->currencyCode
							      ,'carrierName' 			=>$item->carrierName
							      ,'countryCode' 			=>$item->countryCode
							      ,'localPhoneNumberLength' =>$item->localPhoneNumberLength
							      ,'internationalCodes' 	=>strtok($item->internationalCodes, ' ')
							      ,'allowDecimal' 			=>$item->allowDecimal
							      )
						);
			}
			$row++;
		}	   		
	    fclose($outstream);
		exit();
	}
	
	// Private Function 
	private function get_carrier_list(){
		$ppnDetails  = $this->config->item('prepaynation');		
		$ppmApi 	 = new PrepaynationWS($ppnDetails['username'], $ppnDetails['password'], $ppnDetails['sandbox']);		
		$carrierList = $ppmApi->getCarrierList();
		
		return $carrierList; 
	}
	
	private function getStoreInfo($id) {
		//time_period array
		$this->data['option_time_period'] = array('today' => 'Today', 'yesterday' => 'Yesterday', 'month' => 'This Month', 'year' => 'Year to Date');
		
		//agent info
		$info = $this->agent_model->getAgent((int)$id);
		if (!is_numeric($id)|| !$info) {
			show_error('Store you requested was not found.', 404, 'Store is not found!');
		}
				
		$this->data['agentID'] = $id;
		$this->data['info'] = $info;
		
		return $info;
	}
	
	
}
?>