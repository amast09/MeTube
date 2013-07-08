<div class="container-fluid">
	<div class="row-fluid">
		<div class="span6 offset3">
			<h2>Edit Media</h2>

			<form class="form-horizontal" enctype="multipart/form-data" method="post" action='<?php echo site_url();?>/media/edit'>
				<input type="hidden" name="mediaID" id="mediaID" value="<?=$mediaID?>">

	
				<div class="control-group">
					<input type="text" class="span12" name="title" value="<?=$initialTitle?>" id="title" placeholder="Media Title">
				</div>

				<div class="control-group">
					<textarea  class="span12" style="resize:none" rows=12 name="description" id="description" placeholder="Describe your media..."><?=$initialDescription?></textarea>
				</div>

				<div class="control-group">
					<i class="icon-comment"></i>
					<strong>Allow Comments:&nbsp;</strong>
					<input type="radio" name="cv" <?=$initialCV==1 ? 'checked="checked"' : ''?>
							value="1">Yes</input>
					<input type="radio" name="cv" <?=$initialCV==0 ? 'checked="checked"' : ''?>
							value="0">No</input>
				</div>

				<div class="control-group">
					<i class="icon-star"></i>
					<strong>Allow Ratings:&nbsp;</strong>
					<input type="radio" name="rv" <?=$initialRV==1 ? 'checked="checked"' : ''?>
						value="1">Yes</input>
					<input type="radio" name="rv" <?=$initialRV==0 ? 'checked="checked"' : ''?>
						value="0">No</input>
				</div>

				<div class="control-group">
					<i class="icon-eye-open"></i>
					<strong>Visibility:&nbsp;</strong>
					<select name="mv">
						<option value="0" <?=$initialMV==0 ? 'selected="selected"' : ''?> >Public</option>
						<option value="1" <?=$initialMV==1 ? 'selected="selected"' : ''?> >Friends</option>
						<option value="2" <?=$initialMV==2 ? 'selected="selected"' : ''?> >Only Me</option>
					</select>
				</div>

				<div class="control-group">
					<i class="icon-th-large"></i>
					<strong>Category:&nbsp;</strong>
					<select name="category">
						<?php foreach($categories->result() as $cat){?>
							<option value="<?=$cat->ID?>"<?=$initialCat==$cat->ID ? 'selected="selected"' : ''?>>
								<?=$cat->name?> </option>
						<?php } ?>
					</select>
				</div>

				<div class="control-group">
					<i class="icon-tags"></i>
					<strong>Tags:&nbsp;</strong>
					<input type="text" class="span11" value="<?=$initialKW?>" name="tags" id="tags" placeholder="Seperate tags with dashes...">      
				</div>

				<div class="control-group">
					<button type="submit" value="update" class="btn btn-primary">Update</button>
				</div>

			</form>
		</div>
	</div>
</div>

