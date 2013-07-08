<div class="container-fluid">
	<div class="row-fluid">
		<!-- media type selection list -->
		<div class="row-fluid">
			<div class="span2">
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
				<ul class="nav nav-list nav-stacked" style="text-align:left">
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

		<!-- media results -->
		<div class="span10" style="text-align:center">
			<h1><?=$header?></h1>
			<?php $this->load->view('media_pagination'); ?>
		</div>

			</div>
		</div>
		<!-- end videos section -->
	</div>
</div>
