<?php
ini_set("display_errors", "1");
    error_reporting(E_ALL);
class Agent extends CI_Controller {
	
	private $error = array();
	private $data = array();
	
	function __construct() {
		parent::__construct();
		$this->roleuser->checkLogin();
		if ($this->session->userdata('usertype') != 'admin' && $this->session->userdata('usertype') != 'sub-admin' && $this->session->userdata('usertype') != 'super-admin') {
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
    
	// Store ===============================================================================================
	public function add_new_agent() {
		if (isset($_POST) && count($_POST) > 0) {
			
			//process submit
			if ($this->validateAgentFrom($_POST)) {
				$phoneEmail = $this->getPhoneEmail(trim( preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone'])) ));	
				
				if(!$this->error){
					$createdDate = date('Y-m-d H:i:s');
					$authProfile = $this->getAuthProfile($_POST['email']);
					$data = array(
						'company_name'		=> trim($_POST['storeName']),
						'firstName'			=> trim($_POST['firstName']),
						'lastName'			=> trim($_POST['lastName']),
						'phone'				=> trim(  preg_replace('/[^0-9]+/','', strip_tags($_POST['phone'])) ),
						'cellPhone'			=> trim(  preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone'])) ),
						'address'			=> trim($_POST['address']),
						'city'				=> trim($_POST['city']),
						'zipCode'			=> trim($_POST['zipCode']),
						'stateID'			=> trim($_POST['state']),
						'email'				=> trim($_POST['email']),
						'note'				=> trim($_POST['note']),
						'loginID'			=> trim($_POST['agentLoginID']),
						'password'			=> trim(md5($_POST['agentPassword'])),
						'securityCode'		=> (string)time(),
						'agentTypeID'		=> 2,
						'parentAgentID'		=> $_POST['distributor'],
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
					
					// Report 
					$this->adminReport('Added Store '.$_POST['storeName']);
					
					$this->session->set_userdata('message', 'You have added new store successfully!');
					redirect('store/commission/'.$agentID);
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
		for ($i=1;$i<=$maxCommission;$i++) {
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
		
		//Account Distributor
		$option_distributor = array();
		$option_distributor['0'] = '-- Select --';
		$account_reps = $this->agent_model->getAllAgents(1, $subDistributor=4);
		foreach ($account_reps as $item) {
			$option_distributor[$item->agentID] = $item->firstName.' '.$item->lastName;
		}
		$this->data['option_distributor'] = $option_distributor;
		
		$this->data['current_page'] = 'agent';  
		$this->load->view('add_new_agent', $this->data);
	}
    public function agent_account_manager() {
		if (isset($_POST) && count($_POST) > 0) { 
			$where = array();
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
			$results = $this->agent_model->getAgents($where, 2, null, $this->config->item('num_per_page'), $offset);
			$this->data[$_POST['search_key']] = $_POST['search_value'];
			$this->data['results'] = $results;
			
			$this->data += $_POST;
		}
		
		$this->data['current_page'] = 'agent';
		$this->load->view('agent_account_manager', $this->data);
	}
	public function delete() {
		
		$info = $this->getAgentInfo($_POST['id']);
		$data = array('success'=>0,'text'=>'Please try again!');
		if ($this->agent_model->delete($_POST['id'])) {
			
			// Delete Payment Records ======
			$this->payment_model->deleteAgentPaymentByAgentID(array('agentID' => $_POST['id']));
			
			// Delete Commission Records ======
			$this->agent_model->deleteCommissionByAgent(array('agentID' => $_POST['id']));
			
			// delete authorize.net Profile
			if($info->authProfile){
				$this->deleteAuthProfile($info->authProfile);
				$this->payment_model->deletePaymentProfile(array('agentID' => $_POST['id']));
			}
			
			// Delete Customers=====
			$this->customer_model->deleteCustomersByAgent(array('agentID' => $_POST['id']));
			
			// Report 
			$this->adminReport('Deleted Store '.$info->company_name);
				
			$data = array('success'=>1,'text'=>'You have deleted store successfully!');
		}
		echo json_encode($data);
	}
	public function profile($agentID) {
			
		$info = $this->getAgentInfo($agentID);
		
		if (!empty($_POST) && count($_POST) > 0) {
			
			//process submit			
			if ($this->validateAgentFrom($_POST, $agentID)) {
				$data = array(
					'company_name'		=> trim($_POST['storeName']),
					'firstName'			=> trim($_POST['firstName']),
					'lastName'			=> trim($_POST['lastName']),
					'phone'				=> trim($_POST['phone']),
					//'cellPhone'			=> trim($_POST['cellphone']),
					'address'			=> trim($_POST['address']),
					'city'				=> trim($_POST['city']),
					'zipCode'			=> trim($_POST['zipCode']),
					'stateID'			=> trim($_POST['state']),
					'email'				=> trim($_POST['email']),
					//'password'			=> md5($_POST['password']),
					'parentAgentID'		=> $_POST['distributor'],
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
				
					// Report 
					$this->adminReport('Updated Store '.$_POST['storeName'].' Profile');
				
					//redirect to list page
					$this->session->set_userdata('message', 'You have updated store profile successfully!');
					redirect(site_url('store/profile/'.$agentID));	
				}				
			}
            
			$this->data += $_POST;
		} else {
			$this->data['storeName']		= $info->company_name;
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
			$this->data['commissionRate'] 	= $info->commissionRate;
			$this->data['distributor'] 		= $info->parentAgentID;
			$this->data['note'] 			= $info->note;
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
		
		//Account Distributor
		$option_distributor = array();
		//echo "gddfg";die;
		$account_dist = $this->agent_model->getAllAgents(1, $subDistributor=4);
		foreach ($account_dist as $item) {
			$option_distributor[$item->agentID] = $item->firstName.' '.$item->lastName;
		}
		$this->data['option_account_rep'] = $option_distributor;
		
		$this->data['current_page'] = 'agent';
		$this->data['sub_current_page'] = 'profile';
		$this->load->view('store/_store', $this->data);
	}
	public function commission_agent($agentID, $productType='') {
		$info = $this->getAgentInfo($agentID);
		
		$page = 1;
		if(isset($_POST['page']) && $_POST['page'] > 1){
			$page = $_POST['page'];
		}
		
		if (isset($_POST) && count($_POST) > 0 && !isset($_POST['page']) ) {
			//validate form
			if (!$this->error) {
				//process submit
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
						$this->session->set_userdata('message', 'You have updated commission successfully!');
						
						// Report 
						$this->adminReport('Updated store commission for '.$info->company_name);
					}else {
						$this->agent_model->addCommission($data);
						$this->session->set_userdata('message', 'You have added commission successfully!');
						
						// Report 
						$this->adminReport('Added store commission for '.$info->company_name);
					}					
				}
					
				redirect(site_url('store/commission/'.$agentID.'/'.$productType));
			}
			$this->data += $_POST;
		}
		//assign error
		$this->data['error'] = $this->error;
		
		//assign list commissionRateHistory
		$this->data['results'] = $this->agent_model->getCommissions($agentID);
		
		//$results  = $this->agent_model->getCommissions($agentID);
		//echo '<pre>';print_r($results);die;
		
		// All Products
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
		
		$limit='';
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
			//$getAllProducts = $this->admin_model->getAllProductsByAgent($info->parentAgentID, array('vp.vproductCategory' => $productCategory));
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
		
		//product
		$this->load->model('admin_model');  
		$option_product = array();
		$option_product[''] = '-- Select --';
		$products = $this->admin_model->getProducts();
		foreach ($products as $item) {
			$option_product[$item->productID] = $item->productName;
		}
		$this->data['option_product'] = $option_product;
		
		//option_max_store_commission
		$max_store_commission_array = array();
		$max_store_commission_array['0'] = '-- Select --';
		$this->data['option_max_store_commission'] = $max_store_commission_array;
		
		$this->data['current_page'] = 'agent';
		$this->data['sub_current_page'] = 'commission';
		$this->load->view('store/_store', $this->data);
	}
	public function commission_delete($agentID) {
		$info = $this->getAgentInfo($agentID);
		$data = array('success'=>0,'text'=>'Delete fail! Please try again!');
		$this->agent_model->deleteCommission($_POST['id']);
		$this->session->set_userdata('message', 'You have deleted commission successfully!');		
		// Report 
		$this->adminReport('Deleted store commission for '.$info->company_name);
		
		$data = array('success'=>1);
		echo json_encode($data);
	}
	// Store END ============================================================================================
	
	//Distributors ===========================================================================================
	public function add_new_destributor() {
		if (isset($_POST) && count($_POST) > 0) {
			//process submit
			if ($this->validateRepFrom($_POST)) {
				$phoneEmail = $this->getPhoneEmail(trim($_POST['cellphone']));	
				
				if(!$this->error){					
					$createdDate = date('Y-m-d H:i:s');
					$authProfile = $this->getAuthProfile($_POST['email']);
					
					$data = array(
						'company_name'		=> trim($_POST['company_name']),
						'firstName'			=> trim($_POST['firstName']),
						'lastName'			=> trim($_POST['lastName']),
						'phone'				=> trim($_POST['phone']),
						'cellPhone'			=> trim($_POST['cellphone']),
						'email'				=> trim($_POST['email']),
						'address'			=> trim($_POST['address']),
						'city'				=> trim($_POST['city']),
						'stateID'			=> trim($_POST['state']),
						'zipCode'			=> trim($_POST['zip']),
						'address2'			=> trim($_POST['address2']),
						'loginID'			=> trim($_POST['agentLoginID']),
						'password'			=> trim(md5($_POST['agentPassword'])),
						'securityCode'		=> (string)time(),
						'agentTypeID'		=> 1,
						'parentAgentID'		=> 0,
						'status'			=> 1,
						'createdDate'		=> $createdDate,
						'authProfile'		=> $authProfile?$authProfile:'',
						'phoneEmailID'		=> addslashes($phoneEmail)
					);
					$agentID = $this->agent_model->add($data);
					
					// Text Send to Distributor's cell number
					$textMessage = "Congrats! You have joined vonecall.com as a distributor";
					$subject	 = "New Distributor on Vonecall";
					$this->sendText($textMessage, $subject, $phoneEmail);
					
					// Report 
					$this->adminReport('Added distributor '.$_POST['firstName']);
			
					$this->session->set_userdata('message', 'You have added new distributor successfully!');
					redirect(site_url('destributor/commission/'.$agentID));
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
		
		$this->data['current_page'] = 'rep';  
		$this->load->view('add_new_account_rep', $this->data);
	}
    public function destributor_manager() {
		if (isset($_POST) && count($_POST)>0) {
			$where = array();
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
				$results = $this->agent_model->getAgents($where, 1, 4);
				print $this->load->view('agent_account_manager_excel', array('results'=>$results), true); die; 
			}
			//end export excel
			//echo "gdfgdfg";die;
			$paging_data = array(
				'limit' => $this->config->item('num_per_page'),
				'current' => $_POST['page'] > 1 ? $_POST['page'] : 1,
				'total' => $this->agent_model->getTotalAgents($where, 1, 4)
			);
			$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
			$offset 			  = ($paging_data['current'] - 1) * $this->config->item('num_per_page');
			$results 			  = $this->agent_model->getAgents($where, 1, 4, $this->config->item('num_per_page'), $offset);
			$this->data[$_POST['search_key']] = $_POST['search_value'];
			$mergeResult = array();
			foreach($results as $repID){
				$totalSubAgent = $this->agent_model->getTotalAgents(array('parentAgentID'=>$repID->agentID), 2);
				$totalSubDist  = $this->agent_model->getTotalAgents(array('parentAgentID'=>$repID->agentID), 4);
				$repID->totalAgents  = $totalSubAgent;
				$repID->totalSubdist = $totalSubDist;
				$mergeResult[] = $repID;
			}
			//die;
			$this->data['results'] = $mergeResult;
			
			$this->data += $_POST;
		}
		//echo '<pre>';
		//echo 'gdgfdfgfd';die;
		
		//print_r($this->data);die;
		$this->data['current_page'] = 'rep';
		$this->load->view('account_rep_manager', $this->data);
	}
	public function destributor_profile($agentID) {
		$info = $this->getAgentInfo($agentID);
			
		if (isset($_POST) && count($_POST) > 0) {
			//process submit
			if ($this->validateRepFrom($_POST, $agentID)) {
				$data = array(
					'company_name'	=> trim($_POST['company_name']),
					'firstName'		=> trim($_POST['firstName']),
					'lastName'		=> trim($_POST['lastName']),
					'email'			=> trim($_POST['email']),
					'phone'			=> trim($_POST['phone']),
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
					$data = array_merge($data, array('cellPhone' => trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))) ));
				}else{;
					$phoneEmail = $this->getPhoneEmail(trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))));
					$data = array_merge($data, array('cellPhone' => trim(preg_replace('/[^0-9]+/','', strip_tags($_POST['cellphone']))), 'phoneEmailID' => addslashes($phoneEmail) ));
				}	
				
				if(!$this->error){
					$this->agent_model->update($agentID, $data);
				
					// Report 
					$this->adminReport('Updated distributor '.$_POST['firstName'].'`s Profile');
								
					//redirect to list page
					$this->session->set_userdata('message', 'You have updated profile successfully!');
					redirect(site_url('destributor/profile/'.$agentID));	
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
		
		$this->data['current_page'] 	= 'rep';
		$this->data['sub_current_page'] = 'profile';
		$this->load->view('distributor/_distributor', $this->data);
	}
	public function delete_destributor() {
		$info = $this->getAgentInfo($_POST['id']);
		$data = array('success'=>0,'text'=>'Please try again!');
		$totalSubAgent = $this->agent_model->getTotalAgents(array('parentAgentID'=>$_POST['id']), 2);
		$totalSubDist  = $this->agent_model->getTotalAgents(array('parentAgentID'=>$_POST['id']), 4);
		
		if ($totalSubAgent > 0 || $totalSubDist > 0) {
			$data = array('success'=>0,'text'=>'Error: This Distributor has respect to the Store OR Sub Distributor, Please remove Store/Sub-distributor out of this distributor before!');
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
	public function destributor_status(){
		$data = array('success'=>0,'text'=>'Please try again!');
		$info = $this->getAgentInfo($_POST['id']);
		
		if($info->status==1){ 
			$status = 0;
			$statusText ='Disable';
		}else {
			$status = 1;
			$statusText ='Enable';
		}
		if ($this->agent_model->update($_POST['id'], array('status' => $status))) {
			// Report 
			$this->adminReport($statusText.' distributor '.$info->firstName);
			
			$data = array('success'=>1,'text'=>'Distributor status successfully updated!');
		}
		echo json_encode($data);
	}
	public function destributor_commission($agentID) {
		$info = $this->getAgentInfo($agentID);
			
		if (isset($_POST) && count($_POST) > 0) {
			//validate form
			$id = strlen($_POST['edit'])>0?$_POST['edit']:0;
			if (strlen($_POST['product']) < 1) {
				$this->error['product'] = 'Product Name is required!';
			} elseif ($this->agent_model->checkCommissionExist($agentID, $_POST['product'], $id)) {
				$this->error['product'] = 'Commission of this product is already existed!';
			}
			if (!$this->error) {
				//process submit
				$commissionRate = trim($_POST['commissionRate'])!=''?trim($_POST['commissionRate']):0;
				$data = array(
					'agentID'			=> $agentID,
					'productID'			=> trim($_POST['product']),
					'commissionRate'	=> trim($_POST['commissionRate'])!=''?trim($_POST['commissionRate']):0,
					'maxStoreCommission'=> trim($_POST['maxStoreCommission'])!=''?trim($_POST['maxStoreCommission']):0,
					'enteredBy'			=> $this->session->userdata('username').' - '.$this->session->userdata('usertype'),
					'note'				=> trim($_POST['note']),
					'createdDate'		=> date('Y-m-d H:i:s')
				);
				if ($id) {
					$this->agent_model->updateCommission($id, $data);
					$this->session->set_userdata('message', 'You have updated commission successfully!');
					
					// Report 
					$this->adminReport('Updated distributor '.$info->firstName.'`s commission');
				} else {
					$this->agent_model->addCommission($data);
					$this->session->set_userdata('message', 'You have added commission successfully!');
					
					// Report 
					$this->adminReport('Added distributor '.$info->firstName.'`s commission');
				}
				
				redirect(site_url('destributor/commission/'.$agentID));
			}
			$this->data += $_POST;
		}
		//assign error
		$this->data['error'] = $this->error;
		
		//assign list commissionRateHistory
		$this->data['results'] = $this->agent_model->getCommissions($agentID);
		
		//product
		$this->load->model('admin_model');  
		$option_product = array();
		$option_product[''] = '-- Select --';
		$products = $this->admin_model->getProducts();
		foreach ($products as $item) {
			$option_product[$item->productID] = $item->productName;
		}
		$this->data['option_product'] = $option_product;
		
		//option_max_store_commission
		$max_store_commission_array = array();
		$max_store_commission_array[''] = '-- Select --';
		$maxCommission = $this->config->item('max_commission');
		for ($i=1;$i<=$maxCommission;$i++) {
			$max_store_commission_array[$i] = $i.'%';
		}
		$this->data['option_max_store_commission'] = $max_store_commission_array;
		
		$this->data['current_page'] = 'rep';
		$this->data['sub_current_page'] = 'commission';
		$this->load->view('distributor/_distributor', $this->data);
	}
	public function account_rep_commission_delete($agentID) {
		$info = $this->getAgentInfo($agentID);		
		$data = array('success'=>0,'text'=>'Delete fail! Please try again!');
		$this->agent_model->deleteCommission($_POST['id']);
		$this->session->set_userdata('message', 'You have deleted commission successfully!');
		// Report 
		$this->adminReport('Deleted distributor '.$info->firstName);
		$data = array('success'=>1);		
		echo json_encode($data);
	}
	
	//Distributors END =======================================================================================
	
	private function validateAgentFrom($data, $agentID=0) {
		if (strlen(trim($data['storeName'])) < 1) {
			$this->error['storeName'] = 'Store name is required!';
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
		if (strlen(trim($data['phone'])) > 0 && !is_numeric(trim(  preg_replace('/[^0-9]+/','', strip_tags($data['phone']))  ))) {
			$this->error['phone'] = 'Invalid phone number!';
		}
		if (strlen(trim($data['cellphone'])) > 0 && !is_numeric(  preg_replace('/[^0-9]+/','', strip_tags($data['cellphone']))  )) {
			$this->error['cellphone'] = 'Invalid cell phone number!';
		}
		if (strlen(trim($data['email'])) < 1) {
			$this->error['email'] = 'Your Email is required!';
		} elseif (!$this->form_validation->valid_email($data['email'])) {
			$this->error['email'] = 'Your Email is invalid!';
		} elseif ($this->agent_model->checkAgentEmailExist($data['email'], $agentID)) {
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
	private function validateRepFrom($data, $agentID=0) {
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
		} elseif (strlen(trim($data['phone'])) > 0 && !is_numeric(trim(  preg_replace('/[^0-9]+/','', strip_tags($data['phone']))  ))) {
			$this->error['phone'] = 'Invalid phone number!';
		}

		if (strlen(trim($data['cellphone'])) < 1) {
			$this->error['cellphone'] = 'Cellphone is required!';
		} elseif (strlen(trim($data['cellphone'])) > 0 && !is_numeric(trim(  preg_replace('/[^0-9]+/','', strip_tags($data['cellphone']))  ))) {
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
	private function getAgentInfo($id) {
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
	private function getParentAgentInfo($id){
		//agent info
		$info = $this->agent_model->getAgent((int)$id);
		$this->data['info'] = $info;
		
		return $info;
	}
	
	public function get_max_store_commission($agentID=0, $productID=0) {
		$max = 0;
		$min = 0;		
		//$commission = $this->agent_model->getCommission((int)$agentID, (int)$productID);
		$commission = $this->admin_model->getProducts(array('productID'=> (int)$productID));
		
		if ($commission) {
			$max = $commission[0]->maxStoreCommission;
			$min = $commission[0]->minStoreCommission;
		}
		$ret = '<option value="">-- Select --</option>';
		for ($i=$min;$i<=$max;$i++) {
			$ret .= '<option value="'.$i.'">'.$i.'%</option>';
		}		
		echo $ret;
	}

	// Admin Report ===================================================================
	private function adminReport($actionMessage){
		$adminID = $this->session->userdata('userid');
		$reportData = array( 'action' => $actionMessage, 'adminID' => $adminID, 'date_time' => date('Y-m-d H:i:s') );
		return $this->admin_model->addReport($reportData);
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