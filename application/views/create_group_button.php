
<a class="btn btn-primary" data-toggle="modal" href="#myModal" >Create a Group</a>

<div class="modal hide" id="myModal">


  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
    <h3>Create a Group</h3>
  </div>
  <div class="modal-body">
    <form class="form-inline well" method="post" action='<?php echo site_url();?>/group/create_group'>
      <input type="text" class="span3" name="name" id="name" placeholder="Group Name">
      <textarea class="span5" style="resize:none" rows=6 name="description" id="description" placeholder="What is your group about?"></textarea>
      <button type="submit" class="btn btn-primary">Create Group</button>
    </form>
  </div>


</div>

