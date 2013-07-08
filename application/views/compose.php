<div class="container-fluid">
<div class="span8 well">
    <form accept-charset="UTF-8" action="<?php echo site_url();?>/message/create_message" method="POST">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-user"></i></span><input class="span4" type="text" id="receiverName" name="receiverName" placeholder="User Name">
				</div>
				<div class="input-prepend">
					<span class="add-on"><i class="icon-comment"></i></span><input class="span4" type="text" id="subject" name="subject" placeholder="Subject">
				</div>
				<div class="input-prepend">
					<span class="add-on"><i class="icon-pencil"></i></span>
        	<textarea class="span7" id="body" name="body" placeholder="Type message here" rows="5"></textarea>
				</div>
        <button class="btn btn-info pull-right" type="submit">Send Message</button>
    </form>
</div>
</div>


