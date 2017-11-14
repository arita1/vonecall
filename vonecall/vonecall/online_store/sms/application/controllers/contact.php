<?php
class Contact extends CI_Controller {
	
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
		
	public function all_contacts(){
		$this->roleuser->checkLogin();
		$getAllContacts = $this->contact_model->getContacts(array('optOut'=>null));
		
		//getGroups
		$groupList 	= array();
		$groupList[''] = '-- All --';
		$groups = $this->contact_model->getAllGroups();
		foreach ($groups as $item) {
			$groupList[$item->groupID] = $item->groupName;
		}
		
		$this->data['option_groups']    = $groupList;		
		$this->data['allContacts'] 		= $getAllContacts;
		$this->data['error'] 			= $this->error;		
		$this->data['current_page'] 	= 'contact';
		$this->data['sub_current_page'] = 'all_contacts';
		$this->load->view('contact/_contacts', $this->data);
	}
	public function contacts(){
		$this->roleuser->checkLogin();
		
		if (isset($_POST) && count($_POST) > 0) {
			if(strlen(trim($_POST['group'])) < 1){
				$this->error['group'] = 'Group Required!'; 
			}	
			
			if (!is_numeric($_POST['phoneNumber']) || strlen($_POST['phoneNumber']) != 10) {
				$this->error['phoneNumber'] = 'Invalid phone number!';			        	
	        }else{
	        	$checkNumber = $this->contact_model->getAllContacts(array('contactNumber'=> $_POST['phoneNumber'], 'groupID' => $_POST['group']));
		        if($checkNumber){
		        	$this->error['phoneNumber'] = 'This phone number already exist in group!';
		        }	
	        }					
			
			if(!$this->error){
				$contactEmails = $this->contact_model->contactNumberEmail($_POST['phoneNumber']);			//Check Number is Valid Cellphone or not by DATA24x7
				if($contactEmails->results->result[0]->status=='OK'){
					$contactData = array('contactNumber' 		=> $_POST['phoneNumber'],
										 'groupID'	 			=> $_POST['group'],
										 'contactNumberEmail'	=> addslashes($contactEmails->results->result[0]->sms_address),
										 'date_time' 			=> date('Y-m-d H:i:s')
								   );
					$this->contact_model->add($contactData);
					$this->session->set_userdata('message', 'Records Successfully Added');						
					redirect('contacts');
				}else{
					$this->error['contact_phone'] = 'Invalid Cellphone number';
				}					
			}
			$this->data += $_POST;	
		}
		$this->data['sub_current_page'] = 'add_contact';
		$this->data['sub_title'] 		= 'Add Contact';
						
		//getGroups
		$groupList 	= array();
		$groupList[''] = '-- Select --';
		$groups = $this->contact_model->getAllGroups();
		foreach ($groups as $item) {
			$groupList[$item->groupID] = $item->groupName;
		}		
		$this->data['option_groups']  = $groupList;
					
		$this->data['error'] 			= $this->error;		
		$this->data['current_page'] 	= 'contact';
		$this->data['sub_current_page'] = 'contacts';
		$this->load->view('contact/_contacts', $this->data);
	}
	public function import_contacts(){
		$this->roleuser->checkLogin();
		if (isset($_FILES) && count($_FILES) > 0) { 
			if(strlen(trim($_POST['group'])) < 1){
				$this->error['group'] = 'Group Required!'; 
			}			
			if(empty($_FILES['contactFile']['name'])){
				$this->error['contactFile'] = 'Contact File Required!';
			}
			
			if(!$this->error){					
				if(!empty($_FILES['contactFile']['name'])){
					$file    = $_FILES['contactFile']['name'];
					$allowed =  array('csv','xls');
					$ext 	 = pathinfo($file, PATHINFO_EXTENSION);
					if(!in_array($ext,$allowed) ) {
						$this->data['warning'] = 'Only .CSV and .XLS file allow for upload';
					}else{
						if($ext=='csv'){ 							//contact upload by csv file
							set_time_limit(0);
							$row  = 1;
							$file = $_FILES['contactFile']['tmp_name'];
							if (($handle = fopen($_FILES['contactFile']['tmp_name'], "r")) !== FALSE) {
								?><code><?php 
								while (($data = fgetcsv($handle, 1000,',','"')) !== FALSE) {
									$checkNumber = $this->contact_model->getAllContacts(array('contactNumber'=> $data[0], 'groupID' => $_POST['group']));
									if(!$checkNumber){														// Check Number Exist or not from DB
										$contactEmails = $this->contact_model->contactNumberEmail($data[0]);
										if($contactEmails->results->result[0]->status=='OK'){				// Check Phone Validation by DATA24x7				
											$contactData = array('contactNumber' 	  => $data[0],
																 'groupID'	 	 	  => $_POST['group'],
																 'contactNumberEmail' => addslashes($contactEmails->results->result[0]->sms_address),
																 'date_time' 	 	  => date('Y-m-d H:i:s')
														   );
											$this->contact_model->add($contactData);
										}
									}
									$row++;
								   
								}
							?></code><?php 
							}
							fclose($handle);	
						} else { 									//contact upload by xls file
							set_time_limit(0);						
							require(APPPATH.'libraries/xlsreader/reader.php');
							error_reporting(1);
							$data = new Spreadsheet_Excel_Reader();
							$data->setOutputEncoding('CP1251');
							$data->read($_FILES['contactFile']['tmp_name']);
							$master = array();
							for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
							{
								for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
								{
									$master[$i][$j] = $data->sheets[0]['cells'][$i][$j];
								}  
							 
							}
							$row = 1;
							foreach($master as $fileData){
								if($row != 1){
									$checkNumber = $this->contact_model->getAllContacts(array('contactNumber'=> $fileData[1], 'groupID' => $_POST['group']));
									if(!$checkNumber){												// Check Number Exist or not from DB
										$contactEmails = $this->contact_model->contactNumberEmail($fileData[1]);
										if($contactEmails->results->result[0]->status=='OK'){		//Check Phone Validation by DATA24x7
											$contactData = array('contactNumber' 	  => $fileData[1],
																 'groupID'	 	 	  => $_POST['group'],
																 'contactNumberEmail' => addslashes($contactEmails->results->result[0]->sms_address),
															 	 'date_time' 	 	  => date('Y-m-d H:i:s')
													   );
										
											$this->contact_model->add($contactData);
										}
									}
								}							
								$row++;
							}
						}					
						$this->session->set_userdata('message', 'Records Successfully Imported');						
						redirect('contacts');
					}					
				}	
			}			
			$this->data += $_POST;					
		}
		
		//getGroups
		$groupList 	= array();
		$groupList[''] = '-- Select --';
		$groups = $this->contact_model->getAllGroups();
		foreach ($groups as $item) {
			$groupList[$item->groupID] = $item->groupName;
		}		
		$this->data['option_groups']  = $groupList;			
		$this->data['error'] 			= $this->error;		
		$this->data['current_page'] 	= 'contact';
		$this->data['sub_current_page'] = 'import_contact';
		$this->load->view('contact/_contacts', $this->data);
	}
	public function delete_contact(){
		$data = array('success'=>0,'text'=>'Delete fail! Please try again!');
		$this->contact_model->delete($_POST['id']);
		$this->session->set_userdata('message', 'You has been delete contact successfully!');
		$data = array('success'=>1);
		echo json_encode($data);
	}
	public function update_contact($contactID){
		$this->roleuser->checkLogin();
		$getContacts = $this->contact_model->getAllContacts(array('contactID' => $contactID));
		if (isset($_POST) && count($_POST) > 0) {
			if(strlen(trim($_POST['group'])) < 1){
				$this->error['group'] = 'Group Required!'; 
			}	
			
			if (!is_numeric($_POST['phoneNumber']) || strlen($_POST['phoneNumber']) != 10) {
				$this->error['phoneNumber'] = 'Invalid phone number!';			        	
	        }				
			
			if(!$this->error){
				$email = false;
				if($getContacts[0]->contactNumber != $_POST['phoneNumber']){
					$numberEmail  = $this->contact_model->contactNumberEmail($_POST['phoneNumber']);			//Check Number is Valid Cellphone or not by DATA24x7
					$contactEmail = $numberEmail->results->result[0]->sms_address;
					if($numberEmail->results->result[0]->status=='OK'){
						$email = 'OK';
					}
				}else{
					$contactEmail = $getContacts[0]->contactNumberEmail;
					$email = 'OK';
				}
				
				if($email=='OK'){
					$contactData = array('contactNumber' 		=> $_POST['phoneNumber'],
										 'groupID'	 			=> $_POST['group'],
										 'contactNumberEmail'	=> addslashes($contactEmail),
										 'optOut'				=> $_POST['optout']					 
								   );
					$this->contact_model->update($contactID, $contactData);
					$this->session->set_userdata('message', 'Records Successfully Updated');						
					redirect('contacts');
				}else{
					$this->error['phoneNumber'] = 'Invalid Cellphone number';
				}					
			}
			$this->data += $_POST;	
		}
		$this->data['sub_current_page'] = 'add_contact';
		$this->data['sub_title'] 		= 'Add Contact';
						
		//getGroups
		$groupList 	= array();
		$groupList[''] = '-- Select --';
		$groups = $this->contact_model->getAllGroups();
		foreach ($groups as $item) {
			$groupList[$item->groupID] = $item->groupName;
		}		
		$this->data['option_groups']  = $groupList;
				
		$this->data['contactID']		= $getContacts[0]->contactID;
		$this->data['phoneNumber']		= $getContacts[0]->contactNumber;
		$this->data['groupID']			= $getContacts[0]->groupID;	
		$this->data['optout']			= $getContacts[0]->optOut;		
		$this->data['error'] 			= $this->error;		
		$this->data['current_page'] 	= 'contact';
		$this->data['sub_current_page'] = 'update_contact';
		$this->load->view('contact/_contacts', $this->data);
	}
	
	public function optout_contact(){
		
		$getAllContacts = $this->contact_model->getContacts(array('c.date_time'=>date('Y-m-d H:i:s', base64_decode( $_GET['contactid'] )) ));
		$this->contact_model->update($getAllContacts[0]->contactID, array('optOut' => 1 ));
		$this->session->set_userdata('message_optout', true);						
		redirect('optout-success');		
	}
	public function optout_success(){
		
		if($this->session->userdata('message_optout'))	{
			$this->data['success_message'] = 1;
			$this->session->unset_userdata('message_optout');
		}			
		$this->data['error'] 			= $this->error;		
		$this->data['current_page'] 	= 'contact';		
		$this->load->view('optout_success', $this->data);
	}
	public function optout_contact_list(){
		$this->roleuser->checkLogin();
		$getAllContacts = $this->contact_model->getContacts(array('optOut'=>1));
		
		//getGroups
		$groupList 	= array();
		$groupList[''] = '-- All --';
		$groups = $this->contact_model->getAllGroups();
		foreach ($groups as $item) {
			$groupList[$item->groupID] = $item->groupName;
		}
		
		$this->data['option_groups']    = $groupList;		
		$this->data['allContacts'] 		= $getAllContacts;
		$this->data['error'] 			= $this->error;		
		$this->data['current_page'] 	= 'contact';
		$this->data['sub_current_page'] = 'optout_contacts';
		$this->load->view('contact/_contacts', $this->data);
	}
	// Private Functions ===========================================================================
	private function getUserInfo() {
		//customer info			
		$info = $this->admin_model->getAdmin((int)$this->session->userdata('sms_userid'));			
		return $info;
	}
}
?>