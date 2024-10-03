<?php
defined('BASEPATH') or exit('No direct script access allowed');
$base_url = base_url();
$asset_url = $this->config->item('assets_url');
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
                                <span>Organisation</span>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Information Form</span>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <a href="<?php echo $base_url ?>information_form" class="btn btn-sm btn-default pull-right">Back</a>&nbsp;
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
                                        Copy Information Form
                                        <div class="tools"> </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <form id="feedbackform" name="feedbackform" method="POST">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_overview">
                                                <div id="errordiv" class="alert alert-danger display-hide">
                                                    <button class="close" data-close="alert"></button>
                                                    You have some form errors. Please check below.
                                                    <br><span id="errorlog"></span>
                                                </div>
                                                <div class="row">
                                                    <?php if ($Company_id == "") { ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="">Company Name<span class="required"> * </span></label>
                                                                <span class="notranslate"><select id="company_id" name="company_id" class="form-control input-sm select2" placeholder="Please select" style="width:100%">
                                                                        <?php foreach ($CompanySet as $cmp) { ?>
                                                                            <option value="<?= $cmp->id; ?>" <?php echo ($HeadResult->company_id == $cmp->id ? 'Selected' : ''); ?>><?= $cmp->company_name; ?> </option>
                                                                        <?php } ?>
                                                                    </select></span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Title<span class="required"> * </span></label>
                                                            <input type="text" name="form_name" id="form_name" maxlength="255" class="notranslate form-control input-sm" value="<?php echo $HeadResult->form_name; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Short Description</label>
                                                            <textarea rows="4" class="form-control input-sm notranslate" id="short_description" maxlength="150" name="short_description" placeholder=""><?php echo $HeadResult->short_description; ?></textarea>
                                                            <span class="text-muted">(Max 150 Characters)</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Status<span class="required"> * </span></label>
                                                            <span class="notranslate"><select id="status" name="status" class="form-control input-sm select2" placeholder="Please select">
                                                                    <option value="1" <?php echo ($HeadResult->status == 1) ? 'selected' : ''; ?>>Active</option>
                                                                    <option value="0" <?php echo ($HeadResult->status == 0) ? 'selected' : ''; ?>>In-Active</option>
                                                                </select></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row margin-bottom-10">
                                                    <div class="col-md-12 text-right">
                                                        <button type="button" id="confirm" name="confirm" data-loading-text="Please wait..." class="btn btn-orange mt-ladda-btn ladda-button mt-progress-demo" data-style="expand-left" onclick="ConfirmField()">
                                                            <span class="ladda-label">Add Field</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-hover" id="FieldDatatable" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="20%">Display Name</th>
                                                                    <th width="20%">Type</th>
                                                                    <th width="20%">Data</th>
                                                                    <th width="5%">Mandatory</th>
                                                                    <th width="10%">Order</th>
                                                                    <th width="10%">Status</th>
                                                                    <th width="5%"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="notranslate">
                                                                <?php
                                                                $EditField = count($Result);
                                                                $key = 0;
                                                                if ($EditField > 0) {
                                                                    foreach ($Result as $fr) {
                                                                        $key++;
                                                                ?>
                                                                        <tr id="Row-<?php echo $key; ?>" class="notranslate">
                                                                            <td><input type="text" name="New_disp_name[]" id="disp_name<?php echo $key; ?>" value="<?php echo $fr->field_display_name ?>" class="form-control input-sm" maxlength="255" style="width:100%"> </td>
                                                                            <td><select id="field_type<?php echo $key; ?>" name="New_fieldtype_id[]" class="form-control input-sm select2" style="width:100%" onchange="addDATA(<?php echo $key; ?>)">
                                                                                    <?php foreach ($SelectType as $ftype) { ?>
                                                                                        <option value="<?= $ftype->name; ?>" <?php echo ($fr->field_type == $ftype->name ? 'Selected' : ''); ?> class="notranslate"><?= $ftype->name; ?> </option>
                                                                                    <?php } ?>
                                                                                </select></td>
                                                                            <td><textarea rows="3" class="form-control input-sm" id="data_area<?php echo $key; ?>" maxlength="150" name="New_data_area[]" <?php echo ($fr->field_type == 'dropdown' ? '' : 'readonly') ?>><?php echo $fr->default_value ?></textarea></td>
                                                                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                                                    <input type="checkbox" class="checkboxes" name="required_id[]" value="1" <?php echo ($fr->is_required ? 'Checked' : ''); ?> id="chk<?php echo $key; ?>" onclick="setCheckBoxValue(<?php echo $key; ?>);" />
                                                                                    <input type="hidden" name="New_required_id[]" id="required_id<?php echo $key; ?>" value="<?php echo $fr->is_required; ?>">
                                                                                    <span></span>
                                                                                </label></td>
                                                                            <td><input type="number" name="New_order[]" id="order<?php echo $key; ?>" value="<?php echo $fr->field_order ?>" class="form-control input-sm" max="255" min="1" style="width:100%"> </td>
                                                                            <td><select id="New_field_status<?php echo $key; ?>" name="New_field_status[]" class="form-control input-sm select2" style="width:100%">
                                                                                    <option value="1" <?php echo ($fr->status ? 'Selected' : ''); ?>>Active</option>
                                                                                    <option value="0" <?php echo (!$fr->status ? 'Selected' : ''); ?>>In-Active</option>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" id="remove" name="remove" class="btn btn-danger btn-sm" onclick="RowDelete(<?php echo $key; ?>)"><i class="fa fa-times"></i></button>
                                                                            </td>
                                                                        </tr>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <button type="button" id="feedback-submit" name="questionset-submit" data-loading-text="Please wait..." class="btn btn-orange mt-ladda-btn ladda-button mt-progress-demo" data-style="expand-left" onclick="SaveFormData();">
                                                            <span class="ladda-label">Submit</span>
                                                        </button>
                                                        <a href="<?php echo site_url("information_form"); ?>" class="btn btn-default btn-cons">Cancel</a>
                                                    </div>
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

        </div>
    </div>
    <?php //$this->load->view('inc/inc_quick_sidebar');    
    ?>
    </div>
    <?php //$this->load->view('inc/inc_footer');     
    ?>
    </div>
    <?php //$this->load->view('inc/inc_quick_nav');  
    ?>
    <?php $this->load->view('inc/inc_footer_script'); ?>

    <script>
        var FieldArrray = [];
        var SelectedArrray = [];
        var Totalfield = <?php echo $key + 1; ?>;
        var trainer_no;
        var base_url = "<?php echo $base_url; ?>";
        var Encode_id = "<?php echo base64_encode($HeadResult->id); ?>";
        var AddEdit = 'C';

        jQuery(document).ready(function() {
            $('.select2').select2().on('select2:open', function(e) {
                $('.select2-container').addClass('notranslate');
                $('.select2').addClass('notranslate');
            });
            $('.select2').select2().on('select2', function(e) {
                $('.select2-container').addClass('notranslate');
                $('.select2').addClass('notranslate');
            });
        });
    </script>
    <script type="text/javascript" src="<?php echo $asset_url; ?>assets/customjs/informationform_validation.js"></script>
</body>

</html>