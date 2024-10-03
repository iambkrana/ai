<?php $base_url= base_url(); 

$query = "SELECT login_page,default_lang FROM ai_language";
$result = $this->db->query($query);
$Sel_Lang = $result->result_array();
 
	if($Sel_Lang[0]['login_page']==''){
		$login_page_final=$Sel_Lang[0]['default_lang'];
	}else{
		$login_page_final=$Sel_Lang[0]['login_page'];
	}

	$short_id = array(); 
    foreach ($multi_lang as $cmp) { 
        $short_id[]=$cmp['ml_short'];
    } 
     $final_short= implode (",", $short_id);
?>
<!DOCTYPE html>
<html>
	<!--begin::Head-->
	<head>
		<?php $this->load->view('auth/signin-head'); ?>
	
		<link href="<?=$base_url.'/assets/layouts/auth/css/google-btn.css';?>" rel="stylesheet" type="text/css" />
		<link href="<?=$base_url.'/assets/layouts/auth/css/googleTranslate.css';?>" rel="stylesheet" type="text/css" />
		<!-- <link href="< ?php echo $base_url; ?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" /> -->
		<!-- <link href="< ?php echo $base_url; ?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
		<!-- <link href="< ?= $base_url;?>/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" /> -->
		<link href="<?= $base_url;?>/assets/layouts/layout/fonts/fonts.css" rel="stylesheet" type="text/css" />
		<link href="<?= $base_url;?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<link href="<?= $base_url;?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
		<!-- BEGIN THEME LAYOUT STYLES -->
<link href="<?php echo $base_url; ?>assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>assets/layouts/layout/css/themes/black_orange.min.css" rel="stylesheet" type="text/css" id="style_color" />
<link href="<?php echo $base_url; ?>assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>assets/layouts/layout/css/custom.css" rel="stylesheet" type="text/css" />
<!-- END THEME LAYOUT STYLES -->
<style>
		/* .select2-search {
			display: none;
		} */
		/* .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple{
			border: 1px solid #b5b5c3;
		} */
		/* .select2-container--default .select2-results__option[aria-selected=true] {
			background: #db1f48;
			color: #ffff;
		} */
		/* .select2-container--default .select2-results__options {
			padding: 0px;
		} */
		/* .select2-container--default .select2-dropdown {
			border: 1px solid #69b3ff7a;
		} */
		/* .form-group{
			width: 90%;
		} */
		/* .select2-container{
			width: 90%!important;
		} */
		/* .select2-selection__arrow:after{
			display: none;
			font-family: Ki;
		} */
		/* .select2-container--default .select2-selection--single .select2-selection__arrow b, .select2-container--default .select2-selection--multiple .select2-selection__arrow b {
			display: block;
			position: initial;
		} */
		/* .tooltip-bx{
			font-size: 12px; 
			font-family: sans-serif;
		} */
		/* .tooltip-inner {
			
			font-family: sans-serif; 
    		text-align: left;
			background-color:#db1f48;
			 color: #ffff;
		} */
		/* .tooltip.show li{
			text-align:left;
			margin-bottom: 5px;
		} */
		.alert-forget{
			display: none;
		}
		.icon-user{
			font-size:25px;
			top: 8px;
			position: relative;
		}
		#kt_body{
	top: 0px !important;
}

/* add By shital for language module : 19:01:2024 */
@media (min-width: 992px){
.login.login-1 .login-content {
    max-width: 100%;
}}
.land_div{
	float: left;
	padding: 10px;
}
.lang_select{
	width: fit-content;
    float: inline-end;
	border: 1px solid #ccc;
}
.font-weight-bolder{
	height: fit-content;}
	#lang_select {
			width: 136px;
			border-top-right-radius: 20px;
			border-bottom-right-radius: 20px;
			border-top-left-radius: 20px;
			border-bottom-left-radius: 20px;
			background: #db1f48;
			color: white;
			font-weight: bold;
			height: 40px;
		}
		.caret {
    display: inline-block;
    width: 0;
    height: 0;
    margin-left: 2px;
    vertical-align: middle;
    border-top: 4px dashed;
    border-top: 4px solid\9;
    border-right: 4px solid transparent;
    border-left: 4px solid transparent;
}
.dropdown-toggle.btn:after {
	margin-left: 0.5rem
		/*rtl:ignore*/;
	font-family: none;
	/* font-style: normal; */
	font-weight: normal;
	font-variant: normal;
	/* line-height: 1; */
	/* text-decoration: inherit; */
	/* text-rendering: optimizeLegibility; */
	/* text-transform: none; */
	-moz-osx-font-smoothing: grayscale;
	-webkit-font-smoothing: antialiased;
	font-smoothing: antialiased;
	 content: block; 
}

.dropdown-menu {
    box-shadow: 5px 5px rgba(102,102,102,.1);
    left: 0;
    /* position: absolute; */
	position: fixed !important;
    z-index: 1000;
    display: none;
	text-align: left;
    float: left;
    list-style: none;
    text-shadow: none;
    padding: 0;
	font-size: 13px !important;
	transform: translate3d(530px, 38px, 1px) !important;
    background-color: #fff;
    margin: 0px 0 0;
    border: 1px solid #eee;
    font-family: "Proxima Nova", "Helvetica Neue", Helvetica, Arial, sans-serif;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}

.dropdown-menu>li>a, .dropdown-menu>.dropdown-item {
    outline: black;
    display: -webkit-box;
    display: -ms-flexbox;
    display: list-item;
    -webkit-box-flex: revert;
    -ms-flex-positive: 1;
    flex-grow: 1;
    color: #000;
    margin: 9px;
}
.dropdown-toggle.btn:after {
	display: none !important;
}
.btn_newLang{
	letter-spacing: 1px;
    line-height: 15px;color: white !important;
    border-radius: 40px !important;
    width: auto;
	padding: 4px 5px !important;
    float: right;
	text-transform: uppercase;
    background:#db1f48 !important;
    margin: 1px 1px 0px 0px;
}
/* add By shital for language module : 19:01:2024 */
</style>

</head>
<!--end::Head-->
<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
				
				<!--begin::Content-->
				<div class="login-content flex-row-fluid d-flex flex-column position-relative overflow-hidden mx-auto">
					<!--begin::Content body-->

<!-- add By shital for language module : 19:01:2024 -->
<input type="hidden" style="width: auto;float: inline-end;" name="final_short" id="final_short" value="<?php echo $final_short; ?>" class="form-control input-sm">   

<div class="flex-column position-relative overflow-hidden land_div">				
<!-- <span  class="notranslate"><select id="sel_lang" name="sel_lang" onchange="val()" class="input-sm lanselect lang_select" placeholder="Please select">
	<option value="">Language Select</option>
		<?php foreach ($multi_lang as $cmp) { ?>
			<option value="<?= $cmp['ml_short']; ?>"><?php echo $cmp['ml_name']; ?> - <?php echo $cmp['ml_actual_text']; ?></option>
		<?php } ?>
	</select></span>	 -->
	<div class="top-menu">

		



				<ul class="nav navbar-nav pull-right notranslate">
                	<li>
						<button class="btn dropdown-toggle btn-sm btn_newLang" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="icon-globe" style="color: #fff !important;padding-right: unset;font-size: 14px;"></i> <span id="short_LN" style="font-size: 13px;"></span>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu dropdown-menu-default"  id='myid' name="sel_lang"  style="position: fixed !important;">
						<?php foreach ($multi_lang as $ln) { ?>
							<!-- <option value="<?= $ln['ml_short']; ?>"  
							<?php //echo ($ln['ml_short'] == $back_lang_final )?'selected':'';?>></option> -->


<li id="<?php echo $ln['ml_short']; ?>"><a href="#" onClick="setDay('<?php echo $ln['ml_short']; ?>');"><?php echo $ln['ml_name']; ?> - <?php echo $ln['ml_actual_text']; ?></a></li>
   

						<?php } ?>
						</ul>
                	</li>
				</ul>
				</div></div>

<!-- End By shital for language module : 19:01:2024 -->
					<div class="d-flex flex-column-fluid flex-center">

						<!--begin::prelogin-->
						<div class="login-form login-prelogin">
							<!--begin::Form-->
							<?php $attributes = ['class' => 'form','novalidate'=>"novalidate", 'id' => 'kt_login_prelogin_form'];
                            echo form_open('',$attributes); ?>
								<!--begin::Title-->
								<div class="pb-15 pt-lg-0 pt-5 text-center" >
									<img src="<?= $base_url;?>/assets/layouts/auth/media/Awarathon-Logo2020-RedBlack-Crop.png" class="max-h-55px" alt="" />
								</div>
								
								<div class="form-group ">
									<div class="alert alert-danger_pwa col-md-10" style='margin-left: 50px;border: 1px solid #db1f48;display:none'>
										<button class="close" data-close="alert"></button>
										<span class="error-msg_pwa"> </span>
									</div>
									<div><span class="col-md-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><label class="col-md-10 font-size-h8 font-weight-bolder text-dark ">Are you a Learner?</label></div>
									<div><span class="col-md-2"><i style="color: #db1f48;" class="icon-user font-black sub-title"></i></span><button type="button" id="login_type" name="login_type" class="col-md-10 form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3" onclick="give_massage()">Click here to Play</button></div>
									<!-- <div><span class="col-md-2"><i style="color: #db1f48;" class="icon-user font-black sub-title"></i></span><button type="button" id="login_type" name="login_type" class="col-md-10 form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3" onclick="window.location.href='https:/\/ai.awarathon.com/app/web.html'">Click here to Play</button></div> -->
									<!-- <div class="input-group"><span class="col-md-2 input-group-prepend"><i style="color: #ffff;background-color: #db1f48;" class="icon-user font-black sub-title input-group-text"></i></span><button type="button" id="login_type" name="login_type" class="col-md-10 form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3" onclick="window.location.href='https:/\/pwa.awarathon.com'">Click here to Play</button></div> -->
                                </div>
								<div class="form-group ">
									<div><span class="col-md-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><label class="col-md-10 font-size-h8 font-weight-bolder text-dark ">Are you Manager or Admin?</label></div>
									<div><span class="col-md-2"><i style="color: #db1f48;" class="icon-user font-black sub-title"></i></span><a href="<?= $base_url.'login' ?>" class="col-md-10 form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3" >Click here to Give Rating or Create</a></div>
                                </div>
								<!--end::Form group-->
							<?php echo form_close(); ?>
							<!--end::Form-->
						</div>
						<!--end::prelogin-->

						<!--begin::Signin-->
						<div class="login-form login-signin">
							<!--begin::Form-->
							<?php $attributes = ['class' => 'form', 'novalidate'=>"novalidate", 'id' => 'kt_login_signin_form'];
        						echo form_open('login/service',$attributes); ?>
								<!--begin::Title-->
								<div class="pb-15 pt-lg-0 pt-5 text-center" >
									<!-- <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg signin-heading">Sign in</h3> -->
									<img src="<?= $base_url;?>/assets/layouts/auth/media/Awarathon-Logo2020-RedBlack-Crop.png" class="max-h-55px" alt="" />
								</div>
								<?php if ($error!==''){ ?>
								<div class="alert alert-danger display-block" >
									<button class="close" data-close="alert"></button>
									<!-- KRISHNA --- VAPT - ENABLED MAX 3 ATTEMPT FOR LOGIN WITHIN 5 MINUTES -->
									<span><?php echo ($error == 'HTTP 429') ? 'Too many requests. Try again after 5 minutes' : $error;?></span>
								</div>
								<?php } ?>
								<!--begin::Title-->
								<!--begin::Form group-->
								<!-- <div style="margin-bottom: 1rem !important;">
										<select class="form-group form-control form-control-solid h-auto py-3 px-3 select2tp" id="login_type" name="login_type" onchange="RedirectToPlay();">
											<option value="1">Manager or Admin</option>
											<option value="2">Learner</option>
										</select>
									<a href="javascript:;" data-toggle="tooltip" data-placement="right" data-html="true" title="<span class='tooltip-bx'><li>Select <b>Manager or Admin</b> For Rating or Create.</li><li>Select <b>Learner</b> For Play.</li></span>"><i style="color: #db1f48;" class="icon-info font-black sub-title"></i>
								    </a>
								</div> -->
								<!-- added by shital : 08:01:2024 -->
								<div class="top_lang_xx">
									<input type="hidden" name="log_lang" id="log_lang" value="<?php echo $login_page_final; ?>" class="form-control input-sm">
									<div id='google_translate_element' class="dropdown-user open" style="display:none;"></div>        
<input type="hidden" name="manage_lang" id="src_box" class="form-control input-sm">
<input type="hidden" name="Final_LN" id="Final_LN" class="form-control input-sm">
														</div>
								<!-- End by shital : 08:01:2024 -->
								<div class="form-group">
									<label class="font-size-h8 font-weight-bolder text-dark">Username</label>
									<input class="form-control form-control-solid h-auto py-3 px-3" type="text" name="username" id="username" autocomplete="off" value="<?= (isset($member_username) ? $member_username : set_value('username')); ?>" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group">
									<div class="d-flex justify-content-between mt-n5 lbl-password">
										<label class="font-size-h8 font-weight-bolder text-dark pt-5">Password</label>
                                    </div>
                                    <small class="password-help">
                                        <a href="javascript:;" id="show-password" onclick="togglePassword();" class=" text-pink font-size-h8 font-weight-bolder text-hover-pink pt-4">Show Password</a>
                                        </small>
									<input class="form-control form-control-solid h-auto py-3 px-3" type="password" name="password" id="txt-password" autocomplete="off" value="<?= (isset($member_password) ? $member_password : set_value('password')); ?>"/>
								</div>
                                <!--end::Form group-->

                                <div class="form-group">
                                    <!--<input class="" type="checkbox" name="remember_me"/>
                                    <label class="font-size-h8 font-weight-bolder text-dark pt-0" style="margin:0px 0px 0px 5px;padding:0px;">Remember me</label> -->
									<label class="rememberme mt-checkbox mt-checkbox-outline">
										<input type="checkbox" id="remember" <?php echo (isset($member_username) ? 'checked': ''); ?> name="remember" value="1" /> Remember me
										<span></span>
									</label>
                                </div>

								<!--begin::Action-->
								<div class="pb-lg-0 pb-1">
									<button type="button" id="kt_login_signin_submit" class="form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3" <?= ($error == 'HTTP 429') ? "disabled" : "" ?>>Log in</button>
								</div>
                                <!--end::Action-->
								<div class="pb-5 form-group">
                                    <a href="javascript:;" class="text-pink font-size-h8 font-weight-bolder text-hover-pink pt-5" id="kt_login_forgot">Forgot Password?</a>
                                </div>
							
							<?php echo form_close(); ?>
							<!--end::Form-->
						</div>
						<!--end::Signin-->

						<!--begin::Forgot-->
						<div class="login-form login-forgot" style="padding: 15px;">
							<!--begin::Form-->
							<?php $attributes = ['class' => 'form','novalidate'=>"novalidate", 'id' => 'kt_login_forgot_form'];
                            echo form_open('login/forget_password',$attributes); ?>
								<!--begin::Title-->
								<div class="pb-15 pt-lg-0 pt-5 text-center" >
									<!-- <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg signin-heading">Sign in</h3> -->
									<img src="<?= $base_url;?>/assets/layouts/auth/media/Awarathon-Logo2020-RedBlack-Crop.png" class="max-h-55px" alt="" />
								</div>
								
								<div class="pb-5 pt-lg-0 pt-5 text-center" >
									<h3 class="font-weight-bolder text-blue-dark font-size-h4 font-size-h1-lg">Forgot Password?</h3>
									<p class="text-blue-light font-weight-bold font-size-h6">We just need your registered Email Id to send you password reset instruction</p>
								</div>
								<!--end::Title-->
								<!--begin::Form group-->
								<div class="alert alert-danger alert-forget" >
									<button class="close" data-close="alert"></button>
									<span class="error-msg"> </span>
								</div>
								<div class="alert alert-success alert-forget" >
									<button class="close" data-close="alert"></button>
									<span class="success-msg"> </span>
								</div>
								<div class="form-group">
									<label class="font-size-h8 font-weight-bolder text-dark">Email Address</label>
									<input class="form-control form-control-solid h-auto py-3 px-3" type="email" name="email" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="pb-lg-0 pb-5" >
									<button type="button" id="kt_login_forgot_submit" class="form-control btn btn-blue-dark font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3">RESET PASSWORD</button>
								</div>
								<div class="form-group text-center pt-8">
									<a href="<?= $base_url.'login' ?>" class="text-pink font-size-h6 font-weight-bolder text-hover-pink pt-5" ><< Back</a>
									<!-- <a href="javascript:;" id="kt_login_forgot_cancel" class="text-pink font-size-h6 font-weight-bolder text-hover-pink pt-5" ><< Back</a> -->
                                </div>
								<!--end::Form group-->
							<?php echo form_close(); ?>
							<!--end::Form-->
						</div>		
						<!--end::Forgot-->
						<!--begin::Reset password-->
						<div class="login-form login-reset" >
							<!--begin::Form-->
							<?php $attributes = ['class' => 'form', 'novalidate'=>"novalidate", 'id' => 'kt_login_reset_form'];
        						echo form_open('login/reset_password',$attributes); ?>
								<!--begin::Title-->
								<div class="pb-15 pt-lg-0 pt-5 text-center" >
									<!-- <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg signin-heading">Sign in</h3> -->
									<img src="<?= $base_url;?>/assets/layouts/auth/media/Awarathon-Logo2020-RedBlack-Crop.png" class="max-h-55px" alt="" />
								</div>
								<!--begin::Title-->
								<!--begin::Form group-->
								<div class="alert alert-danger alert-forget" >
									<button class="close" data-close="alert"></button>
									<span class="error-msg"> </span>
								</div>
								<div class="form-group">
									<label class="font-size-h8 font-weight-bolder text-dark">New Password</label>
									<input class="form-control form-control-solid h-auto py-3 px-3" type="password" name="new_password" id="new_password" autocomplete="off" value="" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group pb-5">
									<label class="font-size-h8 font-weight-bolder text-dark">Confirm Password</label>
									<input class="form-control form-control-solid h-auto py-3 px-3" type="password" name="confirm_password" id="confirm_password" autocomplete="off" value="" />
								</div>
								<input class="form-control" type="hidden" name="user_id" id="user_id" value="<?= isset($user_id) ? $user_id : ''; ?>" />
                                <!--end::Form group-->
								<!--begin::Action-->
								<div class="pb-lg-0 pb-1" >
									<button type="button" id="kt_login_reset_submit" class="form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3" <?= ($error == 'HTTP 429') ? "disabled" : "" ?>>OK</button>
								</div>
								<div class="form-group text-center pt-8">
									<a href="<?= $base_url ?>" class="text-pink font-size-h6 font-weight-bolder text-hover-pink pt-5" ><< Home </a>
									<!-- <a href="javascript:;" id="kt_login_reset_cancel" class="text-pink font-size-h6 font-weight-bolder text-hover-pink pt-5" ><< Home </a> -->
                                </div>
                                <!--end::Action-->
                                
							<?php echo form_close(); ?>
							<!--end::Form-->
						</div>
						<!--end::Reset password-->
						<!--begin::Changed-->
						<div class="login-form login-changed" style="padding: 15px;">
							<!--begin::Form-->
							<?php $attributes = ['class' => 'form','novalidate'=>"novalidate", 'id' => 'kt_login_changed_form'];
                            echo form_open('',$attributes); ?>
								<!--begin::Title-->
								<div class="pb-15 pt-lg-0 pt-5 text-center" >
									<!-- <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg signin-heading">Sign in</h3> -->
									<img src="<?= $base_url;?>/assets/layouts/auth/media/Awarathon-Logo2020-RedBlack-Crop.png" class="max-h-55px" alt="" />
								</div>
								<div class="pb-5 pt-lg-0 pt-5 text-center" >
									<p class="text-blue-light font-weight-bold font-size-h6">Your password has been changed successfully!</p>
								</div>
								<!--end::Title-->
								
								<div class="form-group text-center">
									<a href="<?= $base_url.'login' ?>" class="form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3" >Click here to login</a>
									<!-- <a href="javascript:;" id="kt_login_changed" class="form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3" >Click here to login</a> -->
                                </div>
								<!--end::Form group-->
							<?php echo form_close(); ?>
							<!--end::Form-->
						</div>		
						<!--end::Changed-->
					</div>
					<!--end::Content body-->
					<!--begin::Content footer-->
					<div class="d-flex justify-content-lg-center justify-content-center align-items-end py-7 py-lg-0">
                        <div class="copyright">
							© <?= date('Y') ?>. Awarathon Awareness Initiatives Pvt. Ltd. All rights reserved.
							<br/>
							<a tabindex="0" target="_blank" href="https://awarathon.com/privacy-policy/" class="privacy-policy-text text-pink">Privacy Policy <span><i class="fa fas fa-external-link-alt privacy-policy-icon"></i></span></a>
                        </div>
					</div>
					<!--end::Content footer-->
				</div>
				<!--end::Content-->

				<!--begin::Aside-->
				<div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #004369;">
					<!--begin::Aside Top-->
					<div class="text-center d-flex flex-column-auto flex-column dp-pt-lg-20 pt-15">
						
						<div class="login-aside-left-box">
							<!--begin::Aside title-->
							<h3 class="banner-heading">Pitch Perfect. Always.</h3>
							<h5 class="banner-sub-heading">Our AI-enabled, video roleplay platform ensures <br/>your teams hit the right notes. Consistently.</h5>
							<!--end::Aside title-->

							<!--begin::Know More-->
							<div class="pb-lg-0 pb-5">
								<a target="_blank" href="https://awarathon.com"><button type="button" class="form-control btn btn-pink font-weight-bolder font-size-h6 px-8 py-3 my-3 mr-3 btn-know-more">KNOW MORE</button></a>
							</div>
							<!--end::Know More-->
						</div>
						
					</div>
					<!--end::Aside Top-->
					
					
				</div>
				<!--begin::Aside-->
			</div>
			<!--end::Login-->
		</div>
		<!--end::Main-->
		<?php 
			$data['is_change'] = isset($is_sign) ? $is_sign : '';
			$this->load->view('auth/signin-js',$data); 
		?>	
		
		<!-- Add By shital for language module : 19:01:2024 -->
		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

<script type="text/javascript">
	 function give_massage()
    {
        $('.alert-danger_pwa').show();
        $('.error-msg_pwa').html("<p style='color:#db1f48'>Dear Users,<br><br>If you are seeing this message means you have clicked the wrong link. Request you to contact your System Admin to get the correct details.<br><br>Regards,<br>Awarathon Team</p>");
    }
function googleTranslateElementInit() {
    var Blang = $('#log_lang').val();
	var mng = $('#src_box').val(); //$('#sel_lang').val();
			var FNlang = $('#final_short').val();

	if(mng!=''){
		var fnl = mng;
	}else{
		var fnl =Blang;
	}
	$("html").removeClass("translated-ltr");
	$('html').removeClass('notranslate');

	$('#Final_LN').val(fnl);
	$('.btn_newLang').val(fnl);
	$('#short_LN').text(fnl);    
	
	new google.translate.TranslateElement({
        pageLanguage: 'en',
        //autodisplay:true,
		autoDisplay: false,
        includedLanguages: FNlang, //'gu,en,hi,mr',
		gaTrack: true
    }, 'google_translate_element');
    setTimeout(function() {
        // Set the default language 
        var selectElement = document.querySelector('#google_translate_element select');
        selectElement.value = fnl; //Blang; //'gu';
        selectElement.dispatchEvent(new Event('change'));
      }, 500);
	  $("html").removeClass("translated-ltr");
	  console.log(mng);
}

function val(){
    d = document.getElementById("sel_lang").value;
	$('#src_box').val(d);
	googleTranslateElementInit();
    }
	

function setDay($day)
{
	//alert($day);
   // console.log($day);

		$('#src_box').val($day);
			googleTranslateElementInit();
}
$(document).ready(function(){          
			var Blang = $('#log_lang').val();
			
			$('#short_LN').text(Blang);$("html").removeClass("translated-ltr");           
			googleTranslateElementInit();           
		});

$(function() {
    $("html").removeClass("translated-ltr");           
        $('html').addClass('notranslate');
});
</script>
<!-- End By shital for language module : 19:01:2024 -->
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

	</body>

	<!--end::Body-->
	
</html>