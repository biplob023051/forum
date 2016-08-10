<div class="header"><h2><?php echo $title_for_layout;?></h2></div>
<?php echo $this->Form->create('User', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'label' => false,
        'wrapInput' => false,
        'class' => 'form-control'
    ),
    'class' => false
)); ?>
        
    <?php
        echo $this->Form->input('email', array('label'=>array('text'=>__('Email Address')),'placeholder' => __('Enter your email')));
    ?>
         
    <?php echo $this->Form->submit(__('Reset Request'), array(
        'div' =>false,
        'class' => 'btn bg-olive btn-block'
    )); ?>
    <p><?php echo $this->Html->link('<i class="fa fa-long-arrow-left"></i> '.__('Login Now'),array('controller'=>'users','action'=>'home', 'member' => true),array('escape'=>false));?></p>
<?php echo $this->Form->end(); ?>