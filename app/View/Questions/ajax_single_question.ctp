<li class="list-group-item" id="">
	<?php if (empty($question['Question']['type'])) : ?> <!-- Question section -->
		<div class="question_title">
			<?php if ($question['Question']['user_id'] == AuthComponent::user('id')) : ?>
				<span><button type="button" class="btn btn-danger btn-sm delete-question" ques-id="<?php echo $question['Question']['md5_id']; ?>" title="<?php echo __('Delete Question'); ?>">
                    <i class="fa fa-trash"></i>
                </button></span>
			<?php endif; ?>
			<?php echo '<i class="fa fa-star"></i> ' . $this->Html->link($this->Text->truncate(strip_tags(str_replace(array('<br>', '&nbsp;'), array(' ', ''), $this->Forum->cleanHtmlTopic($question['Question']['title']))), 95),array('controller'=>'questions','action' => 'view', md5($question['Question']['id'])),array("role"=>"button", "class"=>"btn btn-link"));?>
		</div>
		<p>
			<?php echo h($this->Text->truncate(strip_tags(str_replace(array('<br>', '&nbsp;'), array(' ', ''), $this->Forum->cleanHtmlTopic($question['Question']['body']))), 330)); ?>
		</p>
		<span class="question_category_title"><a href="javascript:void(0)" class="category" cat-id="<?php echo md5($question['Category']['id']); ?>"><?php echo $question['Category']['name']; ?></a></span>
	<?php else : ?> <!-- Poll section -->
		<div class="poll_title">
			<?php if ($question['Question']['user_id'] == AuthComponent::user('id')) : ?>
				<span><button type="button" class="btn btn-danger btn-sm delete-question" ques-id="<?php echo $question['Question']['md5_id']; ?>" title="<?php echo __('Delete Question'); ?>">
                    <i class="fa fa-trash"></i>
                </button></span>
			<?php endif; ?>
			<?php echo '<i class="fa fa-star"></i> ' . h($question['Question']['title']);?>
		</div>
		<div class="poll-section">
			<?php 
				if(AuthComponent::user('id')) {
					$voted = $this->Forum->userVoteExist(md5(AuthComponent::user('id')), md5($question['Question']['id']));
				} else {
					$voted = '';
				}  
			?>
			<?php if (!empty($voted)) : ?>
				<h3 class="vote_status_info text-success">You already voted this poll.</h3>
				<a href="javascript:void(0)" class="after_view_result" data-rel="<?php echo md5($question['Question']['id']); ?>"> View results </a>
			<?php else : ?>
				<form class="vote_form">
					<input type="hidden" name="data[Vote][question_id]" value="<?php echo md5($question['Question']['id']) ?>">
					<?php foreach ($question['QuestionOption'] as $key => $value) : ?>
					<p>
						<input type="radio" name="data[Vote][question_option_id]" value="<?php echo md5($value['id']) ?>" id="poll_<?php echo md5($value['id']) ?>">
						<label for="poll_<?php echo md5($value['id']) ?>"><?php echo $value['text']; ?></label>
					</p>
					<?php endforeach; ?>
					<p class="poll-form-bottom">
						<input type="submit" value="Vote" class="btn btn-default cast-vote">
						<a href="javascript:void(0)" class="view_result" data-rel="<?php echo md5($question['Question']['id']); ?>"> View results </a>
					</p>
				</form>
			<?php endif; ?>
			<div class="poll-result" style="display: none;">
				<?php $percentage = empty($question['Question']['vote_count']) ? 0 : 100/$question['Question']['vote_count']; ?>
				<?php foreach ($question['QuestionOption'] as $key => $value) : ?>
					<?php 
						if (!empty($value['vote_count'])) {
							$progress = $question['Question']['vote_count']/$value['vote_count']; 
						}
						$progress = $value['vote_count']*$percentage;
					?>
					<div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $progress; ?>%">
                        	<?php echo number_format((float)$progress, 2, '.', '') . '% ' . $value['text']; ?>
                        </div>
                    </div>
				<?php endforeach; ?>
			</div>
		</div>
		<span class="question_category_title"><a href="javascript:void(0)" class="category" cat-id="<?php echo md5($question['Category']['id']); ?>"><?php echo $question['Category']['name']; ?></a></span>
		<span class="pull-right"><button ques-id="<?php echo md5($question['Question']['id']); ?>" class="btn btn-xs wrong_category"><?php echo __('Report Abuse/Wrong Category'); ?></button></span>
	<?php endif; ?>
</li>