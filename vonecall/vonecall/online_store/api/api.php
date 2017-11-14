
<?php


require_once("Rest.inc.php");
include('connection.php');

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
   function set_login(){
  
    if (isset($_POST) && count($_POST) > 0) {
                       
                       session_start();

                       $_SESSION['store_role'] = 'reseller';
      // $login = login($_POST['username'], md5($_POST['password']));
      
      // if($login['status'] === true) {       
      //   redirect('home');
        //redirect('topup');
}

   }



//***************************************************************************************/
    /*      Encode array into JSON
     */

    function json($data) {

        if (is_array($data)) {
        // $data= array_map('utf8_encode',$data);
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