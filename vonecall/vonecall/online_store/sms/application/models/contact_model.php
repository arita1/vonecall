<?php

class Contact_model extends CI_Model {
	var $table = 'tbl_smsContacts';	
	
	function Contact_model() {
	   parent::__construct();	   
	}
	
	//add-edit-delete Contacts =============================================================================================================
	function add($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	function update($id, $data) {
		return $this->db->update($this->table, $data, array('contactID' => $id));
	}
	function delete($id) {
		return $this->db->delete($this->table, array('contactID' => $id));
	}	
	function getAllContacts($where=array()) {
		$this->db->select('*');
		$this->db->from($this->table);				
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('contactID');
		$query = $this->db->get();
		return $query->result();
	}
	function getContacts($where=array()) {
		$this->db->select('c.*, g.groupName');
		$this->db->from($this->table.' as c');	
		$this->db->join('tbl_SMSGroup as g', 'g.groupID = c.groupID', 'left');			
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('c.contactID');
		$query = $this->db->get();
		return $query->result();
	}
	function contactNumberEmail($phonenum) 
	{
		$userDetails = $this->config->item('data24X7');
		$username = $userDetails['username'];
		$password = $userDetails['password'];
		if ($phonenum[0] != "1") $phonenum = "1" . $phonenum;
		$url = "https://api.data24-7.com/v/2.0?api=T&user=$username&pass=$password&p1=$phonenum";
		$xml = simplexml_load_file($url) or die("feed not loading");
	  /* echo '<pre>';
	   print_r($xml->results->result[0]);die;*/
		$outphone = $xml->results->result[0]->number;
		$status   = $xml->results->result[0]->sms_address;
		$name     = $xml->results->result[0]->name;
		return($xml);
	} 
	
	function deleteContacts($where=array()) {
		return $this->db->delete($this->table, $where);
	}
		
		
	// Groups ========================================================================================================================
	
	function addGroup($data) {
		$this->db->insert('tbl_SMSGroup', $data);		
		return $this->db->insert_id();
	}
	function updateGroup($id, $data) {
		return $this->db->update('tbl_SMSGroup', $data, array('groupID' => $id));
	}
	function getAllGroups($where=array()) {
		$this->db->select('c.*');
		$this->db->from('tbl_SMSGroup c');				
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('c.groupID');
		$query = $this->db->get();
		return $query->result();
	}
	
	function deleteGroups($where=array()) {
		return $this->db->delete('tbl_SMSGroup', $where);
	}
	
}
?>