<?php
class Group extends CI_Controller {
	
	private $data = array();
	private $error = array();
	
	function __construct() {
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->model('admin_model');
		$this->load->model('contact_model');
		
		if($this->session->userdata('message')){
			$this->data['message'] = $this->session->userdata('message');
			$this->session->unset_userdata('message');
		}
	}
	public function index() {
		
	}

	public function groups() {		
		$this->roleuser->checkLogin();
		
		if(isset($_POST) && count($_POST) > 0 ){
			$id = strlen($_POST['edit'])>0?$_POST['edit']:0;
			if (strlen(trim($_POST['group_name'])) < 1) {
                $this->error['group_name'] = 'Group name is required!';
            }
			if (strlen(trim($_POST['group_description'])) < 1) {
                $this->error['group_description'] = 'Description required!';
            }

			if(!$this->error){
				$data = array(' groupName ' => $_POST['group_name'], 'groupDescription' => $_POST['group_description']);
				
				if($id){
					$this->contact_model->updateGroup($id, $data);
					$this->session->set_userdata('message', 'Group Successfully Updated');
				} else{
					$this->contact_model->addGroup($data);
					$this->session->set_userdata('message', 'Group Successfully Added');
				}			
				redirect('all-groups');
			}
		}
		
		$getAllGroups = $this->contact_model->getAllGroups();
		
		$this->data['error'] 			= $this->error;
		$this->data['allGroups']   		= $getAllGroups;
		$this->data['current_page'] 	= 'group';
		$this->data['sub_current_page'] = 'groups';
		$this->load->view('group/_group', $this->data);
	}
	public function delete_group(){
		$data = array('success'=>0,'text'=>'Delete fail! Please try again!');
		$this->contact_model->deleteContacts(array('groupID'=>$_POST['id']));
		$this->contact_model->deleteGroups(array('groupID'=>$_POST['id']));
		$this->session->set_userdata('message', 'You has been delete group successfully!');
		$data = array('success'=>1);
		echo json_encode($data);
	}
		
	public function group_contacts($groupID=''){
		
		$getContacts 	 = $this->contact_model->getAllContacts(array('groupID' => $groupID, 'optOut' => NULL));
		$getGroupDetails = $this->contact_model->getAllGroups(array('groupID'=>$groupID));
		
		$this->data['groupContats']		= $getContacts;
		$this->data['groupDetails']		= $getGroupDetails[0];
		$this->data['error'] 			= $this->error;		
		$this->data['current_page'] 	= 'group';
		$this->data['sub_current_page'] = 'group_contacts';
		$this->load->view('group/_group', $this->data);
	}
	public function delete_group_contacts($groupID){		
		if(isset($_POST) && count($_POST) > 0 ){
			foreach($_POST['contactID'] as $contactID){
				$this->contact_model->deleteContacts(array('contactID'=>$contactID));
			}
			$this->session->set_userdata('message', 'Contacts deleted successfully!');
			redirect('group-contacts/'.$groupID);
		}
	}
	
	// Filter Contact By Group Ajax Search
	public function filterContactsByGroups(){
		if($_REQUEST['groupID']){
			$getContacts 	 = $this->contact_model->getAllContacts(array('groupID' => $_REQUEST['groupID'], 'optOut' => NULL));
			$getGroupDetails = $this->contact_model->getAllGroups(array('groupID'=>$_REQUEST['groupID']));
			
			if(isset($_REQUEST['optout']))
				$getContacts = $this->contact_model->getAllContacts(array('groupID' => $_REQUEST['groupID'], 'optOut' => 1));
			
			$getAllContacts = array();
			foreach($getContacts as $contact){
				$getAllContacts[] = array( 'contactID' 		=> $contact->contactID, 
										   'groupID'   		=> $contact->groupID, 
										   'contactName'    => $contact->contactName, 
										   'contactNumber'  => $contact->contactNumber,
										   'groupName'		=> $getGroupDetails[0]->groupName );
			}
		}else{
			$getAllContacts = $this->contact_model->getContacts();
		}
		
		if($getAllContacts){
			echo json_encode($getAllContacts);	
		}else{
			echo 0;
		}
	}
	
	// Private Functions ===========================================================================
	private function getUserInfo() {
		//customer info			
		$info = $this->admin_model->getAdmin((int)$this->session->userdata('sms_userid'));			
		return $info;
	}
}
?>