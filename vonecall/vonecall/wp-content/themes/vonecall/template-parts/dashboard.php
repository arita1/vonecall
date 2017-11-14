<?php
/* Template Name: Dashboard  */
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
$_SESSION['account_info']->last_usage;
/*** to show last used date ************/
if(!empty($_SESSION['account_info']->last_usage) ){
$last_used = date('d/m/Y' , strtotime($_SESSION['account_info']->last_usage));
}else if ($_SESSION['account_info']->last_usage == null){
	$last_used = 'N/A';
}
 $last_login = date('d/m/Y' , strtotime($_SESSION['last_login']));
 if($last_login == ''){
 	$last_login = 'N/A';
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
			        <li><div class="user-profile text-center">
				<span><img src="<?php echo $user_image ; ?>"></span>
				<span><p> <?php echo ucfirst($_SESSION['account_info']->firstname); ?></p> </span>
			</div> </li>
						  <?php wp_nav_menu( array('menu'=>'dashboard_menu')); ?>
					</ul>
			</div>
			<div id="snackbar">Login Successfully..</div>
				</nav>
				</div>

		<div class="deshbord-menu">
			<div class="nav_menu">
            <nav>
              <div>
                <button id="menu_toggle" class="deshbord-toggle"><i class="fa fa-bars"></i></button>
               
              </div>
               
          </nav></div>
          </div>

			<div class="right_col" >
			<div class="col-md-12" style="clear: both;">
			<div class="desh-bg">
			<div class="row">
				<div class="col-md-12 " >
				<div class="desh-title" >
				<div class="user-img"> 
 						<img src="<?php echo $user_image ; ?>" >
				</div>
					<h5 > Welcome to Your Dashboard  </h5>
					<span> <?php echo $Name ?> </span>
				</div>
			   </div>
			</div>
			<div class=" user-detal">
			<div class="row">
				<div class="col-md-6" >
					<h4><span> Current Account Balance : </span> $ <?php echo $_SESSION['account_info']->balance; ?> </h4>
				</div>

				<div class="col-md-6" >
					<h4><span> Last Login Date : </span><?php echo $last_login; ?>  </h4>
				</div>

			</div>			
			<div class="row">
				<div class="col-md-6" >
					<h4><span> Last Recharge Date : </span>  15/7/2017 </h4>
				</div>
				<div class="col-md-6" >
					<h4><span> Quick Recharge : </span>   </h4>
				</div>
			</div>			
			<div class="row">
				<div class="col-md-6" >
					<h4><span> Last Used Date : </span> <?php echo $last_used; ?></h4>
				</div>
			</div>	
			</div>
     		</div>
        
                <div class="desh-bg">
            <div class="row">
                <div class="col-md-6">
                    <h3 style="font-weight: 800;">Download Our App and Get $1 Free</h3>

                </div>

            <div class="col-md-6" style="display:flex; flex-wrap:wrap; justify-content:center;"> 
                    <a style="margin:10px;" href="javascript:;" class="forget google-play img-responsive" data-toggle="modal" data-target="confirmation-modal"><img src="http://www.vonecall.com/vonecall/wp-content/uploads/android.jpg" width="150px"></a>
                    <a style="margin:10px;" href="javascript:;" class="forget app-store img-responsive" data-toggle="modal" data-target="confirmation-modal"><img src="http://www.vonecall.com/vonecall/wp-content/uploads/i-phone.jpg" width="150px"></a></div>
                                </div>
                </div> 
			
     		</div>
     		</div>
     		</div>
		</div>
	</div>


<?php
//echo"session".$_SESSION['portaoneSession'];
get_footer();
}else{
	wp_redirect(WP_SITEURL);
}
?>
