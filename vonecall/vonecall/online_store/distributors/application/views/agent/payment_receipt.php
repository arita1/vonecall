<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Receipt</title>
</head>
<body style="padding:50px;">
<table width="100%" align="center" cellpadding="10">
  <tr>
    <td width="200"><img src="<?php echo $this->config->item('base_url')?>public/images/logo_receipt.png"/></td>
    <td style="text-align:center;"><h1>RECEIPT</h1></td>
  </tr>
  <tr>
    <td colspan="2">
      <span><b>Date:</b> <?php echo date('m/d/Y H:i:s A', strtotime($payment->createdDate));?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <span><b>Account Rep ID:</b> <?php echo $payment->accountRepLoginID;?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <span><b>Store ID:</b> <?php echo $payment->agentLoginID;?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <span><b>Payment Amount:</b> <?php echo format_price($payment->chargedAmount);?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <span><b>Received by:</b> <?php echo $payment->paidTo;?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <span><b>Thank you for purchasing our service. If you have any questions, please contact your Account Representative or Customer Service.</b></span>
    </td>
  </tr>
</table>
</body>
</html>