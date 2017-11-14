<?php

class Payment_model extends CI_Model {
	var $table = 'tbl_CustomerPayment';	
	
	function Payment_model() {
	   parent::__construct();
	}
	
	//Sale Report
	function getTotalSaleReportDaily($agentID, $from_date, $to_date) {
		$this->db->select("count(p.paymentID) as num");
		$this->db->from('tbl_AgentPayment as p');
		$this->db->join('tbl_Agent as agent', 'agent.agentID = p.agentID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->where('p.accountRepID', $this->session->userdata('rep_userid'));
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getSaleReportDaily($agentID, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, agent.loginID as agentLoginID');
		$this->db->from('tbl_AgentPayment as p');
		$this->db->join('tbl_Agent as agent', 'agent.agentID = p.agentID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->where('p.accountRepID', $this->session->userdata('rep_userid'));
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
	function getSaleReportSummary($agentID, $from_date, $to_date) {
		$this->db->select("agentLoginID = (select loginID from tbl_Agent where agentID = p.agentID), sum(p.chargedAmount) as chargedAmount, sum(p.chargedDiscount) as chargedDiscount, sum(p.accountRepCommission) as accountRepCommission");
		$this->db->from('tbl_AgentPayment as p');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->where('p.accountRepID', $this->session->userdata('rep_userid'));
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}
		$this->db->group_by('p.agentID');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getTotalStoreSaleQuery($agentID, $from_date, $to_date) {
		$this->db->select("count(p.customerID) as num");
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Customer as c', 'c.customerID = p.customerID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->where('p.paymentMethodID != '.$this->config->item('payment_promotion'));
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getStoreSaleQuery($agentID, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, c.firstName, c.lastName, c.loginID, pm.paymentMethod, a.loginID as agentLoginID');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Customer as c', 'c.customerID = p.customerID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->where('p.paymentMethodID != '.$this->config->item('payment_promotion'));
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
	function getAgentPayment($paymentID) {
		$this->db->select('p.*, pm.paymentMethod, agent.loginID as agentLoginID, rep.loginID as accountRepLoginID');
		$this->db->from('tbl_AgentPayment as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Agent as agent', 'agent.agentID = p.agentID', 'left');
		$this->db->join('tbl_Agent as rep', 'rep.agentID = p.accountRepID', 'left');
		$this->db->where('p.paymentID', $paymentID);
		$query = $this->db->get();
		return $query->row();
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
	function getTotalAgentPaymentByCond($where='') {
		$this->db->select("sum(p.chargedAmount) as total");
		$this->db->from('tbl_AgentPayment as p');
		$this->db->where('p.accountRepID', $this->session->userdata('rep_userid'));
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row()->total;
	}
	function getAgentPaymentByCond($where='') {
		$this->db->select('p.*, pm.paymentMethod, agent.loginID as agentLoginID, rep.loginID as accountRepLoginID');
		$this->db->from('tbl_AgentPayment as p');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Agent as agent', 'agent.agentID = p.agentID', 'left');
		$this->db->join('tbl_Agent as rep', 'rep.agentID = p.accountRepID', 'left');
		$this->db->where('p.accountRepID', $this->session->userdata('rep_userid'));
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('p.paymentID', 'desc');
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
	
	function getCustomerPaymentReportByRep($accountRepID, $agentID, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, c.firstName, c.lastName, c.loginID, store.loginID as agentLoginID, pm.paymentMethod, rep.loginID as accountRepLoginID, pr.productName,
		promotion = (select chargedAmount from '.$this->table.' where createdDate = p.createdDate and paymentMethodID = '.$this->config->item('payment_promotion').')');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Customer as c', 'c.customerID = p.customerID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_Product as pr', 'p.productID = pr.productID', 'left');
		$this->db->join('tbl_Agent as store', 'store.agentID = p.agentID', 'left');
		$this->db->join('tbl_Agent as rep', 'rep.agentID = p.accountRepID', 'left');
		$this->db->where('p.paymentMethodID != '.$this->config->item('payment_promotion')." AND p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($accountRepID > 0) {
			$this->db->where('p.accountRepID', $accountRepID);
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
	
	/*function getAgentPaymentReportByRep($accountRepID, $agentID, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, a.loginID, pm.paymentMethod');
		$this->db->from('tbl_AgentPayment as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($accountRepID > 0) {
			//$this->db->where('p.accountRepID', $accountRepID);
		}
		if ($agentID > 0) {
			$this->db->where('p.agentID', $agentID);
		}
		$this->db->order_by('p.paymentID', 'desc');
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
	}*/
	function getStorePaymentReportByDist($accountRepID, $agentID, $from_date, $to_date, $limit=0, $offset=0) {
		$this->db->select('p.*, pm.paymentMethod, a.loginID as agentLoginID, vpr.vproductName, vpr.vproductType, at.agentTypeName, a.firstName, a.lastName, pr.productName');
		$this->db->from($this->table.' as p');
		$this->db->join('tbl_Agent as a', 'a.agentID = p.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'at.agentTypeID = a.agentTypeID', 'left');
		$this->db->join('tbl_PaymentMethod as pm', 'pm.paymentMethodID = p.paymentMethodID', 'left');
		$this->db->join('tbl_VonecallProducts as vpr', 'vpr.vproductID = p.productID', 'left');
		$this->db->join('tbl_Product as pr', 'pr.productID = vpr.productTypeID', 'left');
		$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		if ($accountRepID > 0) {
			$this->db->where('p.accountRepID', $accountRepID);
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
	
	function getSaleCommissionSummary($agentID=0) {
		$this->db->select("productName = (select productName from tbl_Product where productID = cp.productID), sum(cp.chargedAmount) as chargedAmount, sum(cp.storeCommission) as storeCommission, sum(cp.accountRepCommission) as accountRepCommission");
		$this->db->from('tbl_CustomerPayment as cp');
		//$this->db->where("p.createdDate BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'");
		$this->db->where('cp.accountRepID', $this->session->userdata('rep_userid'));
		if ($agentID > 0) {
			$this->db->where('cp.agentID', $agentID);
		}
		$this->db->group_by('cp.productID');
		$query = $this->db->get();
		return $query->result();
	}
	
	// Saved Card
	function addCardDetails ($data) {
		$insert = $this->db->insert('tbl_AgentPaymentProfile', $data);
		return $this->db->insert_id();
	}	
	function getAgentPaymentProfiles($where='') {
		$this->db->select('*');
		$this->db->from('tbl_AgentPaymentProfile');
		$this->db->where($where);
		$this->db->order_by('profileID');
		$query = $this->db->get();
		return $query->result();
	} 	
	function deletePaymentProfile ($where) {
		return $this->db->delete('tbl_AgentPaymentProfile', $where);
	}
	
}
?>