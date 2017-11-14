<?php
/* Template Name: report */
if(!empty($_SESSION['account_info'])){
get_header();
  $phone = $_SESSION['phone'];
 $data   = getCustomerByPhone($phone);
 $p_image = $data[0]->customer_image;

if($p_image == Null  || empty($p_image)){

 $user_image = '../wp-content/uploads/user-defolt.png';

}else{

 $user_image = '../wp-content/uploads/'.$p_image;

}
 
   if(empty($_SESSION['account_info']->firstname)){
    $Name = $phone;
   }else{
    $Name = ucfirst($_SESSION['account_info']->firstname);
   }
?>

<div id="desh-bord">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 left_col" >
        <nav id="left-nav"  >
        <div class="scrollbar" id="style-5">
          <ul>
            <li>
              <div class="user-profile text-center"> <span><img src="<?php echo $user_image ; ?>" ></span> <span>
                <p> <?php echo $Name; ?></p>
                </span> </div>
            </li>
            <?php wp_nav_menu( array('menu'=>'dashboard_menu')); ?>
          </ul>
     </div>
        </nav>
      </div>
      <div class="deshbord-menu">
        <div class="nav_menu">
          <nav>
            <div>
              <button id="menu_toggle" class="deshbord-toggle"><i class="fa fa-bars"></i></button>
            </div>
          </nav>
        </div>
      </div>
      <div class="right_col" >
        <div class="col-md-12" style="clear: both;">
          <div class="desh-bg">
            <div class="row">
              <div class="col-md-12">
                <div class="title">
                  <h4 >REPORTS </h4>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 ">
				<div id="rates-success">
  <div class="rates-success">
          <div id="exTab3">
            <ul  class="nav nav-pills " style="width: 100%;" >
              <li class="active" id="access"> <a  href="#1b" data-toggle="tab">Calling History</a> </li>
              <li id="rates"><a href="#2b" data-toggle="tab">Transaction History</a> </li>
            </ul>
            <div class="tab-content clearfix">
              <div class="tab-pane active" id="1b">
        <div class="table-responsive">
       <table id="access-numbers" class="table table-bordered" cellspacing="0" width="100%">
       <thead class="tab-the-bg" >
           <tr>
               <th>#</th>
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
               <th><?php echo $row['sno'];?></th>
             <td><?php echo $row['access_number'];?></td>
             <td><?php echo $row['city'];?></td>
             <td><?php echo $row['state'];?></td>
             <td><?php echo $row['language'];?></td>
             </tr>
         <?php }?>
       </tbody>
   </table>
</div>
           </div><!--tab 1-->
       
              <div class="tab-pane rates-view" id="2b" >
              <div class="row">
              <div class="col-md-12">
              
                          
                            <div class="col-md-6" >
                            <select id="country_select">
                              
                               <option selected hidden >Afghanistan</option>
                                 <option >All</option>
                              <?php  do_action('get_country');
                                    $country_ = get_all_country();
                                    foreach($country_ as $name){
                                ?>
                                    <option><?php  echo $name['country']; ?></option>
                              <?php }?>
                          </select>
                      </div><!--col-md-4-->
                        <div class="col-md-6">
                              <select id="balance_select" >
                               <option value="5" >    5 </option>
                               <option value="10">    10</option>
                                <option value="15">   15</option>
                                 <option value="20">  20</option>

                              </select>
                            </div><!--col-md-4-->
           
                  </div><!--col-md-12-->
                  </div><!--row-->
                   <div class="rates_all_div">
		    <div class="table-responsive">
                       <table id="rates_table" class="table table-bordered" cellspacing="0" width="100%">
                       <thead class="tab-the-bg" >
                        <tr>
                         <th>Country</th>
                          <th>Description</th>
                          <th>Rates</th>
                           <th>Effective Date</th>
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

                           <td><?php echo $row['date'];?></td>

                           <td class="minutes" data-minute="<?php echo  number_format(1/$row['rates'],0);?>"></td>

                         </tr>

                  <?php }?>
            </tbody>
        </table>   
           </div>            
             </div>
                 
                  <div id="searched_data">
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
                          <!--<div>GBP</div>--></div>
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
                          <!--<div>GBP</div--></div>
                          </div>
                      <div class="col-md-4 col-sm-4   ">
                        <div class="boxes">
                          <div class="box-cricle box-cricle3" ><img src="<?php echo WP_SITEURL; ?>/wp-content/uploads/calendar.png"></div>
                          <h4> Date</h4>
                          <div class="cent" >
                            <span id="sms" class="put-txt"></span>
                          </div>
                          <!--<div>GBP</div>put-txtsms--></div>
                          </div>                      
                      </div>
                    </div>
                   </div> <!--tab 1-->
                  </div>
                </div>
        </div>
      </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>

<?php get_footer();
}else{
  wp_redirect(WP_SITEURL);
} ?>