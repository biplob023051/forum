<?php if (empty($errorFound)) : ?>
	<h3 class="vote_status_info text-success">You successfully voted this poll.</h3>
	<a href="javascript:void(0)" class="after_view_result" data-rel="<?php echo md5($question['Question']['id']); ?>"> View results </a>
	<div class="poll-result">
		<?php $percentage = empty($question['Question']['vote_count']) ? 0 : 100/$question['Question']['vote_count']; ?>
		<?php foreach ($question['QuestionOption'] as $key => $value) : ?>
			<?php 
				if (!empty($value['vote_count'])) {
					$progress = $question['Question']['vote_count']/$value['vote_count']; 
				}
				$progress = $value['vote_count']*$percentage;
			?>
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $progress; ?>"
				aria-valuemin="0" aria-valuemax="<?php echo $question['Question']['vote_count']; ?>" style="width:<?php echo $progress; ?>%">
					<?php echo number_format((float)$progress, 2, '.', '') . '% '. $value['text']; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php else : ?>
	<script type="text/javascript">
		var errorType = '<?php echo $errorFound; ?>';
		if (errorType == 1) {
			window.location.reload();
		} else {
			window.location.href = projectBaseUrl + "member";
		}
	</script>
<?php endif; ?>