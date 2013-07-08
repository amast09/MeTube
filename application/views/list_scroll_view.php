
<div id="list-accordion-resizer" style="height:10cm;" class="ui-widget-content">
	<div id="list-accordion">

		<?php foreach($lists as $list) { ?>
			<h4><?=$list['name']?></h4>
			<div>
				<ul style="list-style-type:none;">
					<?php foreach($list['query']->result() as $item) { ?>
						<li>
							<a href="<?=site_url().$list['link'].$item->$list['field']?>">
								<strong><?=$item->$list['field']?></strong>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>

			<h4>Playlists</h4>
			<div>
				<ul style="list-style-type:none;">
						<li>
							<a href="<?=site_url();?>/playlist/favorite/<?=$userName2;?>">Favorites</a>
						</li>
					<?php foreach($playlists->result() as $playlist){ ?>
						<li>
							<a href="<?=site_url()?>/playlist/view/<?=$playlist->ID;?>"><?=$playlist->name;?></a>
						</li>
					<?php } ?>
				</ul>
			</div>


	</div>
</div>


<script>
$(function(){
	$('#list-accordion').accordion({
			active: false,
			collapsible: true,
			heightStyle: "content",
			speed: "fast"
	});
	//$(function(){
	//	$('#list-accordion-resize').accordion("refresh");
	//});	
});
</script>
