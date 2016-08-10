<?php if (empty($errorFound)) : ?>
	<?php echo $this->element('answers/answers_list', array('answer' => $answer)); ?>
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