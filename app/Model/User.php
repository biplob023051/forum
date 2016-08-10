<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
/**
 * User Model
 *
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $virtualFields = array(
	    //'full_name' => 'CONCAT(User.first_name, " ", User.last_name)'
	);
	
	//public $actsAs = array('Acl' => array('type' => 'requester'));
	
	public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
		)
	);
	
	public $hasMany = array(
		
	);
	
	public $hasOne = array(
		
	);
	
	public $hasAndBelongsToMany = array();
	
	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email',false),
				'message' => 'Invalid Email Address',
				'allowEmpty' => false,
				'required' => false,
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Already Used Email Address',
				'required' =>  false,
				'allowEmpty'=> false,
			),
		),
		'currentPassword' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Current password required',
				'required' =>  false,
				'allowEmpty'=> false,
			),
			'identical' => array(
				'rule' => array('matchCurrentPassword'),
				'message' => 'Current password required',
				'required' =>  false,
				'allowEmpty'=> false,
			)
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Password is required',
				'required' =>  false,
				'allowEmpty'=> false,
			),
			'alphaNumeric' => array(
				'rule' => array('custom','/^\S{1,100}$/'),
				'message' => 'Space is not allowed',
				'required' =>  false,
				'allowEmpty'=> false,
			),
			'minLength' => array(
				'rule' => array('minLength', '6'),
				'message' => 'Password minimum 6 characters is required',
				'required' =>  false,
				'allowEmpty'=> false,
			),
		),
		'passwordVerify' => array(
			'identical' => array(
				'rule' => array('matchIdentical','password'),
				'message' => 'Password and confirm password must be same',
				'required' =>  false,
				'allowEmpty'=> false,
			)
		),
		'name' => array(
			'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'Name is required',
                'allowEmpty' => false,
                'required'   => false,
            )
		),
        'district' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'District is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        ),
        'state' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'State is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        ),
        'role_id' => array(
            'notempty' => array(
                'rule'       => 'notEmpty',
                'message'    => 'User role is required',
                'allowEmpty' => false,
                'required'   => false,
            )
        )
	);
	
	/* function for checking currentPassword fields */
	function matchCurrentPassword($checkField) {
		$value = array_values($checkField);
		$value = $value[0];

		$params = array();
		$params ['conditions']= array();
		$params ['conditions']['User.id']= AuthComponent::user('id');
		$user=$this->find('first',$params);

		return ($user['User']['password'] == $value);
	}
	
	/* function for checking match value of two fields */
	function matchIdentical($checkField,$compareField) {
		$value = array_values($checkField);
		$value = $value[0];
		return ($this->data[$this->alias][$compareField] == $value);
	}

	public function beforeSave($options = array()) {
        if (!empty($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = $this->encryptPassword($this->data[$this->alias]['password']);
        } else {
            unset($this->data[$this->alias]['password']);
        }
        return true;
    }

    public function encryptPassword($password) {
        $passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
        return $passwordHasher->hash($password);
    }
	
	public function parentNode() {
		
		if (!$this->id && empty($this->request->data)) {
			return null;
		}
		if (isset($this->request->data[$this->alias]['role_id'])) {
			$role = $this->request->data[$this->alias]['role_id'];
		}
		else{
			$role = $this->field('role_id');
		}
		if (!$role) {
			return null;
		} else {
			return array('Role' => array('id' => $role));
		}
	}
	
	public function bindNode($user) {
		return array('model' => 'Role', 'foreign_key' => $user[$this->alias]['role_id']);
	}

	public function client_list($q) {
		$clients = $this->find('all', array(
			'conditions' => array(
				$this->alias . '.role_id !=' => Configure::read('Role.Backoffice.SuperAdministrator'),
				$this->alias . '.isactive' => 1, 
				$this->alias . '.name LIKE "' . $q . '%"'
			),
			'fields' => array($this->alias . '.id', $this->alias . '.name')
		));
		$client_options = array();
		foreach ($clients as $key => $client) {
			$client_options[] = array('id' => $client['User']['id'], 'name' => $client['User']['name']);
		}
		return $client_options;
	}

}
