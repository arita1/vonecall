<?php

define('PRIVATEKEY','iso-8859-1');
define('site_url','http://applinkpro.com/Dev_Findme/');
define('host','http://applinkpro.com/Dev_Findme/uploads/');
define('location','https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=latlng&radius=100000&key=AIzaSyDDtKN07mqCaSUBMEQoj8hS52ShU9YEzyU');

function set_forgot_link($email){
$code=get_unique(20);
$query="UPDATE users set rand_code='$code' WHERE email = '$email'";
$result=mysql_query($query);
if($result){
    return $code;
}else{
    return 0;
}




}


function check_register_email($email)
{
     $select="SELECT * FROM users WHERE email = '$email'";
        $res=mysql_query($select);
        $num=mysql_num_rows($res);
        if($num>0){
            return 0;
        }else{
            return 1;
        }



}

function check_user_fav($userid,$e_id){

$sql="SELECT * FROM `user_favouraites`  WHERE user_id =$userid AND establishment_id = $e_id";
$result=mysql_query($sql);
return $num=mysql_num_rows($result);


}
function register_user($json_array)
{
                $user_name=mysql_real_escape_string($json_array['username']);
                $full_name=mysql_real_escape_string($json_array['fullname']);
                $mobile_no=$json_array['mobile'];
                $email=$json_array['email'];
                $password=base64_decode($json_array['password']);

                $device_id=$json_array['deviceid'];
                $zipcode=$json_array['zipcode'];
                $user_type=0;
                $address=mysql_real_escape_string($json_array['address']);
                $dob=$json_array['dob'];
                $code=get_unique(6);
                $device_type=$json_array['devicetype'];



                   

           $insert="INSERT INTO `users`(`user_name`, `full_name`, `mobile_no`, `email`, `password`, `zipcode`, `role`, `device_id`, `address` ,`dob` , `email_code`, `device_type`) VALUES ('".$user_name."','".$full_name."','".$mobile_no."','".$email."','".$password."','".$zipcode."','".$user_type."','".$device_id."','".$address."','".$dob."','".$code."','".$device_type."')";

              $result = mysql_query($insert);
               if($result>0)
              {
                $userid=mysql_insert_id();
                 if($user_type == 1)
                    {
                        $user_type ="Admin";
                    }else{

                        $user_type="customer";
                    }

                $data=array("userid"=>$userid,"fullname"=>$full_name,"username"=>$user_name,"address"=>$address,"email"=>$email,"mobile"=>$mobile_no,"zipcode"=>$zipcode,"usertype"=>$user_type);
                $subject='Find Me Email Verification ';
               $message='<body>DEAR User,<br><p>You have been successfully registered on FIND ME APP ,<br> please click the below link to verify your email <br>'.site_url.'welcome/verify_mail/'.$code.' <br><br> FIND ME<br></p></body>';
                                 $allow= send_mail($email,$message,$subject);

                return $data;
            }else{



                return null;
            }

}


function login($user_name,$pass)
{

  
        $sql="SELECT `userid`, `user_name`, `full_name`, `mobile_no`, `email`, `password`, `zipcode`, `role`, `device_id`, `device_token`, `address`, `dob`,status FROM `users` WHERE user_name = '".$user_name."'";
        $result=mysql_query($sql);
        $num=mysql_num_rows($result);
        if($num>0 && $num != 0 )
        {
                $row=mysql_fetch_array($result);

                $state = $row['status'];
                if($state != 1){
                    return $data=array("code"=>"2",'message'=>"verify your email address ");

                }

                $password=$row['password'];
               
                if($password == base64_decode($pass))
                {
                    $userid=$row['userid'];
                    $email=$row['email'];
                    $full_name=$row['full_name'];
                    $mobile_no=$row['mobile_no'];
                    $zipcode=$row['zipcode'];
                    $user_type=$row['role'];
                    $address=$row['address'];
                    $dob=$row['dob'];
                    if($user_type == 1)
                    {
                        $user_type ="Admin";
                    }else{

                        $user_type="customer";
                    }


         $check=array("userid"=>$userid,"fullname"=>$full_name,"username"=>$user_name,"address"=>$address,"email"=>$email,"mobile"=>$mobile_no,"zipcode"=>$zipcode,"usertype"=>$user_type,"dob"=>$dob);
           return $data=array("code"=>"1",'data'=>$check);
          }else{
          return  $data=array("code"=>"3",'message'=>"Password doesnot match ");
         }
      }else{
        return    $data=array("code"=>"0",'message'=>"user doesnot exist ");
      }
}
function get_user_detail($email){
$sql="SELECT `userid`, `user_name`, `full_name`,password FROM users WHERE email = '$email'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$userid=$row['userid'];
$username=$row['user_name'];
$fullname=$row['full_name'];
$password=$row['password'];
return $data=array("userid"=>$userid,"username"=>$username,"full_name"=>$fullname,"password"=>$password);
}

function send_mail($email,$message,$subject){
/**************sending email**************** */
    date_default_timezone_set('Etc/UTC');
    $mail = new PHPMailer;
    $mail->isSMTP();
//Set the hostname of the mail server
    $mail->Host = qa_opt('smtp_host');
    $mail->Port = qa_opt('smtp_port');
    $mail->SMTPSecure = qa_opt('smtp_secure');
    $mail->SMTPAuth = true;
    $mail->Username ="listfind1@gmail.com";
    $mail->Password = "findmetest@1409";
//Set who the message is to be sent from
    $mail->setFrom(qa_opt('from_email'));
//Set an alternative reply-to address
   $mail->addReplyTo('listfind1@gmail.com', 'ListAFind');
//Set who the message is to be sent to
    $mail->addAddress($email);
//Set the subject line
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = 'This is a plain-text message body';
//send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }else{
        return 1;
    }

}

function update_password($password, $code)
{

        $pass=base64_decode($password);
       $upd="UPDATE users set password ='$pass'  WHERE rand_code = '$code'";

        $result=mysql_query($upd);
        return 1;

}
/*function to update user details */
function update_user_detail($json_array){
$userid=$json_array['userid'];
$fullname=$json_array['fullname'];
// removing password updation
//$pass=base64_decode($json_array['password']);
$mob=$json_array['mobile'];
$sql="UPDATE `users` SET `full_name`='".$fullname."',`mobile_no`='".$mob."' WHERE userid = '".$userid."'";
$result=mysql_query($sql);
return $result;
}

function get_category_list($start)
{
   $sql="SELECT * FROM `category_table` WHERE `category_status` != 0  LIMIT $start,10";

    $result=mysql_query($sql);
    if(mysql_num_rows($result)>0){
    while($row=mysql_fetch_array($result))
    {
        $cat_id=$row['category_id'];
        $cat_name=$row['category_name'];
        $status=$row['category_status'];
        $created=$row['created'];
        $icon=host.$row['icon'];
       if($row['c_logo_url']){
        $logo=host.$row['c_logo_url'];
    }else{
        $logo=$row['c_logo_url'];
    }
    if($row['c_banner_url']){
        $banner=host.$row['c_banner_url'];
    }else{
        $banner=$row['c_banner_url'];
    }
        $data[]=array("categoryid"=>$cat_id,"categoryname"=>$cat_name,"status"=>$status,"logo"=>$logo,"banner"=>$banner,"icon"=>$icon);

    }
return $data;
}
else{
    return 0;
}

}

function get_subcategory_list($start,$category_id)
{
  $sql="SELECT * FROM `sub_category_table` WHERE `cat_id`= '$category_id' AND  `sub_category_status` != 0   LIMIT $start,10";

    $result=mysql_query($sql);
    if(mysql_num_rows($result)>0){
    while($row=mysql_fetch_array($result))
    {
        $cat_id=$row['sub_category_id'];
        $cat_name=$row['sub_category_name'];
        $status=$row['sub_category_status'];
        $created=$row['created'];
        $icon=get_category_icon($category_id);
        $logo=$row['s_logo_url'];
        if($logo != null ){
            $logo=host.$logo;
        }
        $banner=$row['s_banner_url'];
         if($banner != null ){
            $banner=host.$banner;
        }

        $data[]=array("categoryid"=>$category_id,"subcategoryid"=>$cat_id,"subcategoryname"=>$cat_name,"status"=>$status,"logo"=>$logo,"banner"=>$banner,"icon"=>$icon);

    }
return $data;
}
else{
    return 0;
}

}
function check_user_name($unm)
{
 $sql="SELECT user_name From users WHERE user_name = '$unm'";
$res=mysql_query($sql);
$num=mysql_num_rows($res);
if($num>0)
{
    return 1;
}else{
    return 0;
}

}
function get_search_data($keyword)
{

     $sql="SELECT category_id, 0 as sub_category_id, category_name, 0 as subcategory_name from category_table c WHERE c.category_name LIKE '%$keyword%' UNION SELECT cat_id, sub_category_id, 0,sub_category_name from sub_category_table s where s.sub_category_name like'%$keyword%'";
    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
    if($num>0){
    while($row=mysql_fetch_array($result))
    {
                $cat_id=$row['category_id'];
                $cat_name=$row['category_name'];
                $sub_cat=$row['sub_category_id'];
                $s_nm=$row['subcategory_name'];

                $data[]=array("categoryid"=>$cat_id,"subcategoryid"=>$sub_cat,"categoryname"=>$cat_name,"subcategoryname"=>$s_nm);
             }

        }else{
                $data=0;
        }
     return $data;
}


function get_establishment_list($start,$category_id,$sub)
{

 $sql="SELECT * FROM `establishment_table` WHERE `category_id`='".$category_id."' AND `sub_category_id`= '".$sub."' AND status != 0 LIMIT $start,10 ";

    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
    if($num>0){
    while($row=mysql_fetch_array($result)){
                $establishment_id=$row['establishment_id'];
                $establishment_name=$row['establishment_name'];
                $e_type=$row['establishment_type'];
                $address=$row['address'];
                $state=$row['state'];
                $city=$row['city'];
                $country=$row['country'];
                $c_code=$row['country_code'];
                $zipcode=$row['zipcode'];
                $email=$row['email'];
                $status=$row['status'];
                $created=$row['created'];
                $image=host.$row['logo'];
                $lat=$row['lat'];
                if($lat == ''){
                  $lat='0.0';
                }
                $long=$row['long'];
                if($long == ''){
                  $long='0.0';
                }
                $video=$row['Promo_video_url'];
                $q_code=$row['QR_code'];
                $w_url=$row['website_url'];
                $f_url=$row['facebook_url'];
                $contact=$row['phone_number'];
                $about=$row['About_us'];
                $net=$row['net_rating'];
                $trading_hours=$row['trading_hours'];
                         if(!empty($trading_hours)){
                         $trading_hours=preg_replace("/[^a-zA-Z 0-9 -:?]+/", " ", $trading_hours);
                          $mainarray=explode(',',$trading_hours);
                          if(!empty($mainarray)){
                           $data_=array();
                           $list=array();
                            foreach($mainarray as $values){
                                 if($values!=''){
                                   $data_[]=explode('?',trim($values));
                                    }
                             }
                             foreach ($data_ as $trade) {
                                   if(!empty($trade[0]) && isset($trade[0])){
                                    $trade1=str_replace(':'," ",$trade[0]);
                                    $list[] = trim($trade1).': '.trim($trade[1]);
                                  }
                              }
                              
                                $daily=$list;
                            }else{
                              $daily=null;
                            }    
                                
                        }else{
                        $daily=null;
                        }
                      $public=$row['public_holiday'];
                      $after_hour=$row['after_hour'];
                      $banner=array();
                       if(!empty($row['banner1'])){
                         $banner[]=host.$row['banner1'];
                        }
                        if(!empty($row['banner2'])){
                         $banner[]=host.$row['banner2'];
                         }
                       if(!empty($row['banner3'])){
                           $banner[]=host.$row['banner3'];
                       }
                       
                      if(!empty($row['banner4'])){
                       $banner[]=host.$row['banner4'];
                         }
                       if(!empty($row['banner5'])){
                       $banner[]=host.$row['banner5'];
                        }

                        if(empty($banner)){
                            $banner=array();
                        }
          
                         $map_icon=get_map_icon($category_id);
                         $booking=$row['booking'];
                          $icon=get_category_icon($category_id);
                        $data1[]=array("categoryid"=>$category_id,"subcategoryid"=>$sub,"establishmentid"=> $establishment_id,"establishmentname"=>$establishment_name,"type"=> $e_type,"status"=>$status,"address"=>$address,"state"=>$state,"country"=>$country,"city"=>$city,"countrycode"=>$c_code,"created"=>$created,"image"=>$image,"latitude"=>$lat,"longitude"=>$long,"tradinghours"=>$daily,"publicholiday"=>$public,"afterhour"=>$after_hour,"websiteurl"=>$w_url,"facebookurl"=>$f_url,"PhoneNumber"=>$contact,"aboutus"=>$about,"netrating"=>$net,"QRcode"=>$q_code,"email"=>$email,"banners"=>$banner,"map-icon"=>$map_icon,"booking"=>$booking,"icon"=>$icon);
                     }
                }
           

    else{

            $data1=array();
    }
    //print_r($data1);
return $data1;

}








function removeEmptyElements($var)
{
  return trim($var) != "" ? $var : null;
}

/*set message category for push notification*/
function set_message_category($data,$userid){
$cat_id=array();// intialize array
foreach($data as $row){// get category with status == 1
  if($row['status'] == 1){
       $cat_id[]=$row['categoryid'];// put in array
    }
  }// end foreach
$value=implode(',',$cat_id);
if(empty($cat_id)){
$value = null;
}
$check=check_user_cat($userid);// check if user has his entry in db or not
if($check == 0){//if not then insert 
$sql="INSERT INTO `category_message_list`(`user_id`, `category_id`) VALUES($userid,'$value')";
$result=mysql_query($sql);
return 1;
}else{// else update
$sql="UPDATE `category_message_list` set `category_id`= '$value'     WHERE user_id = $userid";
$result=mysql_query($sql);
return 1;
}//else close
}// function close


/*function to check user existence for message setting*/
function check_user_cat($userid){
 $sql="SELECT `user_id`, `category_id` FROM `category_message_list` WHERE  user_id = $userid ";
 $result=mysql_query($sql);
 if(mysql_num_rows($result)>0){return 1;}else{ return 0;}
}// function close



/*function to get user selected categories if not then return all with status 0*/
function get_message_category($userid){
$check=check_user_cat($userid);// checking user categories in db
if($check == 1)
{// if user found
 $sql="SELECT `user_id`, `category_id` FROM `category_message_list` WHERE  user_id = $userid ";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if($num>0){// rows available
$row=mysql_fetch_array($result);
$cat_id=$row['category_id'];// get category
if($cat_id != ''){
  // if not empty category
$data=explode(',',$cat_id);// explode the string in array

foreach($data as $value){
$c_id=$value;
$c_nm=get_cat_name($c_id);// get category name

if(get_category_status($c_id)){
$follow[]=array("categoryid"=>$c_id,"categoryname"=>$c_nm,"status"=>"1");// create follow category array
}

}// for each close

/*if any error then return empty follow array*/
if(empty($follow)){
$follow=array();
}

$unfollow=get_unfollow_cat($cat_id,1);// get unfollow category

$check=array_merge($follow,$unfollow);// merge the follow and unfollow array
if(!empty($check)){
  // check array not empty then return the combined array
return $check;
}

}else{
  //if category are not found in db for particular user return unfollow all category array
$get=get_unfollow_cat(0,2);
return $get;
}// category empty else condition

}else{
  /*if number of rows found 0 , return unfollow all category array */
$get=get_unfollow_cat(0,2);
return $get;
}// num row else condition 
   
}else{
  // if user not found then return unfollow all category array
$get=get_unfollow_cat(0,2);
return $get;
}// user data not found else condition


}// function close


/*function to get category name */
function get_cat_name($id){
$sql="SELECT `category_name` FROM `category_table` WHERE `category_id`= $id  ";
$res=mysql_query($sql);
$row=mysql_fetch_array($res);
return $name=$row['category_name'];
}// function close

/*function to get unfollow message category*/
function get_unfollow_cat($id,$type){
//$id implies category id and type implies user categories are available 
  //if type =1 (available) type = 2 not avvailable
if($type == 1){// get those categories that user doesnot follow
 $sql="SELECT `category_id`, category_name FROM `category_table` WHERE `category_id` NOT IN (".$id.") AND category_status != 0 ";
}else{// if not follow category than return all category array 
     $sql="SELECT `category_id`, category_name FROM `category_table` WHERE category_status != 0";
}
$result=mysql_query($sql);

while($row=mysql_fetch_array($result)){
    $category_id=$row['category_id'];
    $c_nm=$row['category_name'];
    $data[]=array("categoryid"=>$category_id,"categoryname"=>$c_nm,"status"=>"0");
}

if(empty($data)){
  $data=array();
}
return $data;
}

/*get category status*/
function get_category_status($id){
$sql="SELECT * FROM `category_table` WHERE category_id = $id";
$result=mysql_query($sql);
if($result){
  $row=mysql_fetch_array($result);
  $status=$row['category_status'];
  return $status;
}
}// function close








function get_e_typ_list($type,$e_id)
{

     $sql="SELECT `establishment_id`, `menu_name`, `menu_img_url`, `product_name`, `product_img_url`, `service_name`,  `service_img_url` FROM `establishment_type` WHERE `establishment_id`= $e_id ";
        $result=mysql_query($sql);
        $num=mysql_num_rows($result);


        if($num>0)
        {
            while($row=mysql_fetch_array($result))
            {
             // print_r($row);
               
                $m_url=$row['menu_img_url'];
                $p_url=$row['product_img_url'];
                $service=$row['service_img_url'];
               switch($type){
                
                case 1:
                        $data[]=array("image_url"=>host.$m_url);

                         break;
               case  2:

                     $data[]=array("image_url"=>host.$p_url);
                      
                      break;

                case 3:   $data[]=array('image_url'=>host.$service);
                        
                        break;
            }

        }
      
    return $data;

}else{
    return 0;
}

}


function get_estab_type($e_id)
{

    $sql="SELECT `establishment_type` FROM `establishment_table` WHERE `establishment_id`= $e_id AND status != 0 ";
    $result=mysql_query($sql);
            if($result)
            {


            $row=mysql_fetch_array($result);
            return $type=$row['establishment_type'];

            }else{

                return 0;
            }




}





function post_e_rate($json_array)
{
        $e_id=$json_array['establishmentid'];
        $u_id=$json_array['userid'];
        $text=$json_array['comment'];
        $rate=$json_array['rate'];
$check=check_user_rate($u_id,$e_id);
if($check == 1){
$sql="UPDATE `review_establishment` SET `review_text`= '$text' ,`rating`= $rate WHERE `establishment_id`= $e_id AND `user_id` = $u_id";
    $result=mysql_query($sql);
   if($result>0)
    {
                         extra_e_rate($e_id);
      
                        return 2;

    }else{

                     return 0;
    }

}else{

   $sql="INSERT INTO `review_establishment`( `establishment_id`, `user_id`, `review_text`, `rating`) VALUES ($e_id,$u_id,'$text',$rate)";
    $result=mysql_query($sql);

    if($result>0)
    {
                         extra_e_rate($e_id);
      
                        return 1;

    }else{

                     return 0;
    }
}

}
function check_user_rate($userid,$e_id)
{
$sql="SELECT * FROM `review_establishment` WHERE user_id = $userid AND  establishment_id = $e_id";
$res=mysql_query($sql);
return $num=mysql_num_rows($res);



}
function get_e_rate($json_array)
{
   $userid=$json_array['userid'];
   $e_id=$json_array['establishmentid'];

    $sql="SELECT `review_id`, `establishment_id`, `user_id`, `review_text`, `rating` FROM `review_establishment` WHERE user_id = $userid AND  establishment_id = $e_id";
    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
    if($num>0)
    {
        $row=mysql_fetch_array($result);
        $text=$row['review_text'];
        $rating=$row['rating'];

      return  $data=array("userid"=>$userid,"establishmentid"=>$e_id,"comment"=>$text,"rate"=>$rating);

    }else{
        return null;
    }


}


function extra_e_rate($e_id)
{
      $query="SELECT sum(rating)/count(rating) as net_rate from review_establishment WHERE establishment_id = $e_id";
        $res=mysql_query($query);
        $row=mysql_fetch_array($res);
      $count=$row['net_rate'];
        
     $sql="UPDATE establishment_table set `net_rating`= $count WHERE establishment_id = $e_id";
     $result=mysql_query($sql);  
 

   
     //  $sql="INSERT into establisment_table ( `net_rating`) values ( $count ) WHERE establishment_id = $e_id";
     // $result=mysql_query($sql);  

 

}


function get_user_favouraites($userid)
{
     $sql="SELECT  `establishment_id`, `flag` FROM `user_favouraites` WHERE `user_id`=$userid";
            $result=mysql_query($sql);
          //  $row=mysql_fetch_array($result);
            if(mysql_num_rows($result)>0)
            {

                while($row=mysql_fetch_array($result))
                {
                   $e_id=$row['establishment_id'];

                     $sql1="SELECT `establishment_id`, `category_id`, `sub_category_id`, `establishment_name`, `establishment_type`, `address`, `state`, `city`, `country`, `country_code`, `zipcode`, `email`, `phone_number`, `trading_hours`, `image_url`, `logo`, `icon`, `Geo_coordinates`, `Promo_video_url`, `QR_code`, `website_url`, `facebook_url`, `status`, `created`, `About_us`, `net_rating`, `booking`, `lat`, `long`, `banner1`, `banner2`, `banner3`, `banner4`, `banner5`, public_holiday , after_hour FROM `establishment_table` WHERE establishment_id = $e_id AND status != 0 ";
                    $result1=mysql_query($sql1);

                    $row=mysql_fetch_array($result1);

   
                  $establishment_id=$row['establishment_id'];
                $establishment_name=$row['establishment_name'];
                $category_id=$row['category_id'];
                $sub=$row['sub_category_id'];
                $e_type=$row['establishment_type'];
                $address=$row['address'];
                $state=$row['state'];
                $city=$row['city'];
                $country=$row['country'];
                $c_code=$row['country_code'];
                $zipcode=$row['zipcode'];
                $email=$row['email'];
                $status=$row['status'];
                $created=$row['created'];
                $image=host.$row['logo'];
                $lat=$row['lat'];
                if($lat == ''){
                  $lat='0.0';
                }
                $long=$row['long'];
                if($long == ''){
                  $long='0.0';
                }
                $video=$row['Promo_video_url'];
                $q_code=$row['QR_code'];
                $w_url=$row['website_url'];
                $f_url=$row['facebook_url'];
                $contact=$row['phone_number'];
                $about=$row['About_us'];
                $net=$row['net_rating'];
                $trading_hours=$row['trading_hours'];
                         if(!empty($trading_hours)){
                         $trading_hours=preg_replace("/[^a-zA-Z 0-9 -:?]+/", " ", $trading_hours);
                          $mainarray=explode(',',$trading_hours);
                          if(!empty($mainarray)){
                           $data_=array();
                           $list=array();
                            foreach($mainarray as $values){
                                 if($values!=''){
                                   $data_[]=explode('?',trim($values));
                                    }
                             }
                             foreach ($data_ as $trade) {
                                   if(!empty($trade[0]) && isset($trade[0])){
                                    $trade1=str_replace(':'," ",$trade[0]);
                                    $list[] = trim($trade1).': '.trim($trade[1]);
                                  }
                                  
                              }
                              
                                $daily=$list;
                            }else{
                              $daily=null;
                            }    
                                
                        }else{
                        $daily=null;
                        }
                       $public=$row['public_holiday'];
                      $after_hour=$row['after_hour'];
                      $banner=array();
                       if(!empty($row['banner1'])){
                         $banner[]=host.$row['banner1'];
                        }
                        if(!empty($row['banner2'])){
                         $banner[]=host.$row['banner2'];
                         }
                       if(!empty($row['banner3'])){
                           $banner[]=host.$row['banner3'];
                       }
                       
                      if(!empty($row['banner4'])){
                       $banner[]=host.$row['banner4'];
                         }
                       if(!empty($row['banner5'])){
                       $banner[]=host.$row['banner5'];
                        }

                        if(empty($banner)){
                            $banner=array();
                        }
        
                       $map_icon=get_map_icon($category_id);
                      $icon=get_category_icon($category_id);
                          $booking=$row['booking'];
                        $data1[]=array("categoryid"=>$category_id,"subcategoryid"=>$sub,"establishmentid"=> $establishment_id,"establishmentname"=>$establishment_name,"type"=> $e_type,"status"=>$status,"address"=>$address,"state"=>$state,"country"=>$country,"city"=>$city,"countrycode"=>$c_code,"created"=>$created,"image"=>$image,"latitude"=>$lat,"longitude"=>$long,"tradinghours"=>$daily,"publicholiday"=>$public,"afterhour"=>$after_hour,"websiteurl"=>$w_url,"facebookurl"=>$f_url,"PhoneNumber"=>$contact,"aboutus"=>$about,"netrating"=>$net,"QRcode"=>$q_code,"email"=>$email,"banners"=>$banner,"map-icon"=>$map_icon,"icon"=>$icon,"booking"=>$booking);
                    
                            }
                           
                          
                            return $data1;
                        }else{


                            return 0;
                        }


}

function get_estab_detail($e_id)
{

 $sql="SELECT `establishment_id`, `category_id`, `sub_category_id`, `establishment_name`, `establishment_type`, `address`, `state`, `city`, `country`, `country_code`, `zipcode`, `email`, `phone_number`, `trading_hours`, `image_url`, `logo`, `icon`, `Geo_coordinates`, `Promo_video_url`, `QR_code`, `website_url`, `facebook_url`, `status`, `created`, `About_us`, `net_rating`, `booking`, `lat`, `long`, `banner1`, `banner2`, `banner3`, `banner4`, `banner5` , public_holiday , after_hour FROM `establishment_table` WHERE establishment_id = $e_id AND status != 0 ";
$result=mysql_query($sql);

$row=mysql_fetch_array($result);

   
       $establishment_id=$row['establishment_id'];
                $establishment_name=$row['establishment_name'];
                $category_id=$row['category_id'];
                $sub=$row['sub_category_id'];
                $e_type=$row['establishment_type'];
                $address=$row['address'];
                $state=$row['state'];
                $city=$row['city'];
                $country=$row['country'];
                $c_code=$row['country_code'];
                $zipcode=$row['zipcode'];
                $email=$row['email'];
                $status=$row['status'];
                $created=$row['created'];
                $image=host.$row['logo'];
                $lat=$row['lat'];
                if($lat == ''){
                  $lat='0.0';
                }
                $long=$row['long'];
                if($long == ''){
                  $long='0.0';
                }
                $video=$row['Promo_video_url'];
                $q_code=$row['QR_code'];
                $w_url=$row['website_url'];
                $f_url=$row['facebook_url'];
                $contact=$row['phone_number'];
                $about=$row['About_us'];
                $net=$row['net_rating'];
                $trading_hours=$row['trading_hours'];
                         if(!empty($trading_hours)){
                         $trading_hours=preg_replace("/[^a-zA-Z 0-9 -:?]+/", " ", $trading_hours);
                          $mainarray=explode(',',$trading_hours);
                          if(!empty($mainarray)){
                           $data_=array();
                           $list=array();
                            foreach($mainarray as $values){
                                 if($values!=''){
                                   $data_[]=explode('?',trim($values));
                                    }
                             }
                             foreach ($data_ as $trade) {
                                   if(!empty($trade[0]) && isset($trade[0])){
                                    $trade1=str_replace(':'," ",$trade[0]);
                                    $list[] = trim($trade1).': '.trim($trade[1]);
                                  }
                              }
                              
                                $daily=$list;
                            }else{
                              $daily=null;
                            }    
                                
                        }else{
                        $daily=null;
                        }
                         $public=$row['public_holiday'];
                      $after_hour=$row['after_hour'];
                      $banner=array();
                       if(!empty($row['banner1'])){
                         $banner[]=host.$row['banner1'];
                        }
                        if(!empty($row['banner2'])){
                         $banner[]=host.$row['banner2'];
                         }
                       if(!empty($row['banner3'])){
                           $banner[]=host.$row['banner3'];
                       }
                       
                      if(!empty($row['banner4'])){
                       $banner[]=host.$row['banner4'];
                         }
                       if(!empty($row['banner5'])){
                       $banner[]=host.$row['banner5'];
                        }

                        if(empty($banner)){
                            $banner=array();
                        }
          
                       $map_icon=get_map_icon($category_id);
$icon=get_category_icon($category_id);
$booking=$row['booking'];
                        $data1[]=array("categoryid"=>$category_id,"subcategoryid"=>$sub,"establishmentid"=> $establishment_id,"establishmentname"=>$establishment_name,"type"=> $e_type,"status"=>$status,"address"=>$address,"state"=>$state,"country"=>$country,"city"=>$city,"countrycode"=>$c_code,"created"=>$created,"image"=>$image,"latitude"=>$lat,"longitude"=>$long,"tradinghours"=>$daily,"publicholiday"=>$public,"afterhour"=>$after_hour,"websiteurl"=>$w_url,"facebookurl"=>$f_url,"PhoneNumber"=>$contact,"aboutus"=>$about,"netrating"=>$net,"QRcode"=>$q_code,"email"=>$email,"banners"=>$banner,"map-icon"=>$map_icon,"icon"=>$icon,"booking"=>$booking);
                     

return $data1;
}


function get_booked($json_array)
{
$userid=$json_array['userid'];
$e_id=$json_array['establishmentid'];
$time=$json_array['datetime'];
$unm=$json_array['username'];
$email=$json_array['email'];
$mob=$json_array['contact'];
$descript=$json_array['addinfo'];
$no=$json_array['people'];




 $sql="INSERT INTO `booking_establishment`(`establishment_id`, `user_id`, `user_name`, `email`, `contact_no`, `booking_time`, `status`,info,num) VALUES ($e_id,$userid,'$unm','$email',$mob,'$time','pending','$descript',$no)";
$result=mysql_query($sql);
        if($result>0){
          $id=mysql_insert_id();
          $accept=base64_encode('accept-'.$id);
            $deny=base64_encode('deny-'.$id);
            $data=get_estab_detail($e_id);
            
           $name=$data[0]["establishmentname"];
            $subject="Booking Request from Find me";
           
           $body='<p>Dear <strong>'.$name .',</strong></p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; Your Establishment recieved a booking for '.$no.' person(s),</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; The request booking time is '.$time.' (local-time).</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; The guest details to contact regarding this booking are</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; Name : '.$unm.'</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; Contact no.: '.$mob.'</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; Email-address: '.$email.'</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; Please Click on the below links to follow up&nbsp;with your guest</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; promptly to confirm or to deny &nbsp;the booking.&nbsp;</p>
<blockquote><a href="'.site_url.'welcome/booking/'.$accept.'"><button>Accept</button></a>&nbsp; &nbsp; &nbsp; &nbsp;<a href="'.site_url.'welcome/booking/'.$deny.'"><button>Deny</button></a></blockquote>
<blockquote>Best Regards,</blockquote>
<blockquote>ListAFind</blockquote>
';
                  $mail=$data[0]['email'];
               // $mail="developerproduct123@gmail.com";
                  send_mail($mail,$body,$subject);
                         return 1;

                }else{
                         return 0;
                }

}
function get_all_advertise()
{

$sql="SELECT `ad_id`, `establishment_id`, `ad_img_url`, `ad_content` FROM `advertise` WHERE `ad_img_url` != '' ";
$result=mysql_query($sql);
            if(mysql_num_rows($result)>0)
            {
                while($row=mysql_fetch_array($result)){
                        $ad_id=$row['ad_id'];
                        $e_id=$row['establishment_id'];
                        $img=$row['ad_img_url'];
                        if(!empty($img)){
                            $img=host.$img;
                        }
                        $con=$row['ad_content'];
                       

                        $data[]=array("advertiseid"=>$ad_id,"establishmentid"=>$e_id,"imageurl"=>$img,"content"=>$con);
                     


                }
        return $data;

     }else{

          return ;

            }

}



function set_qr_code($json_array)
{
    $userid=$json_array['userid'];
    $cycle=get_user_code_cycle($userid);
    $code=$json_array['code'];
    $pos=$json_array['place'];
  
  $check=check_qr_scan_time($userid,$cycle);

           if($check == 1){
            $time=date('Y-m-d H:i:s');
         $sql="INSERT INTO `qr_codes` (`user_id`, `cycle`, `code_id`, `position`, `date-time`) VALUES ($userid,$cycle,'$code',$pos,'$time')";
         $result=mysql_query($sql);
                if($result>0){
                        $reward=check_user_reward($userid,$cycle);
                        if($reward == 0){
                              $num=get_return($userid,$cycle);
                                return $num;
                            }else{
                                return 10;
                            }

                }else{
                        return;
                }
         }else{
             return $check;
        }

}


function get_return($userid,$cycle)
{
     $sql="SELECT count(`user_id`) FROM `qr_codes` WHERE `user_id` = $userid AND  `cycle`= $cycle  order by `cycle` DESC";
      $result=mysql_query($sql);
    if($result>0){
       $row=mysql_fetch_array($result);
       return $row['count(`user_id`)'];
    }



}
function get_user_code_cycle($userid)
{
    $sql="SELECT `userid`, `Q_cycle` FROM `reward_cycle` WHERE `userid` = $userid order by `Q_cycle` desc";
  $result=mysql_query($sql);
    if(mysql_num_rows($result)>0){
        
            while($row=mysql_fetch_array($result)){
            return $row['Q_cycle'];
            }

    }else{
        $sql="INSERT into reward_cycle (`userid`, `Q_cycle`) VALUES ($userid , '1')";
            $result=mysql_query($sql);
            if($result>0)
                return 1;

    }


}

function check_user_reward($userid,$cycle){

$sql="SELECT count(cycle) from `qr_codes` WHERE `user_id`= $userid AND cycle = $cycle";
$res=mysql_query($sql);
$row=mysql_fetch_array($res);

//echo $row['count(cycle)'];
            if($row['count(cycle)']%6 == 0){
                $cycle=$cycle+1;
                $sql="INSERT into reward_cycle (`userid`, `Q_cycle`) VALUES ($userid , $cycle)";
            $result=mysql_query($sql);
            if($result>0)
                return 1;

                    return 1;
            }else{
              
                     return 0;

            }

}




function reset_qr_code($json_array)
{
    $userid=$json_array['userid'];
    $sql="SELECT `Q_cycle` FROM `reward_cycle` WHERE `userid` = $userid ORDER by `Q_cycle` DESC";
    $result=mysql_query($sql);
        if($result>0){
        $row=mysql_fetch_array($result);

        $cycle=$row['Q_cycle'];
        return $cycle;
    }else{
        return 0;
    }

}


function get_user_filled_pos($cycle,$userid){

$sql="SELECT `position` FROM `qr_codes` WHERE `user_id`='$userid' AND `cycle`='$cycle' AND `position` !='0'";
$result=mysql_query($sql);
if(mysql_num_rows($result) >0){

while($row=mysql_fetch_array($result)){
$position[]=$row['position'];
}
return $position;

}


}

function get_qr_banner(){

    $sql="SELECT `qr_banner` FROM `qr_codes` WHERE `sno` = 1";
    $result=mysql_query($sql);
     if($result){
          $row=mysql_fetch_assoc($result);
          $image=$row['qr_banner'];
          return host.$image;
     }else{
      return null;
     }





}












function check_qr_scan_time($userid,$cycle){


   $sql="select * from qr_codes WHERE `user_id`='".$userid."' AND`cycle`=  '".$cycle."' order by `sno` desc limit 1";
  $result=mysql_query($sql);
  $num=mysql_num_rows($result);
  if($num>0){
        $row=mysql_fetch_assoc($result);
        $fetch_date=strtotime($row['date-time']);
        $current_date=strtotime(date('Y-m-d H:i:s'));
        $diff = abs($current_date - $fetch_date);
        $time = round($diff / 60);

        $seconds = round($time * 60);
        $minutes = $time;
        $hours = round($minutes / 60);
        $days = round($hours / 24);
        $weeks = round($days / 7);
        $months = round($weeks / 4);
        $years = round($months / 12);
        if($hours > 24){
          return 1;
        }else{
          return 4;
        }

  }else{
      if($cycle >1){
        $nw_cycle = $cycle-1 ;
         $sql="SELECT `date-time` FROM `qr_codes` WHERE `user_id`='".$userid."' AND `cycle` =  $nw_cycle  order by `sno` desc limit 1 ";
          $result=mysql_query($sql);
          if(mysql_num_rows($result) > 0){
             $row=mysql_fetch_assoc($result);
              $fetch_date=strtotime($row['date-time']);
              $current_date=strtotime(date('Y-m-d H:i:s'));
              $diff = abs($current_date - $fetch_date);
              $time = round($diff / 60);
              $hours = round($time / 60);
                if($hours > 24){
                  return 1;
                }else{
                  return 4;
                }
          }else{
            return 1;
          }


      }// if condition close
      else{
        return 1;

      }// inner else close

  }// outer else close

}// function close






function get_all_sub_category($sub)
{
   

$sql="SELECT `sub_category_id`, `cat_id`, `sub_category_name`, `sub_category_img`, `sub_category_status`, `created`, `s_logo_url`, `s_banner_url` FROM `sub_category_table`WHERE `sub_category_name` LIKE '%$sub%' AND sub_category_status != 0 ";

    $result=mysql_query($sql);
    if(mysql_num_rows($result)>0){
                while($row=mysql_fetch_array($result))
                {
                    $cat_id=$row['sub_category_id'];
                    $cat_name=$row['sub_category_name'];
                    $status=$row['sub_category_status'];
                    $created=$row['created'];
                    $logo=$row['s_logo_url'];
                    $banner=$row['s_banner_url'];
                    $category_id=$row['cat_id'];

                    $data[]=array("categoryid"=>$category_id,"subcategoryid"=>$cat_id,"subcategoryname"=>$cat_name,"status"=>$status,"logo"=>$logo,"banner"=>$banner);

                }
        return $data;
     }
     else{
            return 0;
        }



}

function get_specials($eid){
    $sql="SELECT `special_id`, `establishment_id`, `description`, `price`, `image_url`, `video_url`, `youtube_url` FROM `specialestablishments`WHERE `establishment_id`= $eid order by `special_id` DESC LIMIT 1 ";
              $result=mysql_query($sql);
              if(mysql_num_rows($result)>0){
                  while($row=mysql_fetch_array($result)){
                      $s_id=$row['special_id'];
                      $e_id=$row['establishment_id'];
                      $des=trim($row['description']);
                      $price='R'.trim($row['price']);
                      $img=explode(',',$row['image_url']);
                      $v_url=$row['video_url'];
                      $y_url=$row['youtube_url'];
                      if($y_url == NULL && $v_url != ''){
                        $v_url=host.$v_url;
                        $y_url = '<iframe width="560" height="315"   src="'.$v_url.'" frameborder="0" allowfullscreen></iframe>';
                      }

                      $data[]=array("specialsid"=>$s_id,"establishmentid"=>$e_id,"description"=>$des,"price"=>$price,"image"=>$img,"video"=>$v_url,"youtubelink"=>$y_url);
                  }
             return $data;
          }else{
             return null;
       }
}



function get_estab_sub($cat_id,$sub_id,$key){
 $sql="SELECT `establishment_id`, `category_id`, `sub_category_id`, `establishment_name`, `establishment_type`, `address`, `state`, `city`, `country`, `country_code`, `zipcode`, `email`, `phone_number`, `trading_hours`, `image_url`, `logo`, `icon`, `Geo_coordinates`, `Promo_video_url`, `QR_code`, `website_url`, `facebook_url`, `status`, `created`, `About_us`, `net_rating`, `booking`, `lat`, `long`, `banner1`, `banner2`, `banner3`, `banner4`, `banner5` , public_holiday , after_hour FROM `establishment_table` WHERE `category_id`='".$cat_id."' AND `sub_category_id`= '".$sub_id."' AND `establishment_name` LIKE '%$key%' AND status != 0 ";

    $result=mysql_query($sql);
    if(mysql_num_rows($result)>0){
    while($row=mysql_fetch_array($result)){
                $establishment_id=$row['establishment_id'];
                $establishment_name=$row['establishment_name'];
                $category_id=$row['category_id'];
                $sub=$row['sub_category_id'];
                $e_type=$row['establishment_type'];
                $address=$row['address'];
                $state=$row['state'];
                $city=$row['city'];
                $country=$row['country'];
                $c_code=$row['country_code'];
                $zipcode=$row['zipcode'];
                $email=$row['email'];
                $status=$row['status'];
                $created=$row['created'];
                $image=host.$row['logo'];
                $lat=$row['lat'];
                if($lat == ''){
                  $lat='0.0';
                }
                $long=$row['long'];
                if($long == ''){
                  $long='0.0';
                }
                $video=$row['Promo_video_url'];
                $q_code=$row['QR_code'];
                $w_url=$row['website_url'];
                $f_url=$row['facebook_url'];
                $contact=$row['phone_number'];
                $about=$row['About_us'];
                $net=$row['net_rating'];
                $trading_hours=$row['trading_hours'];
                         if(!empty($trading_hours)){
                         $trading_hours=preg_replace("/[^a-zA-Z 0-9 -:?]+/", " ", $trading_hours);
                          $mainarray=explode(',',$trading_hours);
                          if(!empty($mainarray)){
                           $data_=array();
                           $list=array();
                            foreach($mainarray as $values){
                                 if($values!=''){
                                   $data_[]=explode('?',trim($values));
                                    }
                             }
                             foreach ($data_ as $trade) {
                                   if(!empty($trade[0]) && isset($trade[0])){
                                    $trade1=str_replace(':'," ",$trade[0]);
                                    $list[] = trim($trade1).': '.trim($trade[1]);
                                  }
                              }
                              
                                $daily=$list;
                            }else{
                              $daily=null;
                            }    
                                
                        }else{
                        $daily=null;
                        }
                         $public=$row['public_holiday'];
                      $after_hour=$row['after_hour'];
                      $banner=array();
                       if(!empty($row['banner1'])){
                         $banner[]=host.$row['banner1'];
                        }
                        if(!empty($row['banner2'])){
                         $banner[]=host.$row['banner2'];
                         }
                       if(!empty($row['banner3'])){
                           $banner[]=host.$row['banner3'];
                       }
                       
                      if(!empty($row['banner4'])){
                       $banner[]=host.$row['banner4'];
                         }
                       if(!empty($row['banner5'])){
                       $banner[]=host.$row['banner5'];
                        }

                        if(empty($banner)){
                            $banner=array();
                        }
          
                        $map_icon=get_map_icon($category_id);
                        $icon=get_category_icon($category_id);
                        $booking=$row['booking'];
                        $data1[]=array("categoryid"=>$category_id,"subcategoryid"=>$sub,"establishmentid"=> $establishment_id,"establishmentname"=>$establishment_name,"type"=> $e_type,"status"=>$status,"address"=>$address,"state"=>$state,"country"=>$country,"city"=>$city,"countrycode"=>$c_code,"created"=>$created,"image"=>$image,"latitude"=>$lat,"longitude"=>$long,"tradinghours"=>$daily,"publicholiday"=>$public,"afterhour"=>$after_hour,"websiteurl"=>$w_url,"facebookurl"=>$f_url,"PhoneNumber"=>$contact,"aboutus"=>$about,"netrating"=>$net,"QRcode"=>$q_code,"email"=>$email,"banners"=>$banner,"map-icon"=>$map_icon,"icon"=>$icon,"booking"=>$booking);
                     }
    return $data1;
}else{
    
}
}



function CallAPI($method, $url, $data = false) {

   $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  
  CURLOPT_HTTPHEADER => array(
    "authorization: Basic dGVzdDpDQzE5ODEj",
    "cache-control: no-cache",
    "content-type: application/json",
    "salt: d11ce45f610c1bdb0e7c8f837530ab9980472fe9"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
   // echo $response;
  return $response;
}
  //  return $result;
}


function get_location_establishments($lat,$long){
         $sql="SELECT `establishment_id`, `category_id`, `sub_category_id`, `establishment_name`, `establishment_type`, `address`, `state`, `city`, `country`, `country_code`, `zipcode`, `email`, `phone_number`, `trading_hours`, `image_url`, `logo`, `icon`, `Geo_coordinates`, `Promo_video_url`, `QR_code`, `website_url`, `facebook_url`, `status`, `created`, `About_us`, `net_rating`, `booking`, `lat`, `long`, `banner1`, `banner2`, `banner3`, `banner4`, `banner5`, public_holiday , after_hour , ( 6371 * acos( cos( radians(".$lat.") ) * cos( radians( `lat` ) ) * cos( radians( `long` ) - radians(".$long.") ) + sin(radians(".$lat.")) * sin(radians(`lat`)) ) )  FROM `establishment_table` WHERE lat != '' AND status != 0 ";
            $result=mysql_query($sql);
            if($result)
            if(mysql_num_rows($result)>0)
            {

                while($row=mysql_fetch_assoc($result)){
                 //print_r($row);
                $establishment_id=$row['establishment_id'];
                $establishment_name=$row['establishment_name'];
                $category_id=$row['category_id'];
                $sub=$row['sub_category_id'];
                $e_type=$row['establishment_type'];
                $address=$row['address'];
                $state=$row['state'];
                $city=$row['city'];
                $country=$row['country'];
                $c_code=$row['country_code'];
                $zipcode=$row['zipcode'];
                $email=$row['email'];
                $status=$row['status'];
                $created=$row['created'];
                $image=host.$row['logo'];
                $video=$row['Promo_video_url'];
                $q_code=$row['QR_code'];
                $w_url=$row['website_url'];
                $f_url=$row['facebook_url'];
                $contact=$row['phone_number'];
                $about=$row['About_us'];
                $net=$row['net_rating'];
                $lat=$row['lat'];
                if($lat == ''){
                  $lat=null;
                }
                $long=$row['long'];
                if($long == ''){
                  $long=null;
                }
                $trading_hours=$row['trading_hours'];
                         if(!empty($trading_hours)){
                         $trading_hours=preg_replace("/[^a-zA-Z 0-9 -:?]+/", " ", $trading_hours);
                          $mainarray=explode(',',$trading_hours);
                          if(!empty($mainarray)){
                           $data_=array();
                           $list=array();
                            foreach($mainarray as $values){
                                 if($values!=''){
                                   $data_[]=explode('?',trim($values));
                                    }
                             }
                             foreach ($data_ as $trade) {
                                   if(!empty($trade[0]) && isset($trade[0])){
                                    $trade1=str_replace(':'," ",$trade[0]);
                                    $list[] = trim($trade1).': '.trim($trade[1]);
                                  }
                              }
                              
                                $daily=$list;
                            }else{
                              $daily=null;
                            }    
                                
                        }else{
                        $daily=null;
                        }
                         $public=$row['public_holiday'];
                      $after_hour=$row['after_hour'];
                      $banner=array();
                       if(!empty($row['banner1'])){
                         $banner[]=host.$row['banner1'];
                        }
                        if(!empty($row['banner2'])){
                         $banner[]=host.$row['banner2'];
                         }
                       if(!empty($row['banner3'])){
                           $banner[]=host.$row['banner3'];
                       }
                       
                      if(!empty($row['banner4'])){
                       $banner[]=host.$row['banner4'];
                         }
                       if(!empty($row['banner5'])){
                       $banner[]=host.$row['banner5'];
                        }

                        if(empty($banner)){
                            $banner=array();
                        }
                      $map_icon=get_map_icon($category_id);
                      $icon=get_category_icon($category_id);
                          $booking=$row['booking'];
                        $data1[]=array("categoryid"=>$category_id,"subcategoryid"=>$sub,"establishmentid"=> $establishment_id,"establishmentname"=>$establishment_name,"type"=> $e_type,"status"=>$status,"address"=>$address,"state"=>$state,"country"=>$country,"city"=>$city,"countrycode"=>$c_code,"created"=>$created,"image"=>$image,"latitude"=>$lat,"longitude"=>$long,"tradinghours"=>$daily,"publicholiday"=>$public,"afterhour"=>$after_hour,"websiteurl"=>$w_url,"facebookurl"=>$f_url,"PhoneNumber"=>$contact,"aboutus"=>$about,"netrating"=>$net,"QRcode"=>$q_code,"email"=>$email,"banners"=>$banner,"map-icon"=>$map_icon,"icon"=>$icon,"booking"=>$booking);
                     }
              return $data1;
            }else{
                    return 0;
            }
}



/*************************************************************************/
function get_all_beacon(){
    $sql="SELECT * FROM beacon";
    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
    if($num>0){
        while($row=mysql_fetch_assoc($result)){
            $beacon_id=$row['beacon_id'];
            $major=trim($row['major_no']);
            $minor=trim($row['minor_no']);
            $description=trim($row['Description']);
            $data[]=array("uuid"=>$beacon_id,"major"=>$major,"minor"=>$minor,"description"=>$description);
        }
      return $data;
    }
}

/**********************************************************************/

function location(){
  /*  https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=22.725565,%2075.881180&radius=500&type=restaurant&keyword=cruise&key=AIzaSyDDtKN07mqCaSUBMEQoj8hS52ShU9YEzyU*/

  //by lat & long
 /* http://maps.googleapis.com/maps/api/geocode/json?latlng=22.725565,%2075.881180&sensor=true*/

 // autocomplete
 /*
https://maps.googleapis.com/maps/api/place/queryautocomplete/json?&key=AIzaSyDO4nV1f65CrKGB7rCFFSaCJS5JOFJ-1Fc&input=pizza
 */
}
function get_map_icon($category_id){

$sql="SELECT `map_icon` FROM `category_table` WHERE `category_id` = '".$category_id."'";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
$img=host.$row['map_icon'];
return $img;
}

function get_category_icon($category_id){
$sql="SELECT `icon` FROM `category_table` WHERE `category_id` = '".$category_id."'";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
$img=host.$row['icon'];
return $img;
}

function qa_opt($title){
   $select="SELECT content FROM options WHERE title = '$title'";
   $result=mysql_query($select);
   $row=mysql_fetch_array($result);
   return $content=$row['content'];
}

function get_unique($length){
    $string = "abcdeJKLMNOP0123456789pqrstuvw";
    $cookie_str = str_shuffle($string);
    $cookie_str = substr($cookie_str, 0, $length);
    return $cookie_str;
}

?>