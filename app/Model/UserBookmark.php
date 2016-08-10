<?php
App::uses('AppModel', 'Model');
/**
 * UserBookmark Model
 *
 */
class UserBookmark extends AppModel {

    public $belongsTo = array(
        'Question' => array(
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'counterCache' => true,
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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
    );
	

}
