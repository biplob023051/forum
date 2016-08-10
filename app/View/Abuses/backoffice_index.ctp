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
                        <th class="text-center col-md-1"><?php echo __('Abus ID'); ?></th>
                        <th class="text-center"><?php echo __('Reported By'); ?></th>
                        <th class="text-center"><?php echo __('Report for'); ?></th>
                        <th class="text-center"><?php echo __('Comments'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
					<?php foreach ($abuses as $abuse): ?>
                        <tr>
                            <td class="text-center"><?php echo h($abuse['Abus']['id']); ?></td>
                            <td class="text-center"><?php echo h($abuse['User']['name']) . ' (' . $abuse['User']['email'] . ')'; ?></td>
                            <td class="text-center">
                                <?php 
                                    $target = empty($abuse['Question']['type']) ? 'view' : 'poll_view';
                                    echo $this->Html->link($abuse['Question']['title'],array('controller'=>'questions','action' => $target, md5($abuse['Question']['id']), 'member' => false),array("role"=>"button", "class"=>"btn btn-link"));
                                ?>
                                    
                            </td>
                            <td class="text-center"><?php echo h($abuse['Abus']['comment']); ?></td>
                            <td class="text-center" nowrap="nowrap">
                                <?php echo $this->Form->postLink(__('DELETE REPORT'), array('action' => 'delete', md5($abuse['Abus']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-danger btn-xs','escape'=>false), __('Are you sure to delete %s?', trim($abuse['Abus']['id']))); ?>
                                <?php //echo $this->Form->postLink(__('DELETE QUESTION'), array('controller' => 'questions', 'action' => 'delete_question', md5($abuse['Question']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-danger btn-xs','escape'=>false), __('Are you sure that you want to delete this question?', trim($abuse['Question']['title']))); ?>
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


