<form class="form-inline"  method="POST" action="<?=site_url()?>/group/<?=$function?>">
	<input type="hidden" name="groupName" value="<?=$groupName?>" />
	<button type="submit" name="submit" class="btn <?=$button?> btn-block">
	<i class="<?=$icon?>"></i> <strong> <?=$text?></strong></button>
</form>

