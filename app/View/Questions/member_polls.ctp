<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body"> 
        <div class="row">
            <div class="col-md-12 text-right">                
				<?php	if($this->Paginator->counter('{:count}')) :?>
                    <p><?php echo $this->Paginator->counter(array('format' => __('SHOWING {:start} TO {:end} OUT {:count}')));?></p>
                <?php endif;?>
            </div>
        </div>
        <div class="table-responsive">
            <table cellpadding="0" cellspacing="0"  class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center col-md-1"><?php echo __('Sl No'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('title', 'Title'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('Category.name', 'Category'); ?></th>
                        <th class="text-center col-md-1"><?php echo $this->Paginator->sort('created', 'Created'); ?></th>
                        <th class="text-center col-md-1"><?php echo $this->Paginator->sort('isacitve', 'Status'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
					<?php $i = 0; foreach ($polls as $poll): $i++; ?>
                        <tr>
                            <td class="text-center"><?php echo $i; ?></td>
                            <td class="text-center"><?php echo $this->Html->link($poll['Question']['title'],array('controller'=>'questions','action' => 'poll_view', md5($poll['Question']['id']), 'backoffice' => false),array("role"=>"button", "class"=>"btn btn-link")); ?></td>
                            <td class="text-center"><?php echo h($poll['Category']['name']);?></td>
                            <td class="text-center" nowrap="nowrap"><?php echo __('Posted') . ' ' . $this->Forum->getTime($poll['Question']['created']); ?></td>
                            <td class="text-center" nowrap="nowrap">
                                <?php if($poll['Question']['isactive'] == 3):?>
                                    <i class="fa fa-exclamation-triangle"></i>
                                <?php else :?>
                                    <i class="fa fa-check"></i>
                                <?php endif;?>
                        	</td>
                            <td class="text-center" nowrap="nowrap">
                                <?php echo $this->Form->postLink(__('DELETE'), array('action' => 'delete_question', md5($poll['Question']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-danger btn-xs','escape'=>false), __('Are you sure that you want to delete this poll?', trim($poll['Question']['id']))); ?>
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


