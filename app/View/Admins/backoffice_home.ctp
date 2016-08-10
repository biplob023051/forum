<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body">
        <h2>General Settings</h2>
        <form action="<?php echo $this->request->base; ?>/backoffice/admins/home" enctype="multipart/form-data" method="post">
			<div class="form-group">
				<label>Site Name</label>
				<?php echo $this->Form->text('site_name', array('class' => 'form-control', 'value' => $setting['site_name'])); ?>
			</div>
			<div class="form-group">
				<label>Site Email</label>
				<?php echo $this->Form->text('site_email', array('class' => 'form-control', 'value' => $setting['site_email'])); ?>
			</div>
			<div class="form-group">
				<label>Unanswered question auto publish interval (In hours)</label>
				<?php echo $this->Form->text('auto_publish', array('class' => 'form-control', 'value' => $setting['auto_publish'])); ?>
			</div>
			<div class="form-group">
				<label>Restricted Keywords</label><span> (Comma (',') is the restricted keyword separator)</span>
				<?php echo $this->Form->textarea('site_keywords', array('class' => 'form-control', 'value' => $setting['site_keywords'])); ?>
			</div>
		<div class="regSubmit">
			<input type="submit" value="Save Settings" class="btn btn-primary btn-xlarge">
		</div>
	</form>
    </div>
</div>