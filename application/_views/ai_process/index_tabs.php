<?php
defined('BASEPATH') or exit('No direct script access allowed');
$base_url = base_url();
$asset_url = $this->config->item('assets_url');
$acces_management1 = $this->session->userdata('awarathon_session');
$ismasterAdmin = ($acces_management1['username'] == 'masteradmin') ? 1 : 0;
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">

<head>
    <!--datattable CSS  Start-->
    <link href="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <!--datattable CSS  End-->
    <?php $this->load->view('inc/inc_htmlhead'); ?>
    <style>
        .table-scrollable>.table>tbody>tr>th,
        .table-scrollable>.table>tfoot>tr>td,
        .table-scrollable>.table>tfoot>tr>th,
        .table-scrollable>.table>thead>tr>th {
            white-space: normal;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #d9d9d9 !important;
            color: #fff !important;

        }

        .opt-green {
            color: #004369;
        }

        .opt-green:hover {
            background-color: #d9d9d9;
            color: #004369;
        }

        .opt-red {
            color: #db1f48;
        }

        .opt-red:hover {
            background-color: #d9d9d9;
            color: #db1f48;
        }
    </style>
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
                                <span>Jarvis</span>
                            </li>
                            <li>
                                <i class="fa fa-circle"></i>
                                <span>AI Final Report</span>
                            </li>
                        </ul>
                    </div>
                    <div class="row mt-10">
                        <div class="col-md-12">
                            <input type="hidden" id="company_id" name="company_id" value="<?php echo $company_id; ?>" />
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption caption-font-24">
                                        AI Reports
                                        <div class="tools"> </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="tabbable-line tabbable-full-width">
                                        <ul class="nav nav-tabs" id="tabs">
                                            <li class="active">
                                                <a href="#tab_ai_process" data-toggle="tab">AI Process</a>
                                            </li>
                                            <li>
                                                <a href="#tab_assessment_report" data-toggle="tab">AI Report</a>
                                            </li>
                                            <li>
                                                <a href="#tab_trainee" data-toggle="tab">Trainee Report</a>
                                            </li>
                                            <li>
                                                <a href="#tab_mapping_manager" data-toggle="tab">Manager Report</a>
                                            </li>
                                            <li>
                                                <a href="#tab_overview" data-toggle="tab">Final Report</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <!-- AI Process Start -->
                                            <div class="tab-pane active" id="tab_ai_process">
                                                <div class="row mt-10">
                                                    <div class="col-md-12">
                                                        <div class="panel-group accordion" id="accordion3">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3">
                                                                            Assessment Search </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse_3" class="panel-collapse ">
                                                                    <div class="panel-body">
                                                                        <div class="row margin-top-10 ">
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <div class="col-md-12" style="padding:0px;">
                                                                                        <input type="hidden" id="company_id" name="company_id" value="<?php echo $company_id; ?>" />
                                                                                        <label class="control-label">Assessment<span class="required" aria-required="true"> * </span></label>
                                                                                        <select id="process_assessment_id" name="process_assessment_id" class="form-control input-sm select2me" placeholder="Select" style="width: 100%;">
                                                                                            <option value="">Select</option>
                                                                                            <?php
                                                                                            if (isset($assessment)) {
                                                                                                foreach ($assessment as $adata) {
                                                                                                ?>
                                                                                                    <option value="<?php echo $adata->assessment_id; ?>"><?php echo $adata->assessment . ' - ['. $adata->assessment_type .'] - [' . $adata->status . ']'; ?></option>
                                                                                                <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- by Bhautik rana for Datepicker -->
                                                                            <?php if ($ismasterAdmin) { ?>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <div class="col-md-12" style="padding:0px;">
                                                                                                <label>Date</label>
                                                                                                <input class="form-control input-sm" id="date_picker" value="" name="date_picker" readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                            <?php } ?>
                                                                            <!-- by Bhautik rana for Datepicker -->
                                                                            <!--<div class="col-md-1" style="padding:0px 0px 0px 20px;margin:0px 0px 5px 0px;width:100px;">
                                                                                    <button id='btn_clear_process' onclick="clearTimer()" class="btn btn-sm btn-orange" disabled>Clear Process</button>&nbsp;
                                                                                </div>-->
                                                                        </div>
                                                                        <div class="row margin-top-10">
                                                                            <div class="col-lg-12" id="process_participants_table"></div>
                                                                            <div id="responsive-modal" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" data-width="760">
                                                                                <div class="modal-dialog modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                            <h4 class="modal-title">Error Logs</h4>
                                                                                        </div>
                                                                                        <div class="modal-body" id="mdl_error_log">

                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <div class="col-md-12 text-right ">
                                                                                                <button type="button" data-dismiss="modal" class="btn btn-default btn-cons">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div id="responsive-video-modal1" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" data-width="760">
                                                                                <div class="modal-dialog modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button> -->
                                                                                            <h4 class="modal-title">Assessment Video</h4>
                                                                                        </div>
                                                                                        <div class="modal-body" id="mdl_video">
                                                                                            <iframe id='dp-video1' src='' frameborder='0' allow='autoplay; fullscreen; picture-in-picture;' allowFullScreen style='top: 0;left: 0;width: 100%;box-sizing: border-box;height: 500px;border-top-width: 0px;border-right-width: 0px;border-bottom-width: 0px;border-left-width: 0px;'></iframe>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <div class="col-md-12 text-right ">
                                                                                                <button type="button" data-dismiss="modal" class="btn btn-default btn-cons" onclick="stop_video(1)">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- AI Process End -->

                                            <!-- Ai Reports Start -->
                                            <div class="tab-pane" id="tab_assessment_report">
                                                <div class="row mt-10">
                                                    <div class="col-md-12">
                                                        <div class="panel-group accordion" id="accordion3">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1">
                                                                            Report Search </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse_3_1" class="panel-collapse ">
                                                                    <div class="panel-body">
                                                                        <div class="row margin-top-10 ">
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <div class="col-md-12" style="padding:0px;">
                                                                                        <input type="hidden" id="company_id" name="company_id" value="<?php echo $company_id; ?>" />
                                                                                        <label class="control-label">Assessment<span class="required" aria-required="true"> * </span></label>
                                                                                        <select id="assessment_id" name="assessment_id" class="form-control input-sm select2me" placeholder="Select" style="width: 100%;">
                                                                                            <option value="">Select</option>
                                                                                            <?php
                                                                                            if (isset($assessment)) {
                                                                                                foreach ($assessment as $adata) {
                                                                                                ?>
                                                                                                        <option value="<?php echo $adata->assessment_id; ?>"><?php echo $adata->assessment . ' - [' . $adata->status . ']'; ?></option>
                                                                                                <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row margin-top-10">
                                                                            <div class="col-lg-12" id="participants_table"> </div>
                                                                            <div id="responsive-question-modal" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" data-width="760">
                                                                                <div class="modal-dialog modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                            <h4 class="modal-title">Questions List</h4>
                                                                                        </div>
                                                                                        <div class="modal-body" id="mdl_questions"> </div>
                                                                                        <div class="modal-footer">
                                                                                            <div class="col-md-12 text-right ">
                                                                                                <button type="button" data-dismiss="modal" class="btn btn-default btn-cons">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div id="responsive-video-modal2" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true" data-width="760">
                                                                                <div class="modal-dialog modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h4 class="modal-title">Assessment Video</h4>
                                                                                        </div>
                                                                                        <div class="modal-body" id="mdl_video">
                                                                                            <iframe id='dp-video2' src='' frameborder='0' allow='autoplay; fullscreen; picture-in-picture;' allowFullScreen style='top: 0;left: 0;width: 100%;box-sizing: border-box;height: 500px;border-top-width: 0px;border-right-width: 0px;border-bottom-width: 0px;border-left-width: 0px;'></iframe>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <div class="col-md-12 text-right ">
                                                                                                <button type="button" data-dismiss="modal" class="btn btn-default btn-cons" onclick="stop_video(2)">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Ai Reports End -->

                                            <!-- Trainee Report Start -->
                                            <div class="tab-pane" id="tab_trainee">
                                                <div class="row mt-10">
                                                    <div class="col-md-12">
                                                        <div class="panel-group accordion" id="accordion3">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_2">
                                                                            Report Search </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse_3_2" class="panel-collapse ">
                                                                    <div class="panel-body">
                                                                        <form id="frmReorts_trainee" name="frmReorts_trainee" method="post" action="<?php echo base_url() . 'ai_reports/exportReport_trainee' ?>">
                                                                            <div class="row margin-bottom-10">
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label"> Report Type<span class="required" aria-required="true"> * </span></label>
                                                                                        <select id="report_type_catg" name="report_type_catg" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" onchange="getReportwiseData_trainee()">
                                                                                            <option value="0">Please Select</option>
                                                                                            <option value="1">AI</option>
                                                                                            <option value="2">Manual</option>
                                                                                            <option value="3">AI and Manual</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label"> Assessment <span class="required" aria-required="true"> * </span></label>
                                                                                        <select id="assessment_id_trainee" name="assessment_id_trainee[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" multiple="">
                                                                                            <option value="">Please Select</option>
                                                                                            <?php
                                                                                            if (isset($assessment)) {
                                                                                                foreach ($assessment as $adata) {
                                                                                            ?>
                                                                                                    <option value="<?php echo $adata->assessment_id; ?>"><?php echo $adata->assessment . ' - [' . $adata->status . ']'; ?></option>
                                                                                            <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label"> Status &nbsp;</label>
                                                                                        <select id="status_id_trainee" name="status_id_trainee" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                            <option value="0">Completed</option>
                                                                                            <option value="1">Incompleted</option>
                                                                                            <option value="2">Overall</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row margin-bottom-10">
                                                                                <div class="col-md-6"></div>
                                                                                <div class="col-md-6">
                                                                                    <div class="col-md-offset-8 col-md-4 text-right">
                                                                                        <button type="button" class="btn blue-hoki btn-sm" onclick="DatatableRefresh_trainee()">Search</button>
                                                                                        <button type="button" class="btn blue-hoki btn-sm" onclick="ResetFilter_trainee()">Reset</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12" id="report_section_trainee">
                                                    <div class="portlet light bordered">
                                                        <div class="portlet-title">
                                                            <div class="caption caption-font-24">Assessment Report
                                                                <div class="tools"> </div>
                                                            </div>
                                                            <?php if ($acces_management->allow_export) { ?>
                                                                <div class="actions">
                                                                    <div class="btn-group pull-right">
                                                                        <button type="button" onclick="exportConfirm_trainee()" name="export_excel_trainee" id="export_excel_trainee" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                                                                        &nbsp;&nbsp;
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="clearfix margin-top-20"></div>
                                                        <div class="portlet-body">
                                                            <table class="table table-bordered table-hover table-checkable order-column" id="index_table_trainee">
                                                                <thead>
                                                                    <tr>
                                                                        <th>E code</th>
                                                                        <th>Employee Name</th>
                                                                        <th>Date of Join</th>
                                                                        <th>Email</th>
                                                                        <th>Assessment</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-10" id="report_section_trainee"></div>
                                            </div>
                                            <!-- Trainee Report End -->

                                            <!-- Manager Report Start -->
                                            <div class="tab-pane" id="tab_mapping_manager">
                                                <div class="row mt-10">
                                                    <div class="col-md-12">
                                                        <div class="panel-group accordion" id="accordion3">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_3">
                                                                            Report Search </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse_3_3" class="panel-collapse ">
                                                                    <div class="panel-body">
                                                                        <form id="frmReorts_manager" name="frmReorts_manager" method="post" action="<?php echo base_url() . 'ai_reports/exportReport_manager' ?>">
                                                                            <div class="row margin-bottom-10">
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label"> Assessment <span class="required" aria-required="true"> * </span></label>
                                                                                        <select id="assessment_id_manager" name="assessment_id_manager[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" multiple="">
                                                                                            <option value="">Please Select</option>
                                                                                            <?php
                                                                                            if (isset($assessment_manager)) {
                                                                                                foreach ($assessment_manager as $adata) {
                                                                                            ?>
                                                                                                    <option value="<?= $adata->assessment_id; ?>"><?php echo $adata->assessment . ' - [' . $adata->status . ']'; ?></option>
                                                                                            <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label"> Status</label>
                                                                                        <select id="status_id_manager" name="status_id_manager" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                            <option value="0"> Completed</option>
                                                                                            <option value="1">Incompleted</option>
                                                                                            <option value="2">Overall</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row margin-bottom-10">
                                                                                <div class="col-md-6"></div>
                                                                                <div class="col-md-6">
                                                                                    <div class="col-md-offset-8 col-md-4 text-right">
                                                                                        <button type="button" class="btn blue-hoki btn-sm" onclick="DatatableRefresh_manager()">Search</button>
                                                                                        <button type="button" class="btn blue-hoki btn-sm" onclick="ResetFilter_manager()">Reset</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" id="report_section_manager">
                                                        <div class="portlet light bordered">
                                                            <div class="portlet-title">
                                                                <div class="caption caption-font-24">
                                                                    Assessment Report
                                                                    <div class="tools"> </div>
                                                                </div>
                                                                <?php if ($acces_management->allow_export) { ?>
                                                                    <div class="actions">
                                                                        <div class="btn-group pull-right">
                                                                            <button type="button" onclick="exportConfirm_manager()" name="export_excel_manager" id="export_excel_manager" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                                                                            &nbsp;&nbsp;
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="clearfix margin-top-20"></div>
                                                            <div class="portlet-body">
                                                                <table class="table  table-bordered table-hover table-checkable order-column" id="index_table_manager">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>E code</th>
                                                                            <th>Employee Name</th>
                                                                            <th>Email</th>
                                                                            <th>Employee Status</th>
                                                                            <th>Manager Name</th>
                                                                            <th>Manager Status</th>
                                                                            <th>Assessment Name</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-10" id="report_section_manager"></div>
                                            </div>
                                            <!-- Manager Report End -->


                                            <!-- Changes by Bhautik rana  -->
                                            <!-- Final Report Start -->
                                            <div class="tab-pane " id="tab_overview">
                                                <ul class="nav nav-tabs" id="tabs">
                                                    <li class="active">
                                                        <a href="#tab_division_wise" data-toggle="tab">Division</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_region_wise" data-toggle="tab">Region</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_manager_wise" data-toggle="tab">Manager</a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_assessment_wise" data-toggle="tab">Assessment</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <!-- Division Wise data -->
                                                    <div class="tab-pane active" id="tab_division_wise">
                                                        <div class="row mt-10">
                                                            <div class="col-md-12">
                                                                <div class="panel-group accordion" id="tab_division_wise">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_4">
                                                                                    Report Search </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_3_4" class="panel-collapse ">
                                                                            <div class="panel-body">
                                                                                <form id="frmReorts" name="frmReorts" method="post" action="<?php echo base_url() . 'ai_reports/exportReport' ?>">
                                                                                    <input type="hidden" name='form_name' id="form_name" value='div_form'>
                                                                                    <div class="row margin-bottom-10">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Division<span class="required" aria-required="true"> * <span><span style="position: absolute;top: 4px;left: 10px;color: red;font-size: 8px;"> (Mandatory)</span></label>
                                                                                                <select id="department_id" name="department_id[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" onchange="get_department_wise_data()" multiple="">
                                                                                                    <?php
                                                                                                    if (isset($department)) {
                                                                                                        foreach ($department as $ddate) {
                                                                                                    ?>
                                                                                                            <option value="<?= $ddate->department; ?>"><?php echo $ddate->department; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Manager Name &nbsp;</label>
                                                                                                <select id="trainer_id" name="trainer_id[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" multiple="">

                                                                                                    <?php
                                                                                                    if (isset($manager)) {
                                                                                                        foreach ($manager as $mdata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $mdata->trainer_id; ?>"><?php echo '[' . $mdata->trainer_id . '] - ' . $mdata->fullname; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- <div class="row margin-bottom-10"> -->
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Assessment</label>
                                                                                                <select id="assessment_id3" name="assessment_id3[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" multiple="">
                                                                                                    <?php
                                                                                                    if (isset($assessment)) {
                                                                                                        foreach ($assessment as $adata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $adata->assessment_id; ?>"><?php echo $adata->assessment . ' - [' . $adata->status . ']'; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Reps &nbsp;</label>
                                                                                                <select id="user_id" name="user_id" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="">All Employee</option>
                                                                                                    <?php
                                                                                                    if (isset($user_details)) {
                                                                                                        foreach ($user_details as $Rdata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $Rdata->user_id; ?>"><?php echo $Rdata->user_name; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="row margin-bottom-10">
                                                                                        <!-- By Bhautik Rana new option add -->
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Report Type </label>
                                                                                                <select id="report_type" name="report_type" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <?php echo '<option value="0">Please Select</option>'; ?>
                                                                                                    <option value="1">AI</option>
                                                                                                    <option value="2">Manual</option>
                                                                                                    <option value="3">AI and Manual</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Status &nbsp;</label>
                                                                                                <select id="status_id" name="status_id" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="0">Completed</option>
                                                                                                    <option value="1">Incompleted</option>
                                                                                                    <option value="2">Overall</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="col-md-offset-8 col-md-4 text-right" style="margin-top: 30px;">
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="check_div()">Search</button>
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="ResetFilter()">Reset</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12" id="report_section">
                                                                <div class="portlet light bordered">
                                                                    <div class="portlet-title">
                                                                        <div class="caption caption-font-24">
                                                                            Assessment Report
                                                                            <div class="tools"> </div>
                                                                        </div>
                                                                        <?php if ($acces_management->allow_export) { ?>
                                                                            <div class="actions">
                                                                                <div class="btn-group pull-right">
                                                                                    <button type="button" onclick="exportConfirm()" name="export_excel" id="export_excel" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                                                                                    &nbsp;&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <div class="clearfix margin-top-20"></div>
                                                                        <div class="portlet-body" id="skill-table"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Final Report End -->
                                                    </div>
                                                    <!-- Region Wise data -->
                                                    <div class="tab-pane" id="tab_region_wise">
                                                        <div class="row mt-10">
                                                            <div class="col-md-12">
                                                                <div class="panel-group accordion" id="tab_region_wise">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_4">
                                                                                    Report Search </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_3_4" class="panel-collapse ">
                                                                            <div class="panel-body">
                                                                                <form id="frmReport_region" name="frmReport_region" method="post" action="<?php echo base_url() . 'ai_reports/exportReport' ?>">
                                                                                    <input type="hidden" name='form_name' id="form_name" value='reg_form'>
                                                                                    <div class="row margin-bottom-10">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Region<span class="required" aria-required="true"> * <span><span style="position: absolute;top: 4px;left: 10px;color: red;font-size: 8px;"> (Mandatory)</span></label>
                                                                                                <select id="region_id" name="region_id[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" onchange="get_region_wise_data()" multiple="">
                                                                                                    <?php
                                                                                                    if (isset($region)) {
                                                                                                        foreach ($region as $rg) {
                                                                                                    ?>
                                                                                                            <option value="<?= $rg->region_id; ?>"><?php echo $rg->region_name; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Manager Name &nbsp;</label>
                                                                                                <select id="trainer_id_region_wise" name="trainer_id_region_wise[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" multiple="">

                                                                                                    <?php
                                                                                                    if (isset($manager)) {
                                                                                                        foreach ($manager as $mdata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $mdata->trainer_id; ?>"><?php echo '[' . $mdata->trainer_id . '] - ' . $mdata->fullname; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- <div class="row margin-bottom-10"> -->
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Assessment</label>
                                                                                                <select id="assessment_id3_region_wise" name="assessment_id3_region_wise[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" multiple="">
                                                                                                    <?php
                                                                                                    if (isset($assessment)) {
                                                                                                        foreach ($assessment as $adata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $adata->assessment_id; ?>"><?php echo $adata->assessment . ' - [' . $adata->status . ']'; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Employee Name &nbsp;</label>
                                                                                                <select id="user_id_region_wise" name="user_id_region_wise" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="">All Employee</option>
                                                                                                    <?php
                                                                                                    if (isset($user_details)) {
                                                                                                        foreach ($user_details as $Rdata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $Rdata->user_id; ?>"><?php echo $Rdata->user_name; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="row margin-bottom-10">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Report Type </label>
                                                                                                <select id="report_type_region_wise" name="report_type_region_wise" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <?php echo '<option value="0">Please Select</option>'; ?>
                                                                                                    <option value="1">AI</option>
                                                                                                    <option value="2">Manual</option>
                                                                                                    <option value="3">AI and Manual</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Status &nbsp;</label>
                                                                                                <select id="status_id_region_wise" name="status_id_region_wise" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="0">Completed</option>
                                                                                                    <option value="1">Incompleted</option>
                                                                                                    <option value="2">Overall</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="col-md-offset-8 col-md-4 text-right" style="margin-top: 30px;">
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="check_reg()">Search</button>
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="ResetFilter()">Reset</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12" id="region_section">
                                                                <div class="portlet light bordered">
                                                                    <div class="portlet-title">
                                                                        <div class="caption caption-font-24">
                                                                            Assessment Report
                                                                            <div class="tools"> </div>
                                                                        </div>
                                                                        <?php if ($acces_management->allow_export) { ?>
                                                                            <div class="actions">
                                                                                <div class="btn-group pull-right">
                                                                                    <button type="button" onclick="exportConfirmRegion()" name="export_excel" id="export_excel" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                                                                                    &nbsp;&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <div class="clearfix margin-top-20"></div>
                                                                        <div class="portlet-body" style="overflow-y: hidden; overflow-x:hidden;" id="region_table"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- region Wise Data end -->
                                                    <!-- Manager wise data -->
                                                    <div class="tab-pane" id="tab_manager_wise">
                                                        <div class="row mt-10">
                                                            <div class="col-md-12">
                                                                <div class="panel-group accordion" id="tab_manager_wise">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_4">
                                                                                    Report Search </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_3_4" class="panel-collapse ">
                                                                            <div class="panel-body">
                                                                                <form id="ManagerfrmReorts" name="ManagerfrmReorts" method="post" action="<?php echo base_url() . 'ai_reports/exportReport' ?>">
                                                                                    <input type="hidden" name='form_name' id="form_name" value='man_form'>
                                                                                    <div class="row margin-bottom-10">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Manager Name &nbsp;<span class="required" aria-required="true"> * <span><span style="position: absolute;top: 4px;left: 10px;color: red;font-size: 8px;"> (Mandatory)</span></label>
                                                                                                <select id="managerid" name="managerid[]" class="form-control input-sm select2me" onchange="get_manager_wise_data()" placeholder="Please select" style="width: 100%" multiple="">
                                                                                                    <?php
                                                                                                    if (isset($manager)) {
                                                                                                        foreach ($manager as $mdata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $mdata->trainer_id; ?>"><?php echo '[' . $mdata->trainer_id . '] - ' . $mdata->fullname; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- <div class="row margin-bottom-10"> -->
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Assessment</label>
                                                                                                <select id="assessment_id3_manager" name="assessment_id3_manager[]" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%" multiple="">
                                                                                                    <?php
                                                                                                    if (isset($assessment)) {
                                                                                                        foreach ($assessment as $adata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $adata->assessment_id; ?>"><?php echo $adata->assessment . ' - [' . $adata->status . ']'; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Employee Name &nbsp;</label>
                                                                                                <select id="user_id_manager" name="user_id_manager" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="">All Employee</option>
                                                                                                    <?php
                                                                                                    if (isset($user_details)) {
                                                                                                        foreach ($user_details as $Rdata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $Rdata->user_id; ?>"><?php echo $Rdata->user_name; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Report Type </label>
                                                                                                <select id="report_type_manager" name="report_type_manager" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <?php echo '<option value="0">Please Select</option>'; ?>
                                                                                                    <option value="1">AI</option>
                                                                                                    <option value="2">Manual</option>
                                                                                                    <option value="3">AI and Manual</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row margin-bottom-10">
                                                                                        <!-- By Bhautik Rana new option add -->

                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Status &nbsp;</label>
                                                                                                <select id="status_id_manager" name="status_id_manager" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="0">Completed</option>
                                                                                                    <option value="1">Incompleted</option>
                                                                                                    <option value="2">Overall</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3"></div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="col-md-offset-8 col-md-4 text-right" style="margin-top: 30px;">
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="check_manager()">Search</button>
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="ResetFilter()">Reset</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12" id="trainer_section">
                                                                <div class="portlet light bordered">
                                                                    <div class="portlet-title">
                                                                        <div class="caption caption-font-24">
                                                                            Assessment Report
                                                                            <div class="tools"> </div>
                                                                        </div>
                                                                        <?php if ($acces_management->allow_export) { ?>
                                                                            <div class="actions">
                                                                                <div class="btn-group pull-right">
                                                                                    <button type="button" onclick="exportConfirmTrainer()" name="export_excel" id="export_excel" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                                                                                    &nbsp;&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <div class="clearfix margin-top-20"></div>
                                                                        <div class="portlet-body" id="manager_table"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Manager wise data end -->
                                                    <!-- Assessment wise data -->
                                                    <div class="tab-pane" id="tab_assessment_wise">
                                                        <div class="row mt-10">
                                                            <div class="col-md-12">
                                                                <div class="panel-group accordion" id="tab_assessment_wise">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_4">
                                                                                    Report Search </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_3_4" class="panel-collapse ">
                                                                            <div class="panel-body">
                                                                                <form id="frmReortsAssessment" name="frmReortsAssessment" method="post" action="<?php echo base_url() . 'ai_reports/exportReport' ?>">
                                                                                    <input type="hidden" name='form_name' id="form_name" value='ass_form'>
                                                                                    <div class="row margin-bottom-10">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Assessment <span class="required" aria-required="true"> * <span><span style="position: absolute;top: 4px;left: 10px;color: red;font-size: 8px;"> (Mandatory)</span></label>
                                                                                                <select id="ass_id" name="ass_id[]" class="form-control input-sm select2me" onchange="get_ass_wise_data()" placeholder="Please select" style="width: 100%" multiple="">
                                                                                                    <?php
                                                                                                    if (isset($assessment)) {
                                                                                                        foreach ($assessment as $adata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $adata->assessment_id; ?>"><?php echo $adata->assessment . ' - [' . $adata->status . ']'; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label">Employee Name &nbsp;</label>
                                                                                                <select id="user_id_ass" name="user_id_ass" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="">All Employee</option>
                                                                                                    <?php
                                                                                                    if (isset($user_details)) {
                                                                                                        foreach ($user_details as $Rdata) {
                                                                                                    ?>
                                                                                                            <option value="<?= $Rdata->user_id; ?>"><?php echo $Rdata->user_name; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Report Type </label>
                                                                                                <select id="report_type_ass" name="report_type_ass" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <?php echo '<option value="0">Please Select</option>'; ?>
                                                                                                    <option value="1">AI</option>
                                                                                                    <option value="2">Manual</option>
                                                                                                    <option value="3">AI and Manual</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label class="control-label"> Status &nbsp;</label>
                                                                                                <select id="status_id_ass" name="status_id_ass" class="form-control input-sm select2me" placeholder="Please select" style="width: 100%">
                                                                                                    <option value="0">Completed</option>
                                                                                                    <option value="1">Incompleted</option>
                                                                                                    <option value="2">Overall</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3"></div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="col-md-offset-8 col-md-4 text-right" style="margin-top: 30px;">
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="check_ass()">Search</button>
                                                                                                <button type="button" class="btn blue-hoki btn-sm" onclick="ResetFilter()">Reset</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12" id="report_section_assessment">
                                                                <div class="portlet light bordered">
                                                                    <div class="portlet-title">
                                                                        <div class="caption caption-font-24">
                                                                            Assessment Report
                                                                            <div class="tools"> </div>
                                                                        </div>
                                                                        <?php if ($acces_management->allow_export) { ?>
                                                                            <div class="actions">
                                                                                <div class="btn-group pull-right">
                                                                                    <button type="button" onclick="exportConfirmAssessment()" name="export_excel" id="export_excel" class="btn orange btn-sm btn-outline"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                                                                                    &nbsp;&nbsp;
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <div class="clearfix margin-top-20"></div>
                                                                        <div class="portlet-body" id="assessment_table"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Assessment wise data -->
                                                </div>
                                            </div>
                                            <!-- Division Wise Data end -->
                                        </div>
                                        <!-- Changes by Bhautik rana  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('inc/inc_footer_script'); ?>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/customjs/ai_reports.js" type="text/javascript"></script>
    <script>
        var base_url = '<?php echo base_url(); ?>';
        var json_participants = [];

        function format_assessment_data(data) {
            var result = data.text;
            if (result.substr(-6) == "[Live]") {
                var $opt_data = $('<option class="opt-green">' + data.text + '</option>');
                return $opt_data;
            } else if (result.substr(-9) == "[Expired]") {
                var $opt_data = $('<option class="opt-red">' + data.text + '</option>');
                return $opt_data;
            } else {
                return $opt_data;
            }
        }
        jQuery(document).ready(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr("href") // activated tab
                if (target !== '#tab_ai_process') {
                    clearTimer(); //clear all AI process timers 
                }
            });
            $('#assessment_id,#process_assessment_id').select2({
                placeholder: "Select",
                width: '100%',
                allowClear: true,
                templateResult: format_assessment_data
            });
            $("#process_assessment_id").change(function() {
                fetch_process_participants();
            });
            $("#assessment_id").change(function() {
                fetch_participants();
            });
            //  Changes by Bhautik rana  
            $('#report_section_trainee').hide();
            $('#report_section').hide();
            $('#region_section').hide();
            $('#trainer_section').hide();
            $('#report_section_manager').hide();
            $('#report_section_assessment').hide();
            // Changes by Bhautik rana  
        });
        var frmReorts = document.frmReorts;
        var frmReorts_manager = document.frmReorts_manager;
        var frmReorts_trainee = document.frmReorts_trainee;
        var frmReortsAssessment = document.frmReortsAssessment;
        var ManagerfrmReorts = document.ManagerfrmReorts;
        var frmReport_region = document.frmReport_region;
        // Changes by Bhautik rana  

        function getReportwiseData_trainee() {
            $('#assessment_id_trainee').empty();
            var compnay_id = $('#company_id').val();
            if (compnay_id == "") {
                return false;
            }
            $.ajax({
                type: "POST",
                data: {
                    company_id: compnay_id,
                    report_type_catg: $('#report_type_catg').val()
                },
                //async: false,
                url: "<?php echo $base_url; ?>ai_reports/report_assessment_trainee",
                beforeSend: function() {
                    customBlockUI();
                },
                success: function(msg) {
                    if (msg != '') {
                        var Oresult = jQuery.parseJSON(msg);
                        $('#assessment_id_trainee').empty();
                        $('#assessment_id_trainee').append(Oresult['assessment_list_data_trainee']);
                    }
                    customunBlockUI();
                }
            });
        }

        function DatatableRefresh_trainee() {
            if ($("#assessment_id_trainee").val() == 0 || $("#assessment_id_trainee").val() == "" || $("#assessment_id_trainee").val() == null) {
                ShowAlret("Please select assessment first.!!", 'error');
                return false;
            }
            $('#report_section_trainee').show();
            var table = $('#index_table_trainee');
            table.dataTable({
                destroy: true,
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "No records found",
                    "infoFiltered": "(filtered1 from _MAX_ total records)",
                    "lengthMenu": "Show _MENU_",
                    "search": "Search:",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },

                "bStateSave": false,
                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                "pageLength": 10,
                "paging": true,
                "pagingType": "bootstrap_full_number",
                "columnDefs": [{
                        'className': 'dt-head-left dt-body-left',
                        'width': '50px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [0]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [1]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [2]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [3]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '80px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [4]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [5]
                    },
                ],
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                //"serverSide": true,
                "serverSide": false,
                "sAjaxSource": "<?php echo base_url() . 'ai_reports/generate_report_trainee'; ?>",
                "fnServerData": function(sSource, aoData, fnCallback) {

                    aoData.push({
                        name: 'assessment_id_trainee',
                        value: $('#assessment_id_trainee').val()
                    });
                    aoData.push({
                        name: 'status_id_trainee',
                        value: $('#status_id_trainee').val()
                    });
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json);
                    });
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    return nRow;
                },
                "fnFooterCallback": function(nRow, aData) {},
                "initComplete": function(settings, json) {
                    $('thead > tr> th:nth-child(1)').css({
                        'min-width': '100px',
                        'max-width': '100px'
                    });
                }
            });
        }



        function DatatableRefresh_manager() {
            $('#report_section_manager').hide();
            console.log($("#assessment_id_manager").val());
            if ($("#assessment_id_manager").val() == 0 || $("#assessment_id_manager").val() == "" || $("#assessment_id_manager").val() == null) {
                ShowAlret("Please select assessment first.!!", 'error');
                return false;
            }
            $('#report_section_manager').show();

            var table = $('#index_table_manager');
            table.dataTable({
                destroy: true,
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "No records found",
                    "infoFiltered": "(filtered1 from _MAX_ total records)",
                    "lengthMenu": "Show _MENU_",
                    "search": "Search:",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },

                "bStateSave": false,
                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                "pageLength": 10,
                "paging": true,
                "pagingType": "bootstrap_full_number",
                "columnDefs": [


                ],
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                // "serverSide": true,
                "serverSide": false,
                "sAjaxSource": "<?php echo base_url() . 'ai_reports/generate_report_manager'; ?>",
                "fnServerData": function(sSource, aoData, fnCallback) {
                    aoData.push({
                        name: 'manager_id',
                        value: $('#manager_id').val()
                    });
                    aoData.push({
                        name: 'assessment_id_manager',
                        value: $('#assessment_id_manager').val()
                    });
                    aoData.push({
                        name: 'status_id_manager',
                        value: $('#status_id_manager').val()
                    });
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json);
                    });
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    return nRow;
                },
                "fnFooterCallback": function(nRow, aData) {},
                "initComplete": function(settings, json) {
                    $('thead > tr> th:nth-child(1 )').css({
                        'min-width': '100px',
                        'max-width': '100px'
                    });
                }
            });
        }

        function getReportwiseData() {
            $('#assessment_id3').empty();
            var compnay_id = $('#company_id').val();
            if (compnay_id == "") {
                return false;
            }
            $.ajax({
                type: "POST",
                data: {
                    company_id: compnay_id,
                    report_type: $('#report_type').val()
                },
                //async: false,
                url: "<?php echo $base_url; ?>ai_reports/report_wise_assessment",
                beforeSend: function() {
                    customBlockUI();
                },
                success: function(msg) {
                    if (msg != '') {
                        var Oresult = jQuery.parseJSON(msg);
                        $('#assessment_id3').empty();
                        $('#assessment_id3').append(Oresult['assessment_list_data']);
                    }
                    customunBlockUI();
                }
            });
        }

        function ResetFilter() {
            $('.select2me,.select2_rpt2').select("val", "");
            $('.select2me,.select2_rpt2').val(null).trigger('change');
            document.frmReorts.reset();
        }

        function ResetFilter_manager() {
            $('.select2me,.select2_rpt2').select("val", "");
            $('.select2me,.select2_rpt2').val(null).trigger('change');
            document.frmReorts_manager.reset();
        }

        function ResetFilter_trainee() {
            $('.select2me,.select2_rpt2').select("val", "");
            $('.select2me,.select2_rpt2').val(null).trigger('change');
            document.frmReorts_trainee.reset();
        }

        function exportConfirm() {
            var compnay_id = $('#company_id').val();
            var assessment_id = $('#assessment_id').val();

            if (compnay_id == "") {
                ShowAlret("Please select Company first.!!", 'error');
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
                            frmReorts.submit();
                        }
                    },
                    cancel: function() {
                        this.onClose();
                    }
                }
            });
        }

        // Changes by Bhautik rana 14-03-2023  
        function exportConfirmRegion() {
            var compnay_id = $('#company_id').val();
            var region_id = $('#region_id').val();

            if (compnay_id == "") {
                ShowAlret("Please select Company first.!!", 'error');
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
                            frmReport_region.submit();
                        }
                    },
                    cancel: function() {
                        this.onClose();
                    }
                }
            });
        }

        function exportConfirmTrainer() {
            var compnay_id = $('#company_id').val();
            var managerid = $('#managerid').val();

            if (compnay_id == "") {
                ShowAlret("Please select Company first.!!", 'error');
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
                            ManagerfrmReorts.submit();
                        }
                    },
                    cancel: function() {
                        this.onClose();
                    }
                }
            });
        }

        function exportConfirmAssessment() {
            var compnay_id = $('#company_id').val();
            var managerid = $('#managerid').val();

            if (compnay_id == "") {
                ShowAlret("Please select Company first.!!", 'error');
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
                            frmReortsAssessment.submit();
                        }
                    },
                    cancel: function() {
                        this.onClose();
                    }
                }
            });
        }

        function exportConfirm_manager() {
            var compnay_id = $('#company_id').val();
            var assessment_id = $('#assessment_id_manager').val();

            if (compnay_id == "") {
                ShowAlret("Please select Company first.!!", 'error');
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
                            frmReorts_manager.submit();
                        }
                    },
                    cancel: function() {
                        this.onClose();
                    }
                }
            });
        }

        // Changes by Bhautik rana 14-03-2023  

        function exportConfirm_trainee() {
            var compnay_id = $('#company_id').val();
            var assessment_id = $('#assessment_id_trainee').val();

            if (compnay_id == "") {
                ShowAlret("Please select Company first.!!", 'error');
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
                            frmReorts_trainee.submit();
                        }
                    },
                    cancel: function() {
                        this.onClose();
                    }
                }
            });
        }

        // Changes by Bhautik rana 14-03-2023  
        function get_department_wise_data() {
            $.ajax({
                type: "POST",
                data: {
                    department_id: $('#department_id').val()
                },
                //async: false,
                url: "<?php echo $base_url; ?>ai_reports/ajax_assessmentwise_data",
                beforeSend: function() {
                    customBlockUI();
                },
                success: function(msg) {
                    if (msg != '') {
                        var Oresult = jQuery.parseJSON(msg);

                        $('#user_id').empty();
                        $('#user_id').append(Oresult['user_list_data']);
                        $('#assessment_id3').empty();
                        $('#assessment_id3').append(Oresult['assessment_list_data']);
                        $('#trainer_id').empty();
                        $('#trainer_id').append(Oresult['manager_list_data']);
                    }
                    customunBlockUI();
                }
            });
        }




        function getAssessmentwiseData_manager() {
            $.ajax({
                type: "POST",
                data: {
                    assessment_id_manager: $('#assessment_id_manager').val()
                },
                //async: false,
                url: "<?php echo $base_url; ?>ai_reports/ajax_assessmentwise_data_manager",
                beforeSend: function() {
                    customBlockUI();
                },
                success: function(msg) {
                    if (msg != '') {
                        var Oresult = jQuery.parseJSON(msg);
                        $('#manager_id').empty();
                        $('#manager_id').append(Oresult['assessment_list_data']);
                    }
                    customunBlockUI();
                }
            });
        }
        // Division Functions 
        function check_div() {
            var division = $('#department_id').val();
            if (division == null) {
                ShowAlret("Please select Division first.!!", 'error');
                return false;
            } else {
                refreshTableColumnDiv()
            }
        }

        function refreshTableColumnDiv() {
            var assessment_id = $("#assessment_id3").val();
            var department_id = $("#department_id").val();
            $.ajax({
                url: '<?= base_url() . 'ai_reports/generate_header'; ?>',
                type: 'POST',
                data: {
                    'assessment_id': assessment_id,
                    'department_id': department_id,
                },
                success: function(data) {
                    $('#report_section').show();
                    $('#skill-table').html(data);
                    DatatableRefresh();
                },
                error: function(data) {
                    alert(data);
                }
            });
        }

        function DatatableRefresh() {
            // if ($("#assessment_id3").val() == 0 || $("#assessment_id3").val() == "") {
            //     ShowAlret("Please select assessment first.!!", 'error');
            //     return false;
            // }
            $('#report_section').show();
            $('#report_section_manager').hide();
            var table = $('#index_table');
            table.dataTable({
                destroy: true,
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "No records found",
                    "infoFiltered": "(filtered1 from _MAX_ total records)",
                    "lengthMenu": "Show _MENU_",
                    "search": "Search:",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },

                "bStateSave": false,
                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                "pageLength": 10,
                "paging": true,
                "pagingType": "bootstrap_full_number",
                "columnDefs": [{
                        'className': 'dt-head-left dt-body-left',
                        'width': '50px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [0]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [1]
                    },
                    // {'className': 'dt-head-left dt-body-left','width': '100px','orderable': true,'searchable': true,'targets': [2]},
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [2]
                    },
                    // {'className': 'dt-head-left dt-body-left','width': '80px','orderable': false,'searchable': false,'targets': [4]},
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': true,
                        'searchable': false,
                        'targets': [3]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [4]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [5]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [6]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [7]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [8]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [9]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '180px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [10]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '250px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [11]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '80px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [12]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [13]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [14]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [15]
                    }
                    // {'className': 'dt-head-left dt-body-left','width': '130px','orderable': false,'searchable': false,'targets': [15]},
                    // {'className': 'dt-head-left dt-body-left','width': '130px','orderable': false,'searchable': false,'targets': [16]}

                ],
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                //"serverSide": true,
                "serverSide": false,
                "sAjaxSource": "<?php echo base_url() . 'ai_reports/generate_report'; ?>",
                "fnServerData": function(sSource, aoData, fnCallback) {
                    aoData.push({
                        name: 'user_id',
                        value: $('#user_id').val()
                    });
                    aoData.push({
                        name: 'assessment_id',
                        value: $('#assessment_id3').val()
                    });
                    aoData.push({
                        name: 'status_id',
                        value: $('#status_id').val()
                    });
                    aoData.push({
                        name: 'trainer_id',
                        value: $('#trainer_id').val()
                    });
                    aoData.push({
                        name: 'report_type',
                        value: $('#report_type').val()
                    });
                    aoData.push({
                        name: 'department_name',
                        value: $('#department_id').val()
                    });
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json);
                    });
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    return nRow;
                },
                "fnFooterCallback": function(nRow, aData) {},
                "initComplete": function(settings, json) {
                    $('thead > tr> th:nth-child(1)').css({
                        'min-width': '100px',
                        'max-width': '100px'
                    });
                }
            });
        }
        // Division Functions end

        // Region Functions 
        function get_region_wise_data() {
            $.ajax({
                type: "POST",
                data: {
                    region_id: $('#region_id').val()
                },
                //async: false,
                url: "<?php echo $base_url; ?>ai_reports/ajax_region_wise_data",
                beforeSend: function() {
                    customBlockUI();
                },
                success: function(msg) {
                    if (msg != '') {
                        var Oresult = jQuery.parseJSON(msg);
                        $('#user_id_region_wise').empty();
                        $('#user_id_region_wise').append(Oresult['users']);
                        $('#assessment_id3_region_wise').empty();
                        $('#assessment_id3_region_wise').append(Oresult['assessment']);
                        $('#trainer_id_region_wise').empty();
                        $('#trainer_id_region_wise').append(Oresult['manager']);
                    }
                    customunBlockUI();
                }
            });
        }

        function check_reg() {
            var division = $('#region_id').val();
            if (division == null) {
                ShowAlret("Please select Region first.!!", 'error');
                return false;
            } else {
                refreshTableColumnReg();
            }
        }

        function refreshTableColumnReg() {
            var assessment_id = $("#assessment_id3_region_wise").val();
            var region_id = $("#region_id").val();
            $.ajax({
                url: '<?= base_url() . 'ai_reports/generate_header_region'; ?>',
                type: 'POST',
                data: {
                    'assessment_id': assessment_id,
                    'region_id': region_id,
                },
                success: function(data) {
                    $('#report_section').hide();
                    $('#region_section').show();
                    $('#region_table').html(data);
                    DatatableRefresh_based_on_region();
                },
                error: function(data) {
                    alert(data);
                }
            });
        }

        function DatatableRefresh_based_on_region() {
            $('#report_section').hide();
            $('#region_section').show();
            var table = $('#index_table_region');
            table.dataTable({
                destroy: true,
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "No records found",
                    "infoFiltered": "(filtered1 from _MAX_ total records)",
                    "lengthMenu": "Show _MENU_",
                    "search": "Search:",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },
                "bStateSave": false,
                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                "pageLength": 10,
                "paging": true,
                "pagingType": "bootstrap_full_number",
                "columnDefs": [{
                        'className': 'dt-head-left dt-body-left',
                        'width': '50px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [0]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [1]
                    },
                    // {'className': 'dt-head-left dt-body-left','width': '100px','orderable': true,'searchable': true,'targets': [2]},
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [2]
                    },
                    // {'className': 'dt-head-left dt-body-left','width': '80px','orderable': false,'searchable': false,'targets': [4]},
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': true,
                        'searchable': false,
                        'targets': [3]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [4]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [5]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [6]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [7]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [8]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [9]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '180px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [10]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '250px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [11]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '80px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [12]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [13]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [14]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [15]
                    }
                    // {'className': 'dt-head-left dt-body-left','width': '130px','orderable': false,'searchable': false,'targets': [15]},
                    // {'className': 'dt-head-left dt-body-left','width': '130px','orderable': false,'searchable': false,'targets': [16]},
                ],
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                //"serverSide": true,
                "serverSide": false,
                "sAjaxSource": "<?php echo base_url() . 'ai_reports/generate_report'; ?>",
                "fnServerData": function(sSource, aoData, fnCallback) {
                    aoData.push({
                        name: 'user_id',
                        value: $('#user_id_region_wise').val()
                    });
                    report_type
                    aoData.push({
                        name: 'assessment_id',
                        value: $('#assessment_id3_region_wise').val()
                    });
                    aoData.push({
                        name: 'status_id',
                        value: $('#status_id_region_wise').val()
                    });
                    aoData.push({
                        name: 'trainer_id',
                        value: $('#trainer_id_region_wise').val()
                    });
                    aoData.push({
                        name: 'report_type',
                        value: $('#report_type_region_wise').val()
                    });
                    aoData.push({
                        name: 'region_id',
                        value: $('#region_id').val()
                    });
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json);
                    });
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    return nRow;
                },
                "fnFooterCallback": function(nRow, aData) {},
                "initComplete": function(settings, json) {
                    $('thead > tr> th:nth-child(1)').css({
                        'min-width': '100px',
                        'max-width': '100px'
                    });
                }
            });
        }
        // region Functions end

        // Manager function
        function get_manager_wise_data() {
            $.ajax({
                type: "POST",
                data: {
                    managerid: $('#managerid').val()
                },
                //async: false,
                url: "<?php echo $base_url; ?>ai_reports/ajax_manager_wise_data",
                beforeSend: function() {
                    customBlockUI();
                },
                success: function(msg) {
                    if (msg != '') {
                        var Oresult = jQuery.parseJSON(msg);
                        $('#user_id_manager').empty();
                        $('#user_id_manager').append(Oresult['user_manager_based']);
                        $('#assessment_id3_manager').empty();
                        $('#assessment_id3_manager').append(Oresult['assessment_manager_based']);
                    }
                    customunBlockUI();
                }
            });
        }

        function check_manager() {
            var manager_id = $('#managerid').val();
            if (manager_id == null) {
                ShowAlret("Please select Manager first.!!", 'error');
                return false;
            } else {
                refreshTableColumnManager()
            }
        }

        function refreshTableColumnManager() {
            var assessment_id = $("#assessment_id3_manager").val();
            var managerid = $("#managerid").val();
            $.ajax({
                url: '<?= base_url() . 'ai_reports/generate_header_manager'; ?>',
                type: 'POST',
                data: {
                    'assessment_id': assessment_id,
                    'managerid': managerid,
                },
                success: function(data) {
                    $('#region_section').hide();
                    $('#trainer_section').show();
                    $('#manager_table').html(data);
                    DatatableRefreshManager();
                },
                error: function(data) {
                    alert(data);
                }
            });
        }

        function DatatableRefreshManager() {
            $('#region_section').hide();
            $('#trainer_section').show();
            // var table = 
            $('#manager_table_index').dataTable({
                destroy: true,
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "No records found",
                    "infoFiltered": "(filtered1 from _MAX_ total records)",
                    "lengthMenu": "Show _MENU_",
                    "search": "Search:",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },
                "bStateSave": false,
                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                "pageLength": 10,
                "paging": true,
                "pagingType": "bootstrap_full_number",
                "columnDefs": [{
                        'className': 'dt-head-left dt-body-left',
                        'width': '50px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [0]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [1]
                    },
                    // {'className': 'dt-head-left dt-body-left','width': '100px','orderable': true,'searchable': true,'targets': [2]},
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [2]
                    },
                    // {'className': 'dt-head-left dt-body-left','width': '80px','orderable': false,'searchable': false,'targets': [4]},
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': true,
                        'searchable': false,
                        'targets': [3]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [4]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [5]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [6]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [7]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [8]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [9]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '180px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [10]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '250px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [11]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '80px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [12]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [13]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [14]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [15]
                    }
                    // {'className': 'dt-head-left dt-body-left','width': '130px','orderable': false,'searchable': false,'targets': [15]},
                    // {'className': 'dt-head-left dt-body-left','width': '130px','orderable': false,'searchable': false,'targets': [16]},
                ],
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                //"serverSide": true,
                "serverSide": false,
                "sAjaxSource": "<?php echo base_url() . 'ai_reports/generate_report'; ?>",
                "fnServerData": function(sSource, aoData, fnCallback) {
                    aoData.push({
                        name: 'user_id',
                        value: $('#user_id_manager').val()
                    });
                    aoData.push({
                        name: 'assessment_id',
                        value: $('#assessment_id3_manager').val()
                    });
                    aoData.push({
                        name: 'status_id',
                        value: $('#status_id_manager').val()
                    });
                    aoData.push({
                        name: 'trainer_id',
                        value: $('#managerid').val()
                    });
                    aoData.push({
                        name: 'report_type',
                        value: $('#report_type_manager').val()
                    });
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json);
                    });
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    return nRow;
                },
                "fnFooterCallback": function(nRow, aData) {},
                "initComplete": function(settings, json) {
                    $('thead > tr> th:nth-child(1)').css({
                        'min-width': '100px',
                        'max-width': '100px'
                    });
                }
            });
        }
        // Manager function end

        // assessment_wise_data end
        function get_ass_wise_data() {
            $.ajax({
                type: "POST",
                data: {
                    ass_id: $('#ass_id').val()
                },
                //async: false,
                url: "<?php echo $base_url; ?>ai_reports/ajax_ass_wise_data",
                beforeSend: function() {
                    customBlockUI();
                },
                success: function(msg) {
                    if (msg != '') {
                        var Oresult = jQuery.parseJSON(msg);
                        $('#user_id_ass').empty();
                        $('#user_id_ass').append(Oresult['user_ass_based']);
                    }
                    customunBlockUI();
                }
            });
        }

        function check_ass() {
            var ass_id = $('#ass_id').val();
            if (ass_id == null) {
                ShowAlret("Please select assessment first.!!", 'error');
                return false;
            } else {
                refreshTableColumnAssessment()
            }
        }

        function refreshTableColumnAssessment() {
            var assessment_id = $("#ass_id").val();
            $.ajax({
                url: '<?= base_url() . 'ai_reports/generate_header_ass'; ?>',
                type: 'POST',
                data: {
                    'assessment_id': assessment_id,
                },
                success: function(data) {
                    $('#report_section_assessment').show();
                    $('#assessment_table').html(data);
                    DatatableRefreshAss();
                },
                error: function(data) {
                    alert(data);
                }
            });
        }

        function DatatableRefreshAss() {

            $('#report_section_assessment').show();
            // $('#report_section_manager').hide();
            var table = $('#index_table_ass');
            table.dataTable({
                destroy: true,
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "No records found",
                    "infoFiltered": "(filtered1 from _MAX_ total records)",
                    "lengthMenu": "Show _MENU_",
                    "search": "Search:",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },

                "bStateSave": false,
                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                "pageLength": 10,
                "paging": true,
                "pagingType": "bootstrap_full_number",
                "columnDefs": [{
                        'className': 'dt-head-left dt-body-left',
                        'width': '50px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [0]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [1]
                    },
                    // {'className': 'dt-head-left dt-body-left','width': '100px','orderable': true,'searchable': true,'targets': [2]},
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '100px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [2]
                    },
                    // {'className': 'dt-head-left dt-body-left','width': '80px','orderable': false,'searchable': false,'targets': [4]},
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': true,
                        'searchable': false,
                        'targets': [3]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [4]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [5]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '130px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [6]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [7]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [8]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '200px',
                        'orderable': true,
                        'searchable': true,
                        'targets': [9]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '180px',
                        'orderable': false,
                        'searchable': true,
                        'targets': [10]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '250px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [11]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '80px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [12]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [13]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [14]
                    },
                    {
                        'className': 'dt-head-left dt-body-left',
                        'width': '120px',
                        'orderable': false,
                        'searchable': false,
                        'targets': [15]
                    }
                    // {'className': 'dt-head-left dt-body-left','width': '130px','orderable': false,'searchable': false,'targets': [15]},
                    // {'className': 'dt-head-left dt-body-left','width': '130px','orderable': false,'searchable': false,'targets': [16]}

                ],
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                //"serverSide": true,
                "serverSide": false,
                "sAjaxSource": "<?php echo base_url() . 'ai_reports/generate_report'; ?>",
                "fnServerData": function(sSource, aoData, fnCallback) {
                    aoData.push({
                        name: 'user_id',
                        value: $('#user_id_ass').val()
                    });
                    aoData.push({
                        name: 'status_id',
                        value: $('#status_id_ass').val()
                    });
                    aoData.push({
                        name: 'report_type',
                        value: $('#report_type_ass').val()
                    });
                    aoData.push({
                        name: 'assessment_id',
                        value: $('#ass_id').val()
                    });
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json);
                    });
                },
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    return nRow;
                },
                "fnFooterCallback": function(nRow, aData) {},
                "initComplete": function(settings, json) {
                    $('thead > tr> th:nth-child(1)').css({
                        'min-width': '100px',
                        'max-width': '100px'
                    });
                }
            });
        }
        // Changes by Bhautik rana 14-03-2023  
        // assessment_wise_data end 
        var quarter = moment().quarter();
        var year = moment().year();
        // By Bhautik rana 23-01-2023
        $('#date_picker').daterangepicker({
            "ranges": {
                'Current Year': [moment().year(year).startOf('year'), moment()],
                'Last 7 Days': [moment().subtract('days', 7), moment()],
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'Last 60 Days': [moment().subtract('days', 59), moment()],
                'Last 90 Days': [moment().subtract('days', 89), moment()],
                'Last 365 Days': [moment().subtract('days', 365), moment()]
            },
            "autoApply": true,
            "mirrorOnCollision": true,
            "applyOnMenuSelect": true,
            "autoFitCalendars": true,
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
                // "firstDay": 1
            },
            "startDate": moment().subtract('month', 1).format("DD/MM/YYYY"),
            "endDate": moment().format("DD/MM/YYYY"),
            "drops": "left",
            // "opens": "right",
            opens: (App.isRTL() ? 'right' : 'left'),
        }, function(start, end, label) {
            sessionStorage.setItem("IsCustom", label);
        });
        if ($('#date_picker').attr('data-display-range') != '0') {
            var thisYear = (new Date()).getFullYear();
            var thisMonth = (new Date()).getMonth() + 1;
            var start = new Date(thisMonth + "/1/" + thisYear);
        }
        $('#date_picker').on('apply.daterangepicker', function(ev, picker) {
            $('#date_lable').text(picker.chosenLabel);
            StartDate = picker.startDate.format('DD-MM-YYYY');
            EndDate = picker.endDate.format('DD-MM-YYYY');

            let IsCustom = sessionStorage.getItem("IsCustom");
            load_assessment_datewise(IsCustom);
        });
    </script>
</body>

</html>