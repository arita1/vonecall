<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Vonecall </title>
<link rel="icon" href="images/titlelogo.png" type="image/PNG" />
<!-- css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="css/jquery.bxslider.css" rel="stylesheet" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" >

<base/>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/gs.css" type="text/css" media="screen" />
<base  />
<!-- JS -->
<!--  CSS -->

<!--  -->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/StyleT23860.css?v=1" type="text/css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css" type="text/css"/>
</head>
<body class="">
<div class="wraper">
	<header>
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
				<div width="50%" align="right">
					<font style="font-weight:bold">Country:</font> &nbsp;
					<!-- <img id="countryImg" src="https://www.gtalk.us/pinless/flags/us.gif" align="absmiddle">	 -->
					<select id="country_Code" name="country_Code">				
					<option selected="selected" value="US">USA</option><option value="CA">Canada</option><option value="AU">Australia</option><option value="BE">Belgium</option><option value="FR">France</option><option value="DE">Germany</option><option value="IT">Italy</option><option value="SA">South Africa</option><option value="ES">Spain</option><option value="SW">Sweden</option><option value="UK">UK</option>		</select>
				</div>
			  </div>
			  <div class="top-b">
					<div class="menuDiv round">
						<ul class="menu nav navbar-nav">
							<li><a href="<?php echo base_url();?>vonecall-store69" class="active">Home</a></li>
							<li><a href="<?php echo base_url();?>">About</a></li>
							<li><a href="<?php echo base_url();?>vonecall-services">Services</a></li>
							<li><a href="<?php echo base_url();?>vonecall-rates">Rates</a></li>
							<li><a href="<?php echo base_url();?>vonecall-access-numbers">Access Numbers</a></li>
							<li><a href="<?php echo base_url();?>vonecall-how-it-works">How It Works</a></li>
							<li><a href="<?php echo base_url();?>vonecall-faq">FAQ</a></li>
							<li class="right"><a href="user/Contact.html">Contacts</a></li>
						</ul>
						<br class="clear">	
					</div>
			  </div>
			  <div class="top-c">
				<div class="TopNewsTicker">
		
					<marquee direction="left" onmouseout="this.setAttribute('scrollamount', 3, 0);" onmousemove="this.setAttribute('scrollamount', 0, 0);" loop="-1" scrolldelay="0" scrollamount="3" behavior="scroll"> 
						Vonecall<sup>&reg;</sup> <?php if(isset($headline_message) && $headline_message!="") { echo $headline_message; }?></marquee>
				</div>
				<div class="callcenternumber"><img src="images/icon_phone.png"> 855-241-0007</div>
			  </div>
			  
			</div><!--/.nav-collapse -->
		</div>
	  </nav>
	</header>