<div class="container-fluid">
	<div class='row-fluid'>

			<!-- mediaType selection view -->
			<div class="span2" style="text-align:center;">

				<ul class="nav nav-list nav-stacked" style="text-align:center">
					<li class="nav-header">Media Types</li>
					<?php foreach($mediaTypes as $m) { ?>
						<li class="<?php echo $m == $mediaType ? 'active' : ''; ?>">
							<a href='<?=site_url()."/$url/$m/$category/".$pag['sort_by'].'/'.$pag['sort_order']?>'>
								<strong><?=$m?></strong>
							</a>
						</li>
					<?php }?>
				</ul>

				<!-- put some extra space between the selection lists -->
				<hr />

				<!-- category selection list -->
				<ul class="nav nav-list nav-stacked" style="text-align:left;">
					<li class="nav-header">Categories</li>
					<?php foreach($categories as $c) { ?>
						<li class="<?php echo $c == $category ? 'active' : ''; ?>">
							<a href='<?=site_url()."/$url/$mediaType/$c/".$pag['sort_by'].'/'.$pag['sort_order']?>'>
								<strong><?=$c?></strong>
							</a>
						</li>
					<?php }?>
				</ul>

			</div>

			<!-- userName2's videos -->
			<div class="span8">
			<h1 style="text-align:center"><?php echo $userName2; ?> MeTube Channel</h1>
					<?php $this->load->view('media_pagination'); ?>
			</div>

			<!-- playlists -->
			<div class="span2" style="text-align:center;">
					<!-- add to lists buttons -->
					<?php if($userName2 != $userName && $this->session->userdata('logged_in')){ ?>

						<?php foreach($buttonData as $li){ ?>
							<div class="nav nav-pill nav-stack">
								<form id="<?=$li['text']?>" class="form-inline">
									<input type="hidden"; id="url" value="<?=$li['url']?>" />
									<input type="hidden" name="list" value="<?=$li['list']?>" />
									<input type="hidden" name="userName2" value="<?=$userName2?>" />
									<a id="button" class="btn <?=$li['button']?> btn-block">
									<i id="icon" class="<?=$li['icon']?>"></i> <strong> <?=$li['text']?></strong></a>
								</form>
							</div>
						<?php } ?>

					<?php } ?>

				<?php $this->load->view('list_scroll_view');?>
			</div>
	</div>
</div>


<script>
$('#Friend').click(function () {
	var str = $('#Friend').serialize();
	var url = ($('#Friend #url').val() == 1) ? "<?=site_url()?>/channel/delete_user" : "<?=site_url()?>/channel/add_user";
	toggle(str, url);
	if($('#Friend #button').hasClass('btn-danger') || $('#Friend #button').hasClass('btn-success')){
		$('#Friend #button').removeClass();
		$('#Friend #icon').removeClass();
		$('#Friend #icon').addClass('icon-ok');
		$('#Friend #button').addClass('btn btn-block btn-primary');
	}
	else{
		$('#Foe #icon').removeClass();
		$('#Foe #button').removeClass();
		$('#Foe #icon').addClass('icon-ok');
		$('#Foe #button').addClass('btn btn-block btn-primary');
		$('#Friend #icon').removeClass();
		$('#Friend #button').removeClass();
		if(<?php echo json_encode($pending); ?>){
			$('#Friend #icon').addClass('icon-remove');
			$('#Friend #button').addClass('btn btn-block btn-danger');
		}
		else{
			$('#Friend #icon').addClass('icon-refresh');
			$('#Friend #button').addClass('btn btn-block btn-success');
		}
	}
	var x = ($('#Friend #url').val() == 1) ? 0 : 1;
	$('#Friend #url').val(x);
});

$('#Contact').click(function () {
	var str = $('#Contact').serialize();
	var url = ($('#Contact #url').val() == 1) ? "<?=site_url()?>/channel/delete_user" : "<?=site_url()?>/channel/add_user";
	toggle(str, url);
	if($('#Contact #button').hasClass('btn-danger')){
		$('#Contact #icon').removeClass();
		$('#Contact #button').removeClass();
		$('#Contact #icon').addClass('icon-ok');
		$('#Contact #button').addClass('btn btn-block btn-primary');
	}
	else{
		$('#Foe #icon').removeClass();
		$('#Foe #button').removeClass();
		$('#Foe #icon').addClass('icon-ok');
		$('#Foe #button').addClass('btn btn-block btn-primary');
		$('#Contact #icon').removeClass();
		$('#Contact #button').removeClass();
		$('#Contact #icon').addClass('icon-remove');
		$('#Contact #button').addClass('btn btn-block btn-danger');
	}
	var x = ($('#Contact #url').val() == 1) ? 0 : 1;
	$('#Contact #url').val(x);
});

$('#Subscribe').click(function () {
	var str = $('#Subscribe').serialize();
	var url = ($('#Subscribe #url').val() == 1) ? "<?=site_url()?>/channel/delete_user" : "<?=site_url()?>/channel/add_user";
	toggle(str, url);
	if($('#Subscribe #button').hasClass('btn-danger')){
		$('#Subscribe #icon').removeClass();
		$('#Subscribe #button').removeClass();
		$('#Subscribe #icon').addClass('icon-ok');
		$('#Subscribe #button').addClass('btn btn-block btn-primary');
	}
	else{
		$('#Foe #icon').removeClass();
		$('#Foe #button').removeClass();
		$('#Foe #icon').addClass('icon-ok');
		$('#Foe #button').addClass('btn btn-block btn-primary');
		$('#Subscribe #icon').removeClass();
		$('#Subscribe #button').removeClass();
		$('#Subscribe #icon').addClass('icon-remove');
		$('#Subscribe #button').addClass('btn btn-block btn-danger');
	}
	var x = ($('#Subscribe #url').val() == 1) ? 0 : 1;
	$('#Subscribe #url').val(x);
});

$('#Foe').click(function () {
	var str = $('#Foe').serialize();
	var url = ($('#Foe #url').val() == 1) ? "<?=site_url()?>/channel/delete_user" : "<?=site_url()?>/channel/add_user";
	toggle(str, url);
	if($('#Foe #button').hasClass('btn-danger')){
		$('#Foe #icon').removeClass();
		$('#Foe #button').removeClass();
		$('#Foe #icon').addClass('icon-ok');
		$('#Foe #button').addClass('btn btn-block btn-primary');
	}
	else{
		$('#Contact #icon').removeClass();
		$('#Contact #button').removeClass();
		$('#Contact #icon').addClass('icon-ok');
		$('#Contact #button').addClass('btn btn-block btn-primary');
		$('#Subscribe #icon').removeClass();
		$('#Subscribe #button').removeClass();
		$('#Subscribe #icon').addClass('icon-ok');
		$('#Subscribe #button').addClass('btn btn-block btn-primary');
		$('#Friend #button').removeClass();
		$('#Friend #icon').removeClass();
		$('#Friend #icon').addClass('icon-ok');
		$('#Friend #button').addClass('btn btn-block btn-primary');
		$('#Foe #icon').removeClass();
		$('#Foe #button').removeClass();
		$('#Foe #icon').addClass('icon-remove');
		$('#Foe #button').addClass('btn btn-block btn-danger');
	}
	var x = ($('#Foe #url').val() == 1) ? 0 : 1;
	$('#Foe #url').val(x);
});

function toggle(str, url){
	$.ajax({
		type: "POST",
		url: url,
		data: str,
 		dataType: "json",
		success: function(data){
		}
	});
}
</script>
