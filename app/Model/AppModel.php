<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	
	public $recursive = -1;
	
	public $actsAs = array('Containable');


	
	// declaration of japanese state constant
	public $statesOptions;

	// declaration of gender constant
	public $genderOptions;

	// default category
	public $defaultCategory;
	
	public function __construct($id = false , $table = null , $ds = null ){
		parent::__construct($id,$table,$ds);
		
		// initialize gender constant
		$this->defaultCategory = array('0' => __('All Categories'));
		$this->genderOptions = array('1' => __('Male'), '0' => __('Female'));
		$this->voucherTypes = array('1' => __('Roti Sabji'), '0' => __('Kheer Roti'));
		$this->taxOptions = array('480' => '480', '440' => '440');
		$this->statesOptions = array(
			'' => __('Choose state'),
			'Andra Pradesh' => __('Andra Pradesh'),
			'Arunachal Pradesh' => __('Arunachal Pradesh'),
			'Assam' => __('Assam'),
			'Bihar' => __('Bihar'),
			'Chhattisgarh' => __('Chhattisgarh'),
			'Goa' => __('Goa'),
			'Gujarat' => __('Gujarat'),
			'Haryana' => __('Haryana'),
			'Himachal Pradesh' => __('Himachal Pradesh'),
			'Jammu and Kashmir' => __('Jammu and Kashmir'),
			'Jharkhand' => __('Jharkhand'),
			'Karnataka' => __('Karnataka'),
			'Kerala' => __('Kerala'),
			'Madya Pradesh' => __('Madya Pradesh'),
			'Maharashtra' => __('Maharashtra'),
			'Manipur' => __('Manipur'),
			'Meghalaya' => __('Meghalaya'),
			'Mizoram' => __('Mizoram'),
			'Nagaland' => __('Nagaland'),
			'Orissa' => __('Orissa'),
			'Punjab' => __('Punjab'),
			'Rajasthan' => __('Rajasthan'),
			'Sikkim' => __('Sikkim'),
			'Sikkim' => __('Sikkim'),
			'Tripura' => __('Tripura'),
			'Uttaranchal' => __('Uttaranchal'),
			'Uttar Pradesh' => __('Uttar Pradesh'),
			'West Bengal' => __('West Bengal')
		);
	}

	public function beforeFind($query) {
		parent::beforeFind($query);
		$this->virtualFields['md5_id'] = 'MD5('.$this->alias.'.id)';
	    return $query;
	}

	public function beforeSave($options = array()) {
		parent::beforeSave($options);		
		if(AuthComponent::user('id')){ //checked logged user
			if(empty($this->data[$this->alias]['id'])){ //data will insert now
				$this->data[$this->alias]['created_by']=AuthComponent::user('id');
			} else { //data will update now				
				$this->data[$this->alias]['modified_by']=AuthComponent::user('id');
			}
		}
		return $options;
	}
	 
	public function randText($length=40){
		$random= "";
		srand((double)microtime()*1000000);
		$strset  = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
		$strset.= "abcdefghijklmnpqrstuvwxyz";
		$strset.= "123456789";
		// Add the special characters to $strset if needed
		
		for($i = 0; $i < $length; $i++) {
			$random.= substr($strset,(rand()%(strlen($strset))), 1);
		}
		return $random;
	}
	
	public function isOwnedBy($record, $checkField='createdby', $user=null) {
		if(empty($user))$user=AuthComponent::user('id');
		if(empty($user)) return false;
		return $this->field($this->alias.'.id', array($this->alias.'.id' => $record, $this->alias.'.'.$checkField => $user)) === $record;
	}
	
	public function makeSlug ($string, $id=null, $fieldname='slug',$translate=false) {
		$slug = $string;
				
		//remove non unicode character from string
		$regx = '/([\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3})|./s';
		$slug  = preg_replace( $regx , '$1' , $slug );
		
		
		//remove unicode BOM character from string
		$regx = '\xef\xbb\xbf';
		$slug  = str_replace( $regx , '' , $slug );
		
		$slug = mb_strtolower($slug,'UTF-8');
		
		if($translate && extension_loaded('iconv')){
			//translate non ascii character to ascii character
			$slug = iconv( 'UTF-8' , 'US-ASCII//TRANSLIT//IGNORE' , $slug );
		}
		
		//remove special character
		if($translate && @preg_match( '//u', '' )){
			$slug = preg_replace( '/[^\\p{L}\\p{Nd}\-_]+/u' , '-' , $slug );
		} else {
			$slug = preg_replace( '/[\(\)\>\<\+\?\&\"\'\/\\\:\s\-\#\%\=\@\^\$\,\.\~\`\'\"\*\!]+/' , '-' , $slug );
		}
		
		$slug=trim($slug,"_-");
		
		$params = array ();
		$params ['conditions']= array();
		$params ['conditions'][$this->alias.'.'.$fieldname]= $slug;
		if (!is_null($id)) {
			$params ['conditions']['not'] = array($this->alias.'.id'=>$id);
		}
		$i = 0;		
		//check and make unique slug
		while (count($this->find ('all',$params))) {
			if (!preg_match ('/-{1}[0-9]+$/', $slug )) {
				$slug .= '-' . ++$i;
			} else {
				$slug = preg_replace ('/[0-9]+$/', ++$i, $slug );
			}
			$params ['conditions'][$this->alias.'.'.$fieldname]= $slug;
		}
		return $slug;
	}
	
	//making unique key
	public function makeUniqueKey ($length=15, $id=NULL,  $fieldname='unique_key') {
		$uniqueKey= $this->randText($length); 
		
		$params = array ();
		$params ['conditions']= array();
		$params ['conditions'][$this->alias.'.'.$fieldname]= $uniqueKey;
		if(isset($id)){
			$params ['conditions']['not'] = array($this->alias.'.id'=>$id);
		}
		while (count($this->find ('all',$params))) {
			$uniqueKey= $this->randText($length); 
			$params ['conditions'][$this->alias.'.'.$fieldname]= $uniqueKey;
		}
		return $uniqueKey;
	}
	
	//get current date time
	public function getCurrentDateTime(){
		App::uses('CakeTime', 'Utility');
		return CakeTime::format('Y-m-d H:i:s',CakeTime::convert(time(),CakeTime::timezone()));
	}

	/**
	 * System wide send email method
	 * @param string $recipientEmail - recipient's email address
	 * @param string $subject
	 * @param string $template - email template to use
	 * @param array $vairables - array of vairables to set in email
	 * @param string $senderEmail - sender's email address
	 */
	public function sendEmail($recipientEmail, $subject, $template, $vairables, $senderEmail = null, $senderName = null) {
		App::uses('CakeEmail', 'Network/Email');
		
		if (empty($senderEmail)) {
			// for bsk server
			$senderEmail = 'no-reply@bskapps.com';
			// for localhost
			//$senderEmail = 'bcsarkar02@gmail.com';
		}
		
		if (empty($senderName)) {
			$senderName = 'FORUM';
		}

		$vairables['loginUrl'] = Router::url('/',true);
		
		$email = new CakeEmail();
		$email->config('default');
		$email->from($senderEmail, $senderName);
				$email->to($recipientEmail);
				$email->subject($subject);
				$email->template($template);
				$email->viewVars($vairables);
				$email->emailFormat('text');
  				$email->send(); 
	}

	/**
	 * find method for a single field by md5 id
	 * @param string $md5_id - md5 id
	 * @param string $fieldname - field to find, if null default id
	 */
	public function fieldFromMd5Id($md5_id, $fieldname = null) {
		// if field is empty, then return actual id
		if (empty($fieldname)) {
			$fieldname = 'id';
		}
		$params['conditions']= array();
		$params['conditions']['md5('.$this->alias.'.id)'] = $md5_id;
		$params['fields'] = $fieldname;
		$result = $this->find('first',$params);
		return $result[$this->alias][$fieldname];
	}

	// get current field list on any table
	public function initFields() {
		$res[$this->name] = array_fill_keys(array_keys($this->Schema()), '');
		return $res;
	}

	/**
	 * find method for a row by md5 id
	 * @param string $md5_id - md5 id
	 */
	public function rowFromMd5Id($md5_id, $fields = array(), $contain = array()) {
		//$params['conditions'][$this->alias.'.isactive']= 1;
		$params['conditions']['md5('.$this->alias.'.id)'] = $md5_id;
		$params['fields'] = $fields;
		$params['contain'] = $contain;
		$result = $this->find('first',$params);
		return $result;
	}

	/* this function will unbind each model except those are given as inputs*/
	public function unbindModelAll($params=array()) {
        foreach(array(
                'hasOne' => array_keys($this->hasOne),
                'hasMany' => array_keys($this->hasMany),
                'belongsTo' => array_keys($this->belongsTo),
                'hasAndBelongsToMany' => array_keys($this->hasAndBelongsToMany)
        ) as $relation => $model) {
        		$model=array_diff($model, $params);
        		$this->unbindModel(array($relation => $model));
        }
    }

}
