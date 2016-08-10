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
</div>
<div id="category_section" class="col-md-12">
	<?php echo $this->element('left_category_list', array('ul_id_name' => 'middle_category_list')); ?>
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
				<?php echo $this->element('ajax_single_question', array('question' => $question)); ?>
			<?php endforeach; ?>
		<?php else : ?>
			<li class="list-group-item"><?php echo __('Questions/polls has not found yet!'); ?></li>
		<?php endif; ?>
	</ul>
	<?php 
		echo $this->Paginator->pagination( array(
			'ul' => 'pagination custom_pagination fg-pagination-margin pull-right'
		)); 
	?>
</div>