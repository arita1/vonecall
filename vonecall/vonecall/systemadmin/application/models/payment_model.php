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
		$this->db->select('p.*, pm.paymentMethod, pr.productName');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Product as pr', 'p.productID = pr.productID', 'left');
		$this->db->where('p.customerID', $customerID);
		$this->db->where('p.seqNo', $seqNo);
		$query = $this->db->get();
		return $query->row();
	}
	function getPaymentByCustomer($customerID) {
		$this->db->select('p.*, pm.paymentMethod, a.loginID as agentLoginID, pr.productName');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Product as pr', 'p.productID = pr.productID', 'left');
		$this->db->where('p.customerID', $customerID);
		$this->db->order_by('p.createdDate', 'desc');
		$query = $this->db->get();
		return $query->result();
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
	function deleteAgentPaymentByAgentID($where) {
		return $this->db->delete('tbl_AgentPayment', $where);
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
	
	function getAllPaymentMethods() {
		$this->db->select('*');
		$this->db->from('tbl_PaymentMethod');
		$this->db->where("paymentMethodID IN (".implode(",", $this->config->item('payment_method')).")");
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

	function getAllPayments() {
		$this->db->select('p.*, pm.paymentMethod, a.loginID as agentLoginID, vpr.vproductName, vpr.vproductType, at.agentTypeName, a.firstName, a.lastName, a.company_name, pr.productName');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_VonecallProducts as vpr', 'vpr.vproductID = p.productID', 'left');
		$this->db->join('tbl_Product as pr', 'pr.productID = vpr.productTypeID', 'left');
		$this->db->order_by('p.createdDate', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	// Payment Profile (Authorize.net)
	function deletePaymentProfile ($where) {
		return $this->db->delete('tbl_AgentPaymentProfile', $where);
	}
	
	function getStorePaymentReportByDist($distID, $agentID, $product, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, pm.paymentMethod, a.loginID as agentLoginID, vpr.vproductName, vpr.vproductType, vpr.vproductCategory, at.agentTypeName, a.firstName, a.lastName, a.company_name, pr.productName');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_VonecallProducts as vpr', 'vpr.vproductID = p.productID', 'left');
		$this->db->join('tbl_Product as pr', 'pr.productID = vpr.productTypeID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($distID > 0) {
			$this->db->where('p.accountRepID', $distID);
		}
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}
		if ($product) { 
			$this->db->where('vpr.vproductCategory', $product);
		}
		$this->db->order_by('p.createdDate', 'desc');
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function getStoreFundsReport($distID, $agentID, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, pm.paymentMethod, a.loginID as agentLoginID, at.agentTypeName, a.firstName, a.lastName, a.company_name');
		$this->db->from('tbl_AgentPayment as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');		
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($distID > 0) {
			$this->db->where('p.accountRepID', $distID);
		}
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}
		$this->db->order_by('p.createdDate', 'desc');
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}

}
?>