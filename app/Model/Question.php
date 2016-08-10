<?php
App::uses('AppModel', 'Model');
/**
 * Maker Model
 *
 */
class Question extends AppModel {


/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

    public $order = array('Question.id' => 'DESC');

    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id',
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        )
    );

	public $hasMany = array(
        'QuestionOption' => array(
            'className' => 'QuestionOption',
            'foreignKey' => 'question_id',
            'dependent' => true
        ),
        'Vote' => array(
            'className' => 'Vote',
            'foreignKey' => 'question_id',
            'dependent' => true
        ),
        'Answer' => array(
            'className' => 'Answer',
            'foreignKey' => 'question_id',
            'dependent' => true
        ),
        'UserBookmark' => array(
            'className' => 'UserBookmark',
            'foreignKey' => 'question_id',
            'dependent' => true
        ),
        'UserInterest' => array(
            'className' => 'UserInterest',
            'foreignKey' => 'question_id',
            'dependent' => true
        ),
        'QuestionPhoto' => array(
            'className' => 'QuestionPhoto',
            'foreignKey' => 'question_id',
            'dependent' => true
        ),
    );

    public $validate = array(
        'title' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Question title is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        ),
        'category_id' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Category is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        )
    );
	

}
