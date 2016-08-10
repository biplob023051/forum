<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body"> 
        <div class="row">
            <?php if (!empty($user)) : ?>
                <div class="col-md-12">
                    <h4>You are viewing questions of "<?php echo !empty($user['User']['name']) ? $user['User']['name'] . ' (' . $user['User']['email'] . ')' : $user['User']['email'] ; ?>"</h4>
                </div>
            <?php endif; ?>
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
                        <th class="text-center"><?php echo $this->Paginator->sort('User.email', 'User'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('Question.Category.name', 'Category'); ?></th>
                        <th class="text-center"><?php echo __('Description'); ?></th>
                        <th class="text-center col-md-1"><?php echo $this->Paginator->sort('created', 'Created'); ?></th>
                        <th class="text-center col-md-1"><?php echo $this->Paginator->sort('isacitve', 'Status'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
					<?php $i = 0; foreach ($questions as $question): $i++; ?>
                        <tr>
                            <td class="text-center"><?php echo $i; ?></td>
                            <td class="text-center"><?php echo $this->Html->link($question['Question']['title'],array('controller'=>'questions','action' => 'view', md5($question['Question']['id']), 'member' => false),array("role"=>"button", "class"=>"btn btn-link"));?></td>
                            <td class="text-center"><?php echo !empty($question['User']['name']) ? h($question['User']['name']) . ' (' . $question['User']['email'] . ')' : $question['User']['email'];?></td>
                            <td class="text-center"><?php echo h($question['Question']['Category']['name']);?></td>
                            <td class="text-center"><?php echo h($this->Text->truncate(strip_tags(str_replace(array('<br>', '&nbsp;'), array(' ', ''), $this->Forum->cleanHtmlTopic($question['Question']['body']))), 100)); ?></td>
                            <td class="text-center" nowrap="nowrap"><?php echo __('Posted') . ' ' . $this->Forum->getTime($question['Question']['created']); ?></td>
                            <td class="text-center" nowrap="nowrap">
                                <?php if(empty($poll['Question']['isactive'])):?>
                                    New
                                <?php elseif($poll['Question']['isactive'] == 1):?>
                                    Pending
                                <?php elseif($poll['Question']['isactive'] == 2):?>
                                    Completed
                                <?php else :?>
                                    Censored
                                <?php endif;?>
                            </td>
                            <td class="text-center" nowrap="nowrap">
                                <?php echo $this->Form->postLink(__('REMOVE'), array('action' => 'remove_bookmark', md5($question['Question']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-danger btn-xs','escape'=>false), __('Are you sure that you want to remove this bookmark?', trim($question['Question']['id']))); ?>
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


