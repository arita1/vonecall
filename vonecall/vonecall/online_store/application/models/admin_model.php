<?php
class Admin_Model extends CI_Model {
	
	var $table = 'tbl_Agent'; 
	
	function __construct() {
		parent::__construct();
	}
	function add($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	function update($id, $data) {
		return $this->db->update($this->table, $data, array('agentID' => $id));
	}
	function delete($id) {
		return $this->db->delete($this->table, array('agentID' => $id));
	}

	function set_session($status){
		$data = array('log_status'=>$status);
		$this->db->where('agentID',$this->session->userdata('store_userid'));
		$this->db->update('tbl_Agent', $data);
        // echo $this->db->last_query();
        // die();		
	}
	function login($username, $password) {
		$this->db->select('a.*, at.agentTypeName');
		$this->db->from($this->table.' as a');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->where('a.loginID', $username);
		$this->db->where('a.password', $password);
		$this->db->where('a.agentTypeID in (2,3)');
		
		$query = $this->db->get();
                
//                echo $this->db->last_query();
//                die;
                
		$row = $query->row();
		//echo $this->db->last_query();die;
		if (!$row) {
			return array('status' => false, 'code' => 'INVALID');
		}
		
		if (!$row->enabled) {
			return array('status' => false, 'code' => 'DISABLED');
		}
		
		//Get header contact
		$getStoreHeader = $this->getSettings(array('settingKey' => 'contactNumber'));
		
		$userid = $row->agentTypeID==2?$row->agentID:$row->parentAgentID;
		$this->session->set_userdata('store_role', 'reseller');
		$this->session->set_userdata('store_username', $username);
		$this->session->set_userdata('store_usertypeid', $row->agentTypeID);
		$this->session->set_userdata('store_usertype', $row->agentTypeName);
		$this->session->set_userdata('store_userid', $userid);
		$this->session->set_userdata('store_logindatetime', date('Y-m-d H:i:s'));
		$this->session->set_userdata('header_contact', $getStoreHeader->  settingValue?$getStoreHeader->settingValue:'6619933305');
		//return $this->session->userdata('store_role');
		 return array('status' => true);
	}
	function logout() {
		$this->session->unset_userdata('store_role');
		$this->session->unset_userdata('store_username');
		$this->session->unset_userdata('store_usertypeid');
		$this->session->unset_userdata('store_usertype');
		$this->session->unset_userdata('store_userid');
		$this->session->unset_userdata('store_logindatetime');
	}
	function checkPassword($password) {
		$this->db->select('count(adminID) as num');
		$this->db->from($this->table);
		$this->db->where('adminID', $this->session->userdata('store_userid'));
		$this->db->where('password', $password);
		$query = $this->db->get();
		return $query->row()->num;
	}
	function checkUserExist($username, $id=0) {
		$this->db->select('count(adminID) as num');
		$this->db->from($this->table);
		$this->db->where('username', $username);
		if ($id) {
			$this->db->where('adminID != ', $id);
		}
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	function getAdminTypes() {
		$this->db->select('at.*');
		$this->db->from('tbl_AdminType as at');
		$query = $this->db->get();
		return $query->result();
	}
	function getAdmin($id) {
		$this->db->select('a.*, at.adminTypeCode');
		$this->db->from($this->table.' as a');
		$this->db->join('tbl_AdminType as at', 'at.adminTypeID = a.adminTypeID', 'left');
		$this->db->where('a.adminID', $id);
		$query = $this->db->get();
		return $query->row();
	}
	function getTotalAdmins($where) {
		$this->db->select('count(a.adminID) as num');
		$this->db->from($this->table.' as a');
		$this->db->join('tbl_AdminType as at', 'at.adminTypeID = a.adminTypeID', 'left');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getAdmins($where, $limit, $offset) {
		$this->db->select('a.*, at.adminTypeCode');
		$this->db->from($this->table.' as a');
		$this->db->join('tbl_AdminType as at', 'at.adminTypeID = a.adminTypeID', 'left');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('a.adminID');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result();
	}
	
	//Product
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
	
	//Pinless Refunds
	function addPinlessRefund($data) {
		$this->db->insert('tbl_PinlessRefunds', $data);
	}
	function getPinlessRefunds($where='') {
		$this->db->select('*');
		$this->db->from('tbl_PinlessRefunds');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
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
	
	
	
}
?>