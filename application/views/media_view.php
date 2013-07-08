<div class="container-fluid">

	<div class="row-fluid">
		<div class="span7 offset1">
			<?php
				if($media->row()->mediaType == "audio")	$this->load->view('load_audio');
				else if($media->row()->mediaType == "video") $this->load->view('load_video');
				else{ ?>
					<div style="text-align:center;">
						<img  src="<?=base_url();?>application/uploads/image/<?=$media->row()->fileName;?>" 
								alt="Image Not Found">
					</div>
			<?php } ?>
		</div>

		<div class="span3 well">
			<div class="row-fluid">

				<div class="span12">
					<h4><?php echo $media->row()->title; ?></h4>
					<h5>
						<div class="span3">
							<a href="<?=site_url();?>/channel/user/<?=$media->row()->userName;?>"><?=$media->row()->userName;?></a>
						</div>
						<div class="span9">
							<?php if($this->session->userdata('logged_in')){ ?>
								<?php if(!$foe){ ?>
									<?php if($media->row()->userName == $this->session->userdata('userName')){ ?>
										<a href="<?=site_url();?>/media/delete_media_by_id/<?=$media->row()->ID;?>"><i class="icon-trash"></i></a>&nbsp;
										<a href="<?=site_url();?>/media/edit_media/<?=$media->row()->ID;?>"><i class="icon-edit"></i></a>&nbsp;
									<?php } else{ ?>
										<a id="favorite"><i id="icon-star" class="<?=($fav ? 'icon-star' : 'icon-star-empty')?>"></i></a>&nbsp;	
									<? } ?>
									<a id="playlist" href="#pModal" role="button" data-toggle="modal"><i id="icon-playlist" class="icon-th-list"></i></a>&nbsp;	
								<? } ?>
							<? } ?>
							<a href="<?=site_url();?>/media/download_media/<?=$media->row()->ID;?>"><i class="icon-download"></i></a>
						</div>
					</h5>
				</div>

				<div class="span12">
					<div id="progressBar">
					<h5>
						<i class="icon-eye-open"></i>&nbsp;<strong>&nbsp;<?php echo $media->row()->views; ?></strong>&nbsp;&nbsp;
						<i class="icon-thumbs-up"></i>&nbsp;<strong><?=$likes;?></strong>&nbsp;&nbsp;
						<i class="icon-thumbs-down"></i>&nbsp;<strong><?=$dislikes;?></strong>
					</h5>	
						<div class="progress">
							<div class="bar bar-primary" style="width:<?=($total == 0) ? 0 : ($likes / $total) * 100;?>%"></div>
							<div class="bar bar-danger" style="width:<?=($total == 0) ? 0 : ($dislikes / $total) * 100;?>%"></div>
						</div>
						</div>
				<?php if(!$media->row()->ratingVisibility){ ?>
					<p>User has Disabled Rating on this Media...</p>
				<?php } else if($this->session->userdata('logged_in') && !$foe){ ?>
					<p>
						<button id="like" class="btn btn-small btn-primary <?php if($liked == 1){?>disabled<?php } ?>">
							<i class="icon-thumbs-up"></i>&nbsp;Like&nbsp;&nbsp;
						</button>
						<button id="dislike" class="btn btn-small btn-danger <?php if($liked == 0){?>disabled<?php } ?>">
							<i class="icon-thumbs-down"></i>&nbsp;Dislike
						</button></p>
					</p>
				<?php } else if($foe){ ?>
				<?php } else{ ?>
					<p style="text-align:center">You Must Be Logged In To Rate Media...</p>
				<?php } ?>
				</div>
			</div>

			<div class="row-fluid">
				<a style="cursor:hand; cursor:pointer;"><h4 id="dHead">Description</h4></a>
				<div style="display:none;" id="dText">
					<?php echo $media->row()->description; ?>
				</div>
				<a style="cursor:hand; cursor:pointer;"><h4 id="kHead">Keywords</h4></a>
					<div style="display:none;" id="kText">
					<?php
						$string = "";
						foreach($keywords->result() as $keyword){
							$string.=$keyword->keyword.", ";
						}
						$string = rtrim($string, ", ");
						echo $string;
					?>
				</div>
			</div>



		</div>
	</div>

	<br/>
	<div class="span11"><hr/></div>

	<div class="row-fluid">
		<div class="span7 offset2">
			<div class="row-fluid">
				<h4>Comment</h4>
				<?php if($this->session->userdata('logged_in') && $media->row()->commentVisibility && !$foe){ ?>
					<form class="form-horizontal" id="commentForm" method="post" action='<?=site_url(); ?>/comment/create_comment'>
						<input type="hidden" id="table" name="table" value="MediaComment">
						<input type="hidden" id="url" name="url" value="<?php echo uri_string(); ?>">
						<input type="hidden" id="id" name="id" value="<?php echo $this->uri->segment(3); ?>">

						<div class="control-group">
							<textarea class="span12" style="resize:none" rows=6 name="body" id="body" placeholder="Tell it like it is"></textarea>
						</div>

						<div class="control-group pull-right">
							<a class="btn btn-primary" id="addComment">Post</a>
						</div>
					</form>
				<?php } else if(!$media->row()->commentVisibility){?>
					<p>User has disabled Comments on this Media...</p>
				<?php } else if($foe){ ?>
					<p>This User Has You On Their Block List...</p>
				<?php } else{ ?>
					<p>You Must Be Logged In To Post Comments...</p>
				<?php } ?>
			</div>

			<div class="row-fluid">
				<?php $this->load->view('comment_pagination'); ?>
			</div>
		</div>
	</div>


		</div>
	</div>

</div>

<?php $this->load->view('playlist_modal');?>



<script>
	$(document).on("click", "#addComment", function() {
		$.ajax({
			type: "POST",
			url: "<?=site_url()?>/comment/create_comment",
			data: { table : $('#table').attr('value'), url : $('#url').attr('value'), id: $('#id').attr('value'), body : $('#body').val() },
			dataType: "json",
			success: function(data) {}
		})

		$('#body').val('');
		$('#comments').load('<?=site_url()."/$url"?> #comments');	
	});

$('#dHead').click(function() {
	$('#dText').toggle('', function() { });
});

$('#kHead').click(function() {
	$('#kText').toggle('', function() { });
});

$('#favorite').click(function () {
	$.ajax({
		type: "POST",
		url: "<?=site_url()?>/playlist/toggle_favorite",
		data: "mediaID=<?=$media->row()->ID?>",
		dataType: 'json',
		success: function(data){
			if(data.added){
				$('#icon-star').removeClass();
				$('#icon-star').addClass('icon-star');
			}
			else{
				$('#icon-star').removeClass();
				$('#icon-star').addClass('icon-star-empty');
			}
		}
	});
});

$('#playlist').click(function () {
	get_playlists();
});

$('#create_playlist').click(function () {
	create_playlist();
});

$('#add').click(function () {
	add_media();
	$('#pModal').modal('hide'); 
});

function get_playlists(){
	$.ajax({
		url: "<?=site_url()?>/playlist/get_playlists",
		data: "",
		dataType: 'json',
		success: function(data){
			$('#pbody').empty();
			var multi = $('<select multiple="multiple" id="playlist_select"></select>');
			$('#pbody').append(multi);
			for(var i = 0; i < data.length; i++){
				var playlist = $('<option value="' + data[i].ID + '">' + data[i].name + '</option>');
				$('#playlist_select').append(playlist);
			}
		}
	});
}

function create_playlist(){
	$.ajax({
		type: "POST",
		url: "<?=site_url()?>/playlist/create_playlist",
		data: 'name=' + $('#new_playlist').val(),
		dataType: "json",
		success: function(data){
	get_playlists();
		}
	});
}

function add_media(){
	var post = {'mediaID': <?=$media->row()->ID;?>, 'playlists': $('#playlist_select').val()};
	$.ajax({
		type: "POST",
		url: "<?=site_url()?>/playlist/add_to_playlist",
		data: post,
 		dataType: "json",
		success: function(data){
		}
	});
}

$('#like').click(function () {
	if(!$('#like').hasClass('disabled')){
		$('#dislike').removeClass('disabled');
		$('#like').addClass('disabled');
		rate(1);
	}
	else{
		$('#like').removeClass('disabled');
		rate(-1);
	}

	$('#progressBar').load('<?=site_url()."/$url"?> #progressBar');
});

$('#dislike').click(function () {
	if(!$('#dislike').hasClass('disabled')){
		$('#like').removeClass('disabled');
		$('#dislike').addClass('disabled');
		rate(0);
	}
	else{
		$('#dislike').removeClass('disabled');
		rate(-1);
	}

	$('#progressBar').load('<?=site_url()."/$url"?> #progressBar');
});

function rate(rating){
	var post = {'mediaID': <?=$media->row()->ID;?>, 'rating': rating};
	$.ajax({
		type: "POST",
		url: "<?=site_url()?>/rating/rate_media",
		data: post,
 		dataType: "json",
		success: function(data){
		}
	});
}

</script>
