<div class="row">
    <div class="col-sm-12">
    	<ul class="nav nav-pills">
            <li><?php echo $this->Html->link(__('Category List'),array('controller'=>'categories','action'=>'index', 'backoffice' => true),array("role"=>"button", "class"=>"btn btn-link"));?></li> 
            <li><?php echo $this->Html->link(__('New Category'),array('controller'=>'categories','action'=>'insert', 'backoffice' => true),array("role"=>"button", "class"=>"btn btn-link"));?></li> 
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
        <?php if (!empty($parents)) : ?>
            Parents: 
            <?php foreach ($parents as $key => $parent) : ?>
                <?php if ($key < (count($parents)-1)) : ?>
                    <?php echo $this->Html->link(h($parent['Category']['name']),array('controller'=>'categories','action' => 'index', md5($parent['Category']['id'])),array("role"=>"button", "class"=>"btn btn-link"));?><i class="fa fa-angle-double-right"></i> 
                <?php else : ?>
                    <?php echo h($parent['Category']['name']); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="table-responsive">
            <table cellpadding="0" cellspacing="0"  class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center col-md-1"><?php echo __('Category ID'); ?></th>
                        <th class="text-center"><?php echo __('NAME'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('SORT'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('STATUS'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
					<?php foreach ($categories as $category): ?>
                        <tr>
                            <td class="text-center"><?php echo h($category['Category']['id']); ?></td>
                           <td class="text-center"><?php echo $this->Html->link($category['Category']['name'],array('controller'=>'categories','action' => 'index', md5($category['Category']['id'])),array("role"=>"button", "class"=>"btn btn-link"));?></td>
                            <td class="text-center" nowrap="nowrap">
                                    <?php echo $this->Form->postLink('<span class="glyphicon glyphicon-arrow-up"></span>', array('action' => 'moveup', md5($category['Category']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))), array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
                                    <?php echo $this->Form->postLink('<span class="glyphicon glyphicon-arrow-down"></span>', array('action' => 'movedown', md5($category['Category']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))), array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
							</td>
                            <td class="text-center" nowrap="nowrap">
                                <?php if($category['Category']['isactive']):?>
                                    <?php echo $this->Form->postLink('<div class="btn-group"><button type="button" class="btn btn-default btn-xs active">'.__('ON').'</button><button type="button" class="btn btn-default btn-xs inactive">'.__('OFF').'</button></div>', array('action' => 'active', md5($category['Category']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('escape'=>false), __('CONFIRM_INACTIVE_Category_%s?', trim($category['Category']['id']))); ?>
                                <?php else :?>
                                    <?php echo $this->Form->postLink('<div class="btn-group"><button type="button" class="btn btn-default btn-xs inactive">'.__('ON').'</button><button type="button" class="btn btn-default btn-xs active">'.__('OFF').'</button></div>', array('action' => 'active', md5($category['Category']['id']),1,'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('escape'=>false), __('CONFIRM_ACTIVE_Category_%s?', trim($category['Category']['id']))); ?>
                                <?php endif;?>
                        	</td>
                            <td class="text-center" nowrap="nowrap">
                                <?php echo $this->Html->link(__('EDIT'), array('action' => 'insert', md5($category['Category']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-primary btn-xs','escape'=>false)); ?>
                                <?php if(!$category['Category']['isactive']):?>
									<?php echo $this->Form->postLink(__('DELETE'), array('action' => 'delete', md5($category['Category']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-danger btn-xs','escape'=>false), __('CONFIRM_DELETE_Category_%s?', trim($category['Category']['id']))); ?>
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


