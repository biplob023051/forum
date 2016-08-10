<?php if (!empty($categories)) : ?>
	<?php foreach ($categories as $key => $category) : ?>
    	<li class="list-group-item"><a href="javascript:void(0)" cat-id="<?php echo md5($key); ?>"><?php echo $category; ?></a></li>
    <?php endforeach; ?>
<?php endif; ?>