<div class="container-fluid">
<div class="row-fluid">
<div class="span4 offset4">
<form class="form-horizontal" action='<?php echo site_url(); ?>/account/create_account' method="POST">
	<fieldset>
 		<div id="legend">
			<legend class="">Create an Account</legend>
		</div>

        <div class="control-group">
          <!-- First Name -->
          <label class="control-label"  for="firstName">First Name</label>
          <div class="controls">
           <input type="text" id="firstName" name="firstName" placeholder="" class="input-xlarge">
          </div>
        </div>
 
        <div class="control-group">
          <!-- Last Name -->
          <label class="control-label" for="lastName">Last Name</label>
          <div class="controls">
            <input type="text" id="lastName" name="lastName" placeholder="" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <!-- Email -->
          <label class="control-label"  for="email">Email</label>
          <div class="controls">
            <input type="text" id="email" name="email" placeholder="" class="input-xlarge">
          </div>
        </div>
 
        <div class="control-group">
          <!-- Username-->
          <label class="control-label" for="userName">Username</label>
          <div class="controls">
            <input type="text" id="userName" name="userName" placeholder="" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <!-- Password -->
          <label class="control-label"  for="password">Password</label>
          <div class="controls">
            <input type="password" id="password" name="password" placeholder="" class="input-xlarge">
          </div>
        </div>
 
        <div class="control-group">
          <!-- Password 2 -->
          <label class="control-label" for="password2">Re-Type Password</label>
          <div class="controls">
            <input type="password" id="password2" name="password2" placeholder="" class="input-xlarge">
          </div>
        </div>

				<div class="control-group">
		 			<div class="controls">
						<button class="btn btn-success" type="submit">Create</button>
					</div>
				</div>

		</fieldset>
    </form>
</div>
</div>
</div>

