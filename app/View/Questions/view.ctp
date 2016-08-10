<section id="content_wrapper" class="col-md-9">
	<div class="row">
		<div class="col-md-12">
			<?php if (!empty($parent_cats)) : ?>
	            <?php foreach ($parent_cats as $key => $parent) : ?>
				<?php if ($key != 0) : ?>>><?php endif; ?> <a href="javascript:void(0)" id="category_<?php echo $key; ?>" class="category" cat-id="<?php echo md5($parent['Category']['id']); ?>"><?php echo $parent['Category']['name']; ?></a>
			<?php endforeach; ?>
	        <?php endif; ?>
		</div>
		<div class="col-md-12">
			<div class="col-md-1 user-avatar">
				<img class="img-circle img-responsive" src="<?php echo $this->Forum->getUserAvatar($question['User']['avatar']); ?>">
			</div>
			<div class="col-md-11 user-avatar">
				<h2><?php echo h($question['Question']['title']); ?></h2>
			</div>
			
			<p><?php echo $this->Forum->cleanHtmlTopic($question['Question']['body']); ?></p>
			<?php
				if (!empty($question['QuestionPhoto'])) :
					$total_photos = count($question['QuestionPhoto']);
					switch ($total_photos) {
						case '1':
							$className = 'col-md-12';
							break;
						case '2':
							$className = 'col-md-6';
							break;
						case '3':
							$className = 'col-md-4';
							break;
						default:
							$className = '';
							break;
					}
			?>
				<div class="row">
					<?php foreach ($question['QuestionPhoto'] as $photo_key => $photo) : ?>
						<div class="<?php echo $className ?>"><img class="img-responsive" src="<?php echo $this->request->webroot; ?>uploads/questions/<?php echo $photo['photo']; ?>"></div>
					<?php endforeach; ?>
				</div>
				<br>
			<?php endif; ?>
			<span class="question_posted_time"><?php echo __('Posted') . ' ' . $this->Forum->getTime($question['Question']['created']); ?></span>
			<span><div class="fb-share-button" data-title='<?php echo h($question['Question']['title']); ?>' data-href="<?php echo $this->request->base; ?>/questions/view/<?php echo md5($question['Question']['id']); ?>" data-layout="button_count"></div></span>
			<span><button ques-id="<?php echo md5($question['Question']['id']); ?>" class="btn btn-xs interest"><?php echo __('Interest') . ' '; ?><span class="interest-count"><?php echo $question['Question']['user_interest_count']; ?></span></button>&nbsp;</span>
			<span>
				<?php if (!empty($question['UserBookmark'])) : ?>
					<button class="btn btn-xs" disabled><?php echo __('Bookmark'); ?></button>
				<?php else : ?>
					<button ques-id="<?php echo md5($question['Question']['id']); ?>" class="btn btn-xs bookmark"><?php echo __('Bookmark'); ?></button>
				<?php endif; ?>
			</span>
			<span class="pull-right"><button ques-id="<?php echo md5($question['Question']['id']); ?>" class="btn btn-xs wrong_category"><?php echo __('Report Abuse/Wrong Category'); ?></button></span>
		</div>
		<div class="col-md-12">
			<div class="row">
		        <div class="col-md-12 text-right">                
					<?php	if($this->Paginator->counter('{:count}')) :?>
		                <p><?php echo $this->Paginator->counter(array('format' => __('SHOWING {:start} TO {:end} OUT {:count}')));?></p>
		            <?php endif;?>
		        </div>
		    </div>
			<ul class="list-group" id="all_answers">
				<?php foreach ($answers as $key => $answer) : ?>
					<?php echo $this->element('answers/answers_list', array('answer' => $answer)); ?>
				<?php endforeach; ?>
			</ul>
			<?php 
				echo $this->Paginator->pagination( array(
					'ul' => 'pagination answer_pagination fg-pagination-margin pull-right'
				)); 
			?>
		</div>
		<div class="col-md-12">
			<form role="form" id="answer_form">
				<input type="hidden" id="answer_question_id" name="data[Answer][question_id]" value="<?php echo md5($question['Question']['id']); ?>">
				<div class="form-group">
					<label for="answer_body"><?php echo __('Answer'); ?><?php echo ' (Total ' . $question['Question']['answer_count'] . ' answers found)'; ?></label>
					<textarea name="data[Answer][body]" class="form-control" rows="5" placeholder="Please write your answer" id="answer_body"></textarea>
				</div>
				<div class="form-group" id="answer_create_error"></div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
</section>
<?php 
	echo $this->Html->script(array('vendors/jquery/1.11.2/jquery.min', 'question_view'), array('inline' => true));
?>

<script type="text/javascript">
$("#left_category_list li").find('.current_category').removeClass('current_category');
</script>