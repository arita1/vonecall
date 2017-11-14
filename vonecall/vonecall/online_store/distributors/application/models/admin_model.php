<?php
class Admin_Model extends CI_Model {
	
	var $table = 'tbl_Agent'; 
	
	function __construct() {
		parent::__construct();
	}
	function update($id, $data) {
		return $this->db->update($this->table, $data, array('agentID' => $id));
	}
	function checkLogin($username, $password){
		$this->db->select('a.*, at.agentTypeName');
		$this->db->from($this->table.' as a');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->where('a.loginID', $username);
		$this->db->where('a.password', $password);
		$this->db->where('a.agentTypeID in (1)');
				
		$query = $this->db->get();
                
                
                $row = $query->row();
		if(isset($row)){
			return $row;	                       
		}else{
			$this->db->select('a.*, at.agentTypeName');
			$this->db->from($this->table.' as a');
			$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
			$this->db->where('a.loginID', $username);
			$this->db->where('a.password', $password);
			$this->db->where('a.agentTypeID = 4');
					
			$query = $this->db->get();
			return $query->row();	
                      
		}
		
	}
	function login($username, $password) {
		$row = $this->checkLogin($username, $password);
                       
               
		if ($row) {
			$login = true;
			$this->session->set_userdata('rep_role', 'rep');
			$this->session->set_userdata('rep_username', $username);
			$this->session->set_userdata('rep_usertype', $row->agentTypeName);
			$this->session->set_userdata('rep_userid', $row->agentID);
			$this->session->set_userdata('rep_logindatetime', date('Y-m-d H:i:s'));
		} else {
			$login = false;
		}
		return $login;
	}
	function logout() {
		$this->session->unset_userdata('rep_role');
		$this->session->unset_userdata('rep_username');
		$this->session->unset_userdata('rep_usertype');
		$this->session->unset_userdata('rep_userid');
		$this->session->unset_userdata('rep_logindatetime');
	}
	function checkPassword($password) {
		$this->db->select('count(agentID) as num');
		$this->db->from($this->table);
		$this->db->where('agentID', $this->session->userdata('rep_userid'));
		$this->db->where('password', $password);
		$query = $this->db->get();
		return $query->row()->num;
	}
	
	//Rates ============================
	function getTotalRates($where='') {
		$this->db->select('count(ID) as num');
		$this->db->from('tbl_Rates');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getRates($where='', $limit=0, $offset=0) {
		$this->db->select('*');
		$this->db->from('tbl_Rates');
		if ($where) {
			$this->db->where($where);
		}
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('destination');
		$query = $this->db->get();
		return $query->result();
	}
	// Access Numbers =====================================
	function getAccessNumbers($where='') {
		$this->db->select('*');
		$this->db->from('tbl_AccessNumber');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('AccessNumber');
		$query = $this->db->get();
		return $query->result();
	}
	
	//Product ====================================================================
	function addProduct($data) {
		$this->db->insert('tbl_Product', $data);
	}
	function updateProduct($productID, $data) {
		return $this->db->update('tbl_Product', $data, array('productID'=>$productID));
	}
	function deleteProduct($productID) {
		return $this->db->delete('tbl_Product', array('productID'=>$productID));
	}
	function getProduct($state, $city) {
		$this->db->select('*');
		$this->db->from('tbl_Product');
		$this->db->where('productID', $productID);
		$query = $this->db->get();
		return $query->row();
	}
	function getProducts($where='') {
		$this->db->select('*');
		$this->db->from('tbl_Product');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function checkProductExist($productName, $productID=0) {
		$this->db->select('count(productID) as num');
		$this->db->from('tbl_Product');
		$this->db->where('productName', $productName);
		if ($productID) {
			$this->db->where('productID != ', $productID);
		}
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	function getAllProductsByAgent($agentID, $where='', $limit=0, $offset=0) {
		$this->db->select('ac.ID, ac.agentID, ac.productID, ac.commissionRate, ac.maxStoreCommission, ac.createdDate, p.productName, vp.*');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->join('tbl_VonecallProducts as vp', 'vp.productTypeID = ac.productID', 'left');
		$this->db->where("ac.agentID", $agentID);
		if($where){
			$this->db->where($where);
		}
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('ac.ID');
		$query = $this->db->get();
		return $query->result();
	}
	function getTotalProductsByAgent($agentID, $where='') {
		$this->db->select('count(ac.agentID) as num');
		$this->db->from('tbl_AgentCommission as ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->join('tbl_VonecallProducts as vp', 'vp.productTypeID = ac.productID', 'left');
		$this->db->where("ac.agentID", $agentID);
		if($where){
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}

	//Product END =========================================================================
	
	// General Settings ===============================================================================
	function getSettings($where='') {
		$this->db->select('*');
		$this->db->from('tbl_GeneralSettings');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row();
	}
	
	// Unique array Sort ===========================================
	function unique_sort($array, $key) {
	    $unique_arr = array();
	    foreach ($array AS $arr) {
	
	        if (!in_array($arr->$key, $unique_arr)) {
	            $unique_arr[] = $arr->$key;
	        }
	    }
	    sort($unique_arr);
	    return $unique_arr;
	}
}
?>