<?php
/* Template Name: Recharge */
get_header();
if(!empty($_SESSION['account_info'])){
get_header();
 $phone = $_SESSION['phone'];
 $data   = getCustomerByPhone($phone);
 /***to show user image or default image ****/
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
$customer_id = $_SESSION['customerID'];

$card_data = getSavedCardByCustomerID($customer_id);


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
      <div class="right_col" style="margin-left: 230px; transition: 0.5s;">
        <div class="col-md-12" style="clear: both;">
          <div class="desh-bg">
            <div class="row">
              <div class="col-md-12">
                <div class="title">
                  <h4>debit/credit Card </h4>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8 col-md-offset-2">
                <div class="tab-content">
                  <div class="loading" style="display: none;"> <img src="../wp-content/uploads/loader.gif"> </div>
                  <div class="row">
                    <div class="col-md-12 text-center ">
                      <div> <img  style="width: 110px; " src="../wp-content/uploads/wallet.png">
                        <h2><strong style="color:#000;"> Balance : <i style=" font-size:24px; " class="fa  fa-usd" aria-hidden="true"></i> <?php echo $_SESSION['account_info']->balance; ?></strong></h2>
                      </div>
                    </div>
                  </div>
                  <br>
                  <form id="payment-method" name="update_profile" method="post" enctype="multipart/enctype" autocomplete="off">
                    <div class="persnal-detail">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="sel1"> Amount</label>
                            <span class="star">*</span> <br>
                            <select class="form-control" name="amount" id="sel1">
                              <option value="0" selected="selected">-- Select --</option>
                              <option value="10">$ 10</option>
                              <option value="20">$ 20</option>
                              <option value="40">$ 40</option>
                              <option value="60">$ 60</option>
                              <option value="100">$ 100</option>
                            </select>
                            <img style="    position: absolute; height: 25px; top: 28px;  right: 40px;" src="../wp-content/uploads/select-amt.png"> </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="sel1"> Use Previous Credit Card</label>
                            <span class="star"></span> <br>
                            <select class="form-control" id="select-card" name="saved_card">
                            <?php if(empty($card_data)){ ?>
                                <option value="0" selected="selected">-- NA --</option>
                            <?php  }else{ ?>

                             <option value="0" selected="selected">-- Select --</option>
                            <?php foreach($card_data as $card_extract){?>
                             
                              <option value="<?php echo $card_extract['sa_card_id'] ;?>"><?php echo $card_extract['sa_card_type'] . str_repeat('X', strlen($card_extract['sa_card_number']) - 10) . substr($card_extract['sa_card_number'], -4);?></option>
                              <?php } }?>
                            </select>
                            <img style="    position: absolute; height: 25px; top: 28px;  right: 40px;" src="../wp-content/uploads/credit-cart.png"> </div>
                        </div>
                      </div>
                    </div>
                    <div id="hide-card-detail">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>name on card</label>
                            <span class="star">*</span> <br>
                            <input  id="card-name" name="accountName" class="form-control" placeholder=" " autocomplete = "off" type="text" value="" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>card Number</label>
                            <span class="star">*</span> <br>
                            <input name="cardNumber" id="card-number" class="form-control" placeholder="" type="text" value="" required>
                            <img style="    position: absolute; height: 30px; top: 28px;  right: 12px;" src="../wp-content/uploads/billing.jpg"> </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>expiry date</label>
                                <span class="star">*</span> <br>
                                <input name="exp_date" autocomplete = "off" id="expiration-date" class="form-control " placeholder="MM-YYYY " type="text" value="" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>cvv</label>
                                <span class="star">*</span> <br>
                                <input name="cvv" id="cvv" autocomplete = "off" class="form-control" placeholder="" type="text" maxlength="3" value="" required>
                                <img id="cvv_question" style="position: absolute; height: 30px; top: 28px;  right: 12px;" src="../wp-content/uploads/qm.jpg"> </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                              <div class="row">
                                  <div class="col-md-12">
                              <div class="form-group">
                                  <img id="cvv_info" src="../wp-content/uploads/cvv_info.png" style="display:none; position: absolute; top:-105px; left:-8px;"> </div>
                              </div>
                              </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="row">
                              <div class="col-md-6">
                          <div class="form-group">
                            <input type="checkbox" id="checkbox-1-4" name="future_save" value="" class="ui-checkbox green-tick no-border" />
                            <label for="checkbox-1-4">Save this card for future use</label>
                          </div>
                        </div>
                          </div>
                      </div>
                  </div>
                    <div class="row">
                      <div class="col-md-11">
                        <div class="form-group">
                          <label> Terms of Agreement </label>
                          <br>
                          <p class="more"> By Completing this form, you are authorizing us to charge the payment amount above against the payment method you selected. You also agree that the information you've provided is correct and that you are an authorized party on the account.</p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-2">
                            <div class="form-group" style=" margin-bottom: 5px;" >
                              <input type="checkbox" id="checkbox-1-5" class="ui-checkbox green-tick no-border" />
                              <label for="checkbox-1-5"> I Agree.</label>
                            </div>
                          </div>
                       <!--   <div class="col-md-3">
                            <div class="form-group" style=" margin-bottom: 5px;" >
                              <input type="checkbox" id="checkbox-1-6" class="ui-checkbox green-tick no-border" />
                              <label for="checkbox-1-6"> don't Agree.</label>
                            </div>
                          </div> -->
                        </div>
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="form-group">
                         <input type="hidden" name="action" value="do_recharge"/>
                        <button value="" class="hvr-shutter-out-horizontal" type="button" > Cancel </button>
                        <button value="" class="hvr-shutter-out-horizontal" type="submit" name="submit"> Recharge  </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    /* For showing CVV detailss hide show Badal */
    $(document).ready(function(){
        $('#cvv_question').mouseover(function(){
            $('#cvv_info').show();
        });
        $('#cvv_question').mouseout(function(){
            $('#cvv_info').hide();
        });
    });
    
    </script>

<?php
get_footer();
}else{
  wp_redirect(WP_SITEURL);
}
?>