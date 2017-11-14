<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
class Frontend extends CI_Controller {
	
	private $error = array();
	private $data = array();
	private $agentID = 0;
	
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
					}
					
					$data=array('last_login'=>date('Y-m-d H:i:s'));
					$update = $this->customer_model->update($customerID, $data);
					
					$this->session->set_userdata('customerID', $customerID);
					$this->session->set_userdata('firstName', $firstName);
					$this->session->set_userdata('email', $email);
					redirect('vonecall-my-account');
					
				}elseif(!empty($login_by_phone)){
					foreach ($login_by_phone as $value) {
						$customerID=$value['customerID'];
						$firstName=$value['firstName'];
						$email=$value['email'];
					}
					
					$data=array('last_login'=>date('Y-m-d H:i:s'));
					$update = $this->customer_model->update($customerID, $data);
					
					$this->session->set_userdata('customerID', $customerID);
					$this->session->set_userdata('firstName', $firstName);
					$this->session->set_userdata('email', $email);
					redirect('vonecall-my-account');
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
					$phone = $_POST['phone'];
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
		$this->data['results'] = $this->customer_model->getRates();
		$this->data['results_random'] = $this->customer_model->getRatesRandom();
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
	

	public function forgot_password() {
		
		$this->data['current_page'] = 'forgot password';
		
		$this->load->view('online_store/forgot_password', $this->data);
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
		
		//echo "<pre>";print_r($getAccountResponse);die;
		
		if(!empty($customerDetails) && !empty($getAccountResponse) ){
			//exist on portaone and exist on db
			$redirect_rule=1;
		}elseif(empty($customerDetails) && !empty($getAccountResponse)){
			//exist on portaone but not in db
			$redirect_rule=2;
		}elseif(!empty($customerDetails) && empty($getAccountResponse)){
			$redirect_rule=3;
		}else{
			$redirect_rule=4;
		}
		
		
		echo json_encode(array('vonecall'=>$customerDetails,'portaOne'=>$getAccountResponse,'redirect_rule'=>$redirect_rule));die;
	}
		
	public function goToMyAccount()
	{
		$customerID=$_REQUEST['customerID'];
		$this->load->library('session');
		$this->session->set_userdata(array(
		                            'customerID'=> $customerID
		                    ));
		
		echo json_encode(array('customerID'=> $customerID));die;
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
			if($email!=""){
				redirect('vonecall-my-account');
			}
		}else{
			
			redirect('vonecall-register');
		}
		
		$this->data['current_page'] = 'update password';
		$this->load->view('online_store/update_password');
	}
	
	public function update_user_profile()
	{
		
		if($this->session->userdata('customerID')!="" && $_POST['new_email']!="" 
		&& $_POST['new_password']!=""
		){
			
			$this->load->model('customer_model');
			$data = array(
						'password'=> md5($_POST['new_password']),
						'email'=>$_POST['new_email'],
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
		if($this->session->userdata('customerID')==""){
		    redirect('vonecall-store');	
		}
		$this->load->model('customer_model');
		$this->data['current_page'] = 'services';
		$this->data['results'] = $this->customer_model->getCustomersByID($this->session->userdata('customerID'));
		
		$this->load->view('online_store/my_account', $this->data);
	}
	
	
	public function sms_test() {
		
		
		$phoneEmail = $this->getPhoneEmail('8109592693');
	
	print_r($phoneEmail);
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
		//print_r($result);die;
		if($result->results->result->status=='OK'){
			return $result->results->result->sms_address;
		}else{
			return  'Invalid Cellphone number';
		}	
	}
	
}
?>
