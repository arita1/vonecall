<?php
class roleuser {
	// Contructor
	function roleuser() {
		
	}
	public function checkLogin() {
		$CI = & get_instance();
        $CI->load->library('session');
        $CI->load->helper('url');
		if ($CI->session->userdata('store_role') == 'reseller' && $CI->session->userdata('store_userid') != '') {
			$language = $CI->session->userdata('language');        			
			if($language != false && $language != '') {
				$CI->lang->load($language, $language);
			}
			return true;
		} else {
			redirect('login');
		}
	}
}
?>