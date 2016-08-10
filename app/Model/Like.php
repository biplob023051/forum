<?php
App::uses('AppModel', 'Model');
/**
 * Like Model
 *
 */
class Like extends AppModel {

	public $belongsTo = array(
        'Answer' => array(
            'className' => 'Answer',
            'foreignKey' => 'answer_id',
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
        'answer_id' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Answer is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        ),
    );
	

}
