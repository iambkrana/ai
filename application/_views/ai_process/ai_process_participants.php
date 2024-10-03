<?php
$acces_management = $this->session->userdata('awarathon_session');
$isAdmin = ($acces_management['login_type']==1) ? 1 : 0;
$ismasterAdmin = ($acces_management['username']=='masteradmin') ? 1 : 0;
?>
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Participants List
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-section">
            <div class="kt-section__info notranslate" style="padding:0px 0px 10px 0px;"><!-- added by shital LM: 08:03:2024 -->
                Wait while system process all the participants data.
            </div>
            <?php if($ismasterAdmin){ ?>

            <div class="actions" style="text-align: right;margin-bottom: 10px;">
                <div class="btn-group">
                    <button type="button" onclick="exportConfirmparticipate()" name="export_excel_trainee" id="export_excel_trainee" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                </div>
            </div>
            <?php } ?>
            <div class="kt-section__content">
                <div class="table-responsive">
                    <!-- Form tag commented by Anurag date:- 11-03-24 -->
                    <!-- <form id="formReportparticipate" name="formReportparticipate" method="post" action="<?php echo $assessment_type == 3  ? base_url() .'ai_process/export_trinity_dump' : base_url() . 'ai_reports/export_participate'  ?>"> -->
                        <input type="hidden" name="st_date" id="st_date" value='<?php echo $start_date; ?>'>
                        <input type="hidden" name="end_date" id="end_date" value='<?php echo $end_date; ?>'>
                        <input type="hidden" name="IsCustom" id="IsCustom" value='<?php echo $IsCustom; ?>'>
                        <input type="hidden" name="assessment_id" id="assessment_id" value='<?php echo $assessment_id; ?>'>
                        <input type="hidden" name="count_records" id="count_records" value='<?php echo $count_records; ?>'>
                        <table class="table table-bordered" id="aiprocess_tb">
                            <thead>
                                <tr>
                                <?php if($ismasterAdmin){ ?><th style="width:8%">Assessment Id</th> <?php } ?>
                                    <th style="width:8%">User Id</th>
                                    <th>User Name</th>
                                    <th style="width:8%">Question</th>
                                    <th style="width:8%">Attempts</th>
                                    <th style="width:8%">Video<br/> Uploaded</th>
                                    <th style="width:5%">Task <br/>Schedule (Yes/No)</th>
                                    <th style="width:5%">Video <br/>Process Status</th>
                                    <th style="width:5%">Excel <br/>Status</th>
                                    <th style="width:5%">Report <br/>Generated</th>
                                    <?php if($isAdmin){ 
                                        $col_name = ($assessment_type==3) ? 'Trinity' : (($assessment_type==2) ? 'Spotlight' : 'Excel');
                                    ?> 
                                        <th style="width:5%"><?= $col_name ?> <br/>Dump</th> 
                                    <?php } ?>
                                    <th style="width:5%">Watch <br/>Video</th>
                                    <th style="width:8%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(count((array)$_participants_result)>0) {
                                        foreach ($_participants_result as $pdata) { 
                                            $company_id      = $pdata->company_id;
                                            $assessment_id   = $pdata->assessment_id;
                                            $user_id         = $pdata->user_id;
                                            $trans_id        = $pdata->trans_id;
                                            $question_id     = $pdata->question_id;
                                            $attempts        = $pdata->attempts;
                                            $added_date      = $pdata->added_date;
                                            $uploaded_dt     = $pdata->uploaded_dt;
                                            $process_dt      = isset($pdata->process_dt) ? $pdata->process_dt : '';
                                            $datediff        = isset($pdata->datediff) ? $pdata->datediff: '';
                                            $time_diff       = isset($pdata->time_diff) ? $pdata->time_diff : '';
                                            $ftpto_vimeo_uploaded = $pdata->ftpto_vimeo_uploaded;
                                            $portal_name     = $pdata->portal_name;
                                            $assessment      = $pdata->assessment;
                                            $user_name       = $pdata->user_name;
                                            $question_series = $pdata->question_series;
                                            $video_url       = isset($pdata->video_url)?$pdata->video_url:"";
                                            $vimeo_uri       = isset($pdata->vimeo_uri)?$pdata->vimeo_uri:"";
                                            $vimeo_id        = ($vimeo_uri != $video_url ? $vimeo_uri : $video_url);
                                            $schedule_icon   = "-";
                                            $status_icon     = "-";
                                            $report_icon     = "-";
                                            $import_icon     = "-";
                                            $filedownload_icon  = "-";
                                            $schedule_input  = "";
                                            $status_input    = "";
                                            $report_input    = "";
                                            $import_input    = "";
                                            // Vimo uploaded icon
                                            $vimo_icon       = "-";
                                            $vimo_input      = "";
                                            if ($ftpto_vimeo_uploaded !== "" AND $ftpto_vimeo_uploaded != 0) {
                                                $vimo_icon  = '<img src="'.base_url().'/assets/images/yes.png" style="height:16px;width:16px;" />';
                                                $vimo_input = 1;
                                            }
                                            // Vimo uploaded icon

                                            if($assessment_type == 3){
                                                $ai_schedule_result = $this->common_model->get_value('ai_schedule', '*', 'company_id="'.$company_id.'" AND assessment_id="'.$assessment_id.'" AND user_id="'.$user_id.'" AND question_id="'.$question_id.'"');
                                            }else{
                                                $ai_schedule_result = $this->common_model->get_value('ai_schedule', '*', 'company_id="'.$company_id.'" AND assessment_id="'.$assessment_id.'" AND user_id="'.$user_id.'" AND trans_id="'.$trans_id.'" AND question_id="'.$question_id.'"');
                                            }
                                            if (isset($ai_schedule_result) AND count((array)$ai_schedule_result)>0){
                                                if ($ai_schedule_result->task_id!=="" AND $ai_schedule_result->task_id!=="FAILED") {
                                                    $schedule_icon  = '<img src="'.base_url().'/assets/images/yes.png" style="height:16px;width:16px;" />';
                                                    $schedule_input = 1;
                                                }
                                                if ($ai_schedule_result->task_status==1 OR $ai_schedule_result->task_status=="1") {
                                                    $status_icon  = '<img src="'.base_url().'/assets/images/yes.png" style="height:16px;width:16px;" />';
                                                    $status_input = 1;
                                                }
                                                if (($ai_schedule_result->xls_generated==1 OR $ai_schedule_result->xls_generated=="1") AND  $ai_schedule_result->xls_filename!='') {
                                                    $report_icon  = '<img src="'.base_url().'/assets/images/yes.png" style="height:16px;width:16px;" />';
                                                    $report_input = 1;
                                                }
                                                if ($ai_schedule_result->xls_imported==1 OR $ai_schedule_result->xls_imported=="1") {
                                                    $import_icon  = '<img src="'.base_url().'/assets/images/yes.png" style="height:16px;width:16px;" />';
                                                    $import_input = 1;
                                                }
                                                if ($ai_schedule_result->xls_filename!=="") {
                                                    if(file_exists('output_excel/'.$ai_schedule_result->xls_filename)){
                                                        $filedownload_icon  = '<a href="'.base_url().'output_excel/'. $ai_schedule_result->xls_filename.'"><i class="fa fa-file-excel-o "   aria-hidden="true"></i></a>' ;
                                                    }
                                                    else{
                                                        $filedownload_icon  = '<a   onclick="Filecheck()"><i class="fa fa-file-excel-o "   aria-hidden="true"></i></a>' ;
                                                    }
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <?php if($ismasterAdmin){  ?>
                                                <td><?php echo $pdata->assessment_id; ?></td>
                                                <?php } ?>
                                                <td><?php echo $pdata->user_id;?></td>
                                                <td><?php echo $pdata->user_name;?></td>
                                                <td><?php echo $pdata->question_series;?></td>
                                                <td><?php echo $pdata->attempts;?></td>
                                                <td><span id="schedule-icon-<?php echo $pdata->uid;?>"><?php echo $vimo_icon;?></span></td>
                                                <td><span id="schedule-icon-<?php echo $pdata->uid;?>"><?php echo $schedule_icon;?></span></td>
                                                <td><span id="status-icon-<?php echo $pdata->uid;?>"><?php echo $status_icon;?></span></td>
                                                <td><span id="report-icon-<?php echo $pdata->uid;?>"><?php echo $report_icon;?></span></td>
                                                <td><span id="import-icon-<?php echo $pdata->uid;?>"><?php echo $import_icon;?></span></td>
                                                <?php if($isAdmin && $assessment_type == 1){ ?>
                                                <td><span id="filedownload-icon-<?php echo $pdata->uid;?>"><?php echo $filedownload_icon;?></span></td>
                                                <?php } else if ($isAdmin && $assessment_type == 2) { ?>
                                                <td>
                                                    <form method="post" name="export_spotlight" id="export_spotlight" action="<?php echo base_url() . 'ai_reports/export_spotlight_dump/'; ?>">
                                                        <input type="hidden" name='user_id' value='<?php echo $pdata->user_id; ?>'>
                                                        <input type="hidden" name='assessment_id' value='<?php echo $assessment_id; ?>'>
                                                        <button type="submit" autofocus="" accesskey="" name="export_spot_light_dump" id="export_spot_light_dump" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i></button>
                                                    </form>
                                                </td>
                                                <?php }else if($isAdmin && $assessment_type == 3){ ?>
                                                <td>
                                                    <form method="post" name="export_trinity" id="export_trinity" action="<?php echo base_url() . 'ai_process/export_trinity_dump'; ?>">
                                                        <input type="hidden" name='user_id' value='<?php echo $pdata->user_id; ?>'>
                                                        <input type="hidden" name='assessment_id' value='<?php echo $assessment_id; ?>'>
                                                        <button type="submit" name="export_trinity_dump" id="export_trinity_dump" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i></button>
                                                    </form>
                                                </td>
                                                <?php } ?>
                                                <td>
                                                    <?php if ($vimeo_id!='') {
                                                        $play = "play_video('$vimeo_id',1)";
                                                        ?>
                                                        <button class="btn default btn-xs btn-solid" type="button" data-toggle="modal" onclick="<?= $play ?>"> 
                                                        <i class="fa fa-video-camera"></i>&nbsp;&nbsp;Play
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn orange btn-xs btn-outline dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> 
                                                            Actions&nbsp;&nbsp;<i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right" role="menu">
                                                            <li>
                                                                <a data-toggle="modal" onclick="task_error_log('<?php echo $pdata->uid;?>','<?php echo $company_id;  ?>','<?php echo $assessment_id;  ?>','<?php echo $user_id;  ?>','<?php echo $trans_id;  ?>','<?php echo $question_id;  ?>');">
                                                                <i class="fa fa-bug"></i>&nbsp;&nbsp;Error Logs
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <input type="hidden" id="schedule-<?php echo $pdata->uid;?>" name="schedule-<?php echo $pdata->uid;?>" value="<?php echo $schedule_input;?>"/>
                                                <input type="hidden" id="status-<?php echo $pdata->uid;?>" name="status-<?php echo $pdata->uid;?>" value="<?php echo $status_input;?>"/>
                                                <input type="hidden" id="report-<?php echo $pdata->uid;?>" name="report-<?php echo $pdata->uid;?>" value="<?php echo $report_input;?>"/>
                                                <input type="hidden" id="import-<?php echo $pdata->uid;?>" name="import-<?php echo $pdata->uid;?>" value="<?php echo $import_input;?>"/>
                                            </tr>
                                    <?php } } ?>
                            </tbody>
                        </table>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function exportConfirmparticipate() {
        var compnay_id = $('#company_id').val();
        var assessment_id = $('#assessment_id_trainee').val();
        var count_records = $('#count_records').val();
        if (compnay_id == "") {
            ShowAlret("Please select Company first.!!", 'error');
            return false;
        }
        if (count_records == 0) {
            ShowAlret("No Records Found.!!", 'error');
            return false;
        }
        $.confirm({
            title: 'Confirm!',
            content: "Are you sure want to Export. ? ",
            buttons: {
                confirm: {
                    text: 'Confirm',
                    btnClass: 'btn-primary',
                    keys: ['enter', 'shift'],
                    action: function() {
                        formReportparticipate.submit();
                    }
                },
                cancel: function() {
                    this.onClose();
                }
            }
        });
    }

   function Filecheck(){
        ShowAlret('File is missing','error');
    }
</script>