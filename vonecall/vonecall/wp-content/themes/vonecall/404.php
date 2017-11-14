<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
  <div class="container" style=" margin: 10% auto;" >
    <div class="row text-center" > <i style=" font-size: 180px; color:#3f8ed9;  " class="fa fa-exclamation-circle" aria-hidden="true"></i>
      <h1 style=" font-size: 90px; color: #000; font-weight: 700; ">404</h1>
      <h3 style=" color: #999;   font-weight: 400; ">Page not found</h3>
      <p style="  font-weight: 400; font-size: 16px;">The Page you are looking for doesn't exist or an other error occurred. </p>
      <br>
      <a style=" padding: 15px 40px; background: #0099ff; color: #fff; text-decoration: none; font-weight: 600; " href="javascript:history.go(-1)">Go back</a> </div>
  </div>
</div>
<!-- .content-area -->

<?php get_footer(); ?>
