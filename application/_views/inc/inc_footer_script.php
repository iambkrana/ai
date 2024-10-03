<?php
defined('BASEPATH') or exit('No direct script access allowed');
$asset_url = $this->config->item('assets_url');

?>
<?php $this->load->view('inc/inc_footer'); ?>
<!--[if lt IE 9]>
<script src="<?php echo $asset_url; ?>assets/global/plugins/respond.min.js"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/excanvas.min.js"></script> 
<script src="<?php echo $asset_url; ?>assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->

<!-- BEGIN CORE PLUGINS -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

<script src="<?php echo $asset_url; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<!--<script src="< ?php echo $asset_url; ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>-->
<script src="<?php echo $asset_url; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script><!--
 END CORE PLUGINS 

 BEGIN PAGE LEVEL PLUGINS 
--><!--
<script src="< ?php echo $asset_url; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="< ?php echo $asset_url; ?>assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
<script src="< ?php echo $asset_url; ?>assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>-->
<script src="<?php echo $asset_url; ?>assets/global/plugins/ladda/spin.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/ladda/ladda.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<!--<script src="< ?php echo $asset_url; ?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="< ?php echo $asset_url; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>-->
<!-- <script src="<?php //echo $asset_url; 
                    ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script> -->
<!-- <script src="<?php //echo $asset_url; 
                    ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script> -->
<!-- <script src="<?php //echo $asset_url; 
                    ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script> -->


<!--<script src="< ?php echo $asset_url; ?>assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
<script src="< ?php echo $asset_url; ?>assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
<script src="< ?php echo $asset_url; ?>assets/global/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>-->
<!--<script src="< ?php echo $asset_url; ?>assets/global/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript"></script>
<script src="< ?php echo $asset_url; ?>assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>-->
<!--<script src="< ?php echo $asset_url; ?>assets/global/plugins/jcrop/js/jquery.color.js" type="text/javascript"></script>-->
<!-- <script src="<?php //echo $asset_url; 
                    ?>assets/global/plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script> -->
<!-- <script src="<?php //echo $asset_url; 
                    ?>assets/global/plugins/jquery.sticky-kit/jquery.sticky-kit.js" type="text/javascript"></script> -->


<script src="<?php echo $asset_url; ?>assets/global/plugins/jquery-confirm/dist/jquery-confirm.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?php echo $asset_url; ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?php echo $asset_url; ?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo $asset_url; ?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<script>
    //    $(document).ajaxStop(function () {
    //        $.unblockUI();
    //    });
    //    $(document).ajaxStart(function () {
    //         $.blockUI({
    //            centerY: 0,
    //            css: {
    //                'z-index':'10052',padding: '11px', height: '45px',top: '60px', left: '', right: '10px'
    //            }
    //        });
    //    });

    //$.fn.select2.defaults.set("theme", "bootstrap");
    function customBlockUI() {
        $.blockUI({
            centerY: 0,
            css: {
                'z-index': '10052',
                padding: '11px',
                height: '45px',
                top: '60px',
                left: '',
                right: '10px'
            }
        });
    }

    function customunBlockUI() {
        $.unblockUI();
    }
    var placeholder = "";
    $(".select2, .select2-multiple").select2({
        placeholder: 'Select',
        width: '100%',
        allowClear: true
    });


    //-- added by shital: 29:01:2024 ----
    $('.select2, .select2-multiple, .select2me, .select2_rpt2, .select2_rpt,.myselect2,.ValueUnq').select2().on('select2:open', function(e) {
        $('.select2-container').addClass('notranslate');
        $('.select2').addClass('notranslate');
        $('.select2me-container').addClass('notranslate'); 
        $('.select2me').addClass('notranslate');
        $('.select2_rpt2-container').addClass('notranslate');
        $('.select2_rpt2').addClass('notranslate');
        $('.myselect2-container').addClass('notranslate');
        $('.myselect2').addClass('notranslate');
        $('.ValueUnq-container').addClass('notranslate');
        $('.ValueUnq').addClass('notranslate');
    });
    $('.select2, .select2-multiple, .select2me, .select2_rpt2, .select2_rpt,.myselect2,.ValueUnq').select2().on('select2', function(e) {
        $('.select2-container').addClass('notranslate');
        $('.select2').addClass('notranslate');
        $('.select2me-container').addClass('notranslate');
        $('.select2me').addClass('notranslate');
        $('.select2_rpt2-container').addClass('notranslate');
        $('.select2_rpt2').addClass('notranslate');
        $('.myselect2-container').addClass('notranslate');
        $('.myselect2').addClass('notranslate');
        $('.ValueUnq-container').addClass('notranslate');
        $('.ValueUnq').addClass('notranslate');
    });

    $('.select2, .select2-multiple, .select2me,.filter_list, .select2_rpt2, .select2_rpt').wrap('<span class="notranslate">');
    $('.form_datetime').wrap('<span class="notranslate">');

    jQuery(document).ready(function() {
        $('#question_type').wrap('<span class="notranslate">');
        $('.groupSelectClass').wrap('<span class="notranslate">');
        $('#question').wrap('<span class="notranslate">');
        $('#description').wrap('<span class="notranslate">');
        $('#script').wrap('<span class="notranslate">');
        $('#instruction').wrap('<span class="notranslate">');
        $('#situation').wrap('<span class="notranslate">');

        $('#reps_picker').wrap('<span class="notranslate">');
        $('#monthly_end_picker').wrap('<span class="notranslate">');
        $('.ranges').wrap('<span class="notranslate">');
        $('#monthly_picker').wrap('<span class="notranslate">');
        $('.daterangepicker').wrap('<span class="notranslate">');
    });
    //-- added by shital: 29:01:2024 ----


    //Ladda.bind( 'input[type=submit]' );
    //Ladda.bind('button[id=role-submit]');
    function ShowAlret(message, type) {
        if (type == 'success') {
            toastr.success(message);
        }
        if (type == 'info') {
            toastr.info(message);
        }
        if (type == 'warning') {
            toastr.warning(message);
        }
        if (type == 'error') {
            toastr.error(message);
        }
    }


var $zoho=$zoho || {};
$zoho.salesiq = $zoho.salesiq || {widgetcode: "siq3206dc3e52880f76998e25e1b724ddd31866e1e37d97ee8a6246b44924a7087f", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zohopublic.in/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);

$zoho.salesiq.ready=function()
{
   $zoho.salesiq.floatbutton.position("topright");
}
</script>