<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body"> 
		<?php echo $this->Form->create('User', array(
            'inputDefaults' => array(
                'div' => 'form-group',
                'label' => array(
                    'class' => 'col col-sm-3 control-label'
                ),
                'wrapInput' => 'col col-sm-7',
                'class' => 'form-control'
            ),
            'class' => 'form-horizontal',
            'type' => 'file',
            'novalidate'=>'novalidate'
        )); ?>
        
             <?php
                echo $this->Form->input('id');
                echo $this->Form->input('name', array('label'=>array('text'=>__('Name')), 'placeholder' => __('Enter your name')));
                echo $this->Form->input('email', array('label'=>array('text'=>__('Email Address')),'placeholder' => __('Enter your email')));
                echo $this->Form->input('password', array('label'=>array('text'=>__('Password')),'placeholder' => __('Enter your password'),'type'=>'password'));  
                    echo $this->Form->input('passwordVerify', array('label'=>array('text'=>__('Verify Password')),'placeholder' => __('Verify password'),'type'=>'password'));
                echo $this->Form->input('phone', array('label'=>array('text'=>__('Mobile number')), 'placeholder' => __('Enter your mobile number')));
                echo $this->Form->input('city', array('label'=>array('text'=>__('City')), 'placeholder' => __('Enter your city')));
                echo $this->Form->input('district', array('label'=>array('text'=>__('District')), 'placeholder' => __('Enter your district')));
                echo $this->Form->input('state', array('label'=>array('text'=>__('State')), 'placeholder' => __('Enter your state')));

            ?>
            
            <div class="form-group">
            	<div class="col col-sm-7 col-sm-offset-3">
					<?php echo $this->Form->submit(__('Register'), array(
                        'div' => false,
                        'class' => 'btn btn-primary btn-xlarge'
                    )); ?>                
                </div>
                <div class="col col-sm-7 col-sm-offset-3">
                    Already registered member? <?php echo $this->Html->link(__('Login Now!'),array('controller'=>'users','action'=>'home', 'backoffice' => true),array());?>
                </div>
            </div>
            
        <?php echo $this->Form->end(); ?>
	</div>
</div>