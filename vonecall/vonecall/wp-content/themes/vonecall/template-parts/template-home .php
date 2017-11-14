<?php
/* Template Name: Home Page */
get_header();
?>
<div id="home-slider" style="margin-top:100px;">
  <?php echo do_shortcode('[crellyslider alias="home"]'); ?>
    <!--?php putRevSlider( 'slider1' ); ? //-->
</div>
<div class="clf">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-4">
        <div class="row">
          <div class="col-md-12 col-sm-12 text-center"> <img src="<?php echo WP_SITEURL; ?>wp-content/uploads/call-new.png"> </div>
          <div class="col-md-12 col-sm-12 text-center">
            <h3> Unlimited Calling Plans </h3>
            <p>Unlimited Calling Plan and Fair Use Policy of Vonecall Pinless will save you $$$. 
                Simply call all you want to a number of countries, from $10.00 to $50.00 a month. 
                Any calls outside unlimited calling plan will require additional funding.  <a id="call_details" href="#">See more...</a></p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="row">
          <div class="col-md-12 col-sm-12 text-center"> <img src="<?php echo WP_SITEURL; ?>wp-content/uploads/chat-new.png"> </div>
          <div class="col-md-12 col-sm-12 text-center">
            <h3> Live Chat </h3>
            <p>Welcome to Live Chat! <a  id="click1" href="#">Click HERE</a> to chat with one of our Vonecall Pinless Experts.
              Our Support team is available everyday from 9:00am to 9:00pm Pacific Standard Time.
              We're happy and available to help via <a href="contact" title="Click here to email">email</a> or <a href="contact" title="Click here to call">call</a> as well.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="row">
          <div class="col-md-12 col-sm-12 text-center"> <img src="<?php echo WP_SITEURL; ?>wp-content/uploads/faq-new.png"> </div>
          <div class="col-md-12 col-sm-12 text-center"> <a href="vonecall-faq">
            <h3> FAQ</h3> </a>
            <p> What is Vonecall pinless Calling Service?<p> 
            <p>Vonecall pinless is a premium quality enhanced prepaid pinless calling service for making domestic and international calls.<!-- 
Unlike calling cards Vonecall Pinless Service users do not have to dial 10 or 12 digit PINs every time they try to make a call.
                                                                                                                                          The caller just has to dial the access number followed by destination telephone number.  --><br> <span><a href="<?php echo WP_SITEURL; ?>vonecall-faq/?1/">See more...</a></span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<?php putRevSlider('slider11', 'homepage'); ?>

<!--div id="planes" >
  <div class="container">
    <div class="row">
       <div class="col-md-6 col-md-offset-6">
        <div class="planes-box">
          <div class="planes-heading"><img src="<?php// echo WP_SITEURL; ?>wp-content/uploads/Pinless.png"> Pinless Dailing </div>
          <div class="planes-detial" >
            <ul>
              <li>Best International Rates </li>
              <li>Manage your accounts online</li>
              <li>Check Your Call Details</li>
              <li>No Contracts</li>
              <li>No Expiration Date</li>
              <li>No Hidden Fees</li>
              <li>Online top up</li>
            </ul>
           <div>  <a href="register" class="hvr-shutter-out-horizontal pull-right "> Join Now </a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div //-->




<div id="rates-success">
  <div class="rates-success">
    <div class="container">
      <div class="row" >
        <div class="col-md-12">
          <div id="exTab3">
            <ul  class="nav nav-pills " style="width: 100%;" >
              <li class="active" id="access"> <a  href="#1b" data-toggle="tab">Access Numbers</a> </li>
              <li id="rates"><a href="#2b" data-toggle="tab">View Rates</a> </li>
            </ul>
            <div class="tab-content clearfix">
              <div class="tab-pane active" id="1b">
     
       <table id="access-numbers" class="table table-bordered" cellspacing="0" width="100%">
       <thead class="tab-the-bg" >
           <tr>
              <!--  <th>#</th> -->
               <th>Access Number</th>
               <th>City</th>
               <th>State</th>
               <th>Language</th>
             
           </tr>
       </thead>
       <tbody>
        <?php
         do_action('Get_access_numbers');
         $data = get_access();
         foreach($data as $row){ ?>
        <tr>
           <!-- <th><?php// echo $row['sno'];?></th> -->
            <td><?php
              $Access_Number = $row['access_number'];
              if(ctype_digit($Access_Number) && strlen($Access_Number) == 10) {
                $Access_Number = substr($Access_Number, 0, 3) .'-'.
                          substr($Access_Number, 3, 3) .'-'.
                          substr($Access_Number, 6);
              }
             echo $Access_Number ?></td>
             <td><?php echo $row['city'];?></td>
             <td><?php echo $row['state'];?></td>
             <td><?php echo $row['language'];?></td>
             </tr>
         <?php }?>
       </tbody>
   </table>
           </div><!--tab 1-->
       
              <div class="tab-pane rates-view" id="2b" >
              <form id="rates-form" name="rates-form" type="post">
              <div class="row">
              <div class="col-md-12">
              
                <form id="rates-form" name="rates-form" type="post">
                    <div class="col-md-5">
                      <select id="country_select">
                                 <option >All</option>
                        <?php  do_action('get_country');
                                    $country_ = get_all_country();
                                    foreach($country_ as $name){
                                ?>
                        <option>
                        <?php  echo $name['country']; ?>
                        </option>
                        <?php }?>
                      </select>
                    </div><!--col-md-5-->
                    
                    <div class="col-md-5">
                      <select id="balance_select" >
                        <option value="5" > 5 </option>
                        <option value="10"> 10</option>
                        <option value="15"> 15</option>
                        <option value="20"> 20</option>
                      </select>
                    </div><!--col-md-5--> 
          
           <div class="col-md-2">
                          <input class="btn btn-block btn-serch-coustom " type="submit" name="" value="Search" >
                       </div><!--col-md-2-->       
          </form>
                <!--row-->
                  <div class="loading">
          <img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>">
          </div>
                  </div><!--col-md-12-->
                  </div><!--row-->
               
                   <div class="rates_all_div">
                       <table id="rates_table" class="table table-bordered" cellspacing="0" width="100%">
                       <thead class="tab-the-bg" >
                        <tr>
                         <th>Country</th>
                          <th>Description</th>
                          <th>Rates</th>
                           <!-- <th>Effective Date</th> -->
                          <th>Minutes</th>
                         </tr>
                       </thead>
                      <tbody>
      
                    <?php         do_action('rates_all');
                                $rates = get_all_rates();
                              foreach($rates as $row){
                     ?>

                           <th><?php echo $row['country'];?></th>

                           <td><?php echo $row['description'];?></td>

<td class="rates_get text-right" abbr= "<?php echo $row['rates'];?>"><?php echo  number_format($row['rates']*100, 2);?>&cent;</td>

                           <!-- <td style="display:none;"><?php echo $row['date'];?></td> -->

                           <td class="minutes" data-minute="<?php echo  number_format(1/$row['rates'],0);?>"></td>

                         </tr>

                  <?php }?>
            </tbody>
        </table>   
                      
             </div>
                 
                <!--   <div id="searched_data">
                  <div class="row" >
                    <div class="col-md-4 col-sm-4  ">
                        <div class="boxes">
                          <div class="box-cricle box-cricle1" ><img src="<?php echo WP_SITEURL; ?>/wp-content/uploads/phone.png"></div>
                          <h4>Landline</h4>
                          <div class="cent" >
                          <span style="margin: 0 2px;"> &cent; </span>
                            <span  id="city"  class="put-txt"></span>
                            <input type="hidden" id="landline"/>
                          </div>
                          <!--<div>GBP</div>--><!-- </div>
                          </div>
                       <div class="col-md-4 col-sm-4 ">
                        <div class="boxes">
                           <div class="box-cricle box-cricle2" ><img src="<?php echo WP_SITEURL; ?>/wp-content/uploads/mobile-1.png"></div>
                          <h4>Mobile</h4>
                          <div class="cent">
                          <span style="margin: 0 2px;"> &cent; </span>
                            <span id="state" class="put-txt"></span>
                            <input type="hidden" id="mobile" />
                          </div>
                          <div>GBP</div</div>
                          </div>
                      <div class="col-md-4 col-sm-4   ">
                        <div class="boxes">
                          <div class="box-cricle box-cricle3" ><img src="<?php echo WP_SITEURL; ?>/wp-content/uploads/calendar.png"></div>
                          <h4> Date</h4>
                          <div class="cent" >
                            <span id="sms" class="put-txt"></span>
                          </div>
                          <!--<div>GBP</div>put-txtsms</div>
                          </div>                      
                      </div> 
                    </div> -->
                   </div> <!--tab 1-->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    

<div class="container">
  <div class="row">
    <div class="country-slider">
      <div class="container download-app" style="background: none;">
        <h3>Our Top Destinations</h3>
      </div>
      <?php  
     echo do_shortcode('[gs_logo title="yes" posts="15" order="ASC" logo_cat="food" mode="vertical" speed="1000" inf_loop="0" ticker="1" logo_color="gray_to_def"]');
    ?>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
  $(".gs_logo_single").click(function() {
    $('html, body').animate({
        scrollTop: $("#rates-success").offset().top - 150}, 2000);

});
});
</script>
<?php get_footer(); ?>




