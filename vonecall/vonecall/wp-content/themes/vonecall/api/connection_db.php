<?php
function dbConnect() {
        $hostname = "localhost";
        $user = "root";
        $pass = "";
        $db = "post3";
        $con = mysql_connect($hostname, $user, $pass) or die("problem in connection");
        mysql_select_db($db) or die("problem in selection");
    }

?>