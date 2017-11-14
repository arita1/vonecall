<?php

class Customer_model extends CI_Model {
	var $table = 'tbl_Customer';	
	
	function Customer_model() {
	   parent::__construct();
	}
	
	//add-edit-delete customers =============================================================================================================
	function add($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	function update($id, $data) {
		return $this->db->update($this->table, $data, array('customerID' => $id));
	}
	function updateBalance($id, $amount) {
		return $this->db->query("UPDATE {$this->table} SET balance = balance + '$amount' WHERE customerID = '$id'");
	}
	function delete($id) {
		return $this->db->delete($this->table, array('customerID' => $id));
	}
	function deleteCustomers($where) {
		return $this->db->delete($this->table, $where);
	}
	function getBalance($id) {
		$this->db->select('balance');
		$this->db->from($this->table);
		$this->db->where('customerID', $id);
		$query = $this->db->get();
		$row = $query->row();
		if ($row) {
			return $row->balance;
		}
		return 0;
	}
	
	function checkUserExist($loginID, $customerID) {
		$this->db->select('count(customerID) as num');
		$this->db->from($this->table);
		$this->db->where('loginID', $loginID);
		if ($customerID) {
			$this->db->where('customerID != ', $customerID);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function checkPhoneNumberExist($phone_number, $customerID) {
		$this->db->select('count(customerID) as num');
		$this->db->from($this->table);
		$this->db->where('phone', $phone_number);
		if ($customerID) {
			$this->db->where('customerID != '.$customerID);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getCustomer($id) {
		$this->db->select('c.*, s.stateName, at.alertTypeName');
		$this->db->from($this->table.' as c');
		$this->db->join('tbl_StateZip as s', 's.stateID = c.stateID', 'left');
		$this->db->join('tbl_AlertType as at', 'at.alertTypeID = c.alertID', 'left');
		$this->db->where('c.customerID', $id);
		$query = $this->db->get();
		return $query->row();
	}
	function getCustomerByPhone($phone) {
		$this->db->select('c.*, null as password, s.stateName, at.alertTypeName, a.loginID as agentLoginID');
		$this->db->from($this->table.' as c');
		$this->db->join('tbl_Agent as a', 'a.agentID = c.agentID', 'left');
		$this->db->join('tbl_StateZip as s', 's.stateID = c.stateID', 'left');
		$this->db->join('tbl_AlertType as at', 'at.alertTypeID = c.alertID', 'left');
		//$this->db->where('c.subscriberID', $row->PPR_USER_ID);
		$this->db->where('c.phone', $phone);
		$query = $this->db->get();
		return $query->row();
	}
	function getTotalCustomers($where) {
		$this->db->select('count(c.customerID) as num');
		$this->db->from($this->table.' as c');
		$this->db->join('tbl_StateZip as s', 's.stateID = c.stateID', 'left');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getCustomers($where, $limit, $offset) {
		$this->db->select('c.*, s.stateName');
		$this->db->from($this->table.' as c');
		$this->db->join('tbl_StateZip as s', 's.stateID = c.stateID', 'left');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('c.customerID');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result();
	}
	function getGenders() {
		$this->db->select('genderID, genderName');
		$this->db->from('tbl_Gender');
		$query = $this->db->get();
		return $query->result();
	}
	function getStates() {
		$this->db->select('stateID, stateName');
		$this->db->from('tbl_StateZip');
		$query = $this->db->get();
		return $query->result();
	}
	function getCountries() {
		$this->db->select('Country, CountryCode');
		$this->db->from('tblCountry');
		$query = $this->db->get();
		return $query->result();
	}
	
	//Start phone_log========================================================================================================================
	function getPhoneLogs($customerID) {
		$this->db->select('*');
		$this->db->from('tbl_PhoneLog');
		$this->db->where('customerID', $customerID);
		$this->db->order_by('createdDate', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	function addPhoneLog($data) {
		$data['updateBy'] = $this->session->userdata('usertype').' - '.$this->session->userdata('username');
		$data['createdDate'] = date('Y-m-d H:i:s');
		$this->db->insert('tbl_PhoneLog', $data);
	}
	function updatePhoneLog($id, $data) {
		return $this->db->update('tbl_PhoneLog', $data, array('id' => $id));
	}
	function deletePhoneLog ($id) {
		return $this->db->delete('tbl_PhoneLog', array('id' => $id));
	}
	//End phone_log==========================================================================================================================
	
	function getPaymentTypes() {
		$this->db->select('*');
		$this->db->from('tbl_PaymentMethod');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getCreditCardTypes() {
		$this->db->select('*');
		$this->db->from('tbl_CreditCardType');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getTotalNewReport($from_date, $to_date) {
		$this->db->select('count(c.customerID) as num');
		$this->db->from($this->table.' as c');
		$this->db->join('tbl_StateZip as s', 's.stateID = c.stateID', 'left');
		$this->db->where("c.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getNewReport($from_date, $to_date, $limit, $offset) {
		$this->db->select('c.*, s.stateName');
		$this->db->from($this->table.' as c');
		$this->db->join('tbl_StateZip as s', 's.stateID = c.stateID', 'left');
		$this->db->where("c.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->order_by('c.customerID');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result();
	}
	function getCallDetails($subscriberID) {
		return array();
	}
	
	//Phone
	function addPhone($data) {
		return $this->db->insert('tbl_CustomerPhones', $data);
	}
	function updatePhone($where, $data) {
		return $this->db->update('tbl_CustomerPhones', $data, $where);
	}
	function deletePhone($id) {
		return $this->db->delete('tbl_CustomerPhones', array('customerID' => $id));
	}
	function getPhones($customerID) {
		$query = $this->db->query("SELECT * FROM tbl_CustomerPhones WHERE customerID = '$customerID'");
		return $query->result();
	}
	function checkPhoneNumberExist2($customerID, $phone) {
		$query = $this->db->query("SELECT COUNT(*) as NUM FROM tbl_CustomerPhones WHERE aniPhoneNumber='$phone' and customerID ='$customerID'");
		return $query->row()->NUM;
	}
	
	//SpeedDial
	function addSpeedDial($data) {
		return $this->db->insert('tbl_SpeedDial', $data);
	}
	function updateSpeedDial($where, $data) {
		return $this->db->update('tbl_SpeedDial', $data, $where);
	}
	function deleteSpeedDial($where) {
		return $this->db->delete('tbl_SpeedDial', $where);
	}
	function getSpeedDials($customerID) {
		$query = $this->db->query("SELECT * FROM tbl_SpeedDial WHERE customerID = '$customerID'");
		return $query->result();
	}
	function checkSpeedDialExist($customerID, $entry) {
		$query = $this->db->query("SELECT count(*) as NUM FROM tbl_SpeedDial WHERE customerID = '$customerID' AND speed_dial = '$entry'");
		return $query->row()->NUM;
	}
	
	//promotion
	function addPromotion($data) {
		$this->db->insert('tbl_Promotion', $data);
		return $this->db->insert_id();
	}
	function updatePromotion($id, $data) {
		return $this->db->update('tbl_Promotion', $data, array('ID' => $id));
	}
	function getPromotion($productID=0, $createdDate='') {
		$this->db->select('*');
		$this->db->from('tbl_Promotion');
		if ($productID) {
			$this->db->where('productID', $productID);
		}
		if ($createdDate) {
			$this->db->where("'$createdDate' BETWEEN fromDate AND toDate");
		}
		$this->db->where('status', 1);
		$query = $this->db->get();
		return $query->row();
	}
	function getPromotionHistory() {
		$this->db->select('pr.*, p.productName');
		$this->db->from('tbl_Promotion pr');
		$this->db->join('tbl_Product as p', 'pr.productID = p.productID', 'left');
		$this->db->where('pr.status', 0);
		$query = $this->db->get();
		return $query->result();
	}
}
?>