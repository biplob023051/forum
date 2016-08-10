<?php
App::uses('AppModel', 'Model');
/**
 * Vote Model
 *
 */
class Vote extends AppModel {

    public $belongsTo = array(
        'Question' => array(
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'counterCache' => true,
        ),
        'QuestionOption' => array(
            'className' => 'QuestionOption',
            'foreignKey' => 'question_option_id',
            'counterCache' => true,
        )
    );

	// public $hasMany = array(
 //        'QuestionOption' => array(
 //            'className' => 'QuestionOption',
 //            'foreignKey' => 'question_id',
 //            'dependent' => false
 //        ),
 //    );

    public $validate = array(
        'user_id' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'User is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        ),
        'question_id' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Question is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        ),
        'question_option_id' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'QuestionOption is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        )
    );
	

}
