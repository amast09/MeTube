<?php if($content=='dashboard_view' || $isOwner) { ?>
<form class="form" action="<?=$isOwner ? site_url().'/playlist/delete_media/' : site_url().'/media/delete_media'?>" method="POST">
	<input type="hidden" name="playlistID" id="playlistID" value="<?=$this->uri->segment(3)?>">
	<input type="hidden" name="redirect" id="redirect" value="<?=uri_string()?>">
<? } ?>

	<div class="container-fluid">
		<table class="table table-hover table-striped">
			<thead>
				<tr class="info">
					<!-- if we're at the dashboard, there needs to be checkboxes so align the column titles. -->
					<?php if($url == 'dashboard/view' || $isOwner) echo '<th class="span1"></th>'; ?>

					<?php foreach($pag['fields'] as $field_name => $field_display) { ?>
						<th style="min-width: 100px"> 
							<?php echo anchor("$url/$mediaType/$category/$field_name/" . 
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
				<?php foreach($pag['media']->result() as $media) { ?>
					<tr>
						<!-- checkboxes for deleting media on dashboard -->
						<?php if($url == 'dashboard/view' || $isOwner) { ?>
							<td>
								<input type="checkbox" name="media[]" value="<?php echo $media->ID; ?>" />
							</td>	
						<?php } ?>

						<?php foreach($pag['fields'] as $field_name => $field_display) { ?>
							<td>
								<?php if($field_name == 'title'){?>
										<i class="<?php
												if($media->mediaType == 'audio') echo "icon-headphones";
												else if($media->mediaType == 'video') echo "icon-film";
												else echo "icon-picture";
											?>"></i>&nbsp;
										<a href="<?=site_url();?>/media/view/<?=$media->ID?>"><?=$media->$field_name?></a><?php
									} else if($field_name == 'userName'){?>
										<a href="<?=site_url();?>/channel/user/<?=$media->userName?>"><?=$media->$field_name?></a><?php
									}else echo $media->$field_name;
								?>
							</td>
						<?php } ?>
					</tr>
				<?php } ?>
			</tbody>

		</table>

		<?=$pag['pagination']?>	

		<?php if($url == 'dashboard/view' || $isOwner) { ?>
			<div class="control-group pull-left">
				<!-- Button -->
				<div class="controls">
					<button type="submit" class="btn btn-danger"><i class="icon-trash"></i> Delete Media</button>
				</div>
			</div>
		<?php } ?>
	</div>
</form>
