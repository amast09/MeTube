<div class="container-fluid">

	<h2 style="text-align:center" id="title"><?=$header?></h2>

	<hr />

	<!-- media types and results -->
	<div class="row-fluid">

		<div class="span1">
		<!-- only allow deletion of the playlist by the owner -->
		<?php if($isOwner && $this->uri->segment(2) != 'favorite') { ?>
				<form class="form-inline" action="<?=site_url().'/playlist/delete_playlist'?>" method="POST">
					<!-- delete playlist button -->
					<input type="hidden" name="playlistID" id="playlistID" value="<?=$this->uri->segment(3)?>">
					<button type="submit" class="btn btn-small btn-danger"><i class="icon-trash"></i></button>
				</form>
				<button href="#pModal" role="button" data-toggle="modal" class="btn btn-primary"><i class="icon-edit"></i></button>
		<?php } ?>
		</div>

		<!-- media results -->
		<div class="span10">
			<?php $this->load->view('media_pagination'); ?>
		<!-- end videos section -->
		</div>

	</div>
</div>

<?php $this->load->view('edit_playlist_modal'); ?>

<script>
$('#change').click(function () {
	var post = {'playlistID': <?=$this->uri->segment(3)?>, 'name': $('#new_name').val()};
	$.ajax({
		type: "POST",
		url: "<?=site_url()?>/playlist/edit_playlist",
		data: post,
		dataType: "json",
		success: function(data){
			$('#pModal').modal('hide'); 
			$('#title').text($('#new_name').val());
		}
	});
});
</script>

