<?php
require_once('PhpSIP.class.php');

/* Sends NOTIFY to reset Linksys phone */

try
{
  $api = new PhpSIP();
  $api->setUsername('ani16619933305'); // authentication username
  $api->setPassword('9n5q0owj'); // authentication password
  // $api->setProxy('some_ip_here'); 
  $api->addHeader('Event: resync');
  $api->setMethod('NOTIFY');
  $api->setFrom('sip:ani16619933305@sip.somtelusa.com');
  $api->setUri('sip:ani16619933305@sip.somtelusa.com');
  $res = $api->send();

  echo "response: $res\n";
  
} catch (Exception $e) {
  
  echo $e;
}

?>
