<?php
defined('BASEPATH') or exit('No direct script access allowed');
$asset_url = $this->config->item('assets_url');
$acces_management = $this->session->userdata('awarathon_session');
$userID = $acces_management['user_id'];
$roleID = $acces_management['role'];
$avatar = $acces_management['avatar'];
$Compnay_Name = $acces_management['company_name'];
// $admin_notification = $this->session->userdata('admin_notification');

// $UserSegment = strtolower($this->uri->segment(1));
//added By shital for language module : 19:01:2024
$Sel_Lang = CheckLanguageRights($acces_management);
$language = SelectLanguageBox($acces_management);
$site_lang = $this->session->userdata('site_lang');

if ($acces_management['login_type'] == 1) {
    if ($site_lang != '') {
        $back_lang_final = $site_lang;
    } else {
        if ($Sel_Lang[0]['backend_page'] == '') {
            $back_lang_final = $Sel_Lang[0]['default_lang']; // 'en';
        } else {
            $back_lang_final = $Sel_Lang[0]['backend_page'];
        }
    }
} else {
    if ($site_lang == '') {
        $back_lang_final = $Sel_Lang[0]['backend_page'];
    } else {
        $back_lang_final = $site_lang;
    }
}

$short_id = array();
foreach ($language as $cmp) {
    $short_id[] = $cmp['ml_short'];
}
$final_short = implode(",", $short_id);
?>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<link href="<?= $asset_url . '/assets/layouts/auth/css/googleTranslate.css'; ?>" rel="stylesheet" type="text/css" />
<style>
    .btn_newLang{
	letter-spacing: 1px;
    line-height: 15px !important;
    color: white !important;
    border-radius: 40px !important;
    width: auto;
    float: left;
	text-transform: uppercase;
    background:#db1f48 !important;
    margin: 10px 7px 0px 0px;
}
.dropdown-menu>li>a{
    outline: black;
    display: -webkit-box;
    display: -ms-flexbox;
    display: list-item;
    -webkit-box-flex: revert;
    -ms-flex-positive: 1;
    flex-grow: 1;
    color: #000;
    margin: 0px !important;
}
.dropdown-toggle.btn:after {
	display: none !important;
}
.page-header.navbar .top-menu .navbar-nav {
 
 margin-right: 78px !important; 
}
.zsiq_flt_rel {
 vertical-align: top !important;
}
.zsiq_theme1 .zsiq_flt_rel {
 /* position: absolute; */
 /* padding: revert; */
 width: 52px !important;
 height: 37px !important;
 border-radius: 11% !important;
}
.zsiq_theme1 .siqicon:before {
 font-size: 22px !important;
 line-height: 35px !important;
}
</style>
<!-- End By shital for language module : 19:01:2024 -->

<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner ">
        <div class="page-logo">
            <a href="<?php echo site_url("dashboard"); ?>">
                <!--  <img src="https://pitchperfect.awarathon.com/assets/layouts/layout/img/logo_pitchperfect.png" alt="logo" style="filter: brightness(250%);" class="logo-default">-->
                <!-- <img src="<?php echo $asset_url; ?>assets/layouts/layout/img/logo.png" alt="logo" class="logo-default" /> </a>	-->
                <!-- <img src="<?php echo $asset_url; ?>assets/layouts/layout/img/Awarathon-logo-RedWhite.png" alt="logo" class="logo-default" /> </a> -->
                <img src="<?php echo $asset_url; ?>assets/layouts/layout/img/Awarathon-Logo-RedGrey.png" alt="logo" class="logo-default" />
                <!-- <img src="https://alkem.awarathon.com/assets/layouts/layout/img/alkem.jpg" alt="logo" class="logo-default" /> -->
                <!-- <img src="https://pitchperfect.awarathon.com/assets/layouts/layout/img/logo_pitchperfect.png" alt="logo" class="logo-default" /> -->
                <!-- <img src="https://bajajfinserv.awarathon.com/assets/uploads/company/1562834243.gif" alt="logo" class="logo-default" /> -->
            </a>
            <!--<div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>-->
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <?php if ($Compnay_Name != "") { ?>
            <!-- <div class="page-actions" style="color:#ccc;margin: 3px 0 15px 330px;padding: 0;float: left;"> -->
            <!--<div class="btn-group">
                <h4 class="caption-subject title bold uppercase"> <?php echo $Compnay_Name; ?></h4>
			</div> -->
            <!-- </div> -->
        <?php } ?>

        <!-- KRISHNA -- ADMIN NOTIFICATIONS CHANGES -->
        <?php //if(isset($admin_notification['message']) && $this->mw_session['role'] == 1){ 
        ?>
        <?php if (isset($admin_notification) && $this->mw_session['role'] == 1) { ?>
            <div class="page-actions" style="padding: 0;float: left;">
                <div class="alert alert-danger fade in alert-dismissible" style="padding: 7px 50px 7px 15px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close" style="top: 12px;right: -40px;">×</a>
                    <h5 class="caption-subject title bold" style="color:#db1f48;"> <?php echo $admin_notification; ?></h5>
                </div>
                <!-- <div class="btn-group">
                <h5 class="caption-subject title bold"> <?php //echo $admin_notification['message']; 
                                                        ?></h5>
            </div> -->
            </div>
        <?php } ?>
        <!-- <div class="top_lang">
            <div id='google_translate_element' class="dropdown-user open"></div>   
		</div> -->
        <!-- added by shital: 08:01:2024 -->
        <input type="hidden" style="width: auto;float: inline-end;" name="final_short" id="final_short" value="<?php echo $final_short; ?>" class="form-control input-sm">

        <div class="top_lang_xx">
            <input type="hidden" style="width: auto;float: inline-end;" name="back_lang" id="back_lang" value="<?php echo $back_lang_final; ?>" class="form-control input-sm">
            <div id='google_translate_element' class="dropdown-user open" style="display: none;"></div>
        </div>
        
       
        <!-- end by shital : 08:01:2024 -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right notranslate">
                	

            <button class="btn dropdown-toggle btn_newLang" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
    <i class="icon-globe" style="color: #fff !important;"></i> <span id="short_LN notranslate"><?php echo $back_lang_final; ?></span>
    <span class="caret"></span>
</button>
<ul class="dropdown-menu dropdown-menu-default notranslate" style="right: initial !important;
    margin: -6px 0px 0px -83px;min-width: auto !important;">
<?php foreach ($language as $ln) { $LN=$ln['ml_short'];?>
 


<li>
<a href="<?php echo base_url('multilang/back_language/'.$LN); ?>" <?php echo ($ln['ml_short'] == $back_lang_final )?'selected':''; ?>>
    <?php echo $ln['ml_name']; ?> - <?php echo $ln['ml_actual_text']; ?>
</a>
</li>
<?php } ?>
</ul>

                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="<?php echo  $avatar; ?>" />
                        <span class="username username-hide-on-mobile"> <?php echo  $acces_management['first_name']; ?>&nbsp;<?php echo $acces_management['last_name'] ?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <?php if ($acces_management['login_type'] != 1) { ?>
                            <li>
                                <a href="<?php echo site_url("profile"); ?>">
                                    <i class="icon-user"></i> My Profile </a>
                            </li>
                        <?php } ?>
                        <?php if ($acces_management['superaccess']) { ?>
                            <li data-id="configuration" class="main"><a href="<?php echo base_url() ?>configuration/site_settings"><i class="icon-settings mr10"></i> Configuration</a></li>
                            <li>
                                <!-- added by shital for language module : 08:01:2024 -->
                                <a href="<?php echo base_url(); ?>multilang">
                                    <i class="icon-globe"></i> Language </a>
                            </li>
                            <!-- End by shital for language module : 08:01:2024 -->
                        <?php } ?>
                        <li>
                            <a href="<?php echo base_url(); ?>login/logout">
                                <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- added by shital for language module :19:01:2024 -->
        <?php if ($acces_management['login_type'] == 2) { 
        ?>
        <!-- <select id="my_lang" name="my_lang" onchange="BacklangChange()" class="form-control input-sm lanselect" style="width: auto;float: right;margin: 11px 2px;border: 1px solid;" placeholder="Please select">
            <option value="">Language Select</option>
            <?php foreach ($language as $ln) { ?>
                <option value="<?= $ln['ml_short']; ?>" <?php echo ($ln['ml_short'] == $back_lang_final) ? 'selected' : ''; ?>><?php echo $ln['ml_name']; ?></option>
            <?php } ?>
        </select> -->
        <?php } 
        ?>

        <input type="hidden" name="log_lang" id="log_lang" value="<?php echo $back_lang_final; ?>" class="form-control input-sm">
        <!-- end by shital for language module :19:01:2024 -->


    </div>
</div>
<!-- added by shital for language module :08:01:2024 -->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script type="text/javascript">

    function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (15 * 60 * 1000)); 
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        console.log(key+'-----'+expires+'-----'+value);
    }


    function googleTranslateElementInit() {

        var Blang = $('#back_lang').val();
        //var newLN = $('#my_lang').val();
        var newLN = $('#log_lang').val();
        var FNlang = $('#final_short').val();
        //alert(newLN+'=-==-=-=-');
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            autodisplay: true,
            includedLanguages: FNlang,
        }, 'google_translate_element');

        setCookie('googtrans', newLN , 1); //set your language here

        setTimeout(function() { //alert('change language---'+newLN); //'gu,en,hi,mr,zh-CN',
            // Set the default language 
            var selectElement = document.querySelector('#google_translate_element select');
            selectElement.value = newLN; //Blang; //'gu';
            selectElement.dispatchEvent(new Event('change'));
            // window.location.reload();
        }, 500);
        //$("html").attr("lang", newLN);
        //setTimeout(function(){ window.location.reload(); },2000);  
    }


    // added :19:01:2024
    function BacklangChange() {
        val = document.getElementById("my_lang").value;
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>multilang/newlanguage',
            data: {
                lan: val
            },
            success: function(data) {
                //alert(data);  
                location.reload();
            }
        });
    }

    // $(document).ready(function() {
    //     var Blang = $('#log_lang').val();
    //     $('#short_LN').text(Blang);
    //     googleTranslateElementInit();
    //     $("html").attr("lang", Blang); //'language' value is retrieved from a cookie
    // });
</script>
<!-- end by shital for language module :08:01:2024 -->