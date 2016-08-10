<div class="header"><?php echo $title_for_layout;?></div>
<?php if(!empty($reset_done)) : ?>
    <div class="body bg-gray">
        <p class="text-success"><?php echo __('Reset password success');?></p>
    </div>
    <div class="footer">
    	<?php echo $this->Html->link(__('Login'),'/'.$this->params['prefix'],array('class'=>'btn bg-olive btn-block','role'=>'button')); ?>
    </div>
<?php else : ?>
    <?php echo $this->Form->create('User', array(
        'inputDefaults' => array(
            'div' => 'form-group',
            'label' => false,
            'wrapInput' => false,
            'class' => 'form-control'
        ),
        'class' => false
    )); ?>
        <div class="body bg-gray">
            <?php echo $this->Form->input('id');?>
            <?php echo $this->Form->input('password', array('label'=>array('text'=>__('New Password')),'placeholder' => __('Enter New Password'),'type'=>'password')); ?>
            <?php echo $this->Form->input('passwordVerify', array('label'=>array('text'=>__('Verify Password')),'placeholder' => __('Verify your password'),'type'=>'password')); ?>
        </div>

        <div class="footer">                
            <?php echo $this->Form->submit(__('Update Password'), array(
                'div' =>false,
                'class' => 'btn bg-olive btn-block'
            )); ?>
            <p><?php echo $this->Html->link('<i class="fa fa-long-arrow-left"></i> '.__('Back To Login'),array('controller'=>'users','action'=>'home','member'=>true),array('escape'=>false));?></p>
        </div>
    <?php echo $this->Form->end(); ?>
    
<?php endif;?>