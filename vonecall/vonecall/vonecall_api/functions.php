<?php
error_reporting(E_ALL); 

 require 'helper_function.php' ;

// require 'db_functions.php';
//die("jsdlfh");
  include_once 'PortaOneWS.php' ;



define('PRIVATEKEY','iso-8859-1');
define('site_url','https://www.vonecall.com/vonecall');
define('host','https://www.vonecall.com/vonecall/uploads/');
define('location','https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=latlng&radius=100000&key=AIzaSyDDtKN07mqCaSUBMEQoj8hS52ShU9YEzyU');



function get_all_country(){
        $return_data   =     array();
        $select        =     "SELECT * FROM  `tbl_Rates` ";
        $result        =      mysql_query($select);
       
        while( $row    =      mysql_fetch_assoc($result)){

                $country_image = site_url."/wp-content/uploads/india-1.png";
                $dial_code = "+91";
                $return_data[] = array(
                                     'name' => $row['destination'],
                                     'description' => $row['city'],
                                     'rates' => number_format($row['rate']*100, 2),
                                     "date" => $row['date_time'],
                                     "id" => $row['ID'],
                                     "dial_code" => $dial_code,
                                     "image" => $country_image
                                    );

            }

               
        return  $return_data;
}

function get_distinct_country(){
         $select       =     "SELECT distinct destination  FROM  `tbl_Rates` ";
         $result       =      mysql_query($select);
        while( $row    =      mysql_fetch_assoc($result)){
              $dial_code = "+91";
              $data[] = array("name" => $row['destination'] ,"dial_code" => $dial_code ); 
              
                

        }
        return $data;
}


function verify_user_phone_byportaone($phone)
{
     
    $loginSession      = portaoneSession(); // create session
    
    $getAccountRequest = array(
        'login' => $phone
    );
    $api               = new PortaOneWS("AccountAdminService.wsdl") ;
   
    $result             = $api->getInfo($getAccountRequest, $loginSession);
    $getAccountResponse = $result->account_info;
   
    $customerDetails    = getCustomerByPhone($phone);
    
    if (!empty($customerDetails) && !empty($getAccountResponse)) {
        //exist on portaone and exist on db
        //redirect to login page
       $otp           = send_otp($phone);
     //  print_r($otp['smsStatus']);
        if($otp['smsStatus'] != 'error')
        $redirect_rule = 1;
        else
        $redirect_rule = 2;
    } else{
        $redirect_rule = 0;
    }
  return $redirect_rule;
    
 
}

/***************FUNCTION TO CREATE PORTAONE USER SESSION **************/
function portaoneSession()
{
    
    // Get Pinless Mode
    $pinlessDetails = getMode('pinlessMode', 'pinlessUsername', 'pinlessPassword');
  
    $portaOne = new PortaOneWS("SessionAdminService.wsdl");
    
    $loginSession = $portaOne->getSessionID(array(
        "user" => $pinlessDetails['username'],
        "password" => $pinlessDetails['password']
    ));
    
    // Set login session in Session variable
   
    
    return $loginSession;
}
/***************FUNCTION TO GET ACCOUNT DETAILS FROM PORTAONE **************/
function getAccountDetailsByPortaone($phone, $loginSession)
{
    $getAccountRequest = array(
        'login' => $phone
    );
    $api               = new PortaOneWS("AccountAdminService.wsdl");
    $result            = $api->getInfo($getAccountRequest, $loginSession);
    if (isset($result->error)) {
        return 0;
    } else {
        $account_details          = $result->account_info;
       
        return $account_details;
    }
    
}

function getAccountDetailsFromPortaOne($phone)
{
    $loginSession       = portaoneSession();
    $getAccountResponse = getAccountDetailsByPortaone($phone, $loginSession); // Get Account details from Portaone                     
    if ($getAccountResponse)
        return $getAccountResponse;
    else
        return 0;
}




/******************* VERIFY OTP ****************/

function check_user_otp($phone,$otp)
{
    $isOTPexists = checkOtpForPhone($phone);
//        print_r($isOTPexists);
//        echo $isOTPexists[0]->otpString;
        if ($isOTPexists[0]->otpString != $otp) {
        $result['code']=0;
        $result['error']="Otp does not match, please check and enter again!";
        return $result;
       }else{
         $loginSession      = portaoneSession();
         $account_details   = getAccountDetailsByPortaone($phone, $loginSession);
         $data              = array( 'last_login' => date('Y-m-d H:i:s') );
         $update            = update($customerID, $data);




       }
    
    
}

//************ Function to Resend OTP**************//
function resend_otp($phone){
        $otp = send_otp($phone);
        if($otp)
            return 1;
        else
            return 0;
}





function addNewPortaoneAccount($phone, $loginSession, $timezone)
{
    $password          = substr(uniqid('', true), -5); // Generate Random password
    $AddAccountRequest = array(
        "account_info" => array(
            'i_customer' => customerID,
            'billing_model' => '-1',
            'i_product' => product,
            'activation_date' => date('Y-m-d'),
            'id' => 'ani' . '1' . $phone,
            'balance' => '0',
            'opening_balance' => '0',
            'i_time_zone' => $timezone,
            'i_lang' => 'en',
            'login' => $phone,
            'password' => $password,
            'h323_password' => $password,
            'blocked' => 'N'
        )
    );
    
    $api    = new PortaOneWS("AccountAdminService.wsdl");
    $result = $api->addAccount($AddAccountRequest, $loginSession);
    return $result;
}



/****************FUNCTION TO GET USER DETAILS************/
function get_user_details($phone){
     $loginSession       = portaoneSession();
        $account_details   = getAccountDetailsByPortaone($phone, $loginSession);
         $data              = array( 'last_login' => date('Y-m-d H:i:s') );
         $update            = update($customerID, $data);
      //   $firstname         = $account_details->first
         $user_data = array("last_recharge"=>'N/A',
                            "last_login"=>date('Y-m-d H:i:s'),
                            "balance" => $account_details->balance,
                            "last_usage"=>$account_details->last_usage,
                            "firstname"=>"N/A",
                             "lastname"=>"N/A",
                              "email"=>"N/A",
                               "phone"=>$phone,
                                "zipcode"=>"N/A",
                                 "state"=>"N/A",
                                  "city"=>"N/A",
                                   "country"=>"N/A"
                                  
                            
                        );
         return $user_data;

}



// function Update_user_detail(){
   
//           $wp_upload_dir = WP_PROFILE_UPLOAD;
//      /*** Upload file *********/
//          $uploadedfile      = $_FILES['user_image']; // GET UPLOADED FILE
//          $upload_overrides  = array( 'test_form' => false ); // SET UPLOAD OVERRIDE FALSE

//          /********** FUNCTION TO UPLOAD IMAGE ***********/
//          $movefile          = wp_handle_upload( $uploadedfile, $upload_overrides ); 
//             if ( $movefile && !isset( $movefile['error'] ) ) {
//                  $image = explode('uploads/', $movefile['file']);
//                $filename =  $image[1];
//             } else {
//                 /**
//                  * Error generated by _wp_handle_upload()
//                  * @see _wp_handle_upload() in wp-admin/includes/file.php
//                  */
//                 $movefile['error'];
//                 $filename = $_POST['old_image'];
//             }
//         $data           = array("firstName"=>$_POST['first_name'], "lastName"=>$_POST['last_name'],
//                                 "email" => $_POST['email'],  "phone"=>$_POST['phone'],
//                                 "zipCode"=>$_POST['zip_code'], "city" => $_POST['city'],
//                                 "country" => $_POST['country'], "stateID" => $_POST['state'] ,
//                                 "customer_image" => $filename
//                                 );
//         $cutomerID      = $_POST['customerID'];
//         $update_user_in_db = update($cutomerID , $data); // update user data in database
//         $portaone_update = updateAccountOnPortaone($_POST); // update data in portaone account of user
//         if($portaone_update){
//             echo 1;
//         }else{
//             echo 0;
//         }
//     exit();
// }


/*********Update Password **********/
 
function Update_user_pass(){
   
   $old_password    = trim($_POST['session_pass']);
   $new_pass        = $_POST['new_pass'];
   $current_pass     = trim($_POST['old_pass']);
    if($old_password == md5($current_pass)){
        $data = array("password" => md5($current_pass) );
        $cutomerID      = $_POST['customerID'];
       $result =  update($cutomerID , $data);
       if($result)
        echo 1;
       else
        echo 0;

    }else{
        echo 2;
    }

exit();
    


}


/*******Update detail  at portaone ************/
 function updateAccountOnPortaone($update_field)
    {
        $account_id = $_SESSION['account_info']->i_account;
        
        $data= array("firstname" => $update_field['first_name'] ,
                      "lastname" => $update_field['last_name'] ,
                       "city" => $update_field['city'] ,
                        "state" => $update_field['state'] ,
                         "country" => $update_field['country'],
                         'i_account'=> $account_id
                       );

        $loginSession          = $_SESSION['portaoneSession'];
        $getAccountListRequest = array("account_info" => $data);
        $api    = new PortaOneWS("AccountAdminService.wsdl");
        $result = $api->updateAccount($getAccountListRequest, $loginSession);
        if($result){
            return true;
        }else{
            return false;
        }
    }


/*************************************************************/
function get_calling_history($phone,$from_date,$to_date){
               
                $loginSession = portaoneSession();
           //       echo $to_date  = date('Y-m-d');
           //        $time = strtotime($to_date.' -20 days');
           // echo $from_date = date("Y-m-d", $time);
                
                  
                $getAccountDetails = getAccountDetailsByPortaone($phone, $loginSession);
                $xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
                                     'i_service' => 3,
                                     'from_date' => date('Y-m-d 00:00:00', strtotime( $from_date)),
                                     'to_date'   => date('Y-m-d 11:59:59', strtotime( $to_date))                                                                            
                                );
                // print_r($xcdrRequest);
                $api    = new PortaOneWS("AccountAdminService.wsdl");
                $result = $api->getxCdr($xcdrRequest, $loginSession);
                 
                 if(!empty($result->xdr_list)){
                         $i=1;
                    foreach($result->xdr_list as $data){
                      $call_duration = $data->unix_disconnect_time - $data->unix_connect_time ;// call duration by unix time out
                     $call_date = date("d-m-Y H:i:s",$data->unix_connect_time);
                        $paid_amount = $data->charged_amount;
                     $return_array[] = array("sno"=>$i,"called_number"=>$data->CLD ,"charged_amount"=> $paid_amount,"call_duration"=>date('H:i:s',$call_duration),"call_on" => $call_date);
                    $i++;  
                 }
                
            return  $return_array;
             }
           
}

/***************get transaction history*************/
function get_transaction_history($phone,$from_date,$to_date){
    
     // if (!empty($_SESSION['portaoneSession'])){
     //                $loginSession = $_SESSION['portaoneSession'];
     //            } else {
     //                $loginSession = portaoneSession();
     //            }
                // $from_date = $_POST['from_date'];
                // $to_date  = $_POST['to_date'];
                $loginSession = portaoneSession();
                $getAccountDetails = getAccountDetailsByPortaone($phone, $loginSession);
                $xcdrRequest = array('i_account' => $getAccountDetails->i_account, 
                                     'i_service' => 2, // (Recharge/transaction)
                                     'from_date' => date('Y-m-d 00:00:00', strtotime( $from_date)),
                                     'to_date'   => date('Y-m-d 11:59:59', strtotime( $to_date))                                                                            
                                );
    
                $api    = new PortaOneWS("AccountAdminService.wsdl");
                $result = $api->getxCdr($xcdrRequest, $loginSession);
                //print_r($result);
                 
                 if(!empty($result->xdr_list)){
                         $i=1;
                    foreach($result->xdr_list as $data){
                          $paid_amount = -($data->charged_amount).".00";
                     $call_date = date("d-m-Y H:i:s",$data->unix_connect_time);// date of payment
                     $return_array[] = array("payment_type"=>$data->CLD ,"charged_amount"=>$paid_amount,"call_date" => $call_date);
                    $i++;  
                 }
                
           return  $return_array;
             }
           
            
}

/**************************************************************/
function qa_opt($title){
        $select        =     "SELECT content FROM options WHERE title = '$title'";
        $result        =      mysql_query($select);
        $row           =      mysql_fetch_array($result);
        $content       =      $row['content'];
        return $content;
}







?>