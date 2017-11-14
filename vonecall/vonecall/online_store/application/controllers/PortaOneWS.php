<?php
error_reporting(1);
ini_set('default_socket_timeout', 10);
class PortaOneWS
{
	
	static $sandbox 	= true;
	static $proxy_host	= 'https://98.158.159.101/wsdl';	
	static $proxy_port	= '443';		
	static $uri_base    = 'http://portaone.com/wsdl';	
	static $_errorCodes = array( );

	/**
 	* @var SoapClient
 	*/
	private $user;
	private $pass;
	private $_client;
	private $sessionID;
	/**
	 * @var SoapFault
	 */
	private $_errorString;
	private $_errorCode;
	private $_authstr;
	private $_callback;
	
	function __construct($clientUrl='', $session = false, $test = false) {
       
	    $this->user = $user;
        $this->pass = $pass;
	
		$options = array('trace' => true, "cache_wsdl" => 0); //"exceptions" => 0,       
		$this->_client = new SoapClient(self::$proxy_host.'/'.$clientUrl, $options);	
		
    }

	private function _handleQuery($method, $params = array()) {
		if (!isset($this->_client)) return null; // client undefined if missed internet connection
		
		$timeStart = microtime(true);
		try{
			$this->_errorCode   = null;
			$this->_errorString = null;
			
			## Setup Header (AUTH_INFO)				
	        $auth_info = new SoapHeader(
		        "http://schemas.portaone.com/soap",
		        "auth_info",
		        new SoapVar(
		            array('session_id' => $this->sessionID),
		            SOAP_ENC_OBJECT
		        )
			);
			
			// Call Soap ==========================			
			$test   = $this->_client->__setSoapHeaders($auth_info);			
			
			// Call Soap for Method				
			$result = $this->_client->$method($params);

			/**					
			echo	'<h2>Request</h2><pre>' . htmlspecialchars($this->_client->__getLastRequest(), ENT_QUOTES) . '</pre>';
	    	echo	'<h2>Response</h2><pre>' . htmlspecialchars($this->_client->__getLastResponse(), ENT_QUOTES) . '</pre>';
			**/

						if(is_null($result)) {
				throw new Exception('Undefined API result');
			}
		}
		catch(SoapFault $e) { 
			//die($e->faultstring);
			//echo 123456;echo '<pre>';print_r($e);die;
			$this->_errorCode = $e->faultcode;
			$this->_errorString = $e->faultstring;
			$result->error =  $e->faultstring;
		}
		catch(Exception $e){ 
			//echo '<pre>';print_r($e);die;
			$this->_errorCode = $e->getCode();
			$this->_errorString = $e->getMessage();
			$result->error =  $e->getMessage;
		}

		$timeFinish = microtime(true);
		// If result contains error field trying to resolve error text by error code
		if (isset($result->error) && $result->error > 0) {
			$result->error = isset(self::$_errorCodes[$result->error]) ? self::$_errorCodes[$result->error] :
					'Unknown error with code : ' . $result->error;
		}

		if ($this->_callback) {
			call_user_func_array($this->_callback, array(
				"result" => $result,
				"method" => $method,
				"params" => $params,
				"error" => $this->_errorString,
				"code" => $this->_errorCode,
				"seconds" => $timeFinish - $timeStart
			));
		}
		return $result;
	}


	## Get Session ID
	public function getSessionID($params){		
		$this->sessionID = $this->_client->login($params['user'], $params['password']);
		/****
		echo	'<h2>Request</h2><pre>' . htmlspecialchars($this->_client->__getLastRequest(), ENT_QUOTES) . '</pre>';
    	echo	'<h2>Response</h2><pre>' . htmlspecialchars($this->_client->__getLastResponse(), ENT_QUOTES) . '</pre>';
    	****/
		return $this->sessionID;
	}
		
	## Add Customer
	public function addCustomer($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('add_customer', $params);
		
		return $response;
	}
	
	## Get Account Info
	public function getInfo($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('get_account_info', $params);
		
		return $response;		
	}
	
	## Add Customer
	public function addAccount($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('add_account', $params);
		
		return $response;
	}
	
	## Add Alias
	public function addAlias($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('add_alias', $params);
		
		return $response;
	}

	## Get Alias List
	public function getAliasList($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('get_alias_list', $params);
		
		return $response;
	}
	
	## Delete Alias
	public function deleteAlias($params, $sessionID) {
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('delete_alias', $params);
		
		return $response;
	}
	
	## Get Balance
	public function getBalance($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('get_account_info', $params);
		
		return $response;		
	}
	
	## Terminate Account
	public function terminateAccount($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('terminate_account', $params);
		
		return $response;
	}
	
	## Update Account
	public function updateAccount($params, $sessionID){		
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('update_account', $params);
		
		return $response;
	}
	
	## Recharge Account
	public function rechargeAccount($params, $sessionID){		
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('make_transaction', $params);
		
		return $response;
	}
	
	## Get Account Lists
	public function getAccountLists($params, $sessionID){		
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('get_account_list', $params);
		
		return $response;
	}

	## Speed dial
	/*public function addSpeedDial($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('add_abbreviated_dialing_number', $params);
		
		return $response;
	}*/
	
	## Get Service
	public function getService($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('get_service_list', $params);
		
		return $response;
	}
	
	## Get xCdr details
	public function getxCdr($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('get_xdr_list', $params);
		
		return $response;
	}
	
	## Get All xCdr details for Customer
	public function getAllxCdr($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('get_customer_xdrs', $params);
		
		return $response;
	}
	
	## Add Speed dial
	public function addSpeeddial($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('add_phonebook_record', $params);
		
		return $response;
	}
	
	## Get Speed dial
	public function getSpeeddial($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('get_phonebook_list', $params);
		
		return $response;
	}
	
	## Delete Speed dial
	public function deleteSpeeddial($params, $sessionID){
		$this->sessionID = $sessionID;
		$response = $this->_handleQuery('delete_phonebook_record', $params);
		
		return $response;
	}
}
