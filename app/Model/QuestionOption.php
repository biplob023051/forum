<?php
App::uses('AppModel', 'Model');
/**
 * Maker Model
 *
 */
class QuestionOption extends AppModel {


/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'text';

    public $belongsTo = array(
        'Question' => array(
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'conditions' => array('Question.type !=' => null),
            'dependent' => false
        )
    );

    public $hasMany = array(
        'Vote' => array(
            'className' => 'Vote',
            'foreignKey' => 'question_option_id',
            'dependent' => false
        ),
    );

    public $validate = array(
        'text' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Option name is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        ),
        'question_id' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Question id is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        )
    );
	
}
