<ul class="list-group" id="<?php echo $ul_id_name; ?>">
	<li class="list-group-item parents">
		<?php if (!empty($parents)) : ?>
			<a href="javascript:void(0)" id="default_category" class="category" cat-id="0"><?php echo __('All Categories'); ?></a>
			<?php foreach ($parents as $key => $parent) : ?>
				>> <a href="javascript:void(0)" id="category_<?php echo $key; ?>" class="category" cat-id="<?php echo md5($parent['Category']['id']); ?>"><?php echo $parent['Category']['name']; ?></a>
			<?php endforeach; ?>
			<br>
			<a href="javascript:void(0)" class="category current_category" cat-id="<?php echo md5($current_category['Category']['id']); ?>"><?php echo $current_category['Category']['name']; ?></a>
		<?php else : ?>
			<a href="javascript:void(0)" id="default_category" class="category current_category" cat-id="0"><?php echo __('All Categories'); ?></a>
		<?php endif; ?>
	</li>
	<?php if (!empty($firstChild)) : ?>
		<?php foreach ($firstChild as $key => $category) : ?>
	    	<li class="list-group-item childs"><a href="javascript:void(0)" class="category" cat-id="<?php echo md5($category['Category']['id']); ?>"><?php echo $category['Category']['name']; ?></a></li>
	    <?php endforeach; ?>
	<?php endif; ?>
</ul>