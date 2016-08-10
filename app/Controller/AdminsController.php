<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class AdminsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public $uses = array('User');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->layout = 'member';
		$this->Auth->allow('backoffice_home', 'backoffice_login', 'backoffice_signup', 'backoffice_logout', 'backoffice_activation');
	}

	/*	
	 *	Backoffice home method
	 *	if logged in display backoffice home 
	 *	otherwise display login form
	 */
	public function backoffice_home() {
		if ($this->Auth->user() && in_array($this->Auth->user('role_id'), Configure::read('Role.Backoffice'))) {
			$setting = $this->_getSettings();
			if ($this->request->is(array('post','put'))) {
				App::uses('Sanitize', 'Utility');
				foreach ($this->request->data as $key => $value) {
					if (array_key_exists($key, $setting) && $setting[$key] != $value) {
						$this->Setting->updateAll(array('value' => '"' . Sanitize::escape($value) . '"'), array('field' => $key));
					}
				}
				$this->Session->setFlash('Changes have been saved', 'success');
				$this->redirect($this->request->referer());
			}
			$this->set('title_for_layout', 'System Settings');
			$this->set(compact('setting'));		
			
		} else { 
			//force user to login
			$this->autoRender=false;
			$this->backoffice_login();
			$this->render("backoffice_login");
		}	
	}

	/*	
	 *	User login method
	 */	
	public function backoffice_login() {
		$this->layout = 'login';
		$this->set('title_for_layout',__('Backoffice Login'));

		// login form submit
		if (!empty($this->request->data)) {
			App::Import('Utility', 'Validation'); // validation for the form

			if ($this->Auth->login()) {	// try to login using auth component	

				$this->Session->write('Auth.User.loginform',$this->User->getCurrentDateTime());
				$session_id=$this->Session->id();					
				
				$this->User->id = $this->Auth->user('id');
				if($this->User->exists()){	
					// update user values
					$data=array();
					$data['User']['id']=$this->Auth->user('id');
					$data['User']['lastaccess']=$this->User->getCurrentDateTime();
					$this->User->saveAll($data);						
				}
					
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Session->setFlash($this->Auth->loginError,'danger');
				$this->redirect($this->Auth->loginAction);
			}
		} 
	}

	/*	
	 *	admin logout method
	 */
	public function backoffice_logout() {
		$this->Session->destroy(); 		
		$this->redirect($this->Auth->logout());
    }

	
	/**
	 *	method for user activation and auto login by clicking activation link from email
	 */
	public function backoffice_activation($code = null) {
		$this->autoRender = false;

		// find user for activated by activation code
		$user = $this->User->findByActivation($code);

		if (!empty($user)) {
			$this->User->id = $user['User']['id'];
			// save remove activation code after success
			$this->User->save(array('activation' => null, 'activationtime' => $this->User->getCurrentDateTime(), 'isactive' => 1), $validate = false);
			$user = $user['User'];
			$this->Session->setFlash(__('Email confirmatin success, you can login now'),'success');
			$this->redirect(array('controller' => 'users', 'action' => 'login', 'backoffice' => true));
		} else {
			throw new NotFoundException(__('Activation Code Expired'));
		}
	}
	
	/**
	 *	method for user active/deactive from backoffice
	 */
	public function backoffice_active($user_id,$active=NULL) {
		$this->autoRender=false;
		
		$conditions = array(
			'MD5(User.id)' => $user_id,
			'User.isdel'=>NULL,
		);
		
		if ($this->User->hasAny($conditions)){
			$this->User->updateAll(
				array(
					'User.isactive' =>$active
				),
				$conditions
			);
			$this->User->afterSave(false);
		} else {
			$this->Session->setFlash(__('ADMIN_STATUS_CANNOT_CHANGE'),'danger');
		}			
		
		if(isset($this->params['url']['redirect_url'])){			
			return $this->redirect(urldecode($this->params['url']['redirect_url']));
		} else {
			return $this->redirect(array('action' => 'index'));
		}
		
	}

	/* rebuilt aros table method */
	public function backoffice_build_ARO() {
		$this->autoRender=false;
		// Build the groups.
		$roles = $this->User->Role->find('all');
		$aro = new Aro();
		$aro->query("TRUNCATE TABLE `". $aro->tablePrefix . $aro->useTable . "`");

		$aros=array();
		foreach($roles as $role) {
			$aro->create();
			$aro->save(array(
				'foreign_key' => $role['Role']['id'],
				'model'=>'Role',
				'parent_id' => NULL
			));
			$aros[$role['Role']['id']]=$aro->id;
		}
		
		$i=0;
		$aroList=array();
		// Build the merchants.
		$users = $this->User->find('all');
		foreach($users as $user) {
			if(isset($aros[$user['User']['role_id']])){
				$aroList[$i++]= array(
				'foreign_key' => $user['User']['id'],
				'model' => 'User',
				'parent_id' => $aros[$user['User']['role_id']]
				);	
			}
		}
		
		foreach($aroList as $data) {
			$aro->create();
			$aro->save($data);
		}
		
		$this->Session->setFlash(__('ARO_BUILD_SUCCESS'),'success');
		$this->redirect(Router::url('/backoffice',true));
		
	}
	
	/* set access control list depends on user role */
	public function backoffice_set_ACL() {
		$this->autoRender=false;
		$role = $this->User->Role;
		
		//Customer
		$role->id = Configure::read('Role.Backoffice.User');
		$this->Acl->deny($role, 'controllers');
		$this->Acl->allow($role, 'users/edit_profile');
		$this->Acl->allow($role, 'users/change_password');

		$this->Session->setFlash(__('SET_ACL_SUCCESS'),'success');
		$this->redirect(Router::url('/backoffice',true));
	}
	
}
