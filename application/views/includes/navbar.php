<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<a class="brand" style="overflow: visible;padding-top: 5px;padding-bottom: 0;" href="<?=site_url().'/'.'media/feed/'?>" name="top"><img src="http://mmlab.cs.clemson.edu/spring13/u6/MeTube/application/views/includes/logo.png" width="80" height="80"></a>

			<div class="nav-collapse collapse">
				<ul class="nav">
					<li class="dropdown">
						<a href="#" data-toggle="dropdown"><i class="icon-th-list icon"></i> Browse <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="<?=site_url()?>/media/browse"><i class="icon-hdd icon"></i> Media</a></li>
              <li><a href="<?=site_url()?>/channel/browse"><i class="icon-play icon"></i> Channels</a></li>
              <li><a href="<?=site_url()?>/group/browse"><i class="icon-flag icon"></i> Groups</a></li>
            </ul>
					</li>
					<li class="divider-vertical"></li>
					
					<li><a href="<?=site_url()?>/dashboard"><i class="icon-globe icon"></i> Dashboard</a></li>
					<li class="divider-vertical"></li>

          <li><a href="<?=site_url()?>/media/upload_media"><i class="icon-upload icon"></i> Upload</a></li>

					<li>
						<form class="navbar-form" action="<?=site_url()?>/media/search" method="POST">
  						<input type="text" id="searchFields" name="searchFields" class="span4"></input>
  						<button type="submit" class="btn"><i class="icon-search"></i> Search</button>
						</form>
					</li>
				</ul>

				<div class="btn-group pull-right">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i>
							<?php 
								if($this->session->userdata('logged_in')) echo $this->session->userdata('userName');
							 	else echo 'Login';
							?>
						<span class="caret"></span>
					</a>

					<ul class="dropdown-menu"  <?php if(!$this->session->userdata('logged_in')){ ?>style="padding:10px;"<?php } ?> >
						<?php if(!$this->session->userdata('logged_in')){ ?>
							<li class="dropdown">
								<form class="form-inline" action='<?php echo site_url(); ?>/account/validate_credentials' method="POST">
									<fieldset>
										<div id="legend">
											<legend class="">Login</legend>
										</div>

										<div class="control-group">
											<!-- Username -->
											<label class="control-label" for="userName">Username</label>

											<div class="controls">
												<input type="text" id="userName" name="userName" placeholder="" class="input-xlarge">
											</div>
										</div>
				 
										<div class="control-group">
											<!-- Password-->
											<label class="control-label" for="password">Password</label>
											<div class="controls">
												<input type="password" id="password" name="password" placeholder="" class="input-xlarge">
											</div>
										</div>

										<input type="hidden" id="url" name="url" value="<?php echo uri_string(); ?>" class="input-xlarge">
					 
										<div class="control-group">
											<!-- Button -->
											<div class="controls">
												<button class="btn btn-success" type="submit">Login</button>
												<a href="<?php echo site_url();?>/account/sign_up" class="btn btn-primary">Sign Up</a> 
											</div>
										</div>
									</fieldset>
								</form>	
							</li>
						<?php } else{ ?> 
							<li><a href="<?php echo site_url();?>/message/conversations">
								<i class="icon-envelope"></i> Conversations</a></li>

							<li><a href="<?=site_url()."/channel/user/".$this->session->userdata('userName')?>"><i class="icon-home"></i> My Channel</a></li>
							<li><a href="<?=site_url();?>/account"><i class="icon-wrench"></i> Settings</a></li>

							<li><a href="<?php echo site_url();?>/account/logout">
								<i class="icon-share"></i> Logout</a></li>
						<?php } ?>
					</ul>

				</div>
			</div>
			<!--/.nav-collapse -->
		</div>
		<!--/.container-fluid -->
	</div>
	<!--/.navbar-inner -->
</div>
<!--/.navbar -->
