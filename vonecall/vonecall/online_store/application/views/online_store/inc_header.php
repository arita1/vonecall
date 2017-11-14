<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Vonecall </title>
<link rel="icon" href="images/titlelogo.PNG" type="image/PNG" />
<!-- css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="css/jquery.bxslider.css" rel="stylesheet" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" >
<meta http-equiv="X-UA-Compatible" content="IE=8;FF=3;OtherUA=4" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<base/>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/gs.css" type="text/css" media="screen" />
<base  />
<!-- JS -->
<!--  CSS -->
<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="ie7-and-down.css" />
<![endif]-->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<!--  -->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/StyleT23860.css?v=1" type="text/css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/screen/system.css" type="text/css"/>
<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script> -->
<!--[if IE]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
<![endif]-->
</head>
<body class="">
<div class="wraper">
	<header class="main-header">
	  <nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
			  <a class="navbar-brand" href="#">
				<div style="width:234px; height:99px; position: relative; " class="logoContainer">
					<div class="logo"></div>
				</div>
			  </a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
			  <div class="top-a">
			  	<?php if($this->session->userdata('customerID')==""){ ?>
				<div width="50%" align="right">
					<a href="<?php echo base_url();?>vonecall-register" class="btn btn-xs btn-info">
                            Register
                    </a>
					<a href="<?php echo base_url() ?>vonecall-user-login" class="btn btn-xs btn-info">Login</a>
					<!--<a href="#myModal" data-toggle="modal" class="btn btn-xs btn-info" id="myLoginBtn">
                            Login
                    </a>-->
					
					<!-- <img id="countryImg" src="https://www.gtalk.us/pinless/flags/us.gif" align="absmiddle">
						<font style="font-weight:bold">Login as:</font> &nbsp;	 
					<select id="login_select" name="login_select" onchange="selectUser()">				
						<option selected="selected" value="0">Choose</option>
						<option value="1">Customer</option>
						<option value="2">Store</option>
						<option value="3">Distribtutor</option>
					</select>-->
				</div>
				<?php }else{?>
					<div width="50%" align="right">
					<a href="<?php echo base_url() ?>vonecall-user-logout" class="btn btn-danger">Logout</a>
					</div>
				<?php } ?>
			  </div>
			  <div class="top-b">
					<div class="menuDiv round">
						<ul class="menu nav navbar-nav">
							<li><a href="<?php echo base_url();?>vonecall-store69" class="<?php if($this->uri->segment(1)=='vonecall-store'){ echo 'active'; } ?>">Home</a></li>
							<li><a href="<?php echo base_url();?>vonecall-about-us">About</a></li>
							<li><a href="<?php echo base_url();?>vonecall-services" class="<?php if($this->uri->segment(1)=='vonecall-services'){ echo 'active'; } ?>">Services</a></li>
							<li><a href="<?php echo base_url();?>vonecall-rates" class="<?php if($this->uri->segment(1)=='vonecall-rates'){ echo 'active'; } ?>">Rates</a></li>
							<li><a href="<?php echo base_url();?>vonecall-access-numbers" class="<?php if($this->uri->segment(1)=='vonecall-access-numbers'){ echo 'active'; } ?>">Access Numbers</a></li>
							<li><a href="<?php echo base_url();?>vonecall-how-it-works" class="<?php if($this->uri->segment(1)=='vonecall-how-it-works'){ echo 'active'; } ?>">How It Works</a></li>
							<!--<li><a href="<?php //echo base_url();?>vonecall-faq" class="<?php if($this->uri->segment(1)=='vonecall-faq'){ echo 'active'; } ?>">FAQ</a></li>-->
							<?php if($this->session->userdata('customerID')!="" && $this->session->userdata('email')!=""){ ?>
							<li class=""><a href="<?php echo base_url();?>vonecall-my-account" class="<?php if($this->uri->segment(1)=='vonecall-my-account'){ echo 'active'; } ?>">My Account</a></li>
						   <?php  } ?>
						</ul>
						<br class="clear">	
					</div>
			  </div>
			  <div class="top-c">
				<div class="TopNewsTicker">
		
					<marquee direction="left" onmouseout="this.setAttribute('scrollamount', 3, 0);" onmousemove="this.setAttribute('scrollamount', 0, 0);" loop="-1" scrolldelay="0" scrollamount="3" behavior="scroll"> 
						Vonecall<sup>&reg;</sup> <?php if(isset($headline_message) && $headline_message!="") { echo $headline_message; }?></marquee>
				</div>
				<div class="callcenternumber"><img src="<?php echo base_url(); ?>images/icon_phone.png"><a href="<?php echo base_url();?>vonecall-contact-us" class="<?php if($this->uri->segment(1)=='vonecall-contact-us'){ echo 'active'; } ?>">Contacts</a></div>
			  </div>
			  
			</div><!--/.nav-collapse -->
		</div>
	  </nav>
	</header>
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#33d1ff ">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> × </button>
                    <h4 class="modal-title">Login with vonecall</h4>
                </div>
                <div class="modal-body">
				<!--task  message start-->  
				<span style="color:red">  
				<div id="errorMessage"></div>
				</span>
				
				<!--task  message start-->  
				
				<span style="color:green; margin-bottom: 5px;">  
				<div id="messageOnLoinPopup"></div>
				</span>
				<!--task  message end-->
							
				<!--task  message end-->	
                    <form role="form" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input name="email" type="text" class="form-control" id="login_email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input name="password"type="password" class="form-control" id="login_password" placeholder="Password">
                        </div>
                        <input id="mylogin" type="submit" class="btn btn-primary" type="submit" value="Submit"/>
                    </form>
                </div>
            </div>
        </div>
    </div>