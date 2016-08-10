<?php 
    echo $this->Html->script(array('vendors/jquery/1.11.2/jquery.min'), array('inline' => true));
?>
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
            <div class="col-md-6">
                <div class="form-group">
                    <label for="QuestionIsactive" class="col col-sm-3 control-label">Choose Status</label>
                    <div class="col col-sm-7">
                        <select name="data[Question][isactive]" class="form-control" id="QuestionIsactive">
                            <option<?php if (!isset($selected)) echo ' selected'; ?> value="all">All</option>
                            <option<?php if (isset($selected) && ($selected == 0)) echo ' selected'; ?> value="0">New</option>
                            <option<?php if (isset($selected) && ($selected == 1)) echo ' selected'; ?> value="1">Pending</option>
                            <option<?php if (isset($selected) && ($selected == 2)) echo ' selected'; ?> value="2">Completed</option>
                            <option<?php if (isset($selected) && ($selected == 3)) echo ' selected'; ?> value="3">Censored</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">                
				<?php	if($this->Paginator->counter('{:count}')) :?>
                    <p><?php echo $this->Paginator->counter(array('format' => __('SHOWING {:start} TO {:end} OUT {:count}')));?></p>
                <?php endif;?>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table cellpadding="0" cellspacing="0"  class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center col-md-1"><?php echo __('Sl No'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('title', 'Title'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('Category.name', 'Category'); ?></th>
                        <th class="text-center"><?php echo __('Description'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('answer_count', 'No of Answers'); ?></th>
                        <th class="text-center col-md-1"><?php echo $this->Paginator->sort('created', 'Created'); ?></th>
                        <th class="text-center col-md-1"><?php echo $this->Paginator->sort('isacitve', 'Status'); ?></th>
                        <th class="text-center col-md-1"><?php echo __('ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
					<?php $i = $this->Paginator->counter('{:start}'); foreach ($questions as $question): ?>
                        <tr>
                            <td class="text-center"><?php echo $i; ?></td>
                            <td class="text-center"><?php echo $this->Html->link($question['Question']['title'],array('controller'=>'questions','action' => 'view', md5($question['Question']['id']), 'backoffice' => false),array("role"=>"button", "class"=>"btn btn-link"));; ?></td>
                            <td class="text-center"><?php echo h($question['Category']['name']);?></td>
                            <td class="text-center"><?php echo h($this->Text->truncate(strip_tags(str_replace(array('<br>', '&nbsp;'), array(' ', ''), $this->Forum->cleanHtmlTopic($question['Question']['body']))), 100)); ?></td>
                            <td class="text-center"><?php  echo $question['Question']['answer_count']; ?></td>
                            <td class="text-center" nowrap="nowrap"><?php echo __('Posted') . ' ' . $this->Forum->getTime($question['Question']['created']); ?></td>
                            <td class="text-center" nowrap="nowrap">
                                <?php if(empty($question['Question']['isactive'])):?>
                                    New
                                <?php elseif($question['Question']['isactive'] == 1):?>
                                    Pending
                                <?php elseif($question['Question']['isactive'] == 2):?>
                                    Completed
                                <?php else :?>
                                    Censored
                                <?php endif;?>
                        	</td>
                            <td class="text-center" nowrap="nowrap">
                                 <?php echo $this->Form->postLink(__('Bring to Top'), array('action' => 'question_home', md5($question['Question']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-success btn-xs','escape'=>false), __('Are you sure that you want to display this question on home?', trim($question['Question']['title']))); ?>
                                <?php echo $this->Html->link(__('Edit'), array('controller' => 'questions', 'action' => 'edit_question', md5($question['Question']['id'])), array('class'=>'btn btn-danger btn-xs')); ?>
                                <?php echo $this->Form->postLink(__('DELETE'), array('action' => 'delete_question', md5($question['Question']['id']),'?'=>array('redirect_url'=>urlencode(Router::reverse($this->request, true)))),array('class'=>'btn btn-danger btn-xs','escape'=>false), __('Are you sure that you want to delete this question?', trim($question['Question']['title']))); ?>
                            </td>
                        </tr>
                    <?php $i++; endforeach; ?>
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#QuestionIsactive').change(function() {
            var sel = $(this).val();
            var url = '<?php echo $this->request->base; ?>/backoffice/questions/index';
            if (sel == 'all') {
                url = url;
            } else if (sel) {
                url = url + '/status:' + sel;
            }
            window.location = url;
        });
    });

</script>