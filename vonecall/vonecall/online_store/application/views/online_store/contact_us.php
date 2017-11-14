<?php $this->load->view('online_store/inc_header');?>


	<!-- slider -->
			<div id="main-slider" class="slider">
				  <div><img src="images/slider/img2.jpg" title="" /></div>
			</div>	
	<!-- /slider -->

<!-- content -->
<div class="content">
    <!-- /.intro-header -->
		
	<div class="how-it-work vone-contact-us">
			<div class="container">		
			 <!-- Start Content Section -->
      <section id="content">
        <div class="container">
          <div class="row">
            <div class="col-md-9">
              <h2>Contact Us</h2>   
              
				<div id="msgSubmit" class="h3 text-center hidden">
              </div> 
            <!-- Start Contact Form -->
            <form role="form" id="contactForm" class="contact-form" data-toggle="validator" class="shake" action="#">
              <div class="form-group">
                <div class="controls">
                  <input type="text" id="name" class="form-control" placeholder="Name" required data-error="Please enter your name">
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="form-group">
                <div class="controls">
                  <input type="email" class="email form-control" id="email" placeholder="Email" required data-error="Please enter your email">
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="form-group">
                <div class="controls">
                  <input type="text" id="msg_subject" class="form-control" placeholder="Subject" required data-error="Please enter your message subject">
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="form-group">
                <div class="controls">
                  <textarea id="message" rows="7" placeholder="Massage" class="form-control" required data-error="Write your message"></textarea>
                  <div class="help-block with-errors"></div>
                </div>  
              </div>

              <button type="submit" id="submit" class="btn btn-success"></i> Send Message</button>
              
              <div class="clearfix"></div>   

            </form>     
            <!-- End Contact Form -->

            </div>
            <div class="col-md-3">
              <h2 class="big-title">Contact Info</h2>   
              <div class="information">              
                <div class="contact-datails">  
                  <p> 777 Silver spur road suite 212,
                      Rolling Hills estate ,
                      CA 90274 
                  <p>
		        	<img class="float_left p10r" alt="" src="<?php echo base_url()?>public/images/icon_phone.png">
		          	<div class="contact_line">Customer Services </br> 24Hr/7days a week:<span class="red_color"><strong> 855-241-0007</strong></span></div>
		        </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- End Content Section  -->	
			</div>
		</div>
</div>
<?php $this->load->view('online_store/inc_footer');?>



