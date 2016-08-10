<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Recaptcha.Recaptcha');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('member_reset_password', 'member_forgot_password', 'backoffice_members', 'backoffice_member_delete', 'backoffice_member_insert', 'backoffice_index', 'backoffice_delete', 'backoffice_insert', 'member_signup_success',  'member_home', 'member_login', 'member_signup', 'member_logout', 'member_activation', 'backoffice_admins');
	}

	/*	
	 *	member forgot password method
	 */
	public function member_forgot_password() {
		$this->layout = 'login';
		$this->set('title_for_layout',__('Forget Password'));
				
		if ($this->request->is('post')){
			
			$user=$this->User->find('first',array(
				'conditions' => array(
					'User.email' => $this->request->data['User']['email']
				)
			));
			if($user){
				$this->request->data['User']['id']=$user['User']['id'];				
				$resetpassword=$this->User->randText();
				$this->request->data['User']['resetpassword']=$resetpassword;
				
				$this->User->save($this->request->data);

				// Send an email to user for reset password
				$this->User->sendEmail($this->request->data['User']['email'], __('Your password reset request for FORUM'), 'user/forgot_password', array(
					'resetpassword' => $resetpassword,
					'user_id' => $user['User']['id'],
					'name' => $user['User']['name'],
					'email' => $this->request->data['User']['email']
				));

				$this->Session->setFlash(__('Password reminder email sent, please check your email.'),'success');
				$this->redirect(array('controller'=>'users','action'=>'home','member'=>true));
			}else {
				$this->Session->setFlash(__('Member not found'),'danger');
			}
		}
	}
		
	/*
	 *	user reset password method
	 */
	public function member_reset_password($id=NULL,$resetpassword=NULL){
		$this->layout = 'login';
		$this->set('title_for_layout',__('Reset Password'));
		
		$reset_done=false;
		
		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $id, 
				'User.resetpassword' => $resetpassword
				)
			)
		);

		if (empty($user)) {
			throw new NotFoundException(__('Password reset link expired'));
		}
							
		if ($this->request->is(array('post', 'put'))){
			$this->request->data['User']['resetpassword']=NULL;
			
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Password has been updated successfully.'),'success');
				$reset_done=true;
				return $this->redirect(array('controller'=>'users','action'=>'home','member'=>true));
			} else {
				$this->Session->setFlash(__('Password update failed, please contact with BSK admin'),'danger');
			}
		} else {
			$this->request->data=$user;
			$this->request->data['User']['password'] = NULL;
		}
		$this->set(compact('reset_done'));		
		
	}

	/**
	 *	method for all member list from backoffice
	 */
	public function backoffice_members() {
		$this->layout = 'member';
		$this->set('title_for_layout',__('Sewadhari List'));
		
		if ($this->Auth->user('role_id') != Configure::read('Role.Backoffice.SuperAdministrator')) {
			$this->Session->setFlash($this->Auth->authError,'danger');			
			return $this->redirect(array('controller' => 'admins', 'action' => 'home'));	
		}

		$this->paginate = array(
			'limit' => 10,
			'conditions' => array(
				'User.id !=' => $this->Auth->user('id'),
				'User.role_id' => Configure::read('Role.Member'),
				'User.isactive' => 1
			),
			'order' => array(
				'User.name' => 'ASC',
			),
			'contain'=>array()
		);
		
		try {
			$this->set('users', $this->Paginator->paginate());
		} catch (NotFoundException $e) { 
			// when pagination error found redirect to first page e.g. paging page not found
			return $this->redirect(array('action' => 'home'));
		}
	}

	/**
	 *	method for member create/edit from backoffice
	 */
	public function backoffice_member_insert($user_id = null) {
		$this->layout = 'member';

		if ($this->Auth->user('role_id') != Configure::read('Role.Backoffice.SuperAdministrator')) {
			$this->Session->setFlash($this->Auth->authError,'danger');			
			return $this->redirect(array('controller' => 'admins', 'action' => 'home'));	
		}

		// if user_id then, admin edit
		if(empty($user_id)){
			$this->set('title_for_layout',__('New Sewadhari'));
		} else {
			$this->set('title_for_layout',__('Edit Sewadhari'));
		}

		$this->set('statesOptions', $this->User->statesOptions);
		
		if ($this->request->is(array('post','put'))) {

			// pr($this->request->data);
			// exit;

			if(empty($user_id)){
				$this->request->data['User']['role_id'] = Configure::read('Role.Member.Admin');
				$this->request->data['User']['isactive'] = 1;
			}
			
			// Auth user can't edit his own profile from here
			if (!empty($user_id) && ($this->request->data['User']['id'] == $this->Auth->user('id'))) {
				$this->Session->setFlash($this->Auth->authError,'danger');
				$this->redirect($this->referer());
			}

			// if password field change request not found during admin edit
			if (empty($this->request->data['User']['password']) && !empty($user_id)) {
				unset($this->request->data['User']['password']);
				unset($this->request->data['User']['passwordVerify']);
			}
			// pr($this->request->data);
			// exit;
			// save admin data
			if ($this->User->saveAll($this->request->data)) {

				$this->Session->setFlash(__('Sewadhari Saved Success'),'success');			
				return $this->redirect(array('action' => 'members'));
			} else {
				$this->Session->setFlash(__('Sewadhari saved failed'),'danger');
			}
		} elseif(!empty($user_id)) {
			// if admin edit, set edit admin data
			$conditions = array(
				'User.id' => $user_id
			);
			
			if ($this->User->hasAny($conditions)){				
				$options = array(
					'conditions'=>$conditions
				);
				$this->request->data=$this->User->find('first',$options);
				$this->request->data['User']['password'] = null;
			} else {
				$this->Session->setFlash(__('Sewadhari not found'),'danger');
				$this->redirect($this->referer());
			}
		}
	}

	/**
	 *	method for member soft delete from backoffice
	 */
	public function backoffice_member_delete($user_id) {

		if ($this->Auth->user('role_id') != Configure::read('Role.Backoffice.SuperAdministrator')) {
			$this->Session->setFlash($this->Auth->authError,'danger');			
			return $this->redirect(array('controller' => 'admins', 'action' => 'home'));	
		}
		
		$this->autoRender=false;
		
		$conditions = array(
			'User.id' => $user_id,
		);
		
		if ($this->User->hasAny($conditions)){
			$this->User->updateAll(
				array(
					'User.isactive' =>NULL
				),
				$conditions
			);
			$this->Session->setFlash(__('Sewadhari successfully deleted'),'success');
		} else {
			$this->Session->setFlash(__('Sewadhari not exist'),'danger');
		}		
			
		return $this->redirect(array('action' => 'members'));
		
	}

	/**
	 *	method for all admin list from backoffice
	 */
	public function backoffice_index() {
		$this->layout = 'member';
		$this->set('title_for_layout',__('Members List'));
		
		if ($this->Auth->user('role_id') != Configure::read('Role.Backoffice.SuperAdministrator')) {
			$this->Session->setFlash($this->Auth->authError,'danger');			
			return $this->redirect(array('controller' => 'admins', 'action' => 'home'));	
		}

		$this->paginate = array(
			'limit' => 10,
			'conditions' => array(
				'User.id !=' => $this->Auth->user('id'),
				'User.role_id' => Configure::read('Role.Member'),
				'User.isactive' => 1
			),
			'order' => array(
				'User.name' => 'ASC',
			),
			'contain'=>array()
		);
		
		try {
			$this->set('admins', $this->Paginator->paginate());
		} catch (NotFoundException $e) { 
			// when pagination error found redirect to first page e.g. paging page not found
			return $this->redirect(array('action' => 'home'));
		}
	}


	public function backoffice_admins() {
		if (($this->Auth->user('role_id') != Configure::read('Role.Backoffice.SuperAdministrator')) && $this->Auth->user('id') != 1) {
			$this->Session->setFlash($this->Auth->authError,'danger');			
			return $this->redirect(array('controller' => 'admins', 'action' => 'home'));	
		}
		$this->layout = 'member';
		$this->set('title_for_layout',__('Admin List'));
		
		if ($this->Auth->user('role_id') != Configure::read('Role.Backoffice.SuperAdministrator')) {
			$this->Session->setFlash($this->Auth->authError,'danger');			
			return $this->redirect(array('controller' => 'admins', 'action' => 'home'));	
		}

		$this->paginate = array(
			'limit' => 10,
			'conditions' => array(
				'User.id !=' => $this->Auth->user('id'),
				'User.role_id' => Configure::read('Role.Backoffice'),
				'User.isactive' => 1
			),
			'order' => array(
				'User.name' => 'ASC',
			),
			'contain'=>array()
		);
		// pr($this->paginate);
		// pr($this->Paginator->paginate());
		// exit;
		try {
			$this->set('admins', $this->Paginator->paginate());
		} catch (NotFoundException $e) { 
			// when pagination error found redirect to first page e.g. paging page not found
			return $this->redirect(array('action' => 'home'));
		}
	}

	/**
	 *	method for admin create/edit from backoffice
	 */
	public function backoffice_insert($admin_id = null) {
		$this->layout = 'member';

		if ($this->Auth->user('role_id') != Configure::read('Role.Backoffice.SuperAdministrator')) {
			$this->Session->setFlash($this->Auth->authError,'danger');			
			return $this->redirect(array('controller' => 'admins', 'action' => 'home'));	
		}

		// if admin_id then, admin edit
		if(empty($admin_id)){
			$this->set('title_for_layout',__('New Member'));
		} else {
			$this->set('title_for_layout',__('Edit Member'));
		}
		
		if ($this->request->is(array('post','put'))) {

			// pr($this->request->data);
			// exit;

			if(empty($admin_id)){
				$this->request->data['User']['role_id'] = Configure::read('Role.Backoffice.Administrator');
				$this->request->data['User']['isactive'] = 1;
			}
			
			// Auth user can't edit his own profile from here
			if (!empty($admin_id) && ($this->request->data['User']['id'] == $this->Auth->user('id'))) {
				$this->Session->setFlash($this->Auth->authError,'danger');
				$this->redirect($this->referer());
			}

			// if password field change request not found during admin edit
			if (empty($this->request->data['User']['password']) && !empty($admin_id)) {
				unset($this->request->data['User']['password']);
				unset($this->request->data['User']['passwordVerify']);
			}
			// save admin data
			if ($this->User->saveAll($this->request->data)) {

				$this->Session->setFlash(__('Member Saved Success'),'success');			
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Member saved failed'),'danger');
			}
		} elseif(!empty($admin_id)) {
			// if admin edit, set edit admin data
			$conditions = array(
				'User.id' => $admin_id
			);
			
			if ($this->User->hasAny($conditions)){				
				$options = array(
					'conditions'=>$conditions
				);
				$this->request->data=$this->User->find('first',$options);
				$this->request->data['User']['password'] = null;
			} else {
				$this->Session->setFlash(__('Member not found'),'danger');
				$this->redirect($this->referer());
			}
		}
	}

	/**
	 *	method for admin soft delete from backoffice
	 */
	public function backoffice_delete($user_id) {

		$this->autoRender=false;
		
		$conditions = array(
			'User.id' => $user_id,
		);

		$user = $this->User->find('first', array(
			'conditions' => $conditions,
			'fields' => array('User.name', 'User.email')
		));
		
		if (!empty($user)) {
			$this->User->updateAll(
				array(
					'User.isactive' =>NULL
				),
				$conditions
			);

			$this->User->sendEmail(array('biplob.weblancer@gmail.com', 'jatingupta69@gmail.com'), __('User has been deleted'), 'member/member_delete', array(
				'user' => $user
			));

			$this->Session->setFlash(__('Member successfully deleted'),'success');
		} else {
			$this->Session->setFlash(__('Member not exist'),'danger');
		}		
			
		return $this->redirect(array('action' => 'index'));
		
	}

	public function member_signup_success($id = null) {
		$this->layout = 'member';
		if (empty($id)) {
			$this->redirect($this->referer());
		}
		$this->set('title_for_layout', __('Your account created'));
		$message = __('Registration success, please check email to activate your account');
		$this->set(compact('message'));
	}
	
	/*	
	 *	Member sign up
	 */
	public function member_signup() {
		$this->layout = 'member';
		$this->set('title_for_layout', __('Member Registration'));
		$this->set('statesOptions', $this->User->statesOptions);
		if ($this->request->is(array('post','put'))) {
			if ($this->Recaptcha->verify()) {
		        // do something, save you data, login, whatever
		        // check if deleted email
				$deleted = $this->User->find('first', array(
					'conditions' => array(
						'User.email' => $this->request->data['User']['email'],
						'User.isactive' => 0,
						'User.activation' => NULL
					),
					'fields' => array('User.id')
				));
				if (!empty($deleted)) { 
					$this->Session->setFlash(__('This email address has been deactivated by admin'), 'danger');
					$this->redirect($this->referer());
				}
				$role_type = Configure::read('Role.Member');
				$this->request->data['User']['role_id'] = $role_type['Admin'];
				$this->request->data['User']['activation'] = $this->User->randText(16);
				if ($this->User->saveAll($this->request->data)) {
					$this->User->sendEmail($this->request->data['User']['email'], __('Please confirm your registration'), 'member/member_signup', array(
						'activation' => $this->request->data['User']['activation'],
						'name' => $this->request->data['User']['name'],
						'user_id' => $this->User->id
					));
					
					//$this->Session->setFlash(__('Registration success, please check email to activate your account'),'success');
					$this->redirect(array('controller' => 'users', 'action' => 'signup_success', $this->User->id, 'member' => true));
				} else {
					$this->Session->setFlash(__('Registration failed'),'danger');
				}
		    } else {
		        // display the raw API error
		        $this->Session->setFlash($this->Recaptcha->error);
		    }
			
		}
	}


	/*	
	 *	Member home method
	 *	if logged in display member home 
	 *	otherwise display login form
	 */
	public function member_home() {
		if ($this->Auth->user() && in_array($this->Auth->user('role_id'), Configure::read('Role.Member'))){ 			
			$this->layout = 'member';
			$this->set('title_for_layout', __('My Profile'));
			if ($this->request->is(array('post','put'))) {
				if ($this->request->data['User']['id'] != $this->Auth->user('id')) {
					$this->Session->setFlash(__('Save failed'),'danger');
					$this->redirect($this->referer());
				}

				// pr($this->request->data);
				// exit;

				if (!empty($this->request->data['Crop']['x']) && 
					!empty($this->request->data['Crop']['y']) && 
					!empty($this->request->data['Crop']['w']) && 
					!empty($this->request->data['Crop']['h']) && 
					!empty($this->request->data['User']['avatar'])) {
					$crop = $this->request->data['Crop'];
					$path = WWW_ROOT . 'uploads' . DS . 'avatars';
					$avatar_thumb_loc = $path . DS . 't_' . $this->request->data['User']['avatar'];
					App::import('Vendor', 'phpThumb', array('file' => 'phpThumb/ThumbLib.inc.php'));
					$avatar_thumb = PhpThumbFactory::create($path . DS . $this->request->data['User']['avatar'], array('jpegQuality' => 90));
					$avatar_thumb->crop($crop['x'], $crop['y'], $crop['w'], $crop['h'])->resize(AVATAR_THUMB_WIDTH, AVATAR_THUMB_HEIGHT)->save($avatar_thumb_loc);
					unset($this->request->data['Crop']);
				}

				// pr($this->request->data);
				// exit;

				if ($this->User->saveAll($this->request->data)) {
					$this->Session->setFlash(__('Your profile updated successfully'),'success');
					$this->redirect($this->referer());
				} else {
					$this->Session->setFlash(__('Save failed'),'danger');
				}
			} else {
				$this->request->data = $this->User->findById($this->Auth->user('id'));
			}
		} else { 
			//force user to login
			$this->autoRender=false;
			$this->member_login();
			$this->render("member_login");
		}	
	}

	/*	
	 *	User login method
	 */	
	public function member_login() {
		$this->layout = 'login';
		$this->set('title_for_layout',__('Member Login'));

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
	 *	user logout method
	 */
	public function member_logout() {
		$this->Session->destroy(); 		
		$this->redirect($this->Auth->logout());
    }

	
	/**
	 *	method for user activation and auto login by clicking activation link from email
	 */
	public function member_activation($user_id = null, $code = null) {
		$this->autoRender = false;

		// find user for activated by activation code
		$user = $this->User->findByActivation($code);

		if (!empty($user)) {
			$this->User->id = $user['User']['id'];
			// save remove activation code after success
			$this->User->save(array('activation' => null, 'activationtime' => $this->User->getCurrentDateTime(), 'isactive' => 1), $validate = false);
			$this->Session->setFlash(__('Hi') . ' ' . $user['User']['name'] . ', ' . __('Email confirmatin success, you can login now'),'success');
		} else {
			$user = $this->User->findById($user_id);
			if (!empty($user) && !empty($user['User']['isactive'])) {
				$this->Session->setFlash(__('This account has been activated before.'), 'warning');
			} else {
				$this->Session->setFlash(__('This account has been deleted by admin'), 'danger');
			}
		}
		$this->redirect(array('controller' => 'users', 'action' => 'login', 'member' => true));
	}
	
	/**
	 *	method for user active/deactive from backoffice
	 */
	public function member_active($user_id,$active=NULL) {
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
	public function member_build_ARO() {
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
		// Build the users.
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
		$this->redirect(Router::url('/member',true));
		
	}
	
	/* set access control list depends on user role */
	public function member_set_ACL() {
		$this->autoRender=false;
		$role = $this->User->Role;
		
		//Customer
		$role->id = Configure::read('Role.Member.Admin');
		$this->Acl->deny($role, 'controllers');
		$this->Acl->allow($role, 'users/edit_profile');
		$this->Acl->allow($role, 'users/change_password');

		$this->Session->setFlash(__('SET_ACL_SUCCESS'),'success');
		$this->redirect(Router::url('/member',true));
	}

	
	
}
