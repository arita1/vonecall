<?php
class roleuser {
	// Contructor
	function roleuser() {
		
	}
	public function checkLogin() {
		$CI = & get_instance();
        $CI->load->library('session');
        $CI->load->helper('url');
        if ($CI->session->userdata('rep_role') == 'rep' && $CI->session->userdata('rep_userid') != '') {
			return true;
		} else {
			redirect('login');
		}
	}
}
?>