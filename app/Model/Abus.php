<?php
App::uses('AppModel', 'Model');
/**
 * Abus Model
 *
 */
class Abus extends AppModel {


	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'dependent' => false
        ),
        'Question' => array(
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'dependent' => false
        ),
        // 'Warranty' => array(
        //     'className' => 'Warranty',
        //     'foreignKey' => 'category_id',
        //     'dependent' => false
        // )
    );

}
