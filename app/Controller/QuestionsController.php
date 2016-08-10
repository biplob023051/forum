<?php
App::uses('AppController', 'Controller');
/**
 * Questions Controller
 *
 * @property Question $Question
 * @property PaginatorComponent $Paginator
 */
class QuestionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	
	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('ajax_single_question', 'ajax_answer_delete', 'ajax_question_delete', 'ajax_search', 'poll_view', 'backoffice_edit_poll', 'backoffice_question_home', 'backoffice_edit_question', 'ajax_answer_reply', 'backoffice_remove_interest', 'backoffice_polls', 'backoffice_bookmarks', 'backoffice_interests', 'backoffice_answers', 'backoffice_answer_delete', 'backoffice_remove_bookmark', 'backoffice_delete_question', 'backoffice_index', 'member_remove_bookmark', 'member_answer_delete', 'member_delete_question', 'member_interests', 'member_bookmarks', 'member_polls', 'member_answers', 'member_index', 'ajax_home', 'ajax_abuse', 'ajax_create', 'ajax_vote_cast', 'view', 'ajax_answer', 'ajax_like', 'ajax_bookmark', 'ajax_interest', 'ajax_answer_view');
	}

	/*
	* Home page questions/polls search method
	*/
	public function ajax_search() {
		$this->layout = "ajax";
		if (empty($this->request->data['page_no'])) {
			$page = 1;
		} else {
			$page = (int) $this->request->data['page_no'];
		}

		$cat_condition = array();

		if (!empty($this->request->data['keyword'])) {
			$this->Session->write('keyword', $this->request->data['keyword']);
		}

		$keyword = empty($this->request->data['keyword']) ? $this->Session->read('keyword') : $this->request->data['keyword'];

		$this->paginate = array(
			'limit' => 3,
			'conditions' => array(
				'AND' => array(
					array(
						'OR' => array(
							'Question.isactive' => array(1,2),
							'AND' => array(
								'Question.isactive' => 0,
								'Question.user_id' => $this->Auth->user('id') 
							)
						),
					),
					array(
						'OR' => array(
							"Question.title LIKE" => '%'.$keyword.'%',
							"Question.body LIKE" => '%'.$keyword.'%',
							"Question.tags LIKE" => '%'.$keyword.'%',
						),
					)
				)
				
			),
			'order' => array(
				'Question.modified' => 'desc',
			),
			'page' => $page,
			'contain' => array('QuestionOption', 'Category' => array('fields' => array('Category.id', 'Category.name')))
		);
		// pr($this->paginate);
		// exit;
		// pr($this->Paginator->paginate());
		// exit;
		try {
			$this->set('questions', $this->Paginator->paginate());
			$this->set(compact('keyword'));
		} catch (NotFoundException $e) { 
			// when pagination error found redirect to first page e.g. paging page not found
			return $this->redirect('/');
		}
	}

	/*
	* Poll view page 
	*/
	public function poll_view($poll_id = null) {
		if (empty($poll_id)) {
			return $this->redirect('/');
		}
		$poll = $this->Question->find('first', array(
			'conditions' => array(
				'MD5(Question.id)' => $poll_id
			),
			'contain' => array(
				'QuestionOption',
				'Category' => array('fields' => array('Category.id', 'Category.name'))
			)
		));
		
		if (empty($poll)) {
			return $this->redirect('/');
		}
		$parent_cats = $this->Category->getPath($poll['Category']['id']);
		$this->set(compact('poll', 'parent_cats'));
	}

	/*
	* Poll edit method for backoffice
	*/
	public function backoffice_edit_poll($poll_id = null) {
		$this->layout = 'member';
		$this->set('title_for_layout', __('Edit Poll'));
		if (empty($poll_id)) {
			$this->redirect($this->referer());
		}

		if ($this->request->is(array('post', 'put'))) {

			if ($this->Question->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Poll edited successfully'), 'success');
				$this->redirect(array('controller' => 'questions', 'action' => 'polls'));
			} else {
				$this->Session->setFlash(__('Something went wrong, try again.'), 'danger');
			}
		} else {
			$poll = $this->Question->rowFromMd5Id($poll_id, array('Question.*'), array('QuestionOption'));
		
			if (empty($poll)) {
				$this->redirect($this->referer());
			}
			$this->request->data = $poll;
			$treelist = $this->Question->Category->generateTreeList();
			$this->set(compact('treelist'));
		}
	}

	// backoffice delete question
	public function backoffice_question_home($question_id) {
		$this->autoRender = false;
		if (empty($question_id)) {
			$this->redirect($this->referer());
		}
		$question = $this->Question->rowFromMd5Id($question_id, array('Question.id', 'Question.user_id'));
		if (empty($question)) {
			$this->Session->setFlash(__('Question not found!'), 'danger');
			$this->redirect($this->referer());
		}
		$this->Question->id = $question['Question']['id'];
		if ($this->Question->saveField('modified', date("Y-m-d H:i:s"))) {
			$this->Session->setFlash(__('You have successfully display this question on home.'), 'success');
			$this->redirect($this->referer());
		} else {
			// no authorization
			$this->Session->setFlash(__('You are not authorize to display this question home.'), 'danger');
			$this->redirect('/member');
		}
	}

	/*
	* Question edit method for backoffice
	*/
	public function backoffice_edit_question($question_id = null) {
		$this->layout = 'member';
		$this->set('title_for_layout', __('Edit Question'));
		if (empty($question_id)) {
			$this->redirect($this->referer());
		}

		if ($this->request->is(array('post', 'put'))) {
			if ($this->Question->save($this->request->data)) {
				$this->Session->setFlash(__('Question edited successfully'), 'success');
				$this->redirect(array('controller' => 'questions', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('Something went wrong, try again.'), 'danger');
			}
		} else {
			$question = $this->Question->rowFromMd5Id($question_id, array('Question.*'));
			if (empty($question)) {
				$this->redirect($this->referer());
			}
			$this->request->data = $question;

			$treelist = $this->Question->Category->generateTreeList();
			$this->set(compact('treelist'));
		}
	}

	// admin reply for answer
	public function ajax_answer_reply() {
		$this->layout = 'ajax';
		$errorFound = 0;
		if ($this->Auth->user() && in_array($this->Auth->user('role_id'), Configure::read('Role.Backoffice'))) {
			$answer = $this->Question->Answer->rowFromMd5Id($this->request->data['answer_id'], array('Answer.id'));
			if (!empty($answer)) {
				$this->request->data['Reply']['answer_id'] = $answer['Answer']['id'];
				$this->request->data['Reply']['user_id'] = $this->Auth->user('id');
				$this->request->data['Reply']['body'] = $this->request->data['reply_body'];
				$reply = $this->Question->Answer->Reply->save($this->request->data);
				if (!empty($reply)) {
					$this->set('reply', $reply['Reply']);
				} else {
					$errorFound = 1;
					$this->Session->setFlash(__('Something went wrong, try again later.'), 'danger');
				}
			} else {
				$errorFound = 2;
				$this->Session->setFlash(__('Something went wrong, try again later.'), 'danger');
			}
		} else {
			$errorFound = 1;
			$this->Session->setFlash(__('You are not authorize to view this section.'), 'danger');
		}
		$this->render('/Elements/answers/answer_reply');
		$this->set(compact('errorFound'));
	}

	// backoffice remove interest
	public function backoffice_remove_interest($question_id) {
		$this->autoRender = false;
		if (empty($question_id)) {
			$this->redirect($this->referer());
		}
		$question = $this->Question->rowFromMd5Id($question_id, array('Question.id', 'Question.user_id'));
		if (empty($question)) {
			$this->Session->setFlash(__('Question not found!'), 'danger');
			$this->redirect($this->referer());
		}
		$this->Question->UserInterest->deleteAll(array(
			'UserInterest.question_id' => $question['Question']['id']
		));

		$this->Session->setFlash(__('You have successfully deleted.'), 'success');
		$this->redirect($this->referer());
	}

	// backoffice remove bookmark
	public function backoffice_remove_bookmark($question_id) {
		$this->autoRender = false;
		if (empty($question_id)) {
			$this->redirect($this->referer());
		}
		$question = $this->Question->rowFromMd5Id($question_id, array('Question.id', 'Question.user_id'));
		if (empty($question)) {
			$this->Session->setFlash(__('Question not found!'), 'danger');
			$this->redirect($this->referer());
		}
		$this->Question->UserBookmark->deleteAll(array(
			'UserBookmark.question_id' => $question['Question']['id']
		));

		$this->Session->setFlash(__('You have successfully deleted.'), 'success');
		$this->redirect($this->referer());
	}

	// backoffice detete answer
	public function backoffice_answer_delete($answer_id) {
		$this->autoRender = false;
		if (empty($answer_id)) {
			$this->redirect($this->referer());
		}
		$answer = $this->Question->Answer->rowFromMd5Id($answer_id, array('Answer.id', 'Answer.user_id'));

		if (empty($answer)) {
			$this->Session->setFlash(__('Answer not found!'), 'danger');
			$this->redirect($this->referer());
		}
		if ($this->Question->Answer->delete($answer['Answer']['id'])) {	
			$this->Session->setFlash(__('You have successfully deleted.'), 'success');
			$this->redirect($this->referer());
	
		} else {
			// no authorization
			$this->Session->setFlash(__('Something went wrong, please try again later'), 'danger');
			$this->redirect('/backoffice');
		}
	}

	// backoffice all interest
	public function backoffice_interests($user_id = null) {
		$this->layout = 'member';
		$this->set('title_for_layout', __('All Interests'));
		$conditions = array();
		if (!empty($user_id)) $conditions = array('UserInterest.user_id' => $user_id);
		$this->paginate = array(
			'limit' => 5,
			'conditions' => $conditions,
			'group' => array('UserInterest.id'),
			'contain'=>array('Question' => array('Category'))
		);
		
		$this->set('questions', $this->Paginator->paginate('UserInterest'));
	}

	// backoffice all bookmark
	public function backoffice_bookmarks($user_id = null) {
		$this->layout = 'member';
		$this->set('title_for_layout', __('All Bookmarks'));
		$conditions = array();
		if (!empty($user_id)) {
			$conditions = array('MD5(UserBookmark.user_id)' => $user_id);
			$user = $this->User->find('first', array(
				'conditions' => array(
					'MD5(User.id)' => $user_id
				),
				'fields' => array('User.email')
				//'fields' => array('User.Message', 'User.email')
			));
			$this->set(compact('user'));
		}
		$this->paginate = array(
			'limit' => 5,
			'conditions' => $conditions,
			// 'group' => array('UserBookmark.id'),
			// 'fields' => array('DISTINCT UserBookmark.id', 'UserBookmark.question_id', 'UserBookmark.user_id'),
			'contain'=>array('User', 'Question' => array('Category'))
		);
		$this->set('questions', $this->Paginator->paginate('UserBookmark'));
	}

	// backoffice dashboard all poll
	public function backoffice_polls($user_id = null) {
		$this->layout = 'member';
		$this->set('title_for_layout', __('All Polls'));
		if (isset($this->params->named['status'])) {
			$selected = $this->params->named['status'];
			$conditions[] = array('Question.isactive' => $this->params->named['status']);
			$this->set(compact('selected'));
		}
		$conditions[] = array('Question.type' => 1);
		if (!empty($user_id)) {
			$conditions[] = array('MD5(Question.user_id)' => $user_id);
			$user = $this->User->find('first', array(
				'conditions' => array(
					'MD5(User.id)' => $user_id
				),
				'fields' => array('User.name', 'User.email')
			));
			$this->set(compact('user'));
		}
		$this->paginate = array(
			'limit' => 5,
			'conditions' => $conditions,
			'contain'=>array('Category', 'QuestionOption')
		);
		$this->set('polls', $this->Paginator->paginate());
	}

	// backoffice dashboard all answers
	public function backoffice_answers($user_id = null) {
		$this->layout = 'member';
		$this->set('title_for_layout', __('My Answers'));
		$conditions = array();
		if (!empty($user_id)) {
			$conditions = array('MD5(Answer.user_id)' => $user_id);
			$user = $this->User->find('first', array(
				'conditions' => array(
					'MD5(User.id)' => $user_id
				),
				'fields' => array('User.name', 'User.email')
			));
			$this->set(compact('user'));
		}
		$this->paginate = array(
			'limit' => 5,
			'conditions' => $conditions,
			'contain'=>array('Question' => array('Category'))
		);
		$this->set('answers', $this->Paginator->paginate('Answer'));
	}

	// backoffice delete question
	public function backoffice_delete_question($question_id) {
		$this->autoRender = false;
		if (empty($question_id)) {
			$this->redirect($this->referer());
		}
		$question = $this->Question->rowFromMd5Id($question_id, array('Question.id', 'Question.user_id'));
		if (empty($question)) {
			$this->Session->setFlash(__('Question not found!'), 'danger');
			$this->redirect($this->referer());
		}
		if ($this->Question->delete($question['Question']['id'])) {
			$this->Session->setFlash(__('You have successfully deleted.'), 'success');
			$this->redirect($this->referer());
	
		} else {
			// no authorization
			$this->Session->setFlash(__('You are not authorize to delete this.'), 'danger');
			$this->redirect('/member');
		}
	}

	// backoffice questions list
	public function backoffice_index($user_id = null) {
		$this->layout = 'member';
		$this->set('title_for_layout', __('All Questions'));
		if (isset($this->params->named['status'])) {
			$selected = $this->params->named['status'];
			$conditions[] = array('Question.isactive' => $this->params->named['status']);
			$this->set(compact('selected'));
		}
		$conditions[] = array('Question.type' => NULL);
		if (!empty($user_id)) {
			$conditions[] = array('MD5(Question.user_id)' => $user_id);
			$user = $this->User->find('first', array(
				'conditions' => array(
					'MD5(User.id)' => $user_id
				),
				'fields' => array('User.name', 'User.email')
			));
			$this->set(compact('user'));
		} 
		$this->paginate = array(
			'limit' => 5,
			'conditions' => $conditions,
			'contain'=>array('Category')
		);
		$this->set('questions', $this->Paginator->paginate());
	}

	// member remove bookmark
	public function member_remove_bookmark($question_id) {
		$this->autoRender = false;
		if (empty($question_id)) {
			$this->redirect($this->referer());
		}
		$user_id = $this->getUserId();
		$question = $this->Question->rowFromMd5Id($question_id, array('Question.id', 'Question.user_id'));
		if (empty($question)) {
			$this->Session->setFlash(__('Question not found!'), 'danger');
			$this->redirect($this->referer());
		}
		$this->Question->UserBookmark->deleteAll(array(
			'UserBookmark.user_id' => $user_id, 
			'UserBookmark.question_id' => $question['Question']['id']
		));

		$this->Session->setFlash(__('You have successfully deleted.'), 'success');
		$this->redirect($this->referer());
	}

	// member detete answer
	public function member_answer_delete($answer_id) {
		$this->autoRender = false;
		if (empty($answer_id)) {
			$this->redirect($this->referer());
		}
		$user_id = $this->getUserId();
		$answer = $this->Question->Answer->rowFromMd5Id($answer_id, array('Answer.id', 'Answer.user_id'));

		if (empty($answer)) {
			$this->Session->setFlash(__('Answer not found!'), 'danger');
			$this->redirect($this->referer());
		}
		// check authorization
		if ($answer['Answer']['user_id'] == $user_id) {
			// authorize to delete
			$this->Question->Answer->delete($answer['Answer']['id']);
			$this->Session->setFlash(__('You have successfully deleted.'), 'success');
			$this->redirect($this->referer());
	
		} else {
			// no authorization
			$this->Session->setFlash(__('You are not authorize to delete this.'), 'danger');
			$this->redirect('/member');
		}
	}
	public function ajax_answer_delete() {
		$this->autoRender = false;
		$user_id = $this->getUserId();
		$res = array();
		$answer = $this->Question->Answer->rowFromMd5Id((int)$this->request->data['answer_id'], array('Answer.id', 'Answer.user_id'));
		
		if (empty($answer)) {
			$res['result'] = 2;
			$res['message'] = __('Not found!');
		} elseif ($answer['Answer']['user_id'] == $user_id) {
			// authorize to delete
			$this->Question->Answer->delete($answer['Answer']['id']);
			$res['result'] = 1;
			$res['message'] = __('You have successfully deleted.');
	
		} else {
			// no authorization
			$res['result'] = 2;
			$res['message'] = __('You are not authorize to delete this.');
		}
		echo json_encode($res);
		exit;
	}

	// member delete question
	public function member_delete_question($question_id) {
		$this->autoRender = false;
		if (empty($question_id)) {
			$this->redirect($this->referer());
		}
		$user_id = $this->getUserId();
		$question = $this->Question->rowFromMd5Id($question_id, array('Question.id', 'Question.user_id'));
		if (empty($question)) {
			$this->Session->setFlash(__('Question not found!'), 'danger');
			$this->redirect($this->referer());
		}
		// check authorization
		if ($question['Question']['user_id'] == $user_id) {
			// authorize to delete
			$this->Question->delete($question['Question']['id']);
			$this->Session->setFlash(__('You have successfully deleted.'), 'success');
			$this->redirect($this->referer());
	
		} else {
			// no authorization
			$this->Session->setFlash(__('You are not authorize to delete this.'), 'danger');
			$this->redirect('/member');
		}
	}

	public function ajax_question_delete() {
		$this->autoRender = false;
		$user_id = $this->getUserId();
		$res = array();
		$question = $this->Question->rowFromMd5Id((int)$this->request->data['ques_id'], array('Question.id', 'Question.user_id'));
		if (empty($question)) {
			$res['result'] = 2;
			$res['message'] = __('Not found!');
		} elseif ($question['Question']['user_id'] == $user_id) {
			// authorize to delete
			$this->Question->delete($question['Question']['id']);
			$res['result'] = 1;
			$res['message'] = __('You have successfully deleted.');
	
		} else {
			// no authorization
			$res['result'] = 2;
			$res['message'] = __('You are not authorize to delete this.');
		}
		echo json_encode($res);
		exit;
	}

	// member all interest
	public function member_interests() {
		$user_id = $this->getUserId();
		$this->layout = 'member';
		$this->set('title_for_layout', __('My Interests'));
		$this->paginate = array(
			'limit' => 5,
			'conditions' => array(
				'UserInterest.user_id' => $user_id
			),
			'contain'=>array('Question' => array('Category'))
		);
		
		$this->set('questions', $this->Paginator->paginate('UserInterest'));
	}

	// member all bookmark
	public function member_bookmarks() {
		$user_id = $this->getUserId();
		$this->layout = 'member';
		$this->set('title_for_layout', __('My Bookmarks'));
		$this->paginate = array(
			'limit' => 5,
			'conditions' => array(
				'UserBookmark.user_id' => $user_id
			),
			'contain'=>array('Question' => array('Category'))
		);
		
		$this->set('questions', $this->Paginator->paginate('UserBookmark'));
	}

	// member dashboard all poll
	public function member_polls() {
		$user_id = $this->getUserId();
		$this->layout = 'member';
		$this->set('title_for_layout', __('My Polls'));
		$this->paginate = array(
			'limit' => 5,
			'conditions' => array(
				'Question.type' => 1,
				'Question.user_id' => $user_id
			),
			'contain'=>array('Category', 'QuestionOption')
		);
		$this->set('polls', $this->Paginator->paginate());
	}

	// member dashboard all answers
	public function member_answers() {
		$user_id = $this->getUserId();
		$this->layout = 'member';
		$this->set('title_for_layout', __('My Answers'));
		$this->paginate = array(
			'limit' => 5,
			'conditions' => array(
				'Answer.user_id' => $this->Auth->user('id')
			),
			'contain'=>array('Question' => array('Category'))
		);
		$this->set('answers', $this->Paginator->paginate('Answer'));
	}

	// member dashboard question
	public function member_index() {
		$user_id = $this->getUserId();
		$this->layout = 'member';
		$this->set('title_for_layout', __('My Questions'));
		$this->paginate = array(
			'limit' => 5,
			'conditions' => array(
				'Question.type' => NULL,
				'Question.user_id' => $user_id
			),
			'contain'=>array('Category')
		);
		$this->set('questions', $this->Paginator->paginate());
	}

	// question view page
	public function view($question_id = null) {
		if (empty($question_id)) {
			return $this->redirect('/');
		}
		$question = $this->Question->find('first', array(
			'conditions' => array(
				'MD5(Question.id)' => $question_id
			),
			'contain' => array(
				'User' => array('fields' => array('User.name', 'User.email', 'User.avatar')),
				'UserBookmark' => array('conditions' => array('UserBookmark.user_id' => $this->Auth->user('id'))),
				'Category' => array('fields' => array('Category.id', 'Category.name')))
		));
		
		if (empty($question)) {
			return $this->redirect('/');
		}
		
		$this->set('answers', $this->answers_pagination($question_id, 1));
		
		$parent_cats = $this->Category->getPath($question['Category']['id']);
		$this->set(compact('question', 'parent_cats'));
	}

	// ajax pagination for answers
	public function ajax_answer_view() {
		$this->layout = 'ajax';
		$this->set('answers', $this->answers_pagination($this->request->data['question_id'], $this->request->data['page_no']));
	}

	//  a function for answers pagination
	// $question_id md5
	private function answers_pagination($question_id, $page) {
		if (empty($question_id) || empty($page)) {
			return array();
		}

		$this->paginate = array(
			'limit' => 20,
			'conditions' => array(
				'Answer.isactive'=>1,
				'MD5(Answer.question_id)' => $question_id
			),
			'page' => $page,
			'contain'=>array('User', 'Reply'),
			'order' => 'Answer.popular DESC'
		);
		return $this->Paginator->paginate('Answer');
	}

	/*
	* Home page questions and polls method
	*/
	public function ajax_home() {
		$this->layout = "ajax";
		$defaultCategory = $this->Question->defaultCategory;
		if (empty($this->request->data['page_no'])) {
			$page = 1;
		} else {
			$page = (int) $this->request->data['page_no'];
		}


		if (!empty($this->request->data['cat_id'])) {
			$current_category = $this->Question->Category->rowFromMd5Id($this->request->data['cat_id']);
			if (empty($current_category)) {
				$this->_jsonError(__('Category does not exist!!!'));
			}
			$parents = $this->Question->Category->getPath($current_category['Category']['id']);
			$this->set(compact('parents', 'current_category'));
			$firstChild = $this->Question->Category->children($current_category['Category']['id'], true);
			$allChilds = $this->Question->Category->children($current_category['Category']['id']);
			$childIds = Hash::combine($allChilds, '{n}.Category.id', '{n}.Category.id');
			$findCatIds = Hash::merge(array($current_category['Category']['id'] => $current_category['Category']['id']), $childIds);
			// pr($childIds);
			// exit;
			$conditions['Question.category_id'] = $findCatIds;
			$cat_condition = array('Question.category_id' => $findCatIds);
		} else {
			$firstChild = $this->Question->Category->find('all', array(
				'conditions' => array(
					'Category.isactive' => 1,
					'Category.parent_id' => NULL
				),
				'contain' => array()
			));
			$cat_condition = array();
		}
		$conditions['Question.isactive'] = array(1,2);
		$this->paginate = array(
			'limit' => 10,
			'conditions' => array(
				'OR' => array(
					'Question.isactive' => array(1,2),
					'AND' => array(
						'Question.isactive' => 0,
						'Question.user_id' => $this->Auth->user('id') 
					)
				),
				$cat_condition
			),
			'order' => array(
				'Question.modified' => 'desc',
			),
			'page' => $page,
			'contain' => array('QuestionOption', 'Category' => array('fields' => array('Category.id', 'Category.name')))
		);
		// pr($this->Paginator->paginate());
		// exit;
		try {
			$this->set('questions', $this->Paginator->paginate());
			$this->set(compact('firstChild', 'defaultCategory'));
		} catch (NotFoundException $e) { 
			// when pagination error found redirect to first page e.g. paging page not found
			return $this->redirect('/');
		}
	}

	/*
	* Question/Poll abuse method
	*/
	public function ajax_abuse() {
		$this->autoRender = false;
		$user_id = $this->getUserId();
		$question = $this->Question->rowFromMd5Id($this->request->data['ques_id']);
		if (!empty($question)) {
			$infoToSave['Abuse']['user_id'] = $user_id;
			$infoToSave['Abuse']['question_id'] = $question['Question']['id'];
			$infoToSave['Abuse']['comment'] = $this->request->data['comment'];
			$this->loadModel('Abuse');
			if ($this->Abuse->save($infoToSave)) {
				// send notification email to the admin
				$this->User->sendEmail(Configure::read('AdminEmail'), __('Abuse resported on FORUM'), 'question/abuse', array(
					'question' => $question['Question'],
					'comment' => $this->request->data['comment']
				));
				$this->Session->setFlash(__('You have successfully reported, thanks.'), 'success');
				echo json_encode(array('result' => 1, 'message' => __('Reported done')));
			}
		} else {
			$this->Session->setFlash(__('Something went wrong. Question not found!'), 'danger');
			echo json_encode(array('result' => 1, 'message' => __('Something went wrong. Question not found!')));
		}
	}

	/*
	* Question/poll create method
	*/
	public function ajax_create() {
		$this->autoRender = false;
		$this->request->data['Question']['user_id'] = $this->getUserId();
		$category_id = $this->Question->Category->rowFromMd5Id($this->request->data['Question']['category_id'], array('Category.id'));
		$this->request->data['Question']['category_id'] = empty($category_id) ? NULL : $category_id['Category']['id'];
		
		if (!empty($this->request->data['QuestionOption'])) {
			foreach ($this->request->data['QuestionOption'] as $key => $value) {
				if (empty($value['text'])) {
					unset($this->request->data['QuestionOption'][$key]);
				}
			}
		}

		// check if there is censor words
		$this->request->data['Question']['isactive'] = $this->_checkRestrictedWords($this->request->data, 'Question'); 
		if (($this->request->data['Question']['isactive'] == 3) && !isset($this->request->data['yes_confirmed'])) { // restricted word found
			echo json_encode(array('result' => 3, 'message' => __('Censor word found!')));	
			exit;
		}
		$parents = $this->Question->Category->getPath($this->request->data['Question']['category_id']);
		$tags = '';
		if (!empty($parents)) {
			foreach ($parents as $key => $parent) {
				if ((count($parents)-1) == $key) {
					$tags .= $parent['Category']['name'];
				} else {
					$tags .= $parent['Category']['name'] . ', ';	
				}
			}
		}
		$this->request->data['Question']['tags'] = $tags;
		if ($this->Question->saveAll($this->request->data)) {
			// check if photos exist
			if (!empty($this->request->data['photos'])) {
				$newpath = WWW_ROOT . 'uploads' . DS . 'questions';
				foreach ($this->request->data['photos'] as $key => $photo) {
					if (!empty($photo)) {
	                    copy(WWW_ROOT . 'uploads' . DS . 'tmp' . DS . $photo, $newpath . DS . $photo);
	                    copy(WWW_ROOT . 'uploads' . DS . 'tmp' . DS . 't_' . $photo, $newpath . DS . 't_' . $photo);
	                    unlink(WWW_ROOT . 'uploads' . DS . 'tmp' . DS . $photo);
	                    unlink(WWW_ROOT . 'uploads' . DS . 'tmp' . DS . 't_' . $photo);

	                    $savePhotos = array();
	                    $savePhotos['QuestionPhoto']['question_id'] = $this->Question->id;
	                    $savePhotos['QuestionPhoto']['photo'] = $photo;
	                    // save in db
	                    $this->Question->QuestionPhoto->create();
	                    $this->Question->QuestionPhoto->save($savePhotos);
	                }
				}
			}
			$warning = !empty($this->request->data['Question']['isactive']) ? __(' with censor words, its unpublished!') : '';
			$res['message'] = empty($this->request->data['Question']['type']) ? __('You have successfully aksed your question') . $warning : __('You have successfully posted your poll') . $warning;
			$res['flash_class'] = !empty($this->request->data['Question']['isactive']) ? 'warning' : 'success';
			$res['result'] = 1;
			$res['question_id'] = md5($this->Question->id);
			echo json_encode($res);	
		} else {
			$this->Session->setFlash(__('Something went wrong. Please try again later!'), 'danger');
			echo json_encode(array('result' => 1, 'message' => __('Something went wrong. Please try again later.')));
		}
	}

	public function ajax_single_question() {
		$this->layout = 'ajax';
		$question = $this->Question->find('first', array(
			'conditions' => array('MD5(Question.id)' => $this->request->data['question_id']),
			'contain' => array('QuestionOption', 'Category' => array('fields' => array('Category.id', 'Category.name')))
		));
		$this->set(compact('question'));
		$this->render('/Elements/ajax_single_question');
	}

	/*
	* Poll vote cast
	*/
	public function ajax_vote_cast() {
		$this->layout = 'ajax';
		$errorFound = '';
		$user_id = $this->Auth->user('id');
		if (empty($user_id)) {
			$this->Session->setFlash(__('Please login first to continue, thanks.'), 'danger');
			$errorFound = 2; // login error
		} else {
			$this->request->data['Vote']['user_id'] = $this->Auth->user('id');
			$question = $this->Question->rowFromMd5Id($this->request->data['Vote']['question_id'], array('Question.id'));
			$this->request->data['Vote']['question_id'] = $question['Question']['id'];
			$question_option = $this->Question->QuestionOption->rowFromMd5Id($this->request->data['Vote']['question_option_id'], array('QuestionOption.id'));
			$this->request->data['Vote']['question_option_id'] = $question_option['QuestionOption']['id'];
			// pr($this->request->data);
			// exit;
			if ($this->Question->QuestionOption->Vote->saveAll($this->request->data)) {
				$question = $this->Question->find('first', array(
					'conditions' => array('Question.id' => $this->request->data['Vote']['question_id']),
					'contain' => array('QuestionOption')
				));
				$this->set(compact('question'));
			} else {
				$this->Session->setFlash(__('Something went wrong. Please try again later!'), 'danger');
				$errorFound = 1; // unexpected error
			}
		}
		$this->set(compact('errorFound'));
	}

	// ajax answer
	public function ajax_answer() {
		$this->layout = 'ajax';
		$errorFound = '';
		$user_id = $this->Auth->user('id');
		if (empty($user_id)) {
			$this->Session->setFlash(__('Please login first to continue, thanks.'), 'danger');
			$errorFound = 2; // login error
		} else {
			$this->request->data['Answer']['user_id'] = $this->Auth->user('id');
			$question = $this->Question->rowFromMd5Id($this->request->data['Answer']['question_id'], array('Question.id'));
			$this->request->data['Answer']['question_id'] = $question['Question']['id'];
			// pr($this->request->data);
			// exit;
			$this->request->data['Answer']['isactive'] = $this->_checkRestrictedWords($this->request->data, 'Answer');
			$this->request->data['Answer']['isactive'] = empty($this->request->data['Answer']['isactive']) ? 1 : $this->request->data['Answer']['isactive']; 
			if ($this->Question->Answer->saveAll($this->request->data)) {
				$answer = $this->Question->Answer->find('first', array(
					'conditions' => array('Answer.id' => $this->Question->Answer->id),
					'contain' => array('User')
				));
				$this->set(compact('answer'));
				if ($this->request->data['Answer']['isactive'] == 3) {
					$errorFound = 1;
					$this->Session->setFlash(__('Your answer has been posted with warning, its unpublished!'), 'warning');
				}
			} else {
				$this->Session->setFlash(__('Something went wrong. Please try again later!'), 'danger');
				$errorFound = 1; // unexpected error
			}
		}
		$this->set(compact('errorFound'));
	}

	// ajax like/dislike
	public function ajax_like() {
		$this->autoRender = false;
		
		$this->request->data['Like']['user_id'] = $this->getUserId();
		$type = true;
		if ($this->request->data['type'] == 'yes') {
			$this->request->data['Like']['type'] = NULL;
		} elseif ($this->request->data['type'] == 'no') {
			$this->request->data['Like']['type'] = 1;
		} else {
			$type = false;
		}

		$answer = $this->Question->Answer->rowFromMd5Id($this->request->data['answer_id'], array('Answer.id', 'Answer.like_count', 'Answer.dislike_count', 'Answer.popular'));
		$this->request->data['Like']['answer_id'] = $answer['Answer']['id'];

		if (empty($this->request->data['Like']['answer_id']) && empty($type)) {
			echo json_encode(array('result' => 2, 'message' => __('Something went wrong. Please try again later.')));
			exit;
		}

		// if user has like/dislike then remove like/dislike
		$conditions = array(
			'Like.user_id' => $this->request->data['Like']['user_id'],
			'Like.answer_id' => $this->request->data['Like']['answer_id'],
			'Like.type' => $this->request->data['Like']['type']
		);

		$like_dislike = $this->Question->Answer->Like->find('first', array(
			'conditions' => $conditions
		));

		// pr($like_dislike);
		// exit;

		if (!empty($like_dislike)) {
			$this->Question->Answer->Like->delete($like_dislike['Like']['id']);
			$data['Answer']['id'] = $answer['Answer']['id'];
			if (empty($this->request->data['Like']['type'])) {
				$data['Answer']['popular'] = $answer['Answer']['popular']-1;
				$data['Answer']['like_count'] = $answer['Answer']['like_count']-1;
				$this->Question->Answer->saveAll($data);
			} else {
				$data['Answer']['popular'] = $answer['Answer']['popular']+0.5;
				$data['Answer']['dislike_count'] = $answer['Answer']['dislike_count']-1;
				$this->Question->Answer->saveAll($data);
			}
			echo json_encode(array('result' => 1, 'added' => 0, 'message' => __('Like/Dislike remove')));
			exit;
		}

		if ($this->Question->Answer->Like->saveAll($this->request->data)) {
			$data['Answer']['id'] = $answer['Answer']['id'];
			if (empty($this->request->data['Like']['type'])) {
				$data['Answer']['popular'] = $answer['Answer']['popular']+1;
				$data['Answer']['like_count'] = $answer['Answer']['like_count']+1;
				$this->Question->Answer->saveAll($data);
			} else {
				$data['Answer']['popular'] = $answer['Answer']['popular']-0.5;
				$data['Answer']['dislike_count'] = $answer['Answer']['dislike_count']+1;
				$this->Question->Answer->saveAll($data);
			}
			echo json_encode(array('result' => 1, 'added' => 1, 'message' => __('Like/Dislike added')));	
		} else {
			echo json_encode(array('result' => 2, 'message' => __('Something went wrong. Please try again later.')));
		}

	}

	// ajax bookmark
	public function ajax_bookmark() {
		$this->autoRender = false;
		$this->request->data['UserBookmark']['user_id'] = $this->getUserId();
		$answer = $this->Question->rowFromMd5Id($this->request->data['question_id'], array('Question.id'));
		$this->request->data['UserBookmark']['question_id'] = $answer['Question']['id'];
		if (!empty($this->request->data['UserBookmark']['question_id']) && $this->Question->UserBookmark->saveAll($this->request->data)) {
			echo json_encode(array('result' => 1, 'message' => __('Bookmark added')));	
		} else {
			echo json_encode(array('result' => 2, 'message' => __('Something went wrong. Please try again later.')));
		}
	}

	// ajax interest
	public function ajax_interest() {
		$this->autoRender = false;
		$this->request->data['UserInterest']['user_id'] = $this->getUserId();
		$answer = $this->Question->rowFromMd5Id($this->request->data['question_id'], array('Question.id'));
		$this->request->data['UserInterest']['question_id'] = $answer['Question']['id'];

		// if interest, then remove
		$userInterest = $this->Question->UserInterest->find('first', array(
			'conditions' => array(
				'UserInterest.user_id' => $this->request->data['UserInterest']['user_id'],
				'UserInterest.question_id' => $this->request->data['UserInterest']['question_id']
			)
		));

		if (!empty($userInterest)) {
			$this->Question->UserInterest->delete($userInterest['UserInterest']['id']);
			echo json_encode(array('result' => 1, 'added' => 0, 'message' => __('Interest removed')));
			exit;
		}

		if (!empty($this->request->data['UserInterest']['question_id']) && $this->Question->UserInterest->saveAll($this->request->data)) {
			echo json_encode(array('result' => 1, 'added' => 1, 'message' => __('Interest added')));	
		} else {
			echo json_encode(array('result' => 2, 'message' => __('Something went wrong. Please try again later.')));
		}
	}

}