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
				$this->session->set_userdata('role', 'admin');
				$this->session->set_userdata('username', $username);
				$this->session->set_userdata('usertype', $row->adminTypeCode);
				$this->session->set_userdata('userid', $row->adminID);
				$this->session->set_userdata('logindatetime', date('Y-m-d H:i:s'));
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
		$this->db->where('adminTypeID != 11');
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
		$this->db->where('a.adminTypeID != 11' );
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
		$this->db->where('a.adminTypeID != 11' );
		$this->db->order_by('a.adminID');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getCountryCodes() {
		$this->db->select('*');
		$this->db->from('tbl_CountryCode');
		$this->db->order_by('CountryName');
		$query = $this->db->get();
		return $query->result();
	}
	
	function AddCountryCodes($data) {
		$this->db->insert('tbl_CountryCode', $data);
		return $this->db->insert_id();
	}	
	function UpdateCountryCodes($where, $data) {
		return $this->db->update('tbl_CountryCode', $data, $where);
	}
	function getCountryCodeDetails($where) {
		$this->db->select('*');
		$this->db->from('tbl_CountryCode');		
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row();
	}	
	
	// Admin Permission ====================================================================
	function addAdminPermission($data){
		$this->db->insert('tbl_AdminPermission', $data);
		return $this->db->insert_id();
	}
	function getAdminPermission($id) {
		$this->db->select('*');
		$this->db->from('tbl_AdminPermission');		
		$this->db->where('adminID', $id);
		$query = $this->db->get();
		return $query->row();
	}
	
	//Product ==============================================================================
	function addProduct($data) {
		$this->db->insert('tbl_Product', $data);
	}
	function updateProduct($productID, $data) {
		return $this->db->update('tbl_Product', $data, array('productID'=>$productID));
	}
	function deleteProduct($productID) {
		return $this->db->delete('tbl_Product', array('productID'=>$productID));
	}
	function getProduct($state, $city) {
		$this->db->select('*');
		$this->db->from('tbl_Product');
		$this->db->where('productID', $productID);
		$query = $this->db->get();
		return $query->row();
	}
	function getProducts($where='') {
		$this->db->select('*');
		$this->db->from('tbl_Product');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function checkProductExist($productName, $productID=0) {
		$this->db->select('count(productID) as num');
		$this->db->from('tbl_Product');
		$this->db->where('productName', $productName);
		if ($productID) {
			$this->db->where('productID != ', $productID);
		}
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	
	function getAllProductsByAgent($agentID, $where='', $limit=0, $offset=0) {
		//$this->db->select('ac.*, p.productName, vp.*');
		$this->db->select('ac.ID,ac.agentID,ac.productID,ac.commissionRate,ac.maxStoreCommission,ac.enteredBy,ac.note as agentCommissionNote,ac.createdDate, p.productName, vp.*');
		$this->db->from('tbl_AgentCommission ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->join('tbl_VonecallProducts as vp', 'vp.productTypeID = ac.productID', 'left');
		$this->db->where("ac.agentID", $agentID);
		if($where){
			$this->db->where($where);
		}
		if ($limit>0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('ac.ID');
		$query = $this->db->get();
		return $query->result();
	}
	function getTotalProductsByAgent($agentID, $where='') {
		$this->db->select('count(ac.agentID) as num');
		$this->db->from('tbl_AgentCommission as ac');
		$this->db->join('tbl_Product as p', 'ac.productID = p.productID', 'left');
		$this->db->join('tbl_VonecallProducts as vp', 'vp.productTypeID = ac.productID', 'left');
		$this->db->where("ac.agentID", $agentID);
		if($where){
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row()->num;
	}
	//Product END =========================================================================
	
	// Truncate Table =====================================================================
	function truncateTable($table) { 		
		$this->db->query("truncate table $table");		
	}
	
	// Rates ==============================================================================		
	function addRates($data){
		$this->db->insert('tbl_Rates', $data);
		return $this->db->insert_id();
	}	
	function getRates($where='', $limit='', $offset='') {
		$this->db->select('*');
		$this->db->from('tbl_Rates');
		if($where){
			$this->db->where($where);
		}	
		if($limit){
			$this->db->limit($limit, $offset);
		}	
		$this->db->order_by('ID');	
		$query = $this->db->get();
		return $query->result();
	}
	function updateRates($rateID, $data) {
		return $this->db->update('tbl_Rates', $data, array('ID'=>$rateID));
	}
	function getTotalRates($where='') {
		$this->db->select('count(a.ID) as num');
		$this->db->from('tbl_Rates as a');	
		if($where){
			$this->db->where($where);
		}		
		$query = $this->db->get();
		return $query->row()->num;
	}
	
	// Access Number ==============================================================================	
	function addAccessNumber($data) {
		$this->db->insert('tbl_AccessNumber', $data);
		return $this->db->insert_id();
	}	
	function getAccessNumber($where='') {
		$this->db->select('*');
		$this->db->from('tbl_AccessNumber');		
		$query = $this->db->get();
		return $query->result();
	}
	function checkAccessNumber($where) {
		$this->db->select('*');
		$this->db->from('tbl_AccessNumber');		
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row();
	}
	function deleteAccessNumber($where) {
		return $this->db->delete('tbl_AccessNumber', $where);
	}
	
	// Admin Report ===============================================================================
	function addReport($data){
		$this->db->insert('tbl_AdminReports', $data);
		return $this->db->insert_id();
	}	
	function getReport($where='', $limit, $offset) {
		$this->db->select('a.*, at.firstName');
		$this->db->from('tbl_AdminReports'.' as a');
		$this->db->join($this->table.' as at', 'at.adminID = a.adminID', 'left');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('a.reportID DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result();
	}
	function getAllReport() {
		$this->db->select('a.*, at.firstName');
		$this->db->from('tbl_AdminReports'.' as a');
		$this->db->join($this->table.' as at', 'at.adminID = a.adminID', 'left');		
		$query = $this->db->get();
		return $query->result();
	}
	function getTotalReports() {
		$this->db->select('count(a.reportID) as num');
		$this->db->from('tbl_AdminReports as a');		
		$query = $this->db->get();
		return $query->row()->num;
	}
	function deleteReports($where){
		return $this->db->delete('tbl_AdminReports', $where);
	}
	
	// Vonecall Products ==========================================================================
	function addVProducts($data) {
		$this->db->insert('tbl_VonecallProducts', $data);
	}
	function updateVProduct($productID, $data) {
		return $this->db->update('tbl_VonecallProducts', $data, array('vproductID'=>$productID));
	}
	function deleteVProduct($productID) {
		return $this->db->delete('tbl_VonecallProducts', array('vproductID'=>$productID));
	}
	function getVProducts($where='', $limit='', $offset='') {
		$this->db->select('vp.*, p.productName, cc.CountryName');
		$this->db->from('tbl_VonecallProducts as vp');
		$this->db->join('tbl_Product as p', 'p.productID = vp.productTypeID', 'left');
		$this->db->join('tbl_CountryCode as cc', 'vp.vproductCountryCode = cc.CountryCodeIso', 'left');
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
	function getVProductsGroupBY($where=''){
		
		$this->db->select('vproductID, vproductSkuID, vproductName, vproductVendor, ppnProductID');
		$this->db->from('tbl_VonecallProducts');
		$this->db->distinct("ppnProductID");
		if($where){
			$this->db->where($where);
		}			
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
	//Product END =========================================================================	
		
	//promotion ======================================================================
	function addPromotion($data) {
		$this->db->insert('tbl_Promotion', $data);
		return $this->db->insert_id();
	}
	function updatePromotion($id, $data) {
		return $this->db->update('tbl_Promotion', $data, array('ID' => $id));
	}
	function updatePromotionStatus($where, $status) {
		return $this->db->update('tbl_Promotion', array('status'=>$status), $where);
	}
	function deletePromotion($id) {
		return $this->db->update('tbl_Promotion', array('status'=>0), array('ID' => $id));
		//return $this->db->delete('tbl_Promotion', array('ID' => $id));
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
	function getPromotions($where=array()) {
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
	function getPromotionHistory() {
		$this->db->select('pr.*, p.productName');
		$this->db->from('tbl_Promotion pr');
		$this->db->join('tbl_Product as p', 'pr.productID = p.productID', 'left');
		$this->db->where('pr.status', 0);
		$query = $this->db->get();
		return $query->result();
	}
	function checkExistPromotion($productID=0) {
		$this->db->select('count(ID) as num');
		$this->db->from('tbl_Promotion');
		$this->db->where('productID', $productID);
		$this->db->where('status', 1);
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	
	//Message ================================================================================
	function addMessage($data) {
		$this->db->insert('tbl_Message', $data);
		return $this->db->insert_id();
	}
	function updateMessage($id, $data) {
		return $this->db->update('tbl_Message', $data, array('ID' => $id));
	}
	function getMessage($where='') {
		$this->db->select('*');
		$this->db->from('tbl_Message');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row();
	}
	
	// General Settings ===============================================================================
	function addSettings($data){
		return $this->db->insert('tbl_GeneralSettings', $data);
	}
	function updateSettings($where, $data) {
		return $this->db->update('tbl_GeneralSettings', $data, $where);
	}
	function getSettings($where='') {
		$this->db->select('*');
		$this->db->from('tbl_GeneralSettings');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row();
	}
	
	// Upload Product Image Query
	function addImageSettings($data){
		return $this->db->insert('tbl_VonecallProductLogo', $data);
	}
	function getProductImages($where='') {
		$this->db->select('pl.*, vp.vproductVendor, cc.CountryName');
		$this->db->from('tbl_VonecallProductLogo pl');
		$this->db->join('tbl_VonecallProducts as vp', 'pl.ppnProductID = vp.ppnProductID', 'left');
		$this->db->join('tbl_CountryCode as cc', 'vp.vproductCountryCode = cc.CountryCodeIso', 'left');
		
		if($where)
			$this->db->where('pr.status', 0);
		
		$query = $this->db->get();
		return $query->result();
	}
	
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
	
	//Phone Numbers ==============================================================================
	function addPhoneNumber($data) {
		$this->db->insert('tbl_PhoneNumbers', $data);
	}
	function updatePhoneNumber($where, $data) {
		return $this->db->update('tbl_PhoneNumbers', $data, $where);
	}
	function deletePhoneNumber($where) {
		return $this->db->delete('tbl_PhoneNumbers', $where);
	}	
	function getPhoneNumbers($where='') {
		$this->db->select('*');
		$this->db->from('tbl_PhoneNumbers');
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	// Calling Cards Batch==============================================================================
	function addCardBatch($data){
		return $this->db->insert('tbl_CallingCardBatch', $data);
	}
	function updateCardBatch($id, $data) {
		return $this->db->update('tbl_CallingCardBatch', $data, array('batchID' => $id));
	}
	function deleteCardBatch($id) {
		return $this->db->delete('tbl_CallingCardBatch', array('batchID' => $id));
	}	
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
	function addCallingCard($data){
		return $this->db->insert('tbl_CallingCard', $data);
	}
	function updateCallingCard($id, $data) {
		return $this->db->update('tbl_CallingCard', $data, array('callingCardID' => $id));
	}
	function deleteCallingCard($id) {
		return $this->db->delete('tbl_CallingCard', array('callingCardID' => $id));
	}	
	function getCallingCards($where='', $limit='', $offset='') {
		$this->db->select('cc.*, cb.*');
		$this->db->from('tbl_CallingCard as cc');
		$this->db->join('tbl_CallingCardBatch as cb', 'cb.batchID = cc.callingCardBatchName', 'left');
		if ($where) {
			$this->db->where($where);
		}
		if($limit){
			$this->db->limit($limit, $offset);
		}	
		$this->db->order_by('cc.callingCardID');
		
		$query = $this->db->get();
		return $query->result();
	}	

	// Calling Cards Access Numbers==============================================================================
	function addCallingCardAccessNumbers($data){
		return $this->db->insert('tbl_CallingCardAccessNumbers', $data);
	}
	function updateCallingCardAccessNumbers($id, $data) {
		return $this->db->update('tbl_CallingCardAccessNumbers', $data, array('accessID' => $id));
	}
	function deleteCallingCardAccessNumbers($id) {
		return $this->db->delete('tbl_CallingCardAccessNumbers', array('accessID' => $id));
	}	
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
}
?>