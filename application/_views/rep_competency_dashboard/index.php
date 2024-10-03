<?php
defined('BASEPATH') or exit('No direct script access allowed');
$base_url = base_url();
$asset_url = $this->config->item('assets_url');
?>
<!DOCTYPE html>
<!--[if IE 8]> 
<html lang="en" class="ie8 no-js">
   <![endif]-->
<!--[if IE 9]> 
   <html lang="en" class="ie9 no-js">
      <![endif]-->
<!--[if !IE]><!-->
<html lang="en">

<head>
    <?php $this->load->view('inc/inc_htmlhead'); ?>
    <link href="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css"
        rel="stylesheet" type="text/css" />
    <!--<link rel="stylesheet" type="text/css" href="< ?php echo $asset_url;?>assets/global/highcharts/css/highcharts.css" />-->
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/css/buttons.dataTables.min.css"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
        rel="stylesheet" type="text/css" />
    <style>
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
        /* float: left; */
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
        /*color: #f1592a !important;*/
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

    /* Assessment List Css */
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

    .select-all {
        margin-left: 100px;
        margin-top: 3px;
    }

    #common_check {
        margin-left: 169px;
        position: absolute;
        left: 73px;
        top: -3px;
    }

    /* Assessment List Css */

    /* Competency graphs img style start here */
    .img-style {
        height: 60%;
        width: 50%;
        text-align: center;
        margin: auto;
        margin-left: 23%;
        margin-top: 2%;
    }

    .head-text {
        font-family: 'Catamaran';
        font-size: 16px;
        font-weight: 600;
        line-height: 24px;
        color: #2A2E36;
        text-transform: inherit;
        margin-bottom: 8px;
        text-align: center;
    }

    .sub-head {
        font-family: 'Catamaran';
        font-size: 12px;
        font-weight: 400;
        line-height: 16px;
        color: #2A2E36;
        text-transform: inherit;
        text-align: center;
    }

    #color-bar {
        margin-top: 3%;
        margin-left: 61.5%;
    }

    .color-boxes {
        padding: 8px;
        font-size: 13px
    }

    .myFont {
        font-size: 14px;
    }

    /* End here */
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
                    <!-- PAGE BAR -->
                    <!-- <form id="rangeform">
                  <div class="theme-panel hidden-xs hidden-sm">
                     <div class="toggler">
                     </div>
                     <div class="toggler-close">
                     </div>
                     <div class="theme-options">
                        <div class="theme-option theme-colors clearfix">
                           <span>THRESHOLD COLOR</span>
                        </div>
                        <?php
                        if (count((array) $ThresholdData) > 0) {
                            $slot_row = 1;
                            foreach ($ThresholdData as $rng) {
                        ?>
                              <div class="theme-option">
                                 <span style="float: left;background-color: <?php echo ($rng->range_color != '' ? $rng->range_color : ''); ?>">&nbsp;
                                 </span>
                                 <div class="col-md-4"><input class=" form-control input-sm " id="range_from<?php echo $slot_row; ?>" name="range_from[]" placeholder="" type="text" value="<?php echo ($rng->range_from != '' ? $rng->range_from : ''); ?>"></div>
                                 <div class="col-md-4"><input class="form-control input-sm " id="range_to<?php echo $slot_row; ?>" name="range_to[]" placeholder="" type="text" value="<?php echo ($rng->range_to != '' ? $rng->range_to : ''); ?>"></div>
                                 <input class="form-control input-sm " id="range_color<?php echo $slot_row; ?>" name="range_color[]" placeholder="" type="hidden" value="<?php echo ($rng->range_color != '' ? $rng->range_color : ''); ?>">
                                 <input class="form-control input-sm " id="range_id<?php echo $slot_row; ?>" name="range_id[]" placeholder="" type="hidden" value="<?php echo ($rng->id != '' ? $rng->id : ''); ?>">
                              </div>
                        <?php $slot_row++;
                            }
                        }
                        ?>
                        <div class="theme-option theme-colors clearfix" style="margin-top:10px">
                           <span>Pass/Fail Color</span>
                        </div>
                        <?php
                        if (count((array) $ResultData) > 0) {
                            $result_row = 1;
                            foreach ($ResultData as $rng) {
                        ?>
                              <div class="theme-option">
                                 <span style="float: left;background-color: <?php echo ($rng->range_color != '' ? $rng->range_color : ''); ?>"><?php echo $rng->assessment_status; ?>
                                 </span>
                                 <?php if ($rng->range_from == 0 && $rng->range_to == 0) { ?>
                                    <div class="col-md-4"><input class="form-control input-sm " id="result_from<?php echo $result_row; ?>" name="result_from[]" placeholder="" type="text" value="0" readonly></div>
                                    <div class="col-md-4"><input class="form-control input-sm " id="result_to<?php echo $result_row; ?>" name="result_to[]" placeholder="" type="text" value="0" readonly></div>
                                 <?php } else { ?>
                                    <div class="col-md-4"><input class="form-control input-sm " id="result_from<?php echo $result_row; ?>" name="result_from[]" placeholder="" type="text" value="<?php echo ($rng->range_from != '' ? $rng->range_from : ''); ?>"></div>
                                    <div class="col-md-4"><input class="form-control input-sm " id="result_to<?php echo $result_row; ?>" name="result_to[]" placeholder="" type="text" value="<?php echo ($rng->range_to != '' ? $rng->range_to : ''); ?>"></div>
                                 <?php } ?>
                                 <input class="form-control input-sm " id="result_color<?php echo $result_row; ?>" name="result_color[]" placeholder="" type="hidden" value="<?php echo ($rng->range_color != '' ? $rng->range_color : ''); ?>">
                                 <input class="form-control input-sm " id="result_status<?php echo $result_row; ?>" name="result_status[]" placeholder="" type="hidden" value="<?php echo ($rng->assessment_status != '' ? $rng->assessment_status : ''); ?>">
                              </div>
                        <?php $result_row++;
                            }
                        }
                        ?>
                        <div class="col-md-offset-9 pull-right" style="margin:10px;">
                           <button type="button" name="submit" id="btnSubmit" class="btn btn-orange" onclick="SubmitData();">Change</button>
                        </div>
                     </div>
                  </div>
               </form> -->
                    <!-- <form id="FilterFrm" name="FilterFrm" method="post"> -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="<?php echo base_url() . 'reports'; ?>">Reports</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Rep Competency Dashboard</span>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <a href="<?php echo base_url() . 'reports'; ?>"
                                class="btn btn-sm btn-default pull-right">Back</a>&nbsp;
                        </div>
                        <div class="col-md-1 page-breadcrumb"></div>
                    </div>
                    <!-- PAGE BAR -->

                    <div class="row">
                        <!-- INDEX CHART -->
                    </div>
                    <!-- New Module -->
                    <style>
                    .btn.btn-outline.blue-oleo.active {
                        background-color: #004369;
                    }

                    .portlet>.portlet-title {
                        border-bottom: none;
                    }

                    .portlet.light.bordered>.portlet-title {
                        border-bottom: none;
                    }

                    .title-bar {
                        background: white;
                        padding: 0px 0px 47px 8px;
                        font-size: 17px;
                        position: static;
                    }

                    .header-right {
                        float: right;
                    }
                    </style>

                    <br>
                    <!-- filter data module created by Patel Rudra  -->
                    <div class="row">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="panel-group accordion" id="accordion3">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle accordion-toggle-styled " data-toggle="collapse"
                                                data-parent="#accordion3" href="#collapse_3_2">
                                                Filter Data </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_3_2" class="panel-collapse ">
                                        <div class="panel-body">
                                            <div class="row margin-bottom-10">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"
                                                            style="padding:0px;">Division: &nbsp;</label>
                                                        <div class="col-md-9" style="padding:0px;">
                                                            <select id="division_id" name="division_id"
                                                                class="form-control input-sm select2"
                                                                placeholder="Please select">
                                                                <option value=""> Please select:</option>
                                                                <?php
                                                                if (isset($division_id)) {
                                                                    foreach ($division_id as $dv) {
                                                                ?>
                                                                <option value="<?php echo  $dv->id; ?>">
                                                                    <?php echo $dv->division_name; ?></option>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"
                                                            style="padding:0px;">Manager:</label>
                                                        <div class="col-sm-7" style="padding:0px;">
                                                            <select id="manager_id" name="manager_id"
                                                                class="form-control input-sm select2"
                                                                placeholder="Please select" style="width: 100%">
                                                                <option value="">Please select</option>
                                                                <?php
                                                                if (isset($managers)) {
                                                                    foreach ($managers as $mn) {
                                                                ?>
                                                                <option value="<?php echo  $mn->id; ?>">
                                                                    <?php echo $mn->username; ?></option>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3" style="padding:0px;">Rep
                                                            name:&nbsp;<span class="required"> * </span></label>
                                                        <div class="col-sm-7" style="padding:0px;">
                                                            <select id="trainee_id" name="trainee_id"
                                                                class="form-control input-sm select2"
                                                                placeholder="Please select" style="width: 100%">
                                                                <option value="">Select Trainee</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin-bottom-10">
                                                <div class="col-md-10">
                                                </div>
                                                <div class="btn-group btn-group-devided header-right"
                                                    data-toggle="buttons"
                                                    style="margin-bottom: 0px; margin-top: 16px; margin-right: 5px;">
                                                    <!-- <a data-toggle="modal" class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active">
                                                    Search
                                                </a> -->
                                                    <button type="button" class="btn blue-hoki btn-sm"
                                                        onclick="checkId()">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- filter data module created by Patel Rudra  -->

                    <!-- Leader board created by Patel Rudra  -->
                    <div class='row'>
                        <div class='col-md-12'>
                            <div class='title-bar'>
                                <span class="caption-subject font-dark bold"
                                    style="font-family:'Catamaran'; position: absolute; top: 22px; left: 14px;">Leader-board:
                                    <a data-title="Individual leader-board of sales reps">
                                        <i class="icon-info font-black sub-title"></i>
                                    </a>
                                </span>
                                <div class="btn-group btn-group-devided header-right" data-toggle="buttons"
                                    style="margin-bottom: 0px; margin-top: 16px; margin-right: 5px;">
                                    <a data-toggle="modal"
                                        class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active"
                                        href="#leaderboard_modal">
                                        Filter
                                    </a>
                                </div>
                                <!-- Table start here -->
                                <div style="width:99%;padding-top: 75px;font-size: 12px;">
                                    <form id="frmUsers" name="frmUsers" method="post" action="">
                                        <input type="hidden" id="iscustom" name="iscustom">
                                        <div class="portlet-body">
                                            <table
                                                class="table table-bordered table-hover table-checkable order-column no-footer dataTable"
                                                id="leader_board_table" style="border: none;">
                                                <thead>
                                                    <th>Your score</th>
                                                    <th>Top performer</th>
                                                    <th>Bottom performer</th>
                                                    <th>Ranks</th>
                                                    <th>Badge</th>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                                <!-- Table end here -->
                            </div>
                        </div>
                    </div>
                    <!-- Leader board ended by Patel Rudra  -->
                    <br>
                    <div class='row'>

                        <!-- Rep spider chart starts from here -->
                        <div class='col-md-6'>
                            <div class='title-bar'>
                                <span class="caption-subject font-dark bold"
                                    style="font-family:'Catamaran'; position: absolute; top: 22px; left: 23px;">Rep-spider
                                    chart:
                                    <a data-title="Assessment wise and parameter wise spider-chart of rep performance">
                                        <i class="icon-info font-black sub-title"></i>
                                    </a>
                                </span>
                                <div class="btn-group btn-group-devided header-right" data-toggle="buttons"
                                    style="margin-bottom: 0px; margin-top: 16px; margin-right: 5px;">
                                    <a data-toggle="modal"
                                        class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active"
                                        href="#rep_spider_modal">
                                        Filter
                                    </a>
                                </div>
                            </div>
                            <input type="hidden" name="custom_date" id="custom_date" value=''>
                            <div id='rep_spider_chart'></div>
                            <br>
                        </div>
                        <!-- Rep spider chart ends from here -->

                        <!-- No of attempts graph starts from here -->
                        <div class='col-md-6'>
                            <div class='title-bar'>
                                <span class="caption-subject font-dark bold"
                                    style="font-family:'Catamaran'; position: absolute; top: 22px; left: 23px;">
                                    No of attempts :
                                    <a data-title="Assessment wise no. of attempts chart">
                                        <i class="icon-info font-black sub-title"></i>
                                    </a>
                                </span>
                                <div class="btn-group btn-group-devided header-right" data-toggle="buttons"
                                    style="margin-bottom: 0px; margin-top: 16px; margin-right: 5px;">
                                    <a data-toggle="modal"
                                        class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active"
                                        href="#attempts_modal">
                                        Filter
                                    </a>
                                </div>
                            </div>
                            <input type="hidden" name="custom_date" id="custom_date" value=''>
                            <div id='assessment_attempt'></div>
                            <br>
                        </div>
                        <!-- No of attempts graph ends from here -->
                    </div>

                    <div class='row'>
                        <!-- Assessment comparison created by Patel Rudra  -->
                        <div class='col-md-6'>
                            <div class='title-bar'>
                                <span class="caption-subject font-dark bold"
                                    style="font-family:'Catamaran'; position: absolute; top: 22px; left: 23px;">Assessment
                                    comparison:
                                    <a data-title="Assessment comparison">
                                        <i class="icon-info font-black sub-title"></i>
                                    </a>
                                </span>
                                <div class="btn-group btn-group-devided header-right" data-toggle="buttons"
                                    style="margin-bottom: 0px; margin-top: 16px; margin-right: 5px;">
                                    <a data-toggle="modal"
                                        class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active"
                                        href="#comparision_modal">
                                        Filter
                                    </a>
                                </div>
                            </div>
                            <input type="hidden" name="custom_date" id="custom_date" value=''>
                            <div id='assessment_comparison'></div>
                            <br>
                        </div>
                        <!-- Assessment comparison ended from here created by Patel Rudra  -->

                    </div>

                    <!-- User_understanding Modal start Here -->
                    <div id="leaderboard_modal" class="modal fade bs-modal-lg" data-keyboard="false"
                        data-backdrop="static" role="dialog" aria-hidden="true" tabindex="-1" data-width="200">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <form id="frmModalForm" name="frmModalForm" onsubmit="return false;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true"></button>
                                        <h4 class="modal-title">Filter</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id='dsk' style="display: none">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="form-group last">
                                                    <label>Assessment<span class="select-all">Select All</span></label>
                                                    <input class="leaderboard_assessment_list"
                                                        id="leaderboard_assessment_list" type="checkbox"
                                                        id="common_check">
                                                    <select id="assessment_id" name="assessment_id[]"
                                                        class="form-control input-sm select2me"
                                                        placeholder="Please select" multiple=''>
                                                    </select>
                                                </div>
                                                <div class="form-group last">
                                                    <label>Select Time</label>
                                                    <input class="form-control input-sm" id="leader_board_picker"
                                                        value="" name="leader_board_picker" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="col-md-12 text-right ">
                                            <!-- <button type="button" class="btn btn-orange" id="btnIndexadoptionFilter"> -->
                                            <button type="button" class="btn btn-orange"
                                                onclick="leader_board_understanding(2)">
                                                <span class="ladda-label">Apply</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- leaderboard_modal End Here -->

                    <!-- Rep spider modal created  -->
                    <div id="rep_spider_modal" class="modal fade bs-modal-lg" data-keyboard="false"
                        data-backdrop="static" role="dialog" aria-hidden="true" tabindex="-1" data-width="200">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <form id="frmModalForm" name="frmModalForm" onsubmit="return false;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true"></button>
                                        <h4 class="modal-title">Filter</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id='dsk' style="display: none">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="form-group last">
                                                    <label>Assessment<span class="select-all">Select All</span></label>
                                                    <input class="repspider_assessment_list"
                                                        id="repspider_assessment_list" type="checkbox"
                                                        id="common_check">
                                                    <select id="assessment_id2" name="assessment_id2[]"
                                                        class="form-control input-sm select2me"
                                                        placeholder="Please select" multiple=''>
                                                    </select>
                                                    <span style="color: red;">Please select 8 assessment only</span>
                                                </div>
                                                <div class="form-group last">
                                                    <label>Select Time</label>
                                                    <input class="form-control input-sm" id="rep_spider_chart_picker"
                                                        value="" name="rep_spider_chart_picker" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="form-group last">
                                                    <label>Report by</label>
                                                    <select id="report_by" name="report_by"
                                                        class="form-control input-sm select2me"
                                                        placeholder="Please select" style="width: 100%">
                                                        <option value="0">Assessment</option>
                                                        <option value="1">Parameter</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="col-md-12 text-right ">
                                            <button type="button" class="btn btn-orange" onClick="rep_spider_chart(2)">
                                                <span class="ladda-label">Apply</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Rep spider modal ended by Patel Rudra -->

                    <!-- Assessment comparision modal created by Patel Rudra  -->
                    <div id="comparision_modal" class="modal fade bs-modal-lg" data-keyboard="false"
                        data-backdrop="static" role="dialog" aria-hidden="true" tabindex="-1" data-width="200">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <form id="frmModalForm" name="frmModalForm" onsubmit="return false;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true"></button>
                                        <h4 class="modal-title">Filter</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id='dsk' style="display: none">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="form-group last">
                                                    <label>Assessment<span class="select-all">Select All</span></label>
                                                    <input class="leaderboard_assessment_list"
                                                        id="comparision_assessment_list" type="checkbox"
                                                        id="common_check">
                                                    <select id="assessment_id3" name="assessment_id3[]"
                                                        class="form-control input-sm select2me"
                                                        placeholder="Please select" multiple=''>
                                                    </select>
                                                    <span style="color: red;">Please select 5 assessment only</span>
                                                </div>
                                                <div class="form-group last">
                                                    <label>Select Time</label>
                                                    <input class="form-control input-sm"
                                                        id="assessment_comparision_picker" value=""
                                                        name="assessment_comparision_picker" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="col-md-12 text-right ">
                                            <!-- <button type="button" class="btn btn-orange" id="btnIndexadoptionFilter"> -->
                                            <button type="button" class="btn btn-orange"
                                                onClick="assessment_comparison(2)">
                                                <span class="ladda-label">Apply</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Assessment comparision modal ended by Patel Rudra  -->
                    <!-- Assessment comparision modal created by Patel Rudra  -->
    <div id="st_modal" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static" role="dialog"
        aria-hidden="true" tabindex="-1" data-width="200">
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
                                    <label>Assessment<span class="select-all">Select All</span></label>
                                    <input class="st_assessment_list" id="st_assessment_list" type="checkbox"
                                        id="common_check">
                                    <select id="assessment_id5" name="assessment_id5[]"
                                        class="form-control input-sm select2me" placeholder="Please select" multiple=''>
                                    </select>
                                    <span style="color: red;">Please select 5 assessment only</span>
                                </div>
                                <div class="form-group last">
                                    <label>Select Time</label>
                                    <input class="form-control input-sm" id="time_series_picker" value=""
                                        name="time_series_picker" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12 text-right ">
                            <!-- <button type="button" class="btn btn-orange" id="btnIndexadoptionFilter"> -->
                            <button type="button" class="btn btn-orange" onClick="time_series(2)">
                                <span class="ladda-label">Apply</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Assessment comparision modal ended by Patel Rudra  -->

                    <!-- Assessment attempts modal created by Patel Rudra -->
                    <div id="attempts_modal" class="modal fade bs-modal-lg" data-keyboard="false" data-backdrop="static"
                        role="dialog" aria-hidden="true" tabindex="-1" data-width="200">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <form id="frmModalForm" name="frmModalForm" onsubmit="return false;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true"></button>
                                        <h4 class="modal-title">Filter</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id='dsk' style="display: none">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="form-group last">
                                                    <label>Assessment<span class="select-all">Select All</span></label>
                                                    <input class="attempts_assessment_list"
                                                        id="attempts_assessment_list" type="checkbox" id="common_check">
                                                    <select id="assessment_id4" name="assessment_id4[]"
                                                        class="form-control input-sm select2me"
                                                        placeholder="Please select" multiple=''>
                                                    </select>
                                                        <span style="color: red;">Please select 5 assessment only</span>
                                                </div>
                                                <div class="form-group last">
                                                    <label>Select Time</label>
                                                    <input class="form-control input-sm" id="assessment_attempts_picker"
                                                        value="" name="assessment_attempts_picker" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="col-md-12 text-right ">
                                            <!-- <button type="button" class="btn btn-orange" id="btnIndexadoptionFilter"> -->
                                            <button type="button" class="btn btn-orange"
                                                onClick="assessment_attempt(2)">
                                                <span class="ladda-label">Apply</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Assessment attempt modal ended by Patel Rudra  -->

                    <div class="modal fade" id="user_data_modal" role="basic" aria-hidden="true" data-width="400"
                        style="height:fit-content">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <span> &nbsp;&nbsp;Loading... </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-footer">
                        <div class="page-footer-inner"> <?php echo $this->setting_value->copyright; ?></div>
                        <div class="scroll-to-top">
                            <i class="icon-arrow-up"></i>
                        </div>
                    </div>

                    <?php $this->load->view('inc/inc_footer_script'); ?>
                    <script src="<?php echo $asset_url; ?>assets/global/plugins/moment.min.js" type="text/javascript">
                    </script>
                    <script
                        src="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
                        type="text/javascript"></script>
                    <script src="<?php echo $asset_url; ?>assets/global/plugins/counterup/jquery.waypoints.min.js"
                        type="text/javascript"></script>
                    <script src="<?php echo $asset_url; ?>assets/global/plugins/counterup/jquery.counterup.min.js"
                        type="text/javascript"></script>
                    <script src="<?php echo $asset_url; ?>assets/global/highcharts/highstock.js"></script>
                    <script src="<?php echo $asset_url; ?>assets/global/highcharts/highcharts-more.js"></script>



                    <!-- Graph Export js -->
                    <script src='<?php echo $asset_url; ?>assets/global/highcharts/modules/exporting.js'></script>
                    <script src='<?php echo $asset_url; ?>assets/global/highcharts/modules/export-data.js'></script>
                    <!-- End Here -->

                    <!-- <script src="< ?php echo $asset_url;?>assets/global/highcharts/highcharts.js"></script>-->
                    <script src="<?php echo $asset_url; ?>assets/global/scripts/datatable.js" type="text/javascript">
                    </script>
                    <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.js"
                        type="text/javascript"></script>
                    <script
                        src="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
                        type="text/javascript"></script>
                    <script
                        src="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/js/dataTables.buttons.min.js"
                        type="text/javascript"></script>
                    <!-- <script src="< ?php echo $asset_url; ?>assets/global/highcharts/modules/exporting.js"></script> -->


                    <script>
                    var firsttimeload = 1;
                    var Company_id = '<?php echo $company_id ?>';
                    var StartDate = "<?php echo $start_date; ?>";
                    var EndDate = "<?php echo $end_date; ?>";
                    var base_url = "<?php echo $base_url; ?>";
                    var quarter = moment().quarter();
                    var year = moment().year();
                    var step = 0;
                    </script>
                    <script src="<?php echo $asset_url; ?>assets/customjs/rep_competency_dashboard.js"></script>
                    <!-- For download heat map as image  -->
                    <script src="<?php echo $asset_url; ?>assets/customjs/html2canvas.min.js"></script>
                    <script>
                    get_rep_info();
                    leader_board_understanding();
                    rep_spider_chart();
                    assessment_comparison();
                    assessment_attempt();

                    //function checkId started by Patel Rudra
                    function checkId() {
                        if ($('#trainee_id').val() == '') {
                            ShowAlret("Please select TraineeID.!!", 'error');
                            return false;
                        } else {
                            leader_board_understanding();
                            rep_spider_chart();
                            assessment_comparison();
                            assessment_attempt();
                            // knowledge_head();
                        }
                    }
                    //function checkId ended by Patel Rudra

                    //function get_rep_info start by Patel Rudra
    $('#manager_id').change(function() {
        get_rep_info();
    });
    //function get_rep_info ended by Patel Rudra

    $("#trainee_id").select2({
        dropdownParent: $('#collapse_3_2'),
        placeholder: 'Please select',
        width: '100%',
        allowClear: true,
    });

    //function get_assessment_info start by Patel Rudra
    $('#trainee_id').change(function() {
        get_assessment_info();
    });
    //function get_assessment_info ended by Patel Rudra 
    // select all function for assessment for leader board table
    $("#leaderboard_assessment_list").click(function() {
        if ($("#leaderboard_assessment_list").is(':checked')) {
            $("#assessment_id").find('option').prop("selected", true);
            $("#assessment_id").trigger('change');
        } else { //deselect all
            $("#assessment_id").find('option').prop("selected", false);
            $("#assessment_id").trigger('change');
        }
    });

                    // select all function for assessment for rep spider chart
    $("#repspider_assessment_list").click(function() {
        if ($("#repspider_assessment_list").is(':checked')) {
            $("#assessment_id2").find('option').prop("selected", true);
            $("#assessment_id2").trigger('change');
        } else { //deselect all
            $("#assessment_id2").find('option').prop("selected", false);
            $("#assessment_id2").trigger('change');
        }
    });

    // select all function for assessment for Assessment comparision chart
    $("#comparision_assessment_list").click(function() {
        if ($("#comparision_assessment_list").is(':checked')) {
            $("#assessment_id3").find('option').prop("selected", true);
            $("#assessment_id3").trigger('change');
        } else { //deselect all
            $("#assessment_id3").find('option').prop("selected", false);
            $("#assessment_id3").trigger('change');
        }
    });

    // select all function for assessment for Assessment attempt chart
    $("#attempts_assessment_list").click(function() {
        if ($("#attempts_assessment_list").is(':checked')) {
            $("#assessment_id4").find('option').prop("selected", true);
            $("#assessment_id4").trigger('change');
        } else { //deselect all
            $("#assessment_id4").find('option').prop("selected", false);
            $("#assessment_id4").trigger('change');
        }
    });

    // select all function for assessment for time series graph
    $("#st_assessment_list").click(function() {
        if ($("#st_assessment_list").is(':checked')) {
            $("#assessment_id5").find('option').prop("selected", true);
            $("#assessment_id5").trigger('change');
        } else { //deselect all
            $("#assessment_id5").find('option').prop("selected", false);
            $("#assessment_id5").trigger('change');
        }
    });

                    $("#division_id").select2({
                        dropdownParent: $('#collapse_3_2'),
                        placeholder: 'Please select',
                        width: '100%',
                        allowClear: true,
                    });

                    //function get_manager_rep_info start by Patel Rudra
                    $('#division_id').change(function() {
                        get_manager_rep_info();
                    });
                    //function get_manager_rep_info ended by Patel Rudra

                    $("#manager_id").select2({
                        dropdownParent: $('#collapse_3_2'),
                        placeholder: 'Please select',
                        width: '100%',
                        allowClear: true,
                    });

                    //For date picking in filter model of Leader board created by Rudra patel 20/11/2023
                    $('#leader_board_picker').daterangepicker({
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
                                "xanuary",
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
                        "drops": "down",
                        "opens": "right",
                        //   opens: (App.isRTL() ? 'right' : 'left'),
                    }, function(start, end, label) {
                        sessionStorage.setItem("IsCustom", label);
                    });

                    if ($('#leader_board_picker').attr('data-display-range') != '0') {
                        var thisYear = (new Date()).getFullYear();
                        var thisMonth = (new Date()).getMonth() + 1;
                        var start = new Date(thisMonth + "/1/" + thisYear);


                    }
                    $('#leader_board_picker').on('apply.daterangepicker', function(ev, picker) {
                        $('#date_lable').text(picker.chosenLabel);
                        StartDate = picker.startDate.format('DD-MM-YYYY');
                        EndDate = picker.endDate.format('DD-MM-YYYY');
                        let IsCustom = sessionStorage.getItem("IsCustom");
                        $('#iscustom').val(IsCustom);
                    });
                    //For date picking in filter model of Leader board ended by Rudra patel 20/11/2023

                    //For date picking in filter model of rep_spider_chart created by Rudra patel 20/11/2023
                    $('#rep_spider_chart_picker').daterangepicker({
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
                        "drops": "down",
                        "opens": "right",
                        //   opens: (App.isRTL() ? 'right' : 'left'),
                    }, function(start, end, label) {
                        sessionStorage.setItem("IsCustom", label);
                    });
                    if ($('#rep_spider_chart_picker').attr('data-display-range') != '0') {
                        var thisYear = (new Date()).getFullYear();
                        var thisMonth = (new Date()).getMonth() + 1;
                        var start = new Date(thisMonth + "/1/" + thisYear);


                    }

                    $('#rep_spider_chart_picker').on('apply.daterangepicker', function(ev, picker) {
                        $('#date_lable').text(picker.chosenLabel);
                        StartDate = picker.startDate.format('DD-MM-YYYY');
                        EndDate = picker.endDate.format('DD-MM-YYYY');
                        let IsCustom = sessionStorage.getItem("IsCustom");
                        $('#custom_date').val(IsCustom);
                    });
                    //For date picking in filter model of rep_spider_chart ended by Rudra patel 20/11/2023

                    //For date picking in filter model of assessment_comparision started by Patel Rudra
                    $('#assessment_comparision_picker').daterangepicker({
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
                        "drops": "down",
                        "opens": "right",
                        //   opens: (App.isRTL() ? 'right' : 'left'),
                    }, function(start, end, label) {
                        sessionStorage.setItem("IsCustom", label);
                    });
                    if ($('#assessment_comparision_picker').attr('data-display-range') != '0') {
                        var thisYear = (new Date()).getFullYear();
                        var thisMonth = (new Date()).getMonth() + 1;
                        var start = new Date(thisMonth + "/1/" + thisYear);


                    }

                    $('#assessment_comparision_picker').on('apply.daterangepicker', function(ev, picker) {
                        $('#date_lable').text(picker.chosenLabel);
                        StartDate = picker.startDate.format('DD-MM-YYYY');
                        EndDate = picker.endDate.format('DD-MM-YYYY');
                        let IsCustom = sessionStorage.getItem("IsCustom");
                        $('#custom_date').val(IsCustom);
                    });
                    //For date picking in filter model of assessment_comparision ended by Patel Rudra

                    //For date picking in filter model of assessment_attempts start by Patel Rudra
                    $('#assessment_attempts_picker').daterangepicker({
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
                        "drops": "down",
                        "opens": "right",
                        //   opens: (App.isRTL() ? 'right' : 'left'),
                    }, function(start, end, label) {
                        sessionStorage.setItem("IsCustom", label);
                    });
                    if ($('#assessment_attempts_picker').attr('data-display-range') != '0') {
                        var thisYear = (new Date()).getFullYear();
                        var thisMonth = (new Date()).getMonth() + 1;
                        var start = new Date(thisMonth + "/1/" + thisYear);
                    }

                    $('#assessment_attempts_picker').on('apply.daterangepicker', function(ev, picker) {
                        $('#date_lable').text(picker.chosenLabel);
                        StartDate = picker.startDate.format('DD-MM-YYYY');
                        EndDate = picker.endDate.format('DD-MM-YYYY');
                        let IsCustom = sessionStorage.getItem("IsCustom");
                        $('#custom_date').val(IsCustom);
                    });
                    //For date picking in filter model of assessment_attempts ended by Patel Rudra
                    </script>
                    <style>
                    image {
                        display: none
                    }
                    </style>
</body>

</html>