<table class="table  table-bordered table-hover table-checkable order-column" id="index_table_assessment">
	<thead>
		<tr>
			<th>E code</th>
			<th>Employee Name</th>
			<!-- <th>DoJ</th> -->
			<th>Division</th>
			<!-- <th>PC HQ</th> -->
			<!-- <th>State</th> -->
			<!-- <th>Zone</th> -->
			<th>Region</th>
			<th>L+1 EC</th>
			<th>L+1 Name</th>
			<th>Email ID</th>
			<th>Assessment Name</th>
			<th>Employee Status</th>
			<th>AI Score %</th>
			<?php
			if (!empty($parameter_score_result)) {
				foreach ($parameter_score_result as $param) { ?>
					<th><?= $param . ' %'  ?></th>
			<?php }
			} ?>
			<?php
			if ($assessment_type != 3) {
				if (!empty($parameter_score_manaul_result)) {
					foreach ($parameter_score_manaul_result as $params_manaul) { ?>
						<th><?= $params_manaul . ' Manual %'  ?></th>
			<?php  }
				}
			} ?>
			<?php if ($assessment_type != 3) { ?>
				<th>Assessor Rating %</th>
			<?php } ?>
			<th>Overall Avg (AI and Assessor) %</th>
			<!-- <th>Diff (AI -Assesor)</th> -->
			<th>Number of Attempts</th>
			<th>Range AI</th>
			<?php if ($assessment_type != 3) { ?>
				<th>Range Asssesor</th>
			<?php } ?>
			<!-- <th>Joining Range</th>  -->
		</tr>
	</thead>
	<tbody class="notranslate"></tbody><!-- added by shital LM: 08:03:2024 -->
</table>