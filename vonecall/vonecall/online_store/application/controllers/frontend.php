<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
class Frontend extends CI_Controller {
	
	private $error = array();
	private $data = array();
	private $agentID = 388;
	
	function __construct() {
		parent::__construct();
		//$this->roleuser->checkLogin();
		$this->load->helper(array('form','format'));
		$this->load->library('form_validation');
		$this->load->model('customer_model');  
		$this->load->model('payment_model');  
		$this->load->model('agent_model');
		$this->load->model('admin_model');
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$headlines  = $this->customer_model->getMessage(array('messageType' => 'home-headlines'));
		$this->data['headline_message'] = $headlines->message;
		
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		
	}
	public function index() {
		
		redirect(site_url('vonecall-store'));
	}
	
	
	
    public function test_portaone()
	{
		require 'PortaOneWS.php';
		$loginSession = $this->portaoneSession();
			
		echo 'sess--';print_r($loginSession);
		$phone='6619997777';
		
		$getAccountResponse = $this->getAccountDetailsByPortaone($phone, $loginSession);	// Get Account details from Portaone 					
		echo "<pre>";print_r($getAccountResponse);die;
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
 
	private function getAccountDetailsByPortaone($phone, $loginSession){
		$getAccountRequest = array('login' => $phone);
		$api 	= new PortaOneWS("AccountAdminService.wsdl");
		$result = $api->getInfo($getAccountRequest, $loginSession);
		return $result->account_info;
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
	
	public function new_login() {
		$this->load->model('customer_model');
		$this->data['current_page'] = 'login';
		$this->data['products'] = $this->customer_model->getVonecallRandomProducts();
		//$this->data['results_random'] = $this->customer_model->getRatesRandom12();
		$this->load->view('online_store/new_login', $this->data);
	}
		//===============================================Landing page strat here =========================================================================
	
	public function online_store_login() {
		$this->data['current_page'] = 'Login';
		
		if($_POST['user_type']==1){
			$this->load->model('customer_model');
			if (isset($_POST) && count($_POST) > 0) {			
				$login_by_email = $this->customer_model->getCustomersLogin(array('email'=>$_POST['email'],'password'=>md5($_POST['password']) ));
				
				$login_by_phone = $this->customer_model->getCustomersLogin(array('phone'=>$_POST['email'],'password'=>md5($_POST['password']) ));
			
				if(!empty($login_by_email)){
					
					foreach ($login_by_email as $value) {
						$customerID=$value['customerID'];
						$firstName=$value['firstName'];
						$email=$value['email'];
						$phone=$value['phone'];
					}
					
					//==============
					require 'PortaOneWS.php';
					$this->load->model('customer_model');
					$portaAuth = $this->config->item('portaone');
					// get Login Session ===============
					if ($this->session->userdata('portaoneSession')){
						$loginSession = $this->session->userdata('portaoneSession');
					} else {
						$loginSession = $this->portaoneSession();
					}
					$getAccountResponse    = $this->getAccountDetailsByPortaone($phone, $loginSession);
					if(!empty($getAccountResponse)){
						$balance=$getAccountResponse->balance;
					}else{
						$balance=0;
					}
					
					//==============
					
					
					$data=array('last_login'=>date('Y-m-d H:i:s'),'balance'=>$balance);
					
					$update = $this->customer_model->update($customerID, $data);
				
					$this->session->set_userdata('customerID', $customerID);
					$this->session->set_userdata('firstName', $firstName);
					$this->session->set_userdata('email', $email);
					$this->session->set_userdata('phone', $phone);
					
					
					redirect('vonecall-my-account');
					
				}elseif(!empty($login_by_phone)){
					foreach ($login_by_phone as $value) {
						$customerID=$value['customerID'];
						$firstName=$value['firstName'];
						$email=$value['email'];
						$phone=$value['phone'];
					}
					
					if($email==""){
						
						
						$this->session->set_userdata('customerID', $customerID);
						$this->session->set_userdata('firstName', $firstName);
						$this->session->set_userdata('email',$email);
						$this->session->set_userdata('phone',$phone);
						//==============
						require 'PortaOneWS.php';
						$this->load->model('customer_model');
						$portaAuth = $this->config->item('portaone');
						// get Login Session ===============
						if ($this->session->userdata('portaoneSession')){
							$loginSession = $this->session->userdata('portaoneSession');
						} else {
							$loginSession = $this->portaoneSession();
						}
						$getAccountResponse    = $this->getAccountDetailsByPortaone($phone, $loginSession);
						if(!empty($getAccountResponse)){
							$balance=$getAccountResponse->balance;
						}else{
							$balance=0;
						}
						
						//==============
						
						
						$data=array('last_login'=>date('Y-m-d H:i:s'),'balance'=>$balance);
					    $update = $this->customer_model->update($customerID, $data);
						redirect('vonecall-update-password/1');
					}else{
						
						$this->session->set_userdata('customerID', $customerID);
						$this->session->set_userdata('firstName', $firstName);
						$this->session->set_userdata('email', $email);
						$this->session->set_userdata('phone',$phone);
						//==============
						require 'PortaOneWS.php';
						$this->load->model('customer_model');
						$portaAuth = $this->config->item('portaone');
						// get Login Session ===============
						if ($this->session->userdata('portaoneSession')){
							$loginSession = $this->session->userdata('portaoneSession');
						} else {
							$loginSession = $this->portaoneSession();
						}
						$getAccountResponse    = $this->getAccountDetailsByPortaone($phone, $loginSession);
						if(!empty($getAccountResponse)){
							$balance=$getAccountResponse->balance;
						}else{
							$balance=0;
						}
						
						//==============
						
						
						$data=array('last_login'=>date('Y-m-d H:i:s'),'balance'=>$balance);
					    $update = $this->customer_model->update($customerID, $data);
						redirect('vonecall-my-account');
					}
					
					
				}else{
					
					$this->session->set_flashdata('error', array('message' => "<span style='color:red;'>Error! Invalid login credentials !</span>")); 
					
					redirect('vonecall-user-login');
				}
			}
		}elseif($_POST['user_type']==2){
			
				$this->load->model('admin_model');
				if (isset($_POST) && count($_POST) > 0) {			
					$login = $this->admin_model->login($_POST['email'], md5($_POST['password']));
					//print_r($login);
					//echo $this->db->last_query();die;
					if($login['status'] === true) {				
						redirect('home');
						//redirect('topup');
					} else {
						$this->data['username'] = $_POST['username'];
						if ($login['code'] == 'INVALID') {
							$this->data['message'] = 'Invalid ID or password!';
						} elseif ($login['code'] == 'DISABLED') {
							$this->data['message'] = 'Your account has been deactivated because a payment is due on your account.  Please contact your account representative or the company.';
						} else {
							$this->data['message'] = 'Invalid ID or password!';
						}
					}
				} elseif( isset($_GET['email']) && isset($_GET['password']) ){			// Redirect By Super Admin
					$login = $this->admin_model->login( $_GET['email'], $_GET['password'] );
					if($login['status'] === true) {
						$this->session->set_userdata('redirect', true);
						redirect('home');					
					} 	
				} else {
					if ($this->session->userdata('store_role') == 'reseller') {
						redirect('topup');
					}
				}
				$this->load->view('login', $this->data);
		}else{
			$this->session->set_flashdata('error', array('message' => "<span style='color:red;'>Error! Please select user type !</span>")); 
					
					redirect('vonecall-user-login');
		}
			
	}
	
	public function new_user_logout() {
		
		$this->session->unset_userdata('customerID');
		$this->session->unset_userdata('firstName');
		$this->session->unset_userdata('email');
		
		redirect('vonecall-user-login');
	}
	

	

	
	public function online_store_register() {
		
		// load from CI library
       $this->load->library('recaptcha');
		
		
      
		$this->data['widget'] =$this->recaptcha->getWidget();
		$this->data['script'] = $this->recaptcha->getScriptTag();
		
		$this->data['current_page'] = 'Register';
		$this->load->model('customer_model');
		//state
		$option_state = array();
		$option_state[''] = $this->lang->line('_select_state_');
		$states = $this->customer_model->getStates();
		foreach ($states as $item) {
			$option_state[$item->stateID] = $item->stateName;
		}
		$this->data['option_state'] = $option_state;
		
         
		
		$this->load->view('online_store/register', $this->data);
	}
	
	
	public function register_new_customer()
	{
		$this->load->library('recaptcha');
		$recaptcha = $this->input->post('g-recaptcha-response');
		if (!empty($recaptcha)) {
			$response = $this->recaptcha->verifyResponse($recaptcha);
			if (isset($response['success']) and $response['success'] === true) {
					//===========
					$phone = $_POST['newPhone'];
				    $first_name = $_POST['first_name'];
				    $last_name = $_POST['last_name'];
				    $email = $_POST['email'];
				    $password = $_POST['password'];
				    
				   /* $card_name = $_POST['card_name'];
				    $card_number = $_POST['card_number'];
				    $exp_month = $_POST['exp_month'];
				    $exp_year = $_POST['exp_year'];*/
				    //$card_ccv = $_POST['card_ccv'];
				   
				    $address = $_POST['address'];
				    $city = $_POST['city'];
				    $card_state = $_POST['card_state'];
				    $zip_code = $_POST['zip_code'];
				
								
									
					$createdDate  = date('Y-m-d H:i:s');
					$randomSubsId = trim('04156'.substr(number_format(time() * rand(),0,'',''),0,10));
					$subscriberId = preg_replace('/[\s]+/','',$randomSubsId);
					
					$data = array(
						'firstName'		=> $first_name,
						'lastName'		=> $last_name,
						'phone'			=> $phone,
						'loginID'		=> $phone,
						'password'		=> md5($password),
						'agentID'		=> $this->agentID,
						'createdDate'	=> $createdDate,
						'subscriberID'  => $subscriberId,
						'customerProduct'=> 'pinless',
						'email'	=>$email,
						'address'=>$address,
						'city'=>$city,
						'stateID'=>$card_state,
						'zipCode'=>$zip_code,
					   /*'card_name'=>$card_name,
						'card_number'=>$card_number,
						'exp_month'=>$exp_month,
						'exp_year'=>$exp_year,*/
					);
					$customerID = $this->customer_model->add($data);
					
					if($customerID){
						
					$data=array('last_login'=>date('Y-m-d H:i:s'));
					$update = $this->customer_model->update($customerID, $data);
					
					$this->session->set_userdata('customerID', $customerID);
					$this->session->set_userdata('firstName', $first_name);
					$this->session->set_userdata('email', $email);
					
						
						
						$this->session->set_flashdata('success', array('message' => "<p style='color:green;'>Your Registerd Successfully .</p>")); 
							redirect('vonecall-my-account');
					}else{
						$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Error! Please try after sometime.</p>")); 
							redirect('vonecall-register');
					}
					//==========
			}else{
				$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Invalid Captcha !</p>")); 
				redirect('vonecall-register');
			}
		}else{
			$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Please verify that your not a robot !</p>")); 
			redirect('vonecall-register');
		}
	}
	
	public function online_store() {
	$this->load->model('customer_model');
		$headlines  = $this->customer_model->getMessage(array('messageType' => 'home-headlines'));
		$this->data['headline_message'] = $headlines->message;
		$this->data['results_random'] = $this->customer_model->getRatesRandom12();	
		$this->data['current_page'] = 'Home';
		$this->load->view('online_store/home', $this->data);
	}

	public function rates() {
		$this->data['current_page'] = 'rates';
		$this->load->model('customer_model');
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
		$num_per_page = 10;//$this->config->item('num_per_page');
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
		$this->data['option_balance'] = array('5'=>'5', '10'=>'10');
		$this->data['results_city'] = $this->customer_model->getRates();
		//$this->load->view('popup_rate', $this->data);
		$this->load->view('online_store/rates', $this->data);
	}

	public function access_numbers() {
		$this->data['current_page'] = 'access_numbers';
		$this->load->model('customer_model');
		$this->data['results'] = $this->customer_model->getAccessNumbers();
		
		$this->load->view('online_store/access_numbers', $this->data);
	}

	public function how_it_works() {
		$this->data['current_page'] = 'how_it_works';
		$this->load->view('online_store/how_it_works', $this->data);
	}

	public function faq() {
		$this->data['current_page'] = 'faq';
		$this->load->view('online_store/faq', $this->data);
	}
	
	public function terms() {
		$this->data['current_page'] = 'terms';
		$this->load->view('online_store/terms', $this->data);
	}

	public function contact_us() {
		$this->data['current_page'] = 'contact_us';
		$this->load->view('online_store/contact_us', $this->data);
	}
	
	public function about_us() {
		$this->data['current_page'] = 'about_us';
		$this->load->view('online_store/about_us', $this->data);
	}
	
	public function all_top_ups() {
		$this->data['current_page'] = 'top_ups';
		$this->data['products'] = $this->customer_model->getVonecallProducts();
		//echo '<pre>'; print_r($this->data['products']);die;
		$this->load->view('online_store/top_ups', $this->data);
	}
	
	public function services() {
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results_random'] = $this->customer_model->getRatesRandom12();
		$this->load->view('online_store/services', $this->data);
	}
	
	public function mobile_top_ups() {
		$this->load->model('customer_model');
		
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		
		$template = 'usa_rtr_step_1';		
		$info 	  = $this->getAgentInfo();				
		$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'RTR', 'vp.vproductCountryCode' => 'US'));
		$carriers = $this->customer_model->array_unique_by_key($products, "ppnProductID");
		$this->data['carriers'] = $carriers; 
		
		$this->data['current_page'] = 'mobile_topup';
		
		
		$this->load->view('online_store/mobile_top_up', $this->data);
	}
	


	
	
	public function save_enqury() {
		$this->load->model('customer_model');
		$data=array(
		    'queryName' =>$_POST['name'] ,
		    'queryEmail' =>$_POST['email'] ,
		    'querySubject' =>$_POST['msg_subject'], 
		    'queryMessage' =>$_POST['message'],
		    'ip_address'=>$_SERVER['REMOTE_ADDR'] 
		);
		$results = $this->customer_model->addContactQuery($data);
		if($results>0){
			echo 'success';die;
		}else{
			echo 'error';die;
		}
	}
	
	public function getCityRatesByID() {
		$this->load->model('customer_model');
		$where=array('ID'=>$_POST['ID']);
		$results = $this->customer_model->getRates($where);
		echo json_encode($results);die;
	}
		
	public function verify_phone_number()
	{
		require 'PortaOneWS.php';
		$this->load->model('customer_model');
		$customerDetails = $this->customer_model->getCustomerByPhone($_REQUEST['phoneNumber']);
		
		$loginSession = $this->portaoneSession();
		$getAccountResponse = $this->getAccountDetailsByPortaone($_REQUEST['phoneNumber'], $loginSession);	// Get Account details from Portaone 					
		
		if(!empty($customerDetails) && !empty($getAccountResponse) ){
			//exist on portaone and exist on db
			//redirect to login page
			$redirect_rule=1;
		}elseif(empty($customerDetails) && !empty($getAccountResponse)){
			//exist on portaone but not in db
			$redirect_rule=2;
		}elseif(!empty($customerDetails) && empty($getAccountResponse)){
			//exist on db but not in portaone
			$redirect_rule=3;
		}else{
			//not in both means fresh customer
			$redirect_rule=4;
			
		}
		
		
		echo json_encode(array('isOTPexists'=>$isOTPexists,'vonecall'=>$customerDetails,'portaOne'=>$getAccountResponse,'redirect_rule'=>$redirect_rule,'smsStatus'=>$sentSMS,'pwd'=>$otp));die;
	}

	public function verify_OTP()
	{
		$otpPhone=$_REQUEST['otpPhone'];
		$otpString=$_REQUEST['otpString'];
		$where=array('otpPhone'=>$otpPhone,'otpString'=>$otpString);
		$isOTPexists=$this->customer_model->checkOtpForPhone($where);
		if(!empty($isOTPexists)){
			$deleteOTP=$this->customer_model->deleteOTP($where);
			echo json_encode(array('status'=>'true','query'=>$this->db->last_query()));die;
		}else{
			echo json_encode(array('status'=>'false','query'=>$this->db->last_query()));die;
		}
	}
	
	public function send_otp()
	{
		//=======================sent otp============================
			
			$phoneEmail = $this->getPhoneEmail($_REQUEST['phoneNumber']);
			
			
			$otp=random_string('numeric', 4);
			$dataX=array('otpString'=>$otp,'otpPhone'=>$_REQUEST['phoneNumber']);
			$where=array('otpPhone'=>$_REQUEST['phoneNumber']);
			
			$isOTPexists=$this->customer_model->checkOtpForPhone($where);
			if(empty($isOTPexists)){
				$addOTP=$this->customer_model->addOTP($dataX);
			}else{
				$updateOTP=$this->customer_model->updateOTP($_REQUEST['phoneNumber'],array('otpString'=>$otp));
			}
			if($addOTP>0 || $updateOTP){
					
				$textMessage='Your new vonecall one time password is '.$otp; 
				$subject='Vonecall OTP'; 
				$sentSMS=$this->sendText($textMessage, $subject, $phoneEmail);

			}else{
				$sentSMS=0;
				$otp=0;
			} 
			//====================sent otp==================================================
			echo json_encode(array('smsStatus'=>$sentSMS,'pwd'=>$otp));die;
	}

	
	public function verify_phone_number_porta_one()
	{
		require 'PortaOneWS.php';
		$this->load->model('customer_model');
		
		
		$loginSession = $this->portaoneSession();
		$getAccountResponse = $this->getAccountDetailsByPortaone($_REQUEST['phoneNumber'], $loginSession);	// Get Account details from Portaone 					
		
		
		if(!empty($getAccountResponse) ){
			//exist on portaone and exist on db
			$redirect_rule=1;
		}else{
			$redirect_rule=2;
		}
		
		
		echo json_encode(array('portaOne'=>$getAccountResponse,'redirect_rule'=>$redirect_rule));die;
	}
	
	
	//recharge page
	public function recharge_phone()
	{
		if($this->session->userdata('customerID')!=""){
			$this->load->model('customer_model');	
			$customerDetails = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
			foreach ($customerDetails as $value) {
					$email=$value->email;
				}
		
			/*if($email!=""){
				redirect('vonecall-my-account');
			}*/
		}else{
			
			redirect('vonecall-register');
		}
		
		$this->data['current_page'] = 'recharge phone';
		$this->load->view('online_store/recharge_phone');
	}
		
	public function goToMyAccount()
	{
		//echo json_encode(array('REQUEST'=> $_REQUEST));die; 
		
		$phone=$_REQUEST['phone'];
	
		$phoneEmail = $this->getPhoneEmail($phone);
			
			
			$passwod=random_string('alnum', 8);
			$EncPasswod=md5($passwod); 
			//$dataX=array('password'=>$EncPasswod);
			//$updatePassword=$this->customer_model->update($customerID, $dataX);
			$data=array('phone'=>$phone,'loginID'=>$phone,'password'=>$EncPasswod);
			
			
			$isCustomer=$this->customer_model->getCustomersByPhoneNumber($phone);
			if($isCustomer){
				$updatePassword=$this->customer_model->updateCustomerByPhone($phone, $data);
			}else{
				$addCustomer=$this->customer_model->add($data);
			}
			
			if(isset($addCustomer) || isset($updatePassword)){
					
				$textMessage='Your new vonecall password is '.$passwod; 
				$subject='Vonecall Update Password'; 
				$sentSMS=$this->sendText($textMessage, $subject, $phoneEmail);
				 
				
				echo json_encode(array('smsStatus'=>$sentSMS,'pwd'=>$passwod));die; 
			}else{
				
				echo json_encode(array('smsStatus'=>$sentSMS,'pwd'=>$passwod));die; 
			} 
	}
	//update_old_password
	public function update_old_password()
	{
		if($this->session->userdata('customerID')!=""){
			$this->load->model('customer_model');	
			$customerDetails = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
			foreach ($customerDetails as $value) {
					$email=$value->email;
				}
			/*if($email!=""){
				redirect('vonecall-my-account');
			}*/
		}else{
			
			redirect('vonecall-register');
		}
		$this->data['current_page'] = 'Register';
		$this->load->model('customer_model');
		//state
		$option_state = array();
		$option_state[''] = $this->lang->line('_select_state_');
		$states = $this->customer_model->getStates();
		foreach ($states as $item) {
			$option_state[$item->stateID] = $item->stateName;
		}
		$this->data['option_state'] = $option_state;
		$this->data['customerDetails']=$customerDetails;
		$this->data['current_page'] = 'update password';
		$this->load->view('online_store/update_password',$this->data);
	}
	
	public function reset_password()
	{
		$this->load->model('customer_model');
		if($_POST['new_email']!="" || $_POST['new_phone']!=""){
		
			if($_POST['new_email']!=""){
				//====send email==========	
				$customerDetails = $this->customer_model->getCustomersByEmailID($_POST['new_email']);
			    foreach ($customerDetails as $value) {
					$email=$value->email;
					$phone=$value->phone;
					$customerID=$value->customerID;
				}
				
				$passwod=random_string('alnum', 8);
				$EncPasswod=md5($passwod);
				
				$dataX=array('password'=>$EncPasswod);
				
				$updatePassword=$this->customer_model->update($customerID, $dataX);
				
				if($updatePassword){
						
					$this->load->library('parser');
					$data = array('phone' =>$phone,'customerID' =>$customerID,'email' =>$email,'password'=>$passwod);
					$subject='Vonecal password update !';
					$html=$this->parser->parse('online_store/forgotPasswordTemplate', $data);
					$check=$this->sendEmail ($html,$subject,$email,'');
					
					$this->session->set_flashdata('success', array('message' => "<p style='color:green;'>Password updated successfully, Please check your email for your new password .</p>")); 
					redirect('vonecall-forgot-password');
					
				}else{
					$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Sever error , please try after sometime !.</p>")); 
					redirect('vonecall-forgot-password');
				}
				
				
			    //==============================
			}elseif($_POST['new_phone']!=""){
				//========send sms================	
				$customerDetails = $this->customer_model->getCustomersByPhoneNumber($_POST['new_phone']);
				 
				 foreach ($customerDetails as $value) {
					$email=$value->email;
					$phone=$value->phone;
					$customerID=$value->customerID;
					$phoneEmailID=$value->phoneEmailID;
				}
				 
				if($phoneEmailID!=""){
					$phoneEmail=$value->phoneEmailID;
				}else{
					$phoneEmail = $this->getPhoneEmail($phone);
				}
				
				$passwod=random_string('alnum', 8);
				$EncPasswod=md5($passwod); 
				$dataX=array('password'=>$EncPasswod);
				$updatePassword=$this->customer_model->update($customerID, $dataX);
				
				if($updatePassword){
						
					$textMessage='Your new vonecall password is '.$passwod; 
					$subject='Vonecall Update Password'; 
					$sentSMS=$this->sendText($textMessage, $subject, $phoneEmail);
					 
					$this->session->set_flashdata('success', array('message' => "<p style='color:green;'>Password updated successfully, New password sent on your phone .</p>")); 
					redirect('vonecall-forgot-password');
					
				}else{
					$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Sever error , please try after sometime !.</p>")); 
					redirect('vonecall-forgot-password');
				} 
				 //===============================
			}else{
				$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Please enter a valid email OR phone number !.</p>")); 
				redirect('vonecall-forgot-password');
			}
		
		}else{
			
			$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Please enter a valid email OR phone number !.</p>")); 
			redirect('vonecall-forgot-password');
		}
		
		$this->data['current_page'] = 'forgot_password';
		$this->load->view('online_store/forgot_password');
	}
	
	
	//reset password by email
	/*public function reset_password()
	{
		if($_POST['new_email']!="" || $_POST['new_phone']!=""){
			$this->load->model('customer_model');	
			$customerDetails = $this->customer_model->getCustomersByEmailID($_POST['new_email']);
		
			if(!empty($customerDetails)){
				if($_POST['new_email']==""){
					
					$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Please enter an email  !.</p>")); 
			        redirect('vonecall-forgot-password');
				}else{
					foreach ($customerDetails as $value) {
						$phone=$value->phone;
						$customerID=$value->customerID;
					}
					
					$passwod=random_string('alnum', 8);
					$EncPasswod=md5($passwod);
					
					$dataX=array('password'=>$EncPasswod);
					
					$updatePassword=$this->customer_model->update($customerID, $dataX);
					
					if($updatePassword){
							
						$this->load->library('parser');
						$data = array('phone' =>$phone,'customerID' =>$customerID,'email' =>$_POST['new_email'],'password'=>$passwod);
						$subject='Vonecal password update !';
						$html=$this->parser->parse('online_store/forgotPasswordTemplate', $data);
						$check=$this->sendEmail ($html,$subject,$_POST['new_email'],'');
						
						$this->session->set_flashdata('success', array('message' => "<p style='color:green;'>Password updated successfully, Please check your email for your new password .</p>")); 
						redirect('vonecall-forgot-password');
						
					}else{
						$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Sever error , please try after sometime !.</p>")); 
						redirect('vonecall-forgot-password');
					}
				}
			}else{
				$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Email not found !.</p>")); 
			    redirect('vonecall-forgot-password');
			}
			
			
		}else{
			
			$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Please enter a valid email OR phone number !.</p>")); 
			redirect('vonecall-forgot-password');
		}
		
		$this->data['current_page'] = 'forgot_password';
		$this->load->view('online_store/forgot_password');
	}*/
	
	public function forgot_password() {
		
		$this->data['current_page'] = 'forgot password';
		
		$this->load->view('online_store/forgot_password', $this->data);
	}

	// Send Email ================
	public function sendEmail ($message, $subject, $emailTo, $emailWithText='') {
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
	
	public function update_user_profile()
	{
	//echo '<pre>';print_r($_POST);die;	
		if($this->session->userdata('customerID')!="" && $_POST['new_email']!="" 
		&& $_POST['new_password']!=""
		){
			
			$this->load->model('customer_model');
			$data = array(
						'password'=> md5($_POST['new_password']),
						'email'=>$_POST['new_email'],
						'firstName'=>$_POST['first_name'],
						'lastName'=>$_POST['last_name'],
						'address'=>$_POST['address'],
						'city'=>$_POST['city'],
						'stateID'=>$_POST['card_state'],
						'zipCode'=>$_POST['zip_code'],
						'last_login'=>date('Y-m-d H:i:s')
					);
					
					$update = $this->customer_model->update($this->session->userdata('customerID'), $data);
					if($update){
						
						$customerDetails = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
						foreach ($customerDetails as $value) {
							$customerID=$value->customerID;
							$first_name=$value->firstName;
							$email=$value->email;
						}
						
												
						$this->session->set_userdata('customerID', $customerID);
						$this->session->set_userdata('firstName', $first_name);
						$this->session->set_userdata('email', $email);
						
						$this->session->set_flashdata('success', array('message' => "<p style='color:green;'>Your Registerd Successfully .</p>")); 
						redirect('vonecall-my-account');
					}else{
						
						$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Error! Please fill the form correctly</p>")); 
							redirect('vonecall-update-password');
					}
		}else{
						$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Error! Please fill the form correctly</p>")); 
							redirect('vonecall-update-password');
					}
					
					
		$this->data['current_page'] = 'update password';
		$this->load->view('online_store/update_password');
	}

	public function my_account() {
		require 'PortaOneWS.php';	
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		$this->load->model('customer_model');
		
		
		
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		
		$portaAuth = $this->config->item('portaone');
				
		// get Login Session ===============
		if ($this->session->userdata('portaoneSession')){
			$loginSession = $this->session->userdata('portaoneSession');
		} else {
			$loginSession = $this->portaoneSession();
		}
		
		$phone=$this->data['results'][0]->phone;
		$getAccountResponse    = $this->getAccountDetailsByPortaone($phone, $loginSession);
		$this->data['login']   = $phone;
		//echo '<pre>';print_r($getAccountResponse);die;
		if(!empty($getAccountResponse)){
			$this->data['balance'] = $getAccountResponse->balance;
			$this->data['id'] = $getAccountResponse->id;
		}else{
			$this->data['balance'] ='0';
			$this->data['id'] = 'Not Available';
		}
		$this->data['wallet_balance'] =$this->customer_model->getWalletBalance($this->session->userdata('customerID'));
		
		$this->load->view('online_store/my_account', $this->data);
	}
	
		public function pinless_account() {
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		
		
		
		//=====================================
		require 'PortaOneWS.php';
		$portaAuth 	  = $this->config->item('portaone');
		$phone 		  = trim($this->data['results'][0]->phone);
		
			// get Login Session ===============
			if ($this->session->userdata('portaoneSession')){
				$loginSession = $this->session->userdata('portaoneSession');
			} else {
				$loginSession = $this->portaoneSession();
			}
		$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
		$this->data['portaOneDetails'] =$getAccountDetails;
		//======================================
		
		/*$xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
									 'i_service' => 2,					// Service ID 2 (Recharge / Transaction History)
									 'limit'	 => NULL,
									 'offset'	 => NULL,			
									 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
									 'to_date'	 => date('Y-m-d 11:59:59', strtotime($_POST['to_date'])),									 													
								);
				
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getxCdr($xcdrRequest, $loginSession);
		*/
		//======================================
		
		$this->data['wallet_balance'] =$this->customer_model->getWalletBalance($this->session->userdata('customerID'));
		$this->load->view('online_store/pinless_calling', $this->data);
	}
	
	public function charge_wallet() {
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		
		$this->data['saved_cards'] = $this->customer_model->getSavedCardByID($this->session->userdata('customerID'));
		//print_r($this->data['saved_cards']);die;
		
		
		//=====================================
		require 'PortaOneWS.php';
		$portaAuth 	  = $this->config->item('portaone');
		$phone 		  = trim($this->data['results'][0]->phone);
		
			// get Login Session ===============
			if ($this->session->userdata('portaoneSession')){
				$loginSession = $this->session->userdata('portaoneSession');
			} else {
				$loginSession = $this->portaoneSession();
			}
		$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
		$this->data['portaOneDetails'] =$getAccountDetails;
		//======================================
		
		/*$xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
									 'i_service' => 2,					// Service ID 2 (Recharge / Transaction History)
									 'limit'	 => NULL,
									 'offset'	 => NULL,			
									 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
									 'to_date'	 => date('Y-m-d 11:59:59', strtotime($_POST['to_date'])),									 													
								);
				
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getxCdr($xcdrRequest, $loginSession);
		*/
		//======================================
		$this->data['wallet_balance'] =$this->customer_model->getWalletBalance($this->session->userdata('customerID'));
		
		//state
		$option_state = array();
		$option_state[''] = $this->lang->line('_select_state_');
		$states = $this->customer_model->getStates();
		foreach ($states as $item) {
			$option_state[$item->stateID] = $item->stateName;
		}
		$this->data['option_state'] = $option_state;
		
		$this->load->view('online_store/charge_wallet', $this->data);
	}

public function transaction_history() {
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		
		$this->data['saved_cards'] = $this->customer_model->getSavedCardByID($this->session->userdata('customerID'));
		
		$this->data['txn_history'] = $this->customer_model->getTransactionHistoryByID($this->session->userdata('customerID'));
		//print_r($this->data['txn_history']);die;
		
		
		//=====================================
		require 'PortaOneWS.php';
		$portaAuth 	  = $this->config->item('portaone');
		$phone 		  = trim($this->data['results'][0]->phone);
		
			// get Login Session ===============
			if ($this->session->userdata('portaoneSession')){
				$loginSession = $this->session->userdata('portaoneSession');
			} else {
				$loginSession = $this->portaoneSession();
			}
		$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
		$this->data['portaOneDetails'] =$getAccountDetails;
		//======================================
		
		/*$xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
									 'i_service' => 2,					// Service ID 2 (Recharge / Transaction History)
									 'limit'	 => NULL,
									 'offset'	 => NULL,			
									 'from_date' => date('Y-m-d 00:00:00', strtotime($_POST['from_date'])),
									 'to_date'	 => date('Y-m-d 11:59:59', strtotime($_POST['to_date'])),									 													
								);
				
				$api 	= new PortaOneWS("AccountAdminService.wsdl");
				$result = $api->getxCdr($xcdrRequest, $loginSession);
		*/
		//======================================
		$this->data['wallet_balance'] =$this->customer_model->getWalletBalance($this->session->userdata('customerID'));
		
		$this->load->view('online_store/txn_history', $this->data);
	}
	
	
	public function speedDails() {
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		$this->data['saved_cards'] = $this->customer_model->getSavedCardByID($this->session->userdata('customerID'));
		$this->data['txn_history'] = $this->customer_model->getTransactionHistoryByID($this->session->userdata('customerID'));
		
		//=====================================
		require 'PortaOneWS.php';
		$portaAuth 	  = $this->config->item('portaone');
		$phone 		  = trim($this->data['results'][0]->phone);
		
			// get Login Session ===============
			if ($this->session->userdata('portaoneSession')){
				$loginSession = $this->session->userdata('portaoneSession');
			} else {
				$loginSession = $this->portaoneSession();
			}
		$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
		$this->data['portaOneDetails'] =$getAccountDetails;
		$this->data['wallet_balance'] =$this->customer_model->getWalletBalance($this->session->userdata('customerID'));
		//======================================
		//========speed dail=================
		$api 		  = new PortaOneWS("AccountAdminService.wsdl");
		//$getParentAccount = $this->getAccountDetailsByPortaone($phone, $loginSession);
		
		// get speed dial ==============================================
		$getPhoneBook = array( 'offset' 			  => 0, 
							   'limit' 				  => NULL, 
							   'i_account' 			  => $getAccountDetails->i_account, 
							   'phone_number_pattern' => NULL
						);
		$result = $api->getSpeeddial($getPhoneBook, $loginSession);
		//echo '<pre>';print_r($result);die;
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
				$addPhoneBook = array( 'phonebook_rec_info' => array('i_account' 	=> $getAccountDetails->i_account, 
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
		//print_r($this->data['option_type']);die;
		$this->data['results'] 			= $result->phonebook_rec_list;
		$this->data['phone'] 			= $phone;	
		$this->data['error'] 			= $this->error;	
		$this->data['current_page'] 	= 'pinless';
		$this->data['sub_current_page'] = 'pinless_speed_dial';
		
		//=======================================
		$this->load->view('online_store/speed_dials', $this->data);
	}

	public function aliasNumbers() {
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		$this->data['saved_cards'] = $this->customer_model->getSavedCardByID($this->session->userdata('customerID'));
		$this->data['txn_history'] = $this->customer_model->getTransactionHistoryByID($this->session->userdata('customerID'));
		
		//=====================================
		require 'PortaOneWS.php';
		$portaAuth 	  = $this->config->item('portaone');
		$phone 		  = trim($this->data['results'][0]->phone);
		
			// get Login Session ===============
			if ($this->session->userdata('portaoneSession')){
				$loginSession = $this->session->userdata('portaoneSession');
			} else {
				$loginSession = $this->portaoneSession();
			}
		$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
		$this->data['portaOneDetails'] =$getAccountDetails;
		$this->data['wallet_balance'] =$this->customer_model->getWalletBalance($this->session->userdata('customerID'));
		//======================================
		//========speed dail=================
		$api 		  = new PortaOneWS("AccountAdminService.wsdl");
		//$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
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
		
		//=======================================
		$this->load->view('online_store/alias_numbers', $this->data);
	}

	public function callingHistory() {
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		$this->data['saved_cards'] = $this->customer_model->getSavedCardByID($this->session->userdata('customerID'));
		$this->data['txn_history'] = $this->customer_model->getTransactionHistoryByID($this->session->userdata('customerID'));
		
		//=====================================
		require 'PortaOneWS.php';
		$portaAuth 	  = $this->config->item('portaone');
		$phone 		  = trim($this->data['results'][0]->phone);
		
			// get Login Session ===============
			if ($this->session->userdata('portaoneSession')){
				$loginSession = $this->session->userdata('portaoneSession');
			} else {
				$loginSession = $this->portaoneSession();
			}
		$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
		$this->data['portaOneDetails'] =$getAccountDetails;
		$this->data['wallet_balance'] =$this->customer_model->getWalletBalance($this->session->userdata('customerID'));
		//======================================
		//========calling history=================
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
				
				//$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
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
		
		//=======================================
		$this->load->view('online_store/calling_history', $this->data);
	}
	
	public function sms_test() {
		
		
		//$phoneEmail = $this->getPhoneEmail('6619933305');
	$phoneEmail = $this->getPhoneEmail('6619926094');
	
	$textMessage='Welcome to vonecall'; $subject='test sms'; 
	$sentSMS=$this->sendText($textMessage, $subject, $phoneEmail);
	
	print_r($sentSMS);
	}
	
	
	
	// Data24X7 ==============
	private function getPhoneEmail($phonenum){			
		//$textSettings = $this->getMode('textMode', 'textUsername', 'textPassword');
		$userName = 'rydadmin';
		$password = 'rydadmin6';
		
		if ($phonenum[0] != "1") $phonenum = "1" . $phonenum;
		$url 	= "https://api.data24-7.com/v/2.0?api=T&user=$userName&pass=$password&p1=$phonenum";
		//echo $url;
		//echo '<pre>';
		
		$result = simplexml_load_file($url) or die("feed not loading");
		
		if($result->results->result->status=='OK'){
			return $result->results->result->sms_address;
		}else{
			return  'Invalid Cellphone number';
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
		   $this->error['email_error'] = 'Message could not be sent: '.$mail->ErrorInfo;
		   return false;
		}			
		return true;		  	
    }

	public function add_account_porta() {
		require 'PortaOneWS.php';
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		
		
		if($_POST['amount']<2){
				$this->session->set_flashdata('error', array('message' => "<p style='color:red;'>Error! Amount must be greaterthen $2</p>")); 
				redirect('vonecall-update-password');
		}else{
			
			$portaAuth 	  = $this->config->item('portaone');
			$phone 		  = trim($_POST['phoneNumberPortaOne']);
			$rechargeAmount=$_POST['amount'];
				// get Login Session ===============
				if ($this->session->userdata('portaoneSession')){
					$loginSession = $this->session->userdata('portaoneSession');
				} else {
					$loginSession = $this->portaoneSession();
				}
			$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
			if(!empty($getAccountDetails)){
				redirect('vonecall-recharge-phone/'.$phone);die;
			}else{
				$addAccountResponse = $this->addNewPortaoneAccount($phone, $loginSession, $portaAuth, $rechargeAmount);
				if($addAccountResponse->i_account){
					
					$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
				}	
				//echo 'test'.'<pre>'; print_r($getAccountDetails);die;	
			}
			
			
			
		}
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		
		$this->load->view('online_store/my_account', $this->data);
	}

	public function chargeAccount() {
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	die;
		}
		/*
    $_REQUEST["accountName"] && $_REQUEST["accountName"]!="" &&
    $_REQUEST["amount"] && $_REQUEST["amount"]!="" &&
    $_REQUEST["my_card_type"] && $_REQUEST["my_card_type"]!="" &&
    $_REQUEST["cardNumber"] && $_REQUEST["cardNumber"]!="" &&
    $_REQUEST["cardExpiry"] && $_REQUEST["cardExpiry"]!="" &&
    $_REQUEST["cardCVC"] && $_REQUEST["cardCVC"]!=""  &&
    $_REQUEST["billingName"] && $_REQUEST["billingName"]!=""  &&
    $_REQUEST["billingAddress"] && $_REQUEST["billingAddress"]!=""  &&
    $_REQUEST["billingCity"] &&  $_REQUEST["billingCity"]!=""  &&
    $_REQUEST["billingState"] && $_REQUEST["billingState"]!=""  &&
    $_REQUEST["billingZip"] &&  $_REQUEST["billingZip"]!=""  &&
    $_REQUEST["billingCountry"] && $_REQUEST["billingCountry"]!=""  &&
    $_REQUEST ["agrrement"] &&  $_REQUEST ["agrrement"] !=""
		 * */
		if(is_numeric($_REQUEST['cardNumber']) && $_REQUEST['cardNumber']!=""
		   && is_numeric($_REQUEST['amount']) && $_REQUEST['amount']!=""
		   && is_numeric($_REQUEST['cardExpiry']) && $_REQUEST['cardExpiry']!=""
		   && is_numeric($_REQUEST['cardCVC']) && $_REQUEST['cardCVC']!=""
		   && is_numeric($_REQUEST['phone']) && $_REQUEST['phone']!="" &&
		    $_REQUEST["accountName"] && $_REQUEST["accountName"]!="" &&
		    $_REQUEST["amount"] && $_REQUEST["amount"]!="" &&
		    $_REQUEST["my_card_type"] && $_REQUEST["my_card_type"]!="" &&
		    $_REQUEST["cardNumber"] && $_REQUEST["cardNumber"]!="" &&
		    $_REQUEST["cardExpiry"] && $_REQUEST["cardExpiry"]!="" &&
		    $_REQUEST["cardCVC"] && $_REQUEST["cardCVC"]!=""  &&
		    $_REQUEST["billingName"] && $_REQUEST["billingName"]!=""  &&
		    $_REQUEST["billingAddress"] && $_REQUEST["billingAddress"]!=""  &&
		    $_REQUEST["billingCity"] &&  $_REQUEST["billingCity"]!=""  &&
		    $_REQUEST["billingState"] && $_REQUEST["billingState"]!=""  &&
		    $_REQUEST["billingZip"] &&  $_REQUEST["billingZip"]!=""  &&
		    $_REQUEST["billingCountry"] && $_REQUEST["billingCountry"]!=""  &&
		    $_REQUEST ["agrrement"] &&  $_REQUEST ["agrrement"] !=""
		){
			$this->session->set_flashdata('error_message', 'Please Enter All Details !');
			redirect("vonecall-charge-wallet");
			die;
		}else{
				$info = $this->getAgentInfo($this->agentID);	
				$phone=$_REQUEST['phone'];
				$createdDate  = date('Y-m-d H:i:s');
				$randomSubsId = trim('04156'.substr(number_format(time() * rand(),0,'',''),0,10));
				$subscriberId = preg_replace('/[\s]+/','',$randomSubsId);
				$rechargeAmount =$_REQUEST['amount'];
				
				if ($info->balance < $_REQUEST["amount"]) {
					$this->session->set_flashdata('error_message', 'insufficient balance in store , please contact to support !');
					redirect("vonecall-charge-wallet");
					die;
				}
				
				
			
				if($_REQUEST ["agrrement"]!=1){
					$this->session->set_flashdata('error_message', 'You have to agree the terms !');
					redirect("vonecall-charge-wallet");
					die;
				}
				
				//get customer details from database
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
					$access_number = $customerDetails->access_number;
				} else {

				// New Customer
					$phoneEmail   = $this->getPhoneEmail($phone);
				}
				//======================================
				
				
				$created_on  =date('Y-m-d H:i:s');
				if(isset($_REQUEST['my_card_type']) && $_REQUEST['my_card_type']!="" && $_REQUEST['my_card_type']=="1"
				&& isset($_REQUEST['my_cvv']) && $_REQUEST['my_cvv']!="" )
				{//if saved card
					
					$sa_card_id=$_REQUEST['my_card_value'];
					$card_cvv=$_REQUEST['my_cvv'];
					$save_card_data=$this->customer_model->getSavedCardByCardID($sa_card_id);
					$card_number=$save_card_data[0]->sa_card_number;
					$sa_card_exp=$save_card_data[0]->sa_card_exp;
					
					
					//================
					// Authorize.net lib
					$this->load->library('authorize_net');
			
					$auth_net = array(
						'x_card_num'			=> $card_number, // Visa
						'x_exp_date'			=> $sa_card_exp,
						'x_card_code'			=> $card_cvv,
						'x_description'			=> 'Vonecall store charge account from web',
						'x_amount'				=> $_REQUEST['amount'],
						'x_first_name'			=> $_REQUEST['accountName'],
						//'x_last_name'			=> 'Doe',
						'x_address'				=> $_REQUEST['billingAddress'],
						'x_city'				=> $_REQUEST['billingCity'],
						'x_state'				=> $_REQUEST['billingState'],
						'x_zip'					=> $_REQUEST['billingZip'],
						'x_country'				=> $_REQUEST['billingCountry'],
						'x_phone'				=> $_REQUEST['phone'],
						//'x_email'				=> 'test@example.com',
						'x_customer_ip'			=> $this->input->ip_address(),
						);
						$this->authorize_net->setData($auth_net);
				
						// Try to AUTH_CAPTURE
						if( $this->authorize_net->authorizeAndCapture() )
						{
							/*echo '<h2>Success!</h2>';
							cho '<p>Transaction ID: ' . $this->authorize_net->getTransactionId() . '</p>';
							echo '<p>Approval Code: ' . $this->authorize_net->getApprovalCode() . '</p>';
							*/
							
							
						
						$responseArray = array(
										'x_card_num'=> $card_number, // Visa
										'x_description'=> 'Vonecall store charge account from web',
										'x_amount'=> $_REQUEST['amount'],
										'x_first_name'=> $_REQUEST['accountName'],
										'x_country'=> $_REQUEST['billingCountry'],
										'x_phone'=> $_REQUEST['phone'],
										'x_customer_ip'=> $this->input->ip_address(),
										'customerID'=>$_REQUEST['customerID'],
										'transactionID'=>$this->authorize_net->getTransactionId(),
										'approvalCode'=>$this->authorize_net->getApprovalCode(),
										'createdOn'=>$created_on,
										);
							
						$addPinlessTxn=$this->customer_model->addPinlessTxn($responseArray);	
							if($addPinlessTxn){
								//=============================
								
								require 'PortaOneWS.php';
								$this->load->model('customer_model');
								
								$loginSession = $this->portaoneSession();
								$getAccountResponse = $this->getAccountDetailsByPortaone($_REQUEST['phone'], $loginSession);	// Get Account details from Portaone 					
								if(!empty($getAccountResponse) ){
									//exist on portaone and exist on db
									$api = new PortaOneWS("AccountAdminService.wsdl");
					                $updateAccountRequest = array('i_account' => $getAccountResponse->i_account,										
																  'action' 	  => 'Manual payment',
															 	  'amount'	  => $_REQUEST['amount']																				
													);			
									$result = $api->rechargeAccount($updateAccountRequest, $loginSession);
									//echo '<pre>';print_r($result);die;
									
									
									//==============================
									// Get Pinless product ======
										$products = $this->customer_model->getVProductsByCommission(array('ac.agentID' => $this->agentID, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Pinless'));
										
										// Get Product Details by ProductID					
										$getCommission = $this->agent_model->getCommissionByProductID($this->agentID, $products[0]->vproductID);
										
										// calculate RYD Commission
										$rydCommission = 0;	
										if($getCommission)			
											$rydCommission = $_REQUEST['amount'] * $getCommission->vproductAdminCommission / 100;
														
										//calculate store commission
										$storeCommission = 0;
										if ($getCommission) {
											$storeCommission = $_REQUEST['amount'] * $getCommission->commissionRate / 100;
										}
										
										//calculate Distributor commission
										$distCommission = $_REQUEST['amount'] * ($getCommission->vproducttotalCommission - ($getCommission->commissionRate + $getCommission->vproductAdminCommission)) / 100;
										
										//Check Subdist Parent Account
										$getParentDistributor = $this->getParentDistInfo($info->parentAgentID);
										$parentAgentID = $info->parentAgentID;
										if($getParentDistributor->agentTypeID == 4){
											$parentAgentID = $getParentDistributor->parentAgentID;
										}
									//============================
									//echo '<br>'.$rydCommission.'<br>'.$storeCommission.'<br>'.$distCommission.'<br>'.$parentAgentID;
									//die;
									
									
									//insert to payment
									$dataPayment = array(
										'customerID'			=> $customerID,
										'seqNo'					=> $this->payment_model->getSeqNo($customerID),
										'paymentMethodID'		=> $this->config->item('payment_credit_card'),
										'chargedAmount'			=> $_REQUEST['amount'],
										'enteredBy'				=> 'Customer'.'-'.'online store',
										'chargedBy'				=> 'ONLINE STORE',
										'productID'				=> $getCommission->vproductID,
										'agentID'				=> $this->agentID,
										'storeCommission'		=> $storeCommission,
										'accountRepID'			=> $parentAgentID,
										'accountRepCommission'	=> $distCommission,
										'comment'				=> 'Pinless:online store ',
										'createdDate'			=> date('Y-m-d H:i:s'),
										'adminCommission'		=> $rydCommission
									);				
									$this->payment_model->add($dataPayment);
									
									//update balance agent
									$totalCredit = -$_REQUEST['amount'] + $storeCommission;
									$this->agent_model->updateBalance($this->agentID, $totalCredit);
									
									if($access_number!=''){
										
										$message = sprintf($this->config->item('access_sms_message'), $rechargeAmount, format_phone_number($access_number));
													
										/** Get Access Number and send in text END**/
										
										// Send Text to Pinless Customer =========================
										$subject	 = "Pinless Recharge";
										if($phoneEmail);
											$this->sendText($message, $subject, $phoneEmail);
										//========================================================
										
									}else{
										
										/** Get Access Number and send in text **/
										$areaCode 		 = substr($phone,0,3);
										$language		 = 'English';
										$message		 = '';
										$getAccessNumber = $this->customer_model->getAccessNumberForPinless("AccessNumber like '%$areaCode%' AND access_lang like '%$language%'");
										 $updateCustomer=$this->customer_model->update($customerID,array('access_number'=>format_phone_number($getAccessNumber->AccessNumber)));
										if($getAccessNumber)
										    
											$message = sprintf($this->config->item('access_sms_message'), $rechargeAmount, format_phone_number($getAccessNumber->AccessNumber));
										else{
											$getAccessNumber = $this->customer_model->getAccessNumberForPinless("city like '%default%' AND access_lang like '%$language%'");
											$message = sprintf($this->config->item('access_sms_message'), $rechargeAmount, format_phone_number($getAccessNumber->AccessNumber));
										}				
										/** Get Access Number and send in text END**/
										
										// Send Text to Pinless Customer =========================
										$subject	 = "Pinless Recharge";
										if($phoneEmail);
											$this->sendText($message, $subject, $phoneEmail);
										   
										//========================================================
										}
										
										
									
									
									if(isset($result->error) && $result->error!=""){
										$this->session->set_flashdata('error_message','PortaOne -'.$result->error);
										redirect("vonecall-charge-wallet");
										die;
									}else{
										$updateBalacne=$this->customer_model->updateBalance($_REQUEST['customerID'],$_REQUEST['amount']);	
										$this->session->set_flashdata('success_message', 'Pinless  recharged successfully.');
										//$this->session->set_flashdata('success_message',$message);
										redirect("vonecall-charge-wallet");
										die;
									}
									
								}else{
									$portaAuth 	  = $this->config->item('portaone');
									$addAccountResponse = $this->addNewPortaoneAccount($_REQUEST['phone'], $loginSession, $portaAuth,$_REQUEST['amount']);
									if($addAccountResponse->i_account){
										
										//$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
										
										
										$updateBalacne=$this->customer_model->updateBalance($_REQUEST['customerID'],$_REQUEST['amount']);	
										$cardArray=array('sa_card_number'=>$card_number,
														'sa_card_exp'=>$sa_card_exp,
														'customerID'=>$_REQUEST['customerID']
														);
										$save_my_card=$this->customer_model->saveMyCard($cardArray);	
										
										
										
										$this->session->set_flashdata('success_message', 'Pinless  recharged successfully.');
										redirect("vonecall-charge-wallet");
										die;
									}else{
										$this->session->set_flashdata('error_message','PortaOne -'.$result->error);
										redirect("vonecall-charge-wallet");
										die;
									}
								}
								
							}else{
								$this->session->set_flashdata('error_message','Server down, please try after sometime !');
								redirect("vonecall-charge-wallet");
								die;
							}
						}
						else
						{
							//echo '<h2>Fail!</h2>';
							// Get error
							//echo '<p>' . $this->authorize_net->getError() . '</p>';
							// Show debug data
							//$this->authorize_net->debug();
							//die;
							
							
							$this->session->set_flashdata('error_message',$this->authorize_net->getError());
							redirect("vonecall-charge-wallet");
							die;
							
						}
					
					//==================
				}else{
					//for new card
					// Authorize.net lib
					$this->load->library('authorize_net');
			
					$auth_net = array(
						'x_card_num'			=> $_REQUEST['cardNumber'], // Visa
						'x_exp_date'			=> $_REQUEST['cardExpiry'],
						'x_card_code'			=> $_REQUEST['cardCVC'],
						'x_description'			=> 'Vonecall store charge account from web',
						'x_amount'				=> $_REQUEST['amount'],
						'x_first_name'			=> $_REQUEST['accountName'],
						//'x_last_name'			=> 'Doe',
						'x_address'				=> $_REQUEST['billingAddress'],
						'x_city'				=> $_REQUEST['billingCity'],
						'x_state'				=> $_REQUEST['billingState'],
						'x_zip'					=> $_REQUEST['billingZip'],
						'x_country'				=> $_REQUEST['billingCountry'],
						'x_phone'				=> $_REQUEST['phone'],
						//'x_email'				=> 'test@example.com',
						'x_customer_ip'			=> $this->input->ip_address(),
						);
						$this->authorize_net->setData($auth_net);
				
						// Try to AUTH_CAPTURE
						if( $this->authorize_net->authorizeAndCapture() )
						{
							$responseArray = array(
										'x_card_num'=>$_REQUEST['cardNumber'], // Visa
										'x_description'=> 'Vonecall store charge account from web',
										'x_amount'=> $_REQUEST['amount'],
										'x_first_name'=> $_REQUEST['accountName'],
										'x_country'=> $_REQUEST['billingCountry'],
										'x_phone'=> $_REQUEST['phone'],
										'x_customer_ip'=> $this->input->ip_address(),
										'customerID'=>$_REQUEST['customerID'],
										'transactionID'=>$this->authorize_net->getTransactionId(),
										'approvalCode'=>$this->authorize_net->getApprovalCode(),
										'createdOn'=>$created_on,
										);
							
						$addPinlessTxn=$this->customer_model->addPinlessTxn($responseArray);	
							if($addPinlessTxn){
								//=============================
								
								require 'PortaOneWS.php';
								$this->load->model('customer_model');
								
								$loginSession = $this->portaoneSession();
								$getAccountResponse = $this->getAccountDetailsByPortaone($_REQUEST['phone'], $loginSession);	// Get Account details from Portaone 					
								if(!empty($getAccountResponse) ){
									//exist on portaone and exist on db
									$api = new PortaOneWS("AccountAdminService.wsdl");
					                $updateAccountRequest = array('i_account' => $getAccountResponse->i_account,										
																  'action' 	  => 'Manual payment',
															 	  'amount'	  => $_REQUEST['amount']																				
													);			
									$result = $api->rechargeAccount($updateAccountRequest, $loginSession);
									//echo '<pre>';print_r($result);die;
									if(isset($result->error) && $result->error!=""){
										$this->session->set_flashdata('error_message','PortaOne -'.$result->error);
										redirect("vonecall-charge-wallet");
										die;
									}else{
										$updateBalacne=$this->customer_model->updateBalance($_REQUEST['customerID'],$_REQUEST['amount']);	
										
										$cardArray=array('sa_card_number'=>$_REQUEST['cardNumber'],
														'sa_card_exp'=>$_REQUEST['cardExpiry'],
														'customerID'=>$_REQUEST['customerID']
														);
										$save_my_card=$this->customer_model->saveMyCard($cardArray);	
										
										$this->session->set_flashdata('success_message', 'Pinless  recharged successfully.');
										redirect("vonecall-charge-wallet");
										die;
									}
									
								}else{
									$portaAuth 	  = $this->config->item('portaone');
									$addAccountResponse = $this->addNewPortaoneAccount($_REQUEST['phone'], $loginSession, $portaAuth,$_REQUEST['amount']);
									if($addAccountResponse->i_account){
										
										//$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
										
										$updateBalacne=$this->customer_model->updateBalance($_REQUEST['customerID'],$_REQUEST['amount']);	
										$this->session->set_flashdata('success_message', 'Pinless  recharged successfully.');
										redirect("vonecall-charge-wallet");
										die;
									}else{
										$this->session->set_flashdata('error_message','PortaOne -'.$result->error);
										redirect("vonecall-charge-wallet");
										die;
									}
								}
								
							}else{
								$this->session->set_flashdata('error_message','Server down, please try after sometime !');
								redirect("vonecall-charge-wallet");
								die;
							}
							
						}
						else
						{
							$this->session->set_flashdata('error_message',$this->authorize_net->getError());
							redirect("vonecall-charge-wallet");
							die;
							
						}
				}
		}
		
		
		
	}


	public function topup_usa_rtr_recharge($productID){
		
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	die;
		}
		
		
		
		
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
						$this->data['warning'] = 'This phone is not a registered number ';
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
						'enteredBy'				=> 'Customer'.'-'.'online store',
						'chargedBy'				=> 'ONLINE STORE',
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
		$this->data['customerResults'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		$this->data['results'] 		= $results[0];
		$this->data['error']		= $this->error;
		$this->data['current_page'] = 'topup';
		$this->load->view('online_store/'.$template, $this->data);	
	}
	

	private function getParentDistInfo($distID) {
		//agent info
		$info = $this->agent_model->getAgent($distID);		
		return $info;
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

private function getAgentInfo() {
		//agent info
			$this->load->model('agent_model');
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

	
}
?>
