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
        <style>
            th{vertical-align: middle!important; text-align: center;}            
        </style>
        <style>
        .checked {
          color: orange;
        }
        #question_table,#rating_table {
            display: block;
            max-height: 350px;
            overflow-y: auto;
            table-layout:fixed;
        }
        .cust_container {
  overflow: hidden;
      width: 100%;
}
        .left-col {
  padding-bottom: 500em;
  margin-bottom: -500em;
}
.right-col {
  margin-right: -1px; /* Thank you IE */
  padding-bottom: 500em;
  margin-bottom: -500em;
  background-color: #FFF;
}      
</style>
        <!--datattable CSS  Start-->
        <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $asset_url; ?>assets/global/css/star-rating.css" rel="stylesheet" type="text/css" /> 
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
        <!--datattable CSS  End-->
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
                                    <!-- <span>Assessment</span>   DARSHIL CHANGED -->
                                    <span>Trinity</span>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <!-- <span>Assessment Listing</span>   DARSHIL CHANGED -->
                                    <span>View Trinity</span>
                                </li>
                            </ul>                            
                        </div>
                        <div class="row mt-10">
                        <div class="col-md-12">
                            <div class="panel-group accordion" id="accordion3">
                                <div class="panel panel-default">

                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_2">
                                                Advanced Search </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_3_2" class="panel-collapse collapse">
                                        <div class="panel-body" >
                                            <form id="FilterFrm" name="FilterFrm" method="post"> 
                                                <div class="row">
                                                    <!-- <div class="col-md-3">    
                                                            <div class="form-group">
                                                                <label>Assessment Type</label>
                                                                <select name="assessment_type" id="assessment_type" class="form-control input-sm select2">
                                                                    <option value="" selected="">All <option>
                                                                    <?php //foreach ($assessment_type as $val) { ?>
                                                                        <option value="<?php //echo $val->id ?>" ><?php //echo $val->description ?></option>
                                                                    <?php //} ?>
                                                                </select>
                                                            </div>
                                                        </div>
													<div class="col-md-3">    
														<div class="form-group">
															<label>Question type</label>
															<select id="question_type" name="question_type" class="form-control input-sm select2" placeholder="Please select">
																<option value="" selected="">All <option>
																<option value="0" >Question</option>
																<option value="1">Situation</option>
															</select>
														</div>
													</div>      DARSHIL COMMENTED	 -->
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="">Status&nbsp;</label>                                                            
                                                                <select id="filter_status" name="filter_status" class="form-control input-sm select2" placeholder="Please select"  style="width: 100%">
                                                                    <option value="">All Status</option>
                                                                    <option value="1">Live</option>
                                                                    <option value="2">Expired</option>
                                                                    <option value="3">Active</option>
                                                                    <option value="4">In-Active</option>
                                                                </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-offset-10 col-md-2 text-right">
                                                            <button type="button" class="btn blue-hoki btn-sm" onclick="DatatableRefresh()">Search</button>
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
                                    <div class="portlet-title col-md-3">
                                        <div class="caption caption-font-24">
                                            <!-- Video Assessment       DARSHIL CHANGED -->
                                            Trinity
                                            <div class="tools"> </div>  
                                        </div>                                        
                                    </div>
                                    <!--< ?php if($is_supervisor || $superaccess) { ?> -->
                                    <div class="col-md-3">    
                                        <div class="form-group">
                                            <label class="notranslate">View As</label><!-- added by shital LM: 07:03:2024 -->
                                            <select id="view_type" name="view_type" class="form-control input-sm select2me" placeholder="Please select" onchange="DatatableRefresh()">                                                                
												<option value="0">Assessor</option>
                                                <option value="1" <?php echo ($role_id==4) ?'selected':'';?>>Supervisor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--< ?php } ?> -->
									
                                    <div class="portlet-body">
                                        <form id="frmAssessment" name="frmAssessment" method="post">
                                            <table class="table  table-bordered table-hover table-checkable order-column" id="index_table">
                                                <thead>
                                                    <!-- <tr>                                                        
                                                        <th rowspan="2">ID</th>
                                                        <th rowspan="2">Type</th>                                                        
                                                        <th rowspan="2">Assessment</th>
                                                        <th rowspan="2">Start Date/Time</th>
                                                        <th rowspan="2">End Date/Time</th>
														<th rowspan="2">Last Assessor Date</th>
                                                        <th rowspan="2">Status</th>
                                                        <th colspan="2">User Status</th>
                                                        <th rowspan="2">Actions</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Candidate</th>
                                                        <th>Assessor</th>
                                                    </tr> DARSHIL CHANGED -->

                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Type</th>
                                                        <th>Trinity</th>
                                                        <th>Start Date/Time</th>
                                                        <th>End Date/Time</th>
                                                        <th>Last Assessor Date</th>
                                                        <th>Status</th>
                                                        <th>Candidate Status</th>
                                                        <th>Actions</th>
                                                    </tr>

                                                </thead>
                                                <tbody class="notranslate"><!-- added by shital LM: 07:03:2024 -->
                                                    
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>            
        </div>  
        <div class="modal fade" id="LoadModalFilter" role="basic" aria-hidden="true" data-width="400">
            <div class="modal-dialog modal-lg" style="width:1024px;">
                <div class="modal-content">
                    <div class="modal-body" id="modal-body">
                        <img src="<?php echo $asset_url; ?>/assets/uploads/avatar/loading.gif" alt="" class="loading">
                        <span>
                            &nbsp;&nbsp;Loading... </span>
                    </div>
                </div>
            </div>
        </div>
    <div class="modal fade" id="stack2" role="basic" aria-hidden="true" data-width="200">
        <div class="modal-dialog modal-sm" style="width:1024px;">
            <div class="modal-content">
                <div class="modal-body" id="modal-body">
                    <img src="<?php echo $asset_url; ?>/assets/uploads/avatar/loading.gif" alt="" class="loading">
                    <span>
                        &nbsp;&nbsp;Loading... </span>
                </div>
            </div>
        </div>
    </div>
        <div class="modal fade" id="stack3" role="basic" aria-hidden="true" data-width="200">
            <div class="modal-dialog modal-sm" style="width:1024px;">
                <div class="modal-content">
                    <div class="modal-body" id="modal-body">
                        <img src="<?php echo $asset_url; ?>/assets/uploads/avatar/loading.gif" alt="" class="loading">
                        <span>
                            &nbsp;&nbsp;Loading... </span>
                    </div>
                </div>
            </div>
        </div>
        
        <?php $this->load->view('inc/inc_footer_script'); ?>
        <script src="<?php echo $asset_url; ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
        <script>            
            var frmAssessment   = document.frmAssessment;
            var Base_url        = "<?php echo base_url(); ?>";
            var superaccess     = "<?php echo $superaccess; ?>";
            var is_supervisor   = "<?php echo $is_supervisor; ?>";
    </script>
    <script src="<?php echo $asset_url; ?>assets/customjs/trinity_status_validation.js" type="text/javascript"></script>
    <script src="<?php echo $asset_url; ?>assets/customjs/trinity_ass_validation.js" type="text/javascript"></script>
    <script>
            jQuery(document).ready(function() {                
                $('.all').click(function () {
                    if ($(this).is(':checked')) {
                        $("input[name='id[]']").prop('checked', true);
                    } else {
                        $("input[name='id[]']").prop('checked', false);
                    }
                });
                DatatableRefresh();
            });            
            function LoadConfirmDialog(content,opt){
                $.confirm({
                    title: 'Confirm!',
                    content: content,
                    buttons: {
                        confirm:{
                        text: 'Confirm',
                        btnClass: 'btn-orange',
                        keys: ['enter', 'shift'],
                        action: function(){
                            $.ajax({
                                type: "POST",
                                data: $('#frmAssessment').serialize(),
                                // url: "<?php echo base_url(); ?>assessment/record_actions/"+opt,      DARSHIL CHANGED
                                url: "<?php echo base_url(); ?>view_trinity/record_actions/"+opt,
                                success: function (response_json) {
                                    var response= JSON.parse(response_json);
                                    ShowAlret(response.message,response.alert_type);
                                    DatatableRefresh();
                                }
                            });
                        }
                    },
                    cancel: function () {
                         this.onClose();
                    }
                    }
                });
            }
            function ValidCheckbox(opt){
                var Check = getCheckCount();
                if (Check > 0){
                    if( opt == 1 ){
                       LoadConfirmDialog("Confirm Active Status ?",opt);  
                    }
                    else if( opt == 2 ){
                        LoadConfirmDialog("Confirm InActive Status ?",opt);
                    }
                    else if( opt == 3 ){
                        LoadConfirmDialog("Are you sure you want to delete ?",opt);
                    }   
                }else{
                    ShowAlret("Please select record from the list.",'error');
                    return false;
                }   
            }
            $(function(){
                $("#bulk_active").click(function(){
                    ValidCheckbox(1);
                });  
                $("#bulk_inactive").click(function(){
                    ValidCheckbox(2);
                });  
                $("#bulk_delete").click(function(){
                    ValidCheckbox(3);
                });  
            });
            function LoadDeleteDialog(Id){
                $.confirm({
                    title: 'Confirm!',
                    content: " Are you sure you want to delete Assessment ? ",
                    buttons: {
                        confirm:{
                        text: 'Confirm',
                        btnClass: 'btn-orange',
                        keys: ['enter', 'shift'],
                        action: function(){
                            $.ajax({
                                type: "POST",
                                // url: "<?php echo base_url();?>assessment/remove/"+Id,        DARSHIL CHANGED
                                url: "<?php echo base_url();?>view_trinity/remove/"+Id,
                                success: function (response_json) {
                                    var response= JSON.parse(response_json);
                                    ShowAlret(response.message,response.alert_type);
                                    DatatableRefresh();
                                }
                            });
                        }
                    },
                    cancel: function () {
                         this.onClose();
                    }
                    }
                });
            }
            function getCheckCount(){
                var x = 0;
                for (var i = 0; i < frmAssessment.elements.length; i++){
                    if (frmAssessment.elements[i].checked == true){
                        x++;
                    }
                }
                return x;
            }
            function ResetFilter() {                
                document.FilterFrm.reset();
                DatatableRefresh();
            }
            function DatatableRefresh() {
                //if(is_supervisor==1 || superaccess==1){                    
                    var view_type = $('#view_type').val();
                    //console.log(view_type);
                //}else{
                //    var view_type = 0;                    
                //}                
//                if (!jQuery().dataTable) {
//                    return;
//                }
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
                            "previous":"Prev",
                            "next": "Next",
                            "last": "Last",
                            "first": "First"
                        }
                    },                    
                    "bStateSave": false,
                    "lengthMenu": [
                        [5,10,15,20, -1],
                        [5,10,15,20, "All"]
                    ],
                    "pageLength": 10,            
                    "pagingType": "bootstrap_full_number",
                    "columnDefs": [                        
                        // {'width': '30px','orderable': true,'searchable': true,'targets': [0]}, 
                        // {'width': '30px','orderable': false,'searchable': false,'targets': [1]}, 
                        // {'width': '30px','orderable': true,'searchable': true,'targets': [2]},                        
                        // {'width': '100px','orderable': true,'searchable': true,'targets': [3]},
                        // {'width': '100px','orderable': true,'searchable': true,'targets': [4]},
                        // {'width': '30px','orderable': true,'searchable': true,'targets': [5]},
                        // {'width': '30px','orderable': false,'searchable': false,'targets': [6]},
                        // {'width': '30px','orderable': false,'searchable': false,'targets': [7]},
                        // {'width': '30px','orderable': false,'searchable': false,'targets': [8]},
                        // {'width': '30px','orderable': false,'searchable': false,'targets': [9],'visible':(view_type==1? false : true)}
                        
                        //DARSHIL- commented above and added below columns

                        {
                            'width': '30px',
                            'orderable': true,
                            'searchable': true,
                            'targets': [0]
                        },
                        {
                            'width': '30px',
                            'orderable': false,
                            'searchable': false,
                            'targets': [1]
                        },
                        {
                            'width': '30px',
                            'orderable': true,
                            'searchable': true,
                            'targets': [2]
                        },
                        {
                            'width': '100px',
                            'orderable': true,
                            'searchable': true,
                            'targets': [3]
                        },
                        {
                            'width': '100px',
                            'orderable': true,
                            'searchable': true,
                            'targets': [4]
                        },
                        {
                            'width': '30px',
                            'orderable': true,
                            'searchable': true,
                            'targets': [5]
                        },
                        {
                            'width': '30px',
                            'orderable': false,
                            'searchable': false,
                            'targets': [6]
                        },
                        {
                            'width': '30px',
                            'orderable': false,
                            'searchable': false,
                            'targets': [7]
                        },
                        {
                            'width': '30px',
                            'orderable': false,
                            'searchable': false,
                            'targets': [8],
                            'visible': (view_type==1 ? false : true)
                        }
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    "processing": true,
                    "serverSide": true,
                    // "sAjaxSource": "<?php //echo base_url() . 'assessment/DatatableRefresh'; ?>", DARSHIL CHANGED
                    "sAjaxSource": "<?php echo base_url() . 'view_trinity/DatatableRefresh'; ?>",
                    "fnServerData": function (sSource, aoData, fnCallback) {
                        aoData.push({name: 'filter_status', value: $('#filter_status').val()});
                        // aoData.push({name: 'assessment_type', value: $('#assessment_type').val()});  DARSHIL COMMENTED
			            // aoData.push({name: 'question_type', value: $('#question_type').val()});  DARSHIL COMMENTED
                        aoData.push({name: 'view_type', value: view_type});
                        $.getJSON(sSource, aoData, function (json) {
                            fnCallback(json);
                        });
                    },
                    "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                        return nRow;
                    }
                    , "fnFooterCallback": function (nRow, aData) {
                    }
                });
                                
            }
            function ResetFilter() {
                $('.select2me').val(null).trigger('change');
                document.FilterFrm.reset();
                DatatableRefresh();
            }
        </script>
    </body>
</html>