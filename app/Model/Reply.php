<?php
App::uses('AppModel', 'Model');
/**
 * Reply Model
 *
 */
class Reply extends AppModel {

    public $belongsTo = array(
        'Answer' => array(
            'className' => 'Answer',
            'foreignKey' => 'answer_id',
        )
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
        'answer_id' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Answer is required',
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
