<div class="modal-header">
	<button type="button" id="CloseModalBtn" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">Participant Trainee List</h4>
	<div style="float:right;font-size:11px;font-weight:400;">
		<span style="height: 15px;width: 15px;background: #db1f48;padding: 9px;color: #ffffff;">SP</span> Schedule Pending
		<span style="height: 15px;width: 15px;background: #004369;padding: 9px;color: #ffffff;">PP</span> PDF Pending
		<span style="height: 15px;width: 15px;background: #36c6d3;padding: 9px;color: #ffffff;">RP</span> Rating Pending
	</div>
</div>
<div class="modal-body">
	<form name="CandidateForm" id="CandidateForm">
		<input type="hidden" name="assessment_id" id="assessment_id" value="<?= $assessment_id; ?>" />
		<label>Assessment: <b style="color: #004369;"><?= $result->assessment; ?></b></label>
		<div>
			<ol class="nav-item dropdown" style="float: right;padding-right: 21px;padding-left: 3px;">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Select Range
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown" style="width: 200px;">
					<form name="rangeform">
						<label style="font-weight: bold; margin-left:30px;margin-top:5px;">Score From Filter</label>
						<div class="form-group">
							<label for="exampleInputEmail1" style="margin-left: 15px;">From</label>
							<input type="range1" class="form-control" name="range1" id="range1" aria-describedby="range1" min='1' max='100' placeholder="From" style="width: 150px;margin-left: 15px;">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1" style="margin-left: 15px;">To</label>
							<input type="range2" class="form-control" name="range2" id="range2" aria-describedby="range2" min='1' max='100' placeholder="To" style="width: 150px;margin-left: 15px;">
						</div>
						<a href="#" class="btn btn-primary" id="range_submit" style="width: 150px;margin-left: 15px;margin-bottom: 10px;">Submit</a>
					</form>
				</div>
			</ol>
			<label style="float:right;">Filer By:</label>
			<a href="#" id="reset_for_all" onclick="confirm_reset_user($('#company_id').val(), $('#assessment_id').val())" style="float: right;padding-right: 21px;padding-left: 3px; font-weight:bold;">Reset Assessment for All</a>
		</div>

		<div class="portlet light">
			<div class="form-body">
				<table class="table table-striped table-bordered table-hover" id="CandidateFilterTable_restart" width="100%">
					<thead>
						<?php if ($is_send) { ?>
							<tr>
								<button type="button" id="candidate_email_send" name="candidate_email_send" data-loading-text="Please wait..." class="btn btn-orange btn-sm btn-outline margin-bottom-10" data-style="expand-right" style="float:right">
									<span class="ladda-label"><i class="fa fa-envelope"></i>&nbsp; Send</span>
								</button>
							</tr>
						<?php } ?>
						<tr>
							<th>
								<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
									<input type="checkbox" class="all group-checkable candidate_check" name="candidate_check" id="candidate_check" data-set="#CandidateFilterTable .checkboxes" />
									<span></span>
								</label>
							</th>
							<th>ID #</th>
							<th>Employee Code</th>
							<th>Candidate Name</th>
							<th>Email</th>
							<th>NO. OF ATTEMPTS</th>
							<th>Ai Score</th>
							<?php if ($report_type == 1 or $report_type == 3 or $report_type == 0) { ?>
								<th>AI</th>
							<?php } ?>
							<?php if ($report_type == 2 or $report_type == 3 or $report_type == 0) { ?>
								<th>Manual</th>
							<?php } ?>
							<?php if ($report_type == 3 or $report_type == 0) { ?>
								<th>AI + Manual</th>
							<?php } ?>
							<?php if ($is_send) { ?>
								<th>Status</th>
							<?php } ?>
							<th>Action</th>
							<th>Reset No.</th>
						</tr>
					</thead>
					<tbody class="notranslate"></tbody><!-- added by shital LM: 08:03:2024 -->
				</table>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		var assessment_id = $('#assessment_id').val();
		var report_type = "<?= $report_type ?>";
		var is_send_tab = "<?= $is_send ?>";
		CandidateDatatableRefresh_restart(assessment_id, report_type, is_send_tab);

		$('.candidate_check').click(function() {
			if ($(this).is(':checked')) {
				$("input[name='candidate_id[]']").prop('checked', true);
			} else {
				$("input[name='candidate_id[]']").prop('checked', false);
			}
		});

		$('#range_submit').click(function() {
			if (($('#range1').val() != '') && ($('#range2').val() != '')) {
				var range1 = $('#range1').val();
				var range2 = $('#range2').val();

				if ((range1 < 0 || range1 > 100) || (range2 < 0 || range2 > 100)) {
					ShowAlret('Please selects valid Range!', 'error');
					return false;
				} else if (range1 > range2) {
					ShowAlret('Range 2 to can not less than Range 1', 'error');
					return false;
				}
				CandidateDatatableRefresh_restart(assessment_id, report_type, is_send_tab, range1, range2);
			} else if (($('#range1').val() == '') && ($('#range2').val() == '')) {
				ShowAlret('Please select the Range!', 'error');
				return false;
			} else {
				if ($('#range1').val() == '') {
					ShowAlret('Please select the Range1', 'error');
					return false;
				} else {
					ShowAlret('Please select the Range2', 'error');
					return false;
				}
			}
		});
		select_candidates = [];
		$('#candidate_email_send').click(function() {
			var select_candidates = $.map($(':checkbox[name=candidate_id\\[\\]]:checked'), function(n, i) {
				return n.value;
			}).join(',');
			if (!select_candidates.trim()) {
				ShowAlret('Please select the candidates!', 'error');
			} else {
				// console.log(select_candidates);
				scheduleCandidateEmail($('#company_id').val(), assessment_id, select_candidates);
			}
		});

		// $('#reset_for_all').click(function() {
		// 	var company_id = $('#company_id').val();
		// 	var range1 = $('#range1').val();
		// 	var range2 = $('#range2').val();
		// 	$.ajax({
		// 		type: 'POST',
		// 		url: base_url + "/ai_process/reset_user_data/",
		// 		data: {
		// 			'company_id': company_id,
		// 			'assessment_id': assessment_id,
		// 			'range1': range1,
		// 			'range2': range2,
		// 		},
		// 		beforeSend: function() {
		// 			customBlockUI();
		// 		},
		// 		success: function(Odata) {
		// 			var json = $.parseJSON(Odata);
		// 			CandidateDatatableRefresh_restart(assessment_id, report_type, is_send_tab, range1, range2);
		// 			// if (json.success == "true") {
		// 			// $('#mdl_questions').html(json['html']);
		// 			// $('#responsive-question-modal').modal('show');
		// 			// }
		// 			customunBlockUI();
		// 		},
		// 		error: function(e) {
		// 			customunBlockUI();
		// 		}
		// 	});
		// });
	});
</script>