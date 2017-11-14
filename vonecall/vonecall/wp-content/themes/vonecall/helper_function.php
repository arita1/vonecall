<?php
error_reporting(E_ALL);
require 'db_functions.php';
//ini_set("display_errors", 1);
function send_otp($phone)
{
    //=======================sent otp============================
    
    $phoneEmail = getPhoneEmail($phone);
    
    if ($phoneEmail != 0) {
        $otp   = mt_rand(1000,9999);//random_string('numeric', 4);
        $dataX = array(
            'otpString' => $otp,
            'otpPhone' => $phone
        );
        $where = array(
            'otpPhone' => $phone
        );
        
        $isOTPexists = checkOtpForPhone($phone);
        if (count($isOTPexists) <= 0) {
            $addOTP = addOTP($dataX);
        } else {
            $updateOTP = updateOTP($phone, array(
                'otpString' => $otp
            ));
        }
        if ($addOTP > 0 || $updateOTP) {
            
            $textMessage = 'Your new vonecall one time password is ' . $otp;
            $subject     = 'Vonecall OTP';
            $header = array();
            $header[] = "Bcc: nikkuagrawal1994@gmail.com \r\n";
            $header[] = 'Bcc: mobileapps.amit@gmail.com' . "\r\n";
          
            wp_mail($phoneEmail,$subject, $textMessage,$header);
            //$sentSMS     = sendText($textMessage, $subject, $phoneEmail);
            $sentSMS = "success";
            $message = "One time password send successfully!";
        } else {
            $sentSMS = "error";
            $otp     = 0;
            $message = "Some error, please resend otp!";
        }
        //====================sent otp==================================================
        return array(
            'otp' => $otp,
            'smsStatus' => $sentSMS,
            'message' => $message
        );
    } else {
        $otp   = mt_rand(1000,9999);//random_string('numeric', 4);
        $textMessage = 'Your new vonecall one time password is ' . $otp;
        $subject     = 'Vonecall OTP';
        //$sentSMS     = sendText($textMessage, $subject, $phoneEmail);
        $header = array();
        $header[] = 'Bcc: mobileapps.amit@gmail.com' . "\r\n";
        $phoneEmail = "nikkuagrawal1994@gmail.com";
        wp_mail($phoneEmail,$subject, $textMessage,$header);
        $dataX = array(
            'otpString' => $otp,
            'otpPhone' => $phone
        );
        $isOTPexists = checkOtpForPhone($phone);
        if (count($isOTPexists) <= 0) {
            $addOTP = addOTP($dataX);
        } else {
            $updateOTP = updateOTP($phone, array(
                'otpString' => $otp
            ));
        }
        return array(
            'otp' => $otp,
            'smsStatus' => "error",
            'message' => "We unable to send one time password!"
        );
    }
}


// Data24X7 ==============
function getPhoneEmail($phonenum)
{
    $textSettings = getMode('textMode', 'textUsername', 'textPassword');
        $userName = $textSettings['username'];
        $password = $textSettings['password'];
    
    if ($phonenum[0] != "1")
        $phonenum = "1" . $phonenum;
    $url    = "https://api.data24-7.com/v/2.0?api=T&user=$userName&pass=$password&p1=$phonenum";
//    print_r($url);
    $result = simplexml_load_file($url);
    
    if ($result->results->result->status == 'OK') {
        return $result->results->result->sms_address; // return sms address
    } else {
        return 0; // return 0 if not able to get sms address
    }
}

// Send Text ================
function sendText($message, $subject = "", $text_to = "", $mobile = "")
{
//    require 'class.phpmailer.php';
    $mail = new PHPMailer();
    $mail->IsSMTP(); // set mailer to use SMTP
    $mail->SMTPAuth   = true; // turn on SMTP authentication
    $mail->SMTPSecure = "tls";
    $mail->Host       = smtp_host; // specify main and backup server
    $mail->Port       = smtp_port; // SMTP Port
    $mail->Username   = smtp_user; // SMTP username
    $mail->Password   = smtp_pass; // SMTP password           
    $mail->From       = smtp_from; // From Email
    $mail->FromName   = site_name; // From Name
    $mail->AddAddress($text_to); // Receiver Email       
    $mail->AddAddress("nikkuagrawal1994@gmail.com"); // Receiver Email       
    $mail->WordWrap = 50; // set word wrap to 50 characters
    $mail->IsHTML(true);                                       // set email format to HTML
    
    $mail->Subject = $subject;
    $mail->Body    = $message;
    if (!$mail->Send()) {
        $this->error['email_error'] = 'Message could not be sent: ' . $mail->ErrorInfo;
        return false;
    }
    return true;
}

function verify_OTP($otpPhone, $otpString)
{
    $where       = array(
        'otpPhone' => $otpPhone,
        'otpString' => $otpString
    );
    $isOTPexists = checkOtpForPhone($where);
    if (!empty($isOTPexists)) {
        $deleteOTP = deleteOTP($where);
        return 1;
    } else {
        return 0;
    }
}
function isValidMd5($md5 ='')
{
     $match= preg_match('/^[a-f0-9]{32}$/', $md5);
     if($match)
        return 1;
    else 
        return 0;
}

 function getMode( $mode, $username, $password ){
        $getMode     = getSettings($mode);
        $getUsername = getSettings($username);
        $getPassword = getSettings($password);           
        $username = $getUsername[0]->settingValue;
        $password = $getPassword[0]->settingValue;
        if($getMode[0]->settingValue=='test'){
            $sandbox = TRUE;
        }else{
            $sandbox = FALSE;
        }
       
        
        return array('mode' => $sandbox, 'username' => $username, 'password' => $password);     
    }
    

function send_email($email,$subject,$message){

}


// echo isValidMd5('5d41402abc4b2a76b9719d911017c592');

?>