<div class="modal-header">
    <button type="button" id="CloseModalBtn" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Search Users</h4>
</div>
<div class="modal-body">
    <form name="UserForm" id="UserForm">
        <div class="portlet light">
            <div class="form-body">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-6">       
                        <div class="form-group">
                            <label class="control-label col-md-4">Trainee Region</label>
                            <div class="col-md-8" style="padding:0px;">
                            <select id="flt_region_id" name="flt_region_id" class="form-control input-sm select2" placeholder="Please select"  style="width: 100%" onchange="LoadFilterUserData();" >
                                <option value="">All Region</option>
                                <?php if(count($RegionList) > 0){
                                    foreach ($RegionList as $Rgn) { ?>
                                    <option value="<?= $Rgn->id; ?>" ><?php echo $Rgn->region_name; ?></option>
                                <?php } 
                                } ?>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4">Department/Division</label>
                            <div class="col-md-8" style="padding:0px;">
                                <select id="flt_division_id" name="flt_division_id" class="form-control input-sm select2" placeholder="Please select" style="width: 100%" onchange="LoadFilterUserData();">
                                    <!-- < ?php if ($login_type != 2) { ?>
                                        <option value="">All Division</option>
                                    < ?php } ?> -->
                                    <?php if (count($division_list) > 0) {
                                        foreach ($division_list as $dvn) { ?>
                                            <option value="<?= $dvn->id; ?>" <?= ($dvn->id == $division_id ? 'selected': ''); ?>><?php echo $dvn->division_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">       
                        <div class="form-group">
                            <label class="control-label col-md-4">Department</label>
                            <div class="col-md-8" style="padding:0px;">
                            <select id="flt_department_id" name="flt_department_id" class="form-control input-sm select2" placeholder="Please select"  style="width: 100%" onchange="LoadFilterUserData();" >
                                <option value="">All Department</option>
                                < ?php if(count($DepartmentList)>0){
                                    foreach ($DepartmentList as $div) { ?>
                                    <option value="< ?= $div->department; ?>" >< ?php echo $div->department; ?></option>
                                < ?php } }?>
                            </select>
                            </div>
                        </div>
                    </div> -->
                </div>
                <table class="table table-striped table-bordered table-hover" id="UserFilterTable" width="100%">
                    <thead>
                        <tr>
                            <th>ID #</th>
                            <th>Trainee Region</th>
                            <th>Department/Division</th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>Mobile No</th>
                            <th>Area</th>
                            <th class="table-checkbox ">
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes all" name="chk"  id="chk" />
                                <span></span>
                            </label>
                        </tr>
                    </thead>
                    <tbody class="notranslate"><!-- Add class by shital for language module :06:02:2024 --></tbody>
                </table>
            </div>
        </div>          
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-orange" onclick="confirm_allow_users();" >Confirm</button>
</div>
<script type="text/javascript">
    var frm1=document.UserForm;
    jQuery(document).ready(function () {
        LoadFilterUserData();
        $('.all').click(function () {
            if ($(this).is(':checked')) {
                $("input[name='id[]']").prop('checked', true);                                                
            } else {
                $("input[name='id[]']").prop('checked', false);
            }
            $("input[name='id[]']").each(function( index ) {
                SelectedUsers($( this ).val());
                //console.log( index + ": " + $( this ).val() );
            });
        });

        //-- Add by shital for language module :06:02:2024 
        $('.select2, .select2-multiple').select2().on('select2:open', function (e) {
                $('.select2-container').addClass('notranslate');
                $('.select2').addClass('notranslate');
            });
            $('.select2, .select2-multiple').select2().on('select2', function (e) {
                $('.select2-container').addClass('notranslate');
                $('.select2').addClass('notranslate');
            });
            $('.select2, .select2-multiple').wrap('<span class="notranslate">');
    });
</script>
