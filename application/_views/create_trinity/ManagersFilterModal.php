<div class="modal-header">
    <button type="button" id="CloseModalBtn" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Search Managers</h4>
</div>
<div class="modal-body">
    <form name="ManagerForm" id="ManagerForm">
        <div class="portlet light">
            <div class="form-body">
                <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-6">       
                        <div class="form-group">
                            <label class="control-label col-md-4">Trainer Region</label>
                            <div class="col-md-8" style="padding:0px;">
                            <select id="flt_tregion_id" name="flt_tregion_id" class="form-control input-sm select2" placeholder="Please select"  style="width: 100%" onchange="LoadFilterManagerData();" >
                                <option value="">All Region</option>
                                <?php if(count($RegionList)>0){
                                    foreach ($RegionList as $Rgn) { ?>
                                    <option value="<?= $Rgn->id; ?>" ><?php echo $Rgn->region_name; ?></option>
                                <?php } }?>
                            </select>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover" id="ManagersFilterTable" width="100%">
                    <thead>
                        <tr>
                            <th>Trainer ID #</th>
                            <th>Trainer Region</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Designation</th>
                            <th class="table-checkbox ">
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes all_chk" name="all_chk"  id="all_chk" />
                                <span></span>
                            </label>
                        </tr>
                    </thead>
                    <tbody class="notranslate"></tbody><!-- added by shital LM: 07:03:2024 -->
                </table>
            </div>
        </div>          
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-orange" onclick="ConfirmManagers();" >Confirm</button>
</div>
        <script type="text/javascript">
            var frm1=document.ManagerForm;
            jQuery(document).ready(function () {
                LoadFilterManagerData();
                $('.all_chk').click(function () {
                    if ($(this).is(':checked')) {
                        $("input[name='Mapping_all[]']").prop('checked', true);                                                
                    } else {
                        $("input[name='Mapping_all[]']").prop('checked', false);
                    }
                    $("input[name='Mapping_all[]']").each(function( index ) {
                        SelectedManagers($( this ).val());
                        //console.log( index + ": " + $( this ).val() );
                    });
                });          
                //-- added by shital LM: 07:03:2024 --
                    $('.select2').select2().on('select2:open', function (e) {
                        $('.select2-container').addClass('notranslate');
                        $('.select2').addClass('notranslate');
                    });
                    $('.select2').select2().on('select2', function (e) {
                        $('.select2-container').addClass('notranslate');
                        $('.select2').addClass('notranslate');
                    });
                
                    $('.select2').wrap('<span class="notranslate">');
            });
        </script>
