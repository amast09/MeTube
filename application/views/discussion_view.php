<div class="container-fluid">
	<div class="row-fluid">
		<a href="<?=site_url()."/group/display/$groupName"?>"><i class="icon-backward"></i></a>
	</div>
	<br />
	<div class="well" id="discussion">
		<h1><?php echo $subject; ?> </h1>
		<p><?php echo $body; ?> </p>
	</div>

	<div class="pull-right">
		<?php $this->load->view('create_comment_button'); ?>
	</div>

	<?php $this->load->view('comment_pagination'); ?>

</div>
