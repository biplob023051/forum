<aside class="col-md-3" id="righ_panel">
	<h2 class="remove_space"><?php echo __('Ask your quesstion'); ?></h2>
	<ul class="nav nav-tabs">
        <li class="active">
        	<a data-toggle="tab" href="#question" aria-expanded="true">Question</a>
        </li>
        <li class="">
        	<a data-toggle="tab" href="#poll" aria-expanded="false">Poll</a>
        </li>
    </ul>
    <div class="tab-content">
		<div id="question" class="tab-pane fade active in">
			<h3><?php echo __('Add New Question'); ?></h3>
			<form role="form" id="question_form">
				<div class="form-group">
					<label for="q_title"><?php echo __('Title'); ?></label>
					<input type="text" name="data[Question][title]" class="form-control" id="q_title">
				</div>
				<div class="form-group">
					<label for="body"><?php echo __('Description'); ?></label>
					<textarea name="data[Question][body]" class="form-control" rows="5" id="question_body"></textarea>
				</div>
				<div class="form-group">
					<label for="ques_cat"><?php echo __('Category'); ?></label>
					<button class="btn btn-primary select-category" id="ques_cat"><?php echo __('Select Category <i class="fa fa-caret-down"></i>'); ?></button>
					<input type="hidden" name="data[Question][category_id]" id="hidden_question_category_id">
				</div>
				<div class="form-group">
					<div id="question_form_photos"></div>
					<div id="questions-0" style=""></div>
				</div>
				<div class="form-group" id="question_create_error"></div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
		<div id="poll" class="tab-pane fade">
			<h3><?php echo __('Add New Poll'); ?></h3>
			<form role="form" id="poll_form">
				<input type="hidden" name="data[Question][type]" value="1">
				<div class="form-group">
					<label for="p_title"><?php echo __('Question'); ?></label>
					<input type="text" name="data[Question][title]" class="form-control" id="p_title">
				</div>
				<div id="poll_options">
					<div class="form-group">
						<label><?php echo __('Option 1'); ?></label>
						<input type="text" name="data[QuestionOption][0][text]" class="form-control poll-option">
					</div>
					<div class="form-group">
						<label><?php echo __('Option 2'); ?></label>
						<input type="text" name="data[QuestionOption][1][text]" class="form-control poll-option">
					</div>
				</div>
				<div class="form-group">
					<label for="poll_cat"><?php echo __('Category'); ?></label>
					<button class="btn btn-primary select-category" id="poll_cat"><?php echo __('Select Category <i class="fa fa-caret-down"></i>'); ?></button>
					<input type="hidden" name="data[Question][category_id]" id="hidden_poll_category_id">
				</div>
				<div class="form-group" id="poll_create_error"></div>
				<div class="form-group">
					<button type="submit" class="btn btn-default pull-left">Submit</button>
					<input type="button" id="add_more_option" class="pull-right btn btn-primary" value="Add More Option">
				</div>
			</form>
		</div>
    </div>
</aside>