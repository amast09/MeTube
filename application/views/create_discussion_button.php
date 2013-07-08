<a class="span12 btn btn-primary" data-toggle="modal" href="#myModal" >Create a Discussion</a>

<div class="modal hide" id="myModal">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Create a Discussion</h3>
  </div>
  <div class="modal-body">
    <form class="form-horizontal well" method="post" action='<?=site_url()?>/discussion/create_discussion'>
      <input type="hidden" name="group" id="group" value="<?=$groupName?>">
			<div class="control-group">
	      <input type="text" class="span12" name="subject" id="subject" placeholder="Discussion Topic">
			</div>
			<div class="control-group">
      	<textarea style="resize:none" class="span12" rows=6 name="body" id="body" placeholder="Tell it like it is"></textarea>
			</div>
			<div class="control-group">
      	<button type="submit" class="btn btn-primary">Create Discussion</button>
			</div>
    </form>
  </div>


</div>
