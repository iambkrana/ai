<div class="modal-header no-padding">
    <button type="button" id="CloseModalBtn" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">USER-WISE OVERALL REPORT</h4>
    <!-- <input id="hitmap_count" type="hidden" value="" name="hitmap_count"> -->
    <a id="image_button" class="btn orange btn-sm btn-outline"
        style="margin-top: -10px; margin-left: 92%; margin-bottom: -20px;" onclick="heatmapimage()">
        <i class="fa fa-file-excel-o"></i> Download</a>
    </a>
    <a id="export_button" class="btn orange btn-sm btn-outline"
        style="margin-top: -50px; margin-left: 85%; margin-bottom: -26px;" onclick="export_heatmap_data()">
        <i class="fa fa-file-excel-o"></i> Export</a>
    </a>
</div>
<div class="row">
    <form name="AssessmentScore" id="AssessmentScore" method="post"
        action="<?php echo base_url() . 'reports_competency/export_regionwise_table' ?>">
        <input id="hitmap_count" type="hidden" value="" name="hitmap_count">
        <input id="rg_id" type="hidden" value="" name="rg_id">
        <input id="rg_ass_id" type="hidden" value="" name="rg_ass_id">
        <div class="col-md-12 ">
            <div class="table-scrollable" style="margin: 10px" id="append_table">

            </div>
        </div>
    </form>
</div>
<script>
    var base_url = "<?php echo base_url(); ?>";
    var assessment_id = "<?php echo $assessment_id; ?>";
    var region_id = "<?php echo $region_id; ?>";
    $(document).ready(function () {
        RegionWiseTable(assessment_id, region_id);
    });
    function RegionWiseTable(assessment_id, region_id) {
        $('.color-set').addClass("tr-background");
        $.ajax({
            url: base_url + "reports_competency/regionwise_table",
            type: 'POST',
            data: { assessment_id: assessment_id, region_id: region_id },
            beforeSend: function () {
                customBlockUI();
            },
            success: function (data) {
                if (data != '') {
                    var json = jQuery.parseJSON(data);
                    var count = json.user_count;
                    $('#hitmap_count').val(count);
                    $('#rg_id').val(json.region_id);
                    $('#rg_ass_id').val(json.assessment_id);
                    $('#append_table').html(json['regiontable_graph']);
                }
                customunBlockUI();
            }
        });
    }

</script>