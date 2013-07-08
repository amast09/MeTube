<div class="container-fluid">
	<div class="row-fluid span12" style="text-align:center;">
		<h1> Browse Groups </h1>
	</div>

	<div class="row-fluid">
		<?php $this->load->view('group_pagination'); ?>
	</div>

	<?php if($logged_in)
		$this->load->view('create_group_button'); 
	?>

</div>
