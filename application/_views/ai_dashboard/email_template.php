<form id="addform" name="addform" method="post" action="<?php echo base_url();?>emailtemplate/update" enctype="multipart/form-data" >
		<?php if (!empty($emailbodys) ){ ?>
	<table class="table table-bordered" width="100%" style="background-color: #F7F7F7;">
			<tr>
				<td colspan="3"><div class="col-md-12">
					<?php echo $emailbodys[0]->description; ?></div>
				</td>
			</tr>
		<tr>
			<td width="21%" ><label for="Subject" class="b-10">Email Label :<span class="required">*</span></label></td>
			<td width="79%" colspan="3"><div class="col-md-5">
			<input name="label" type="text" id="label" value="<?php echo $emailbodys[0]->alert_title; ?>" size="50" maxlength="200" class="form-control input-sm" readonly="true" /></div>
			</td>
		</tr>
		<tr>
			<td width="21%" ><label for="Subject" class="b-10">Subject :<span class="required">*</span></label></td>
			<td width="79%" colspan="3"><div class="col-md-5">
				<input name="subject" type="text" id="subject" value="<?php echo $emailbodys[0]->subject; ?>" size="50" maxlength="200" class="form-control input-sm"/></div>
			</td>
		</tr>
		<tr>
			<td ><label for="ingredient" class="b-10">Message:<span class="required">*</span></label></td>
			<td colspan="3">
				<textarea cols="80" id="editor1" name="message" rows="10" class="cke-editor"><?php echo $emailbodys[0]->message; ?>
				</textarea>
			</td>
		</tr>
		<tr>
			<td><label for="fromname" class="b-10">From/Sender name:</label></td>
			<td colspan="3"><div class="col-md-5">
			<input name="fromname" type="text" id="fromname" value="<?php echo $emailbodys[0]->fromname; ?>" size="50" maxlength="200" class="form-control input-sm" readonly="true"/></div>
			</td>
		</tr>
		<tr>
			<td ><label for="fromemail" class="b-10">From/Sender Email:</label></td>
			<td colspan="3"><div class="col-md-5">
				<input name="fromemail" type="text" id="fromemail" value="<?php echo $emailbodys[0]->fromemail; ?>" size="50" maxlength="200" class="form-control input-sm" readonly="true"/></div>
			</td>
		</tr>
		<tr class="trclass">
			<td colspan="4">
				<div class="col-sm-9 col-sm-offset-3">
					<div align="left">
						<input type="hidden" name="alert_name" id="alert_name" value="<?php echo $emailbodys[0]->alert_name; ?>" />
						<input type="button" name="submit" value="Update" class="btn btn-sm btn-primary btn-orange" onclick="update()">                                                 
					</div>
				</div>
			</td>
		</tr>						
	</table> 
	<?php } ?>										 
</form>
<script src="<?php echo base_url();?>assets/global/plugins/cke-editor/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/global/plugins/cke-editor/adapters/jquery.js"></script>
<!-- <script src="<?php echo base_url();?>assets/global/plugins/editor.js"></script>-->
<script type="text/javascript">
	//KRISHNA --- VAPT - ENABLED CSRF TOKEN ON PROFILE PAGE
	var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
	csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

	CKEDITOR.replace( 'editor1',
            {
                toolbar :
		[
			{ name: 'styles', items : [ 'Styles','Format' ] },
                        { name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
                        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
                        { name: 'links', items : [ 'Link','Unlink','Anchor' ] }
		],
            });			
	CKEDITOR.config.autoParagraph = false;
	function update() { 
		for (instance in CKEDITOR.instances) {
			CKEDITOR.instances[instance].updateElement();
		}
        var Base_Url = '<?php echo base_url(); ?>';

		var htmlContent = CKEDITOR.instances['editor1'].getData();
		// console.log(htmlContent);
		if(validHTML(htmlContent)) {
			//valid HTML email template
			$.ajax({
				type: "POST",
				url: '<?= base_url() ?>ai_dashboard/update_template/',
				data: $('#addform').serialize()+'&'+[csrfName]+'='+csrfHash,
				beforeSend: function () {
					customBlockUI();
				},
				success: function (Odata) {
					var Data = $.parseJSON(Odata);
					if (Data['success']) {
						ShowAlret(Data['Msg'], 'success');                    
					} else {
						ShowAlret(Data['Msg'], 'error');
					}
					csrfName = Data.csrfName;
					csrfHash = Data.csrfHash;
					customunBlockUI();
				}, error: function (XMLHttpRequest, textStatus, errorThrown) {
					ShowAlret("Status: " + textStatus + " ,Contact Atomapp for technical support!");
				}
			});
		} else {
			ShowAlret('Invalid HTML added as Message', 'error');
		}
    }
	function validHTML(html) {
		var openingTags, closingTags;

		html        = html.replace(/<[^>]*\/\s?>/g, '');      // Remove all self closing tags
		html        = html.replace(/<(br|hr|img).*?>/g, '');  // Remove all <br>, <hr>, and <img> tags
		openingTags = html.match(/<[^\/].*?>/g) || [];        // Get remaining opening tags
		closingTags = html.match(/<\/.+?>/g) || [];           // Get remaining closing tags

		return openingTags.length === closingTags.length ? true : false;
	}
</script> 