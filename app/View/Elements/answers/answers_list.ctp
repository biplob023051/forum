<li class="list-group-item">
	<div class="row">
		<div class="col-md-1 user-avatar">
			<img class="img-circle img-responsive" src="<?php echo $this->Forum->getUserAvatar($answer['User']['avatar']); ?>">
		</div>
		<div class="col-md-11 user-answer<?php if (in_array($answer['User']['role_id'], Configure::read('Role.Backoffice'))) echo ' admin_text'; ?>">
			<p><?php if ($answer['User']['id'] == AuthComponent::user('id')) : ?><span><button type="button" class="btn btn-danger btn-sm delete-answer" answer-id="<?php echo $answer['Answer']['md5_id']; ?>" title="<?php echo __('Delete answer'); ?>"><i class="fa fa-trash"></i></button></span> <?php endif; ?><?php echo h($this->Text->truncate(strip_tags(str_replace(array('<br>', '&nbsp;'), array(' ', ''), $this->Forum->cleanHtmlTopic($answer['Answer']['body']))), 10000)); ?></p>
			<div class="author"><span>Posted by <?php echo empty($answer['User']['name']) ? $answer['User']['email'] . ' ' : $answer['User']['name'] . ' '; ?></span><span class="question_posted_time"><?php echo __('Posted') . ' ' . $this->Forum->getTime($answer['Answer']['created']); ?></span></div>
			<?php if (!in_array($answer['User']['role_id'], Configure::read('Role.Backoffice'))) : ?>
			<span><button class="btn like" type="yes" ans-id="<?php echo md5($answer['Answer']['id']); ?>"><i class="fa fa-thumbs-up"></i> <span class="related-count"><?php echo $answer['Answer']['like_count']; ?></span></button></span>
			<span><button class="btn like" type="no" ans-id="<?php echo md5($answer['Answer']['id']); ?>"><i class="fa fa-thumbs-down"></i> <span class="related-count"><?php echo $answer['Answer']['dislike_count']; ?></span></button></span>
			<?php endif; ?>
			<?php  if(AuthComponent::user('id') && in_array(AuthComponent::user('role_id'), Configure::read('Role.Backoffice'))):?>
				<span class="pull-right">
					<button class="btn admin-reply-form" ans-id="<?php echo md5($answer['Answer']['id']); ?>"><i class="fa fa-reply"></i> Reply</button>
				</span>
				<div class="form-group" style="display:none;">
					<div class="form-group" id="reply_create_error_<?php echo md5($answer['Answer']['id']); ?>"></div>
					<input type="hidden" name="reply-id" class="admin-reply-id" value="<?php echo md5($answer['Answer']['id']); ?>">
					<textarea name="reply-body" class="form-control admin-reply-body" rows="3" placeholder="Please write your reply"></textarea>
					<button type="submit" class="btn btn-default admin-reply-submit">Submit</button>
				</div>
			<?php endif; ?>
			<ul class="list-group all-reply">
				<?php if (!empty($answer['Reply'])) : ?>
					<?php foreach ($answer['Reply'] as $key => $reply) : ?>
						<?php echo $this->element('answers/answer_reply', array('reply' => $reply)); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</li>