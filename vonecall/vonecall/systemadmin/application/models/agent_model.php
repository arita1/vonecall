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
		//return $this->db->update($this->table, array('status'=>0), array('agentID' => $id));
		return $this->db->delete($this->table, array('agentID' => $id));
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
		$this->db->select('a.*, at.agentTypeName, s.stateName, rep.commissionRate as accountRepCommissionRate, rep.maxStoreCommission as maxAccountRepCommission');
		$this->db->from($this->table.' as a');
		$this->db->join($this->table.' as rep', 'a.parentAgentID = rep.agentID', 'left');
		$this->db->join('tbl_AgentType as at', 'a.agentTypeID = at.agentTypeID', 'left');
		$this->db->join('tbl_StateZip as s', 's.stateID = a.stateID', 'left');
		$this->db->where('a.agentID', $id);
		$query = $this->db->get();
		return $query->row();
	}
	function getAllAgents($type=2, $type2=0) {
		$this->db->select('a.*');
		$this->db->from($this->table.' as a');
		if($type2>0){
			$this->db->where("`a`.agentTypeID = $type OR `a`.agentTypeID = $type2");
		}else{
			$this->db->where('`a`.agentTypeID', $type);
		}
		$this->db->where('`a`.status', 1);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getTotalAgents($where=array(), $type=2, $type2=0) {
		$this->db->select("count(`a`.agentID) as num");
		$this->db->from($this->table." as a");
		if($type2>0){
			$this->db->where("`a`.agentTypeID = $type OR `a`.agentTypeID = $type2");
		}else{
			$this->db->where("`a`.agentTypeID", $type);
		}
		$this->db->where('`a`.status', 1);
		$this->db->where($where);
		
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getAgents($where, $type=2, $type2=0, $limit=0, $offset=0) { 
		$this->db->distinct();
		$this->db->select('`a`.*');
		$this->db->from($this->table.' as a');
		if($type2>0){
			$this->db->where("`a`.agentTypeID = $type OR `a`.agentTypeID = $type2");
		}else{
			$this->db->where("`a`.agentTypeID", $type);
		}
		$this->db->where($where);
		$this->db->order_by("`a`.agentID");
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		return $query->result();
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
	
	// Update balance
	function updateBalance($agentID, $amount) {
		//echo "UPDATE $this->table SET balance = balance + '$amount' WHERE agentID = '$agentID'";die;
		$this->db->query("UPDATE $this->table SET balance = balance + '$amount' WHERE agentID = '$agentID'");
	}
}
?>