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
	function addContactQuery($data) {
		$this->db->insert('contactQuery', $data);
		return $this->db->insert_id();
	}
	function update($id, $data) {
		return $this->db->update($this->table, $data, array('customerID' => $id));
	}
	
	function updateBalance($id, $amount) {
		return $this->db->query("UPDATE {$this->table} SET balance = balance + '$amount' WHERE customerID = '$id'");
	}
	function updateWalletByPhone($phone,$amount) {
		return $this->db->query("UPDATE {$this->table} SET wallet_balance = 	wallet_balance + '$amount' WHERE phone = '$phone'");
	}
	function delete($id) {
		//return $this->db->delete($this->table, array('customerID' => $id));
		return $this->db->update($this->table, array('status'=>0), array('customerID' => $id));
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
	
	function getWalletBalance($id) {
		$this->db->select('wallet_balance');
		$this->db->from($this->table);
		$this->db->where('customerID', $id);
		$query = $this->db->get();
		$row = $query->row();
		if ($row) {
			return $row->wallet_balance;
		}
		return 0;
	}
	
	function checkUserExist($loginID, $customerID='') {
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
		$this->db->where('c.status', 1);
		$this->db->where('c.customerID', $id);
		$query = $this->db->get();
		return $query->row();
	}
	function getCustomerByPhone($phone) {
		$this->db->select('c.*, s.stateName, at.alertTypeName, a.loginID as agentLoginID');
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
		$this->db->where('c.status', 1);
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getCustomers($where, $limit=0, $offset=0) {
		$this->db->select('c.*, s.stateName');
		$this->db->from($this->table.' as c');
		$this->db->join('tbl_StateZip as s', 's.stateID = c.stateID', 'left');
		$this->db->where('c.status', 1);
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('c.customerID');
		
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function getCustomersByID($ID) {
		$this->db->select('*');
		$this->db->from($this->table);
		
		$this->db->where('customerID',$ID);
		
		$query = $this->db->get();
		return $query->result();
	}
	
	function getSavedCardByID($ID) {
		$this->db->select('*');
		$this->db->from('tbl_saved_cards');
		$this->db->where('customerID',$ID);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getTransactionHistoryByID($ID) {
		$this->db->select('*');
		$this->db->from('tbl_pinless_txns');
		$this->db->where('customerID',$ID);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getSavedCardByCardID($sa_card_id) {
		$this->db->select('*');
		$this->db->from('tbl_saved_cards');
		$this->db->where('sa_card_id',$sa_card_id);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getCustomersByEmailID($ID) {
		$this->db->select('*');
		$this->db->from($this->table);
		
		$this->db->where('email',$ID);
		
		$query = $this->db->get();
		return $query->result();
	}
	
	function getCustomersByPhoneNumber($phone) {
		$this->db->select('*');
		$this->db->from($this->table);
		
		$this->db->where('phone',$phone);
		
		$query = $this->db->get();
		return $query->result();
	}
	

	function getCustomersLogin($where) {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result_array();
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
	function getCountries($where='') {
		$this->db->select('*');
		$this->db->from('tbl_CountryCode');
		$this->db->order_by('CountryName');
		if($where)
			$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
		
	//Rates
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
	
	function getRatesRandom() {
		$this->db->select('*');
		$this->db->from('tbl_Rates');
		$this->db->limit('5');
		$this->db->order_by('destination', 'RANDOM');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getRatesRandom12() {
		$this->db->select('*');
		$this->db->from('tbl_Rates');
		$this->db->limit('12');
		$this->db->order_by('destination', 'RANDOM');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getVonecallProducts() {
		
		$this->db->select('vp.vproductCategory, vp.vproductVendor, vp.vproductSkuID, vp.ppnProductID, pl.logoName');
		$this->db->from('tbl_VonecallProducts vp');
		$this->db->join('tbl_VonecallProductLogo as pl', 'pl.ppnProductID = vp.ppnProductID', 'left');
		$this->db->limit('100');
		$query = $this->db->get();	
		return $query->result();	
		
	}
	
	function getVonecallRandomProducts() {
		
		$this->db->select('pl.logoName,vp.vproductCategory, vp.vproductVendor, vp.vproductSkuID, vp.ppnProductID');

		$this->db->from('tbl_VonecallProducts vp');
		$this->db->join('tbl_VonecallProductLogo as pl', 'pl.ppnProductID = vp.ppnProductID', 'left');
		$this->db->order_by('ppnProductID', 'RANDOM');
		$this->db->group_by('pl.logoName');
		$this->db->where('logoName !=', '');
		$this->db->limit('20');
		$query = $this->db->get();	
		return $query->result();	
		
	}
	
	
	
	
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
	
	function getAccessNumberForPinless($where='') {
		$this->db->select('*');
		$this->db->from('tbl_AccessNumber');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('AccessNumber');
		$query = $this->db->get();
		return $query->row();
	}
	
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
	
	//Message
	function getMessage($where='') {
		$this->db->select('*');
		$this->db->from('tbl_Message');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row();
	}
	
	//Instruction
	function addInstruction($data) {
		$this->db->insert('tbl_Instructions', $data);
		return $this->db->insert_id();
	}
	function updateInstruction($id, $data) {
		return $this->db->update('tbl_Instructions', $data, array('ID' => $id));
	}
	function deleteInstruction($id) {
		return $this->db->delete('tbl_Instructions', array('ID' => $id));
	}
	function getInstructions($where='', $limit=0, $offset=0) {
		$this->db->select('*');
		$this->db->from('tbl_Instructions');
		if ($where) {
			$this->db->where($where);
		}
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	// Vonecall Products ==========================================================================
	function getVProducts($where='', $limit='', $offset='') {
		$this->db->select('vp.*, p.productName');
		$this->db->from('tbl_VonecallProducts as vp');
		$this->db->join('tbl_Product as p', 'p.productID = vp.productTypeID', 'left');
		if($where){
			$this->db->where($where);
		}			
		if($limit){
			$this->db->limit($limit, $offset);
		}	
		$this->db->order_by('vproductID');	
		$query = $this->db->get();
		return $query->result();
	}
	function getTotalVProducts($where='') {
		$this->db->select('count(a.vproductID) as num');
		$this->db->from('tbl_VonecallProducts as a');	
		if($where){
			$this->db->where($where);
		}		
		$query = $this->db->get();
		return $query->row()->num;
	}
	function checkVProductExist($where='', $productID=0) {
		$this->db->select('count(vproductID) as num');
		$this->db->from('tbl_VonecallProducts');
		if($where)
			$this->db->where($where);
		if ($productID) {
			$this->db->where('vproductID != ', $productID);
		}
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	function getVProductsByCommission($where='', $limit='', $offset='') {
		$this->db->select('ac.*, p.productName, vp.vproductName, vp.vproductCategory, vp.vproductVendor, vp.vproductSkuID, vp.ppnProductID, pl.logoName, cc.countryName');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->join('tbl_VonecallProducts as vp', 'ac.vproductID = vp.vproductID', 'left');
		$this->db->join('tbl_VonecallProductLogo as pl', 'pl.ppnProductID = vp.ppnProductID', 'left');
		$this->db->join('tbl_CountryCode as cc', 'vp.vproductCountryCode = cc.CountryCodeIso', 'left');
		$this->db->where($where);
		$this->db->order_by('ac.ID');
		$query = $this->db->get();
		return $query->result();
	}
	
	/*
	function getRTRCountries($where='') {
		$this->db->select('vp.*, cc.*');
		$this->db->from('tbl_VonecallProducts as vp');
		$this->db->join('tbl_CountryCode as cc', 'vp.vproductCountryCode = cc.CountryCodeIso', 'left');
		
		//$this->db->order_by('CountryName');
		if($where)
			$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	*/
	function getRTRCountries($where='') {
		$this->db->select('cc.ID, cc.CountryCode, cc.CountryName, cc.CountryCodeIso, cc.countryFlag, cc.locaNumberLength');
		$this->db->from('tbl_AgentCommission as ac');
		$this->db->join('tbl_VonecallProducts as vp', 'ac.vproductID = vp.vproductID');
		$this->db->join('tbl_CountryCode as cc', 'vp.vproductCountryCode = cc.CountryCodeIso', 'left');
		$this->db->group_by('cc.ID, cc.CountryCode, cc.CountryName, cc.CountryCodeIso, cc.countryFlag, cc.locaNumberLength'); 
		$this->db->order_by('cc.CountryName');
		
		if($where)
			$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}	
	//Product END =========================================================================	
	
	//unique Array ====================================================================================
	function array_unique_by_key (&$array, $key) {
	    $tmp    = array();
	    $result = array();
	    foreach ($array as $value) {
	        if (!in_array($value->$key, $tmp)) {
	            array_push($tmp, $value->$key);
	            array_push($result, $value);
	        }
	    }
	    return $array = $result;
	}
	function searchValueInArray($array, $id) {
	    foreach ($array as $item) {
	        if ($item->ppnProductID == $id)
	            return true;
	    }
	    return false;
	}
	
	// Calling Cards Batch==============================================================================
	function getCardBatches($where='') {
		$this->db->select('*');
		$this->db->from('tbl_CallingCardBatch');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result();
	}

	// Calling Cards==============================================================================
	function updateCallingCard($id, $data) {
		return $this->db->update('tbl_CallingCard', $data, array('callingCardID' => $id));
	}
	function getCallingCards($where='', $limit='', $offset='') {
		$this->db->select('*');
		$this->db->from('tbl_CallingCard');
		if ($where) {
			$this->db->where($where);
		}
		if($limit){
			$this->db->limit($limit, $offset);
		}	
		$this->db->order_by('callingCardID');
		
		$query = $this->db->get();
		return $query->result();
	}	
	
	function getCallingCardDetails($where='', $where_or='') {
		$this->db->select('cc.*, cb.*');
		$this->db->from('tbl_CallingCard as cc');
		$this->db->join('tbl_CallingCardBatch as cb', 'cb.batchID = cc.callingCardBatchName', 'left');
		if ($where) {
			$this->db->where($where);
		}
		if($where_or){
			$this->db->where($where_or);
		}	
		$this->db->order_by('callingCardID');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
		
	// Calling Cards Access Numbers==============================================================================
	function getCallingCardAccessNumbers($where='', $limit='', $offset='') {
		$this->db->select('*');
		$this->db->from('tbl_CallingCardAccessNumbers');
		if ($where) {
			$this->db->where($where);
		}
		if($limit){
			$this->db->limit($limit, $offset);
		}	
		$this->db->order_by('accessID');
		
		$query = $this->db->get();
		return $query->result();
	}

	// PINLESS REPORTS ==========================================================================
	function addPinlessReport($data){
		$this->db->insert('tbl_PinlessResponseReport', $data);
		return $this->db->insert_id();
	}
   //======insert otp============
   function addOTP($data) {
		$this->db->insert('verifyOTP', $data);
		return $this->db->insert_id();
	}
   function updateOTP($phone, $data) {
		return $this->db->update('verifyOTP',$data,array('otpPhone' => $phone));
    }
   function updateCustomerByPhone($phone, $data) {
		return $this->db->update($this->table,$data,array('phone' => $phone));
    }
   function deleteOTP($where) {
		return $this->db->delete('verifyOTP',$where);
   }
   //=======check otp===============
   function checkOtpForPhone($where) {
		$this->db->select('*');
		$this->db->from('verifyOTP');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
   function addPinlessTxn($data) {
		$this->db->insert('tbl_pinless_txns', $data);
		return $this->db->insert_id();
	}
   
   function saveMyCard($data) {
		$this->db->insert('tbl_saved_cards', $data);
		return $this->db->insert_id();
	}
   
}
?>