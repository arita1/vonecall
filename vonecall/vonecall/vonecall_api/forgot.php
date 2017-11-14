<?php
if (isset($_REQUEST["email"])) {
    $email = $_REQUEST["email"];
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Forgot password</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <link href="css/my.css" rel="stylesheet">
            <script>
                $(function () {
                    $("#btn-reset").click(function () {
                        var password = $("#pass").val();
                        var confirmPassword = $("#cpass").val();
                        //if(password.length || confirmPassword.length < 8)
                        // {
                        //  alert("Password length must be 8 digits.");
                        //  return false;  
                        // }
                        if (password != confirmPassword) {
                            alert("Passwords do not match.");
                            return false;
                        }
                        return true;
                    });
                });
            </script>
        </head>
        <body>

            <div class="container">
                <!-- Modal -->
                <div class="modal fade in " id="myModal" role="dialog" style="display: block">
                    <div class="modal-dialog modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <center>  <h1 class="modal-title">Set New Password</h1></center>
                            </div>
                            <form role="form" action="#" method="post" id="login-form" >
                                <div class="form-group">
                                    <label>New password</label>
                                    <input type="password" name="pass" id="pass" class="form-control" placeholder="enter new password" >

                                </div>
                                <div class="form-group">
                                    <label>confirm Password</label>
                                    <input type="password" name="confirm" id="cpass" class="form-control" placeholder="retype above password" >

                                    <input type="submit" id="btn-reset"  name="s" class="btn btn-custom btn-lg btn-block" value="reset" >
                                    </form>

                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </body>
    </html>


    <?php
    if (isset($_REQUEST["s"])) {


        $password = base64_encode($_REQUEST["pass"]);
        $con = array("con" => "1", "email" => $email, "password" => $password);

      $con = json_encode($con);
       $hash = hash_hmac("sha1", $con, "pr_1003_ke_1402");
           

        $ch = curl_init('http://192.168.0.23/findme/findme/forgot_password');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $con);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($con),
            'salt:'. $hash)
        );

 $result = curl_exec($ch);
  $res=json_decode($result,true);
 $status=$res['status'];
        if ($status == 1){
         // $res=json_decode($result);
            $path=$res['data'];
            echo"<script>alert('Password Reset successfully');</script>";
            echo"<script>window.location='$path';</script>";
           //header("location:'".$path."'");
        }

        else{
            echo"<script>alert('Password couldn't be reset);</script>";
            
        }
       
    }


}
?>