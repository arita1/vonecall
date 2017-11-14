<?php
global $mydb = new wpdb(DB_USER,DB_PASSWORD,OLDER_DB,DB_HOST) or die('connection failed');
/*******function to check phone number in db *****/
function getCustomerByPhone($phone) {
	$result =	$mydb->get_results("SELECT `c`.*, `s`.`stateName`, `at`.`alertTypeName`, `a`.`loginID` as agentLoginID
                                FROM (`tbl_Customer` as c)
                                LEFT JOIN `tbl_Agent` as a ON `a`.`agentID` = `c`.`agentID`
                                LEFT JOIN `tbl_StateZip` as s ON `s`.`stateID` = `c`.`stateID`
                                LEFT JOIN `tbl_AlertType` as at ON `at`.`alertTypeID` = `c`.`alertID`
                                WHERE `c`.`phone` =  '".$phone."'");
		return $result;
	}



?>