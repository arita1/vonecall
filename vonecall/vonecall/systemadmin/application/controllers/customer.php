<?php
class Customer extends CI_Controller {
	
	private $error = array();
	private $data = array();
	
	function __construct() {
		parent::__construct();
		$this->roleuser->checkLogin();
		$this->load->helper(array('form','format'));
		$this->load->library('form_validation');
		$this->load->model('customer_model');  
		$this->load->model('payment_model');  
		$this->load->model('agent_model');
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		
		//assign message
		if ($this->session->userdata('message')) {
			$this->data['message'] = $this->session->userdata('message');
			$this->session->unset_userdata('message');
		}
	}
	public function index() {
		redirect(site_url('customer-account-manager'));
	}
		
	public function customer_account_manager() {
		if (isset($_POST) && count($_POST) > 0 ) {
			$where = array();
			
			switch ($_POST['search_key']) {
				case "search_by_product":
					$where['c.customerProduct'] = $_POST['search_value'];
					break;
				case "search_by_store":
					$where['c.agentID'] = $_POST['search_value'];
					break;
				case "search_by_customer_phone_number":
					$where['c.phone'] = $_POST['search_value'];
					break;
			}
			$total = $this->customer_model->getTotalCustomers($where);
			
			//start export excel
			if ($_POST['excel'] == 1) {
				header("Content-type: application/x-msdownload");
				header("Content-Disposition: attachment; filename=".date('Ymd')."_customer_account.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$results = $this->customer_model->getCustomers($where, $total, 0);
				print $this->load->view('customer_account_manager_excel', array('results'=>$results), true); die; 
			}
			//end export excel
			
			$paging_data = array(
				'limit' => $this->config->item('num_per_page'),
				'current' => $_POST['page'] > 1 ? $_POST['page'] : 1,
				'total' => $total
			);
			$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
			$offset = ($paging_data['current'] - 1) * $this->config->item('num_per_page');
			$results = $this->customer_model->getCustomers($where, $this->config->item('num_per_page'), $offset);//print_r(count($results));die;
			$this->data += $_POST;
			$this->data[$_POST['search_key']] = $_POST['search_value'];
			$this->data['results'] = $results;
		}
		
		//state
		$option_product = array();
		$option_product[''] = '-- Select --';
		$products = array('rtr' => 'Topup RTR', 'pin' => 'Topup PIN', 'pinless' => 'Pinless');
		foreach ($products as $key => $value) {
			$option_product[$key] = $value;
		}
		$this->data['option_product'] = $option_product;
		
		//Stores ======================
		$option_store = array();
		$option_store[''] = '-- Select --';
		$stores = $this->agent_model->getAllAgents(2);
		foreach ($stores as $item) {
			$option_store[$item->agentID] = $item->loginID;
		}
		$this->data['option_store'] = $option_store;
		
		$this->data['current_page'] = 'admin';
		$this->load->view('customer_account_manager', $this->data);
	}
	
	public function customer_delete(){
		$data = array('success'=>0,'text'=>'Please try again!');
		if ($this->customer_model->deleteCustomer($_POST['id'])) {
			$data = array('success'=>1,'text'=>'You have deleted customer successfully!');
		}
		echo json_encode($data);
	}
	
	public function profile($customerID) {
		$info = $this->getCustomerInfo($customerID);
		
		// get balance by PHONE (API)
		$getDetailsByani =  $this->getpinbyani($info->phone);
		$this->data['current_balance'] = $getDetailsByani->Balance;
		
		if (isset($_POST) && count($_POST) > 0) {
			//process submit
			if ($this->validateFormAddNewCustomer($_POST, $customerID)) {
				$data = array(
					'alertID'			=> 1,//trim($_POST['alert']),
					'firstName'			=> trim($_POST['firstName']),
					'lastName'			=> trim($_POST['lastName']),
					'phone'				=> trim($_POST['phone1']).trim($_POST['phone2']).trim($_POST['phone3']),
					'fax'				=> trim($_POST['fax']),
					'email'				=> trim($_POST['email']),
					'password'			=> trim($_POST['password']),
					'address'			=> trim($_POST['address']),
					'city'				=> trim($_POST['city']),
					'zipCode'			=> trim($_POST['zipCode']),
					'stateID'			=> trim($_POST['state']),
					'statementAddress'	=> trim($_POST['statementAddress']),
					'statementCity'		=> trim($_POST['statementCity']),
					'statementZipCode'	=> trim($_POST['statementZipCode']),
					'statementStateID'	=> trim($_POST['statementState'])
				);
				$this->customer_model->update($customerID, $data);
				
				//redirect to list page
				redirect(site_url('customer/recharge-account/'.$customerID));
			}
			$this->data += $_POST;
		} else {
			$this->data['alert']			= $info->alertID;
			$this->data['firstName']		= $info->firstName;
			$this->data['lastName'] 		= $info->lastName;
			$this->data['fax'] 				= $info->fax;
			$this->data['address'] 			= $info->address;
			$this->data['city'] 			= $info->city;
			$this->data['zipCode'] 			= $info->zipCode;
			$this->data['state'] 			= $info->stateID;
			$this->data['statementAddress'] = $info->statementAddress;
			$this->data['statementCity'] 	= $info->statementCity;
			$this->data['statementZipCode'] = $info->statementZipCode;
			$this->data['statementState'] 	= $info->statementStateID;
			$this->data['phone1'] 			= substr($info->phone,0,3);
			$this->data['phone2'] 			= substr($info->phone,3,3);
			$this->data['phone3'] 			= substr($info->phone,6,4);
			$this->data['email'] 			= $info->email;
			$this->data['loginID'] 			= $info->loginID;
			$this->data['password'] 		= $info->password;
			$this->data['passwordConfirm'] 	= $info->password;
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
		
		//agents
		$option_agent = array();
		$option_agent[''] = '-- Select --';
		$agents = $this->agent_model->getAllAgents(2);
		foreach ($agents as $item) {
			$option_agent[$item->agentID] = $item->loginID;
		}
		$this->data['option_agent'] = $option_agent;
		
		//alert
		$this->data['option_alert'] = array(''=>'-- Select --', '1'=>'Regular', '2'=>'VIP', '3'=>'Fraud', '4'=>'Closed Account');
		
		$this->data['current_page'] = 'customer_account_manager';
		$this->data['sub_current_page'] = 'profile';
		$this->load->view('customer/_customer', $this->data);
	}
	
	// Private Functions Start ================================================================
	private function validateFormAddNewCustomer($data, $customerID='') {
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
		if (!is_numeric($data['phone1']) || strlen($data['phone1']) != 3 ||
			!is_numeric($data['phone2']) || strlen($data['phone2']) != 3 ||
			!is_numeric($data['phone3']) || strlen($data['phone3']) != 4) {
			$this->error['phone'] = 'Invalid phone number!';
		} elseif ($this->customer_model->checkPhoneNumberExist($data['phone1'].$data['phone2'].$data['phone3'], $customerID)) {
			$this->error['phone'] = 'Phone number is aready existed!';
		}
		if (strlen(trim($data['fax'])) > 0 && !is_numeric($data['fax'])) {
			$this->error['fax'] = 'Invalid fax number!';
		}
		if (strlen(trim($data['email'])) > 0 && !$this->form_validation->valid_email($data['email'])) {
			$this->error['email'] = 'Your Email is invalid!';
		}
		if (strlen(trim($data['state'])) < 1) {
			//$this->error['state'] = 'State is required!';
		}
		//profile
		if ($customerID) {
			if (strlen(trim($data['password'])) < 1) {
				$this->error['password'] = 'Password is required!';
			}
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
		
	private function getCustomerInfo($id) {
		//customer info
		$info = $this->customer_model->getCustomer((int)$id);
		if (!is_numeric($id)|| !$info) {
			show_error('Customer you requested was not found.', 404, 'Customer not found!');
		}
		$this->data['customerID'] = $id;
		$this->data['info'] = $info;
		
		return $info;
	}
	
	private function getIP() {
		//get current client ip
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
}
?>