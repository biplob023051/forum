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
			<h2><?php echo h($poll['Question']['title']); ?></h2>
			<span class="question_posted_time"><?php echo __('Posted') . ' ' . $this->Forum->getTime($poll['Question']['created']); ?></span>
		</div>
		<div class="col-md-12">
			<div class="row">
		        <div class="col-md-12 text-right">                
					<div class="poll-result">
						<?php $percentage = empty($poll['Question']['vote_count']) ? 0 : 100/$poll['Question']['vote_count']; ?>
						<?php foreach ($poll['QuestionOption'] as $key => $value) : ?>
							<?php 
								if (!empty($value['vote_count'])) {
									$progress = $poll['Question']['vote_count']/$value['vote_count']; 
								}
								$progress = $value['vote_count']*$percentage;
							?>
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $progress; ?>"
								aria-valuemin="0" aria-valuemax="<?php echo $poll['Question']['vote_count']; ?>" style="width:<?php echo $progress; ?>%">
									<?php echo $progress . '% ' . $value['text']; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
		        </div>
		    </div>
		</div>
	</div>
</section>

<script type="text/javascript">
$("#left_category_list li").find('.current_category').removeClass('current_category');
</script>