<div class="container-fluid">
<div class="row-fluid">
<div class="span6 offset3">
<h2>Upload Media</h2>
<form class="form-horizontal" enctype="multipart/form-data" method="post" action='<?php echo site_url();?>/media/upload'>
		
	<div class="control-group">
		<input type="text" class="span12" name="title" id="title" placeholder="Media Title">      
	</div>

	<div class="control-group">
		<textarea  class="span12" style="resize:none" rows=12 name="description" id="description" placeholder="Describe your media..."></textarea>
	</div>

	<div class="control-group">
		<i class="icon-comment"></i>
		<strong>Allow Comments:&nbsp;</strong>
		<input type="radio" name="cv" value="1">Yes</input>
		<input type="radio" name="cv" value="0">No</input>
	</div>

	<div class="control-group">
		<i class="icon-star"></i>
		<strong>Allow Ratings:&nbsp;</strong>
		<input type="radio" name="rv" value="1">Yes</input>
		<input type="radio" name="rv" value="0">No</input>
	</div>

	<div class="control-group">
		<i class="icon-eye-open"></i>
		<strong>Visibility:&nbsp;</strong>
		<select name="mv">
			<option value="0">Public</option>
			<option value="1">Friends</option>
			<option value="2">Only Me</option>
		</select>
	</div>

	<div class="control-group">
		<i class="icon-th-large"></i>
		<strong>Category:&nbsp;</strong>
		<select name="category">
			<?php foreach($categories->result() as $cat){?>
				<option value="<?=$cat->ID?>"><?=$cat->name?></option>
			<?php } ?>
		</select>
	</div>


	<div class="control-group">
		<i class="icon-tags"></i>
		<strong>Tags:&nbsp;</strong>
		<input type="text" class="span11" name="tags" id="tags" placeholder="Seperate tags with dashes...">      
	</div>
<!-- icon-tags -->

	<div class="control-group">
		<i class="icon-file"></i>
		<strong>Select a File:&nbsp;</strong>
		<input name="userfile" type="file" label="Browse"></input>
	</div>

	<div class="control-group">
		<button type="submit" value="upload" class="btn btn-primary">Upload</button>
	</div>

</form>

<?php echo validation_errors(); ?>
</div>
</div>
</div>

