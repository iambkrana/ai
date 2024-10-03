<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url = base_url();
$asset_url =$this->config->item('assets_url');
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <head>        
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
                                    <span>Video Q&A/Situation</span>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Edit Q/A</span>
                                </li>
                            </ul>
                            <div class="page-toolbar">
                                <a href="<?php echo $base_url ?>video_situation" class="btn btn-sm btn-default pull-right">Back</a>&nbsp;
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-md-12">
                                <?php if ($this->session->flashdata('flash_message')) { ?> 
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                        <?php echo $this->session->flashdata('flash_message'); ?>
                                    </div>
                                <?php } ?>
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption caption-font-24">
                                            Edit Video Q&A
                                            <div class="tools"> </div>  
                                        </div>
                                    </div>
                                    <div class="portlet-body"> 
                                        <form id="frmVideo_situation" name="frmVideo_situation" method="POST" > 
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_overview">                                                    
                                                    <?php
                                                    $errors = validation_errors();                                                    
                                                    if ($errors) { ?>
                                                        <div style="display: block;" class="alert alert-danger display-hide">
                                                            <button class="close" data-close="alert"></button>
                                                            You have some form errors. Please check below.
                                                            <?php echo $errors; ?>
                                                        </div>
                                                    <?php } ?>                                                    
                                                    <?php if ($Company_id == "") { ?>
                                                    <div class="row">
                                                        <div class="col-md-4">       
                                                            <div class="form-group">
                                                                <label class="">Company Name<span class="required"> * </span></label>
                                                                <select id="company_id" name="company_id" class="form-control input-sm select2" placeholder="Please select" style="width:100%" >
                                                                    <option value="">Please Select</option>
                                                                     <?php foreach ($cmp_result as $cmp) { ?>
                                                                        <option value="<?= $cmp->id; ?>"  <?php echo ($result->company_id==$cmp->id ? 'Selected': ''); ?>><?= $cmp->company_name; ?> </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>    
                                                    <?php } ?> 
                                                    <div class="row">                                                        
                                                        <div class="col-md-3">    
                                                            <div class="form-group">
                                                                <label>Assessment Type<span class="required"> * </span></label>
                                                                <select name="assessment_type" id="assessment_type" class="form-control input-sm select2">
                                                                    <option value="">Please select</option>
                                                                    <?php foreach ($assessment_type as $val) { ?>
                                                                        <option value="<?php echo $val->id ?>" <?php echo($result->assessment_type == $val->id ? 'selected':'') ?>><?php echo $val->description ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-3">    
                                                            <div class="form-group">
                                                                <label>Question type<span class="required"> * </span></label>
                                                                <select id="question_type" name="question_type" class="form-control input-sm select2" onchange="getquestion_type();" >
                                                                    <option value="0" < ?php echo ($result->is_situation==0)?'selected':'';?>>Question</option>
                                                                    <option value="1" < ?php echo ($result->is_situation==1)?'selected':'';?>>Situation</option>
                                                                </select>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-md-3">    
                                                            <div class="form-group">
                                                                <label>Read Timer<span class="required"> * </span></label>
                                                                <input type="number" name="read_timer" id="read_timer" min="0"  class="form-control input-sm" value="<?php echo $result->read_timer ?>">
                                                                <span class="text-muted" style="color:red">(This sets the maximum time in seconds,Zero means no timer)</span>
                                                            </div>                                                            
                                                        </div>
                                                        <div class="col-md-3">    
                                                            <div class="form-group">
                                                                <label>Response timer<span class="required"> * </span></label>
                                                                <input type="number" name="response_timer" id="timer" min="0" max="300"  class="form-control input-sm" value="<?php echo $result->response_timer ?>">
                                                                <span class="text-muted" style="color:red">(This sets the maximum time in seconds,Zero means no timer)</span>
                                                            </div>                                                            
                                                        </div>
														<div class="col-md-3">    
                                                            <div class="form-group">
                                                                <label>Status<span class="required"> * </span></label>
                                                                <select id="status" name="status" class="form-control input-sm select2" placeholder="Please select" >
                                                                    <option value="1" <?php echo ($result->status==1)?'selected':'';?>>Active</option>
                                                                    <option value="0" <?php echo ($result->status==0)?'selected':'';?>>In-Active</option>
                                                                </select>
                                                            </div>
                                                        </div>
														<!-- <div class="col-md-3">    
                                                            <div class="form-group">
                                                                <label>Weightage<span class="required"> * </span></label>
                                                                <input type="number" name="weightage" id="weightage" min="0"  class="form-control input-sm" value="< ?php echo $result->weightage ?>">                                                                
                                                            </div>
                                                        </div> -->
                                                        		
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">    
                                                            <div class="form-group">
                                                                <label>Question Format<span class="required"> * </span></label>
                                                                 <input type="hidden" name="question_options" id="question_options" value="<?php echo $result->question_format ?>" />
                                                                <select id="question_option" name="question_option" class="form-control input-sm select2" placeholder="Please select" disabled="">
                                                                    <option value="0" <?php echo ($result->question_format==0)?'selected':'';?>>Text</option>
                                                                     <option value="1" <?php echo ($result->question_format==1)?'selected':'';?>>Video</option> 
                                                                    <option value="2" <?php echo ($result->question_format==2)?'selected':'';?>>Image</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><span id="label_dyamic"><?php echo ($result->is_situation==0)?'Question':'Situation';?></span><span class="required"> * </span></label>
                                                                <?php if($result->question_format==0){ ?>
                                                                    <textarea type="text" name="question" id="question" cols="5" rows="3" class="form-control input-sm"><?php echo $result->question ?></textarea>   
                                                                <?php }elseif($result->question_format==2){ ?>
                                                               
                                                                    <input type="file" id="myFileInput" name="file" style="display:none;" accept="image/png, image/gif, image/jpeg"/>
                                                                    <input type="hidden" name="question" id="question" value="<?php echo $result->question_path ?>" />   
                                                                    <div class="form-control fileinput fileinput-exists" style="border: none;height:auto;padding:0" data-provides="fileinput" onclick="document.getElementById('myFileInput').click()">
                                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 750px; max-height: 400px;">
                                                                            <img  id="question_preview" src="<?php echo base_url() . 'assets/uploads/' . ($result->question_format == 2 ? 'questions/' . $result->question_path : 'no_image.png'); ?>" style="max-width: 500px; max-height: 400px;" alt=""/>
                                                                        </div>
                                                                    </div>
                                                                    <span class="text-muted" style="color:red" id="file_desc"></span>
                                                                <?php }elseif($result->question_format==1){ ?>
                                                                    <input type="file" id="myFileInputv" name="file" style="display:none;" accept="video/mp4,video/x-m4v,video/*"/>
                                                                    <input type="hidden" name="question" id="question" value="<?php echo $result->question_path ?>" />   
                                                                    <div class="form-control fileinput fileinput-exists" style="border: none;height:auto;padding:0" data-provides="fileinput" onclick="document.getElementById('myFileInputv').click()">
                                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 750px; max-height: 400px;">
                                                                            <img  id="question_preview" src="<?php echo base_url() .'assets/uploads/no_videos.jpg'; ?>" width="250" height="200" alt=""/>
                                                                        </div>
                                                                    </div>
                                                                    <span class="text-muted" style="color:red" id="file_desc"></span>
                                                                     <a class="btn btn-orange btn-sm" style='float:right' data-target="#LoadModalVideo" data-toggle="modal"><i class="fa fa-video-camera"></i>&nbsp;Preview </a>
                                                            
                                                                <?php }?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <?php if($result->question_format==1 || $result->question_format==2){ ?>
                                                        <div class="col-md-6">
                                                             <div id="queTital" class="form-group">
                                                                <label><span id="label_dyamic">Question Title</span><span class="required"> * </span></label>
                                                                <textarea type="text" name="question_tital" id="question_tital" cols="5" rows="3" class="form-control input-sm"><?php echo $result->question ?></textarea> 
                                                            </div> 
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="row">
                                                        <!-- <div class="col-md-6">    
                                                            <div class="form-group">
                                                                <label><span id="label_dyamic">Question</span><span class="required"> * </span></label>
                                                                <textarea type="text" name="question" id="question" cols="5" rows="3" class="form-control input-sm"><?php echo $result->question ?></textarea>   
                                                            </div>
                                                        </div> -->
                                                        <div class="col-md-6">    
                                                            <div class="form-group">
                                                                <label>Assessor Guide</label>
                                                                <textarea type="text" name="assessor_guide" id="assessor_guide" cols="5" rows="3" class="form-control input-sm"><?php echo $result->assessor_guide ?></textarea>   
                                                            </div>                                                           
                                                        </div>                                            
                                                    </div> 
                                                    <div class="row">
                                                        <div class="col-md-6">    
                                                            <div class="form-group">
                                                                <label>Slide Heading (For Spotlight App)</label>
                                                                <textarea type="text" name="slide_heading" id="slide_heading" cols="5" rows="3" class="form-control input-sm"><?php echo $result->slide_heading ?></textarea>   
                                                            </div>                                                           
                                                        </div>   
                                                        <div class="col-md-6">    
                                                            <div class="form-group">
                                                                <label>Slide Description (For Spotlight App)</label>
                                                                <textarea type="text" name="slide_description" id="slide_description" cols="5" rows="3" class="form-control input-sm"><?php echo $result->slide_description ?></textarea>   
                                                            </div>                                                           
                                                        </div>                                           
                                                    </div>
                                                </div>                                                           
                                            </div>                                            
                                            <div class="row">      
                                                <div class="col-md-12 text-right">  
                                                    <button type="button" id="situation-submit" name="situation-submit" data-loading-text="Please wait..." class="btn btn-orange mt-ladda-btn ladda-button mt-progress-demo" data-style="expand-left" onclick="update_video_situation();">
                                                        <span class="ladda-label">Update</span>
                                                    </button>
                                                    <a href="<?php echo site_url("video_situation"); ?>" class="btn btn-default btn-cons">Cancel</a>
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
        <div class="modal fade" id="LoadModalVideo" role="basic" aria-hidden="true" data-width="400">
            <div class="modal-dialog modal-lg" style="width:1024px;">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" id="CloseModalBtn" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Video Question Privew</h4>
                    
                </div>
                <div class="modal-body">
                    <div class="portlet-body">
                        <div class="alert alert-success MedicineReturnSuccess display-hide" id="successDiv">
                        <button class="close" data-close="alert"></button>
                        <span id="SuccessMsg"></span>
                    </div>
                    <div class="alert alert-danger  display-hide" id="modalerrordiv">
                        <button class="close" data-close="alert"></button>
                        <span id="modalerrorlog"></span>
                    </div>
                        <div>
                            <iframe src="<?php echo $result->question_path ?>" width="1000" height="500" frameborder="0" allow="autoplay; fullscreen; picture-in-picture\" allowfullscreen title="data/user/0/com.example.awarathon_pwa/cache/REC8474285680719209381.mp4"></iframe>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-orange" onclick="UploadXlsManager();" >Confirm</button> -->
                    </div>

                </div>
            </div>
        </div>       
        <?php $this->load->view('inc/inc_footer_script'); ?>
        <script src="<?php echo $asset_url; ?>assets/global/plugins/cke-editor/ckeditor.js" type="text/javascript"></script>
	    <script src="<?php echo $asset_url; ?>assets/global/plugins/cke-editor/adapters/jquery.js"></script>
        <script>         
        var frmVideo_situation = $('#frmVideo_situation');            
        var form_error = $('.alert-danger', frmVideo_situation);
        var form_success = $('.alert-success', frmVideo_situation);
        var baseURL ="<?php echo $base_url; ?>";
        jQuery(document).ready(function() {    
            var queFormat = $('#question_option').val();
            if(queFormat == '0'){
                CKEDITOR.replace('question', {
                    toolbar: [
                        // {
                        //     name: 'styles',
                        //     items: ['Styles', 'Format']
                        // },
                        {
                            name: 'basicstyles',
                            items: ['Bold','-','Italic']
                        }
                        // {
                        //     name: 'paragraph',
                        //     items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent']
                        // }
                        // {
                        //     name: 'links',
                        //     items: ['Link', 'Unlink', 'Anchor']
                        // }
                    ],
                });
			    
                CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR; // pressing the ENTER KEY input <br/>
                CKEDITOR.config.autoParagraph = false; 
            }
             if(queFormat != '0'){
                CKEDITOR.replace('question_tital', {
                    toolbar: [
                        // {
                        //     name: 'styles',
                        //     items: ['Styles', 'Format']
                        // },
                        {
                            name: 'basicstyles',
                            items: ['Bold','-','Italic']
                        }
                        // {
                        //     name: 'paragraph',
                        //     items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent']
                        // }
                        // {
                        //     name: 'links',
                        //     items: ['Link', 'Unlink', 'Anchor']
                        // }
                    ],
                });
                CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR; // pressing the ENTER KEY input <br/>
                CKEDITOR.config.autoParagraph = false; 
            }
            showQuestionOption(queFormat);

            // $('#question_option').change(function(){
            //     queFormat = $('#question_option').val();
            //     showQuestionOption(queFormat);
            //     if(queFormat == '0'){
            //         CKEDITOR.instances['question'].setReadOnly(false);
            //     }else{
            //         CKEDITOR.instances['question'].setReadOnly(true);
            //     }
            // });

            $("#myFileInput").change(function()
            {
                var formData = new FormData();
                var file_data = $('#myFileInput').prop('files')[0];
                formData.append('file', file_data);
                formData.append('isUpdate', 1);
                formData.append('que_image', $("#question").val());
                var fileType = (queFormat=='1') ? 'selectvideo' : 'selectImage';
                $.ajax({
                    url : '<?php echo base_url(); ?>video_situation/'+fileType,
                    type : 'POST',
                    data : formData,
                    async: false,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(Odata) {
                        var Data = $.parseJSON(Odata);
                        if (Data['Success'] && Data['Message']!='') {
                            ShowAlret(Data['Message'], 'success');  
                            $("#question").val(Data['que_image']);
                           // CKEDITOR.instances['question'].setData(Data['que_image']);
                            // CKEDITOR.instances['question'].setData(Data['que_image']);
                            $('#question_preview').prop('src', baseURL+'assets/uploads/questions/'+Data['que_image']);
                        } else {
                            ShowAlret(Data['Message'], 'error');
                            $('#errordiv').show();
                            $('#errorlog').html(Data['Message']);
                        }
                    },
                    complete: function(){
                        
                    }
                });
            });           
            $('.range-class').hide();
            frmVideo_situation.validate({
                errorElement: 'span',
                errorClass: 'help-block help-block-error',
                focusInvalid: false,
                ignore: "",
                rules: {
                    company_id: {
                        required: true
                    },
                    assessment_type: {
                        required: true
                    },
                    question: {
                        required: function(element) {
                            return ($('#question_option').val() == "0");
                        },
                        questionCheck: true
                    },
                    // question_type :{
                    //     required: true
                    // },
                    // weightage: {
                    //     required: true                                 
                    // },
                    timer :{
                        required: true
                    },
                    status: {
                        required: true
                    }                    
                },
                invalidHandler: function (event, validator) {
                    form_success.hide();
                    form_error.show();
                    App.scrollTo(form_error, -200);
                },
                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.hasClass('.form-group')) {
                        error.appendTo(element.parent().find('.has-error'));
                    } else if (element.parent('.form-group').length) {
                        error.appendTo(element.parent());
                    } else {
                        error.appendTo(element);
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error');                    
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error');                    
                },
                success: function (label) {
                    label.closest('.form-group').removeClass('has-error');                    
                },
                submitHandler: function (form) {
                    form_success.show();
                    form_error.hide();
                    Ladda.bind('button[id=situation-submit]');
                    form.submit();
                }
            });
            jQuery.validator.addMethod("questionCheck", function (value, element) {
                var isSuccess = false;                                
                $.ajax({
                    type: "POST",
                    data: {question: value, assessment_type: $('#assessment_type').val(),question_id:<?php echo $result->id; ?>},
                    url: "<?php echo base_url(); ?>video_situation/Check_question",
                    async: false,
                    success: function (msg) {
                        isSuccess = msg != "" ? false : true;
                    }
                });
                return isSuccess;
            }
            , "Question already exists!!!");
        });   
        function showQuestionOption(queFormat){
            if(queFormat == '0'){
                    $("#que_file").prop("disabled", true);
                    $("#file_desc").html('');
            }else{
                $("#que_file").prop("disabled", false);
                if(queFormat == '1'){
                    $("#file_desc").html('(Allowed: Extensions: .mp4 , .m4v.)');
                }else{
                    $("#file_desc").html('(Allowed: Extensions: .png , .gif, .jpg, .jpeg, .bmp. Width:750px, Height:400px)');
                }
            }
        }
         function getExtension(filename) {
          var parts = filename.split('.');
          return parts[parts.length - 1];
        }
        function isVideo(filename) {
          var ext = getExtension(filename);
          switch (ext.toLowerCase()) {
            case 'm4v':
            case 'avi':
            case 'mpg':
            case 'mp4':
              // etc
              return true;
          }
          return false;
        }        
        function update_video_situation() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            if (!$('#frmVideo_situation').valid()) {
                return false;
            } 
            var fd = new FormData();
            
            var queFormat = $('#question_option').val();
            
             if(queFormat=='1')
             {

                var file_data = $('#myFileInputv')[0].files;
                console.log(file_data.length);
             //   return false;
                if(file_data.length!='0')
                {
                    fd.append("file", file_data[0]);
                    console.log(file_data[0]['name']);
                    var is_video= isVideo(file_data[0]['name']);
                    if(!is_video)
                    {
                        ShowAlret('Selected File Formate is not valid', 'error');
                        return false;
                    }
                }
                

             }

                           
                var other_data = $('#frmVideo_situation').serializeArray();
                    $.each(other_data,function(key,input){
                    fd.append(input.name,input.value);
                })                                  
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>video_situation/update/<?php echo base64_encode($result->id);?>',
                //data: $('#frmVideo_situation').serialize(),
                data: fd,
                processData: false, 
                contentType: false,
                beforeSend: function () {
                    customBlockUI();
                },
                success: function (Odata) {
                    var Data = $.parseJSON(Odata);
                    if (Data['success']) {
                        ShowAlret(Data['Msg'], 'success');   
                        //window.location.href = '< ?php echo base_url(); ?>video_situation/index';
                    } else {
                        $('#errordiv').show();
                        $('#errorlog').html(Data['Msg']);
                        App.scrollTo(form_error, -200);
                    }
                    customunBlockUI();
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    ShowAlret("Status: " + textStatus + " ,Contact Mediaworks for technical support!");
                }
            });
        }
    function getquestion_type(){
        if($('#question_type').val()==1){
            $('#label_dyamic').text('Situation');
        }else{
            $('#label_dyamic').text('Question');
        }
    }
        </script>                
    </body>
</html>