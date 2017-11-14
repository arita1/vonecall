<?php
/* Template Name: customer-login */
get_header();
?>


<section id="login">
    <div class="container">
        <div class="row">
    	    <div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 login-bg">        	    
                <h1> <img src="<?php echo WP_SITEURL; ?>/wp-content/uploads/customer-page.png"><br><br><span style="color:#3f8ed9; " >Customer</span>  </h1>
                <form role="form" action="javascript:;" method="post" id="login-form" autocomplete="off">
                    <div class="form-group">
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email or Username" required >
                    </div>
                    <div class="form-group">
                        <label for="key" class="sr-only">Password</label>
                        <input type="password" name="key" id="key" class="form-control" placeholder="Password" required >
                    </div>
                    <div class="checkbox">
                        <span class="character-checkbox"  onclick="showPassword()"></span>
                        <span class="label">Show password</span>
                   
                     <!--  <div class="login-cus-stor"> <button class="active"> Customer </button> </div> 
                      <div class="login-cus-stor" > <button> Store </button> </div> -->
                    </div>

                    <input type="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Log in">
                </form>
                <a href="javascript:;" class="forget" data-toggle="modal" data-target=".forget-modal">Forgot your password?</a>
                <hr/>            	   
    		</div> <!-- /.col-xs-12 -->
    	</div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<div class="modal fade forget-modal" tabindex="-1" role="dialog" aria-labelledby="myForgetModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span style="font-size: 36px; color: #fff; " aria-hidden="true">×</span>
					<span class="sr-only">Close</span>
				</button>
				<h2 class="modal-title text-center">Fogot Password</h2>
			</div>
			<div class="modal-body">
			<div class="form-group">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Registered Email *" required="">
                    </div>
            <div class="form-group">
                        <input type="email" name="email" id="email" class="form-control" placeholder=" Registered Phone Number *" required="">
                    </div>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-forgot" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn active btn-forgot" data-dismiss="modal" >Update</button>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->



<?php get_footer(); ?>
