<?php
class common
{
   
   // Contructor	
	function common()
	{
		
	} 
	
	function loadLanguage(){    
        
        $CI = & get_instance();
        $CI->load->library('session');
        
        $language = $CI->session->userdata('language');        			

		if($language != false && $language != ''){			 
			$CI->lang->load($language, $language);			
		}
	}	  
    
    function getCurrentTime(){
        return time(date('H')-1, date('i'), date('s'), date('m'), date('d'), date('Y'));
    }  
    
    function curPageURL(){
	   
    	$pageURL = 'http';
    	if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
    		$pageURL .= "s";
    	}
    	$pageURL .= "://";
    	if ($_SERVER["SERVER_PORT"] != "80"){
    		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    	}else{
    	 	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    	}
    	
    	return $pageURL;        
	}
    
    function getCatList()
    {
        $car_type = $this->input->post('car_type');
		$car_type = $this->input->post('category');
        
        if($car_type <= 0){
            $car_type = 1;
        }
        
        $query = $this->db->get_where('category', array('car_type' => $car_type));   
        
        $list_cat = '<select size="1" class="inputbox" name="category" id="category" aria-invalid="false" style="width:140px">';
        $list_cat .= '<option value="0">T?t c?</option>';
		
        foreach($query->result_array() as $row){
            $list_cat .= '<option value="'.$row['id'].'">'.$row['title'].'</option>';      
        } 
        
        $list_cat .= '</select>';     
         
        echo $list_cat;        
    }
	
	function plitString($text, $chars = 0, $add_f = false, $ch = "&nbsp; ") {
		$text = trim(ucfirst($text));
		if( $chars > 0 && strlen($text) > $chars) {
			$text = $text . " ";
			$text = substr($text, 0, $chars);
			$text = substr($text, 0, strrpos($text, ' '));
			if(substr($text, -1, 1) == ",")
				$text = substr($text, 0, strlen($text)-1);
			$text = $text . "...";
		}
		else{
			if(substr($text, -1, 1) == ",")
				$text = substr($text, 0, strlen($text)-1);
			if($add_f && $chars > 0 && $chars > strlen($text))
				$text .= str_repeat($ch, ($chars - strlen($text))/2);
		}
		return $text;
	}
    
    function get_youtube_image($url, $size = 'small'){
        
        $pos_start_key = strpos($url, '?v=');
        $pos_end_key = strpos($url, '&');
        
        $key = substr($url, $pos_start_key + 3, $pos_end_key - ($pos_start_key + 3));
        
        if($size == 'small'){
            return "http://img.youtube.com/vi/" . $key . "/2.jpg";
        }else{
            return "http://img.youtube.com/vi/" . $key . "/0.jpg";
        }                
    }
    
    function get_youtube_url($url){        
        $url = str_replace('watch?v=','v/', $url);
        return $url;                
    }
   
}
?>