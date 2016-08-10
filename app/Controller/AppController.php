<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	// define common commponents for all controllers
	public $components = array(
 		'Acl',
		'Auth' => array(
			'authorize' => array('Controller'),
			'flash' => array(
				'element' => 'alert',
				'key' => 'auth',
				'params' => array(
					'plugin' => 'BoostCake',
					'class' => 'alert-danger'
				)
			)
		),
        'Session',
		'Cookie',
		'RequestHandler'		
    );

    //define common helper for all controllers	    
	public $helpers = array(
        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
		'Form' => array('className' => 'BoostCake.BoostCakeForm'),
		'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
        'Session',
        'Cache',		
		'Text',
		'Js'=> array('Jquery'),
		'Time',
		'Forum'
    );
	
	public $uses = array('User');
	
	// global variable for all roles menu options
	// which use in users entry form
	public $roleMenuOptions;	
	
	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->RequestHandler->isMobile()) {
			$this->redirect('http://m.forum.subjy.com/');
		}
		$this->set('title_for_layout','');

		// read and intialize role configuration from database
		App::import('model','Role');
		$roleModel  = new Role();
        $roles=$roleModel->find('all',array('order'=>array('created'=>'desc')));
		if($roles){
			foreach($roles as $role){
				Configure::write('Role.'.$role['Role']['role_type'].'.'.$role['Role']['role_key'],$role['Role']['id']);
			}
		}
		
		//default cookie seetings
		$this->Cookie->name = 'DIWALI';
        $this->Cookie->time = 3600;  // or '1 hour'
        $this->Cookie->path = '/';
        $this->Cookie->domain = false;
        $this->Cookie->secure = false;
        $this->Cookie->httpOnly = true;
		
		//default authentication settings		
		$this->Auth->loginError =  __('LOGIN ID PASSWORD MISMATCH');
		$this->Auth->authError = __('NO AUTHORIZATION');
		
		//change authentication settings depends on routing prefix
		if($this->params['prefix']=='backoffice') { 
			// backoffice authentication
			$this->Auth->authenticate = array(
				AuthComponent::ALL => array(
					'userModel' => 'User',
					'scope' =>  array(
						'User.isactive' => 1,
						'User.activation' => null,
						'User.role_id' => Configure::read('Role.Backoffice')
					),
					'fields' => array('username' => 'email'),
					'passwordHasher' => array(
                        'className' => 'Simple',
                        'hashType' => 'sha256'
                    )
				), 
				'Basic',
				'Form'
			);
			$this->Auth->loginAction = array('controller' => 'admins', 'action' => 'home', 'backoffice'=>true);
			$this->Auth->loginRedirect = Router::url('/');
			//$this->Auth->loginRedirect = 'http://bskapps.com/forum/';
			$this->Auth->logoutAction = array('controller' => 'admins', 'action' => 'logout', 'backoffice'=>true);
			$this->Auth->logoutRedirect = Router::url('/');
			//$this->Auth->logoutRedirect = 'http://bskapps.com/forum/';
			$this->Auth->unauthorizedRedirect= array('controller' => 'admins', 'action' => 'home', 'backoffice'=>true);
				
		} elseif($this->params['prefix']=='member') { 
			// member authentication
			$this->Auth->authenticate = array(
				AuthComponent::ALL => array(
					'userModel' => 'User',
					'scope' =>  array(
						'User.isactive' => 1,
						'User.activation' => null,
						'User.role_id' => Configure::read('Role.Member')
					),
					'fields' => array('username' => 'email'),
					'passwordHasher' => array(
                        'className' => 'Simple',
                        'hashType' => 'sha256'
                    )
				), 
				'Basic',
				'Form'
			);
			$this->Auth->loginAction = array('controller' => 'users', 'action' => 'home', 'member'=>true);
			$this->Auth->loginRedirect = Router::url('/');
			//$this->Auth->loginRedirect = 'http://bskapps.com/forum/';
			$this->Auth->logoutAction = array('controller' => 'users', 'action' => 'logout', 'member'=>true);
			$this->Auth->logoutRedirect = Router::url('/');
			//$this->Auth->logoutRedirect = 'http://bskapps.com/forum/';
			$this->Auth->unauthorizedRedirect= array('controller' => 'users', 'action' => 'home', 'member'=>true);
		} else {
			
		}

		// categories
		$this->loadModel('Category');
		$firstChild = $this->Category->find('all', array(
    		'conditions' => array(
    			'Category.isactive' => 1,
    			'Category.parent_id' => NULL
    		)
    	));
    	$this->set(compact('firstChild'));		
	}

	protected function _jsonError($msg) {
        $this->autoRender = false;
        $response['status'] = 0;
        $response['message'] = $msg;
        echo json_encode($response);
        exit;
    }
	
	public function beforeRender() {
		parent::beforeRender();
		//change error page layout
		if($this->name == 'CakeError') {
			$this->layout = 'error';
		}

	    //session flash message override
	    if ($this->Session->check('Message.flash')) {
	        $flash = $this->Session->read('Message.flash');
	        $flash['params']['close']=true;
	        switch ($flash['element']) {
	        	case 'success':
	        		$flash['element'] = 'session/success';
	        		break;
	        	case 'danger':
	        		$flash['element'] = 'session/danger';
	        		break;
	        	case 'modal/success':
	        		$flash['element'] = 'session/success';
	        		$flash['params']['close'] = false;
	        		break;
	        	case 'modal/danger':
	        		$flash['element'] = 'session/danger';
	        		$flash['params']['close'] = false;
	        		break;
	        	case 'modal/default':
	        	case 'modal/info':
	        		$flash['element'] = 'session/default';
	        		$flash['params']['close'] = false;
	        		break;	        	
	        	default:
	        		$flash['element'] = 'session/default';
	        		break;
	        }
	        $this->Session->write('Message.flash', $flash);
	    }
	}

	/*
	* Get authenticate user id
	* return id
	*/
	public function getUserId() {
		$user_id = $this->Auth->user('id');
		if (empty($user_id)) {
			$this->Session->setFlash(__('Please login first to continue, thanks.'), 'danger');
			echo json_encode(array('result' => 0, 'message' => __('Please login to continue.')));
			exit;
		} else {
			return $user_id;
		}
	}

	/**
	 * Get global site settings
	 * @return array
	 */
	public function _getSettings() {
		$this->loadModel('Setting');
		$this->Setting->cacheQueries = true;
		$settings = $this->Setting->find('list', array('fields' => array('field', 'value')));
		return $settings;
	}

	/*
	* Check restricted words
	* return true false
	*/
	public function _checkRestrictedWords($data, $model) {
		$settings = $this->_getSettings();
		if (empty($settings['site_keywords'])) {
			return 0;
		} else {
			$found = false;
			$censor_words = explode(',', $settings['site_keywords']);
			switch ($model) {
				case 'Question':
					foreach ($censor_words as $key => $word) {
						if (strpos($data['Question']['title'], trim($word)) !== false) {
							$found = true;
							break;
						}
						if (!empty($data['Question']['body']) && empty($found)) {
							if (strpos($data['Question']['body'], trim($word)) !== false) {
								$found = true;
								break;
							}
						}

						if (!empty($data['QuestionOption']) && empty($found)) {
							foreach ($data['QuestionOption'] as $key => $option) {
								if (strpos($option['text'], trim($word)) !== false) {
									$found = true;
									break;
								}
							}
							if (!empty($found)) {
								break;
							}
						}
					}
					break;

				case 'Answer':
					foreach ($censor_words as $key => $word) {
						if (!empty($data['Answer']['body']) && empty($found)) {
							if (strpos($data['Answer']['body'], trim($word)) !== false) {
								$found = true;
								break;
							}
						}
					}
					break;
				
				default:
					# code...
					break;
			}
			if (!empty($found)) {
				return 3;
			} else {
				return 0;
			}
		}
	}
	
	/* check authorization of user for current page */
	public function isAuthorized($user = null) {
		$notAuthorized=false;
		
		//check deafult authorization for current page
		if($this->name != 'Pages' && !$this->Acl->check(array('model' => $this->Auth->authenticate['all']['userModel'], 'foreign_key' => $this->Auth->user('id')), $this->name . '/' . $this->params['action'])) {
			//no authorization for current page
			$notAuthorized = true;
		} elseif($this->Auth->loggedIn()) {
			//check default authorization for backoffice and member and group
			if($this->params['prefix']=='backoffice' && !in_array($this->Auth->user('role_id'), Configure::read('Role.Backoffice'))) {
				//no authorization for backoffice
				$notAuthorized = true;
			} elseif($this->params['prefix']=='member' &&  !in_array($this->Auth->user('role_id'), Configure::read('Role.Member'))) {
				//no authorization for member
				$notAuthorized = true;
			} elseif(empty($this->params['prefix']) &&  !in_array($this->Auth->user('role_id'), Configure::read('Role.Member'))) {
				//no authorization for member
				$notAuthorized = true;
			}
		}	
				
		if($notAuthorized){	
			CakeLog::write('auth', 'ACL DENY: ' . $this->Auth->user('id') . ' - ' . $this->Auth->user('username') . ' tried to access ' . $this->name . '/' . $this->params['action'] . '.');
			$this->Session->setFlash($this->Auth->authError,'danger');			
			$this->redirect($this->Auth->loginAction);		
			return false;
		}
		return true;
	}
	
}
