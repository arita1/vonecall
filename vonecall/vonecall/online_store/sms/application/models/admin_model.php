<?php
class Admin_Model extends CI_Model {
	
	var $table = 'tbl_Admin'; 
	
	function __construct() {
		parent::__construct();
	}
	function add($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	function update($id, $data) {
		return $this->db->update($this->table, $data, array('adminID' => $id));
	}
	function delete($id) {
		return $this->db->delete($this->table, array('adminID' => $id));
	}
	function login($username, $password) {
		$this->db->select('a.*, at.adminTypeCode');
		$this->db->from($this->table.' as a');
		$this->db->join('tbl_AdminType as at', 'at.adminTypeID = a.adminTypeID', 'left');		
		$this->db->where('a.username', $username);
		$this->db->where('a.password', $password);
		$query = $this->db->get();
		$row = $query->row();
		if ($row) {
			if($row->adminStatus==1){
				$login = true;
				$this->session->set_userdata('sms_role', 'admin');
				$this->session->set_userdata('sms_username', $username);
				$this->session->set_userdata('sms_usertype', $row->adminTypeCode);
				$this->session->set_userdata('sms_userid', $row->adminID);
				$this->session->set_userdata('sms_logindatetime', date('Y-m-d H:i:s'));
			}else{
				$login = 'disable';
			}
		} else {
			$login = false;
		}
		return $login;
	}
	function checkPassword($password) {
		$this->db->select('count(adminID) as num');
		$this->db->from($this->table);
		$this->db->where('adminID', $this->session->userdata('userid'));
		$this->db->where('password', $password);
		$query = $this->db->get();
		return $query->row()->num;
	}
	function logout() {
		$this->session->unset_userdata('role');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('usertype');
		$this->session->unset_userdata('userid');
		$this->session->unset_userdata('logindatetime');
		$this->session->sess_destroy();
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
	function checkUserEmailExist($email, $id=0) {
		$this->db->select('count(adminID) as num');
		$this->db->from($this->table);
		$this->db->where('email', $email);
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
	
	// Settings ===============================================================================
	function addSettings($data) {
		$this->db->insert('tbl_SMSSettings', $data);		
		return $this->db->insert_id();
	}
	
	function getSettings($where=array()) {
		$this->db->select('c.*');
		$this->db->from('tbl_SMSSettings c');				
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function updateSettings($where, $data) {
		return $this->db->update('tbl_SMSSettings', $data, $where);
	}
		
}
?>