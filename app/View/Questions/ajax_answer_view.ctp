<?php if (!empty($answers)) : ?>
	<?php foreach ($answers as $key => $answer) : ?>
		<?php echo $this->element('answers/answers_list', array('answer' => $answer)); ?>
	<?php endforeach; ?>
<?php endif; ?>