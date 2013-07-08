<table id="comments" class="table table-condensed table-hover table-boredered table-striped">
	<thead>
		<tr class="info">
			<?php foreach($pag['fields'] as $field_name => $field_display){?>
				<th>
				<?php echo anchor("$url/$field_name/" . 
													(($pag['sort_order'] == 'asc' && $pag['sort_by'] == $field_name) ? 'desc' : 'asc'), $field_display); ?> 
				<?php
					if($pag['sort_order'] == 'asc'){ echo "<i class=\"icon-chevron-up\"></i>"; }
					else{ echo "<i class=\"icon-chevron-down\"></i>"; }
				?>
				</th>
			<?php } ?>
		</tr>
	</thead>

	<tbody>
	<?php foreach($pag['comments']->result() as $comment){?>
	<tr>
			<?php foreach($pag['fields'] as $field_name => $field_display){?>
			<td>
				<h4>
					<a href="<?php echo site_url(); ?>/channel/user/<?php echo $comment->userName ?>">
						<?php echo $comment->userName; ?>
					</a> : 
					<?php echo $comment->dateCreated;
					if($comment->userName == $this->session->userdata('userName')) { ?>
						<a style="text-decoration:none;" id="delete_button" value="<?=$comment->ID?>">
							&nbsp;&nbsp;<i class="icon-remove"></i>
						</a>
					<?php } ?>
				</h4>
				<p><?php echo $comment->body; ?></p>
			</td>
			<?php } ?>
	</tr>
	<?php } ?>
	</tbody>

</table>

<?php echo $pag['pagination']; ?>	

<script>
	$('#comments').on("click", '#delete_button', function() {
		var listName = "<?=(($content == 'media_view') ? 'MediaComment' : 'DiscussionComment');?>";

		$.ajax({
  		type: "POST",
		  url: "<?=site_url()."/comment/delete_comment"?>",
  		data: { list : listName, commentID : $(this).attr('value') },
 			dataType: "json",
		  success: function(data) {}
		});

		$('#comments').load('<?=site_url()."/$url"?> #comments');
	});
</script>
