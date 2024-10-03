<?php
defined('BASEPATH') or exit('No direct script access allowed');
$base_url = base_url();
$asset_url = $this->config->item('assets_url');
$array = json_decode(json_encode($lang_result), True);
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">

<head>
    <!--link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"-->
    <?php $this->load->view('inc/inc_htmlhead'); ?>
    </head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
    <div class="page-wrapper">
        <?php $this->load->view('inc/inc_header'); ?>
        <div class="clearfix"> </div>
        <div class="page-container">
            <?php $this->load->view('inc/inc_sidebar'); ?>
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <i class="fa fa-circle"></i>
                                <span>Language</span>
                            </li>
                        </ul>
                        <div class="col-md-1 page-breadcrumb"></div>
                        <div class="page-toolbar">
                            <!-- <div id="dashboard-report-range" name="daterange" class="pull-right tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change dashboard date range">
								<i class="icon-calendar"></i>&nbsp;
								<span class="thin uppercase hidden-xs"></span>&nbsp;
								<i class="fa fa-angle-down"></i>
							</div> -->
                        </div>
                    </div>

                    <div class="row margin-top-10">
                        <div class="col-md-12">

                            <div class="portlet light bordered">
    <div class="portlet-body">
        <div class="tabbable-line tabbable-full-width">
            <!-- Put here new code -->
            <form id="frmAIMethod" name="frmAIMethod" method="POST">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-12 mid-space">
                    <div class="right-content aw-dashboard">
                        <div class="row"><div id='dsk' style="display: none">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="toph4">Default Language</h4>
                                <div class="api-details">
                                    <p class="bottomPR" style="font-size:15px;">Select the default language you want to be available in.</p>
                                    <div class="bottomsel">
                                    <select id="default_id" name="default_id" class="form-control input-sm lanselect" placeholder="Please select">
                                        <?php foreach ($select_lang as $cmp) { ?>
    <option value="<?= $cmp->ml_short; ?>" <?php echo ($array[0]['default_lang'] == $cmp->ml_short )?'selected':'';?>><?php echo $cmp->ml_name; ?> - <?php echo $cmp->ml_actual_text; ?></option>
<?php } ?>
                                        </select>
                                    </div>
                                </div>
 
                            </div>
                            <?php $ml_chk=$array[0]['multi_lang']; ?>
 
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="toph4">MultiLanguage  
                                <input type="checkbox" name="multi_checkbox" <?php if($ml_chk == '1') echo "checked = 'checked'"; ?> value="<?php if($ml_chk == '1'){ echo 'on'; }else{ echo 'off'; } ?>" class="cm-toggle multi_checkbox" style="display: inline-grid;">        
                                </h4>
                                <?php //  <i class='fas fa-crown' style="color:#0bcf6c;"></i> ?>
                                <p class="bottomPR" style="font-size:15px;">By enabling multilanguage option you add more additional languages to this portal.</p>
                                <input type="hidden" name="final_check" id="ml_chk" value="<?php echo $ml_chk; ?>" class="form-control input-sm">				
            
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <button type="button" data-toggle="modal" onclick="LoadCreateModal();" id="send_otp" name="send_otp" class="btn btn-sm btn-outline addbutton" data-style="expand-right">
                                <span class="ladda-label"><i class="fa fa-plus-circle" style="color: #1d4fab;font-size: 20px;"></i>&nbsp; Add Additional Language</span>
                            </button>

                               
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12" style="padding-left: 30px!important;">
                                <div class="col-lg-4 col-md-4 col-sm-4" style="padding-left: 30px!important;font-size: larger;font-weight: 600;">Languages</div>
                                <div class="col-lg-8 col-md-8 col-sm-8" style="padding-left: 30px!important;text-align: center;font-size: larger;font-weight: 600;">Access</div>   
                            </div>

                            <?php   $lang_array = json_decode(json_encode($multi_lang), True);
           // echo "<pre>"; print_r($lang_array); echo "</pre>";  
            //echo count($lang_array);
                    foreach($lang_array as $ln){ ?>
                            <div class="col-lg-12 col-md-12 col-sm-12" style="padding-left: 30px!important;">                   
                                <div class="col-lg-4 col-md-4 col-sm-4 shortname_lang">
                                    <div class="shortname">
                                    <span class="notranslate"><?php $ml_short=$ln['ml_short']; 
                                      echo  substr($ml_short, 0, 2); ?>
                                      </span>
                                    </div>
                                    <label class="shortname_label notranslate"><?php echo $ln['ml_name']; ?> - <?php echo $ln['ml_actual_text']; ?></label>   
                                </div>                    
                                <div class="col-lg-8 col-md-8 col-sm-8 short_check">
                                    <!-- <input type="checkbox" <?php //if($ln['ml_short'] == $array[0]['backend_page']) echo "checked = 'checked'"; ?> value="<?php //echo $ln['ml_id']; ?>" name="mybox[]" id="checkboxmy" class="mybox cm-toggle checkboxmy" style="display:grid;"> -->
                                    <input type="checkbox" <?php if($ln['status'] == 2) echo "checked = 'checked'"; ?> value="<?php echo $ln['ml_id']; ?>" name="mybox[]" id="checkboxmy" class="mybox cm-toggle checkboxmy" style="display:grid;">                
                                </div>             
                            </div>
<?php   }   ?>
 
                      

                            <input type="hidden" name="status" id="status" value="<?php echo $array[0]['status']; ?>" class="form-control input-sm">
                            <input type="hidden" name="addedby" id="addedby" value="<?php echo $array[0]['addedby']; ?>" class="form-control input-sm">
                            <input type="hidden" name="lan_id" id="lan_id" value="<?php echo base64_encode($array[0]['lan_id']); ?>" class="form-control input-sm">
                            <input type="hidden" name="multi_lang" id="multi_lang" value="<?php echo $array[0]['multi_lang']; ?>" class="form-control input-sm">
                                                                           
                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:30px;">
                                <h4 class="bottomh4">Login Page Language</h4>
                                <div class="api-details">
                                    <p class="bottomPR">Your login page will be visible in this language.</p>
                                    <div class="bottomsel">
                                        <select id="log_page_lang" name="log_page_lang" class="form-control input-sm lanselect" placeholder="Please select" style="width:100%">
                                            <option value="">No Selection</option>
                                        <?php
                                        foreach ($select_lang as $cmp) { ?>
                                            <option value="<?= $cmp->ml_short; ?>" <?php echo ($array[0]['login_page'] == $cmp->ml_short )?'selected':'';?>><?php echo $cmp->ml_name; ?> - <?php echo $cmp->ml_actual_text; ?></option>
<?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:15px;">
                                <h4 class="bottomh4">Backend System Language</h4>
                                <div class="api-details">
                                    <p class="bottomPR">Your Admin/Manager backend system will be visible in this language.</p>
                                    <div class="bottomsel">
                                        
                                    <select id="back_lang" name="back_lang" class="form-control input-sm lanselect" placeholder="Please select" style="width:100%">
                                            <option value="">No Selection</option>
                                        <?php
                                       foreach ($select_lang as $cmp) { ?>
                                        <option value="<?= $cmp->ml_short; ?>" <?php echo ($array[0]['backend_page'] == $cmp->ml_short )?'selected':'';?>><?php echo $cmp->ml_name; ?> - <?php echo $cmp->ml_actual_text; ?></option>
<?php } ?>
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:15px;">
                                <h4 class="bottomh4">PWA Language</h4>
                                <div class="api-details">
                                    <p class="bottomPR">Your Participation page for Users will be visible in this language.</p>
                                    <div class="bottomsel">
                                        <select id="pwa_lang" name="pwa_lang" class="form-control input-sm lanselect" placeholder="Please select" style="width:100%">
                                            <option value="">No Selection</option>
                                            <?php
                                           foreach ($select_lang as $cmp) { ?>
                                            <option value="<?= $cmp->ml_short; ?>" <?php echo ($array[0]['pwa_page'] == $cmp->ml_short )?'selected':'';?>><?php echo $cmp->ml_name; ?> - <?php echo $cmp->ml_actual_text; ?></option>
<?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:15px;">
                            <?php if(count($sel_Lang)>0){ ?>
                                <button type="button" id="lang-submit" name="lang-submit" data-loading-text="Please wait..." class="btn btn-orange mt-ladda-btn ladda-button mt-progress-demo" data-style="expand-left" onclick="update_aimethods();">
                                    <span class="ladda-label">Update</span>
                                </button>
                                <?php }else{ ?>
                                <button type="button" id="lang-submit" name="lang-submit" data-loading-text="Please wait..." class="btn btn-orange mt-ladda-btn ladda-button mt-progress-demo" data-style="expand-left" onclick="save_aimethods();">
                                    <span class="ladda-label">Save</span>
                                </button>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <!-- End here new code -->
        </div>


    <div id="responsive-modal" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" data-width="760">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="frmModalForm" name="frmModalForm" onsubmit="return false;">
                <div class="modal-header">
                    <button type="button" class="close" onclick="resetDATA();"  data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Language</h4>
    </div>
<div class="modal-body">
                    <div id='dsk' style="display: none">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="">Language<span class="required"> * </span></label>
                                <!--input type="text" name="lang_namess" id="lang_namess" maxlength="250" class="form-control input-sm" autocomplete="off"-->
                                <input type="hidden" name="edit_id" id="edit_id" class="form-control input-sm" autocomplete="off" value="">

                                    <select id="lang_name" name="lang_name" required class="form-control input-sm select2" placeholder="Please select" style="width:100%">
                                        <option value="">Please Select</option>
                                        <?php
                                        foreach ($add_lang as $cmp) { ?>
                                            <option value="<?= $cmp->ml_id; ?>"><?php echo $cmp->ml_name; ?></option>
                                        <?php } ?>
                                    </select>
                            </div>
                        </div>
                    </div>
                </div>
<div class="modal-footer">
                    <div class="col-md-12 text-right ">
                        <button type="submit" id="modal-create-submit" name="modal-create-submit" data-loading-text="Please wait..." class="btn btn-orange mt-ladda-btn ladda-button mt-progress-demo" data-style="expand-right">
                            <span class="ladda-label">Submit</span>
                        </button>
                        <button type="button" data-dismiss="modal" onclick="resetDATA();" class="btn btn-default btn-cons">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    
    </div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('inc/inc_footer_script'); ?>
    <!--script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script-->
<script src="<?php echo $asset_url; ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/js/dataTables.buttons.min.js" type="text/javascript"></script>


<script>
    var base_url     = "<?php echo $base_url; ?>";       
    var frmModalForm = $('#frmModalForm');            
    var form_error = $('.alert-danger', frmModalForm);
    var form_success = $('.alert-success', frmModalForm);
    //Ladda.bind('button[id=modal-create-submit]');
    var mcs = Ladda.create(document.querySelector('#frmModalForm'));

    function Redirect(url) {
        window.location = url;
    }

    function resetDATA() {
        $("#lang_name").select2("val", "1");
    }
   
    $('.lanselect').wrap('<span class="notranslate">');
    
    $(document).ready(function(){
 
 var multi_checkbox_id = $('.multi_checkbox').val();
 if(multi_checkbox_id=='off'){  
     $("input:checkbox[id^='checkboxmy']").attr('disabled',true);
 }               

 $(".multi_checkbox").on('change',function()
 {
     var abc = this.value; 
     $('#ml_chk').val(abc);

     if(!$(this).is(':checked')){
         $('#ml_chk').val('no');
         alert('Are you sure you want to disable all languages?');
         $("input:checkbox[id^='checkboxmy']").attr('disabled',true);
     }else{
         alert('Enable Multilingual Support?'); 
         $('#ml_chk').val('yes');
         $("input:checkbox[id^='checkboxmy']").attr('disabled',false);
     }   

     /*$.ajax({ 
         type: "POST",
         url: '<?php //echo base_url(); ?>multilang/multiLanguage',
         data:{lan:val},  
         success: function(data) {
             //alert(data);
             //location.reload();
         }
     });*/
 }); // multi_checkbox funciton

        // $("input:checkbox[name^='mybox']").on('change', function () {
        //     var val = this.value;
        //    $.ajax({ 
        //         type: "POST",
        //         url: '<?php //echo base_url(); ?>multilang/adminLanguage',
        //         data:{lan:val},  
        //         success: function(data) {
        //             //alert(data);
        //             location.reload();
        //         }
        //     });
        // }); 

    
}); //----document ready

    $("#frmModalForm").submit(function(event){
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>multilang/addLang',
            data: $('#frmModalForm').serialize(),
            success: function(data) {
                var data= JSON.parse(data);
                // Ajax call completed successfully
                document.getElementById('dsk').innerHTML = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' + data.message + '</div>';
                document.getElementById('dsk').style.display = "block";
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                //setTimeout('document.getElementById("dsk").style.display = "none"', 2000);
                setTimeout(function(){ window.location.reload(); },2000);  
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            },
            error: function(data) {
                // Some error in ajax call
                document.getElementById('dsk').innerHTML = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>some error !</div>';
                document.getElementById('dsk').style.display = "block";
                setTimeout('document.getElementById("dsk").style.display = "none"', 2000);
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });
        
    function save_aimethods(){             
        if (!$('#frmAIMethod').valid()) {
            return false;
        }else{   }                 
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>multilang/submit',
            data: $('#frmAIMethod').serialize(),
            success: function(data) {
                var data= JSON.parse(data);
                // Ajax call completed successfully
                document.getElementById('dsk').innerHTML = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' + data.message + '</div>';
                document.getElementById('dsk').style.display = "block";
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                setTimeout('document.getElementById("dsk").style.display = "none"', 2000);
                setTimeout(function(){ window.location.reload(); },2000);  
                window.location.reload();
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            },
            error: function(data) {
                    
                // Some error in ajax call
                document.getElementById('dsk').innerHTML = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>some error !</div>';
                document.getElementById('dsk').style.display = "block";
                setTimeout('document.getElementById("dsk").style.display = "none"', 2000);
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    }   

    function update_aimethods(){    //alert('update_aimethods');
        var lan_id = $('#lan_id').val();
        if (!$('#frmAIMethod').valid()){
            return false;
        }                
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>multilang/edit',
            data: $('#frmAIMethod').serialize(),
            success: function(data) {
                var data= JSON.parse(data);
                  // alert(data.message);
                //alert("Language Update Successfully olddd");
                document.getElementById('dsk').innerHTML = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>"' + data.message + '"</div>';
                document.getElementById('dsk').style.display = "block";
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                setTimeout('document.getElementById("dsk").style.display = "none"', 1000);
                //setTimeout(function(){ window.location.reload(); },1000);
                window.location.reload();
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            },
            error: function(data) {
                //alert("some Error");
                document.getElementById('dsk').innerHTML = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>some error !</div>';
                document.getElementById('dsk').style.display = "block";
                window.location.href = base_url + 'multilang'; 
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                window.location.reload();
                setTimeout('document.getElementById("dsk").style.display = "none"', 2000);
            }
        });
    }   

    function LoadCreateModal(){ 
        $('.modal-title').html('Add Language').show();
        $('#responsive-modal').modal('show');
    }
</script>
</body>
</html>