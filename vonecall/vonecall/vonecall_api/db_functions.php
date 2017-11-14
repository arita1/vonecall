<?php
/****************Database Function ****************/
function getCustomerByPhone($phone)
{
    
    $customerDetails = mysql_query("SELECT `c`.*, `s`.`stateName`, `at`.`alertTypeName`, `a`.`loginID` as agentLoginID 
                                FROM (`tbl_Customer` as c) 
                                LEFT JOIN `tbl_Agent` as a ON `a`.`agentID` = `c`.`agentID` 
                                LEFT JOIN `tbl_StateZip` as s ON `s`.`stateID` = `c`.`stateID` 
                                LEFT JOIN `tbl_AlertType` as at ON `at`.`alertTypeID` = `c`.`alertID` 
                                WHERE `c`.`phone` =  '" . $phone . "'");
    if(mysql_num_rows($customerDetails)>0){
    return $details  = mysql_fetch_assoc($customerDetails);
   }else{
    return 0;
   }
}
    
function getCustomersLogin($where1,$where2) {
 
  $customerDetails = "SELECT * FROM tbl_Customer WHERE  ".$where1." AND ".$where2."";
     //print_r($mydb);
    return $customerDetails;
    
  }

function add($data)
{
   
    $result = insert('tbl_Customer', $data);
    $last_insert_id = $mydb->insert_id;
    return $last_insert_id;
}


function update($customerID, $data)
{
   
    $result = $mydb->update('tbl_Customer', $data, array( 'customerID' => $customerID
    ));
   
    return $result = true;
}

//=======check otp===============
   function checkOtpForPhone($where) {
   	
        $query = $mydb ->get_results("Select * FROM verifyOTP WHERE otpPhone = '".$where."'");
        return $query;
    }

    //======insert otp============
   function addOTP($data) {
        
   		 $result  = $mydb->insert('verifyOTP', $data);
        $last_insert_id = $mydb->insert_id;
    	return $last_insert_id;
    }
   function updateOTP($phone, $data) {
   	
    	return $result = $mydb->update('verifyOTP',$data,array('otpPhone' => $phone));
    }
   function updateCustomerByPhone($phone, $data) {
       
    	return $result = $mydb->update('tbl_Customer',$data,array('phone' => $phone));
    }
   function deleteOTP($where) {
        
    	return $result = $mydb->delete('verifyOTP',$where);
   }


function getSettings($where='') {

   $query     = "SELECT * FROM tbl_GeneralSettings WHERE settingKey ='".$where."'";
       
 $settings = mysql_query($query);
     
  $row = mysql_fetch_assoc($settings); 

  return $row['settingValue'];
   
  }
function addPinlessTxn($data) {
     
    $result = $mydb->insert('tbl_pinless_txns', $data);
   
    $last_insert_id = $mydb->insert_id;
    return $last_insert_id;
  }

  function getSavedCardByCardID($sa_card_id) {
     
       $query = $mydb ->get_results(" SELECT * FROM tbl_saved_cards where sa_card_id = ".$sa_card_id."");
    
    return $query;
  }

 function getSavedCardByCustomerID($customer_id) {
     

     if($customer_id != '' ||$customer_id != 0){
           $current_date = date('Y-m');
           $query = $mydb->get_results(" SELECT * FROM tbl_saved_cards where `customerID` = ".$customer_id." AND `sa_card_exp` > '".$current_date."'");
           if(!empty($query)){
             foreach($query as $data){
              $card_type = getCreditCardType($data->sa_card_number, $format = 'string') ;
               $query1[] = array("sa_card_id"=>$data->sa_card_id,"sa_card_number"=>$data->sa_card_number,"sa_card_type"=> $card_type ,"sa_card_cvv"=>$data->sa_card_cvv);
             }
             return $query1;
           }

        return $query;
      }else{
        return 0;
    }
  }

function updateBalance($id, $amount) {
  
  $result = $mydb->query("UPDATE tbl_Customer SET balance = balance + ".$amount." WHERE customerID = ".$id."");
  return $result;
   
  }
  function updateagentBalance($id, $amount) {
  
  $result = $mydb->query("UPDATE tbl_Agent SET balance = balance + ".$amount."  WHERE agentID = ".$id."");

  return $result;
   
  }

  function saveMyCard($data) {
    
    $result = $mydb->insert('tbl_saved_cards', $data);
    $last_insert_id = $mydb->insert_id;

    return $last_insert_id;
  }

  function getStates() {
   
     
     $query = $mydb ->get_results(" SELECT stateID, stateName FROM tbl_StateZip ");
    
      return $query;
  }

  function getVProductsByCommission($where='', $limit='', $offset=''){

 
 $productDetails = $mydb->get_results("SELECT `ac`.*, `p`.`productName`, `vp`.`vproductName`, `vp`.`vproductCategory`, `vp`.`vproductVendor`, `vp`.`vproductSkuID`, `vp`.`ppnProductID`, `pl`.`logoName`, `cc`.`countryName` FROM (`tbl_AgentCommission` ac) LEFT JOIN `tbl_Product` as p ON `ac`.`productID` = `p`.`productID` LEFT JOIN `tbl_VonecallProducts` as vp ON `ac`.`vproductID` = `vp`.`vproductID` LEFT JOIN `tbl_VonecallProductLogo` as pl ON `pl`.`ppnProductID` = `vp`.`ppnProductID` LEFT JOIN `tbl_CountryCode` as cc ON `vp`.`vproductCountryCode` = `cc`.`CountryCodeIso` WHERE `ac`.`agentID` = ".$where['ac.agentID']." AND `vp`.`terminateDate` IS NULL AND `vp`.`vproductCategory` = 'Pinless' ORDER BY `ac`.`ID`");
  
    return $productDetails;

}


function getCommissionByProductID($agentID, $ID) {

   

   $productDetails = $mydb->get_results("SELECT `vp`.*, `ac`.* FROM (`tbl_VonecallProducts` vp) LEFT JOIN `tbl_AgentCommission` as ac ON `ac`.`vproductID` = `vp`.`vproductID` WHERE `ac`.`agentID` = '".$agentID."' AND `vp`.`vproductID` = '".$ID."'");
 
    return $productDetails;
  }


function getAgent($agentID){
  

   $agentDetails = $mydb->get_results("SELECT `a`.*, `at`.`agentTypeName`, `s`.`stateName` FROM (`tbl_Agent` as a) LEFT JOIN `tbl_AgentType` as at ON `a`.`agentTypeID` = `at`.`agentTypeID` LEFT JOIN `tbl_StateZip` as s ON `s`.`stateID` = `a`.`stateID` WHERE `a`.`agentID` = $agentID");
 
    return $agentDetails;
  

}

function getSeqNo($agentID) {
  

    
   $agentDetails = mysql_query("SELECT `p`.`seqNo` FROM (`tbl_CustomerPayment` as p) WHERE `p`.`customerID` = $agentID ORDER BY `p`.`seqNo` desc LIMIT 1 ");
      
    $seqNo = 1;
    if ($agentDetails) {
      $seqNo = $agentDetails[0]->seqNo + 1;
    }
   return $seqNo;
   
  }


  function add_payment($data) {
     $mydb            = new wpdb(DB_USER, DB_PASSWORD, OLDER_DB, DB_HOST) or die('connection fail');
     $result          = $mydb->insert('tbl_CustomerPayment', $data);

     $last_insert_id  = $mydb->insert_id;
     return $last_insert_id;
  }

  function addAgentPayment($data){
   
    $mydb            = new wpdb(DB_USER, DB_PASSWORD, OLDER_DB, DB_HOST) or die('connection fail');
    $result          = $mydb->insert('tbl_AgentPayment', $data);
    $last_insert_id  = $mydb->insert_id;
    return $last_insert_id;
  }
function get_store_status($name){
  
   $agentDetails = "SELECT  log_status FROM `tbl_Agent` WHERE loginID = '".$name."' ";
  // print_r($mydb);
     return  $agentDetails[0]->log_status;
}

function getCreditCardType($str, $format = 'string')
    {
        if (empty($str)) {
            return false;
        }

        $matchingPatterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard' => '/^5[1-5][0-9]{14}$/',
            'amex' => '/^3[47][0-9]{13}$/',
            'diners' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'jcb' => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'any' => '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/'
        ];

        $ctr = 1;
        foreach ($matchingPatterns as $key=>$pattern) {
            if (preg_match($pattern, $str)) {
                return $format == 'string' ? $key : $ctr;
            }
            $ctr++;
        }
    }


?>