<?php

/**
 * Home Controller
 */
class HomeController extends AppController
{
	public $uses = array('Category');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

    // home page
	public function index() {
		$this->set('title_for_layout', __('Home'));
    }
}