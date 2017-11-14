<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('format_phone_number'))
{
	function format_phone_number($phone_number) {
		if (strlen($phone_number)==10) {
			$ret = substr($phone_number,0,3)."-".substr($phone_number,3,3)."-".substr($phone_number,6,4);
		} else {
			$ret = $phone_number;
		}
		return $ret;
	}
}
if ( ! function_exists('format_price'))
{
	function format_price($price) {
		if ((float)$price >= 0) {
			$ret = '$'.number_format($price, 2);
		} else {
			$ret = '-$'.number_format(-$price, 2);
		}
		return $ret;
	}
}