<?php

class Payment_model extends CI_Model {
	var $table = 'tbl_CustomerPayment';	
	
	function Payment_model() {
	   parent::__construct();
	}
	function add ($data) {
		$insert = $this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	function update ($id, $data) {
		return $this->db->update($this->table, $data, array('payment_id' => $id));
	}
	function delete ($payment_id) {
		return $this->db->delete($this->table, array('payment_id' => $payment_id));
	}
	function getPaymentTypes() {
		$this->db->select('*');
		$this->db->from('tbl_PaymentMethod');
		$query = $this->db->get();
		return $query->result();
	}
	function getPayment($customerID, $seqNo) {
		$this->db->select('p.*, pm.paymentMethod');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->where('p.customerID', $customerID);
		$this->db->where('p.seqNo', $seqNo);
		$query = $this->db->get();
		return $query->row();
	}
	function getPaymentByCustomer($customerID) {
		$this->db->select('p.*, pm.paymentMethod');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->where('p.customerID', $customerID);
		$this->db->where('p.agentID', $this->session->userdata('store_userid'));
		$this->db->order_by('p.createdDate', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	function getTotalPaymentCustomer($customerID) {
		$this->db->select("sum(p.chargedAmount) as total");
		$this->db->from($this->table.' as p');
		$this->db->where('p.customerID', $customerID);
		$query = $this->db->get();
		return $query->row()->total;
	}
	
	function checkExistPaymentCustomer($customerID) {
		$this->db->select("count(p.seqNo) as num");
		$this->db->from($this->table.' as p');
		$this->db->where('p.customerID', $customerID);
		$query = $this->db->get();
		return $query->row()->num;
	}
	
	function getCustomerPaymentByAgent($agentID, $from_date, $to_date) {
		$this->db->select('p.*, c.loginID, c.firstName, c.lastName, pm.paymentMethod');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Customer as c', 'c.customerID = p.customerID', 'left');
		$this->db->where('p.agentID', $agentID);
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->order_by('p.createdDate', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	function getSeqNo($customerID) {
		$this->db->select('p.seqNo');
		$this->db->from($this->table.' as p');
		$this->db->where('p.customerID', $customerID);
		$this->db->order_by('p.seqNo', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$row = $query->row();
		$seqNo = 1;
		if ($row) {
			$seqNo = $row->seqNo + 1;
		}
		return $seqNo;
	}
	//For agent
	function addAgentPayment($data) {
		$insert = $this->db->insert('tbl_AgentPayment', $data);
		return $this->db->insert_id();
	}
	function updateAgentPayment($id, $data) {
		return $this->db->update('tbl_AgentPayment', $data, array('paymentID' => $id));
	}
	function deleteAgentPayment($id) {
		return $this->db->delete('tbl_AgentPayment', array('paymentID' => $id));
	}
	function getAgentPayments($agentID) {
		$this->db->select('p.*, pm.paymentMethod');
		$this->db->from('tbl_AgentPayment as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->where('p.agentID', $agentID);
		$this->db->order_by('p.paymentID');
		$query = $this->db->get();
		return $query->result();
	}
	function getAgentPaymentReport($agentID, $from_date, $to_date) {
		$this->db->select('p.*, pm.paymentMethod');
		$this->db->from('tbl_AgentPayment as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->where('p.agentID', $agentID);
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->order_by('p.paymentID', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	function getAgentSummary($from_date, $to_date) {
		$this->db->select("
			paymentByAgent = (select sum(chargedAmount) from tbl_CustomerPayment where agentID = a.agentID and createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'), 
			totalCashRecByAgent = (select sum(chargedAmount) from tbl_AgentPayment where agentID = a.agentID and createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'), 
			a.*
		");
		$this->db->from('tbl_Agent as a');
		$this->db->where("a.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$query = $this->db->get();
		return $query->result();
	}
	
	function getAllPaymentMethods() {
		$select = '*';
		if ($this->session->userdata('language')=='spanish') {
			$select .= ', paymentMethodSp as paymentMethod';
		}
		$this->db->select($select);
		$this->db->from('tbl_PaymentMethod');
		$this->db->where("paymentMethodID IN (".implode(",", $this->config->item('payment_method')).")");
		$query = $this->db->get();
		return $query->result();
	}
	function getLastPaymentByCustomer($customerID) {
		$this->db->select('p.*');
		$this->db->from($this->table.' as p');
		$this->db->where('p.customerID', $customerID);
		$this->db->order_by('p.createdDate', 'desc');
		$query = $this->db->get();
		return $query->row();
	}
	
	// Payment Profile =========
	function addPaymentProfile ($data) {
		$insert = $this->db->insert('tbl_AgentPaymentProfile', $data);
		return $this->db->insert_id();
	}
	function deletePaymentProfile ($where) {
		return $this->db->delete('tbl_AgentPaymentProfile', $where);
	}
	function getAgentPaymentProfiles($where='') {
		$this->db->select("*");
		$this->db->from('tbl_AgentPaymentProfile');
		$this->db->where($where);
		//code changes done by arita 
		$this->db->where('isDeleted',0);
		$this->db->where('cimPaymentProfileID >' , 0);
		$this->db->group_by('cimCardNumber');
		$this->db->order_by('profileID');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return  $query->result();
	}
	
	// Reports ==========================
	function getStoreSalesReport($agentID, $product, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, pm.paymentMethod, a.loginID as agentLoginID, vpr.vproductName, vpr.vproductType, vpr.vproductCategory, at.agentTypeName, a.firstName, a.lastName, pr.productName');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_VonecallProducts as vpr', 'vpr.vproductID = p.productID', 'left');
		$this->db->join('tbl_Product as pr', 'pr.productID = vpr.productTypeID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}		
		if ($product) {
			$this->db->where('vpr.vproductCategory', $product);			
			//$this->db->where();
		}
		$this->db->order_by('p.createdDate', 'desc');
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function getStorePaymentReport($agentID, $where='', $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, pm.paymentMethod, a.loginID as agentLoginID, at.agentTypeName, a.firstName, a.lastName');
		$this->db->from('tbl_AgentPayment'.' as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}		
		
		if($where)
			$this->db->where($where);			
		
		$this->db->order_by('p.createdDate', 'desc');
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	// Activity =========================================================
	function getStoreSalesActivity($agentID, $product, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, pm.paymentMethod, a.loginID as agentLoginID, vpr.vproductName, vpr.vproductType, vpr.vproductCategory, at.agentTypeName, a.firstName, a.lastName, pr.productName, c.loginID as phoneNumber');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_VonecallProducts as vpr', 'vpr.vproductID = p.productID', 'left');
		$this->db->join('tbl_Product as pr', 'pr.productID = vpr.productTypeID', 'left');
		$this->db->join('tbl_Customer as c', 'c.customerID = p.customerID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}		
		if ($product) {
			$this->db->where('vpr.vproductCategory', $product);			
			//$this->db->where();
		}
		$this->db->order_by('p.createdDate', 'desc');
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function getPaymentActivity($customerID, $seqNo) {
		$this->db->select('p.*, pm.paymentMethod, c.loginID as phoneNumber');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Customer as c', 'c.customerID = p.customerID', 'left');
		$this->db->where('p.customerID', $customerID);
		$this->db->where('p.seqNo', $seqNo);
		$query = $this->db->get();
		return $query->row();
	}
	
	// USA PIN Activity=======================================
	function addPinActivity($data){
		$this->db->insert('tbl_UsaPinActivity', $data);
		return $this->db->insert_id();
	}
	function getUsaPinActivity($agentID, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('up.*, a.loginID as agentLoginID, at.agentTypeName, a.firstName, a.lastName, c.loginID as phoneNumber');
		$this->db->from('tbl_UsaPinActivity as up');
		$this->db->join('tbl_Agent as a', 'a.agentID = up.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->join('tbl_Customer as c', 'c.customerID = up.customerID', 'left');
		$this->db->where("up.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($agentID > 0) {
			$this->db->where('up.agentID', $agentID);
		}		
		$this->db->order_by('up.createdDate', 'desc');
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}

// CODE ADDED BY ARITA SINGHI

// function to make old card having profileId active
 function makecard_status_active($data){
 	$agentID 		= $data['agentID'];
 	$cimCardNumber	= $data['cimCardNumber'];
 	$this->db->where('cimCardNumber' , $cimCardNumber);
 	$this->db->Where('cimPaymentProfileID > ' , 0 );
 	$this->db->where('isDeleted',1);
 	$result = $this->db->get('tbl_agentpaymentprofile');
 	if($result->num_rows() > 0 ){
 	$value = array('isDeleted'=>0);
 	$this->db->where('cimCardNumber' , $cimCardNumber);
 	$this->db->Where('cimPaymentProfileID > ' , 0 );
 	$this->db->where('agentID', $agentID);
 	$this->db->update('tbl_agentpaymentprofile' , $value);
 	}else{
 		return true;
 	}
 }//function close

// function to get user saved card
	function get_saved_card_info($agentID){
		$this->db->select('cimCardNumber , profileID ');
		$this->db->where('isDeleted',0);
		$this->db->Where('cimPaymentProfileID >',0);
		$this->db->where('agentID', $agentID); 
		$query = $this->db->get('tbl_agentpaymentprofile');
		return  $query->result_array();
	}//function close

// function to deactivate user saved card
	function deactive_saved_card($agentID){
		$profile_id		=	$_POST['id'];
		$card_number	=	$_POST['card'];
		$data 			=	array('isDeleted'=>1);
		$this->db->where('profileID',$profile_id);
		$query =$this->db->update('tbl_agentpaymentprofile',$data);
		if($query)
			$return = 1;
		else
			$return = 0;
		echo $return;
	}// function close
	
}
?>