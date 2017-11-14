<?php
class Index extends CI_Controller {
	
	private $data = array();
	private $error = array();
	
	function __construct() {
		
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->model('admin_model');
		$this->load->model('contact_model');
		
		if($this->session->userdata('message')){
			$this->data['message'] = $this->session->userdata('message');
			$this->session->unset_userdata('message');
		}		
	}
	public function index() {
		if ($this->session->userdata('sms_role') == 'admin') {
			redirect('home');
		} else {
			redirect('login');
		}
	}
	public function login() {
		if (isset($_POST) && count($_POST) > 0) {
			if($this->admin_model->login($_POST['username'], md5($_POST['password']))) {
				redirect('home');
			} else {
				$this->data['username'] = $_POST['username'];
				$this->data['message'] = 'Invalid ID or password!';
			}
		} elseif(isset($_GET['adminlogin']) && isset($_GET['user'] )){ 		// If Redirect from System admin
			$userInfo = $this->admin_model->getAdmin((int)$_GET['user']);
			$this->session->set_userdata('sms_role', 'admin');
			$this->session->set_userdata('sms_username', $userInfo->username);
			$this->session->set_userdata('sms_usertype', $userInfo->adminTypeCode);
			$this->session->set_userdata('sms_userid', $userInfo->adminID);
			$this->session->set_userdata('sms_logindatetime', date('Y-m-d H:i:s'));
			$this->session->set_userdata('redirect', true);
			redirect('send-text-message');
					
		}else {
			if ($this->session->userdata('sms_role') == 'admin') {
				redirect('home');
			}
		}
		$this->load->view('login', $this->data);
	}
	public function logout() {
		$this->admin_model->logout();
		redirect('login');
	}
	
	public function home(){
		$this->roleuser->checkLogin();		
		$userInfo = $this->getUserInfo();
		$this->data['info'] = $userInfo;
		
		$this->data['sub_title'] 		= 'Account Summary';
		$this->data['current_page'] 	= 'dashboard';
		$this->data['sub_current_page'] = 'home';
		$this->load->view('dashboard', $this->data);
	}
	
	// Send Text Message ===========================================================================
	public function send_text(){
		require 'class.phpmailer.php';
		$this->load->library('twilio');
		if(isset($_POST) && count($_POST) > 0 ){
			if (strlen(trim($_POST['group'])) < 1) {
                $this->error['group'] = 'Group is required!';
            }
			if (strlen(trim($_POST['text_message'])) < 1) {
                $this->error['text_message'] = 'Message required!';
            }elseif(strlen(trim($_POST['text_message'])) > 160){
            	 $this->error['text_message'] = 'Only 160 characters allowed!';
            }

			if(!$this->error){
				$getContacts = $this->contact_model->getAllContacts(array('groupID' => $_POST['group'], 'optOut'=>null));
				$subject = 'Vonecall';				
				if($getContacts){
					foreach($getContacts as $contacts){
						$optOutCode = date(strtotime($contacts->date_time));
						$optoutText = 'To opt out click on '.$this->shortUrl(site_url('optout-contact?contactid='.base64_encode($optOutCode)));
						$textMessage = $_POST['text_message'].'. '.$optoutText;
						$this->sendText($textMessage, $subject, $contacts->contactNumberEmail, $contacts->contactNumber);		
					}
					$this->session->set_userdata('message', 'Text Successfully Sent');
					redirect('send-text-message');
				}else{
					$this->data['warning'] = 'Record not found in this Group';
				}								
			}
			$this->data += $_POST;
		}

		//getGroups
		$groupList 	= array();
		$groupList[''] = '-- Select --';
		$groups = $this->contact_model->getAllGroups();
		foreach ($groups as $item) {
			$groupList[$item->groupID] = $item->groupName;
		}		
		$this->data['option_groups']  = $groupList;
		
		$this->data['error'] 			= $this->error;
		$this->data['current_page'] 	= 'send_text';
		$this->load->view('send_text', $this->data);
	}
	
	// Private Functions ===========================================================================
	private function getUserInfo() {
		//customer info			
		$info = $this->admin_model->getAdmin((int)$this->session->userdata('sms_userid'));			
		return $info;
	}
	
	private function sendText ($message, $subject = "", $email_to = "", $mobile="") {
		
		$getSenders = $this->admin_model->getSettings(array('c.settingType'=>'sender'));
		
		if($getSenders[0]->settingParameter == 'ses'){						// If PHP sender is Simple Email Service (AWS)
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
			$mail->AddAddress($email_to);							 	 // Receiver Email			
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
			echo "Message has been sent";
			return true;
			
		}elseif($getSenders[0]->settingParameter == 'twilio'){				// If PHP sender is Twilio
			//require(APPPATH.'libraries/Twilio.php');
			
			if ($mobile[0] != "1") $mobile = "1" . $mobile;
			$from 	  = '+1 424-219-7974';
			$to 	  = $mobile;				
			$response = $this->twilio->sms($from, $to, $message);	
	
			if($response->IsError)
				echo 'Error: ' . $response->ErrorMessage;
			else
				echo 'Sent message to ' . $to;		
			return true;	
		}else{																// If PHP sender is PHP Mail Function
			$mail = new PHPMailer();

			$mail->IsSMTP();                                      // set mailer to use SMTP
			$mail->SMTPAuth 	= true;     // turn on SMTP authentication
			$mail->SMTPSecure 	= "tls";
			$mail->Host 		= $this->config->item('smtp_host');  // specify main and backup server
			$mail->Port 		= $this->config->item('smtp_port');  // SMTP Port
			$mail->Username 	= $this->config->item('smtp_user');  // SMTP username
			$mail->Password 	= $this->config->item('smtp_pass');  // SMTP password			
			$mail->From 		= $this->config->item('smtp_user');	 // From Email
			$mail->FromName 	= $this->config->item('site_name');	 // From Name
			$mail->AddAddress($email_to);							 // Receiver Email			
			$mail->WordWrap 	= 50;                                // set word wrap to 50 characters
			//$mail->IsHTML(true);                                   // set email format to HTML
			
			$mail->Subject = $subject;
			$mail->Body    = $message;						
			if(!$mail->Send())
			{
			   echo "Message could not be sent. <p>";
			   echo "Mailer Error: " . $mail->ErrorInfo;
			   exit;
			}			
			echo "Message has been sent";			   
	    }      	
		return true;	
    }

	private function shortUrl($url)  {		// Get Tiny Url  
	$ch = curl_init();  
	$timeout = 5;  
	curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
	$data = curl_exec($ch);  
	curl_close($ch);  
	return $data;  
}
}
?>