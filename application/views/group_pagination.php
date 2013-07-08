
	<div class="container-fluid">
		<table class="table table-condensed table-hover table-boredered table-striped">
			<thead>
				<tr class="info">
					<?php foreach($pag['fields'] as $field_name => $field_display) { ?>
						<th>
						<?php echo anchor("group/browse/$field_name/" . 
															(($pag['sort_order'] == 'asc' && $pag['sort_by'] == $field_name) ? 'desc' : 'asc'), $field_display); ?> 
						<?php	
							if($pag['sort_by'] == $field_name) {
								echo "<i class=\"icon-chevron-";
								if($pag['sort_order'] == 'asc')
									echo 'up';
								else
									echo 'down';
								echo "\"></i>"; 
							}
						?>
						</th>
					<?php } ?>
				</tr>
			</thead>

			<tbody>
			<?php foreach($pag['groups']->result() as $group) { ?>
			<tr>
					<?php foreach($pag['fields'] as $field_name => $field_display) { ?>
					<td>
						<?php
							if($field_name == 'name') echo anchor("group/display/".$group->$field_name, $group->$field_name);
							else echo $group->$field_name;
						?>
					</td>
					<?php } ?>
			</tr>
			<?php } ?>
			</tbody>
	
		</table>

	<?php echo $pag['pagination']; ?>	
	</div>
