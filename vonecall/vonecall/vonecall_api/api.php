<?php

require_once("Rest.inc.php");
include('connection.php');
require_once('PHPMailerAutoload.php');
include('functions.php');


class API extends REST {
public function __construct() {
parent::__construct();    // Init parent contructor
dbConnect();     // Initiate Database connection
}
/*
* Dynmically call the method based on the query string
*/
public function processApi() {
$func = strtolower(trim(str_replace("/", "", $_REQUEST['x'])));
if ((int) method_exists($this, $func) > 0)
$this->$func();
else
$this->response('', 404); // If the method not exist with in this class "Page not found".
}
/****************************************************************/
function get_country_list(){
//$json_array = $this->check_authorization();
$json_object  =   file_get_contents("php://input");
$json_array   =   json_decode($json_object,true);

if($json_array['get'] == "country"){

 $type       =   $json_array['type'];
switch ($type){
case 1 : 
$result     =  get_distinct_country();
break;
case 2:
$result     =    get_all_country();
break;
}
}else{
$result = array();
}
switch($result){
case !empty(is_array($result)): 
$success    =   array("status"=>"success","message" =>"Country list available","country_data"=>$result);
$this->response($this->json($success),200);// if successfully inserted
break;
case empty($result) :
$error     =   array("status"=>"failure","message"=>"NOT FOUND");
$this->response($this->json($error),204);// error in insertion
break;


}



}// function close



/*****************************************************************************/


function verify_phone_number(){
$json_object  =   file_get_contents("php://input");
$json_array   =   json_decode($json_object,true);

if($json_array['phone_number'] != ''){

 $phone_number      =   $json_array['phone_number']; 
 $result 			= 	verify_user_phone_byportaone($phone_number);
 
}else{
$result = 0;
}
switch($result){
case  1: 
$success    =   array("status"=>"success","message" =>"Verified successfully , OTP sent successfully");
$this->response($this->json($success),200);//  //redirect to login page
break;
case 2  :
$success     =   array("status"=>"success","message"=>"Verified successfully , Unable to send OTP ");
$this->response($this->json($success),200); //exist on portaone / db  
break;
case 4 :
$success     =   array("status"=>"failure","message"=>"Not exits , OTP sent successfully to register");
$this->response($this->json($success),204);// new customer
break;
case 0 :
$error    =   array("status"=>"failure","message"=>"Error in verification");
$this->response($this->json($error),204);// error in verification
break;
}

}

/******************************************************************************/
function verify_otp(){
$json_object  =   file_get_contents("php://input");
$json_array   =   json_decode($json_object,true);



}


/*****************************************************************************/
function get_call_history(){
$json_object  =   file_get_contents("php://input");
$json_array   =   json_decode($json_object,true);

if(empty($json_array['phone_number'])){
   $error = array("success"=>"failure","message"=>"Phone number Empty");
   $this->response($this->json($error),204);
}
 $phone     = $json_array['phone_number'];
 $from_date = $json_array['from_date'];
 $to_date   = $json_array['to_date'];

$call_data = get_calling_history($phone,$from_date,$to_date);

switch($call_data){

	case empty($call_data) : $error = array("success"=>"failure","message"=>"No Data Found");
   							$this->response($this->json($error),204);
	break;
   case  !empty($call_data) : $success =   array("status"=>"success",
   												"message"=>"Call History Available",
   												"callDetails"=>$call_data
   											);
 							$this->response($this->json($success),200); //exist on portaone / db  
  break;
}



}


/**************************************************************************/
function get_transaction_history(){
 $json_object  =   file_get_contents("php://input");
$json_array   =   json_decode($json_object,true);

if(empty($json_array['phone_number']) || empty($json_array['from_date']) || empty($json_array['to_date'])){
   $error = array("success"=>"failure","message"=>"Required field's are Empty");
   $this->response($this->json($error),204);
}
  $phone     = $json_array['phone_number'];
 $from_date = $json_array['from_date'];
 $to_date   = $json_array['to_date'];
$trans_data = get_transaction_history($phone,$from_date,$to_date);

switch($trans_data){

  case empty($trans_data) : $error = array("success"=>"failure","message"=>"No Data Found");
                $this->response($this->json($error),204);
  break;
   case  !empty($trans_data) : $success =   array("status"=>"success",
                          "message"=>"Transaction History Available",
                          "TransactionDetails"=>$trans_data
                        );
              $this->response($this->json($success),200); //exist on portaone / db  
  break;
}



}


/*****************************************************************************/
function check_user_existence($userid){
 $exist_status = check_user_existence($userid);
 switch($exist_status){

 	case 0 : $error      =   array("status"=>'failure',"message"=>"User Not exists");//ARRAY EMPTY// return if json found empty
			$this->response($this->json($error),204);
	break;

 }
}

//***************************************************************************************/
/*      CHECK JSON AND CONTENT AUTHORIZATION
*/
function check_authorization(){
$json_object  =   file_get_contents("php://input");
$hash         =   hash_hmac("sha1",$json_object,PRIVATEKEY);
$contenthash  =   getallheaders();
if($hash == $contenthash['salt']){// IF HASH Salt MATCHED 
$json_array   =   json_decode($json_object,true);
if(empty($json_array)){
$error      =   array("status"=>'failure',"message"=>"Content empty");//ARRAY EMPTY// return if json found empty
$this->response($this->json($error),204);
}else{
return $json_array;
}// NON EMPTY ARRAY THEN RETURN IT ELSE SEND EXCEPTION
}else{
$empty_array  =     array("status" => "failure", "message" => "Unauthorized access");// if Salt doesn't Match 
$this->response($this->json($empty_array), 204);
}// Unauthorized else condition closed
}
/*      Encode array into JSON
*/
function json($data) {
if (is_array($data)) {
$json= json_encode($data ,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
$con=getallheaders();
$con['Salt']=hash_hmac('sha1',$json,PRIVATEKEY);
return $json;   
}
}
}// closing Login api
// Initiiate Library
$api = new API;
$api->processApi();
?>