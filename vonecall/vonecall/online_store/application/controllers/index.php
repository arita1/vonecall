<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
class Index extends CI_Controller {
	
	private $data = array();
	private $error = array();
	
	function __construct() { 
		parent::__construct();
		$this->load->helper(array('form','format'));
		$this->load->model('customer_model');
		$headlines  = $this->customer_model->getMessage(array('messageType' => 'home-headlines'));
		$this->data['headline_message'] = $headlines->message;
	}
	public function index() {
		if ($this->session->userdata('store_role') == 'reseller') {
			redirect('home');
		} else {
			echo'<script> window.location.href = "http://www.vonecall.com/vonecall";</script>';
		}
	}
	function language($lang) {   
		$this->session->set_userdata('language', $lang);
	}
	public function login() {
		$this->load->model('admin_model');
		if (isset($_POST) && count($_POST) > 0) {			
			$login = $this->admin_model->login($_POST['username'], md5($_POST['password']));
			// print_r($login);
			// echo $this->db->last_query();
			if($login['status'] === true) {	
					$this->session->set_userdata('redirect', true);
			 //print_r($this->session->all_userdata());
					$this->admin_model->set_session(1);
					
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
		} elseif( isset($_GET['username']) && isset($_GET['password']) ){			// Redirect By Super Admin
			$login = $this->admin_model->login( $_GET['username'], $_GET['password'] );
			if($login['status'] === true) {
				$this->session->set_userdata('redirect', true);
				redirect('home');					
			} 	
		} else {
			if ($this->session->userdata('store_role') == 'reseller') {
				redirect('topup');
			}
		}
		echo'<script> window.location.href = "http://www.vonecall.com/vonecall/#store_logout";</script>';
		//$this->load->view('login', $this->data);
	}
	public function logout() {
		$this->load->model('admin_model');
	//	print_r($this->session->all_userdata());
		$this->admin_model->set_session(0);
		$this->admin_model->logout();
		
		echo'<script> window.location.href = "http://www.vonecall.com/vonecall/#store_logout";</script>';
	}
	public function terms_and_conditions() {
		$this->data['current_page'] = 'home';
		$this->load->view('terms_and_conditions', $this->data);
	}
	public function privacy_policy() {
		$this->data['current_page'] = 'home';
		$this->load->view('privacy_policy', $this->data);
	}
	public function access_number() {
		$this->load->model('customer_model');
		$this->data['results'] = $this->customer_model->getAccessNumbers();
		$this->load->view('popup_access_number', $this->data);
	}
	
	public function rate() {
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
		$num_per_page = 20;//$this->config->item('num_per_page');
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
		$this->data['option_balance'] = array('5'=>'5', '10'=>'10', '15'=>'15', '20'=>'20', '30'=>'30');
		
		$this->load->view('popup_rate', $this->data);
	}
	public function commission_rates() {
		$this->load->model('agent_model');  
		$info = $this->agent_model->getAgent((int)$this->session->userdata('store_userid'));
	
		//$this->data['commission_rate']	= $this->agent_model->getCommissions($this->agentID);
		$num_per_page = 20;
		$paging_data = array(
			'limit'   => $num_per_page,
			'current' => (isset($_POST['page']) && $_POST['page'] > 1) ? $_POST['page'] : 1,
			'total'   => $this->agent_model->getTotalProductsByAgent((int)$this->session->userdata('store_userid'))
		);
		$this->data['paging']  = $this->paging->do_paging_customer($paging_data);
		$offset 			   = ($paging_data['current'] - 1) * $num_per_page;
		
		$this->data['commission_rate'] = $this->agent_model->getAllProductsByAgent((int)$this->session->userdata('store_userid'), $num_per_page, $offset);
		
		//$this->data['current_page'] = 'home';
		$this->load->view('popup_commission_rates', $this->data);
	}
	
	public function contact() {
		$this->data['option_category'] = array(''=>'-- Select --','Technical'=>'Technical','Billing'=>'Billing','Error Message'=>'Error Messages','Other'=>'Other');
		$this->data['current_page'] = 'help';
		$this->load->view('contact', $this->data);
	}
	
	//Employee
	public function instruction() {
		$this->load->model('customer_model');
		$this->data['results'] = $this->customer_model->getInstructions();
		
		$this->data['current_page'] = 'help';
		$this->load->view('instruction', $this->data);
	}
	
	// Return To Admin
	public function return_to_admin(){
		$this->load->model('admin_model');
		$this->admin_model->logout();
		$adminUrl = $this->config->item('url'); 
		redirect("$adminUrl/systemadmin");  
	}




	
}
?>
