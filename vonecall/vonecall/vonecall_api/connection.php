<?php


 function dbConnect() {
        $hostname = "localhost";
        $user = "root";
        $pass = "5exceptions@123";
        $db = "vonecall_test";
        $con = mysql_connect($hostname, $user, $pass) or die("problem in connection");
        mysql_select_db($db) or die("problem in selection");
    }



?>