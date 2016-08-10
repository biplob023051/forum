<?php $country_list = $this->Forum->country_list(); ?>
<?php  if($this->params['prefix']=='backoffice' && in_array(AuthComponent::user('role_id'), Configure::read('Role.Backoffice')) && (AuthComponent::user('id') == 1)):?>
<div class="row">
    <div class="col-sm-12">
        <ul class="nav nav-pills">
            <li class="active"><?php echo $this->Html->link(__('Site Users'),array('controller'=>'users','action'=>'index', 'backoffice' => true),array("role"=>"button", "class"=>"btn btn-link"));?></li> 
            <li><?php echo $this->Html->link(__('Admins'),array('controller'=>'users','action'=>'admins', 'backoffice' => true),array("role"=>"button", "class"=>"btn btn-link"));?></li> 
        </ul>
    </div>
</div>
<?php endif; ?>
<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body"> 
        <div class="table-responsive">
            <table cellpadding="0" cellspacing="0"  class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center col-md-1"><?php echo __('Member Id'); ?></th>
                        <th class="text-center"><?php echo __('Name'); ?></th>
                        <th class="text-center"><?php echo __('Email'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('Place'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('Work Place'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('Study Place'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('Country'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
					<?php foreach ($admins as $admin): ?>
                        <tr>
                            <td class="text-center"><?php echo h($admin['User']['id']); ?></td>
                            <td class="text-center"><?php echo h($admin['User']['name']); ?></td>
                            <td class="text-center"><?php echo h($admin['User']['email']);?></td>
                            <td class="text-center"><?php echo h($admin['User']['city']); ?></td>
                            <td class="text-center"><?php echo h($admin['User']['work_at']); ?></td>
                            <td class="text-center"><?php echo h($admin['User']['study_at']); ?></td>
                            <td class="text-center"><?php echo !empty($admin['User']['country']) ? $country_list[$admin['User']['country']] : ''; ?></td>
                            <td class="text-center" nowrap="nowrap">
                                <?php echo $this->Html->link(__('Questions'), array('controller' => 'questions', 'action' => 'index', md5($admin['User']['id'])),array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
                                <?php echo $this->Html->link(__('Answers'), array('controller' => 'questions', 'action' => 'answers', md5($admin['User']['id'])),array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
                                <?php echo $this->Html->link(__('Polls'), array('controller' => 'questions', 'action' => 'polls', md5($admin['User']['id'])),array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
                                <?php echo $this->Html->link(__('Bookmarks'), array('controller' => 'questions', 'action' => 'bookmarks', md5($admin['User']['id'])),array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
                                <?php echo $this->Html->link(__('EDIT'), array('action' => 'insert', $admin['User']['id']),array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
                                <?php echo $this->Form->postLink(__('DELETE'), array('action' => 'delete', $admin['User']['id']),array('class'=>'btn btn-danger btn-xs','escape'=>false), __('Confirm Delete User %s?', trim($admin['User']['id']))); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php 
			echo $this->Paginator->pagination( array(
				'ul' => 'pagination fg-pagination-margin pull-right'
			)); 
		?>
        
    </div>
</div>


