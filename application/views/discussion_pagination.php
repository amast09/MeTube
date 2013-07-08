
<div class="container-fluid">
	<table id="discussions" class="table table-condensed table-hover table-boredered table-striped">
		<thead>
			<tr class="info">
				<?php foreach($pag['fields'] as $field_name => $field_display):?>
					<th>
					<?php if($field_name == 'userName') echo "&nbsp;&nbsp;&nbsp;&nbsp;";?>
					<?php echo anchor("group/display/$groupName/$field_name/" . 
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
				<?php endforeach;?>
			</tr>
		</thead>

		<tbody>
		<?php foreach($pag['discussions']->result() as $discussion):?>
		<tr>
			<?php foreach($pag['fields'] as $field_name => $field_display):?>
				<td>
					<?php
						if($field_name == 'subject') echo anchor("discussion/display/$groupName/$discussion->ID", $discussion->$field_name);
						else if($field_name == 'userName') { 
							if($discussion->userName == $this->session->userdata('userName')) { ?>
								<a style="text-decoration:none;" id="delete_button" value="<?=$discussion->ID?>">
									<i class="icon-remove"></i>
								</a>
							<?php } else echo "&nbsp;&nbsp;&nbsp;&nbsp;";  
							echo anchor("channel/user/$discussion->userName", $discussion->$field_name);
						} else echo $discussion->$field_name;
					?>
				</td>
			<?php endforeach;?>
		</tr>
		<?php endforeach;?>
		</tbody>

	</table>

<?php echo $pag['pagination']; ?>	
</div>

<script>
	$('#discussions').on("click", '#delete_button', function() {
		$.ajax({
  		type: "POST",
		  url: "<?=site_url()."/discussion/delete_discussion"?>",
  		data: { discussionID : $(this).attr('value') },
 			dataType: "json",
		  success: function(data) {}
		});

		$('#discussions').load('<?=site_url()."/group/display/$groupName"?> #discussions');
	});
</script>
