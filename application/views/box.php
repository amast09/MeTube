<div class="container-fluid">
	<div class="row-fluid">

		<!-- contact List -->
		<div class="span2" style="text-align:center;">
			<table id="contactList" class="table table-condensed table-striped" style="overflow-y:scroll;">
				<thead> <strong> Contacts </strong> </thead>

				<tbody>
					<?php foreach($contacts->result() as $contact) { ?>
						<tr>
							<td style="text-align:center;"> 
								<a id="contact" data-receiver="<?=$contact->userName2;?>" data-toggle="modal" href="#myModal"> <strong> <?=$contact->userName2?> </strong></a>
							</td>
						</tr> 
					<?php } ?>
				</tbody>
			</table>
		</div>

			<div class="span9">
				<h5>Conversations <i class="icon-comment"></i></h5>
				<form class="form" action="<?=site_url()?>/message/delete_messages" method="POST">
					<input type="hidden" name="seg" value="<?=uri_string()?>" />
					<div class="container-fluid">
						<table class="table table-striped table-hover">
							<thead>
								<tr class="info">
									<th class="span1"></th>
									<?php foreach($fields as $field_name => $field_display) { ?>
										<th <?php if($sort_by == $field_name) echo "class=\"sort_$sort_order\""; ?>>
											<?=anchor("message/conversations/$field_name/" . 
																				(($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'),
																				$field_display)?> 
											<?php if($sort_by == $field_name) {
												echo "<i class=\"icon-chevron-";
													if($sort_order == 'asc')
														echo 'up';
													else
														echo 'down';
													echo "\"></i>"; 
												}
											?>
										</th>
									<?php } ?>
								</tr>
							</thead>

							<tbody>
								<?php foreach($messages->result() as $message) { ?>

									<tr <?php if(($message->senderName==$userName && $message->messageState==1)||($message->receiverName==$userName && $message->messageState==2)){ ?>class="info"<?php } ?> >
										<td class="span1"><input type="checkbox" name="msg[]" value="<?=$message->ID?>" /></td>	
										<input type="hidden" name="seg" value"<?=$this->uri->uri_string()?>"/>
										<?php foreach($fields as $field_name => $field_display) { ?>
										<td class="span3">
											<?php
												if($field_name == 'subject') 
													echo anchor("message/view_message/".$message->parentID, $message->$field_name);
												else if($field_name == 'senderName' || $field_name == 'receiverName')
													echo anchor("channel/user/".$message->$field_name, $message->$field_name);
												else 
													echo $message->$field_name;
											?>
										</td>
									<?php } ?>
								</tr>

								<?php } ?>

							</tbody>
						</table>

						<?=$pagination?>	

						<div class="control-group">
							<!-- Compose Button -->
							<a id="composeButton" class="btn btn-success pull-left" data-toggle="modal" href="#myModal"><i class="icon-envelope"></i> Compose</a>

							<!-- Delete Button -->
							<div class="controls">
								<button type="submit" class="btn btn-danger pull-right"><i class="icon-trash"></i> Delete</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>

<!-- modal for composing a message -->
<div class="modal hide" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Compose A Message</h3>
	</div>

	<div class="modal-body">
		<div class="row-fluid">
	    <form class="form-horizontal" accept-charset="UTF-8" action="<?=site_url()?>/message/create_message" method="POST">	

				<div class="control-group">	
					&nbsp;<i class="icon-user"></i> &nbsp;&nbsp; <input class="span11" type="text" id="receiverName" name="receiverName" placeholder='User Name'>
				</div>

				<div class="control-group">	
					&nbsp;<i class="icon-comment"></i> &nbsp;&nbsp; <input class="span11" type="text" id="subject" name="subject" placeholder="Subject">
				</div>

				<div class="control-group">	
      		<div class="span1"></div><textarea class="span11" style="resize:none" id="body" name="body" placeholder="Type message here" rows="5"></textarea>
				</div>

				<div class="row-fluid">	
					<button class="btn btn-info pull-right" type="submit">Send Message</button>
				</div>
			</form>
		</div>

	</div>
</div>



<script>

$('#contactList').on("click", "#contact", function () {
	$('#receiverName').val($(this).data('receiver'));
	$('#receiverName').text($(this).data('receiver'));
});

$('#composeButton').click(function () {
	$('#receiverName').val('');
	$('#receiverName').text('');
});

</script>
