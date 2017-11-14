<?php
class Index extends CI_Controller {
	
	private $data = array();
	private $error = array();
	
	function __construct() {
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->model('admin_model');
	}
	public function index() {
		if ($this->session->userdata('rep_role') == 'rep') {
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
		}elseif( isset($_GET['username']) && isset($_GET['password']) ){				// Redirect By Super Admin
			if($this->admin_model->login( $_GET['username'], $_GET['password'] )) {
				$this->session->set_userdata('redirect', true);
				redirect('home');
			} else {
				$this->data['username'] = $_POST['username'];
				$this->data['message'] = 'Invalid ID or password!';
			}
		} else {
			if ($this->session->userdata('rep_role') == 'rep') {
				redirect('home');
			}
		}
		$this->load->view('login', $this->data);
	}
	public function logout() {
		$this->admin_model->logout();
		redirect('login');
	}
	
	// Return To Admin
	public function return_to_admin(){
		$this->admin_model->logout();
		$adminUrl = $this->config->item('url'); 
		redirect("$adminUrl/systemadmin");  
	}
}
?>