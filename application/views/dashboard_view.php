<div class="container-fluid">
	<div class="row-fluid">
				<!-- media type selection list and category selection list-->
				<div class="span2" style="text-align:center">

					<!-- media type selection list -->
					<div class="row-fluid">
						<ul class="nav nav-list nav-stacked" style="text-align:center">
							<li class="nav-header">Media Types</li>
							<?php foreach($mediaTypes as $m) { ?>
								<li class="<?php echo $m == $mediaType ? 'active' : ''; ?>">
									<a href='<?=site_url()?>/dashboard/view/<?=$m.'/'.$category.'/'.$pag['sort_by'].'/'.$pag['sort_order']?>'>
										<strong><?=$m?></strong>
									</a>
								</li>
							<?php }?>
						</ul>
					</div>

					<!-- put some extra space between the selection lists -->
					<hr />

					<!-- category selection list -->
					<div class="row-fluid">
						<ul class="nav nav-list nav-stacked" style="text-align:left">
							<li class="nav-header">Categories</li>
							<?php foreach($categories as $c) { ?>
								<li class="<?php echo $c == $category ? 'active' : ''; ?>">
									<a href='<?=site_url()?>/dashboard/view/<?=$mediaType.'/'.$c.'/'.$pag['sort_by'].'/'.$pag['sort_order']?>'>
										<strong><?=$c?></strong>
									</a>
								</li>
							<?php }?>
						</ul>
					</div>

				</div>

				<!-- header and media list -->
				<div class="span8" style="text-align:center">
					<h1>Welcome to MeTube, <?php echo $this->session->userdata('firstName'); ?>!</h1>
					<?php $this->load->view('media_pagination'); ?>
				<!-- end videos section -->
				</div>

				<!-- all lists -->
				<div class="span2 pull-right">
					<?php $this->load->view('list_scroll_view'); ?>
				</div>
				<!-- end lists sections -->

			</div>
		</div>


	</div>
</div>
