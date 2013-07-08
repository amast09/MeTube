<a class="btn btn-primary" data-toggle="modal" href="#myModal" >Comment</a>

<div class="modal hide" id="myModal">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Post a Comment</h3>
  </div>
  <div class="modal-body">
    <form class="form-inline well" method="post" action='<?php echo site_url(); ?>/comment/create_comment'>
			<?php if($this->uri->segment(1) == 'discussion'){ ?>
				<input type="hidden" id="table" name="table" value="DiscussionComment">
				<input type="hidden" id="url" name="url" value="<?php echo uri_string(); ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $this->uri->segment(4); ?>">
			<?php }else{ ?>
				<input type="hidden" id="table" name="table" value="MediaComment">
				<input type="hidden" id="url" name="url" value="<?php echo uri_string(); ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $this->uri->segment(3); ?>">
			<?php } ?>

			<div class="control-group">
      	<textarea class="span5" style="resize:none" rows=6 name="body" id="body" placeholder="Tell it like it is"></textarea>
			</div>

			<div class="control-group">
      	<button type="submit" class="btn btn-primary">Post</button>
			</div>

    </form>
  </div>


</div>
