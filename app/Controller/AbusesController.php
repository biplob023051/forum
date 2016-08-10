<?php
App::uses('AppController', 'Controller');
/**
 * Abuses Controller
 *
 * @property Abuse $Abuse
 * @property PaginatorComponent $Paginator
 */
class AbusesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	
	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('backoffice_index', 'backoffice_delete');
	}

	/**
	 *	method for category list from backoffice
	 */
	public function backoffice_index() {
		$this->layout = 'member';
		$this->set('title_for_layout',__('Reported Abuse List'));
	
		$this->paginate = array(
			'limit' => 10,
			'conditions' => array(
				'Abus.is_del' => NULL,
			),
			'contain'=>array('Question', 'User')
		);
		// pr($this->Paginator->paginate());
		// exit;
		try {
			$this->set('abuses', $this->Paginator->paginate());
		} catch (NotFoundException $e) { 
			// when pagination error found redirect to first page e.g. paging page not found
			return $this->redirect(array('action' => 'index'));
		}
	}

	

	/**
	* method for category delete
	*/
	public function backoffice_delete($abus_id) {
		$this->autoRender=false;
		
		$conditions = array(
			'MD5(Abus.id)' => $abus_id,
			'Abus.is_del'=>NULL,
		);
		
		if ($this->Abus->hasAny($conditions)){
			$this->Abus->updateAll(
				array(
					'Abus.is_del' =>1
				),
				$conditions
			);
			$this->Abus->afterSave(false);
			$this->Session->setFlash(__('Abuse report has been deleted!'),'success');
		} else {
			$this->Session->setFlash(__('Abuse cant be deleted'),'danger');
		}		
			
		if(isset($this->params['url']['redirect_url'])){			
			return $this->redirect(urldecode($this->params['url']['redirect_url']));
		} else {
			return $this->redirect(array('action' => 'index'));
		}
		
	}
	
}
