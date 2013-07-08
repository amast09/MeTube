<div class="container-fluid">
	<div class="row-fluid">

		<!-- Group name and Discussion list -->
		<div class="span10">
			<div class="row-fluid">
				<h1>Welcome to the <?=$groupName?> Group!</h1>
			</div>

			<div class="row-fluid well">
				<h5><?=$description?></h5>
			</div>

			<div class="row-fluid">
				<h3>Discussions</h3>
			</div>

			<?php $this->load->view('discussion_pagination'); ?>
		</div>

		<div class="span2">
			<div class="row-fluid pull-right">
				<?php if($logged_in)
					$this->load->view('join_group_button'); 
				?>
			</div>

			<div class="row-fluid pull-right">
				<?php if($isMember)
					$this->load->view('create_discussion_button'); 
				?>
			</div>

			<div class="row-fluid">&nbsp; </div>

			<div class="row-fluid pull-right" style="overflow-y:scroll">
				<ul class="nav nav-list nav-stacked">
					<li class="nav-header">Members</li>

					<?php foreach($members->result() as $m) { ?>
						<li>
							<a href="<?=site_url()?>/channel/user/<?=$m->userName?>">
								<strong><?=$m->userName?></strong>
							</a>
						</li>
					<?php } ?>
				</li>	
			</div>

		</div>

	</div>
</div>
