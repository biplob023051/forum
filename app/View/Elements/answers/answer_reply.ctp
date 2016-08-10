<?php if (empty($errorFound)) : ?>
	<li class="list-group-item">
		<div class="row">
			<div class="col-md-1 user-avatar">
				<img class="img-circle img-responsive" src="<?php echo $this->request->webroot . 'img/admin-sm.png'; ?>">
			</div>
			<div class="col-md-11 user-answer admin_text">
				<p><?php echo h($this->Text->truncate(strip_tags(str_replace(array('<br>', '&nbsp;'), array(' ', ''), $this->Forum->cleanHtmlTopic($reply['body']))), 10000)); ?></p>
				<div class="author"><span>Admin note </span><span class="question_posted_time"><?php echo __('Posted') . ' ' . $this->Forum->getTime($reply['created']); ?></span></div>
			</div>
		</div>
	</li>
<?php else : ?>
	<script type="text/javascript">
		var errorType = '<?php echo $errorFound; ?>';
		if (errorType == 1) {
			window.location.reload();
		} else {
			window.location.href = projectBaseUrl + "backoffice";
		}
	</script>
<?php endif; ?>