<?php
/*
	Template Name: Logout 
*/
session_destroy();
wp_redirect( WP_SITEURL);

?>