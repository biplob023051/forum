<div class="row">
    <div class="col-sm-12">
    	<ul class="nav nav-pills">
        	<li><?php echo $this->Html->link(__('ADMIN_LIST'),array('controller'=>'users','action'=>'index'),array("role"=>"button", "class"=>"btn btn-link"));?></li> 
            <li><?php echo $this->Html->link(__('NEW_ADMIN'),array('controller'=>'users','action'=>'insert'),array("role"=>"button", "class"=>"btn btn-link"));?></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body"> 
        <div class="row">
            <div class="col-md-12 text-right">                
				<?php	if($this->Paginator->counter('{:count}')) :?>
                    <p><?php echo $this->Paginator->counter(array('format' => __('SHOWING_{:start}_TO_{:end}_OUT_{:count}')));?></p>
                <?php endif;?>
            </div>
        </div>
        <div class="table-responsive">
            <table cellpadding="0" cellspacing="0"  class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center col-md-1"><?php echo __('ADMIN_ID'); ?></th>
                        <th class="text-center"><?php echo __('SHOP_NAME'); ?></th>
                        <th class="text-center"><?php echo __('ADMINNAME'); ?></th>
                        <th class="text-center"><?php echo __('NAME'); ?></th>
                        <th class="text-center"><?php echo __('EMAIL'); ?></th>
                        <th class="text-center"><?php echo __('PHONE'); ?></th>
                        <th class="text-center"><?php echo __('BIRTHDATE'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('STATUS'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
					<?php foreach ($users as $user): ?>
                        <tr>
                            <td class="text-center"><?php echo h($user['User']['id']); ?></td>
                            <td class="text-center"><?php echo h($user['Shop']['name']); ?></td>
                            <td class="text-center"><?php echo h($user['User']['username']); ?></td>
                            <td class="text-center">
                                <?php 
                                    echo h($user['User']['name']); 
                                    echo empty($user['User']['name_kana']) ? '' : '<br/>' . '(' . h($user['User']['name_kana']) . ')';
                                ?>
                            </td>
                            <td class="text-center"><?php echo h($user['User']['email']); ?></td>
                            <td class="text-center"><?php echo h($user['User']['phone']); ?></td>
                            <td class="text-center"><?php echo h($user['User']['birthdate']); ?></td>
                            <td class="text-center" nowrap="nowrap">
                                <?php if($user['User']['isactive']):?>
                                    <?php echo $this->Form->postLink('<div class="btn-group"><button type="button" class="btn btn-default btn-xs active">'.__('ON').'</button><button type="button" class="btn btn-default btn-xs inactive">'.__('OFF').'</button></div>', array('action' => 'active', md5($user['User']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('escape'=>false), __('CONFIRM_INACTIVE_ADMIN_%s?', trim($user['User']['id']))); ?>
                                <?php else :?>
                                    <?php echo $this->Form->postLink('<div class="btn-group"><button type="button" class="btn btn-default btn-xs inactive">'.__('ON').'</button><button type="button" class="btn btn-default btn-xs active">'.__('OFF').'</button></div>', array('action' => 'active', md5($user['User']['id']),1,'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('escape'=>false), __('CONFIRM_ACTIVE_ADMIN_%s?', trim($user['User']['id']))); ?>
                                <?php endif;?>
                        	</td>
                            <td class="text-center" nowrap="nowrap">
                                <?php echo $this->Html->link(__('EDIT'), array('action' => 'insert', md5($user['User']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
                                <?php if(!$user['User']['isactive']):?>
									<?php echo $this->Form->postLink(__('DELETE'), array('action' => 'delete', md5($user['User']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-danger btn-xs','escape'=>false), __('CONFIRM_DELETE_ADMIN_%s?', trim($user['User']['id']))); ?>
                                <?php endif;?>
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


