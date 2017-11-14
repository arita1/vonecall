<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
class Admin extends CI_Controller {
	
	private $error = array();
	private $data = array();

	private $agentID = 0;
	function __construct() {
		parent::__construct();
		$this->roleuser->checkLogin();
		if ($this->session->userdata('usertype') != 'admin'  && $this->session->userdata('usertype') != 'sub-admin' && $this->session->userdata('usertype') != 'super-admin') {
			show_error('You are not allowed to access this page.', 404, 'Access Denied!');
		}
		$this->load->helper(array('form','format'));
		$this->load->library('form_validation');
		$this->load->model('admin_model'); 
			$this->load->model('customer_model');  
		$this->load->model('payment_model');
		$this->load->model('agent_model');  
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		
		if ($this->session->userdata('message')) {
			$this->data['message'] = $this->session->userdata('message');
			$this->session->unset_userdata('message');
		}
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
		$this->data['current_page'] = 'admin_password';
		$this->load->view('admin_change_password', $this->data);
	}
	public function manage_admin() {
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
			
			//asign results
			$this->data['results'] = $results;
		} 
		
		$paging_data = array(
			'limit' => $this->config->item('num_per_page'),
			'current' => $current,
			'total' => $total
		);
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
		
		//Admin Types
		$option_admin_type = array();
		$option_admin_type[''] = '-- Select --';
		$admin_types = $this->admin_model->getAdminTypes();
		foreach ($admin_types as $item) {
			$option_admin_type[$item->adminTypeID] = $item->adminTypeName;
		}
		$this->data['option_admin_type'] = $option_admin_type;
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] = 'admin';
		$this->load->view('manage_admins', $this->data);
	}
	public function add_admin(){
		
		if (isset($_POST) && count($_POST) > 0) {
			if ($this->validateForm($_POST)) {
				//add admin				
				$data = array(
					'adminTypeID'	=> $_POST['adminType'],
					'firstName'		=> $_POST['firstName'],
					'lastName'		=> $_POST['lastName'],
					'phone'			=> $_POST['phone'],
					'cell_phone'	=> $_POST['cell_phone'],
					'email'			=> $_POST['email'],
					'username'		=> $_POST['username'],
					'password'		=> md5($_POST['password']),
					'createdDate'	=> date('Y-m-d H:i:s'),
					'adminStatus'	=> 1
				);
				$adminID = $this->admin_model->add($data);
				
				if($_POST['adminType']!=1){
					// add admin permissions
					$permissionData = array(
							'adminID' 			 => $adminID,
							'create_admin' 		 => ($_POST['create_admin']) ? $_POST['create_admin'] : 0,
							'manage_admin' 		 => ($_POST['manage_admin']) ? $_POST['manage_admin'] : 0,
							'manage_distributor' => ($_POST['manage_distributor']) ? $_POST['manage_distributor'] : 0,
							'manage_store' 	  	 => ($_POST['manage_store']) ? $_POST['manage_store'] : 0,
							'manage_product' 	 => ($_POST['manage_product']) ? $_POST['manage_product'] : 0,
							'payment' 			 => ($_POST['payment']) ? $_POST['payment'] : 0,
							'reports' 			 => ($_POST['reports']) ? $_POST['reports'] : 0,
							'admin_password' 	 => ($_POST['admin_password']) ? $_POST['admin_password'] : 0,
							'admin_log' 		 => ($_POST['admin_log']) ? $_POST['admin_log'] : 0,
							'admin_upload' 		 => ($_POST['admin_upload']) ? $_POST['admin_upload'] : 0,
						);
					$this->admin_model->addAdminPermission($permissionData);
				}
				
				$this->adminReport('Added Admin '.$_POST['firstName']);
				
				redirect('admin-manager');
			}
			$this->data += $_POST;
		}
		
		//Admin Types
		$option_admin_type = array();
		$option_admin_type[''] = '-- Select --';
		$admin_types = $this->admin_model->getAdminTypes();
		foreach ($admin_types as $item) {
			$option_admin_type[$item->adminTypeID] = $item->adminTypeName;
		}
		$this->data['option_admin_type'] = $option_admin_type;
		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'admin';
		$this->load->view('add_admin', $this->data);
	}
	public function delete() {
		$data 	   = array('success'=>0,'text'=>'Please try again!');
		$adminInfo = $this->getAdminInfo($_POST['id']);
		if ($_POST['id'] == $this->session->userdata('userid')) {
			$data = array('success'=>0,'text'=>'You can not delete this admin!');
		} elseif ($this->admin_model->delete($_POST['id'])) {
			$this->adminReport('Deleted Admin '.$adminInfo->firstName);
			$data = array('success'=>1,'text'=>'You was deleted successfull!');
		}
		echo json_encode($data);
	}
	public function admin_profile($id){
		$adminInfo = $this->getAdminInfo($id);
		
		if (isset($_POST) && count($_POST) > 0) {
			//process submit			
			if ($this->validateForm($_POST, $id)) {
				$data = array(
					'adminTypeID'	=> $_POST['adminType'],
					'firstName'		=> $_POST['firstName'],
					'lastName'		=> $_POST['lastName'],
					'phone'			=> $_POST['phone'],
					'cell_phone'	=> $_POST['cell_phone'],
					'email'			=> $_POST['email'],
					'username'		=> $_POST['username'],
					//'password'		=> md5($_POST['password']),
					'createdDate'	=> date('Y-m-d H:i:s'),
					'adminStatus'	=> 1
				);
				
				if($_POST['password']) 
					$data = array_merge($data, array('password' => md5($_POST['password'])));
				
				$adminID = $this->admin_model->update($id, $data);
				
				if($_POST['adminType']!=1){
					// add admin permissions
					$permissionData = array(
							'adminID' 			 => $id,
							'create_admin' 		 => ($_POST['create_admin']) ? $_POST['create_admin'] : 0,
							'manage_admin' 		 => ($_POST['manage_admin']) ? $_POST['manage_admin'] : 0,
							'manage_distributor' => ($_POST['manage_distributor']) ? $_POST['manage_distributor'] : 0,
							'manage_store' 	  	 => ($_POST['manage_store']) ? $_POST['manage_store'] : 0,
							'manage_product' 	 => ($_POST['manage_product']) ? $_POST['manage_product'] : 0,
							'payment' 			 => ($_POST['payment']) ? $_POST['payment'] : 0,
							'reports' 			 => ($_POST['reports']) ? $_POST['reports'] : 0,
							'admin_password' 	 => ($_POST['admin_password']) ? $_POST['admin_password'] : 0,
							'admin_log' 		 => ($_POST['admin_log']) ? $_POST['admin_log'] : 0,
							'admin_upload' 		 => ($_POST['admin_upload']) ? $_POST['admin_upload'] : 0,
						);
					$this->admin_model->addAdminPermission($permissionData);
				}
				
				$this->adminReport('Update Admin '.$_POST['firstName']);
				
				redirect('admin-profile/'.$id);
			}
			
			$this->data += $_POST;
		} else {
			$this->data['adminType']	= $adminInfo->adminTypeID;
			$this->data['username']		= $adminInfo->username;
			$this->data['firstName'] 	= $adminInfo->firstName;
			$this->data['lastName'] 	= $adminInfo->lastName;
			$this->data['phone'] 		= $adminInfo->phone;
			$this->data['cell_phone'] 	= $adminInfo->cell_phone;
			$this->data['email'] 		= $adminInfo->email;	
			$this->data['password']		= $adminInfo->password;	
			$this->data['id'] 			= $adminInfo->adminID;	
		}
		
		//Admin Types
		$option_admin_type = array();
		$option_admin_type[''] = '-- Select --';
		$admin_types = $this->admin_model->getAdminTypes();
		foreach ($admin_types as $item) {
			$option_admin_type[$item->adminTypeID] = $item->adminTypeName;
		}
		$this->data['option_admin_type'] = $option_admin_type;
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] = 'admin';
		$this->load->view('profile_admin', $this->data);
	}
	public function admin_status(){
		$data = array('success'=>0,'text'=>'Please try again!');
		$getAdminStatus =  $this->getAdminInfo($_POST['id']);
		
		if($getAdminStatus->adminStatus==1) 
			$status = 0;
		else 
			$status = 1;
		
		if ($_POST['id'] == $this->session->userdata('userid')) {
			$data = array('success'=>0,'text'=>'You can not disable this admin!');
		} elseif ($this->admin_model->update($_POST['id'], array('adminStatus' => $status))) {
			$this->adminReport('Update Admin '.$getAdminStatus->firstName.'`s status');
			$data = array('success'=>1,'text'=>'Admin status successfully updated!');
		}
		echo json_encode($data);
	}
	
	private function validateForm($data, $id='') {
		
		if (strlen(trim($data['adminType'])) < 1) {
			$this->error['adminType'] = 'Admin type is required!';
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
			$this->error['username'] = 'Login id is required!';
		} elseif ($this->admin_model->checkUserExist($data['username'], $id)) {
			$this->error['username'] = 'Login id is already existed!';
		}
		if (strlen(trim($data['password'])) < 1 && !$id) {
			$this->error['password'] = 'The password is required!';
		}
		if (strlen(trim($data['phone'])) > 0 && !is_numeric(trim($data['phone']))) {
			$this->error['phone'] = 'Invalid phone number!';
		}
		if (strlen(trim($data['cell_phone'])) > 0 && !is_numeric(trim($data['cell_phone']))) {
			$this->error['cell_phone'] = 'Invalid cellphone number!';
		}
		
		if (strlen(trim($data['email'])) < 1) {
			$this->error['email'] = 'Your Email is required!';
		} elseif (!$this->form_validation->valid_email($data['email'])) {
			$this->error['email'] = 'Your Email is invalid!';
		} elseif ($this->admin_model->checkUserEmailExist($data['email'], $id)) {
			$this->error['email'] = 'Email is already existed!';
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function getAdminInfo($id){
		//admin info
		$info = $this->admin_model->getAdmin((int)$id);
		if (!is_numeric($id)|| !$info) {
			show_error('Store you requested was not found.', 404, 'Store is not found!');
		}
				
		$this->data['adminID'] = $id;
		$this->data['info'] = $info;
		
		return $info;
	}
	
	// Uploads ========================================================================
	public function pinless_rates(){
		
		##### Reset Table #####
		if(isset($_POST['reset']) && $_POST['reset'] == 1){
			// Truncate Table Record before insert			
			$this->admin_model->truncateTable('tbl_Rates');	
			$this->session->set_userdata('message', 'Rates reset successfully!');
			redirect('pinless-rates');
		}
		##### Reset Table END #####
		
		if (isset($_FILES) && count($_FILES) > 0) {
			if(!$_POST['page']){
				if (strlen(trim($_FILES['rates']['name'])) < 1 ) {
					$this->error['rates'] = 'Valid file required!';
				}
				if ($_FILES["rates"]["type"] !='text/csv') {
					$this->error['rates'] = 'Please Import valid .csv file!';
				}		
				if (!$this->error) {
					 
					$filename = $_FILES["rates"]["tmp_name"];			
					$file 	  = fopen($filename, "r");
					$faildData   = 0;	
					$successData = 0;			
					while (($impData = fgetcsv($file, 10000, ",")) !== FALSE)
					{
						$checkRates = $this->admin_model->getRates(array( 'destination' => $impData[0], 'city' => $impData[1] ));
						if( !empty($impData[0]) && !empty($impData[1]) && !empty($impData[2]) && is_numeric($impData[2]) ){
							if($checkRates)
							$this->admin_model->updateRates($checkRates[0]->ID, array('terminationDate' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') .' -1 day')) ));
						
							$data = array(
										'destination' 	=> $impData[0],								
										'city'		  	=> $impData[1],
										'rate' 			=> $impData[2],
										'date_time'	  	=> date('Y-m-d H:i:s')																	
									);
							$this->admin_model->addRates($data);
							$successData++;
						}else{
							$faildData++;
						}			  		
					}
					// Add Report
					$this->adminReport('Import Pinless Rates');
					
					if($faildData)
						$this->session->set_userdata('warning', $faildData.' rows fail to import due to null field or rate field is not integer value. Please check and try again.');
					
					$this->session->set_userdata('message', $successData.' rows imported successfully!');
					redirect('pinless-rates');				
				}			
			}
		}
		
		if (isset($_POST) && count($_POST) > 0) {
			$page = $_POST['page'];		
		}else{
			$page = 1;
		}
		$paging_data = array(
			'limit' => $this->config->item('num_per_page'),
			'current' => $page > 1 ? $page : 1,
			'total' => $this->admin_model->getTotalRates(array('terminationDate' => NULL))
		);
		$offset = ($paging_data['current'] - 1) * $this->config->item('num_per_page');			
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
				
		$getRates = $this->admin_model->getRates(array('terminationDate' => NULL), $this->config->item('num_per_page'), $offset);
			
		$option_country = array(''=>$this->lang->line('_all_'));
		$rates = $this->admin_model->getRates();
		foreach ($rates as $item) {
			$option_country[trim($item->destination)] = trim($item->destination);
		}
		$this->data['option_country'] = $option_country;
		
		//option balance
		$this->data['option_balance'] = array('5'=>'5', '10'=>'10', '15'=>'15', '20'=>'20', '30'=>'30');
				
		$this->data['get_rates']    = $getRates;
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'uploads';
		$this->load->view('pinless_rates', $this->data);
	}	
	public function export_rates(){
		$getRates = $this->admin_model->getRates();		
		
		header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename=pinless_rates.csv');
	    header('Pragma: no-cache');
	    header("Expires: 0");
	
	    $outstream = fopen("php://output", "w");
		$row = 1;
	    foreach($getRates as $result)
	    {						
			fputcsv($outstream, array('destination' 	  => $result->destination,								
										'city'		  	  => $result->city,
										'rate' 			  => $result->rate,
										'date_time'	  	  => $result->date_time,
										'terminationDate' => $result->terminationDate	
									 )
					);				        
	    }		
	    fclose($outstream);
		exit();
	}
	
	public function pinless_access_number(){
		
		if (isset($_FILES) && count($_FILES) > 0) {
			if (strlen(trim($_FILES['accessNumber']['name'])) < 1) {
				$this->error['accessNumber'] = 'Valid file required!';
			}elseif ($_FILES["accessNumber"]["type"] !='text/csv') {
				$this->error['accessNumber'] = 'Please Import valid .csv file!';
			}				
			if (!$this->error) { 				 
				$filename=$_FILES["accessNumber"]["tmp_name"];			
				$file = fopen($filename, "r");
				
				// Delete Record before insert
				if($_POST['importType']=='overwrite')			
					$this->admin_model->truncateTable("tbl_AccessNumber");
								
				$count = 0;
				$duplicateCount = 0;
				$duplicateRecords = '';
				while (($accessData = fgetcsv($file, 10000, ",")) !== FALSE)
				{								
					$data = array(
								'AccessNumber'=> $accessData[0],
								'State'		  => $accessData[1],
								'City' 		  => $accessData[2],
								'access_lang' => $accessData[3],
								'note' 		  => $accessData[4]
							);
			    	$checkDuplicateRecord = $this->admin_model->checkAccessNumber(array('AccessNumber' => $accessData[0], 'State' => $accessData[1], 'City' => $accessData[2]));
																	    
				  	if($checkDuplicateRecord){
				  		$this->admin_model->deleteAccessNumber(array('AccessNumber' => $accessData[0], 'State' => $accessData[1], 'City' => $accessData[2]));
				  		$duplicateCount++;
					} else {
				  		$count++;
				  	}
					$this->admin_model->addAccessNumber($data);				
				}
				
				$this->adminReport('Imported Access Number');
				
				$this->session->set_userdata('message', 'Access number imported successfully! Total '.$count.' records imported');
				redirect('pinless-access-number');					
			}		
		}
		
		$getAccess = $this->admin_model->getAccessNumber();
		$this->data['get_access']    = $getAccess;
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] = 'uploads';
		$this->load->view('pinless_access_number', $this->data);
	}
	public function export_access_record() {
		$results = $this->admin_model->getAccessNumber();
		header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename=access_record.csv');
	    header('Pragma: no-cache');
	    header("Expires: 0");
	
	    $outstream = fopen("php://output", "w");
		$row = 1;
	    foreach($results as $result)
	    {						
			fputcsv($outstream, array('AccessNumber'=> $result->AccessNumber, 
									  'State' 		=> $result->State, 
									  'City' 		=> $result->City,
									  'access_lang' => $result->access_lang, 
									  'note' 		=> $result->note,
									 )
					);				        
	    }		
	    fclose($outstream);
		exit();
	}	
	
	public function products_upload(){
		
		##### Reset Table #####
		if(isset($_POST['reset']) && $_POST['reset'] == 1){
			// Add Report
			$this->adminReport('Reset Products');
			// Truncate Table Record before insert			
			$this->admin_model->truncateTable('tbl_vonecallProducts');	
			$this->session->set_userdata('message', 'Products reset successfully!');
			redirect('products-upload');
		}
		##### Reset Table END #####
		
		if (isset($_FILES) && count($_FILES) > 0) {
			if(!$_POST['page']){
				if (strlen(trim($_FILES['products']['name'])) < 1 ) {
					$this->error['products'] = 'Valid file required!';
				}
				if ($_FILES['products']['type'] !='text/csv') {
					$this->error['products'] = 'Please Import valid .csv file!';
				}
				if(strlen($_POST['productType']) < 1){
					$this->error['productType'] = 'Product list recuired!';
				}	
				if (!$this->error) {
					 
					$filename = $_FILES["products"]["tmp_name"];			
					$file 	  = fopen($filename, "r");
					$faildData   = 0;	
					$successData = 0;			
					while (($impData = fgetcsv($file, 10000, ",")) !== FALSE)
					{
						$checkProducts = $this->admin_model->getVProducts(array( 'vproductName' => $impData[2], 'productTypeID' => $_POST['productType'], 'vproductSkuID' => $impData[1] ));
						if( !empty($impData[0]) && !empty($impData[1]) && !empty($impData[2]) && !empty($impData[9]) && is_numeric($impData[9]) ){
								
							if($checkProducts)
							$this->admin_model->updateVProduct($checkProducts[0]->vproductID, array('terminateDate' => date('Y-m-d H:i:s')));
						
							$data = array(
										'productTypeID'  		  	 => $_POST['productType'],
						             	'ppnProductID' 	 	 		 => $impData[0],
						             	'vproductSkuID' 	 	 	 => $impData[1],
						  				'vproductName' 	 		   	 => $impData[2],
						  				'vproductSkuName'			 => $impData[3],
						  				'vproductCategory'	 	  	 => $impData[4],
						  				'vproductVendor' 		  	 => $impData[5],
										'vproductCarrierName'		 => $impData[5],
										'vproductDenomination' 	   	 => $impData[6],				// Using as a face value
										'vproductMinAmount' 	   	 => $impData[7],
										'vproductmaxAmount' 	 	 => $impData[8],
										'vproductDiscount'			 => $impData[9],
										'vproducttotalCommission' 	 => $impData[9],
										'vproductAdminCommission' 	 => $impData[10],
										'vproductDistCommission'  	 => $impData[11],
										'vproductMinStoreCommission' => $impData[12],
										'vproductMaxStoreCommission' => $impData[13],
										'vproductCurrencyCode'		 => $impData[14],
										'vproductCountryCode'		 => $impData[15],
										'vproducteExchangeRate'		 => $impData[16],
										'effectiveDate'	  	 	  	 => date('Y-m-d H:i:s'),
										'vproductType'				 => $impData[17],
										'vLocalPhoneNumberLength'	 => $impData[18],
										
										/*'vproductSalesTaxCharged'	 => $impData[8],										
										'vproductBonusAmount'		 => $impData[10],									
										'vLocalPhoneNumberLength'	 => $impData[14],
										'vproductInternationalCodes' => $impData[15],
										'vproductAllowDecimal' 		 => $impData[16],									
										'vproductType'			 	 => $impData[21],
										//'note'					 	 => $impData[22],
										*/
																																							
									);
							$this->admin_model->addVProducts($data);
							$successData++;
						}else{
							$faildData++;
						}	  		
					}
					// Add Report
					$this->adminReport('Import Products');
					
					if($faildData)
						$this->session->set_userdata('warning', $faildData.' rows fail to import due to null field or commission field is not integer value. Please check and try again.');
					
					$this->session->set_userdata('message', $successData.' rows imported successfully!');
					redirect('products-upload');				
				}	
				$this->data += $_POST; 		
			}
		}
		
		if (isset($_POST) && count($_POST) > 0) {
			$page = $_POST['page'];		
		}else{
			$page = 1;
		}
		$paging_data = array(
			'limit' => $this->config->item('num_per_page'),
			'current' => $page > 1 ? $page : 1,
			'total' => $this->admin_model->getTotalVProducts(array('terminateDate' => NULL))
		);
		$offset = ($paging_data['current'] - 1) * $this->config->item('num_per_page');			
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
		
		//Product Type
		$product_types = array();
		$product_types[''] = '-- Select --';
		$products = $this->admin_model->getProducts();
		foreach ($products as $item) {
			$product_types[$item->productID] = $item->productName;
		}
		$this->data['product_types'] = $product_types;
					
		$getProducts = $this->admin_model->getVProducts(array('terminateDate' => NULL), $this->config->item('num_per_page'), $offset);
			
		$this->data['getvProducts'] = $getProducts;
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'uploads';
		$this->load->view('upload_product', $this->data);
	}
	public function export_products() {
		$getProducts = $this->admin_model->getVProducts(array('terminateDate' => NULL));
		header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename=vonecall_products_'.time().'.csv');
	    header('Pragma: no-cache');
	    header("Expires: 0");
		
	    $outstream = fopen("php://output", "w");
		$row = 1;
	    foreach($getProducts as $result)
	    {						
			fputcsv($outstream, array(
									    /*'Product Name' 	 		  => $result->vproductName,								
										'Product Type'	 		  => $result->vproductType,
										'Product Category'		  => $result->vproductCategory,
										'Product Vendor' 		  => $result->vproductVendor,
										'Product Total Commission'=> $result->vproducttotalCommission,
										'Product Admin Commission'=> $result->vproductAdminCommission,
										'Total Distrib Commission'=> $result->vproductDistCommission,
										'Store Max Commission'	  => $result->vproductMaxStoreCommission,
										'Store Min Commission'	  => $result->vproductMinStoreCommission,	
										'SKUID'	  	 	  		  => $result->vproductSkuID,
										'CountryCode'	  		  => $result->countryCode,
										'Note'					  => $result->note*/
										'Product Id' 	 	 		=> $result->ppnProductID,
						             	'Sku Id' 	 	 	 		=> $result->vproductSkuID,
						  				'Product Name' 	 		   	=> $result->vproductName,
						  				'Sku Name'			 		=> $result->vproductSkuName,
						  				'Category'	 	  	 		=> $result->vproductCategory,
						  				'Operator' 		  	 		=> $result->vproductVendor,										
										'Face Value' 	   	 		=> $result->vproductDenomination,				// Using as a face value
										'Min' 	   	 		 		=> $result->vproductMinAmount,
										'Max' 	 	 		 		=> $result->vproductmaxAmount,
										'Discount'			 		=> $result->vproductDiscount,
										'RYD Commission' 	 		=> $result->vproductAdminCommission,
										'Distributor Commission'  	=> $result->vproductDistCommission,
										'Store Min Commission' 		=> $result->vproductMinStoreCommission,
										'Store Max Commission' 		=> $result->vproductMaxStoreCommission,
										'Currency'		 			=> $result->vproductCurrencyCode,
										'Country'		 			=> $result->vproductCountryCode,
										'Ex Rate'		 			=> $result->vproducteExchangeRate,
										'Product Type'	 			=> $result->vproductType,
										'Local Phone Number Length'	=> $result->vLocalPhoneNumberLength,
									 )
					);				        
	    }		
	    fclose($outstream);
		exit();
	}	
	
	public function product_logo_upload(){
		
		if (isset($_FILES) && count($_FILES) > 0) {
						
			if(strlen($_POST['ppnProductID']) < 1){
				$this->error['ppnProductID'] = 'Product recuired!';
			}
			if(!$this->error){
				$config['upload_path']   = dirname($_SERVER["SCRIPT_FILENAME"]).'/public/uploads/product_logo';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
				$config['max_size']		 = '100';
				$config['max_width'] 	 = '1024';
				$config['max_height']    = '768';
				
				$this->load->library('upload', $config);
		
				if ( ! $this->upload->do_upload('file')){
					$this->error['image_error'] = $this->upload->display_errors();				
				} else {
					$data = array('ppnProductID'=>$_POST['ppnProductID'], 'logoName' => $_FILES['file']['name']);
					
					$this->admin_model->addImageSettings($data);
					
					$this->session->set_userdata('message', 'Image Uploaded successfully!');
					redirect('product-logo-upload');
				}
			}
			$this->data += $_POST;	   	    
		}
		
		// Get Product Logo
		$productLogo   		   = $this->admin_model->getProductImages();
		$getLogoResult 		   = $this->admin_model->array_unique_by_key($productLogo, "ppnProductID");
		$this->data['results'] = $getLogoResult;
		
		//Product Type
		$productOperator = array();
		$i=0;
		$productOperator[''] = '-- Select --';
		$getProducts = $this->admin_model->getVProductsGroupBY(array('terminateDate' => NULL, 'vproductType' => 'Topup'));
		$getUniqueResult = $this->admin_model->array_unique_by_key($getProducts, "ppnProductID");
		foreach ($getUniqueResult as $item) {
			if(!$this->admin_model->searchValueInArray($productLogo, $item->ppnProductID)){
				$productOperator[$item->ppnProductID] = $item->vproductVendor;
			}		
			
		}
		$this->data['productOperator'] = $productOperator;		
		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'uploads';
		$this->load->view('upload_product_logo', $this->data);
	}
	public function country_flag_upload(){
		
		if (isset($_FILES) && count($_FILES) > 0) {
						
			if(strlen($_POST['countryCode']) < 1){
				$this->error['countryCode'] = 'Country recuired!';
			}
			if(!$this->error){
				$config['upload_path']   = dirname($_SERVER["SCRIPT_FILENAME"]).'/public/uploads/country_flag';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
				$config['max_size']		 = '100';
				$config['max_width'] 	 = '1024';
				$config['max_height']    = '768';
				
				$this->load->library('upload', $config);
		
				if ( ! $this->upload->do_upload('flag')){
					$this->error['image_error'] = $this->upload->display_errors();				
				} else {
					$data = array('countryFlag' => $_FILES['flag']['name']);
					
					$this->admin_model->UpdateCountryCodes(array('CountryCodeIso' => $_POST['countryCode']), $data);
					
					$this->session->set_userdata('message', 'Flag Uploaded successfully!');
					redirect('country-flag-upload');
				}
			}
			$this->data += $_POST;	   	    
		}
		
		//Country
		$option_country = array('' => '-- Country --');
		$country 		= $this->admin_model->getCountryCodes();
		foreach ($country as $item) {
			$option_country[trim($item->CountryCodeIso)] = trim($item->CountryName);
		}
		$this->data['optionCountry'] = $option_country;
		
		$this->data['results'] 		= $country;
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'uploads';
		$this->load->view('upload_country_flag', $this->data);
	}
	
	// Calling cards===================================================================
	public function calling_card_upload(){
		$where = array();
		if (isset($_FILES) && count($_FILES) > 0) {
			if(!$_POST['page']){
				if (strlen(trim($_FILES['callingCards']['name'])) < 1) {
					$this->error['callingCards'] = 'Valid file required!';
				}elseif ($_FILES["callingCards"]["type"] !='text/csv') {
					$this->error['callingCards'] = 'Please Import valid .csv file!';
				}
				
				if (strlen(trim($_POST['batchID'])) < 1) {
					$this->error['batchID'] = 'Batch required!';
				}
								
				if (!$this->error) {
					
					// Delete Record before insert
					if($_POST['importType']=='overwrite')			
						$this->admin_model->truncateTable("tbl_CallingCard");
					 				 
					$filename=$_FILES["callingCards"]["tmp_name"];			
					$file = fopen($filename, "r");
					
					$count = 0;
					$duplicateCount = 0;
					$duplicateRecords = '';
					while (($cardsData = fgetcsv($file, 10000, ",")) !== FALSE)
					{								
						$data = array(
									'callingCardBatchName'     => $_POST['batchID'],
									'callingCardPin'		   => $cardsData[0],
									//'callingCardDenomination'  => $cardsData[1],
									//'callingCardPurchaseDate'  => $cardsData[3],
									//'callingCardPurchaseStore' => $cardsData[4],
									//'callingCardPurchaseStoreName' => $cardsData[5]
								);
						$this->admin_model->addCallingCard($data);
						$count++;				
					}
					
					$this->adminReport('Imported Calling Cards');
					
					$this->session->set_userdata('message', 'Calling Cards imported successfully! Total '.$count.' records imported');
					redirect('calling-card-upload');					
				}
			}	// END IF (Post Page)	
			
			$this->data += $_POST;	
		}
		
		if (isset($_POST) && count($_POST) > 0) {
			$page = $_POST['page'];		
		}else{
			$page = 1;
		}
		$paging_data = array(
			'limit'   => 30,
			'current' => $page > 1 ? $page : 1,
			'total'   => count($this->admin_model->getCallingCards())
		);
		$offset = ($paging_data['current'] - 1) * 30;			
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
				
		// Get Results
		$getCardsResult 	   = $this->admin_model->getCallingCards($where, 30, $offset);
		$this->data['results'] = $getCardsResult;
		
		//Batch
		$option_batch 	  = array();
		$option_batch[''] = '-- Select --';
		$getBatches 	  = $this->admin_model->getCardBatches();
		foreach ($getBatches as $item) {
			$option_batch[$item->batchID] = $item->batchName;
		}
		$this->data['option_batch'] = $option_batch;		
		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'uploads';
		$this->load->view('upload_calling_card', $this->data);
	}
	public function calling_card_batch(){
		
		if (isset($_POST) && count($_POST) > 0) {
			$batchID = strlen($_POST['edit'])>0?$_POST['edit']:0;
			if (strlen($_POST['batchName']) < 1) {
				$this->error['batchName'] = 'Batch Name is required!';
			} 
									
			if (!$this->error) {
				$imageArray = array();
				if(isset($_FILES) && $_FILES['batchCardImage']['name']!=''){
					
					// unlink Image if exist
					if ($batchID) {
						$getBatchDetails = $this->admin_model->getCardBatches(array('batchID' => $batchID));
						if($getBatchDetails[0]->batchCardImage){
							unlink(dirname($_SERVER["SCRIPT_FILENAME"]).'/public/uploads/calling_cards/'.$getBatchDetails[0]->batchCardImage);
						}
					}
					
					$config['upload_path']   = dirname($_SERVER["SCRIPT_FILENAME"]).'/public/uploads/calling_cards';
					$config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
					$config['max_size']		 = '100';
					$config['max_width'] 	 = '1024';
					$config['max_height']    = '768';
					//$config['encrypt_name']  = TRUE;
					$fileName 				 = time().$_FILES["batchCardImage"]['name'];
					$config['file_name'] 	 = $fileName;
					
					$this->load->library('upload', $config);
			
					if ( ! $this->upload->do_upload('batchCardImage')){
						$this->error['batchCardImage'] = $this->upload->display_errors();				
					}else{
						$imageArray = array('batchCardImage' => $fileName);
					}	
				} 
				
				$data = array( 'batchName' 	 => $_POST['batchName'],
						   	   'batchAmount' => $_POST['batchAmount'],
						);
				
				if ($batchID) {
					if($imageArray)
						$data = array_merge($data, $imageArray);
					
					$this->admin_model->updateCardBatch($batchID, $data);
					$this->session->set_userdata('message', 'You have updated batch successfully!');
					
					// Report
					$this->adminReport('Update Calling Card Batch '.$_POST['batchName']);
				} else {
					$data = array_merge($data, array('dateTime' => date('Y-m-d H:i:s')));
					
					if($imageArray)
						$data = array_merge($data, $imageArray);
					
					$this->admin_model->addCardBatch($data);
					$this->session->set_userdata('message', 'You have added card batch successfully!');
					
					// Report
					$this->adminReport('Added new Calling Card Batch '.$_POST['batchName']);
				}
				redirect('card-batch');
							
			}
			$this->data += $_POST;
		}
		$this->data['error'] = $this->error;
		
		$this->data['results'] 		= $this->admin_model->getCardBatches();
		
		$this->data['current_page'] = 'calling_card';
		$this->load->view('add_calling_card_batch', $this->data);
	}
	public function delete_calling_card_batch() {
		$data = array('success'=>0,'text'=>'Please try again!');		
		
		$getBatchDetails = $this->admin_model->getCardBatches(array('batchID' => $_POST['id']));
		if($getBatchDetails[0]->batchCardImage){
			unlink(dirname($_SERVER["SCRIPT_FILENAME"]).'/public/uploads/calling_cards/'.$getBatchDetails[0]->batchCardImage);
		}		

		if( $this->admin_model->deleteCardBatch($_POST['id']) ){				
			$this->session->set_userdata('message', 'You have deleted batch successfully!');
			$data = array('success'=>1,'text'=>'You have deleted batch successfully!');
		} 
		echo json_encode($data);
	}
	public function calling_cards(){
		$where = array();
		if (isset($_POST) && count($_POST) > 0) {
			$page = $_POST['page'];		
		}else{
			$page = 1;
		}
		$paging_data = array(
			'limit'   => 30,
			'current' => $page > 1 ? $page : 1,
			'total'   => count($this->admin_model->getCallingCards())
		);
		$offset = ($paging_data['current'] - 1) * 30;			
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
				
		// Get Results
		$getCardsResult 	   = $this->admin_model->getCallingCards($where, 30, $offset);
		$this->data['results'] = $getCardsResult;
		
		//Batch
		$option_batch 	  = array();
		$option_batch[''] = '-- Select --';
		$getBatches 	  = $this->admin_model->getCardBatches();
		foreach ($getBatches as $item) {
			$option_batch[$item->batchID] = $item->batchName;
		}
		$this->data['option_batch'] = $option_batch;		
		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'calling_card';
		$this->load->view('all_calling_card', $this->data);
	} 
	public function calling_cards_instruction(){
		$getInstruction 	 = $this->admin_model->getSettings(array('settingKey' => 'ccardInstruction'));
		$getDisclaimer  	 = $this->admin_model->getSettings(array('settingKey' => 'ccardDisclaimer'));
		$getTollFree  		 = $this->admin_model->getSettings(array('settingKey' => 'ccardTollFree'));
		$getCustomerService  = $this->admin_model->getSettings(array('settingKey' => 'ccardCustomerService'));
		
		if (isset($_POST) && count($_POST) > 0) {

			if(!$this->error){
				$instruction 	  = array('settingKey' => 'ccardInstruction', 'settingValue' => $_POST['cardInstruction']);
				$disclaimer  	  = array('settingKey' => 'ccardDisclaimer', 'settingValue' => $_POST['ccardDisclaimer']);
				$tollFree    	  = array('settingKey' => 'ccardTollFree', 'settingValue' => $_POST['ccardTollFree']);
				$customerService  = array('settingKey' => 'ccardCustomerService', 'settingValue' => $_POST['ccardCustomerService']);
				
				// IF GET instruction
				if($getInstruction){ 
					$this->admin_model->updateSettings(array('settingKey' => 'ccardInstruction'), $instruction);
				} else
					$this->admin_model->addSettings($instruction);
				
				// IF GET Disclamier
				if($getDisclaimer)
					$this->admin_model->updateSettings(array('settingKey' => 'ccardDisclaimer'), $disclaimer);
				else
					$this->admin_model->addSettings($disclaimer);
				
				// IF GET Tollfree
				if($getTollFree)
					$this->admin_model->updateSettings(array('settingKey' => 'ccardTollFree'), $tollFree);
				else
					$this->admin_model->addSettings($tollFree);
				
				// IF GET Customer Service
				if($getCustomerService)
					$this->admin_model->updateSettings(array('settingKey' => 'ccardCustomerService'), $customerService);
				else
					$this->admin_model->addSettings($customerService);
				
				$this->session->set_userdata('message', 'You have updated details successfully');
				redirect('calling-cards-instructions');
			}
			$this->data += $_POST;
		}
		
		$this->data['cardInstruction'] 		= $getInstruction?$getInstruction->settingValue:'';
		$this->data['ccardDisclaimer'] 		= $getDisclaimer?$getDisclaimer->settingValue:'';
		$this->data['ccardTollFree'] 		= $getTollFree?$getTollFree->settingValue:'';
		$this->data['ccardCustomerService'] = $getCustomerService?$getCustomerService->settingValue:'';
				
		$this->data['error'] = $this->error;
		$this->data['current_page'] 	= 'calling_card';
		$this->load->view('callingcard_instruction', $this->data);
	}
	public function calling_cards_upload_local_access(){
		$where = array();
		if (isset($_FILES) && count($_FILES) > 0) {
			if(!$_POST['page']){
				if (strlen(trim($_FILES['callingCardsAccessNumber']['name'])) < 1) {
					$this->error['callingCardsAccessNumber'] = 'Valid file required!';
				}elseif ($_FILES["callingCardsAccessNumber"]["type"] !='text/csv') {
					$this->error['callingCardsAccessNumber'] = 'Please Import valid .csv file!';
				}
								
				if (!$this->error) {
					
					// Delete Record before insert
					if($_POST['importType']=='overwrite')			
						$this->admin_model->truncateTable("tbl_CallingCardAccessNumbers");
					 				 
					$filename=$_FILES["callingCardsAccessNumber"]["tmp_name"];			
					$file = fopen($filename, "r");
					
					$count = 0;
					$duplicateCount = 0;
					$duplicateRecords = '';
					while (($accessData = fgetcsv($file, 10000, ",")) !== FALSE)
					{								
						$data = array( 'accessNumber' => $accessData[0]	);
						$this->admin_model->addCallingCardAccessNumbers($data);
						$count++;				
					}
					
					$this->adminReport('Imported Calling card access numbers');
					
					$this->session->set_userdata('message', 'Calling Card access numbers imported successfully! Total '.$count.' records imported');
					redirect('upload-local-access');					
				}
			}	// END IF (Post Page)	
			
			$this->data += $_POST;	
		}
		
		if (isset($_POST) && count($_POST) > 0) {
			$page = $_POST['page'];		
		}else{
			$page = 1;
		}
		$paging_data = array(
			'limit'   => 30,
			'current' => $page > 1 ? $page : 1,
			'total'   => count($this->admin_model->getCallingCardAccessNumbers())
		);
		$offset = ($paging_data['current'] - 1) * 30;			
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
				
		// Get Results
		$getCardAccessResult   = $this->admin_model->getCallingCardAccessNumbers($where, 30, $offset);
		$this->data['results'] = $getCardAccessResult;
		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'calling_card';
		$this->load->view('upload_calling_card_local_access', $this->data);
	}
		
	// Products =======================================================================	
	public function product() {
		if ( isset($_POST) && count($_POST) > 0 && !isset($_POST['productSearchByList']) ) {
			$productID = strlen($_POST['edit'])>0?$_POST['edit']:0;
			if (strlen($_POST['ppnProductID']) < 1 && $_POST['productType'] == 'Topup') {
				$this->error['ppnProductID'] = 'Prepay Nation Product ID is required!';
			}
			if (strlen($_POST['sku']) < 1 && $_POST['productType'] == 'Topup') {
				$this->error['sku'] = 'SKU ID is required!';
			}
			if (strlen($_POST['skuName']) < 1 && $_POST['productType'] == 'Topup') {
				$this->error['skuName'] = 'SKU Name is required!';
			}
			if (strlen($_POST['productName']) < 1) {
				$this->error['productName'] = 'Product Name is required!';
			} 
			if (strlen($_POST['faceValue']) < 1) {
				$this->error['faceValue'] = 'Product face value is required!';
			}
			if (strlen($_POST['productMinAmount']) < 1) {
				$this->error['productMinAmount'] = 'Product minimum amount is required!';
			}
			if (strlen($_POST['productMaxAmount']) < 1) {
				$this->error['productMaxAmount'] = 'Product maximum amount is required!';
			}
			if (strlen($_POST['productList']) < 1) {
				$this->error['productList'] = 'Product List is required!';
			} 
			if (strlen($_POST['productType']) < 1) {
				$this->error['productType'] = 'Product Type is required!';
			}
			if (strlen($_POST['productCategory']) < 1) {
				$this->error['productCategory'] = 'Product Category is required!';
			}
			if (strlen($_POST['productVender']) < 1) {
				$this->error['productVender'] = 'Product Vender is required!';
			}			
			if (strlen(trim($_POST['productCommission'])) < 1) {
				$this->error['productCommission'] = 'Product commission is required!';
			} elseif (!is_numeric(trim($_POST['productCommission'])) || (int)$_POST['productCommission']==0) {
				$this->error['productCommission'] = 'Product commission is invalid!';
			}
			
			if (strlen(trim($_POST['adminCommission'])) < 1) {
				$this->error['adminCommission'] = 'Admin commission is required!';
			} elseif (!is_numeric(trim($_POST['productCommission'])) || (int)$_POST['adminCommission']==0) {
				$this->error['adminCommission'] = 'Admin commission is invalid!';
			}
			
			if (strlen(trim($_POST['distributorCommission'])) < 1) {
				$this->error['distributorCommission'] = 'Distributor commission is required!';
			} elseif (!is_numeric(trim($_POST['productCommission'])) || (int)$_POST['distributorCommission']==0) {
				$this->error['distributorCommission'] = 'Distributor commission is invalid!';
			}
			if (strlen(trim($_POST['storeMaxCommission'])) < 1) {
				$this->error['storeMaxCommission'] = 'Max store commission is required!';
			} elseif (!is_numeric(trim($_POST['productCommission'])) || (int)$_POST['storeMaxCommission']==0) {
				$this->error['storeMaxCommission'] = 'Max store commission is invalid!';
			}
			if (strlen(trim($_POST['storeMinCommission'])) < 1) {
				$this->error['storeMinCommission'] = 'Min store commission is required!';
			} elseif (!is_numeric(trim($_POST['productCommission'])) || (int)$_POST['storeMinCommission']==0) {
				$this->error['storeMinCommission'] = 'Min store commission is invalid!';
			}
			if (strlen($_POST['countryList']) < 1 && $_POST['productType'] == 'Topup') {
				$this->error['countryList'] = 'Country is required!';
			}
			if ( strlen($_POST['localNumberLength']) < 1 ) {
				$this->error['localNumberLength'] = 'Local number length is required!';
			}
			
			if (!$this->error) {
				$data = array(
					'ppnProductID' 	 	 		 => $_POST['ppnProductID'],
					'vproductSkuID' 	 	 	 => $_POST['sku'],	
					'vproductSkuName'			 => $_POST['skuName'],
					'productTypeID'  		  	 => $_POST['productList'],
					'vproductName' 	 		   	 => $_POST['productName'],
					'vproductType'	 		  	 => $_POST['productType'],
					'vproductCategory'	 	  	 => $_POST['productCategory'],
					'vproductVendor' 		  	 => $_POST['productVender'],
					'vproductCarrierName'		 => $_POST['productVender'],
					'vproductDenomination' 	   	 => $_POST['faceValue'],				// Using as a face value
					'vproducttotalCommission' 	 => $_POST['productCommission'],
					'vproductDiscount'			 => $_POST['productCommission'],
					'vproductAdminCommission' 	 => $_POST['adminCommission'],
					'vproductDistCommission'  	 => $_POST['distributorCommission'],
					'vproductMaxStoreCommission' => $_POST['storeMaxCommission'],
					'vproductMinStoreCommission' => $_POST['storeMinCommission'],
					//'effectiveDate'	  	 	  	 => date('Y-m-d H:i:s'),
					'note'					 	 => $_POST['note'],
					'countryCode'				 => $_POST['countryList'],
					'vproductCountryCode'		 => $_POST['countryList'],							
					'vproductMinAmount' 	   	 => $_POST['productMinAmount'],
					'vproductmaxAmount' 	 	 => $_POST['productMaxAmount'],
					'vLocalPhoneNumberLength'	 => $_POST['localNumberLength'],					
				);
				
				if ($productID) {
					$this->admin_model->updateVProduct($productID, $data);
					$this->session->set_userdata('message', 'You have updated product successfully!');
					
					// Report
					$this->adminReport('Update Product '.$_POST['productName']);
				} else {
					$data = array_merge($data, array('effectiveDate' => date('Y-m-d H:i:s'))); 
					
					// Check Duplicate Record
					$checkDuplicate = $this->admin_model->getVProducts(array('terminateDate' => NULL, 'vproductName' => $_POST['productName'],'productTypeID' => $_POST['productList'],'vproductVendor' => $_POST['productVender']));
					if($checkDuplicate)
						$this->admin_model->updateVProduct($checkDuplicate[0]->vproductID, array('terminateDate' => date('Y-m-d H:i:s')));
						
					$this->admin_model->addVProducts($data);
					$this->session->set_userdata('message', 'You have added product successfully!');
					
					// Report
					$this->adminReport('Added new product '.$_POST['productName']);
				}
				redirect('product');
			}
			$this->data += $_POST;
		}
		$this->data['error'] = $this->error;
		
		//Product Lists============
		$product_lists = array();
		$product_lists[''] = '-- Select --';
		$products = $this->admin_model->getProducts();
		foreach ($products as $item) {
			$product_lists[$item->productID] = $item->productName;
		}
		$this->data['product_lists'] = $product_lists;		
		
		//Product Type============
		$product_types 		= array();
		$product_types[''] 	= '-- Select --';
		$productTypes = array('Topup' => 'Topup', 'Pinless' => 'Pinless', 'Calling Card'=>'Calling Card');
		foreach ($productTypes as $key => $item) {
			$product_types[$key] = $item;
		}
		$this->data['product_types'] = $product_types;
		
		//Country
		$option_country = array('' => '-- Country --');
		$country 		= $this->admin_model->getCountryCodes();
		foreach ($country as $item) {
			$option_country[trim($item->CountryCodeIso)] = trim($item->CountryName);
		}
		$this->data['optionCountry'] = $option_country;
		
		if(isset($_POST) && isset($_POST['productSearchByList']) ){
			$res = $this->admin_model->getVProducts(array('terminateDate' => NULL, 'productTypeID' => $_POST['productSearchByList']));
			//echo '<pre>';print_r($res[1]);die;
			$this->data['results'] = $this->admin_model->getVProducts(array('terminateDate' => NULL, 'productTypeID' => $_POST['productSearchByList']));
			$this->data += $_POST;
		}else{
			$this->data['results'] = array();//$this->admin_model->getVProducts(array('terminateDate' => NULL));	
		}			
		
		$this->data['current_page']  = 'admin';
		$this->load->view('product', $this->data);
	}
	public function delete_product() {
		$data = array('success'=>0,'text'=>'Please try again!');
		if ($this->admin_model->updateVProduct($_POST['productID'], array('terminateDate' => date('Y-m-d H:i:s')))) {
							
			// Report
			$this->adminReport('Deleted product '.$_POST['productName']);
			
			$this->session->set_userdata('message', 'You have deleted product successfully!');
			$data = array('success'=>1);
		}
		echo json_encode($data);
	}
	public function product_type(){
		
		if (isset($_POST) && count($_POST) > 0) {
			$productID = strlen($_POST['edit'])>0?$_POST['edit']:0;
			if (strlen($_POST['productName']) < 1) {
				$this->error['productName'] = 'Product Name is required!';
			} 
									
			if (!$this->error) {
				$data = array(
					'productName' 			=> $_POST['productName'],								
					'note'		  			=> $_POST['note'],
				);
				if ($productID) {
					$this->admin_model->updateProduct($productID, $data);
					$this->session->set_userdata('message', 'You have updated product successfully!');
					
					// Report
					$this->adminReport('Update Product type '.$_POST['productName']);
				} else {
					$this->admin_model->addProduct($data);
					$this->session->set_userdata('message', 'You have added product successfully!');
					
					// Report
					$this->adminReport('Added new product '.$_POST['productName']);
				}
				redirect('product-type');
			}
			$this->data += $_POST;
		}
		$this->data['error'] = $this->error;
		
		$this->data['results'] 		= $this->admin_model->getProducts();
		$this->data['current_page'] = 'admin';
		$this->load->view('product_type', $this->data);
	}
	public function delete_product_type() {
		$data = array('success'=>0,'text'=>'Please try again!');
		$getVProducts = $this->admin_model->getVProducts(array('productTypeID' => $_POST['productID'], 'terminateDate' => null ));
		
		if($getVProducts){
			$data = array('success'=>0,'text'=>'This Product Type has respect to the Products, Please remove Products out of this Product Type before!');
		}else{
			if ($this->admin_model->deleteProduct($_POST['productID'])) {
				// Report
				$this->adminReport('Deleted product type '.$_POST['productName']);
				
				$this->session->set_userdata('message', 'You have deleted product successfully!');
				$data = array('success'=>1);
			}
		}		
		echo json_encode($data);
	}
	public function search_products(){
		$result = $this->admin_model->getVProducts(array('terminateDate' => NULL, 'productTypeID' => $_REQUEST['id']));
		if($result)
			echo json_encode($result);
		else
			echo json_encode(array('success'=>0));
	}
	public function search_product_by_key(){
		//Search Here
		$getAllProducts =  $this->admin_model->getVProducts(array('terminateDate' => NULL));
		if (isset($_POST) && count($_POST) > 0) {
			//echo '<pre>';print_r($_POST);die;
			$searchKey = $_POST['searchKey'];
			$where = "vp.vproductVendor LIKE '%".$searchKey."%' OR cc.CountryName LIKE '%".$searchKey."%' OR vp.vproductCategory LIKE '%".$searchKey."%' OR vp.vproductName LIKE '%".$searchKey."%' OR vp.vproductSkuID LIKE '%".$searchKey."%'";
			$getAllProducts = $this->admin_model->getVProducts($where);
			$this->data += $_POST;
		}
		
		$this->data['error'] = $this->error;
		
		//Product Lists============
		$product_lists = array();
		$product_lists[''] = '-- Select --';
		$products = $this->admin_model->getProducts();
		foreach ($products as $item) {
			$product_lists[$item->productID] = $item->productName;
		}
		$this->data['product_lists'] = $product_lists;	
		
		//Product Type============
		$product_types 		= array();
		$product_types[''] 	= '-- Select --';
		$productTypes = array('Topup' => 'Topup', 'Pinless' => 'Pinless');
		foreach ($productTypes as $key => $item) {
			$product_types[$key] = $item;
		}
		$this->data['product_types'] = $product_types;	
		
		//Country
		$option_country = array('' => '-- Country --');
		$country 		= $this->admin_model->getCountryCodes();
		foreach ($country as $item) {
			$option_country[trim($item->CountryCodeIso)] = trim($item->CountryName);
		}
		$this->data['optionCountry'] = $option_country;
		
		$this->data['results'] 		 = $getAllProducts;	
		$this->data['current_page']  = 'admin';
		$this->load->view('product', $this->data);
	}
	
	public function get_all_product_by_list($listID=0) {
		$getVProducts = $this->admin_model->getVProducts(array('productTypeID' => $listID, 'terminateDate' => null ));
		$ret = '<option value="">-- Select --</option>';
		
		if($getVProducts){
			for($i=0; $i < count($getVProducts); $i++){
				$ret .= '<option value="'.$getVProducts[$i]->vproductID.'">'.$getVProducts[$i]->vproductName.'</option>';
			}
		}
		echo $ret;
	}
	
	// Products END ===================================================================
	
	// Admin Logs =====================================================================
	public function admin_logs(){
		$where   = array();
		
		if (isset($_POST) && count($_POST) > 0) {
			$page = $_POST['page'];		
		}else{
			$page = 1;
		}
		$paging_data = array(
			'limit' => $this->config->item('num_per_page'),
			'current' => $page > 1 ? $page : 1,
			'total' => $this->admin_model->getTotalReports()
		);
		$offset = ($paging_data['current'] - 1) * $this->config->item('num_per_page');			
		$this->data['paging'] = $this->paging->do_paging_customer($paging_data);
				
		$results = $this->admin_model->getReport($where, $this->config->item('num_per_page'), $offset);
		
		$this->data['results'] 			= $results;
		$this->data['current_page'] 	= 'admin';
		$this->data['sub_current_page'] = 'reports';
		$this->load->view('reports/_reports', $this->data);
	}
	public function admin_export_report(){
		$results = $this->admin_model->getAllReport();
		
		header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename=admin_log.csv');
	    header('Pragma: no-cache');
	    header("Expires: 0");
	
	    $outstream = fopen("php://output", "w");
		$row = 1;
	    foreach($results as $result)
	    {						
			fputcsv($outstream, array('Time stamp' => $result->date_time, 'Admin' => $result->firstName, 'Action' => $result->action));				        
	    }		
	    fclose($outstream);
		exit();
	}
	public function admin_log_management(){
			
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen(trim($_POST['fromDate'])) < 1) {
				$this->error['fromDate'] = 'From date required!';
			}
			if (strlen(trim($_POST['toDate'])) < 1) {
				$this->error['toDate'] = 'To date required!';
			}			
			if(!$this->error){
				$fromDate = date('Y-m-d 00:00:00', strtotime($_POST['fromDate']));
				$toDate   = date('Y-m-d 11:59:59', strtotime($_POST['toDate']));
				$where = "date_time BETWEEN '$fromDate' AND '$toDate'";
				$deleteReport = $this->admin_model->deleteReports($where);
				if($deleteReport){
					$this->session->set_userdata('message', 'Reports deleted successfully!');
					redirect('log-management');
				}else{
					$this->data['warning'] = 'Please try again';
				}
			}
			$this->data += $_POST;
		}	
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'admin';
		$this->data['sub_current_page'] = 'log_management';
		$this->load->view('reports/_reports', $this->data);
	}
	
	// Promotions =============================================================================
	public function promotion() {
		if ($this->session->userdata('usertype') != 'admin') {
			show_error('You are not allowed to access this page.', 404, 'Access Denied!');
		}
		$promotion = $this->admin_model->getPromotion();
		if (isset($_POST) && count($_POST) > 0) {
			//validate
			$promotionID = strlen($_POST['edit'])>0?$_POST['edit']:0;
			if (strlen(trim($_POST['amount'])) > 0 && !is_numeric(trim($_POST['amount']))) {
				$this->error['amount'] = 'Promotion Dollar Amount is invalid!';
			}
			/*if (strlen(trim($_POST['percentage'])) > 0 && !is_numeric(trim($_POST['percentage']))) {
				$this->error['percentage'] = 'Percentage of Purchase is invalid!';
			}*/
			if (strlen(trim($_POST['product'])) < 1) {
				$this->error['product'] = 'Product is required!';
			} elseif ($this->admin_model->checkExistPromotion($_POST['product']) && $promotionID==0) {
				$this->error['product'] = 'Promotion for this product is already exist!';
			}
			if (!$this->error) {
				$data = array(
					'fromDate'		=> trim($_POST['from_date'])!=''?date('Y-m-d 00:00:00', strtotime($_POST['from_date'])):NULL,
					'toDate'		=> trim($_POST['to_date'])!=''?date('Y-m-d 23:59', strtotime($_POST['to_date'])):NULL,
					'amount'		=> trim($_POST['amount'])!=''?trim($_POST['amount']):0,
					//'percentage'	=> trim($_POST['percentage'])!=''?trim($_POST['percentage']):0,
					'productID'		=> $_POST['product'],
					'status'		=> 1
				);
				
				if($promotionID){
					$this->admin_model->updatePromotion($promotionID, $data);
					$this->session->set_userdata('message', 'You have updated promotion successfully!');
				}else{
					$this->admin_model->addPromotion($data);
					$this->session->set_userdata('message', 'You have added promotion successfully!');	
				}	
				
				//redirect to list page
				redirect(site_url('promotion'));
			}
			$this->data += $_POST;
		}
		//assign error
		$this->data['error'] = $this->error;
		
		//get promotion history
		$this->data['results'] = $this->admin_model->getPromotions();
		
		//product
		$this->load->model('admin_model');  
		$option_product = array();
		$option_product[''] = '-- Select --';
		$products = $this->admin_model->getProducts();
		foreach ($products as $item) {
			$option_product[$item->productID] = $item->productName;
		}
		$this->data['option_product'] = $option_product;
		
		//customer type
		$this->data['option_customer_type'] = array('1'=>'New Only');
		
		$this->data['current_page'] = 'promotion';
		$this->load->view('promotion', $this->data);
	}
	public function delete_promotion() {
		$data = array('success'=>0,'text'=>'Please try again!');
		if (isset($_POST['id']) && $_POST['id'] && $this->admin_model->deletePromotion($_POST['id'])) {
			$this->session->set_userdata('message', 'You have deleted promotion history successfully!');
			$data = array('success'=>1);
		}
		echo json_encode($data);
	}
	
	public function promotion_message(){
		$result = $this->admin_model->getMessage(array('messageType' => 'Promotion'));
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['comment']) < 1) {
				$this->error['comment'] = 'Message is required!';
			}
			if (!$this->error) {
				if (!$result) {
					$this->admin_model->addMessage(array('message'=>$_POST['comment'], 'messageType' => 'Promotion'));
				} else {
					$this->admin_model->updateMessage($result->ID, array('message'=>$_POST['comment']));
				}
				$this->session->set_userdata('message', 'You have updated message successfully!');
				redirect('promotion-message');
			}
			$this->data += $_POST;
		} else {
			$this->data['comment'] = $result?$result->message:'';
		}
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] = 'promotion';
		$this->load->view('promotion_message', $this->data);
	}
	
	// Admin Report ===================================================================
	private function adminReport($actionMessage){
		$adminID = $this->session->userdata('userid');
		$reportData = array( 'action' => $actionMessage, 'adminID' => $adminID, 'date_time' => date('Y-m-d H:i:s') );
		return $this->admin_model->addReport($reportData);
	}
	
	// Payment Report =================================================================
	public function reports(){
		
		if (isset($_POST) && count($_POST) > 0) {
			
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
			$storeID   = $_POST['store'];
			$distID	   = $_POST['distributor'];
			$product   = $_POST['products'];
				
			$storeSaleReport = $this->payment_model->getStorePaymentReportByDist($distID, $storeID, $product, $from_date, $to_date);
			
			if($distID){ 			##### Report search By Distributor
				// Get All Stores ====
				$getAllStores = $this->agent_model->getAgents(array('parentAgentID' => $distID));
				
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
						$allSalesReport[] = $this->payment_model->getStorePaymentReportByDist($distID, $value, $product, $from_date, $to_date);
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
			
			$this->data['results'] 	   = $storeSaleReport;			
			$this->data += $_POST;
		}
		
		//Distributor
		$option_dist = array('' => '-- All --');
		$allStores	 = array();
		$getDistributors = $this->agent_model->getAllAgents(1);  		// 2= parent distributor, 4= sub distributor
		foreach ($getDistributors as $item) {
			$option_dist[trim($item->agentID)] = trim($item->company_name);
			$allStores[] = $this->agent_model->getAgents(array('a.parentAgentID' => $item->agentID), 2,0);
		}
		$this->data['option_dist'] = $option_dist;		
		
		##### change All Stores multi array to single array #####				
		$finalStoreArray =array();
		foreach ($allStores as $val) {
		    foreach($val as $val2) {
		        $finalStoreArray[] = $val2;
		     }
		}		
		if(count($finalStoreArray) > 0){
			$finalStoreArray = $this->subvalsort($finalStoreArray, 'agentID');	
		}
		
		//Stores
		$option_store = array('' => '-- All --');		
		$this->data['option_store'] = $option_store;
		
		//Products
		$option_products = array('' => '-- All --');
		$products 		 = array('Calling Card' => 'Calling Card','Pinless'=>'Pinless', 'Rtr'=> 'Topup RTR', 'Pin' => 'Topup PIN'); 
		foreach ($products as $key => $value) {
			$option_products[trim($key)] = trim($value);
		}
		$this->data['option_products'] = $option_products;		
		
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'sales_report';
		$this->load->view('admin_reports/_reports', $this->data);
	}
	public function commission_report(){
		if (isset($_POST) && count($_POST) > 0) {
			
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
			$storeID   = $_POST['store'];
			$distID	   = $_POST['distributor'];
			$product   = $_POST['products'];
				
			$storeSaleReport = $this->payment_model->getStorePaymentReportByDist($distID, $storeID, $product, $from_date, $to_date);
			
			if($distID){ 			##### Report search By Distributor
				// Get All Stores ====
				$getAllStores = $this->agent_model->getAgents(array('parentAgentID' => $distID));
				
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
						$allSalesReport[] = $this->payment_model->getStorePaymentReportByDist($distID, $value, $product, $from_date, $to_date);
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
			
			$this->data['results'] 	   = $storeSaleReport;			
			$this->data += $_POST;
		}
		
		//Distributor
		$option_dist = array('' => '-- All --');
		$allStores	 = array();
		$getDistributors = $this->agent_model->getAllAgents(1);  		// 2= parent distributor, 4= sub distributor
		foreach ($getDistributors as $item) {
			$option_dist[trim($item->agentID)] = trim($item->company_name);
			$allStores[] = $this->agent_model->getAgents(array('a.parentAgentID' => $item->agentID), 2);
		}
		$this->data['option_dist'] = $option_dist;		
		
		##### change All Stores multi array to single array #####				
		$finalStoreArray =array();
		foreach ($allStores as $val) {
		    foreach($val as $val2) {
		        $finalStoreArray[] = $val2;
		     }
		}		
		if(count($finalStoreArray) > 0){
			$finalStoreArray = $this->subvalsort($finalStoreArray, 'agentID');	
		}
		
		//Stores
		$option_store = array('' => '-- All --');		
		$this->data['option_store'] = $option_store;
		
		//Products
		$option_products = array('' => '-- All --');
		$products 		 = array('Calling Card' => 'Calling Card','Pinless'=>'Pinless', 'Rtr'=> 'Topup RTR', 'Pin' => 'Topup PIN'); 
		foreach ($products as $key => $value) {
			$option_products[trim($key)] = trim($value);
		}
		$this->data['option_products'] = $option_products;		
		
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'commission_report';
		$this->load->view('admin_reports/_reports', $this->data);	
	}
	public function sub_distributor_report(){
		
		$getAllSubDist = $this->agent_model->getAllAgents(4);  		// 2= parent distributor, 4= sub distributor
		
		if (isset($_POST) && count($_POST) > 0) {
			
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
			$storeID   = $_POST['store'];
			$distID	   = $_POST['distributor'];
			$product   = $_POST['products'];
			
			$storeSaleReport = $this->payment_model->getStorePaymentReportByDist($distID, $storeID, $product, $from_date, $to_date);
				
			if($distID){ 			##### Report search By Distributor
				// Get All Stores ====
				$getDistDetails = $this->agent_model->getAgent($distID);
				$getAllStores = $this->agent_model->getAgents(array('parentAgentID' => $distID));
				
				if($storeID){
					$storeSaleReport = $this->payment_model->getStorePaymentReportByDist($getDistDetails->parentAgentID, $storeID, $product, $from_date, $to_date);
				}else{
					//Check Valid Store Report =======
					$allStoresDist = array();
					foreach($getAllStores as $item){
						$allStoresDist[] = $item->agentID; 	
					}
					
					//Get All Reports by parent Dist =======
					$storeSaleReport = $this->payment_model->getStorePaymentReportByDist($getDistDetails->parentAgentID, $storeID, $product, $from_date, $to_date);
					$allStoreSaleReport = array();
					foreach($storeSaleReport as $items){
						$allStoreSaleReport[] = $items->agentID; 	
					}
					
					#### Filter subdist stores report from parent DIST
					$allSalesReport = array();
					foreach($allStoresDist as $key => $value){
						if(in_array($value, $allStoreSaleReport)){
							$allSalesReport[] = $this->payment_model->getStorePaymentReportByDist($getDistDetails->parentAgentID, $value, $product, $from_date, $to_date);
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
				
			}else{
				// Get Parent Distributor and Store By SubDistributor
				$allParentDistributor = array();
				$allStores 			  = array();	
				foreach($getAllSubDist as $subdist){
					$allParentDistributor[$subdist->parentAgentID] = $subdist->agentID;
					$getStoreByDist = $this->agent_model->getAgents(array('parentAgentID' => $subdist->agentID));
					foreach($getStoreByDist as $stores){
						$allStores[$stores->agentID] = $stores->parentAgentID;
					}
				}
				
				// GET ALL REPORTS =======
				$allReports = array();
				foreach($allParentDistributor as $parentID => $value){
					foreach($allStores as $storeID => $values){
						$report = $this->payment_model->getStorePaymentReportByDist($parentID, $storeID, $product, $from_date, $to_date);
						if(count($report)>0){
							$allReports[] = $report;
						}
					}					
				}			
				
				##### change Reports Multi array to single array #####				
				$final_array =array();
				foreach ($allReports as $val) {
				    foreach($val as $val2) {
				        $final_array[] = $val2;
				     }
				}
				
				##### change Reports Multi array to single array #####
				if(count($final_array) > 0){
					$storeSaleReport = $this->subvalsort($final_array, 'createdDate', 'DESC');	
				}else{
					$storeSaleReport = $final_array;
				}					
			}
			
			$this->data['results'] 	   = $storeSaleReport;			
			$this->data += $_POST;
		}
		
		//Distributor
		$option_dist = array('' => '-- All --');
		$allStores	 = array();
		foreach ($getAllSubDist as $item) {
			$option_dist[trim($item->agentID)] = trim($item->company_name);
			$allStores[] = $this->agent_model->getAgents(array('a.parentAgentID' => $item->agentID), 2);
		}
		$this->data['option_dist'] = $option_dist;		
		
		##### change All Stores multi array to single array #####				
		$finalStoreArray =array();
		foreach ($allStores as $val) {
		    foreach($val as $val2) {
		        $finalStoreArray[] = $val2;
		     }
		}		
		if(count($finalStoreArray) > 0){
			$finalStoreArray = $this->subvalsort($finalStoreArray, 'agentID');	
		}
		
		//Stores
		$option_store = array('' => '-- All --');		
		$this->data['option_store'] = $option_store;
		
		//Products
		$option_products = array('' => '-- All --');
		$products 		 = array('Calling Card' => 'Calling Card','Pinless'=>'Pinless', 'Rtr'=> 'Topup RTR', 'Pin' => 'Topup PIN'); 
		foreach ($products as $key => $value) {
			$option_products[trim($key)] = trim($value);
		}
		$this->data['option_products'] = $option_products;		
		
		// Report Type
		$option_reportType = array();
		$reportType		   = array('sale'=>'Sales Report', 'commission'=> 'Commission Report'); 
		foreach ($reportType as $key => $value) {
			$option_reportType[trim($key)] = trim($value);
		}
		$this->data['option_reportType'] = $option_reportType;
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'sub_distributor_report';
		$this->load->view('admin_reports/_reports', $this->data);
	}
	public function store_payments_report(){
		
		if (isset($_POST) && count($_POST) > 0) {
			
			$store = NULL;
			if(isset($_POST['store']) && !empty($_POST['store'])){
				$store = $_POST['store'];
			}
			
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
			$storeID   = $store;
			$distID	   = $_POST['distributor'];
				
			$storePaymentReport 	= $this->payment_model->getStoreFundsReport($distID, $storeID, $from_date, $to_date);
			$this->data['results']  = $storePaymentReport;			
			$this->data += $_POST;
		}

		//Distributor
		$option_dist = array('' => '-- All --');
		$allStores	 = array();
		$getDistributors = $this->agent_model->getAllAgents(1, 4);		// 2= parent distributor, 4= sub distributor
		foreach ($getDistributors as $item) {
			$option_dist[trim($item->agentID)] = trim($item->company_name);
			$allStores[] = $this->agent_model->getAgents(array('a.parentAgentID' => $item->agentID), 2);
		}
		$this->data['option_dist'] = $option_dist;		
		
		##### change All Stores multi array to single array #####				
		$finalStoreArray =array();
		foreach ($allStores as $val) {
		    foreach($val as $val2) {
		        $finalStoreArray[] = $val2;
		     }
		}		
		if(count($finalStoreArray) > 0){
			$finalStoreArray = $this->subvalsort($finalStoreArray, 'agentID');	
		}
		
		//Stores
		$option_store = array('' => '-- All --');		
		$this->data['option_store'] = $option_store;
		
		//Products
		$option_products = array('' => '-- All --');
		$products 		 = array('Calling Card' => 'Calling Card','Pinless'=>'Pinless', 'Rtr'=> 'Topup RTR', 'Pin' => 'Topup PIN'); 
		foreach ($products as $key => $value) {
			$option_products[trim($key)] = trim($value);
		}
		$this->data['option_products'] = $option_products;		
		
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'store_payment';
		$this->load->view('admin_reports/_reports', $this->data);
	}
	public function distributor_payments_report(){
		if (isset($_POST) && count($_POST) > 0) {
			
			$from_date = date('Y-m-d', strtotime($_POST['from_date']));
			$to_date   = date('Y-m-d', strtotime($_POST['to_date']));
			$distID	   = $_POST['distributor'];
				
			$storePaymentReport 	= $this->payment_model->getStoreFundsReport(NULL, $distID, $from_date, $to_date);
			
			if(!$distID){
				$allPaymentReport = array();
				$getDistributors  = $this->agent_model->getAllAgents(1, 4);		// 2= parent distributor, 4= sub distributor
				foreach ($getDistributors as $item) {
					$allPaymentReport[] = $this->payment_model->getStoreFundsReport(NULL, $item->agentID, $from_date, $to_date);;
				}
											
				##### change final multi array to single array #####				
				$final_array =array();
				foreach ($allPaymentReport as $val) {
				    foreach($val as $val2) {
				        $final_array[] = $val2;
				     }
				}
				
				if(count($final_array) > 0){
					$storePaymentReport = $this->subvalsort($final_array, 'createdDate', 'DESC');	
				}else{
					$storePaymentReport = $allPaymentReport;
				}
			}
			$this->data['results']  = $storePaymentReport;			
			$this->data += $_POST;
		}

		//Distributor
		$option_dist 	 = array('' => '-- All --');
		$getDistributors = $this->agent_model->getAllAgents(1, 4);		// 2= parent distributor, 4= sub distributor
		foreach ($getDistributors as $item) {
			$option_dist[trim($item->agentID)] = trim($item->company_name);
		}
		$this->data['option_dist'] = $option_dist;		
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'distributor_payment';
		$this->load->view('admin_reports/_reports', $this->data);
	}
	public function store_balance_report(){
		$getAllStores = $this->agent_model->getAllAgents();
		
		if (isset($_POST) && count($_POST) > 0) {
			if($_POST['store']){
				$getAllStoresDetails = $this->agent_model->getAgents( array('a.agentID' => $_POST['store']) );
			}else{
				$getAllStoresDetails = $this->agent_model->getAllAgents();
			}
			
			$this->data['details'] = $getAllStoresDetails;
			$this->data += $_POST;	
		}
		
		//store
		$option_store = array('' => '-- All --');
		$getAllStores = $this->agent_model->getAllAgents();
		foreach ($getAllStores as $item) {
			$option_store[trim($item->agentID)] = trim($item->company_name);
		}
		$this->data['option_store'] = $option_store;
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'store_balance_report';
		$this->load->view('admin_reports/_reports', $this->data);
	}
	public function distributor_balance_report(){
		
		if (isset($_POST) && count($_POST) > 0) {
			if($_POST['distributor']){
				$getAllDistDetails = $this->agent_model->getAgents( array('a.agentID' => $_POST['distributor']), 1 );
			}else{
				$getAllDistDetails = $this->agent_model->getAllAgents(1);
			}
			
			$this->data['details'] = $getAllDistDetails;
			$this->data += $_POST;	
		}
		
		//Distributors
		$option_dist = array('' => '-- All --');
		$getAllDist  = $this->agent_model->getAllAgents(1);
		foreach ($getAllDist as $item) {
			$option_dist[trim($item->agentID)] = trim($item->firstName.' '.$item->lastName);
		}
		$this->data['option_dist'] = $option_dist;
		
		$this->data['current_page'] 	= 'reports';
		$this->data['sub_current_page'] = 'distributor_balance_report';
		$this->load->view('admin_reports/_reports', $this->data);
	}
	
	
	// General Settings==================================================
	public function general_settings(){
			
		$getSettings = $this->admin_model->getSettings(array('settingKey' => 'rydEmail'));
		
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen(trim($_POST['rydEmail'])) < 1) {
				$this->error['rydEmail'] = 'Email is required!';
			} elseif (!$this->form_validation->valid_email($_POST['rydEmail'])) {
				$this->error['rydEmail'] = 'Email is invalid!';
			} 	

			if(!$this->error){
				$data = array('settingKey' => 'rydEmail', 'settingValue' => $_POST['rydEmail']);
				if($getSettings){
					$this->admin_model->updateSettings(array('settingKey' => 'rydEmail'), $data);
					$this->session->set_userdata('message', 'You have updated RYD email successfully!');
				}else{
					$this->admin_model->addSettings($data);
					$this->session->set_userdata('message', 'You have added RYD email successfully!');
				}
				redirect('ryd-admin');
			}
			$this->data += $_POST;
		}
		
		$this->data['rydEmail'] = ($getSettings) ? $getSettings->settingValue : '';
		$this->data['error'] 	= $this->error;
		$this->data['current_page'] 	= 'admin';
		$this->data['sub_current_page'] = 'general_settings';
		$this->load->view('settings/_settings', $this->data);
	}
	public function banner_message($bannerID){
		$result = $this->admin_model->getMessage(array('messageType' => $bannerID));
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['comment']) < 1) {
				$this->error['comment'] = 'Message is required!';
			}
			if (!$this->error) {
				$result = $this->admin_model->getMessage(array('messageType' => $_POST['bannerCat'])); 
				if (!$result) {
					$this->admin_model->addMessage(array('message'=>$_POST['comment'], 'messageType' => $_POST['bannerCat']));
				} else {
					$this->admin_model->updateMessage($result->ID, array('message'=>$_POST['comment']));
				}
				$this->session->set_userdata('message', 'You have updated Banner text successfully!');
				redirect('banner-message/'.$_POST['bannerCat']);
			}
			$this->data += $_POST;
		} else {
			$this->data['comment']   = $result?$result->message:'';
			$this->data['bannerCat'] = $bannerID;
		}
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] 	= 'admin';
		$this->data['sub_current_page'] = 'banner_message';
		$this->load->view('settings/_settings', $this->data);
	}
	public function ppn_mode(){
		$getPPnMode = $this->admin_model->getSettings(array('settingKey' => 'ppnMode'));
		$getPPNUser = $this->admin_model->getSettings(array('settingKey' => 'ppnUsername'));
		$getPPNPass = $this->admin_model->getSettings(array('settingKey' => 'ppnPassword'));
		
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen(trim($_POST['ppnMode'])) < 1) {
				$this->error['ppnMode'] = 'PPN mode is required!';
			} 
			if (strlen(trim($_POST['username'])) < 1) {
				$this->error['username'] = 'Username is required!';
			} 
			if (strlen(trim($_POST['password'])) < 1) {
				$this->error['password'] = 'Password is required!';
			} 

			if(!$this->error){
				$mode = array('settingKey' => 'ppnMode', 'settingValue' => $_POST['ppnMode']);
				$User = array('settingKey' => 'ppnUsername', 'settingValue' => $_POST['username']);
				$Pass = array('settingKey' => 'ppnPassword', 'settingValue' => $_POST['password']);
				if($getPPnMode && $getPPNUser && $getPPNPass){
					$this->admin_model->updateSettings(array('settingKey' => 'ppnMode'), $mode);
					$this->admin_model->updateSettings(array('settingKey' => 'ppnUsername'), $User);
					$this->admin_model->updateSettings(array('settingKey' => 'ppnPassword'), $Pass);
					$this->session->set_userdata('message', 'You have updated PPN details successfully!');
				}else{
					$this->admin_model->addSettings($mode);
					$this->admin_model->addSettings($User);
					$this->admin_model->addSettings($Pass);
					$this->session->set_userdata('message', 'You have added PPN details successfully!');
				}
				redirect('ppn-mode');
			}
			$this->data += $_POST;
		}
		
		$this->data['ppnMode']  = $getPPnMode?$getPPnMode->settingValue:'';
		$this->data['username'] = $getPPNUser?$getPPNUser->settingValue:'';
		$this->data['password'] = $getPPNPass?$getPPNPass->settingValue:'';		
		
		// PPN Mode ======
		$mode    = array();
		$ppnMode = array('test' => 'Test', 'live' => 'Live');
		foreach ($ppnMode as $key => $value) {
			$mode[$key] = $value;
		}
		$this->data['ppn_mode']    = $mode;
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] 	= 'admin';
		$this->data['sub_current_page'] = 'ppn_settings';
		$this->load->view('settings/_settings', $this->data);
	}
	public function pinless_mode(){
		$getPinlessMode = $this->admin_model->getSettings(array('settingKey' => 'pinlessMode'));
		$getPinlessUser = $this->admin_model->getSettings(array('settingKey' => 'pinlessUsername'));
		$getPinlessPass = $this->admin_model->getSettings(array('settingKey' => 'pinlessPassword'));
		
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen(trim($_POST['pinlessMode'])) < 1) {
				$this->error['pinlessMode'] = 'Pinless mode is required!';
			} 
			if (strlen(trim($_POST['username'])) < 1) {
				$this->error['username'] = 'Username is required!';
			} 
			if (strlen(trim($_POST['password'])) < 1) {
				$this->error['password'] = 'Password is required!';
			} 

			if(!$this->error){
				$mode = array('settingKey' => 'pinlessMode', 'settingValue' => $_POST['pinlessMode']);
				$User = array('settingKey' => 'pinlessUsername', 'settingValue' => $_POST['username']);
				$Pass = array('settingKey' => 'pinlessPassword', 'settingValue' => $_POST['password']);
				if($getPinlessMode && $getPinlessUser && $getPinlessPass){
					$this->admin_model->updateSettings(array('settingKey' => 'pinlessMode'), $mode);
					$this->admin_model->updateSettings(array('settingKey' => 'pinlessUsername'), $User);
					$this->admin_model->updateSettings(array('settingKey' => 'pinlessPassword'), $Pass);
					$this->session->set_userdata('message', 'You have updated Pinless details successfully!');
				}else{
					$this->admin_model->addSettings($mode);
					$this->admin_model->addSettings($User);
					$this->admin_model->addSettings($Pass);
					$this->session->set_userdata('message', 'You have added Pinless details successfully!');
				}
				redirect('pinless-mode');
			}
			$this->data += $_POST;
		}
		
		$this->data['pinlessMode']  = $getPinlessMode?$getPinlessMode->settingValue:'';
		$this->data['username'] = $getPinlessUser?$getPinlessUser->settingValue:'';
		$this->data['password'] = $getPinlessPass?$getPinlessPass->settingValue:'';		
		
		// PPN Mode ======
		$mode    = array();
		$ppnMode = array('test' => 'Test', 'live' => 'Live');
		foreach ($ppnMode as $key => $value) {
			$mode[$key] = $value;
		}
		$this->data['pinless_mode']    = $mode;
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] 	= 'admin';
		$this->data['sub_current_page'] = 'pinless_settings';
		$this->load->view('settings/_settings', $this->data);
	}
	public function text_message(){
		$getTextMode = $this->admin_model->getSettings(array('settingKey' => 'textMode'));
		$getTextUser = $this->admin_model->getSettings(array('settingKey' => 'textUsername'));
		$getTextPass = $this->admin_model->getSettings(array('settingKey' => 'textPassword'));
		
		if (isset($_POST) && count($_POST) > 0) {
			
			if (strlen(trim($_POST['username'])) < 1) {
				$this->error['username'] = 'Username is required!';
			} 
			if (strlen(trim($_POST['password'])) < 1) {
				$this->error['password'] = 'Password is required!';
			} 

			if(!$this->error){
				$mode = array('settingKey' => 'textMode', 'settingValue' => 'live');
				$User = array('settingKey' => 'textUsername', 'settingValue' => $_POST['username']);
				$Pass = array('settingKey' => 'textPassword', 'settingValue' => $_POST['password']);
				if($getTextMode && $getTextUser && $getTextPass){
					$this->admin_model->updateSettings(array('settingKey' => 'textMode'), $mode);
					$this->admin_model->updateSettings(array('settingKey' => 'textUsername'), $User);
					$this->admin_model->updateSettings(array('settingKey' => 'textPassword'), $Pass);
					$this->session->set_userdata('message', 'You have updated Text (Data24X7) details successfully!');
				}else{
					$this->admin_model->addSettings($mode);
					$this->admin_model->addSettings($User);
					$this->admin_model->addSettings($Pass);
					$this->session->set_userdata('message', 'You have added Text (Data24X7) details successfully!');
				}
				redirect('text-message');
			}
			$this->data += $_POST;
		}
		
		$this->data['textMode'] = $getTextMode?$getTextMode->settingValue:'';
		$this->data['username'] = $getTextUser?$getTextUser->settingValue:'';
		$this->data['password'] = $getTextPass?$getTextPass->settingValue:'';		
		
		// PPN Mode ======
		$mode    = array();
		$ppnMode = array('test' => 'Test', 'live' => 'Live');
		foreach ($ppnMode as $key => $value) {
			$mode[$key] = $value;
		}
		$this->data['textMode']    = $mode;
		
		$this->data['error'] = $this->error;
		$this->data['current_page'] 	= 'admin';
		$this->data['sub_current_page'] = 'text_message_settings';
		$this->load->view('settings/_settings', $this->data);
	}
	public function store_header(){
			
		$getSettings = $this->admin_model->getSettings(array('settingKey' => 'contactNumber'));
		
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen(trim($_POST['serviceNumber'])) < 1) {
				$this->error['serviceNumber'] = 'Customer number is required!';
			} 

			if(!$this->error){
				$data = array('settingKey' => 'contactNumber', 'settingValue' => $_POST['serviceNumber']);
				if($getSettings){
					$this->admin_model->updateSettings(array('settingKey' => 'contactNumber'), $data);
					$this->session->set_userdata('message', 'You have updated store settings successfully!');
				}else{
					$this->admin_model->addSettings($data);
					$this->session->set_userdata('message', 'You have added store settings successfully!');
				}
				redirect('store-header');
			}
			$this->data += $_POST;
		}
		
		$this->data['serviceNumber'] = ($getSettings) ? $getSettings->settingValue : '';
		$this->data['error'] 	= $this->error;
		$this->data['current_page'] 	= 'admin';
		$this->data['sub_current_page'] = 'store_header';
		$this->load->view('settings/_settings', $this->data);
	}
	// Payment Settings ================================================================================
	public function authorize_mode(){
		
		$getAuthorizeMode   = $this->admin_model->getSettings(array('settingKey' => 'authrizeMode'));
		$getAuthorizeLogin  = $this->admin_model->getSettings(array('settingKey' => 'authrizeLogin'));
		$getAuthorizeSecret = $this->admin_model->getSettings(array('settingKey' => 'authrizeSecret'));
		
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen(trim($_POST['authMode'])) < 1) {
				$this->error['authMode'] = 'Authorize mode is required!';
			} 
			if (strlen(trim($_POST['authLogin'])) < 1) {
				$this->error['authLogin'] = 'Login key is required!';
			} 
			if (strlen(trim($_POST['authSecret'])) < 1) {
				$this->error['authSecret'] = 'Secret key is required!';
			} 

			if(!$this->error){
				$mode   = array('settingKey' => 'authrizeMode', 'settingValue' => $_POST['authMode']);
				$login  = array('settingKey' => 'authrizeLogin', 'settingValue' => $_POST['authLogin']);
				$secret = array('settingKey' => 'authrizeSecret', 'settingValue' => $_POST['authSecret']);
				if($getAuthorizeMode && $getAuthorizeLogin && $getAuthorizeSecret){
					$this->admin_model->updateSettings(array('settingKey' => 'authrizeMode'), $mode);
					$this->admin_model->updateSettings(array('settingKey' => 'authrizeLogin'), $login);
					$this->admin_model->updateSettings(array('settingKey' => 'authrizeSecret'), $secret);
					$this->session->set_userdata('message', 'You have updated authorize.net details successfully!');
				}else{
					$this->admin_model->addSettings($mode);
					$this->admin_model->addSettings($login);
					$this->admin_model->addSettings($secret);
					$this->session->set_userdata('message', 'You have added authorize.net details successfully!');
				}
				redirect('authorize-mode');
			}
			$this->data += $_POST;
		}
		
		$this->data['authMode']   = $getAuthorizeMode?$getAuthorizeMode->settingValue:'';
		$this->data['authLogin']  = $getAuthorizeLogin?$getAuthorizeLogin->settingValue:'';
		$this->data['authSecret'] = $getAuthorizeSecret?$getAuthorizeSecret->settingValue:'';
		
		// Payment Mode ======
		$mode 		 = array();
		$paymentMode = array('test' => 'Test', 'live' => 'Live');
		foreach ($paymentMode as $key => $value) {
			$mode[$key] = $value;
		}
		$this->data['auth_mode']    = $mode;		
		
		$this->data['error'] 		= $this->error;
		$this->data['current_page'] = 'payment';
		$this->load->view('payment/authorize_mode', $this->data);
	}
	
	public function export_country(){
		$getRates = $this->admin_model->getCountryCodes();		
		
		header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename=vonecall_countries.csv');
	    header('Pragma: no-cache');
	    header("Expires: 0");
	
	    $outstream = fopen("php://output", "w");
		$row = 1;
	    foreach($getRates as $result)
	    {						
			fputcsv($outstream, array('CountryCode' 	  => $result->CountryCode,								
										'CountryName'  	  => $result->CountryName,
										'CountryCodeIso'  => $result->CountryCodeIso,
										'countryFlag'	  => $result->countryFlag,
										'locaNumberLength' => $result->locaNumberLength	
									 )
					);				        
	    }		
	    fclose($outstream);
		exit();
	}
	
	// Add Funds to store account
	public function add_funds_by_admin(){
		
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['store']) < 1) {
				$this->error['store'] = 'Store is required!';
			} 
			if ($_POST['fundAmount'] < 1) {
				$this->error['fundAmount'] = 'Valid Amount is required!';
			}
			
			if(!$this->error){
				$info = $this->agent_model->getAgent((int)$_POST['store']);
				$currentBalance = $info->balance;
				$newBalance		= $info->balance+$_POST['fundAmount'];
								
				// add payment details
				$chargedDiscount = 0;
				$distCommission  = 0;
				$createdDate 	 = date('Y-m-d H:i:s');
 				
 				$data = array(
					'agentID'				=> $_POST['store'],
					'transactionID'			=> 123456,
					'chargedAmount'			=> $_POST['fundAmount'],
					'chargedDiscount'		=> $chargedDiscount,
					'paymentMethodID'		=> 9,
					'enteredBy'				=> 'Admin',
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
				
				// Update balance
				$totalCredit = (float)$_POST['fundAmount'] + (float)$chargedDiscount;
				$this->agent_model->updateBalance($_POST['store'], $totalCredit);
				
				// Success Message and redirect
				$this->session->set_userdata('message', 'You have added fund to store account successfully!');
				redirect('add-funds');
			}
			
			$this->data += $_POST;	
		}
			
		//Account stores
		$option_stores = array();
		$option_stores[''] = '-- Select --';
		$account_stores = $this->agent_model->getAllAgents();
		foreach ($account_stores as $item) {
			$option_stores[$item->agentID] = $item->company_name." ($item->loginID)";
		}
		$this->data['option_stores'] = $option_stores;
		
		//Account Distributors
		$option_dist = array();
		$option_dist[''] = '-- Select --';
		$account_dist = $this->agent_model->getAllAgents(1, 4);
		foreach ($account_dist as $item) {
			$option_dist[$item->agentID] = $item->company_name." ($item->loginID)";
		}
		$this->data['option_dist'] = $option_dist;
		
		$this->data['error']		= $this->error;
		$this->data['current_page'] = 'admin';  
		$this->load->view('add_fund_by_admin', $this->data);
	}
	public function add_funds_to_dist_by_admin(){
		
		if (isset($_POST) && count($_POST) > 0) {
			if (strlen($_POST['dist']) < 1) {
				$this->error['dist'] = 'Distributor is required!';
			} 
			if ($_POST['fundAmountDist'] < 1) {
				$this->error['fundAmountDist'] = 'Valid Amount is required!';
			}
			
			if(!$this->error){
				$info = $this->agent_model->getAgent((int)$_POST['dist']);
				$currentBalance = $info->balance;
				$newBalance		= $info->balance+$_POST['fundAmountDist'];
								
				// add payment details
				$chargedDiscount = 0;
				$distCommission  = 0;
				$createdDate 	 = date('Y-m-d H:i:s');
 				
 				$data = array(
					'agentID'				=> $_POST['dist'],
					'transactionID'			=> 123456,
					'chargedAmount'			=> $_POST['fundAmountDist'],
					'chargedDiscount'		=> $chargedDiscount,
					'paymentMethodID'		=> 9,
					'enteredBy'				=> 'Admin',
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
				
				// Update balance
				$totalCredit = (float)$_POST['fundAmountDist'] + (float)$chargedDiscount;
				$this->agent_model->updateBalance($_POST['dist'], $totalCredit);
				
				// Success Message and redirect
				$this->session->set_userdata('message', 'You have added fund to distributor account successfully!');
				redirect('add-funds-to-distributor');
			}
			
			$this->data += $_POST;	
		}
			
		//Account stores
		$option_stores = array();
		$option_stores[''] = '-- Select --';
		$account_stores = $this->agent_model->getAllAgents();
		foreach ($account_stores as $item) {
			$option_stores[$item->agentID] = $item->company_name." ($item->loginID)";
		}
		$this->data['option_stores'] = $option_stores;
		
		//Account Distributors
		$option_dist = array();
		$option_dist[''] = '-- Select --';
		$account_dist = $this->agent_model->getAllAgents(1, 4);
		foreach ($account_dist as $item) {
			$option_dist[$item->agentID] = $item->company_name." ($item->loginID)";
		}
		$this->data['option_dist'] = $option_dist;
		
		$this->data['error']		= $this->error;
		$this->data['current_page'] = 'admin';  
		$this->load->view('add_fund_by_admin', $this->data);
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
	public function get_store_by_dist($distID){		
		$agents = $this->agent_model->getAgents(array('parentAgentID' => $distID), 2);
		if($distID > 0)
			$ret = "<option value=\"\">-- All --</option>";
		else
			$ret = "<option value=\"\" selected=\"selected\">-- All --</option>";
		foreach ($agents as $item) {
			$ret .= "<option value=\"{$item->agentID}\" >{$item->company_name}</option>";
		}
		echo $ret;
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
		
		// $limit = $this->uri->segment(3) != '' ? $this->uri->segment(3) : '10';
		 //$offset= $this->uri->segment(4) != '' ? $this->uri->segment(4) : '0';
	
		
		$customers = $this->customer_model->getAllCustomers(array('customerProduct'=>'pinless'));
		//echo $this->db->last_query();
		$customerArray = json_decode(json_encode($customers), True);
		
		//echo "<pre>";print_r($customerArray);
		$i=1;
		$j=1;
		foreach ($customerArray as $value) {
			    $phone=$value['loginID'];
			    $loginSession = $this->portaoneSession();
			
				//echo 'sess--';print_r($loginSession);
				//$phone='6127154579';
				
				$getAccountResponse = $this->getAccountDetailsByPortaone($phone, $loginSession);	// Get Account details from Portaone 					
				$array = json_decode(json_encode($getAccountResponse), True);
				
				//echo "<pre>";print_r($array);
				
				$i_account=$array['i_account'];
				$first_usage=$array['first_usage'];
				$last_usage=$array['last_usage'];
				$login=$array['login'];
				$issue_date=$array['issue_date'];
				$balance=$array['balance'];
				$idle_days=$array['idle_days'];
				
				$data = array(
							'i_account'			=> trim($i_account),
							'first_usage'	=> trim($first_usage),
							'last_usage'		=> trim($last_usage),
							'issue_date'	=> trim($issue_date),
							'balance'	=> trim($balance),
							'idle_days'	=> trim($idle_days)
						);
			  $result=$this->customer_model->updateByLoginId($login, $data);
			  if($result){
			  	$i++;
			  }else{
			  	$j++;
			  }
		}
		echo $i.'customers updated '.$j.'customers not updated !';die;
	}
	
	
	public function getAccountList()
	{
		require 'PortaOneWS.php';
		$loginSession = $this->portaoneSession();
			
		echo 'sess--';print_r($loginSession);
		
		$getAccountListRequest = array('offset'=>0,'limit'=>10,'i_customer'=>34,'i_batch'=>'1');
		$api 	= new PortaOneWS("AccountAdminService.wsdl");
		$result = $api->getAccountLists($getAccountListRequest, $loginSession);
		$result=$this->getAccountList();			
		echo "<pre>";print_r($result);die;
		
	}
}
?>
