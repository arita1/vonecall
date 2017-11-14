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
	function checkSecurityCode($agentID, $securityCode) {
		$this->db->select('count(agentID) as num');
		$this->db->from($this->table);
		$this->db->where('agentID', $agentID);
		$this->db->where("SUBSTRING(securityCode,4,7) = '$securityCode'");
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	function checkPassword($agentID, $password) {
		$this->db->select('count(agentID) as num');
		$this->db->from($this->table);
		$this->db->where('agentID', $agentID);
		$this->db->where('password', $password);
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	function getAgent($id) {
		
		$this->db->select('a.*, at.agentTypeName, s.stateName');
		$this->db->from($this->table.' as a');
		$this->db->join('tbl_AgentType as at', 'a.agentTypeID = at.agentTypeID', 'left');
		$this->db->join('tbl_StateZip as s', 's.stateID = a.stateID', 'left');
		$this->db->where('a.agentID', $id);
		$query = $this->db->get();
		//echo '<pre>';
		//print_r($query);die;
		//echo '<pre>';
		//echo $this->db->last_query();die;
		//print_r($query->row());die;
		return $query->row();
	}
	function getAllAgents() {
		$this->db->select('a.*');
		$this->db->from($this->table.' as a');
		$this->db->where('a.status', 1);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getTotalAgents($where) {
		$this->db->select('count(a.agentID) as num');
		$this->db->from($this->table.' as a');
		//$this->db->where('a.agentTypeID', 2);
		$this->db->where('a.status', 1);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getAgents($where, $limit, $offset) { 
		$this->db->select('a.*'); 
		$this->db->from($this->table.' as a');
		//$this->db->where('a.agentTypeID', 2);
		$this->db->where('a.status', 1);
		$this->db->where($where);
		$this->db->order_by('a.agentID');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result();
	}
	
	/*function getAgentBalanceQuery($agentID, $from_date, $to_date, $productID='') {
		$current_date = date('Y-m-d H:i:s');
		$strQuery = '';
		if ($productID) {
			$strQuery = "AgentBalanceQueryByProduct $agentID, '$from_date', '$to_date', '$current_date', '$productID'";
		} else {
			$strQuery = "AgentBalanceQuery $agentID, '$from_date', '$to_date', '$current_date'";
		}	
		
		$query = $this->db->query($strQuery);
		return $query->result();
	}*/
	function updateBalance($agentID, $amount) {
		$this->db->query("UPDATE {$this->table} SET balance = balance + '$amount' WHERE agentID = '$agentID'");
		echo $this->db->last_query();
	}
	/*function getAgentSaleQuery($agentID, $from_date, $to_date) {
		$query = $this->db->query("AgentSaleQuery $agentID, '$from_date', '$to_date'");
		return $query->row();
	}*/
	
	//Account Rep
	function getAccountRepSaleReport($agentID, $from_date, $to_date) {
		$query = $this->db->query("AccountRepSaleReport $agentID, '$from_date', '$to_date'");
		return $query->result();
	}
	
	//SubAgent
	function getSubAgents($agentID) {
		$this->db->select('a.*');
		$this->db->from($this->table.' as a');
		$this->db->where('a.parentAgentID', $agentID);
		$this->db->where('a.agentTypeID', 3);
		$this->db->where('a.status', 1);
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
	function getCommission($agentID, $productID) {
		$this->db->select('ac.*, p.productName');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->where("ac.agentID", $agentID);
		$this->db->where("ac.productID", $productID);
		$query = $this->db->get();
		return $query->row();
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
	
	/** Get Commissions **/
	function getCommissions($agentID, $where='') {
		//$this->db->distinct();
		$this->db->select('ac.*, p.productName, vp.vproductName, vp.vproductCategory, vp.vproductSkuName');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->join('tbl_VonecallProducts as vp', 'ac.vproductID = vp.vproductID', 'left');
		$this->db->where("ac.agentID", $agentID);
		if($where){
			$this->db->where($where);
		}
		$this->db->order_by('ac.ID');
		$query = $this->db->get();
		return $query->result();
	}
	function getCommissionByAgent($agentID, $where='') {
		//$this->db->distinct();
		$this->db->select('ac.*');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->where("ac.agentID", $agentID);
		if($where){
			$this->db->where($where);
		}
		//$this->db->order_by('ac.ID');
		$this->db->limit(1);
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
	
	function getCommissionByProductID($agentID, $ID) {
		$this->db->select('vp.*, ac.*');
		$this->db->from('tbl_VonecallProducts vp');
		$this->db->join('tbl_AgentCommission as ac', 'ac.vproductID = vp.vproductID', 'left');
		$this->db->where("ac.agentID", $agentID);
		$this->db->where("vp.vproductID", $ID);
		$query = $this->db->get();
		return $query->row();
	}
	
	#### Get Product Details  ####
	function getProductDetails($productID) {
		$this->db->select('ac.*');
		$this->db->from('tbl_Product ac');
		$this->db->where("ac.productID", $productID);
		$query = $this->db->get();
		return $query->row();
	}
	function getTotalProductsByAgent($agentID) {
		$this->db->select('count(ac.agentID) as num');
		$this->db->from('tbl_AgentCommission as ac');
		$this->db->where("ac.agentID", $agentID);
		$query = $this->db->get();
		return $query->row()->num;
	}
	function getAllProductsByAgent($agentID, $limit=0, $offset=0) {  
		$this->db->select('ac.*, p.productName, vp.vproductName, vp.vproductCategory');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->join('tbl_VonecallProducts as vp', 'ac.vproductID = vp.vproductID', 'left');
		$this->db->where("ac.agentID", $agentID);
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('ac.ID');
		$query = $this->db->get();
		return $query->result();
	}
	function getProductDetailsBySKU($where='') {  
		$this->db->select('vp.*, pl.logoName, cc.CountryCode, cc.CountryName');
		$this->db->from('tbl_VonecallProducts vp');
		$this->db->join('tbl_VonecallProductLogo as pl', 'pl.ppnProductID = vp.ppnProductID', 'left');
		$this->db->join('tbl_CountryCode as cc', 'cc.CountryCodeIso = vp.vproductCountryCode', 'left');
		if($where)
			$this->db->where($where);
		
		$query = $this->db->get();
		return $query->result();
	}
	
	// get Promotion Amount =======================
	function getPromotions($where=array()) {
		//print_r($where);
		$this->db->select('pr.*, p.productName');
		$this->db->from('tbl_Promotion pr');
		$this->db->join('tbl_Product as p', 'pr.productID = p.productID', 'left');
		$this->db->where('pr.status', 1);
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	// Refund request =============================
	function getSettings($where='') {
		$this->db->select('*');
		$this->db->from('tbl_GeneralSettings');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row();
	}
	
	// Unique array Sort ===========================================
	function unique_sort($array, $key) {
	    $unique_arr = array();
	    foreach ($array AS $arr) {
	
	        if (!in_array($arr->$key, $unique_arr)) {
	            $unique_arr[] = $arr->$key;
	        }
	    }
	    sort($unique_arr);
	    return $unique_arr;
	}
	
	
	function getAgentSaleQuery($agentID, $from_date, $to_date) {
		//$query = $this->db->query("AgentSaleQuery $agentID, '$from_date', '$to_date'");
		//return $query->row();
		
		$totalChargedAmount = array();
		if($this->getAgentSaleQueryChargedAmount($agentID, $from_date, $to_date)){
			$totalChargedAmount = $this->getAgentSaleQueryChargedAmount($agentID, $from_date, $to_date);	
		}
		
		$totalTotalPayment = array();
		if($this->getAgentSaleQueryTotalPayment($agentID, $from_date, $to_date)){
			$totalTotalPayment = $this->getAgentSaleQueryTotalPayment($agentID, $from_date, $to_date);	
		}
		
		$this->db->select('SUM(cp.chargedAmount) as totalSale, SUM(cp.storeCommission) as totalCommission');
		$this->db->from('tbl_CustomerPayment cp');
		$this->db->where('cp.paymentMethodID != 3');
		$this->db->where("cp.agentID", $agentID);
		$this->db->where("cp.createdDate BETWEEN '$from_date' AND '$to_date'");
		
		$query = $this->db->get();
		$saleQuery = $query->result_object();
		
		$allResult = array('Promotion' => $totalChargedAmount[0]->totalPromotion, 'Payment' => $totalTotalPayment[0]->totalPayment
		,'Sale' => $saleQuery[0]->totalSale, 'Commission' => $saleQuery[0]->totalCommission) ;
		
		return (object) $allResult;
	}
	function getAgentSaleQueryChargedAmount($agentID, $from_date, $to_date) {		
		$this->db->select('SUM(cp.chargedAmount) as totalPromotion');
		$this->db->from('tbl_CustomerPayment cp');
		$this->db->where('cp.paymentMethodID = 3');
		$this->db->where("cp.agentID", $agentID);
		$this->db->where("cp.createdDate BETWEEN '$from_date' AND '$to_date'");
		
		$query = $this->db->get();
		return $query->result();
	}
	function getAgentSaleQueryTotalPayment($agentID, $from_date, $to_date){
		$this->db->select(' SUM(ap.chargedAmount) as totalPayment');
		$this->db->from('tbl_AgentPayment as ap');
		$this->db->where("ap.agentID", $agentID);
		$this->db->where("ap.createdDate BETWEEN '$from_date' AND '$to_date'");
		
		$query = $this->db->get();
		return $query->result();
	}
	 
}
?>