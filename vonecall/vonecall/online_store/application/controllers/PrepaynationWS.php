<?php
class PrepaynationWS
{
	static $urlSandbox 	= 'http://qa.valuetopup.com/posaservice/servicemanager.asmx?WSDL';
	static $urlProduct	= 'https://www.valuetopup.com/posaservice/servicemanager.asmx?WSDL';
	
	static $_errorCodes = array(
		"100" => "Access denied",
		"150" => "Server error when validating an API client request",
		"151" => "Array has invalid data",
		"200" => "Server error when processing an API client request",
		"300" => "Type not valid",
		"301" => "Protocol not valid",
		"302" => "Unsupported format for this type",
		"303" => "PSTN prefix not supported",
		"400" => "API Order ID not found or invalid",
		"401" => "API Order ID not in valid status",
		"405" => "Transaction refused",
		"410" => "Transaction out of balance",
		"411" => "Account balance is disabled/suspened/has not enough amount for purchases",
		"430" => "Customer: Prepaid Balance disabled or not exist",
		"500" => "Region(s) not found or invalid",
		"501" => "City not found",
		"505" => "DIDs not available for this region",
		"600" => "DID Number not found or invalid",
		"601" => "DID Number not found in Reserved Pool",
		"602" => "DID Number expired. Please renew"
	);

	/**
 	* @var SoapClient
 	*/
	private $user;
	private $pass;
	private $_client;

	/**
	 * @var SoapFault
	 */
	private $_errorString;
	private $_errorCode;
	private $_authstr;
	private $_callback;
	
	function __construct($user, $pass, $sandbox = false) {
		$url = $sandbox ? self::$urlSandbox : self::$urlProduct;
		$header = new SoapHeader('http://www.pininteract.com', 'AuthenticationHeader', array('userId' => $user, 'password' => $pass), false);
		$this->_client = new SoapClient($url, array('trace' => 1));  // Array Add by Nitesh
		$this->_client->__setSoapHeaders($header);
	}

	private function _handleQuery($method, $params = array()) {
		if (!isset($this->_client)) return null; // client undefined if missed internet connection
		
		//$params = array_merge(array('header' => $header), $params);print_r($params);die;
		$timeStart = microtime(true);
		try{
			$this->_errorCode = null;
			$this->_errorString = null;			
			$result = $this->_client->__soapCall($method, array($params));//$method = 'GetSkuList';$params=array();print_r($result->GetSkuListResult->skus->sku);die;
			
		/** Added by Nitesh **/
			/** create Text File **/  // Added on feb_4		
			$txtLocation = "public/xmlfiles/prepaynationws/".$method.time().".txt";
			$file = fopen($txtLocation, 'a') or die("can't open file");
			
			$text  = "";
			$text .= "Request \n";				
			$text .= $this->_client->__getLastRequest() ."\n";
			$text .= "Response \n";
			$text .= $this->_client->__getLastResponse() ."\n";
					
			fwrite($file, $text);
			fwrite($file, "\n");
			fclose($file);		
		/**  --  Nitesh END  -- **/			
			if(is_null($result)) {
				throw new Exception('Undefined API result');
			}
		}
		catch(SoapFault $e) {
			//die('fault'.$e->faultstring);
			$this->_errorCode = $e->faultcode;
			$this->_errorString = $e->faultstring;
			$result->error =  $e->faultstring;
		}
		catch(Exception $e){
			$this->_errorCode = $e->getCode();
			$this->_errorString = $e->getMessage();
			$result->error =  $e->getMessage;
		}

		$timeFinish = microtime(true);
		
		// If result contains error field trying to resolve error text by error code
		if (isset($result->responseCode) && $result->responseCode !== '000') {
			$result->error = isset(self::$_errorCodes[$result->responseCode]) ? self::$_errorCodes[$result->responseCode] :
					'Unknown error with code : ' . $result->responseCode;
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

	public function getCarrierList() {
		$response = $this->_handleQuery('GetCarrierList');
		if (isset($response->carrierListResponse->responseCode) && $response->carrierListResponse->responseCode === '000') {
			return $response->carrierListResponse->carriers->carrier;
		} elseif (isset($response->carrierListResponse->responseCode) && $response->carrierListResponse->responseCode !== '000') {
			$response->error = $response->carrierListResponse->responseMessage;
		}
		return $response;
	}
	
	public function getCarrierInfoByMobileNumber($params=array()) {
		//$params['version'] = '1.8';
		$response = $this->_handleQuery('GetCarrierInfoByMobileNumber', $params);
		if (isset($response->GetCarrierInfoByMobileNumberResult->responseCode) && $response->GetCarrierInfoByMobileNumberResult->responseCode === '000') {
			return $response->GetCarrierInfoByMobileNumberResult->carrier;
		} elseif (isset($response->GetCarrierInfoByMobileNumberResult->responseCode) && $response->GetCarrierInfoByMobileNumberResult->responseCode !== '000') {
			$response->error = $response->GetCarrierInfoByMobileNumberResult->responseMessage;
		} else {
			$response->error = isset($response->error) ? $response->error : 'Unknown error occur.';
		}
		return $response;
	}
	
	public function purchaseRtr2($params) {
		//$params['version'] = '1.8';
		$response = $this->_handleQuery('PurchaseRtr2', $params);
		if (isset($response->orderResponse->responseCode) && $response->orderResponse->responseCode === '000') {
			return $response->orderResponse;
		} elseif (isset($response->orderResponse->responseCode) && $response->orderResponse->responseCode !== '000') {
			$response->error = $response->orderResponse->responseMessage;
		} else {
			$response->error = isset($response->error) ? $response->error : 'Unknown error occur.';
		}
		return $response;
	}
	public function purchasePin($params) {
		//$params['version'] = '1.8';
		$response = $this->_handleQuery('PurchasePin', $params);
		if (isset($response->orderResponse->responseCode) && $response->orderResponse->responseCode === '000') {
			return $response->orderResponse;
		} elseif (isset($response->orderResponse->responseCode) && $response->orderResponse->responseCode !== '000') {
			$response->error = $response->orderResponse->responseMessage;
		} else {
			$response->error = isset($response->error) ? $response->error : 'Unknown error occur.';
		}
		return $response;
	}
	
	public function getSkuList($params) {
		//$params['version'] = '1.8';
		$response = $this->_handleQuery('GetSkuListByCarrier', $params);
		if (isset($response->orderResponse->responseCode) && $response->orderResponse->responseCode === '000') {
			return $response->orderResponse;
		} elseif (isset($response->orderResponse->responseCode) && $response->orderResponse->responseCode !== '000') {
			$response->error = $response->orderResponse->responseMessage;
		} else {
			$response->error = isset($response->error) ? $response->error : 'Unknown error occur.';
		}
		return $response;
	}
	
}