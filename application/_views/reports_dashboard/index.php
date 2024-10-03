<?php
defined('BASEPATH') or exit('No direct script access allowed');
$base_url = base_url();
$asset_url = $this->config->item('assets_url');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('inc/inc_htmlhead'); ?>
    <link href="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <style>
        .row {
            margin-left: -15px;
            margin-right: -10px;
        }

        .arrow-row {
            width: 100%;
            height: auto;
        }

        .tr-background {
            background: #ffffff !important;
        }

        .wksh-td {
            color: #000000 !important;
            vertical-align: top !important;
        }

        .whsh-icon {
            float: right;
            position: absolute;
            top: 10px;
            right: 15px;
            color: #cccccc;
        }

        .potrait-title-mar {
            margin-left: -9px;
            margin-right: -9px;
        }

        .dashboard-stat {
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -ms-border-radius: 4px;
            -o-border-radius: 4px;
            border-radius: 4px;
            background: #fff;
            padding: 5px 5px 5px;
            border: 1px solid #eef1f5;
            border-radius: 5px !important;
            background: aliceblue;
        }

        .dashboard-stat .display {
            height: 70px;
        }

        .dashboard-stat .display .number {
            text-align: center;
            float: left;
            display: inline-block;
            width: 100%;
        }

        .dashboard-stat .display .number small {
            font-size: 12px;
            color: #777777;
            font-weight: 600;
            text-transform: uppercase;
            width: 100%;
        }

        .font-orange-sharp {
            color: #f1592a !important;
            margin: 0px !important;
            padding: 5px !important;
        }

        .tokenize-sample {
            width: 100%;
            height: auto
        }

        .theme-panel>.theme-options>.theme-option>span {
            width: 115px;
        }

        .no-padding {
            padding: 0px !important;
        }

        .page-content-white .page-title {
            margin: 20px 0;
            font-size: 22px;
            font-weight: 300 !important;
        }

        .page-container-bg-solid .page-content {
            background: #f1f1f1;
        }
    </style>
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white">
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
                                <a href="#">Reports Dashboard</a>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <a href="<?php echo base_url() . 'reports'; ?>" class="btn btn-sm btn-default pull-right">Back</a>&nbsp;
                        </div>
                        <div class="col-md-1 page-breadcrumb"></div>
                        <div class="page-toolbar">
                            <div id="dashboard-report-range" name="daterange" class="pull-right tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change dashboard date range">
                                <i class="icon-calendar"></i>&nbsp;
                                <span class="thin uppercase hidden-xs"></span>&nbsp;
                                <i class="fa fa-angle-down"></i>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- tabs -->
                    <div class="tab-pane " id="tab_overview">
                        <ul class="nav nav-tabs" id="tabs">
                            <li class="active">
                                <a href="#manager_tab" data-toggle="tab">Manager Dashboard</a>
                            </li>
                            <li>
                                <a href="#trainee_reports_tab" data-toggle="tab">Trainee Dashboard</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="manager_tab">
                                <div class="row mt-10">
                                    <div class="col-md-12">
                                        <div class="panel-group accordion" id="manager_tab">
                                            <div class="panel panel-default">
                                                <div id="collapse_3_4" class="panel-collapse ">
                                                    <div class="panel-body">
                                                        <!-- manager dashboard start here -->
                                                        <form id="FilterFrm" name="FilterFrm" method="post">
                                                            <div class="clearfix margin-top-10"></div>
                                                            <div class="row">
                                                                <div class="col-lg-12 col-xs-12 col-sm-12">
                                                                    <div class="panel-group accordion" id="accordion3">
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-heading">
                                                                                <h4 class="panel-title">
                                                                                    <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_2">
                                                                                        Filter Data </a>
                                                                                </h4>
                                                                            </div>
                                                                            <div id="collapse_3_2" class="panel-collapse ">
                                                                                <div class="panel-body">
                                                                                    <div class="row margin-bottom-10">
                                                                                        <!--Added below 2 div class-->
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Manager&nbsp; </label>
                                                                                                <select id="supervisor_id" name="supervisor_id" class="form-control input-sm select2me" style="width: 100%" onchange="getAssessmentwiseData()">
                                                                                                    <option value="">Please Select</option>
                                                                                                    <?php foreach ($TrainerResult as $trainer) { ?>
                                                                                                        <option value="<?= $trainer->userid; ?>" <?php echo (isset($manager_id) && $manager_id == $trainer->userid) ? 'selected' : '' ?>><?php echo $trainer->fullname; ?></option>
                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Assessment&nbsp;<span class="required"> </label>
                                                                                                <select id="assessment_id1" name="assessment_id1" class="form-control input-sm select2me" style="width: 100%">
                                                                                                    <option value="">Select Value</option>
                                                                                                    <?php
                                                                                                    //if (isset($assessment_list_data)) {
                                                                                                    foreach ($assessment_list_data as $at) { ?>
                                                                                                        <option value="<?= $at->id; ?>"><?php echo '[' . $at->id . '] - ' . $at->assessment; ?></option>
                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Report by&nbsp;<span class="required"> * </span></label>
                                                                                                <select id="report_by" name="report_by" class="form-control input-sm select2_rpt" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="0">Assessment</option>
                                                                                                    <option value="1">Parameter</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Report Type&nbsp;<span class="required"> * </span></label>
                                                                                                <select id="report_type" name="report_type" class="form-control input-sm select2_rpt" placeholder="Please Select">
                                                                                                    <option value=""></option>
                                                                                                    <?php foreach ($report_type as $rt) { ?>
                                                                                                        <option value="<?= $rt->id; ?>" <?php echo ($rt->default_selected ? 'selected' : ''); ?>><?php echo $rt->description; ?></option>

                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Trainee Region&nbsp;</label>
                                                                                                <select id="region_id" name="region_id" class="form-control input-sm select2me" style="width: 100%">
                                                                                                    <option value="">All Region</option>
                                                                                                    <?php foreach ($region_data as $rg) { ?>
                                                                                                        <option value="<?= $rg->region_id; ?>"><?php echo $rg->region_name; ?></option>
                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!--<div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Store wise/Vertical wise&nbsp;</label>
																<select id="store_id" name="store_id" class="form-control input-sm select2me" placeholder="Please select"  style="width: 100%" >                                                                        
																	<option value="">All Store</option>
																	<?php foreach ($store_data as $st) { ?>
																		<option value="<?= $st->store_id; ?>"><?php echo $st->store_name; ?></option>
																	<?php } ?>
																</select>
                                                            </div>
                                                            
                                                        </div>-->

                                                                                        <div class="col-md-1">
                                                                                            <div class="text-right" style="margin-top: 20px;">
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="dashboard_refresh()">Search</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--                                                    <div class="clearfix margin-top-20"></div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-offset-10 col-md-2 text-right">
                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="dashboard_refresh()">Search</button>                                                                
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                </xdiv>
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-xs-12 col-sm-12">
                                                                        <div class="portlet light bordered">
                                                                            <div class="portlet-title potrait-title-mar">
                                                                                <div class="caption">
                                                                                    <i class="icon-bar-chart font-dark hide"></i>
                                                                                    <span class="caption-subject font-dark bold uppercase">Quick Statistics</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="portlet-body">
                                                                                <div class="clearfix"></div>

                                                                                <!-- STAT LIFE TIME ROW -->
                                                                                <div class="row" style="margin-top: 5px;">
                                                                                    <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                        <div class="dashboard-stat">
                                                                                            <div class="display">
                                                                                                <div class="number">
                                                                                                    <h3 class="font-orange-sharp">
                                                                                                        <span data-counter="counterup" id="total_assessment" data-value="0">0</span>
                                                                                                    </h3>
                                                                                                    <small>Total Assessment</small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                        <div class="dashboard-stat">
                                                                                            <div class="display">
                                                                                                <div class="number">
                                                                                                    <h3 class="font-orange-sharp">
                                                                                                        <span data-counter="counterup" id="candidate_count" data-value="0">0</span>
                                                                                                    </h3>
                                                                                                    <small>Candidate Count</small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                        <div class="dashboard-stat">
                                                                                            <div class="display">
                                                                                                <div class="number">
                                                                                                    <h3 class="font-orange-sharp">
                                                                                                        <span data-counter="counterup" id="average_accuracy" data-value="0">0%</span>
                                                                                                    </h3>
                                                                                                    <small>Average Accuracy</small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row" style="margin-top: 5px;">
                                                                                    <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                        <div class="dashboard-stat">
                                                                                            <div class="display">
                                                                                                <div class="number">
                                                                                                    <h3 class="font-orange-sharp">
                                                                                                        <span data-counter="counterup" id="highest_accuracy" data-value="0">0%</span>
                                                                                                    </h3>
                                                                                                    <small>Highest Accuracy</small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                        <div class="dashboard-stat">
                                                                                            <div class="display">
                                                                                                <div class="number">
                                                                                                    <h3 class="font-orange-sharp">
                                                                                                        <span data-counter="counterup" id="lowest_accuracy" data-value="0">0%</span>
                                                                                                    </h3>
                                                                                                    <small>Lowest Accuracy</small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                        <div class="dashboard-stat">
                                                                                            <div class="display">
                                                                                                <div class="number">
                                                                                                    <h3 class="font-orange-sharp">
                                                                                                        <span data-counter="counterup" id="question_answer" data-value="0">0%</span>
                                                                                                    </h3>
                                                                                                    <small>Total Question Answered </small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--                                                <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                    <div class="dashboard-stat">
                                                        <div class="display">
                                                            <div class="number">
                                                                <h3 class="font-orange-sharp">
                                                                    <span data-counter="counterup" id="attempt_taken" data-value="--">--</span>
                                                                </h3>
                                                                <small>Attempts taken per Assessment</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
                                                                                </div>
                                                                                <div class="row" style="margin-top: 5px;">
                                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                        <div class="portlet light bordered" style="padding: 12px 20px 10px !important;">
                                                                                            <div class="portlet-title potrait-title-mar">
                                                                                                <div class="caption">
                                                                                                    <i class="icon-bar-chart font-dark hide"></i>
                                                                                                    <span class="caption-subject font-dark bold uppercase">Strength</span>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="portlet-body" style="padding: 0px !important">
                                                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                                                    <table class="table table-hover table-light" id="asmnt-top-five">
                                                                                                        <thead>
                                                                                                            <tr class="uppercase">
                                                                                                                <th class="wksh-td th-desc" width="80%"> PARAMETER </th>
                                                                                                                <th class="wksh-td" width="20%"> Accuracy </th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody></tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                        <div class="portlet light bordered" style="padding: 12px 20px 10px !important;">
                                                                                            <div class="portlet-title potrait-title-mar">
                                                                                                <div class="caption">
                                                                                                    <i class="icon-bar-chart font-dark hide"></i>
                                                                                                    <span class="caption-subject font-dark bold uppercase">Improvements</span>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="portlet-body" style="padding: 0px !important">
                                                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                                                    <table class="table table-hover table-light" id="asmnt-bottom-five">
                                                                                                        <thead>
                                                                                                            <tr class="uppercase">
                                                                                                                <th class="wksh-td th-desc" width="80%"> PARAMETER </th>
                                                                                                                <th class="wksh-td" width="20%"> Accuracy </th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody></tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-4" style="padding: 0px 5px 5px 5px;" id="region_performance">

                                                                                    </div>
                                                                                </div>
                                                                                <div class="clearfix"></div>
                                                                                <hr />

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!---Candidate Details-->
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-xs-12 col-sm-12">
                                                                        <div class="portlet light bordered" style="padding: 12px 20px 10px !important;">
                                                                            <div class="portlet-title potrait-title-mar">

                                                                                <h3 class="kt-portlet__head-title">

                                                                                </h3>
                                                                                <div id="participants_html"></div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="responsive-modal1" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" data-width="760">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                <h4 class="modal-title">Questions List</h4>
                                                                            </div>
                                                                            <div class="modal-body" id="mdl_questions">

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <div class="col-md-12 text-right ">
                                                                                    <button type="button" data-dismiss="modal" class="btn btn-default btn-cons">Close</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="responsive-video-modal" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" data-width="760">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Assessment Video</h4>
                                                                            </div>
                                                                            <div class="modal-body" id="mdl_video">
                                                                                <iframe id='dp-video' src='' frameborder='0' allow='autoplay; fullscreen; picture-in-picture;' allowFullScreen style='top: 0;left: 0;width: 100%;box-sizing: border-box;height: 500px;border-top-width: 0px;border-right-width: 0px;border-bottom-width: 0px;border-left-width: 0px;'></iframe>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <div class="col-md-12 text-right ">
                                                                                    <button type="button" data-dismiss="modal" class="btn btn-default btn-cons" onclick="stop_video()">Close</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- ---- -->
                                                                <div class="row">
                                                                    <!-- INDEX CHART -->
                                                                    <div class="col-lg-12 col-xs-12 col-sm-12">
                                                                        <div class="portlet light bordered" style="padding: 12px 20px 10px !important;">
                                                                            <div class="portlet-title potrait-title-mar">
                                                                                <div class="caption">
                                                                                    <i class="icon-bar-chart font-dark hide"></i>
                                                                                    <span class="caption-subject font-dark bold uppercase th-desc">Parameter</span>
                                                                                </div>
                                                                                <div class="actions">
                                                                                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                                                        <a href="#" class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm">
                                                                                            <input type="radio" name="rpt_period_option" class="toggle" id="opt_weekly" value="weekly">Weekly</a>
                                                                                        <a href="#" class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm ">
                                                                                            <input type="radio" name="rpt_period_option" class="toggle" id="opt_monthly" value="monthly">Monthly</a>
                                                                                        <a href="#" class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active">
                                                                                            <input type="radio" name="rpt_period_option" class="toggle" id="opt_yearly" value="yearly">Yearly</a>
                                                                                        <a data-toggle="modal" class="btn btn-circle btn-icon-only btn-default" href="#responsive-modal" style="padding: 3px 0px !important;">
                                                                                            <i class="icon-settings"></i>
                                                                                        </a>
                                                                                        <input type="hidden" id="rpt_period" name="rpt_period" value="yearly" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="portlet-body bordered">
                                                                                <div class="row margin-bottom-15" id="assessment_index" style="padding: 0px !important"></div>
                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                            <hr />
                                                                            <div class="portlet-title potrait-title-mar">
                                                                                <div class="caption">
                                                                                    <i class="icon-bar-chart font-dark hide"></i>

                                                                                    <span class="caption-subject font-dark bold uppercase ">Parameter Level Analysis</span>
                                                                                </div>
                                                                                <div class="col-md-4 pull-right">
                                                                                    <div class="form-group">
                                                                                        <label class="col-md-4" style="font-size: 15px;">Parameters</label>
                                                                                        <div class="col-md-8">
                                                                                            <select id="parameter_id" name="parameter_id" class="form-control input-sm select2" placeholder="Select Parameter" style="width: 100%" onchange="parameter_index_refresh();">
                                                                                                <option value="">All</option>
                                                                                                <?php foreach ($parameter_data as $para) { ?>
                                                                                                    <option value="<?= $para->parameter_id; ?>"><?php echo $para->parameter; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="actions">

                                                                                </div>
                                                                            </div>
                                                                            <div class="portlet-body">
                                                                                <div class="row margin-bottom-15" id="parameter_index" style="padding: 0px !important"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-12 col-xs-12 col-sm-12">
                                                                        <div class="portlet light bordered">
                                                                            <div class="portlet-title potrait-title-mar">
                                                                                <div class="caption">
                                                                                    <i class="icon-bar-chart font-dark hide"></i>
                                                                                    <span class="caption-subject font-dark bold uppercase">Region</span>
                                                                                </div>
                                                                                <div class="actions">
                                                                                    <a data-toggle="modal" class="btn btn-circle btn-default" href="#filter-modal">
                                                                                        Filter By <i class="fa fa-angle-down"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="portlet-body">
                                                                                <div class="row margin-bottom-15 owl-carousel" id="region_data" style="opacity:1!important; position: relative;">

                                                                                </div>
                                                                                <div class="arrow-row row" style="padding:0; margin-bottom: 15px;">
                                                                                    <div class="col-lg-6 col-xs-6 col-sm-6" id="btn-naxt" style="cursor: pointer; float:right; text-align: left; display: none;" onclick="dashboard_region_change(2);"><img src="<?php echo $asset_url; ?>assets/images/play-button-right.png"></div>
                                                                                    <div class="col-lg-6 col-xs-6 col-sm-6" id="btn-prev" style="cursor: pointer; float:left; text-align: right; display: none;" onclick="dashboard_region_change(1);"><img src="<?php echo $asset_url; ?>assets/images/play-button-left.png"></div>
                                                                                </div>

                                                                                <div class="clearfix"></div>
                                                                                <div class="row" id="region_table"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- SETTINGS BOX -->
                                        <div id="filter-modal" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" tabindex="-1" data-width="200">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <form id="frmModalForm" name="frmModalForm" onsubmit="return false;">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Filter By</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id='dsk' style="display: none">&nbsp;</div>
                                                            <!--  <div class="row">
                                                                    <div class="col-md-11">    
                                                                        <div class="form-group last">
                                                                            <label>Region</label>
                                                                            <select id="region_id" name="region_id[]" class="form-control input-sm select2" style="width: 100%" multiple="">
                                                                                <option value="">All Region</option>
                                                                            < ?php foreach ($region_data as $rgn) { ?>
                                                                                    <option value="< ?= $rgn->region_id; ?>">< ?php echo $rgn->region_name; ?></option>
                                                                            < ?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div> -->
                                                            <div class="row">
                                                                <div class="col-md-11">
                                                                    <div class="form-group last">
                                                                        <label>Assessment</label>
                                                                        <select id="assessment_id" name="assessment_id[]" class="form-control input-sm select2" style="width: 100%" multiple="">
                                                                            <?php foreach ($assessment_data as $val) { ?>
                                                                                <option value="<?= $val->assessment_id; ?>"><?php echo $val->assessment; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-12 text-right ">
                                                                <button type="button" class="btn btn-orange" id="btnIndexFilter" onclick="dashboard_region_refresh();">
                                                                    <span class="ladda-label">Apply</span>
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- SETTINGS BOX -->
                                        <div class="modal fade" id="LoadModalFilter" role="basic" aria-hidden="true" data-width="600">
                                            <div class="modal-dialog modal-lg" style="width:80%">
                                                <div class="modal-content">
                                                    <div class="modal-body" id="modal-body">
                                                        <img src="<?php echo $asset_url; ?>/assets/uploads/avatar/loading.gif" alt="" class="loading">
                                                        <span>
                                                            &nbsp;&nbsp;Loading... </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- SETTINGS BOX -->

                                        <div id="responsive-modal" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" tabindex="-1" data-width="200">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <form id="frmModalForm" name="frmModalForm" onsubmit="return false;">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Filter</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id='dsk' style="display: none">&nbsp;</div>
                                                            <div class="row">
                                                                <div class="col-md-11">
                                                                    <div class="form-group last">
                                                                        <label>Month</label>
                                                                        <select id="month" name="month" class="form-control input-sm select2" placeholder="Please select" onchange="getWeek()">
                                                                            <?php foreach (range(1, 12) as $month) :
                                                                                $monthPadding = str_pad($month, 2, "0", STR_PAD_LEFT);
                                                                                $fdate = date("F", strtotime(date('Y') . "-$monthPadding-01"));
                                                                                echo '<option value="' . $monthPadding . '" ' . ($monthPadding == date('m') ? 'selected' : '') . '>' . $fdate . '</option>';
                                                                            endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-11">
                                                                    <div class="form-group last">
                                                                        <label>Week</label>
                                                                        <select id="week" name="week" class="form-control input-sm select2" placeholder="Please select">

                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-11">
                                                                    <div class="form-group last">
                                                                        <label>Year</label>
                                                                        <select id="year" name="year" class="form-control input-sm select2" placeholder="Please select">
                                                                            <?php
                                                                            $fyear = 2019;
                                                                            $cyear = (int)date('Y');
                                                                            for ($i = $cyear; $i >= $fyear; $i--) {
                                                                                echo '<option value=' . $i . '>' . $i . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="col-md-12 text-right ">
                                                                <button type="button" class="btn btn-orange" id="btnIndexchartFilter">
                                                                    <span class="ladda-label">Apply</span>
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Manager dashboard end here -->
                                    </div>
                                </div>
                            </div>
                        </div>





                        <!-- Trainee Dashboard tab Start here =================================================================================================================== -->
                        <div class="tab-pane" id="trainee_reports_tab">
                            <div class="row mt-10">
                                <div class="col-md-12">
                                    <div class="panel-group accordion" id="trainee_reports_tab">
                                        <div class="panel panel-default">
                                            <div id="collapse_3_4" class="panel-collapse ">
                                                <div class="panel-body">
                                                    <!-- Trainee Dashboard Start here -->
                                                    <form id="FilterFrmTrainee" name="FilterFrmTrainee" method="post">
                                                        <div class="clearfix margin-top-10"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-xs-12 col-sm-12">
                                                                <div class="panel-group accordion" id="accordion3">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_2_1">
                                                                                    Filter Data </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_3_2_1" class="panel-collapse ">
                                                                            <div class="panel-body">
                                                                                <div class="row margin-bottom-10">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label col-md-3">Report by&nbsp;<span class="required"> * </span></label>
                                                                                            <div class="col-md-9" style="padding:0px;">
                                                                                                <select id="report_by_trainee" name="report_by_trainee" class="form-control input-sm select2_rpt" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="0">Assessment</option>
                                                                                                    <option value="1">Parameter</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label col-md-3">Report Type&nbsp;<span class="required"> * </span></label>
                                                                                            <div class="col-md-9" style="padding:0px;">
                                                                                                <select id="report_type_trainee" name="report_type_trainee" class="form-control input-sm select2_rpt" placeholder="Please Select">
                                                                                                    <option value=""></option>
                                                                                                    <?php foreach ($report_type as $rt) { ?>
                                                                                                        <option value="<?= $rt->id; ?>" <?php echo ($rt->default_selected ? 'selected' : ''); ?>><?php echo $rt->description; ?></option>

                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label col-md-3" style="padding:0px;">Trainee Name&nbsp;<span class="required"> * </span></label>
                                                                                            <div class="col-md-9" style="padding:0px;">
                                                                                                <select id="user_id_trainee" name="user_id_trainee" class="form-control input-sm select2_rpt" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="">Select Trainee</option>
                                                                                                    <?php
                                                                                                    if (isset($trainee_data)) {
                                                                                                        foreach ($trainee_data as $Tn) {
                                                                                                    ?>
                                                                                                            <option value="<?= $Tn->user_id; ?>"><?php echo $Tn->traineename; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row margin-bottom-10">
                                                                                    <div class="col-md-10">
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="col-md-offset-6 col-md-4 text-right">
                                                                                            <button type="button" class="btn blue-hoki btn-sm" onclick="dashboard_refresh_trainee()">Search</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-12 col-xs-12 col-sm-12">
                                                                <div class="portlet light bordered">
                                                                    <div class="portlet-title potrait-title-mar">
                                                                        <div class="caption">
                                                                            <i class="icon-bar-chart font-dark hide"></i>
                                                                            <span class="caption-subject font-dark bold uppercase">Quick Statistics</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class="clearfix"></div>

                                                                        <!-- STAT LIFE TIME ROW -->
                                                                        <div class="row" style="margin-top: 5px;">
                                                                            <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                <div class="dashboard-stat">
                                                                                    <div class="display">
                                                                                        <div class="number">
                                                                                            <h3 class="font-orange-sharp">
                                                                                                <span data-counter="counterup" id="total_assessment_trainee" data-value="0">0</span>
                                                                                            </h3>
                                                                                            <small>Total Assessment</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                <div class="dashboard-stat">
                                                                                    <div class="display">
                                                                                        <div class="number">
                                                                                            <h3 class="font-orange-sharp">
                                                                                                <span data-counter="counterup" id="question_answer_trainee" data-value="0">0</span>
                                                                                            </h3>
                                                                                            <small>Total Question Answered</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                <div class="dashboard-stat">
                                                                                    <div class="display">
                                                                                        <div class="number">
                                                                                            <h3 class="font-orange-sharp">
                                                                                                <span data-counter="counterup" id="average_accuracy_trainee" data-value="0">0</span>
                                                                                            </h3>
                                                                                            <small>Average Accuracy</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" style="margin-top: 5px;">
                                                                            <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                <div class="dashboard-stat">
                                                                                    <div class="display">
                                                                                        <div class="number">
                                                                                            <h3 class="font-orange-sharp">
                                                                                                <span data-counter="counterup" id="highest_accuracy_trainee" data-value="0">0</span>
                                                                                            </h3>
                                                                                            <small>Highest Accuracy</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                <div class="dashboard-stat">
                                                                                    <div class="display">
                                                                                        <div class="number">
                                                                                            <h3 class="font-orange-sharp">
                                                                                                <span data-counter="counterup" id="lowest_accuracy_trainee" data-value="0">0</span>
                                                                                            </h3>
                                                                                            <small>Lowest Accuracy</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                <div class="dashboard-stat">
                                                                                    <div class="display">
                                                                                        <div class="number">
                                                                                            <h3 class="font-orange-sharp">
                                                                                                <span data-counter="counterup" id="total_time_trainee" data-value="--">--</span>
                                                                                            </h3>
                                                                                            <small>Time Taken</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--                                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                    <div class="dashboard-stat">
                                                        <div class="display">
                                                            <div class="number">
                                                                <h3 class="font-orange-sharp">
                                                                    <span data-counter="counterup" id="attempt_taken" data-value="--">--</span>
                                                                </h3>
                                                                <small>Attempts taken per Assessment</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
                                                                        </div>
                                                                        <div class="row" style="margin-top: 5px;">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                <div class="portlet light bordered" style="padding: 12px 20px 10px !important;">
                                                                                    <div class="portlet-title potrait-title-mar">
                                                                                        <div class="caption">
                                                                                            <i class="icon-bar-chart font-dark hide"></i>
                                                                                            <span class="caption-subject font-dark bold uppercase">Strength</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="portlet-body" style="padding: 0px !important">
                                                                                        <div class="table-scrollable table-scrollable-borderless">
                                                                                            <table class="table table-hover table-light" id="asmnt-top-five_trainee">
                                                                                                <thead>
                                                                                                    <tr class="uppercase">
                                                                                                        <th class="wksh-td th-desc" width="80%"> PARAMETER </th>
                                                                                                        <th class="wksh-td" width="20%"> Accuracy </th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody></tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding: 0px 5px 5px 5px;">
                                                                                <div class="portlet light bordered" style="padding: 12px 20px 10px !important;">
                                                                                    <div class="portlet-title potrait-title-mar">
                                                                                        <div class="caption">
                                                                                            <i class="icon-bar-chart font-dark hide"></i>
                                                                                            <span class="caption-subject font-dark bold uppercase">Improvements</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="portlet-body" style="padding: 0px !important">
                                                                                        <div class="table-scrollable table-scrollable-borderless">
                                                                                            <table class="table table-hover table-light" id="asmnt-bottom-five_trainee">
                                                                                                <thead>
                                                                                                    <tr class="uppercase">
                                                                                                        <th class="wksh-td th-desc" width="80%"> PARAMETER </th>
                                                                                                        <th class="wksh-td" width="20%"> Accuracy </th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody></tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--                                                <div class="col-md-4" style="padding: 0px 5px 5px 5px;" id="region_performance">

                                                </div>-->
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                        <hr />

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <!-- INDEX CHART -->
                                                            <div class="col-lg-12 col-xs-12 col-sm-12">
                                                                <div class="portlet light bordered" style="padding: 12px 20px 10px !important;">
                                                                    <div class="portlet-title potrait-title-mar">
                                                                        <div class="caption">
                                                                            <i class="icon-bar-chart font-dark hide"></i>
                                                                            <span class="caption-subject font-dark bold uppercase th-desc">Parameter</span>
                                                                        </div>
                                                                        <div class="actions">
                                                                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                                                <a href="#" class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm">
                                                                                    <input type="radio" name="rpt_period_option" class="toggle" id="opt_weekly_trainee" value="weekly">Weekly</a>
                                                                                <a href="#" class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm ">
                                                                                    <input type="radio" name="rpt_period_option" class="toggle" id="opt_monthly_trainee" value="monthly">Monthly</a>
                                                                                <a href="#" class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active">
                                                                                    <input type="radio" name="rpt_period_option" class="toggle" id="opt_yearly_trainee" value="yearly">Yearly</a>
                                                                                <a data-toggle="modal" class="btn btn-circle btn-icon-only btn-default" href="#responsive-modal-trainee" style="padding: 3px 0px !important;">
                                                                                    <i class="icon-settings"></i>
                                                                                </a>
                                                                                <input type="hidden" id="rpt_period_trainee" name="rpt_period_trainee" value="yearly" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body bordered">
                                                                        <div class="row margin-bottom-15" id="assessment_index_trainee" style="padding: 0px !important"></div>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <hr />
                                                                    <div class="portlet-title potrait-title-mar">
                                                                        <div class="caption">
                                                                            <i class="icon-bar-chart font-dark hide"></i>
                                                                            <span class="caption-subject font-dark bold uppercase ">Parameter Level Analysis</span>
                                                                        </div>
                                                                        <div class="col-md-4 pull-right">
                                                                            <div class="form-group">
                                                                                <label class="col-md-4" style="font-size: 15px;">Assessment</label>
                                                                                <div class="col-md-8">
                                                                                    <select id="parameter_id_trainee" name="parameter_id_trainee" class="form-control input-sm select2" placeholder="Select Parameter" style="width: 100%" onchange="parameter_index_refresh();">
                                                                                        <option value="">All</option>
                                                                                        <?php foreach ($parameter_data as $para) { ?>
                                                                                            <option value="<?= $para->assessment_id; ?>"><?php echo $para->assessment; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="actions">

                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class="row margin-bottom-15" id="parameter_index_trainee" style="padding: 0px !important"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- SETTINGS BOX -->
                                                    <div id="responsive-modal-trainee" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" tabindex="-1" data-width="200">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <form id="frmModalForm2" name="frmModalForm2" onsubmit="return false;">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                        <h4 class="modal-title">Filter</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div id='dsk' style="display: none">&nbsp;</div>
                                                                        <div class="row">
                                                                            <div class="col-md-11">
                                                                                <div class="form-group last">
                                                                                    <label>Month</label>
                                                                                    <select id="month" name="month" class="form-control input-sm select2" placeholder="Please select" onchange="getWeekTrainee()">
                                                                                        <?php foreach (range(1, 12) as $month) :
                                                                                            $monthPadding = str_pad($month, 2, "0", STR_PAD_LEFT);
                                                                                            $fdate = date("F", strtotime(date('Y') . "-$monthPadding-01"));
                                                                                            echo '<option value="' . $monthPadding . '" ' . ($monthPadding == date('m') ? 'selected' : '') . '>' . $fdate . '</option>';
                                                                                        endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-11">
                                                                                <div class="form-group last">
                                                                                    <label>Week</label>
                                                                                    <select id="week" name="week" class="form-control input-sm select2" placeholder="Please select">

                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-11">
                                                                                <div class="form-group last">
                                                                                    <label>Year</label>
                                                                                    <select id="year" name="year" class="form-control input-sm select2" placeholder="Please select">
                                                                                        <option value="<?php echo date('Y') ?>"><?php echo date('Y') ?></option>
                                                                                        <option value="<?php echo '2020' ?>"><?php echo '2020' ?></option>
                                                                                        <option value="<?php echo '2019' ?>"><?php echo '2019' ?></option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <div class="col-md-12 text-right ">
                                                                            <button type="button" class="btn btn-orange" id="btnIndexchartFilterTrainee">
                                                                                <span class="ladda-label">Apply</span>
                                                                            </button>

                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- SETTINGS BOX -->
                                                    <!-- Trainee Dashboard end here -->

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabs -->
            </div>
        </div>
    </div>
    </div>
    <!-- SETTINGS BOX -->
    <?php $this->load->view('inc/inc_footer_script'); ?>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/highcharts/highstock.js"></script>
    <!--        <script src="< ?php echo $asset_url;?>assets/global/highcharts/highcharts.js"></script>-->
    <script src="<?php echo $asset_url; ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <?php if ($acces_management->allow_print) { ?>
        <script src="<?php echo $asset_url; ?>assets/global/highcharts/modules/exporting.js"></script>
    <?php } ?>
    <script>
        var firsttimeload = 1;
        var Company_id = '<?php echo $company_id ?>';
        var StartDate = "<?php echo $start_date; ?>";
        var EndDate = "<?php echo $end_date; ?>";
        var base_url = "<?php echo $base_url; ?>";

        var step = 0;
        //            var owl =$("#region_data");
    </script>
    <script src="<?php echo $asset_url; ?>assets/customjs/reports_dashboard.js"></script>
    <script>
        jQuery(document).ready(function() {
            $(".select2_rpt").select2({
                placeholder: 'Please Select',
                width: '100%'
            });
            $(".select2me").select2({
                placeholder: 'All',
                width: '100%',
                allowClear: true
            });

            getWeek();
            $('#btnIndexchartFilter').click(function(event) {
                event.preventDefault();
                assessment_index_refresh();
                $('#responsive-modal').modal('toggle');
            });
            $('#opt_weekly').change(function(event) {
                event.preventDefault();
                $("#rpt_period").val('weekly');
                assessment_index_refresh();
            });
            $('#opt_monthly').change(function(event) {
                event.preventDefault();
                $("#rpt_period").val('monthly');
                assessment_index_refresh();
            });
            $('#opt_yearly').change(function(event) {
                event.preventDefault();
                $("#rpt_period").val('yearly');
                assessment_index_refresh();
            });
            if (!jQuery().daterangepicker) {
                return;
            }
            //dashboard_refresh();   
            if (jQuery().datepicker) {
                $('.date-picker').datepicker({
                    rtl: App.isRTL(),
                    orientation: "left",
                    autoclose: true,
                    format: 'dd-mm-yyyy'
                });
            }
            $('#dashboard-report-range').daterangepicker({
                "ranges": {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                "autoApply": true,
                //"linkedCalendars": false,
                //"autoUpdateInput": false,
                "locale": {
                    "format": "DD-MM-YYYY",
                    "separator": " - ",
                    "applyLabel": "Apply",
                    "cancelLabel": "Cancel",
                    "fromLabel": "From",
                    "toLabel": "To",
                    "customRangeLabel": "Custom",
                    "daysOfWeek": [
                        "Su",
                        "Mo",
                        "Tu",
                        "We",
                        "Th",
                        "Fr",
                        "Sa"
                    ],
                    "monthNames": [
                        "January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December"
                    ],
                    "firstDay": 1
                },
                //"startDate": StartDate,
                //"endDate": EndDate,
                opens: (App.isRTL() ? 'right' : 'left'),
            }, function(start, end, label) {
                if ($('#dashboard-report-range').attr('data-display-range') != '0') {
                    $('#dashboard-report-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
            });
            if ($('#dashboard-report-range').attr('data-display-range') != '0') {
                var thisYear = (new Date()).getFullYear();
                var thisMonth = (new Date()).getMonth() + 1;
                //var start = new Date("1/1/" + thisYear);
                var start = new Date(thisMonth + "/1/" + thisYear);
                // $('#dashboard-report-range span').html(moment(start.valueOf()).startOf('month').format('MMMM D, YYYY') + ' - ' + moment().endOf('month').format('MMMM D, YYYY'));
                $('#dashboard-report-range span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            }
            $('#dashboard-report-range').show();
            $('#dashboard-report-range').on('apply.daterangepicker', function(ev, picker) {
                //console.log(ev.chosenLabel);
                //console.log(picker.chosenLabel);
                $('#date_lable').text(picker.chosenLabel);
                StartDate = picker.startDate.format('DD-MM-YYYY');
                EndDate = picker.endDate.format('DD-MM-YYYY');

                //getDatewiseAssessment();
                //getDatewiseRegion();
                dashboard_refresh();
                // dashboard_refresh_trainee();
            });
            setTimeout(function() {
                dashboard_refresh();
            }, 2000);
        });
        //Addded
        getAssessmentwiseData();

        function getAssessmentwiseData() {
            var compnay_id = $('#company_id').val();
            if (compnay_id == "") {
                return false;
            }
            $.ajax({
                type: "POST",
                data: {
                    company_id: compnay_id,
                    userid_id: $('#supervisor_id').val(),
                    startDate: StartDate
                },
                //async: false,
                url: "<?php echo $base_url; ?>reports_dashboard/ajax_assessmentwise_data",
                beforeSend: function() {
                    customBlockUI();
                },
                success: function(msg) {
                    if (msg != '') {
                        var Oresult = jQuery.parseJSON(msg);

                        $('#assessment_id1').empty();
                        $('#assessment_id1').append(Oresult['assessment_list_data']);
                    }
                    customunBlockUI();
                }
            });
        }

        // Trainee tab Start here
        getWeekTrainee();
        $('#btnIndexchartFilterTrainee').click(function(event) {
            event.preventDefault();
            assessment_index_refresh_trainee();
            $('#responsive-modal-trainee').modal('toggle');
        });
        $('#opt_weekly_trainee').change(function(event) {
            event.preventDefault();
            $("#rpt_period_trainee").val('weekly');
            assessment_index_refresh_trainee();
        });
        $('#opt_monthly_trainee').change(function(event) {
            event.preventDefault();
            $("#rpt_period_trainee").val('monthly');
            assessment_index_refresh_trainee();
        });
        $('#opt_yearly_trainee').change(function(event) {
            event.preventDefault();
            $("#rpt_period_trainee").val('yearly');
            assessment_index_refresh_trainee();
        });
    </script>
</body>

</html>