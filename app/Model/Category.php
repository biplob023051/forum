<?php
App::uses('AppModel', 'Model');
/**
 * Maker Model
 *
 */
class Category extends AppModel {


/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Behavior 
 *
 * @var array
 */	
	public $actsAs = array(
		'Tree'
	);

	public $hasMany = array(
        'Question' => array(
            'className' => 'Question',
            'foreignKey' => 'category_id',
            'dependent' => false
        ),
        // 'Warranty' => array(
        //     'className' => 'Warranty',
        //     'foreignKey' => 'category_id',
        //     'dependent' => false
        // )
    );

    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Category name is required',
                'allowEmpty' => false,
                'required'   => true,
            )
        )
    );
	
	// list of active category 
    public function categoryOptions() {        
        $options = array(
            'conditions' => array(
                $this->alias.'.isdel'=>NULL,
                $this->alias.'.isactive'=>true
            ),
            'order' => array(
                $this->alias.'.name'=>' ASC',
            ),
            'contain'=>array()
        );

        $results=$this->find('list',$options);
        return $results;
    }

    // list of main category
    public function mainCategoryOptions() {        
        $options = array(
            'conditions' => array(
                $this->alias.'.parent_id'=>NULL,
                $this->alias.'.isdel'=>NULL,
                $this->alias.'.isactive'=>true
            ),
            'order' => array(
                $this->alias.'.lft'=>' DESC',
                $this->alias.'.rght'=>' ASC',
            ),
            'contain'=>array()
        );

        $results=$this->find('list',$options);
        return $results;
    }

}
