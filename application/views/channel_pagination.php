
	<div class="container-fluid">
		<table class="table table-condensed table-hover table-boredered table-striped">
			<thead>
				<tr class="info">
					<?php foreach($pag['fields'] as $field_name => $field_display) { ?>
						<th>
						<?php echo anchor("channel/browse/$field_name/" . 
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
			<?php foreach($pag['channels']->result() as $channel) {
				if(!$channel->views) $channel->views = 0;
				if(!$channel->downloads) $channel->downloads = 0;?>

				<tr>
						<?php foreach($pag['fields'] as $field_name => $field_display) { ?>
						<td class="span3">
							<?php
								if($field_name == 'userName') echo anchor("channel/user/".$channel->$field_name, $channel->$field_name);
								else echo $channel->$field_name;
							?>
						</td>
						<?php } ?>
				</tr>
			<?php } ?>
			</tbody>
	
		</table>

	<?php echo $pag['pagination']; ?>	
	</div>
