<?php $title = $this->crud->getInfo('system_title'); ?>
<?php $system_name = $this->crud->getInfo('system_name'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Siri Shrine PU College</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="keywords" content="esidential pu Colleges In Bangalore, Pu colleges in Bangalore, Best pu colleges in Bangalore, Residential pre university colleges in Karnataka,Pu college with integrated coaching in Bangalore,Best integrated pu college near electronic city,Pu colleges in Bangalore for science,Pu colleges in Bangalore for commerce,Best pu colleges for girls in Bangalore,Pu colleges with sports in Bangalore,Pu colleges in Bangalore with transport,Top pu colleges in Bangalore,Top ten pu colleges in Bangalore,Pu colleges near by,Pu colleges near sarjapura" >
		<meta name="author"content="Siri Shrine Trust" >
		<meta name="soft_version"content="1.0" >
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="icon" type="image/png" href="<?php echo base_url();?>public/style/loginAssets/public/style/loginAssets/images/icons/favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/vendor/animate/animate.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/vendor/css-hamburgers/hamburgers.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/vendor/animsition/css/animsition.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/vendor/select2/select2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/vendor/daterangepicker/daterangepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/css/util.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/style/loginAssets/css/main.css">
	</head>
	<body>
		<div class="limiter">
			<div class="container-login100">
				<div class="wrap-login100">
					<div class="login100-form-title" style="background-image: url(<?php echo base_url();?>public/style/loginAssets/images/bg.jpg);">
						<span class="login100-form-title-1"><h5><?php echo getEduAppGTLang('login_to_your_account');?></h5></span>
					</div>
					 <?php if($this->session->userdata('error') == '1'):?>    
						<div class="form-login-error">
							<center><div class="alert alert-danger"><?php echo getEduAppGTLang('login_error');?></div></center>
						</div>
					<?php endif;?>
					<?php if($this->session->userdata('failed') == '1'):?>
						<div class="alert alert-danger" style="text-align: center; font-weight: bold;"><?php echo getEduAppGTLang('social_error');?></div>
					<?php endif;?>
					<?php if($this->session->userdata('success_recovery') == '1'):?>
						<div class="alert alert-success" style="text-align: center; font-weight: bold;"><?php echo getEduAppGTLang('password_reset');?></div>
					<?php endif;?>
					<?php if($this->session->userdata('failedf') == '1'):?>
						<div class="alert alert-danger" style="text-align: center; font-weight: bold;"><?php echo getEduAppGTLang('social_error');?></div>
					<?php endif;?>
					<form class="login100-form validate-form" method="POST" action="<?php echo base_url();?>login/auth/">
						<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
							<span class="label-input100">Username</span>
							<input class="input100" type="text" name="username" placeholder="Enter username" required>
							<span class="focus-input100"></span>
						</div>
						<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
							<span class="label-input100">Password</span>
							<input class="input100" type="password" name="password" placeholder="Enter password" required>
							<span class="focus-input100"></span>
						</div>
						<div class="flex-sb-m w-full p-b-30">
							<div class="contact100-form-checkbox">
								<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
								<label class="label-checkbox100" for="ckb1">
									Remember me
								</label>
							</div>
							<div><a href="<?php echo base_url();?>forgot_password/"><?php echo getEduAppGTLang('forgot_my_password');?></a></div>
						</div>
						<div class="container-login100-form-btn">
							<button id="submit" type="submit" class="login100-form-btn"><?php echo getEduAppGTLang('login');?></button> 
						</div>
					</form>
				</div>
			</div>
		</div>
		<script src="<?php echo base_url();?>public/style/loginAssets/vendor/jquery/jquery-3.2.1.min.js"></script>
		<script src="<?php echo base_url();?>public/style/loginAssets/vendor/animsition/js/animsition.min.js"></script>
		<script src="<?php echo base_url();?>public/style/loginAssets/vendor/bootstrap/js/popper.js"></script>
		<script src="<?php echo base_url();?>public/style/loginAssets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>public/style/loginAssets/vendor/select2/select2.min.js"></script>
		<script src="<?php echo base_url();?>public/style/loginAssets/vendor/daterangepicker/moment.min.js"></script>
		<script src="<?php echo base_url();?>public/style/loginAssets/vendor/daterangepicker/daterangepicker.js"></script>
		<script src="<?php echo base_url();?>public/style/loginAssets/vendor/countdowntime/countdowntime.js"></script>
		<script src="<?php echo base_url();?>public/style/loginAssets/js/main.js"></script>
	</body>
</html>