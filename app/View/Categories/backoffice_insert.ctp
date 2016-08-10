<div class="row">
    <div class="col-sm-12">
    	<ul class="nav nav-pills">
        	<li><?php echo $this->Html->link(__('Category List'),array('controller'=>'categories','action'=>'index', 'backoffice' => true),array("role"=>"button", "class"=>"btn btn-link"));?></li> 
            <li><?php echo $this->Html->link(__('New Category'),array('controller'=>'categories','action'=>'insert', 'backoffice' => true),array("role"=>"button", "class"=>"btn btn-link"));?></li> 
        </ul>
    </div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body"> 
		<?php echo $this->Form->create('Category', array(
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
            <div class="form-group">
                <div class="col col-sm-7 col-sm-offset-3 required-note text-right">
                	<?php echo __('Required field');?>
                </div>
            </div>
        
            <?php
                echo $this->Form->input('id');
                echo $this->Form->input('name', array('label' => array('text' =>__('Category Name'))));
                echo $this->Form->input('parent_id', array('label'=>array('text'=>__('Parent Category')), 'options' => $treelist, 'empty' => __('Select Parent')));
            ?>
            
            <div class="form-group">
            	<div class="col col-sm-7 col-sm-offset-3">
                    <?php if(empty($this->params['url']['redirect_url'])) : ?>
                         <?php echo $this->Html->link(__('BACK'),array('controller'=>'categories','action'=>'index', 'backoffice' => true),array('class'=>'btn btn-danger'));?>
                    <?php else : ?>
                        <?php echo $this->Html->link(__('BACK'),urldecode($this->params['url']['redirect_url']),array('class'=>'btn btn-danger'));?>
                    <?php endif; ?>
					<?php echo $this->Form->submit(__('SAVE'), array(
                        'div' => false,
                        'class' => 'btn btn-primary btn-xlarge'
                    )); ?>                
                </div>
            </div>
            
        <?php echo $this->Form->end(); ?>
	</div>
</div>