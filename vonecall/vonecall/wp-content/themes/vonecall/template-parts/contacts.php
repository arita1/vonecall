<?php
/* Template Name: contacts */
get_header();
?>

<div id="home-slider" style="margin-top:100px;">
  <?php echo do_shortcode('[crellyslider alias="support"]'); ?>
    <!--?php putRevSlider( 'slider1' ); ? //-->
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">  
            <div class="title-black" style="margin-top:50px;"> <h3> Contact Us  </h3></div>
                <div id="msgSubmit" class="h3 text-center hidden">
              </div> 
                <div class="loading">
          <img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>">
          </div>
            <!-- Start Contact Form -->
            <form method="post" role="form" id="contact-Form" class="contact-form" data-toggle="validator" action="#" >
              <div class="form-group col-md-6">
                <div class="controls">
                  <input type="text" id="name"  name="customer_name" class="form-control padding-20" placeholder="Your Name" required >
                </div>
              </div>
              <div class="form-group col-md-6">
                <div class="controls">
                  <input type="email" class="email form-control padding-20" id="email"  name="customer_email" placeholder="Your Email" required >
                </div>
              </div>
              <div class="form-group col-md-12">
                <div class="controls">
                  <input type="text" class="form-control padding-20"  name="mail_subject" placeholder="Subject" required >
                </div>
              </div>
              <div class="form-group col-md-12">
                <div class="controls">
               
                  <textarea id="message" rows="8" placeholder="Message"  name="message" class="form-control" required data-error="Write your message"></textarea>
                  <div class="help-block with-errors"></div>
                </div>  
              </div>
              <div class="form-group col-md-12">
                <div class="controls">
                  <button type="submit" id="submit" class="hvr-shutter-out-horizontal" > Send Message</button>
                </div>
              </div>
             <div class="clearfix"></div>   
           </form>     
            <!-- End Contact Form -->
        </div>
        <!--div class="col-md-6"> 
        <div class="title-black"> <h3>Support  </h3></div>
              <div class="information"> 
                <div class="contact-datails">  
              <div class="row contact_line">
                  <div class=" col-md-2">  <img class="text-center" src="https://www.vonecall.com/vonecall/wp-content/uploads/support1.png"></div><div class="col-md-4">Call Us <span ><strong> <br> 855-241-0007</strong></span></div>
              </div>     
               <div class="row contact_line">
                  <div class=" col-md-2">  <img class="text-center" src="https://www.vonecall.com/vonecall/wp-content/uploads/support3.png"></div><div class="col-md-4">Send us a<span id="email_move">  Message</span></div>
              </div>         
               <div class="row contact_line" >
                  <div class=" col-md-2">  <img class="text-center" src="https://www.vonecall.com/vonecall/wp-content/uploads/support4.png"></div><div class="col-md-4"> <span > Live Chat</span></div>
              </div>       
               <div class="row contact_line">
                  <div class=" col-md-2">  <img class="text-center" src="https://www.vonecall.com/vonecall/wp-content/uploads/support5.png"></div><div class="col-md-4"> <span >How It Works </span></div>
              </div> 
              </div>           
               <!  <div class="contact-datails">  
                
                  <div class="contact_line">  <img class="text-center" src="https://www.vonecall.com/vonecall/wp-content/uploads/support1.png"></div><div> <span class="cont-no"><strong>Call Us  855-241-0007</strong></span></div>

               <p><a href="mailto:Info@SkillMetrix.com"><i class="fa fa-envelope" aria-hidden="true"></i> abdi.hassan@rydtechnologies.com </a></p>
                </div> -->
            </div>
        </div //-->
    </div>
</div>

</div>
<?php
get_footer();
?>