<style type="text/css">
.panel-title span {
    font-size: 13px;
}
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> <b><?php echo $title_for_layout;?></b></h3>
    </div>
    <div class="panel-body">
         <?php echo $this->Form->create('User', array(
            'inputDefaults' => array(
                //'div' => 'form-group',
                'label' => array(
                    'class' => 'col col-sm-8 control-label'
                ),
                'wrapInput' => 'col col-sm-8',
                'class' => 'form-control'
            ),
            'class' => 'form-horizontal',
            'type' => 'file',
            'novalidate'=>'novalidate'
        )); ?>  
        <div class="table-responsive">
            <table cellpadding="0" cellspacing="0"  class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2">
                            <b><?php echo __('Your Profile Details');?></b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $this->Form->input('id'); ?>
                    <?php echo $this->Form->input('avatar', array('type' => 'hidden')); ?>
                    <tr>
                        <td class="col-sm-4 text-right" nowrap="nowrap"><?php echo __('Profile Picture');?></td>
                        <td class="col-sm-4 text-left" nowrap="nowrap">
                            <img id="item-avatar" class="img-circle img-responsive" src="<?php echo $this->Forum->getUserAvatar($this->request->data['User']['avatar']); ?>">
                            <div id="select-0" style=""></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-sm-4 text-right" nowrap="nowrap">Crop Image</td>
                        <td class="col-sm-4 text-left" nowrap="nowrap">
                            <img id="target" class="img-responsive" src="<?php echo $this->Forum->getUserFullImage($this->request->data['User']['avatar']); ?>">
                            <div id="coords">
                                <input type="hidden" size="4" id="x" name="data[Crop][x]" />
                                <input type="hidden" size="4" id="y" name="data[Crop][y]" />
                                <input type="hidden" size="4" id="w" name="data[Crop][w]" />
                                <input type="hidden" size="4" id="h" name="data[Crop][h]" />
                            </div>
                        </td>
                    </tr>
                     <tr>
                        <td class="col-sm-4 text-right" nowrap="nowrap"><?php echo __('Your email (Can\'t be changed)');?></td>
                        <td><?php echo $this->request->data['User']['email'] ?></td>
                    </tr>
                    <tr>
                        <td class="col-sm-4 text-right" nowrap="nowrap"><?php echo __('Your Name');?></td>
                        <td><?php echo $this->Form->input('name', array('label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td class="col-sm-4 text-right" nowrap="nowrap"><?php echo __('Work At');?></td>
                        <td><?php echo $this->Form->input('work_at', array('label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td class="col-sm-4 text-right" nowrap="nowrap"><?php echo __('Study At');?></td>
                        <td><?php echo $this->Form->input('study_at', array('label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td class="col-sm-4 text-right" nowrap="nowrap"><?php echo __('Country'); ?></td>
                        <td><?php echo $this->Form->input('country', array('label' => false, 'options' => $this->Forum->country_list(), 'empty' => __('Select Country'))); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <div class="col col-sm-7 col-sm-offset-6">
                <?php echo $this->Form->submit(__('Update'), array(
                    'div' => false,
                    'class' => 'btn btn-primary btn-xlarge'
                )); ?>                
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>


<?php 
    echo $this->Html->script(array('jquery.min', 'jquery.fineuploader', 'jquery.Jcrop.min'), array('inline' => true));
    echo $this->Html->css(array('fineuploader', 'jquery.Jcrop.min'), array('inline' => true));
?>
<script type="text/javascript">
    jQuery(function($){

    var jcrop_api;

    $('#target').Jcrop({
      onChange:   showCoords,
      onSelect:   showCoords,
      onRelease:  clearCoords
    },function(){
      jcrop_api = this;
    });

    $('#coords').on('change','input',function(e){
      var x1 = $('#x1').val(),
          x2 = $('#x2').val(),
          y1 = $('#y1').val(),
          y2 = $('#y2').val();
      jcrop_api.setSelect([x1,y1,x2,y2]);
    });

  });

  // Simple event handler, called from onChange and onSelect
  // event handlers, as per the Jcrop invocation above
  function showCoords(c)
  {
    console.log(c);
    $('#x').val(c.x);
    $('#y').val(c.y);
    // $('#x2').val(c.x2);
    // $('#y2').val(c.y2);
    $('#w').val(c.w);
    $('#h').val(c.h);
  };

  function clearCoords()
  {
    $('#coords input').val('');
  };

    $('#select-0').fineUploader({
        request: {
            endpoint: "<?php echo $this->request->base; ?>/member/uploads/avatar/<?php echo $this->request->data['User']['id']; ?>"
        },
        text: {
            uploadButton: '<?php echo __('Upload Profile Picture'); ?>'
        },
        validation: {
            allowedExtensions: ['jpg', 'jpeg', 'gif', 'png'],
                sizeLimit: 10 * 1024 * 1024
        },
            multiple: false
        }).on('complete', function(event, id, fileName, response) {
            $('#UserAvatar').val(response.filename);
            $('#item-avatar').attr('src', response.avatar);
            $('#target').attr('src', response.fullimage);
            $('.jcrop-holder').find('img').attr('src', response.fullimage);

        });
</script>