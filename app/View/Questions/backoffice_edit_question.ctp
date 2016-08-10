<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body"> 
		<?php echo $this->Form->create('Question', array(
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
                $statusOptions = array(
                    0 => __('New'),
                    1 => __('Pending'),
                    2 => __('Completed'),
                    3 => __('Censored')
                );
                echo $this->Form->input('id');
                echo $this->Form->input('title', array('type' => 'text', 'label'=>array('text'=>__('Title')), 'placeholder' => __('Enter question title')));
                echo $this->Form->input('body', array('label'=>array('text'=>__('Body')),'placeholder' => __('Enter question body')));
                echo $this->Form->input('category_id', array('label'=>array('text'=>__('Category')), 'options' => $treelist));
                echo $this->Form->input('tags', array('type' => 'text', 'label'=>array('text'=>__('Tags')), 'placeholder' => __('Enter comman separated tags')));
                echo $this->Form->input('isactive', array('label'=>array('text'=>__('Status')), 'options' => $statusOptions));
            ?>
            
            <div class="form-group">
            	<div class="col col-sm-7 col-sm-offset-3"> 
                    <?php echo $this->Html->link(__('BACK'),array('controller'=>'questions','action'=>'index', 'backoffice' => true),array('class'=>'btn btn-danger'));?>
					<?php echo $this->Form->submit(__('SAVE'), array(
                        'div' => false,
                        'class' => 'btn btn-primary btn-xlarge'
                    )); ?>                
                </div>
            </div>
            
        <?php echo $this->Form->end(); ?>
	</div>
</div>