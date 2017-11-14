<?php

$conn = mysql_connect("localhost","root","Rashid@ryd#",'POST3');
   if(! $conn )
   {
     die('Could not connect: ' . mysql_error());
   
   }else{
   //mysql_select_db( 'TUTORIALS' );
  
    $db_selected = mysql_select_db('POST3', $conn);
	
	if (!$db_selected) {
		die ('error : ' . mysql_error());
	}
}


$name = date('m/d/y') .'_CSV'; //This will be the name of the csv file.
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$name.'.csv');

$output = fopen('php://output', 'w');

fputcsv($output, array('customerID',
'loginID',
'password',
'agentCode',
'firstName',
'lastName',
'email',
'address',
'city',
'stateID',
'zipCode',
'referredSource',
'createdDate',
'lastLogonDate',
'modifiedDate',
'preferLanguageID',
'subscriberID',
'alertID',
'genderID',
'fax',
'country',
'statementAddress',
'statementCity',
'statementStateID',
'statementZipCode',
'balance',
'status',
'phone',
'agentID',
'note',
'securityCode',
'customerProduct',
'customerProductSKU',
'phoneEmailID')); //The column heading row of the csv file

//establish mysql connections: hope you know the arguments :)
$rows = mysql_query('SELECT * FROM tbl_Customer');

$temp = array();

while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);
 
/*while ($row = mysql_fetch_assoc($rows))
{
	array_push($temp, $row);
}
 $c = count($temp); 
   for($i=0; $i<=$c; $i++) {

	fputcsv($output, $temp[$i]);
}*/
?>

