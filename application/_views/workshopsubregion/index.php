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
        <!--datattable CSS  Start-->
        <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!--datattable CSS  End-->
        <?php         
        $this->load->view('inc/inc_htmlhead'); ?>
        
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
                                    <span>Organisation</span>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Workshop Sub-Region</span>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Subregion Listing</span>
                                </li>
                            </ul>
                            <?php if($acces_management->allow_add){ ?>
                            <div class="page-toolbar">
                                <a class="btn btn-sm btn-orange pull-right" id="btnaddpanel3"  href="<?php echo base_url() . 'workshopsubregion/LoadModal/' ?>" 
                                   data-target="#LoadModalFilter" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;Create New </a>
                            </div>
                            <?php } ?>
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
                                                <?php if ($Company_id == "") { ?>
                                                <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Company&nbsp;</label>
                                                                    <div class="col-md-9" style="padding:0px;">
                                                                        <select id="filter_cmp_id" name="filter_cmp_id" class="form-control input-sm select2me filter_list" placeholder="Please select"  style="width: 100%" onchange="getFilterdWorkshopRegion();">
                                                                            <option value="">All Company</option>
                                                                            <?php foreach ($CompnayResultSet as $cmp) { ?>
                                                                                <option value="<?= $cmp->id; ?>"><?php echo $cmp->company_name; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                      </div>
                                                <div class="clearfix margin-top-10"></div>
                                                 <?php } ?>  
                                                    <div class="row ">
                                                    <div class="col-md-6">       
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Region</label>
                                                                <div class="col-md-9" style="padding:0px;">
                                                                <select id="filter_region_id" name="filter_region_id" class="form-control input-sm select2me filter_list" style="width: 100%" placeholder="Please select">
                                                                    <option value="">All Region</option>
                                                                    <?php if(count($Region)>0){
                                                                            foreach ($Region as $Row) { ?>
                                                                                <option value="<?php echo $Row->id ?>"><?php echo $Row->region_name ?></option>
                                                                        <?php  }
                                                                    } ?>
                                                                </select>
                                                            </div></div>
                                                        </div>                                                    
                                                    <div class="col-md-6">    
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Status</label>
                                                                <div class="col-md-9" style="padding:0px;">
                                                                <select id="filter_status" name="filter_status" class="form-control input-sm select2me filter_list" style="width: 100%" placeholder="Please select" >
                                                                    <option value="">All</option>
                                                                    <option value="1" >Active</option>
                                                                    <option value="0">In-Active</option>
                                                                </select>
                                                            </div></div>
                                                    </div>
                                                </div>                                               
                                                 <div class="clearfix margin-top-20"></div>
                                                
                                                <div class="clearfix margin-top-20"></div>
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
                                    <div class="portlet-title">
                                        <div class="caption caption-font-24">
                                            Manage Sub-Region 
                                           <div class="tools"> </div>  
                                        </div>
                                        <?php if ($acces_management->allow_access OR
                                                  $acces_management->allow_view OR
                                                  $acces_management->allow_add OR
                                                  $acces_management->allow_edit OR
                                                  $acces_management->allow_delete OR
                                                  $acces_management->allow_print OR
                                                  $acces_management->allow_export){ 
                                        ?>
                                        <div class="actions">
                                            <div class="btn-group pull-right">
                                                <button type="button" class="btn orange btn-sm btn-outline dropdown-toggle" data-toggle="dropdown">Bulk Actions
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    <?php if ($acces_management->allow_add OR $acces_management->allow_edit){?>
                                                    <li>
                                                        <a id="bulk_active" href="javascript:;">
                                                            <i class="fa fa-check"></i> Active
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a id="bulk_inactive" href="javascript:;">
                                                            <i class="fa fa-close"></i> In Active 
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                    <?php if ($acces_management->allow_delete){?>
                                                    <li>
                                                        <a id="bulk_delete" href="javascript:;">
                                                            <i class="fa fa-trash-o"></i> Delete 
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                    
                                                    
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <form id="frmWsubregion" name="frmWsubregion" method="post">
                                            <table class="table  table-bordered table-hover table-checkable order-column" id="index_table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                                <input type="checkbox" class="all group-checkable" name="check" id="check" data-set="#index_table .checkboxes" />
                                                                <span></span>
                                                            </label>
                                                        </th>
                                                        <th>ID</th>
                                                        <th>Company Name</th>
                                                        <th>Region</th>
                                                        <th>Sub-Region</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="notranslate"></tbody>

                                            </table>
                                        </form>
                                         <div class="modal fade" id="LoadModalFilter" role="basic" aria-hidden="true" data-width="400">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img src="<?php echo base_url(); ?>/assets/uploads/avatar/loading.gif" alt="" class="loading">
                                                        <span>
                                                            &nbsp;&nbsp;Loading... </span>
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
        <?php $this->load->view('inc/inc_footer_script'); ?>
<script src="<?php echo $asset_url; ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
        <script>
            var wsubregion_edit_id='';
            var region_id='';            
            function Redirect(url)
            {
                window.location = url;
            }
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
            
            function resetDATA(){
                //$('#region_id,#company_id').val(null).trigger('change');
                $('.select2me').select("val", "");
                $('.select2me').val(null).trigger('change');
                document.FilterFrm.reset();
            }
            function getComapnywiseWorkshopRegion(){
                   $.ajax({
                    type: "POST",
                    data: "data=" + $('#company_id').val(),
                    async:false,
                    url: "<?php echo base_url(); ?>workshopsubregion/ajax_company_region",
                    beforeSend: function () {
                    	customBlockUI();
                    },
                        success: function (msg) {
                        if (msg != '') {
                            var Oresult = jQuery.parseJSON(msg);
                            var RegionMSt = Oresult['result'];                           
                            var option='<option value="">Please Select</option>';
                            for (var i = 0; i < RegionMSt.length; i++) {
                                option += '<option value="' + RegionMSt[i]['id'] + '" '+(region_id==RegionMSt[i]['id'] ? 'Selected':'')+'  >' + RegionMSt[i]['region_name'] + '</option>';
                            }
                            $('#region_id').empty();
                            $('#region_id').append(option);
                            //$("#region_id").trigger("change");
                        }
                        customunBlockUI();
                    }
                });
            }
            function getFilterdWorkshopRegion(){
                if($('#filter_cmp_id').val()==""){
                     $('#filter_region_id').empty();
                    return false;
                }
                   $.ajax({
                    type: "POST",
                    data: "data=" + $('#filter_cmp_id').val(),
                    async:false,
                    url: "<?php echo base_url(); ?>workshopsubregion/ajax_company_region",
                    beforeSend: function () {
                    	customBlockUI();
                    },
                    success: function (msg) {
                        if (msg != '') {
                            var Oresult = jQuery.parseJSON(msg);
                            var RegionMSt = Oresult['result'];                           
                            var option='<option value="">All</option>';
                            for (var i = 0; i < RegionMSt.length; i++) {
                                option += '<option value="' + RegionMSt[i]['id'] + '">' + RegionMSt[i]['region_name'] + '</option>';
                            }
                            $('#filter_region_id').empty();
                            $('#filter_region_id').append(option);
                            //$("#topic_id").trigger("change");
                        }
                        customunBlockUI();
                    }
                });
            }
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
                                data: $('#frmWsubregion').serialize(),
                                url: "<?php echo $base_url; ?>workshopsubregion/record_actions/"+opt,
                                beforeSend: function () {
                                    customBlockUI();
                                },
                                success: function (response_json) {
                                    var response= JSON.parse(response_json);
                                    ShowAlret(response.message,response.alert_type);
                                    DatatableRefresh();
                                    customunBlockUI();
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
                        LoadConfirmDialog("Delete Selected Workshop Subregion(s) ?",opt);
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
                    content: " Are you sure you want to delete this region ? ",
                    buttons: {
                        confirm:{
                        text: 'Confirm',
                        btnClass: 'btn-orange',
                        keys: ['enter', 'shift'],
                        action: function(){
                            $.ajax({
                                type: "POST",
                                url: "<?php echo $base_url;?>workshopsubregion/remove",
                                data: {deleteid:Id},
                                beforeSend: function () {
                                    customBlockUI();
                                },
                                success: function (response_json) {
                                    var response= JSON.parse(response_json);
                                    ShowAlret(response.message,response.alert_type);
                                    if(response.alert_type !="error"){
                                       DatatableRefresh();
                                    }
                                    customunBlockUI();
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
            function getCheckCount()
            {
                var x = 0;
                for (var i = 0; i < frmWsubregion.elements.length; i++)
                {
                    if (frmWsubregion.elements[i].checked == true)
                    {
                        x++;
                    }
                }
                return x;
            }
            function DatatableRefresh() {
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
                    //dom: 'Bfrtip',
                    //buttons: [
                    //    { extend: 'print', className: 'btn dark btn-outline' },
                    //    { extend: 'pdf', className: 'btn green btn-outline' },
                    //    { extend: 'csv', className: 'btn purple btn-outline ' }
                    //],
                    //buttons: [
                    //    'copy', 'csv', 'excel', 'pdf', 'print'
                    //],
                    //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                    "bStateSave": false,
                    "lengthMenu": [
                        [5,10,15,20, -1],
                        [5,10,15,20, "All"]
                    ],
                    "pageLength": 10,            
                    "pagingType": "bootstrap_full_number",
                    "columnDefs": [
                        {'width': '30px','orderable': false,'searchable': false,'targets': [0]}, 
                        {'width': '30px','orderable': true,'searchable': true,'targets': [1]}, 
                        {'width': '200px','orderable': true,'searchable': true,'targets': [2], "visible": <?php echo ($Company_id == "" ? 'true' : 'false'); ?>}, 
                        {'width': '200px','orderable': true,'searchable': true,'targets': [3]}, 
                        {'width': '','orderable': true,'searchable': true,'targets': [4]},
                        {'width': '30px','orderable': false,'searchable': false,'targets': [5]}, 
                        {'width': '65px','orderable': false,'searchable': false,'targets': [6]}, 
                    ],
                    "order": [
                        [1, "desc"]
                    ],
                    "processing": true,
                    "serverSide": true,
                    "sAjaxSource": "<?php echo base_url() . 'workshopsubregion/DatatableRefresh'; ?>",
                    "fnServerData": function (sSource, aoData, fnCallback) {
                        aoData.push({name: '__mode', value: 'featuredimage.ajaxload'});
                        aoData.push({name: 'filter_region_id', value: $('#filter_region_id').val()});
                        aoData.push({name: 'filter_cmp_id', value: $('#filter_cmp_id').val()});
                        aoData.push({name: 'filter_status', value: $('#filter_status').val()});
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
                $('.filter_list').val(null).trigger('change');
                //document.FilterFrm.reset();
                DatatableRefresh();
            }
        </script>
    </body>
</html>