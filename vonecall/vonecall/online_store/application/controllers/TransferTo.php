<?php
class TransferTo
{
	private static $wsLogin 	= 'xclnetworkscom';
	private static $wsPassword = 'tJuIxdjziy';
	private static $wsURL 		= 'https://fm.transfer-to.com:5443/cgi-bin/shop/topup';
	private static $param 		= array();
	
	
	/**
	 * check_wallet
	 */
	public static function check_wallet() {
		$key = time();
		self::$param['login'] 	= self::$wsLogin;
		self::$param['key'] 	= $key;
		self::$param['md5'] 	= md5(self::$wsLogin.self::$wsPassword.$key);
		self::$param['action'] = 'check_wallet';
		return self::callRequest();
	}
	
	/**
	 * msisdn_info
	 */
	public static function msisdn_info($phone, $operatorid='') {
		$key = time();
		self::$param['login'] 	= self::$wsLogin;
		self::$param['key'] 	= $key;
		self::$param['md5'] 	= md5(self::$wsLogin.self::$wsPassword.$key);
		self::$param['destination_msisdn'] 		= $phone;
		self::$param['operatorid'] 				= $operatorid;
		self::$param['delivered_amount_info'] 	= 1;
		self::$param['action'] 					= 'msisdn_info';
		return self::callRequest();
	}
	
	/**
	 * topup request
	 */
	public static function topup($phone, $operatorid='', $product, $sender_number, $text='') {
		$key = time();
		self::$param['login'] 	= self::$wsLogin;
		self::$param['key'] 	= $key;
		self::$param['md5'] 	= md5(self::$wsLogin.self::$wsPassword.$key);
		self::$param['msisdn'] 					= $sender_number;
		self::$param['destination_msisdn'] 		= $phone;
		self::$param['operatorid'] 				= $operatorid;
		self::$param['product'] 				= $product;
		self::$param['cid1'] 					= 'My Text';
		self::$param['sms'] 					= $text;
		self::$param['sender_sms'] 				= 'yes';
		self::$param['sender_text'] 			= 'International Pinless';
		self::$param['delivered_amount_info'] 	= 1;
		self::$param['action'] 					= 'topup';
		return self::callRequest();
	}
	
	/**
	 * topup request
	 */
	public static function pricelist($type='countries', $content='') {
		$key = time();
		self::$param['login'] 	= self::$wsLogin;
		self::$param['key'] 	= $key;
		self::$param['md5'] 	= md5(self::$wsLogin.self::$wsPassword.$key);
		self::$param['info_type'] 	= $type;
		self::$param['content'] 	= $content;
		self::$param['action'] 		= 'pricelist';
		return self::callRequest();
	}
	
	/**
	 * callRequest
	 */
	public static function callRequest() {
		$url = self::$wsURL . '?';
		foreach (self::$param as $key => $value) {
			$url .= $key.'='.$value.'&';
		}
		//$url = 'https://fm.transfer-to.com:5443/cgi-bin/shop/topup?login=xclnetworkscom&key='.$key.'&md5='.md5('xclnetworkscom'.'tJuIxdjziy'.$key).'&action=check_wallet';
		$results = file_get_contents($url);
		
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_URL, $url);
//		$results = curl_exec($ch);
//		curl_close($ch);
		
		$arr = explode("\r\n", $results);
		$data = new stdClass();
		foreach ($arr as $value) {
			$tmp = explode("=", $value);
			if (count($tmp) == 2) {
				$data->$tmp[0] = $tmp[1];
			}
		}
		return $data;
	}
}