<div class="container-fluid">
	<div class="row-fluid">
		<div class="span4 offset4">
			<form class="form-horizontal" autocomplete="off" action='<?php echo site_url(); ?>/account/edit_account' method="POST">
				<fieldset>
					<div id="legend">
						<legend class="">Edit Account Information</legend>
					</div>

					<div class="control-group">
						<!-- First Name -->
						<label class="control-label"  for="firstName">First Name</label>
						<div class="controls">
						 <input type="text" id="firstName" value="<?=$fname?>" name="firstName" placeholder="" class="input-xlarge">
						</div>
					</div>
			 
					<div class="control-group">
						<!-- Last Name -->
						<label class="control-label" for="lastName">Last Name</label>
						<div class="controls">
							<input type="text" id="lastName" value="<?=$lname?>" name="lastName" placeholder="" class="input-xlarge">
						</div>
					</div>

					<div class="control-group">
						<!-- New Password -->
						<label class="control-label" for="newPassword">New Password</label>
						<div class="controls">
							<input type="password" id="newPassword" name="newPassword" placeholder="" class="input-xlarge">
						</div>
					</div>
	 
					<div class="control-group">
						<!-- New Password 2 -->
						<label class="control-label" for="newPassword2">Re-Type Password</label>
						<div class="controls">
							<input type="password" id="newPassword2" name="newPassword2" placeholder="" class="input-xlarge">
						</div>
					</div>

					<div class="control-group">
						<!-- Old Password -->
						<label class="control-label" for="password2">Current Password</label>
						<div class="controls">
							<input type="password" id="password" name="password" placeholder="" class="input-xlarge">
						</div>
					</div>

					<div class="control-group">
						<div class="controls">
							<button class="btn btn-success" type="submit">Update</button>
						</div>
					</div>

				</fieldset>
			</form>
		</div>
	</div>
</div>

