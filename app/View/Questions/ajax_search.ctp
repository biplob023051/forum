<div id="search-section" class="col-md-12">
	<form role="form" id="search_form">
		<div class="row">
			<div class="col-md-10">
				<input type="text" name="data[keyword]" class="form-control" placeholder="Enter question/poll title, tags" id="search_text">
			</div>
			<div class="col-md-2">
				<button type="submit" class="btn btn-default">Search</button>
			</div>
		</div>
	</form>
	<br>
	<h2>Search result for "<?php echo $keyword; ?>"</h2>
</div>
<div id="question_section" class="col-md-12">
	<div class="row">
        <div class="col-md-12 text-right">                
			<?php	if($this->Paginator->counter('{:count}')) :?>
                <p><?php echo $this->Paginator->counter(array('format' => __('SHOWING {:start} TO {:end} OUT {:count}')));?></p>
            <?php endif;?>
        </div>
    </div>
    <ul class="list-group" id="middle_content_list">
		<?php if (!empty($questions)) : ?>
			<?php foreach ($questions as $key => $question) : ?>
				<?php echo $this->element('ajax_single_question', array('question' => $question, 'search' => true)); ?>
			<?php endforeach; ?>
		<?php else : ?>
			<li class="list-group-item"><?php echo __('Questions/polls has not found yet!'); ?></li>
		<?php endif; ?>
	</ul>
	<ul class="list-group" id="middle_content_list">
		<?php if (!empty($questions)) : ?>
			<?php foreach ($questions as $key => $question) : ?>
				<li class="list-group-item" id="">
					<?php if (empty($question['Question']['type'])) : ?> <!-- Question section -->
						<div class="question_title"><?php echo '<i class="fa fa-star"></i> ' . $this->Html->link(h($this->Text->truncate(strip_tags(str_replace(array('<br>', '&nbsp;'), array(' ', ''), $this->Forum->cleanHtmlTopic($question['Question']['title']))), 95)),array('controller'=>'questions','action' => 'view', md5($question['Question']['id'])),array("role"=>"button", "class"=>"btn btn-link"));?>
						</div>
						<p>
							<?php echo h($this->Text->truncate(strip_tags(str_replace(array('<br>', '&nbsp;'), array(' ', ''), $this->Forum->cleanHtmlTopic($question['Question']['body']))), 330)); ?>
						</p>
						<span class="question_category_title"><a href="javascript:void(0)" class="category" cat-id="<?php echo md5($question['Category']['id']); ?>"><?php echo $question['Category']['name']; ?></a></span>&nbsp;<span class="question_posted_time"><?php echo __('Posted') . ' ' . $this->Forum->getTime($question['Question']['created']); ?></span>
						<span class="pull-right"><button ques-id="<?php echo md5($question['Question']['id']); ?>" class="btn btn-xs wrong_category"><?php echo __('Report Abuse/Wrong Category'); ?></button></span>
					<?php else : ?> <!-- Poll section -->
						<div class="poll_title"><?php echo '<i class="fa fa-star"></i> ' . h($question['Question']['title']);?>
						</div>
						<div class="poll-section">
							<?php 
								if(AuthComponent::user('id')) {
									$voted = $this->Forum->userVoteExist(md5(AuthComponent::user('id')), md5($question['Question']['id']));
								} else {
									$voted = '';
								}  
							?>
							<div class="poll-result" style="display: none;">
								<?php $percentage = empty($question['Question']['vote_count']) ? 0 : 100/$question['Question']['vote_count']; ?>
								<?php foreach ($question['QuestionOption'] as $key => $value) : ?>
									<?php 
										if (!empty($value['vote_count'])) {
											$progress = $question['Question']['vote_count']/$value['vote_count']; 
										}
										$progress = $value['vote_count']*$percentage;
									?>
									<h5><?php echo $progress; ?>% <?php echo $value['text'];?></h5>
									<div class="progress">
										<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $progress; ?>"
										aria-valuemin="0" aria-valuemax="<?php echo $question['Question']['vote_count']; ?>" style="width:<?php echo $progress; ?>%">
											<span class="sr-only"><?php echo $progress; ?>% <?php $value['text'];?></span>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<?php if (!empty($voted)) : ?>
								<h3 class="vote_status_info text-success">You already voted this poll.</h3>
								<a href="javascript:void(0)" class="view_result" data-rel="<?php echo md5($question['Question']['id']); ?>"> View results </a>
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
						</div>
						<span class="question_category_title"><a href="javascript:void(0)" class="category" cat-id="<?php echo md5($question['Category']['id']); ?>"><?php echo $question['Category']['name']; ?></a></span>&nbsp;<span class="question_posted_time"><?php echo __('Posted') . ' ' . $this->Forum->getTime($question['Question']['created']); ?></span>
						<span class="pull-right"><button ques-id="<?php echo md5($question['Question']['id']); ?>" class="btn btn-xs wrong_category"><?php echo __('Report Abuse/Wrong Category'); ?></button></span>
					<?php endif; ?>
					<?php if (!empty($question['Question']['tags'])) : ?>
						<div class="row"><div class="col-md-12"><b>Related Tags</b> <?php echo $question['Question']['tags']; ?></div></div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		<?php else : ?>
			<li class="list-group-item"><?php echo __('Questions/Polls has not found!'); ?></li>
		<?php endif; ?>
	</ul>
	<?php 
		echo $this->Paginator->pagination( array(
			'ul' => 'pagination search_pagination fg-pagination-margin pull-right'
		)); 
	?>
</div>