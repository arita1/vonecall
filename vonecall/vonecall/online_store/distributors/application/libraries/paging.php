<?php
class paging
{
   
   // Contructor	
	function paging()
	{
	
	} 
	function create_links($data) {
		if ($data['total'] == 0 OR $data['per_page'] == 0) {
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($data['total'] / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
		{
			return '';
		}

		// Determine the current page number.
		$CI =& get_instance();

		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			if ($CI->input->get($this->query_string_segment) != 0)
			{
				$this->cur_page = $CI->input->get($this->query_string_segment);

				// Prep the current page - no funny business!
				$this->cur_page = (int) $this->cur_page;
			}
		}
		else
		{
			if ($CI->uri->segment($this->uri_segment) != 0)
			{
				$this->cur_page = $CI->uri->segment($this->uri_segment);

				// Prep the current page - no funny business!
				$this->cur_page = (int) $this->cur_page;
			}
		}

		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}

		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = 0;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->cur_page > $data['total'])
		{
			$this->cur_page = ($num_pages - 1) * $this->per_page;
		}

		$uri_page_number = $this->cur_page;
		$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}

  		// And here we go...
		$output = '';

		// Render the "First" link
		if  ($this->cur_page > ($this->num_links + 1))
		{
			$output .= $this->first_tag_open.'<a href="'.$this->base_url.$sort.'">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		if  ($this->cur_page != 1)
		{
			$i = $uri_page_number - $this->per_page;
			if ($i == 0) $i = '';
			$output .= $this->prev_tag_open.'<a href="'.$this->base_url.$i.$sort.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
		}

		// Write the digit links
		for ($loop = $start -1; $loop <= $end; $loop++)
		{
			$i = ($loop * $this->per_page) - $this->per_page;

			if ($i >= 0)
			{
				if ($this->cur_page == $loop)
				{
					$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
				}
				else
				{
					$n = ($i == 0) ? '' : $i;
					$output .= $this->num_tag_open.'<a href="'.$this->base_url.$n.$sort.'">'.$loop.'</a>'.$this->num_tag_close;
				}
			}
		}

		// Render the "next" link
		if ($this->cur_page < $num_pages)
		{
			$output .= $this->next_tag_open.'<a href="'.$this->base_url.($this->cur_page * $this->per_page).$sort.'">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if (($this->cur_page + $this->num_links) < $num_pages)
		{
			$i = (($num_pages * $this->per_page) - $this->per_page);
			$output .= $this->last_tag_open.'<a href="'.$this->base_url.$i.$sort.'">'.$this->last_link.'</a>'.$this->last_tag_close;
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;

		return $output;
	}
	
	function do_paging_customer($data) {           
        $num_links = 2;
        $limit = $data['limit'];
		$total = $data['total'];		          
        $current = $data['current']; 
        
		if($total % $limit == 0) {
			$total_page = (int)($total / $limit);
		} else {
			$total_page = (int)($total / $limit) + 1;
		}
		if ($total_page <= 1) {
			return '';
		}
        $from = ($current - $num_links) > 0 ? ($current - $num_links) : 1;
        $to = ($current + $num_links) < $total_page ? ($current + $num_links) : $total_page;
        
        $link = "<div class=\"pag p10b p10t\"><ul>";
        if ($from != 1) {
        	$link .= "<li><a href=\"javascript:\" onclick=\"paging(1);\"><img src=\"".base_url()."public/images/bule_prev.png\"/></a></li>";
        }
        for($i = $from; $i <= $to; $i++) {	
        	if ($current == $i) {
        		$link .= "<li class=\"current\"><a href=\"javascript:\">$i</a></li>";
        	} else {
        		$link .= "<li><a href=\"javascript:\" onclick=\"paging($i);\">$i</a></li>";
        	}
        }
        if ($to != $total_page) {
        	$link .= "<li><a href=\"javascript:\" onclick=\"paging($total_page);\"><img src=\"".base_url()."public/images/bule_next.png\"/></a></li>";
        }
        $link .= "</ul></div>";
        return $link;  
    }
    
    function do_paging_news($url, $paging_data)
    {           
        $link = '';
		
        
        $page_display = $paging_data['page_display'];
        $limit = $paging_data['limit'];
		$total_record = $paging_data['total_record'];		          
        $start_record = $paging_data['start_record'];
		$page_current = $paging_data['page_current']; 
        
		if($total_record % $limit == 0){
			$total_page = (int)$total_record / $limit;
		}
		else{
			$total_page = (int)($total_record / $limit) + 1;
		}
        
        $segment = (int)($page_current-1)/$page_display;        
  
        $from_page = ((int)$segment*$page_display) + 1;
		$to_page = $from_page + ($page_display -1);
        
		if($to_page > $total_page){
			$to_page = $total_page;
		}
                        
        if($total_page >= 2) {    
			            
            if ($page_current != 1)//preview
			{
			    $link .= '<a href="' . $url . 1 . '" title="Start"><li>«</li></a>'; 
				$link .= '<a href="' . $url . ($page_current-1) . '"><li>‹</li></a>';	
			}else{
			    $link .= '<a><li>«</li></a>'; 
			    $link .= '<a><li>‹</li></a>'; 
			}            
          
			for($i = $from_page; $i <= $to_page; $i++){	
			 
				if($i != $page_current){					
					$link .= '<a href="'.$url . $i . '" title="'.$i.'"><li>'.$i.'</li></a>';
				}
				else{					
					$link .= '<a><li class="current">'.$i.'</li></a>';
				}                
			}          

			if($page_current != $total_page) //next 
			{
				$link .= '<a href="' . $url . ($page_current + 1) . '" title="Next" ><li>›</li></a>';
                $link .= '<a href="'.$url.$total_page.'" title="End" ><li>»</li></a>';			
			}else{
			    $link .= '<a><li>›</li></a>'; 
                $link .= '<a><li>»</li></a>';
			}	   
            
		}        
        
        return $link;  
    }
    
    function do_paging($paging_data)
    {           
        $link = '';
        
        $page_display = $paging_data['page_display'];
        $limit = $paging_data['limit'];
		$total_record = $paging_data['total_record'];		          
        $start_record = $paging_data['start_record'];
		$page_current = $paging_data['page_current']; 
        
		if($total_record % $limit == 0){
			$total_page = (int)$total_record / $limit;
		}
		else{
			$total_page = (int)($total_record / $limit) + 1;
		}
        
        $segment = (int)($page_current-1)/$page_display;        
  
        $from_page = ((int)$segment*$page_display) + 1;
		$to_page = $from_page + ($page_display -1);
        
		if($to_page > $total_page){
			$to_page = $total_page;
		}
                        
        if($total_page >= 2) {            
                        
            if ($page_current != 1)//preview
			{
			    $link .= '<div class="button2-right"><div class="start"><a href="javascript:void(0)" title="Start" onclick="setPage(\'' . 1 . '\');submitForm(\'index\',\'\')">Start</a></div></div>'; 
				$link .= '<div class="button2-right"><div class="prev"><a href="javascript:void(0)" title="Pre" onclick="setPage(\'' . ($page_current-1) . '\');submitForm(\'index\',\'\')">Prev</a></div></div>';	
			}else{
			    $link .= '<div class="button2-right off"><div class="start"><span>Start</span></div></div>'; 
			    $link .= '<div class="button2-right off"><div class="prev"><span>Prev</span></div></div>'; 
			}
            
            $link .= '<div class="button2-left"><div class="page">';
			for($i = $from_page; $i <= $to_page; $i++){	
			 
				if($i != $page_current){
					$link .= '<a href="javascript:void(0)" title="'.$i.'" onclick="setPage(\''.$i.'\');submitForm(\'index\',\'\')">'.$i.'</a>';
				}
				else{
					$link .= '<span>'.$i.'</span>';
				}                
			}
            $link .= '</div></div>';	

			if($page_current != $total_page) //next 
			{
				$link .= '<div class="button2-left"><div class="next"><a href="javascript:void(0)" title="Next" onclick="setPage(\''.($page_current+1).'\');submitForm(\'index\',\'\')">Next</a></div></div>';
                $link .= '<div class="button2-left"><div class="end"><a href="javascript:void(0)" title="End" onclick="setPage(\''.$total_page.'\');submitForm(\'index\',\'\')">End</a></div></div>';			
			}else{
			    $link .= '<div class="button2-left off"><div class="next"><span>Next</span></div></div>'; 
                $link .= '<div class="button2-left off"><div class="end"><span>End</span></div></div>';
			}		
            
            $link .= '<div class="limit">Page '.$page_current.' of '.$total_page.'</div>';                        
            
		}        
        
        return $link;  
    }
    
    function list_page_display($arr_record_display, $limit)
    {
        $list_page_display = '';
        $list_page_display .= '<div class="limit">Display <select id="limit" name="limit" class="inputbox" size="1" onchange="submitForm(\'index\',\'\')">';
        	
		for($i=0;$i<count($arr_record_display);$i++){
		  $list_page_display .= '<option value="'.$arr_record_display[$i].'"';
          if($limit==$arr_record_display[$i]){
            $list_page_display .= "selected";
          }
          $list_page_display .= '>'.$arr_record_display[$i].'</option>';		 	
		}
	
	   $list_page_display .= '</select></div>';
       
       return $list_page_display;
    }
        
}
?>