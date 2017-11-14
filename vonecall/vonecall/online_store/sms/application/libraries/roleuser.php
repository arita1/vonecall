<?php
class roleuser {
	// Contructor
	function roleuser() {
		
	}
	public function checkLogin() {
		$CI = & get_instance();
        $CI->load->library('session');
        $CI->load->helper('url');
        if ($CI->session->userdata('sms_role') == 'admin' && $CI->session->userdata('sms_userid') != '') {
			return true;
		} else {
			redirect('login');
		}
	}
}
?>