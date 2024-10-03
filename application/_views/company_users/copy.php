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
                                <span>CMS Users</span>
                                <i class="fa fa-circle"></i>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <a href="<?php echo $base_url ?>company_users" class="btn btn-sm btn-default pull-right">Back</a>&nbsp;
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
                            <div id="errordiv" class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                                <br><span id="errorlog"></span>
                            </div>
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption caption-font-24">
                                        Copy CMS User
                                        <div class="tools"> </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="tabbable-line tabbable-full-width">
                                        <ul class="nav nav-tabs" id="tabs">
                                            <li class="active">
                                                <a href="#tab_overview" data-toggle="tab" id="tab1">Overview</a>
                                            </li>
                                            <!-- <li><a href="javascript:void(0);" data-toggle="tab">Trainer rights</a></li>
                                                    <li><a href="javascript:void(0);" data-toggle="tab">Workshop rights</a></li> -->
                                            <li><a href="javascript:void(0);" data-toggle="tab">Change Avatar</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_overview">
                                                <form id="frmUsers" name="frmUsers" method="POST" action="<?php echo $base_url; ?>company_users/update/<?php echo base64_encode($result->userid); ?>">
                                                    <?php if ($this->mw_session['company_id'] == "") { ?>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Company Name<span class="required"> *
                                                                        </span></label>
                                                                    <select id="company_id" name="company_id" class="form-control input-sm select2" placeholder="Please select" onchange="Rolechange()">
                                                                        <option value="">Please Select</option>
                                                                        <?php foreach ($CompanySet as $cs) { ?>
                                                                            <option value="<?php echo $cs->id ?>" <?php echo ($result->company_id == $cs->id ? 'Selected' : '') ?>><?php echo $cs->company_name ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Employee Code</label>
                                                                <input type="text" name="emp_id" id="emp_id" maxlength="50" value="" class="form-control input-sm">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Region<span class="required"> * </span></label>
                                                                <select id="region_id" name="region_id" class="form-control input-sm select2" placeholder="Please select">
                                                                    <option value="">Please Select</option>
                                                                    <?php foreach ($RegionResult as $region) { ?>
                                                                        <option value="<?php echo $region->id ?>" <?php echo ($region->id == $result->region_id ? 'selected' : '') ?>><?php echo $region->region_name ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Login Type<span class="required"> *
                                                                    </span></label>
                                                                <select id="login_type" name="login_type" class="form-control input-sm select2" placeholder="Please select">
                                                                    <?php foreach ($LoginType as $lt) { ?>
                                                                        <option value="<?php echo $lt->id ?>" <?php echo ($result->login_type == $lt->id) ? 'selected' : ''; ?>><?php echo $lt->name ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Login ID<span class="required"> *
                                                                    </span></label>
                                                                <input type="text" name="loginid" id="loginid" maxlength="80" class="form-control input-sm" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Password<span class="required"> *
                                                                    </span></label>
                                                                <input type="password" name="password" id="password" maxlength="50" class="form-control input-sm">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Confirm Password<span class="required">
                                                                        * </span></label>
                                                                <input type="password" name="confirmpassword" id="confirmpassword" maxlength="50" class="form-control input-sm">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Department/Division<span class="required"> *
                                                                    </span></label>
                                                                <select id="division_id" name="division_id" class="form-control input-sm select2" placeholder="Please select">
                                                                    <?php
                                                                    foreach ($division_id as $dt) { ?>
                                                                        <option value="">Please Select </option>
                                                                        <option value="<?php echo $dt->id ?>" <?php echo ($dt->id == $result->division_id ? 'selected' : '') ?>><?php echo $dt->division_name ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Designation<span class="required"> *
                                                                    </span></label>
                                                                <select id="designation" name="designation" class="form-control input-sm select2">
                                                                    <option value="">Please Select</option>
                                                                    <?php foreach ($DesignationResult as $desig) { ?>
                                                                        <option value="<?php echo $desig->id ?>" <?php echo ($desig->id == $result->designation_id ? 'selected' : '') ?>><?php echo $desig->description ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Role<span class="required"> * </span></label>
                                                                <select id="roleid" name="roleid" class="form-control input-sm select2" placeholder="Please select">
                                                                    <option value="">Please Select</option>
                                                                    <?php foreach ($Role as $rl) { ?>
                                                                        <option value="<?php echo $rl->arid ?>" <?php echo ($rl->arid == $result->role ? 'selected' : '') ?>>
                                                                            <?php echo $rl->rolename ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Status<span class="required"> * </span></label>
                                                                <select id="status" name="status" class="form-control input-sm select2" placeholder="Please select">
                                                                    <option value="1" <?php echo ($result->status == 1) ? 'selected' : ''; ?>>Active</option>
                                                                    <option value="0" <?php echo ($result->status == 0) ? 'selected' : ''; ?>>In-Active</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="my-line"></div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Salutation</label>
                                                                <select id="salutation" name="salutation" class="form-control input-sm select2" placeholder="Please select">
                                                                    <option value="Mr." <?php echo ($result->status == 'Mr.') ? 'selected' : ''; ?>>
                                                                        Mr.</option>
                                                                    <option value="Mrs." <?php echo ($result->status == 'Mrs.') ? 'selected' : ''; ?>>
                                                                        Mrs.</option>
                                                                    <option value="Miss" <?php echo ($result->status == 'Miss') ? 'selected' : ''; ?>>
                                                                        Miss</option>
                                                                    <option value="Dr." <?php echo ($result->status == 'Dr.') ? 'selected' : ''; ?>>
                                                                        Dr.</option>
                                                                    <option value="Prof." <?php echo ($result->status == 'Prof.') ? 'selected' : ''; ?>>Prof.</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">First name<span class="required"> *
                                                                    </span></label>
                                                                <input type="text" name="first_name" id="first_name" maxlength="50" class="form-control input-sm" value="<?php echo $result->first_name; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Last name<span class="required"> *
                                                                    </span></label>
                                                                <input type="text" name="last_name" id="last_name" maxlength="50" class="form-control input-sm" value="<?php echo $result->last_name; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Email<span class="required"> *
                                                                    </span></label>
                                                                <input type="text" name="email" id="email" maxlength="250" class="form-control input-sm" value="<?php echo $result->email; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Mobile No.</label>
                                                                <input type="text" name="mobile" id="mobile" maxlength="50" class="form-control input-sm" value="<?php echo $result->mobile; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Alternate Email</label>
                                                                <input type="text" name="email2" id="email2" maxlength="250" class="form-control input-sm" value="<?php echo $result->email2; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Contact No</label>
                                                                <input type="text" name="contactno" id="contactno" maxlength="50" class="form-control input-sm" value="<?php echo $result->contactno; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">Fax</label>
                                                                <input type="text" name="fax" id="fax" maxlength="50" class="form-control input-sm" value="<?php echo $result->fax; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="my-line"></div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="">Address 1</label>
                                                                <input type="text" name="address" id="address" maxlength="250" class="form-control input-sm" value="<?php echo $result->address1; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="">Address 2</label>
                                                                <input type="text" name="address2" id="address2" maxlength="250" class="form-control input-sm" value="<?php echo $result->address2; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Country</label>
                                                                <select id="country_id" name="country_id" class="form-control input-sm select2" placeholder="Please select">
                                                                    <option value="">Please Select</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <select id="state_id" name="state_id" class="form-control input-sm select2" placeholder="Please select">
                                                                    <option value="">Please Select</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="">City</label>
                                                                <select id="city_id" name="city_id" class="form-control input-sm select2" placeholder="Please select">
                                                                    <option value="">Please Select</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Note</label>
                                                                <textarea rows="4" class="form-control input-sm" name="description" placeholder=""><?php echo $result->note; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 text-right">
                                                            <button type="button" id="role-submit" name="role-submit" data-loading-text="Please wait..." class="btn btn-orange mt-ladda-btn ladda-button mt-progress-demo" data-style="expand-right" onclick="SaveUserData();">
                                                                <span class="ladda-label">Save & Next</span>
                                                            </button>
                                                            <a href="<?php echo site_url("company_users"); ?>" class="btn btn-default btn-cons">Cancel</a>
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
            </div>
        </div>
    </div>
    <?php $this->load->view('inc/inc_footer_script'); ?>

    <script>
        var NewUsersArrray = [];
        var oTable = null;
        var base_url = "<?php echo $base_url; ?>";
        var AddEdit = 'C';
        var Encode_id = "<?php echo base64_encode($result->userid); ?>";
    </script>
    <script type="text/javascript" src="<?php echo $asset_url; ?>assets/customjs/cmsusers_validation.js"></script>
    <script>
        jQuery(document).ready(function() {
            $('#country_id').select2({
                placeholder: '',

                multiple: false,
                separator: ',',
                ajax: {
                    url: base_url + "company_users/ajax_populate_country",
                    dataType: 'json',
                    quietMillis: 100,
                    data: function(term, page) {
                        return {
                            search: term,
                            page_limit: 10
                        };
                    },
                    results: function(data, page) {
                        var more = (page * 30) < data.total_count;
                        return {
                            results: data.results,
                            more: more
                        };
                    }
                },
                initSelection: function(element, callback) {
                    return $.getJSON(base_url + "company_users/ajax_populate_country?id=<?php echo $result->country; ?>", null, function(data) {
                        return callback(data);
                    });
                }
            });
            $("#country_id").select2("trigger", "select", {
                data: {
                    id: "<?php echo $result->country; ?>",
                    text: "<?php echo $result->country_name; ?>"
                }
            });
            $('#state_id').select2({
                placeholder: '',

                multiple: false,
                separator: ',',
                ajax: {
                    url: base_url + "company_users/ajax_populate_state",
                    dataType: 'json',
                    quietMillis: 100,
                    data: function(term, page) {
                        return {
                            country_id: $('#country_id').val(),
                            search: term,
                            page_limit: 10
                        };
                    },
                    results: function(data, page) {
                        var more = (page * 30) < data.total_count;
                        return {
                            results: data.results,
                            more: more
                        };
                    }
                },
                initSelection: function(element, callback) {
                    return $.getJSON(base_url + "company_users/ajax_populate_state?id=<?php echo $result->state; ?>", null, function(data) {
                        return callback(data);
                    });
                }
            });
            $("#state_id").select2("trigger", "select", {
                data: {
                    id: "<?php echo $result->state; ?>",
                    text: "<?php echo $result->state_name; ?>"
                }
            });
            $('#city_id').select2({
                placeholder: '',

                multiple: false,
                separator: ',',
                ajax: {
                    url: base_url + "company_users/ajax_populate_city",
                    dataType: 'json',
                    quietMillis: 100,
                    data: function(term, page) {
                        return {
                            state_id: $('#state_id').val(),
                            search: term,
                            page_limit: 10
                        };
                    },
                    results: function(data, page) {
                        var more = (page * 30) < data.total_count;
                        return {
                            results: data.results,
                            more: more
                        };
                    }
                },
                initSelection: function(element, callback) {
                    return $.getJSON(base_url + "company_users/ajax_populate_city?id=<?php echo $result->city; ?>", null, function(data) {
                        return callback(data);
                    });
                }
            });
            $("#city_id").select2("trigger", "select", {
                data: {
                    id: "<?php echo $result->city; ?>",
                    text: "<?php echo $result->city_name; ?>"
                }
            });
        });
    </script>
</body>

</html>