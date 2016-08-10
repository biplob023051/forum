<div class="header"><h2><?php echo $title_for_layout;?></h2></div>
<?php echo $this->Form->create('User', array(
	'inputDefaults' => array(
		'div' => 'form-group',
        'label' => false,
        'wrapInput' => false,
        'class' => 'form-control'
	),
	'class' => false,
	'url'=>array(
		'controller'=> 'admins',
		'action'=> 'login',
        'backoffice' => true
	)
)); ?>
	<?php
        echo $this->Form->input('email', array('placeholder' => __('Enter email')));
        echo $this->Form->input('password', array('placeholder' => __('Enter password')));
    ?>
            
    <?php echo $this->Form->submit(__('Login'), array(
        'div' =>false,
        'class' => 'btn bg-olive btn-block'
    )); ?>

<?php echo $this->Form->end(); ?>
            