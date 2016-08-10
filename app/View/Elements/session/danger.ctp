<div class="alert alert-danger">
	<?php if(!empty($close)):?>
		<a class="close" data-dismiss="alert" href="#">&times;</a>
	<?php endif;?>
	<i class="fa fa-ban"></i>
	<?php echo $message; ?>
</div>