<?php
App::uses('AppController', 'Controller');
/**
 * Categories Controller
 *
 * @property Category $Category
 * @property PaginatorComponent $Paginator
 */
class CategoriesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	
	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('ajax_categories', 'backoffice_index', 'backoffice_insert', 'backoffice_delete', 'backoffice_moveup', 'backoffice_movedown', 'backoffice_active');
	}

	/**
	 *	method for category list for frontend pop up
	 */
	public function ajax_categories() {
		$this->layout = 'ajax';
		$categories = $this->Category->find('threaded', array(
			'conditions' => array(
				'Category.isactive' => 1
			),
			'fields' => array('Category.id', 'Category.name', 'Category.parent_id')
		));
		$this->set(compact('categories'));
	}

	/**
	 *	method for category list from backoffice
	 */
	public function backoffice_index($parent_id = null) {
		$this->layout = 'member';
		$this->set('title_for_layout',__('Category List'));
		if (empty($parent_id)) {
			$parent_id = NULL;
		} else {
			$category = $this->Category->find('first', array(
				'conditions' => array(
					'Category.isdel'=>NULL,
					'MD5(Category.id)' => $parent_id
				)
			));
			if (empty($category)) {
				$this->redirect('index');
			}
		
			$parents = $this->Category->getPath($category['Category']['id']);
			$this->set(compact('parents'));
		}
		$this->paginate = array(
			'limit' => 10,
			'conditions' => array(
				'Category.isdel'=>NULL,
				'MD5(Category.parent_id)' => $parent_id
			),
			'order' => array(
				'Category.lft'=>' DESC',
				'Category.rght'=>' ASC',
			),
			'contain'=>array()
		);
		
		try {
			$this->set('categories', $this->Paginator->paginate());
		} catch (NotFoundException $e) { 
			// when pagination error found redirect to first page e.g. paging page not found
			return $this->redirect(array('action' => 'index'));
		}
	}

	/**
	* method for category insert
	*/
	public function backoffice_insert($category_id = null) {
		$this->layout = 'member';
		if(empty($category_id)){
			$this->set('title_for_layout',__('New Category'));
		} else {
			$this->set('title_for_layout',__('Edit Category'));
		}

		$treelist = $this->Category->generateTreeList();
		$this->set(compact('treelist'));

		// if form submit to add or edit a category
		if ($this->request->is(array('post','put'))) {
			if ($this->Category->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Category_SAVE_SUCCESS'),'success');			
				if(isset($this->params['url']['redirect_url'])){			
					return $this->redirect(urldecode($this->params['url']['redirect_url']));
				} else {
					return $this->redirect(array('action' => 'index'));
				}
			} else {
				$this->Session->setFlash(__('Category_SAVE_FAILED'),'danger');
			}
		} elseif(!empty($category_id)) {
			$conditions = array(
				'MD5(Category.id)' => $category_id,
				'Category.isdel'=>NULL,
			);
			
			if ($this->Category->hasAny($conditions)){				
				$options = array(
					'conditions'=>$conditions
				);
				$this->request->data=$this->Category->find('first',$options);
			} else {
				$this->Session->setFlash(__('Category_NOT_FOUND'),'danger');
				$this->redirect($this->referer());
			}
		}
	}

	/**
	* method for category delete
	*/
	public function backoffice_delete($category_id) {
		$this->autoRender=false;
		
		$conditions = array(
			'MD5(Category.id)' => $category_id,
			'Category.isdel'=>NULL,
			'Category.isactive'=>NULL,
		);
		
		if ($this->Category->hasAny($conditions)){
			$this->Category->updateAll(
				array(
					'Category.isdel' =>1
				),
				$conditions
			);
			$this->Category->afterSave(false);
		} else {
			$this->Session->setFlash(__('Category_CANNOT_DELETE'),'danger');
		}		
			
		if(isset($this->params['url']['redirect_url'])){			
			return $this->redirect(urldecode($this->params['url']['redirect_url']));
		} else {
			return $this->redirect(array('action' => 'index'));
		}
		
	}
	
	/**
	* method for category active/deactive
	*/
	public function backoffice_active($category_id,$active=NULL) {
		$this->autoRender=false;
		
		$conditions = array(
			'MD5(Category.id)' => $category_id,
			'Category.isdel'=>NULL,
		);
		
		if ($this->Category->hasAny($conditions)){
			$this->Category->updateAll(
				array(
					'Category.isactive' =>$active
				),
				$conditions
			);
			$this->Category->afterSave(false);
		} else {
			$this->Session->setFlash(__('Category_STATUS_CANNOT_CHANGE'),'danger');
		}			
		
		if(isset($this->params['url']['redirect_url'])){			
			return $this->redirect(urldecode($this->params['url']['redirect_url']));
		} else {
			return $this->redirect(array('action' => 'index'));
		}
		
	}
	
	/*
	* Method for moveup sorting
	*/
	function  backoffice_moveup($category_id) {		
		$this->autoRender=false;
		
		$conditions = array(
			'MD5(Category.id)' => $category_id,
			'Category.isdel'=>NULL,
		);
		
		if ($this->Category->hasAny($conditions)){
			$options = array(
				'conditions'=>$conditions,
				'contain'=>array()
			);
			$category=$this->Category->find('first',$options);
			if($category){
				$this->Category->id=$category['Category']['id'];
				if($this->Category->moveDown()==false)
					$this->Session->setFlash(__('Category_POSITION_CANNOT_CHANGE'),'danger');
			}	
		} else {
			$this->Session->setFlash(__('Category_POSITION_CANNOT_CHANGE'),'danger');
		}			
			
		if(isset($this->params['url']['redirect_url'])){			
			return $this->redirect(urldecode($this->params['url']['redirect_url']));
		} else {
			return $this->redirect(array('action' => 'index'));
		}	
		
	}
	
	/*
	* Method for movedown sorting
	*/
	function backoffice_movedown($category_id) {
		$conditions = array(
			'MD5(Category.id)' => $category_id,
			'Category.isdel'=>NULL,
		);
		
		if ($this->Category->hasAny($conditions)){
			$options = array(
				'conditions'=>$conditions,
				'contain'=>array()
			);
			$category=$this->Category->find('first',$options);
			if($category){
				$this->Category->id=$category['Category']['id'];
				if($this->Category->moveUp()==false)
					$this->Session->setFlash(__('Category_POSITION_CANNOT_CHANGE'),'danger');
			}	
		} else {
			$this->Session->setFlash(__('Category_POSITION_CANNOT_CHANGE'),'danger');
		}		
			
		if(isset($this->params['url']['redirect_url'])){			
			return $this->redirect(urldecode($this->params['url']['redirect_url']));
		} else {
			return $this->redirect(array('action' => 'index'));
		}
	}

}
