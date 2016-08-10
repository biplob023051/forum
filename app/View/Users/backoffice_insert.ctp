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
                echo $this->Form->input('name', array('label'=>array('text'=>__('Full Name')), 'placeholder' => __('Enter your full name')));
                echo $this->Form->input('email', array('label'=>array('text'=>__('Email Address')),'placeholder' => __('Enter email')));
                echo $this->Form->input('password', array('label'=>array('text'=>__('Password')),'placeholder' => __('Enter password'),'type'=>'password'));  
                    echo $this->Form->input('passwordVerify', array('label'=>array('text'=>__('Verify Password')),'placeholder' => __('Verify password'),'type'=>'password'));
                echo $this->Form->input('city', array('label'=>array('text'=>__('Place')), 'placeholder' => __('Enter city')));
                echo $this->Form->input('work_at', array('label'=>array('text'=>__('Work Place')), 'placeholder' => __('Enter work place')));
                echo $this->Form->input('study_at', array('label'=>array('text'=>__('Study place')), 'placeholder' => __('Enter study place')));
                if (AuthComponent::user('id') == 1)
                echo $this->Form->input('role_id', array('label'=>array('text'=>__('Role')), 'options' => array(50 => 'User', 10 => 'Site Admin')));
                echo $this->Form->input('country', array('label'=>array('text'=>__('Country')), 'options' => $this->Forum->country_list()));

            ?>
            
            <div class="form-group">
            	<div class="col col-sm-7 col-sm-offset-3"> 
                    <?php echo $this->Html->link(__('BACK'),array('controller'=>'users','action'=>'index', 'backoffice' => true),array('class'=>'btn btn-danger'));?>
					<?php echo $this->Form->submit(__('SAVE'), array(
                        'div' => false,
                        'class' => 'btn btn-primary btn-xlarge'
                    )); ?>                
                </div>
            </div>
            
        <?php echo $this->Form->end(); ?>
	</div>
</div>