<?php
class Testing extends CI_Controller {
	
	private $data = array();
	private $error = array();
	
	function __construct() {
		parent::__construct();
		$this->roleuser->checkLogin();
		if ($this->session->userdata('usertype') != 'admin' && $this->session->userdata('usertype') != 'sub-admin') {
			show_error('You are not allowed to access this page.', 404, 'Access Denied!');
		}
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
	}
	public function test_commission() {
		
		if(isset($_POST) && count($_POST) > 0){
			if (strlen($_POST['product']) < 1) {
				$this->error['product'] = 'Product required!';
			} 
			if (strlen(trim($_POST['store'])) < 1) {
				$this->error['store'] = 'Store required!';
			} 
			if (strlen(trim($_POST['amount'])) < 1) {
				$this->error['amount'] = 'Amount required!';
			} elseif (strlen(trim($_POST['amount'])) > 0 && !is_numeric(trim($_POST['amount']))) {
				$this->error['amount'] = 'Amount is invalid!';
			}
			
			if(!$this->error){
				$getStore   	= $this->getStoreInfo($_POST['store']);
				$getProductComm = $this->agent_model->getCommissionByProduct( array('ac.agentID' => $_POST['store'], 'vp.vproductID' => $_POST['product']) );
								
				// calculate RYD Commission
				$rydCommission = 0;	
				if($getProductComm)			
					$rydCommission = $_POST['amount'] * $getProductComm->vproductAdminCommission / 100;
								
				//calculate store commission
				$storeCommission = 0;
				if ($getProductComm) {
					$storeCommission = $_POST['amount'] * $getProductComm->commissionRate / 100;
				}
				
				//Calculate Distributor Commission
				$distCommission = $_POST['amount'] * ($getProductComm->vproducttotalCommission - ($getProductComm->commissionRate + $getProductComm->vproductAdminCommission)) / 100;
					
				//insert to payment table
				$dataPayment = array(
					'customerID'			=> $this->session->userdata('userid'),
					'seqNo'					=> $this->payment_model->getSeqNo($this->session->userdata('userid')),
					'paymentMethodID'		=> 2,
					'chargedAmount'			=> $_POST['amount'],
					'enteredBy'				=> 'Admin-admin',
					'chargedBy'				=> 'ADMIN SITE',
					'productID'				=> $_POST['product'],
					'agentID'				=> $_POST['store'],
					'storeCommission'		=> $storeCommission,
					'accountRepID'			=> $getStore->parentAgentID,
					'accountRepCommission'	=> $distCommission,
					'comment'				=> 'Commission Testing ',
					'createdDate'			=> date('Y-m-d H:i:s'),
					'adminCommission'		=> $rydCommission
				);
				$this->payment_model->add($dataPayment);
				
				$this->session->set_userdata('message', 'Commission added successfully!');
				redirect('test-commission');
			}
			$this->data += $_POST;
		}
		
		//Product List
		$product_list = array();
		$product_list[''] = '-- Select --';
		$productList = $this->admin_model->getProducts();
		foreach ($productList as $item) {
			$product_list[$item->productID] = $item->productName;
		}
		$this->data['product_list'] = $product_list;
		
		//Products
		$allProduct     = array();
		$allProduct[''] = '-- Select --';
		$this->data['allProduct'] = $allProduct;
		
		// Stores
		$getAllStores = $this->agent_model->getAllAgents();
		$allStore     = array();
		$allStore[''] = '-- Select --';
		foreach ($getAllStores as $item) {
			$allStore[$item->agentID] = $item->firstName.' '.$item->lastName;
		}
		$this->data['allStore'] 	 = $allStore;
		$this->data['error']		 = $this->error;		
		$this->data['current_page']  = 'testing';  
		$this->load->view('testing/test_commission', $this->data);
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

	
	// Release authorize.net profiles
	public function removeProfile(){
		require(APPPATH.'libraries/anet_php_sdk/AuthorizeNet.php');
				
		$sandBox = false;
		if($sandBox){
			$transaction = new AuthorizeNetCIM('4954QwYygE', '5r6k3HTA95ELy5rh');
			$transaction->setSandbox($sandBox);
		}else{
			$transaction = new AuthorizeNetCIM('5rZ65cQM','6jB4W6RR422X6be7');
			$transaction->setSandbox($sandBox);
		}	
		
		$email3 = 'ex@email.com';
		$email4 = 'spcommun@gmail.com';
		$email5 = 'ashir22@hotmail.com';
		$email6 = 'africaan5@gmail.com';
		$email8 = 'sheekeeye@gmail.com';
		$email9 = 'rush.hassan@gmail.com';
		$email10 = 'abdi.hassan@rydtechnologies.com';
		$email11 = 'e@email.com';
		$email14 = 'AFRICAAN5@GMAIL.COM';
		
		//$newProfile  = $transaction->createCustomerProfile(array('email' => $email14));
		//echo '<pre>';print_r($newProfile);die;	
		
		$deteleProfile = $transaction->deleteCustomerProfile(131594042);
		echo '<pre>';print_r($deteleProfile);die;		
		
	}
}
?>