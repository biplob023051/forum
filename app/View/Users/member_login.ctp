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
		'controller'=> 'users',
		'action'=> 'login',
        'member' => true
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
    <p>Not a member yet! <?php echo $this->Html->link(__('Signup Now!'),array('controller'=>'users','action'=>'signup', 'member' => true),array());?></p>
    <p>Forgot password? <?php echo $this->Html->link(__('Reset Password!'),array('controller'=>'users','action'=>'forgot_password', 'member' => true),array());?>
    </p>

<?php echo $this->Form->end(); ?>
            