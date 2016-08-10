<?php
    $this->Html->script(array('vendors/chained/jquery.chained.min'), array('inline' => false));
?>
<?php
    if(empty($this->request->data['Search']['search_type'])){
        $this->request->data['Search']['search_type']='time_period';
    }
    if(empty($this->request->data['Search']['time_period'])){
        $this->request->data['Search']['time_period']='day_type';
    }

    if(empty($showSearchByShop)){
        $showSearchByShop=false;
    }
?>
<?php 
    echo $this->Form->create('Search', array(
        'inputDefaults' => array(
            'div' => false,
            'label' => false,
            'wrapInput' => false,
            'class' => 'form-control'
        ),
        'class' => 'form-search',
        'novalidate'=>'novalidate'
    ));
    ?>
    <fieldset>
        <legend><?php echo $this->Form->input('search_type', array('type'=>'radio','hiddenField'=>false, 'options'=>array('time_period' => __('USER_SEARCH_BY_TIME'))));?></legend>
        <div class="form-group">
            <?php
                echo $this->Form->input('time_period', array(
                    'type'=>'radio',
                    'hiddenField'=>false,
                    'options'=>array('day_type' => '')
                ));
                echo $this->Form->input('Search.day_type', array(
                    'options'=>array(
                        'this-month' => __('THIS_MONTH'),
                        'last-month' => __('LAST_MONTH'),
                        'all' => __('ALL')
                    )
                ));
            ?>
        </div>
        <div class="form-group">
            <?php
                echo $this->Form->input('time_period', array(
                    'type'=>'radio',
                    'hiddenField'=>false,
                    'options'=>array('day_range' => ''),
                ));
                echo $this->Form->input('Search.day_range.from', array(
                    'type'=>'date',
                    'after'=>'~'
                ));
                echo $this->Form->input('Search.day_range.to', array(
                    'type'=>'date'
                ));
            ?>
        </div>
        <div class="form-group">
            <?php
                echo $this->Form->input('time_period', array(
                    'type'=>'radio',
                    'hiddenField'=>false,
                    'options'=>array('new-user' => __('USER_SEARCH_BY_NEW_USER')),
                ));
            ?>
        </div>
        <div class="form-group">
            <?php
                echo $this->Form->input('time_period', array(
                    'type'=>'radio',
                    'hiddenField'=>false,
                    'options'=>array('new-user' => __('USER_SEARCH_BY_CONTACT_END_SOON')),
                ));
            ?>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo $this->Form->input('search_type', array('type'=>'radio','hiddenField'=>false, 'options'=>array('product' => __('USER_SEARCH_BY_PRODUCT'))));?></legend>
        <div class="form-group">
            <?php
                App::import('Model', 'Genre');
                $genre = new Genre();
                $genreOptions = $genre->genreOptions();
                echo $this->Form->input('Search.product.genre_id', array(
                    'options'=>$genreOptions,
                    'empty'=>__('SELECT_ONE_GENRE'),
                    'after'=>' '
                ));
                App::import('Model', 'Category');
                $category = new Category();
                $categoryOptions = $category->categoryOptions();
                echo $this->Form->input('Search.product.category_id', array(
                    'options'=>$categoryOptions,
                    'empty'=>__('SELECT_ONE_CATEGORY'),
                    'after'=>' '
                ));
                App::import('Model', 'SubCategory');
                $sub_category = new SubCategory();
                $subCategoryOptions = $sub_category->subCategoryOptions();
                echo $this->Form->input('Search.product.sub_category_id', array(
                    'options'=>$subCategoryOptions,
                    'empty'=>__('SELECT_ONE_SUB_CATEGORY')
                ));
            ?>
        </div>
    </fieldset>

    <?php if($showSearchByShop):?>

        <fieldset>
            <legend><?php echo $this->Form->input('search_type', array('type'=>'radio','hiddenField'=>false, 'options'=>array('shop' => __('USER_SEARCH_BY_SHOP'))));?></legend>
            <div class="form-group">
                <?php
                    App::import('Model', 'Group');
                    $group = new Group();
                    $groupOptions = $group->groupOptions();
                    echo $this->Form->input('Search.shop.group_id', array(
                        'options'=>$groupOptions,
                        'empty'=>__('SELECT_ONE_GROUP'),
                        'after'=>' '
                    ));
                    App::import('Model', 'Shop');
                    $shop = new Shop();
                    $shopOptions = $shop->shopOptions();
                    echo $this->Form->input('Search.shop.shop_id', array(
                        'options'=>$shopOptions,
                        'empty'=>__('SELECT_ONE_SHOP')
                    ));
                ?>
            </div>
        </fieldset>
    <?php endif;?>

    <fieldset>
        <legend><?php echo $this->Form->input('search_type', array('type'=>'radio','hiddenField'=>false, 'options'=>array('text' => __('USER_SEARCH_BY_TEXT'))));?></legend>
        <div class="form-group row">
            <div class="col col-sm-6 col-md-4">
            <?php
                echo $this->Form->input('Search.text.q');
            ?>
            </div>
        </div>
    </fieldset>
    <div class="form-group">
    <?php echo $this->Form->button(__('SEARCH'), array('type'=>'submit', 'class'=>'btn btn-success'));?>
    </div>
<?php echo $this->Form->end();?>

<?php 
    $this->Js->Buffer(' 
        jQuery("#SearchProductCategoryId").chained("#SearchProductGenreId");
        jQuery("#SearchProductSubCategoryId").chained("#SearchProductCategoryId");
        jQuery("#SearchShopDelearId").chained("#SearchShopShopId");

        jQuery(".form-search").children("fieldset").find(".form-group").hide();
        jQuery(".form-search").children("fieldset").find(".form-group input, .form-group select").attr("disabled","disabled");
        jQuery("input[name=\'data[Search][search_type]\']").change(function(){
            if(jQuery(this).is(":checked")){
                jQuery(this).closest(".form-search").children("fieldset").find(".form-group").hide();
                
                jQuery(this).closest(".form-search").children("fieldset").find(".form-group input, .form-group select").attr("disabled","disabled");
                
                jQuery(this).closest("fieldset").children(".form-group").fadeIn();
                
                jQuery(this).closest("fieldset").find(".form-group input, .form-group select").removeAttr("disabled");                
                jQuery(this).closest("fieldset").find(".form-group input[name=\'data[Search][time_period]\']").trigger("change");
            } 
        }).trigger("change");  
        jQuery("input[name=\'data[Search][time_period]\']").change(function(){
            if(jQuery(this).is(":checked")){
                jQuery(this).closest("fieldset").find(".form-group input[name=\'data[Search][time_period]\']").siblings().attr("disabled","disabled");
                jQuery(this).siblings().removeAttr("disabled");
            }
        }).trigger("change");       
    ');
?>