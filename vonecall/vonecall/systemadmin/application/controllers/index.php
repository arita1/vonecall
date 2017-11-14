<?php
class Index extends CI_Controller {
	
	private $data = array();
	private $error = array();
	
	function __construct() {
		parent::__construct();
		$this->load->helper(array('form','format'));
		$this->load->model('admin_model');
	}
	public function index() {
		if ($this->session->userdata('role') == 'admin') {
			/*if ($this->session->userdata('usertype')=='admin') {
				redirect('destributor-manager');
			}*/
			redirect('destributor-manager');
		} else {
			redirect('login');
		}
	}
	public function login() {
		if (isset($_POST) && count($_POST) > 0) {
			
			if($this->admin_model->login($_POST['username'], md5($_POST['password']))) {
				if($this->admin_model->login($_POST['username'], md5($_POST['password'])) == 1){ 
					if($this->session->userdata('usertype') == 'sub-admin'){
						$getUserPermission = $this->admin_model->getAdminPermission($this->session->userdata('userid'));					
						$this->session->set_userdata($getUserPermission) ;
					}							
					redirect('destributor-manager');
				}else{
					$this->data['username'] = $_POST['username'];
					$this->data['message']  = 'You are not permitted to access admin portal!';
				}
			} else {
				$this->data['username'] = $_POST['username'];
				$this->data['message'] = 'Invalid ID or password!';				
			}
		} elseif(isset($_GET['user'])) {		// If Admin Redirected By SMS Portal
			$userInfo = $this->admin_model->getAdmin((int)$_GET['user']);
			if($this->admin_model->login($userInfo->username, $userInfo->password) == 1){  
				if($this->session->userdata('usertype') == 'sub-admin'){
					$getUserPermission = $this->admin_model->getAdminPermission($this->session->userdata('userid'));					
					$this->session->set_userdata($getUserPermission) ;
				}							
				redirect('destributor-manager');
			}
		} else {
			if ($this->session->userdata('role') == 'admin') {
				redirect('destributor-manager');
			}
		}
		$this->load->view('login', $this->data);
	}
	public function logout() {
		$this->admin_model->logout();
		redirect('login');
	}
	
	public function local_access_number() {
		$this->data['results'] = $this->admin_model->getAccessNumbers();
		
		$this->data['current_page'] = 'local_access_number';
		$this->load->view('local_access_number', $this->data);
	}	
}
?>