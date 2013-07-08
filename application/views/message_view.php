<?php
	$newest = $messages->last_row('array'); 
	$currentOffset = $this->uri->segment(4);
?>

<div class="container-fluid">
	<div class="row-fluid">

		<!-- conversations -->
		<div class="span10 offset1">
			<div class="span12">

				<div class="nav nav-list nav-pill pull-right">
					<a class="btn btn-success" data-toggle="modal" href="#myModal"><i class="icon-envelope"></i> Reply</a>
					<a class="btn btn-danger" href="<?=site_url()."/message/delete_message/".$oldest['ID'];?>"><i class="icon-trash"></i> Delete</a>
				</div>

				<?php
					$contact = 
						($newest['senderName'] == $this->session->userData('userName')) ? $newest['receiverName'] : $newest['senderName'];
				?>
				<h5>
					<a href="<?=site_url();?>/message/conversations"><i class="icon-backward"></i></a>
					&nbsp; &nbsp;
					<i class="icon-user"></i>&nbsp;<a href="<?=site_url();?>/channel/user/<?=$contact?>"><?=$contact?></a>
					&nbsp;&nbsp;- &nbsp;&nbsp;<?=$oldest['subject']?>
				</h5>				 
			</div>
	
			<table class="table">
				<thead>
					<tr>
						<th></th>
						<th></th>
					</tr>
				</thead>
	
				<tbody>
					<?php for($i = 0; $i < $messages->num_rows(); $i++){ ?> 
						<?php $x = $messages->row_array($i); ?>
						<tr <?php if($i == 0 && $currentOffset < 5){?>class="well"<?php } ?>>
							<td class="span2">
								<h5><i class="icon-user"></i>&nbsp;<a href="<?=site_url();?>/channel/user/<?=$x['senderName']?>"><?=$x['senderName']?></a></h5>
								<h5><i class="icon-calendar"></i>&nbsp;<?=$x['dateCreated']?></h5>
							</td>
	
							<td class="span8 offset2">
								<p><?=$x['body']?> </p>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php if($total > 5) { ?>
				<ul class="pager">
					<?php if($currentOffset + 5 < $total) { ?> 
						<li class="next">
							<a href="<?=site_url()?>/message/view_message/<?=$oldest['ID']?>/<?=$currentOffset+5?>">Next</a>
						</li>
					<?php } ?>
					<?php if($currentOffset >= 5) { ?>
						<li class="previous">
							<a href="<?=site_url()?>/message/view_message/<?=$oldest['ID']?>/<?=$currentOffset-5?>">Prev</a>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>

	</div>
</div>

<!-- modal for composing a message -->
<div class="modal hide" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Reply</h3>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
	    <form class="form-horizontal" accept-charset="UTF-8" action="<?=site_url()?>/message/create_message" method="POST">	
				<input type="hidden" id="receiverName" name="receiverName" value="<?=$contact;?>">
				<input type="hidden" id="parentID" name="parentID" value="<?=$oldest['ID'];?>">
				<input type="hidden" id="subject" name="subject" value="Re:<?=$oldest['ID']?>">
				<input type="hidden" id="reply" name="reply" value="1">

				<div class="control-group">	
      		<div class="span1"></div><textarea class="span11" style="resize:none" id="body" name="body" placeholder="Type message here" rows="5"></textarea>
				</div>

				<div class="row-fluid">	
					<button class="btn btn-info pull-right" type="submit">Reply</button>
				</div>
			</form>
		</div>

	</div>
</div>

