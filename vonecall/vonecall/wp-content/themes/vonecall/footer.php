<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>
</div>
<!-- .site-content -->

<div class="download-app">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <h3>DOWNLOAD OUR APP AND GET $1 FREE</h3>
      </div>
      <div class="col-md-4">
        <div><a href="javascript:;" class="forget google-play img-responsive" data-toggle="modal" data-target="confirmation-modal" ><img src="<?php echo WP_SITEURL; ?>wp-content/uploads/android.jpg" width="150px"></a></div>
        <div><a href="javascript:;" class="forget app-store img-responsive" data-toggle="modal" data-target="confirmation-modal"><img src="<?php echo WP_SITEURL; ?>wp-content/uploads/i-phone.jpg" width="150px" width="150px"></a></div>
      </div>
    </div>
  </div>
</div>
<footer>
  <div class="footer-contant">
    <div class="container">
      <div class="row">
       <div class="col-md-3">
        <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
        </div>
        <div class="col-md-3">
            <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
        </div>
        <div class="col-md-3">
         <?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
        </div>
        <div class="col-md-3">
            <?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- .site-footer -->
<div id="copyright">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="copy-right">
            <p>
            <?php dynamic_sidebar( 'copyright-footer-widget-area' ); ?> 
	    </p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="copyright-manu">
          <ul>
            <?php wp_nav_menu( array('menu'=>'terms_conditions')); ?>
          </ul>
        </div>
      </div>


    </div>
  </div>
</div>
</div>
<!-- .site-inner -->
</div>
<!-- .site -->

<?php wp_footer(); ?>

<script>


$('.google-play,.app-store').click(function(e){
    e.preventDefault();
    $('.confirmation-modal').modal('show')
})

</script>

</body>
</html>