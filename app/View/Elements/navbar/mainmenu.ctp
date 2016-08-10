<?php  if(AuthComponent::user('id')):?>
<?php 
	$action = $this->request->action;
	$controller = $this->request->controller;
?>
	<div class="" id="mainmenu">
	    <ul class="nav nav-pills nav-justified">
	    	<?php  if($this->params['prefix']=='backoffice' && in_array(AuthComponent::user('role_id'), Configure::read('Role.Backoffice'))):?>
	    		<li role="presentation" class="<?php if (($action == 'backoffice_home') && ($controller == 'admins')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-cogs"></i><p>'.__('Site Settings').'</p>',array('controller'=>'admins','action'=>'home','backoffice'=>true),array('escape'=>false));?>
				</li>
	    		<li role="presentation" class="<?php if (($action == 'backoffice_index') && ($controller == 'categories')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-sitemap"></i><p>'.__('Categories').'</p>',array('controller'=>'categories','action'=>'index','backoffice'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'backoffice_index') && ($controller == 'users')) echo 'active'; ?>">
						<?php echo $this->html->link('<i class="fa fa-user"></i><p>'.__('Manage Users').'</p>',array('controller'=>'users','action'=>'index','backoffice'=>true),array('escape'=>false));?>
					</li>
				<li role="presentation" class="<?php if (($action == 'backoffice_index') && ($controller == 'questions')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-question-circle"></i><p>'.__('All Questions').'</p>',array('controller'=>'questions','action'=>'index','backoffice'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'backoffice_answers') && ($controller == 'questions')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-check-circle"></i><p>'.__('All Answers').'</p>',array('controller'=>'questions','action'=>'answers','backoffice'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'backoffice_polls') && ($controller == 'questions')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-bar-chart"></i><p>'.__('All Polls').'</p>',array('controller'=>'questions','action'=>'polls','backoffice'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'backoffice_bookmarks') && ($controller == 'questions')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-bookmark"></i><p>'.__('All Bookmarks').'</p>',array('controller'=>'questions','action'=>'bookmarks','backoffice'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'backoffice_index') && ($controller == 'abuses')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-bookmark"></i><p>'.__('Abuse Reports').'</p>',array('controller'=>'abuses','action'=>'index','backoffice'=>true),array('escape'=>false));?>
				</li>
			<?php  elseif($this->params['prefix']=='member' && in_array(AuthComponent::user('role_id'), Configure::read('Role.Member'))):?>
				<li role="presentation" class="<?php if ($action == 'member_home') echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-home"></i><p>'.__('Profile').'</p>',array('controller'=>'users','action'=>'home','member'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'member_index') && ($controller == 'questions')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-question-circle"></i><p>'.__('My Questions').'</p>',array('controller'=>'questions','action'=>'index','member'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'member_answers') && ($controller == 'questions')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-check-circle"></i><p>'.__('My Answers').'</p>',array('controller'=>'questions','action'=>'answers','member'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'member_polls') && ($controller == 'questions')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-bar-chart"></i><p>'.__('My Polls').'</p>',array('controller'=>'questions','action'=>'polls','member'=>true),array('escape'=>false));?>
				</li>
				<li role="presentation" class="<?php if (($action == 'member_bookmarks') && ($controller == 'questions')) echo 'active'; ?>">
					<?php echo $this->html->link('<i class="fa fa-bookmark"></i><p>'.__('My Bookmarks').'</p>',array('controller'=>'questions','action'=>'bookmarks','member'=>true),array('escape'=>false));?>
				</li>
			<?php endif;?>
		</ul>
	</div>
<?php  endif;?>