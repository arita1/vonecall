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
if ( ! function_exists('get_fwd_num_month'))
{
	function get_fwd_num_month($date, $num=1) {
		$year  = 0;
		$month = 0;
		$day = 0;
		list($year,$month,$day) = explode('-', $date);
		
		$month = $month + $num;
		
		$year = $year + (int)($month/12);
		$month = $month % 12;
		
		if ($month == 0) {
			$month = 12;
			$year = $year - 1;
		}
		if($month<10){
			$month='0'.$month;
		}
		$timestamp = strtotime("$year-$month-01");
		$nextday = date('t',$timestamp);
		if($day > $nextday){
			$day = $nextday;
			if($day<10){
				$day='0'.$day;
			}
		}
		
		return $year.'-'.$month.'-'.$day;
	}
}