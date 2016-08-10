<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body"> 
        <center>
            <div class="alert alert-success">
                <?php if(!empty($close)):?>
                    <a class="close" data-dismiss="alert" href="#">&times;</a>
                <?php endif;?>
                <i class="fa fa-check"></i>
                <?php echo $message; ?>
            </div>
            <div>
                Already registered member? <?php echo $this->Html->link(__('Login Now!'),array('controller'=>'users','action'=>'home', 'member' => true),array());?>
            </div>
        </center>
	</div>
</div>

<style type="text/css">
.alert-success {
    font-size: 24px;
}
</style>