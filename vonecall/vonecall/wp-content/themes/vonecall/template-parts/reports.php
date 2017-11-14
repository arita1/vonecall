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
                          <div class=""> <br>
                            <?php  $time = strtotime(date('Y-m-d'));
              $final = date("Y-m-d", strtotime("-1 month", $time));
              ?>
                            <div class="row">
                              <div class="col-md-12">
                                <form id="get_call_history" name="get_call_history" method="post" >
                                  <div class="col-md-2 col-sm-2">
                                    <h4 class="table-label2">From Date</h4>
                                  </div>
                                  <div class="col-md-3 col-sm-3">
                                    <input style="width: 100%;" type="text" id="from_date" name="from_date" class="datepicker call_from_date" />
                                  </div>
                                  <div class="col-md-2 col-sm-2">
                                    <h4 class="table-label2"> To Date </h4>
                                  </div>
                                  <div class="col-md-3 col-sm-3">
                                    <input style="width: 100%;" type="text" name="to_date" id="to_date" class="datepicker call_to_date"/>
                                    <input  type="hidden" name="action" value="get_calling_history" />
                                  </div>
                                  <div class="col-md-2 col-sm-2" ">
                                    <input type="submit" class="hvr-shutter-out-horizontal" value="SUBMIT"/>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <br>
                            <div class="loading"> <img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>"> </div>
                            <script type="text/javascript">
                              $(document).ready(function(){
                                $('#calling_history_wrapper').attr('class','col-sm-12 col-md-12 text-left')
                              });
                            </script>
                            <table id="calling_history" class="table table-bordered" cellspacing="0" width="100%">
                              <thead class="tab-the-bg" >
                                <tr>
                                  <th>#</th>
                                  <th>Called To</th>
                                  <th>Connect Time</th>
                                  <th>Call Duration</th>
                                  <th>Charged Amount($)</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <!--tab 1-->
                        
                        <div class="tab-pane rates-view" id="2b" >
                          <div class=""> <br>
                            <div class="row">
                              <div class="col-md-12">
                                <form id="get_transact_history" name="get_transact_history" method="post" >
                                  <div class="col-md-2 col-sm-2">
                                    <h4 class="table-label2">From Date</h4>
                                  </div>
                                  <div class="col-md-3 col-sm-3">
                                    <input style="width: 100%;" type="text"  id="from_date" name="from_date" class="datepicker call_from_date"/>
                                  </div>
                                  <div class="col-md-2 col-sm-2">
                                    <h4 class="table-label2">To Date</h4>
                                  </div>
                                  <div class="col-md-3 col-sm-3">
                                    <input style="width: 100%;" type="text" id="to_date" name="to_date" class="datepicker call_to_date"/>
                                    <input type="hidden" name="action" value="get_transaction_history" />
                                  </div>
                                  <div class="col-sm-2 col-sm-2" >
                                    <input type="submit" class="hvr-shutter-out-horizontal" value="SUBMIT"/>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <br>
                            <div class="loading"> <img src="<?php echo WP_SITEURL.'/wp-content/uploads/loader.gif'?>"> </div>
                            <table id="trans_history" class="table table-bordered" cellspacing="0" width="100%">
                              <thead class="tab-the-bg" >
                                <tr>
                                  <th>#</th>
                                  <th>Payment Type</th>
                                  <th>(Recharge / Refund) Amount ($)</th>
                                  <th style="text-align: left;">(Recharge / Refund) On</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <!--tab 1--> 
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
