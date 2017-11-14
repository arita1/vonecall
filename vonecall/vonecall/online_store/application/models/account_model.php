<?php

class Account_model extends CI_Model {
	var $table = 'accounts';    
    
	function Account_model() {
	   parent::__construct();
	}
	
	//start accounts ========================================================================================================================
	function add($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	function update($id, $data) {
		return $this->db->update($this->table, $data, array('account_id' => $id));
	}
	function delete($id) {
		return $this->db->delete($this->table, array('account_id' => $id));
	}
	function getAccount($id) {
		$this->db->select('a.account_id, a.account, a.pin, a.status, a.billing_type, a.balance');
		$this->db->from('accounts as a');
		$this->db->where('a.account_id', $id);
		$query = $this->db->get();
		return $query->row();
	}
	//end accounts ==========================================================================================================================
	  
	//start billings ========================================================================================================================
	function addBilling($data) {
		$this->db->insert('billing', $data);
		return $this->db->insert_id();
	}
	function updateBilling($id, $data) {
		return $this->db->update('billing', $data, array('billing_id' => $id));
	}
	function deleteBilling($id) {
		return $this->db->delete('billing', array('billing_id' => $id));
	}
	function getCallDetails($account_id) {
		$this->db->select('b.dnis, b.ani, b.connect_date_time, b.detail, b.description, b.actual_duration, b.amount');
		$this->db->from('billing as b');
		$this->db->where('b.account_id', $account_id);
		$this->db->where('b.entry_type in (1,2)');
		$this->db->where('b.amount > 0');
		$this->db->order_by('b.billing_id', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	//end billings ==========================================================================================================================
	
	//start account_aliases =================================================================================================================
	function addPhone ($data) {
		$data['account_alias_type'] = 1;
		$data['dnis'] = '*';
		$this->db->insert('account_aliases', $data);
		return $this->db->insert_id();
	}
	function updatePhone ($account_alias_id, $data) {
		return $this->db->update('account_aliases', $data, array('account_alias_id' => $account_alias_id));
	}
	function deletePhone ($account_alias_id) {
		return $this->db->delete('account_aliases', array('account_alias_id' => $account_alias_id));
	}
	function checkPhoneNumberExist($phone_number, $account_id=0) {
		$this->db->select('count(account_alias_id) as num');
		$this->db->from('account_aliases');
		$this->db->where('alias', $phone_number);
		if ($account_id) {
			$this->db->where('account_id != '.$account_id);
		}
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	function getPhone($id) {
		$this->db->select('aa.account_alias_id, aa.dnis, aa.alias, aa.account_id, aa.account_alias_type, aa.password');
		$this->db->from('account_aliases as aa');
		$this->db->where('aa.account_alias_id', $id);
		$query = $this->db->get();
		return $query->row();
	}
	function getPhones($account_id) {
		$this->db->select('aa.account_alias_id, aa.dnis, aa.alias, aa.account_id, aa.account_alias_type, aa.password');
		$this->db->from('account_aliases as aa');
		$this->db->where('aa.account_id', $account_id);
		$this->db->order_by('aa.account_alias_id', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
	//end account_aliases========================================================================================================
	
	//start speed dial========================================================================================================
	function addSpeedDial ($data) {
		$this->db->insert('speed', $data);
		return $this->db->insert_id();
	}
    function updateSpeedDial ($account_number, $entry, $data) {
		return $this->db->update('speed', $data, array('account' => $account_number, 'entry' => $entry));
	}
	function deleteSpeedDial ($account_number, $entry) {
		return $this->db->delete('speed', array('account' => $account_number, 'entry' => $entry));
	}
	function checkSpeedDialExist ($account_number, $entry) {
		$this->db->select('count(account) as num');
		$this->db->from('speed');
		$this->db->where('account', $account_number);
		$this->db->where('entry', $entry);
		$query = $this->db->get();
		$row = $query->row();
		return $row->num;
	}
	function getSpeedDials($account_number) {
		$this->db->select('s.account, s.entry, s.number, s.description');
		$this->db->from('speed as s');
		$this->db->where('s.account', $account_number);
		$this->db->order_by('CONVERT(INT, s.entry)');
		$query = $this->db->get();
		return $query->result();
	}
	//end speed dial========================================================================================================

	
}
?>