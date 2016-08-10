<?php
App::uses('AppModel', 'Model');
/**
 * Answer Model
 *
 */
class Answer extends AppModel {

    public $belongsTo = array(
        'Question' => array(
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'counterCache' => true,
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

	public $hasMany = array(
        'Like' => array(
            'className' => 'Like',
            'foreignKey' => 'answer_id',
            //'conditions' => array('Like.type' => null),
            'dependent' => true
        ),
        // 'DisLike' => array(
        //     'className' => 'Like',
        //     'foreignKey' => 'answer_id',
        //     'conditions' => array('Like.type !=' => null),
        //     'dependent' => true
        // ),
        'Reply' => array(
            'className' => 'Reply',
            'foreignKey' => 'answer_id',
            //'conditions' => array('Like.type' => null),
            'dependent' => true
        ),
    );

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
        'body' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'body is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        )
    );
	

}
