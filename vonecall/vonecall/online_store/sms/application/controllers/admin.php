<?php
class Admin extends CI_Controller {
	
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
		
	}
	
	public function text_sender(){
		
		$getSettings = $this->admin_model->getSettings(array('settingType' => 'sender'));
		if(isset($_POST) && count($_POST) > 0 ){
			if (strlen(trim($_POST['provider'])) < 1) {
                $this->error['provider'] = 'Provider is required!';
            }
			
			if(!$this->error){
				$data = array('settingType' => 'sender', 'settingParameter' => $_POST['provider']);
				
				if($getSettings){
					$updateSettings = $this->admin_model->updateSettings(array('settingType' => 'sender'), $data);
					$this->session->set_userdata('message', 'Setting updated successfully!');
				}else{
					$addSettings = $this->admin_model->addSettings($data);
					$this->session->set_userdata('message', 'Setting added successfully!');
				}
				redirect('text-sender');
			}
			$this->data += $_POST;
		}
		
		//getSenders
		$getSendersList 	= array();
		$getSendersList[''] = '-- Select --';
		$senders = $this->config->item('sms_provider');
		foreach ($senders as $key => $value) {
			$getSendersList[$key] = $value;
		}		
		
		$this->data['option_senders']   = $getSendersList;		
		$this->data['get_settings']   	= $getSettings[0];					
		$this->data['error'] 			= $this->error;		
		$this->data['current_page'] 	= 'settings';
		$this->load->view('setting_text_sender', $this->data);
	}
}
?>