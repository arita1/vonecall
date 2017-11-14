<?php

class Agent_model extends CI_Model {
	var $table = 'tbl_Agent';    
    
	function Agent_model() {
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
		return $this->db->update($this->table, array('status'=>0), array('agentID' => $id));
		//return $this->db->delete($this->table, array('agentID' => $id));
	}
	
	function checkAgentExist($loginID, $agentID) {
		$this->db->select('count(agentID) as num');
		$this->db->from($this->table);
		$this->db->where('loginID', $loginID);
		if ($agentID) {
			$this->db->where('agentID != ', $agentID);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function checkSecurityCode($agentID, $securityCode) {
		$this->db->select('count(agentID) as num');
		$this->db->from($this->table);
		$this->db->where('agentID', $agentID);
		$this->db->where("SUBSTRING(securityCode,4,7) = '$securityCode'");
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	function getAgent($id) {
		$this->db->select('a.*, at.agentTypeName, s.stateName, rep.commissionRate as accountRepCommissionRate');
		$this->db->from($this->table.' as a');
		$this->db->join($this->table.' as rep', 'a.parentAgentID = rep.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'a.agentTypeID = at.agentTypeID', 'left');
		$this->db->join('tbl_StateZip as s', 's.stateID = a.stateID', 'left');
		$this->db->where('a.agentID', $id);
		$this->db->where('a.status', 1);
		$query = $this->db->get();
		return $query->row();
	}
	function getAllAgents($type=2) {
		$this->db->select('a.*');
		$this->db->from($this->table.' as a');
		$this->db->where('a.agentTypeID', $type);
		$this->db->where('a.status', 1);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getTotalAgents($where, $type=2) {
		$this->db->select('count(a.agentID) as num');
		$this->db->from($this->table.' as a');
		$this->db->where('a.agentTypeID', $type);
		$this->db->where('a.status', 1);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getAgents($where, $type=2, $limit=0, $offset=0) {
		$this->db->select('a.*');
		$this->db->from($this->table.' as a');
		$this->db->where('a.agentTypeID', $type);
		$this->db->where('a.status', 1);
		$this->db->where($where);
		$this->db->order_by('a.agentID');
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function getAgentBalanceQuery($agentID, $from_date, $to_date) {
		$current_date = date('Y-m-d H:i:s');
		$query = $this->db->query("AgentBalanceQuery $agentID, '$from_date', '$to_date', '$current_date'");
		return $query->result();
	}

	function getAgentBalanceQueryByDist($distID, $from_date, $to_date) {
		$this->db->select('ap.createdDate, "Payment" as category, a.loginID, ap.chargedAmount,ap.comment, ap.enteredBy, a.loginID as accountRepLoginID, cp.createdDate, "Sale" as category, c.loginID, cp.chargedAmount, cp.storeCommission, cp.comment, cp.enteredBy, cp.accountRepCommission, p.productName');
		$this->db->from('tbl_AgentPayment ap');
		$this->db->join('tbl_Agent as a', 'a.agentID = ap.agentID ', 'left');
		$this->db->join('tbl_CustomerPayment as cp', 'a.agentID = cp.accountRepID', 'left');
		$this->db->join('tbl_Customer as c', 'c.customerID = cp.customerID', 'left');
		$this->db->join('tbl_Product as p', 'p.productID = cp.productID', 'left');
		$this->db->where('cp.accountRepID', $distID);
		$this->db->where("ap.createdDate BETWEEN '$from_date' AND '$to_date'");
		$query = $this->db->get();
		return $query->row();	
	}
	/*
	function getAgentBalanceQueryByDistributor($distID, $from_date, $to_date) {
		$this->db->select('ap.*, cp.*');
		$this->db->from('tbl_AgentPayment as ap');
		$this->db->join('tbl_Agent as a', 'a.agentID = ap.agentID ', 'left');
		$this->db->join('tbl_Agent as dist', 'dist.parentAgentID = a.parentAgentID ', 'left');
		$this->db->join('tbl_CustomerPayment as cp', 'a.agentID = cp.accountRepID', 'left');
		//$this->db->where('dist.parentAgentID', $distID);
		$this->db->where("ap.createdDate BETWEEN '$from_date' AND '$to_date'");
		//echo '<pre>';print_r($this->db);die;		
		$query = $this->db->get();
		return $query->result();	
	}*/
	
	function updateBalance($agentID, $amount) {
		$this->db->query("UPDATE {$this->table} SET balance = balance + '$amount' WHERE agentID = '$agentID'");
	}
	/*function getAgentSaleQuery($agentID, $from_date, $to_date) {
		$query = $this->db->query("AgentSaleQuery $agentID, '$from_date', '$to_date'");
		return $query->row();
	}*/
	function getTotalAgentSale($agentID, $from_date, $to_date) {
		$this->db->select('sum(cp.chargedAmount) as Sale, sum(cp.storeCommission) as Commission');
		$this->db->from('tbl_CustomerPayment as cp');
		//$this->db->join('tbl_CustomerPayment as cp', 'ap.agentID = cp.customerID', 'left');
		$this->db->where('cp.agentID', $agentID);
		$this->db->where("cp.createdDate BETWEEN '$from_date' AND '$to_date'");
		$query = $this->db->get();
		return $query->row();
	}
	function getTotalAgentPayment($agentID, $from_date, $to_date) {
		$this->db->select('sum(ap.chargedAmount) as Payment');
		$this->db->from('tbl_AgentPayment as ap');
		$this->db->where('ap.agentID', $agentID);
		$this->db->where("ap.createdDate BETWEEN '$from_date' AND '$to_date'");
		$query = $this->db->get();
		return $query->row();
	}
	function getTotalAgentSaleByDist($distID, $from_date, $to_date, $agentID='') {
		$this->db->select('sum(cp.chargedAmount) as Sale, sum(cp.accountRepCommission) as Commission');
		$this->db->from('tbl_CustomerPayment as cp');
		$this->db->where('cp.accountRepID', $distID);
		if($agentID > 0){
			$this->db->where('cp.agentID', $agentID);
		}
		$this->db->where("cp.createdDate BETWEEN '$from_date' AND '$to_date'");
		$query = $this->db->get();
		return $query->row();
	}
	function checkAgentEmailExist($emailID, $agentID) {
		$this->db->select('count(agentID) as num');
		$this->db->from($this->table);
		$this->db->where('email', $emailID);
		if ($agentID) {
			$this->db->where('agentID != ', $agentID);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	//Account Rep
	/*function getAccountRepSaleReport($accountRepID, $agentID, $from_date, $to_date) {
		if ($agentID > 0) {
			$query = $this->db->query("AccountRepSaleReportByStore $accountRepID, $agentID, '$from_date', '$to_date'");
			return $query->result();
		}
		$query = $this->db->query("AccountRepSaleReport $accountRepID, '$from_date', '$to_date'");
		return $query->result();
	}*/
	
	function addCommissionRateHistory($data) {
		$this->db->insert('tbl_CommissionRateHistory', $data);
		return $this->db->insert_id();
	}
	function getCommissionRateHistory($agentID) {
		$this->db->select('*');
		$this->db->from('tbl_CommissionRateHistory');
		$this->db->where("agentID", $agentID);
		$this->db->order_by('ID desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	//Creditcard History
	function addCreditcardHistory($data) {
		$this->db->insert('tbl_AgentCreditcardHistory', $data);
		return $this->db->insert_id();
	}
    function updateCreditcardHistory($id, $data) {
		return $this->db->update('tbl_AgentCreditcardHistory', $data, array('ID' => $id));
	}
	function deleteCreditcardHistory($id) {
		return $this->db->delete('tbl_AgentCreditcardHistory', array('ID' => $id));
	}
	function getCreditcardHistories($agentID) {
		$this->db->select('ch.*, ct.creditCardType');
		$this->db->from('tbl_AgentCreditcardHistory as ch');
		$this->db->join('tbl_CreditCardType as ct', 'ch.creditCardTypeID = ct.creditCardTypeID', 'left');
		$this->db->where("ch.agentID", $agentID);
		$query = $this->db->get();
		return $query->result();
	}
	function getCreditcardHistory($id) {
		$this->db->select('ch.*, ct.creditCardType');
		$this->db->from('tbl_AgentCreditcardHistory as ch');
		$this->db->join('tbl_CreditCardType as ct', 'ch.creditCardTypeID = ct.creditCardTypeID', 'left');
		$this->db->where("ch.ID", $id);
		$query = $this->db->get();
		return $query->row();
	}
	function checkCreditcardHistory($agentID, $creditCardNumber) {
		$this->db->select('ch.ID');
		$this->db->from('tbl_AgentCreditcardHistory as ch');
		$this->db->where("ch.agentID", $agentID);
		$this->db->where("ch.creditCardNumber", $creditCardNumber);
		$query = $this->db->get();
		$row = $query->row();
		return $row?$row->ID:0;
	}
	
	//commission
	function addCommission($data) {
		$this->db->insert('tbl_AgentCommission', $data);
		return $this->db->insert_id();
	}
	function updateCommission($id, $data) {
		return $this->db->update('tbl_AgentCommission', $data, array('ID' => $id));
	}
	function deleteCommission($id) {
		return $this->db->delete('tbl_AgentCommission', array('ID' => $id));
	}
	function deleteCommissionByAgent($where) {
		return $this->db->delete('tbl_AgentCommission', $where);
	}
	function getCommission($agentID, $productID) {
		$this->db->select('ac.*, p.productName');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->where("ac.agentID", $agentID);
		$this->db->where("ac.productID", $productID);
		$query = $this->db->get();
		return $query->row();
	}
	function getCommissions($agentID) {
		$this->db->select('ac.*, p.productName');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->where("ac.agentID", $agentID);
		$query = $this->db->get();
		return $query->result();
	}
	function checkCommissionExist($agentID, $productID, $id) {
		$this->db->select('count(ID) as num');
		$this->db->from('tbl_AgentCommission');
		$this->db->where('agentID', $agentID);
		$this->db->where('productID', $productID);
		if ($id) {
			$this->db->where('ID != ', $id);
		}
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	
	// Commission Queries 19/sept =============================================
	function checkCommissionExistByVProducts($agentID, $vproductID) {
		$this->db->select('ac.*');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->where("ac.agentID", $agentID);
		$this->db->where("ac.vproductID", $vproductID);
		$query = $this->db->get();
		return $query->row();
	}
	function getCommissionByProductSKU($agentID, $skuID) {
		$this->db->select('vp.*, ac.*');
		$this->db->from('tbl_VonecallProducts vp');
		$this->db->join('tbl_AgentCommission as ac', 'ac.vproductID = vp.vproductID', 'left');
		$this->db->where("ac.agentID", $agentID);
		$this->db->where("vp.vproductSkuID", $skuID);
		$query = $this->db->get();
		return $query->row();
	}
	
	function getCommissionByProduct($where='') {
		$this->db->select('vp.*, ac.*');
		$this->db->from('tbl_VonecallProducts vp');
		$this->db->join('tbl_AgentCommission as ac', 'ac.vproductID = vp.vproductID', 'left');
		if($where)
			$this->db->where($where);
		//$this->db->where("ac.agentID", $agentID);
		//$this->db->where("vp.vproductSkuID", $skuID);
		$query = $this->db->get();
		return $query->row();
	}
	
	// Promotion Message
	function getMessage($where='') {
		$this->db->select('*');
		$this->db->from('tbl_Message');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row();
	}
}
?>