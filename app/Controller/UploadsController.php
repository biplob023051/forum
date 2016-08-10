<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
App::uses('AppController', 'Controller');

class UploadsController extends AppController {
	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('member_avatar', 'ajax_photos');
    }

    public function ajax_photos() {
        $this->autoRender = false;
        $path = 'uploads' . DS . 'tmp';
        $url = 'uploads/tmp/';
        $this->_prepareDir($path);
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        
        App::import('Vendor', 'qqFileUploader');
        $uploader = new qqFileUploader($allowedExtensions);
        
        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
        $result = $uploader->handleUpload($path);
        
        if (!empty($result['success'])) {
            // resize image
            App::import('Vendor', 'phpThumb', array('file' => 'phpThumb/ThumbLib.inc.php'));
            $photo = PhpThumbFactory::create($path . DS . $result['filename'], array('jpegQuality' => PHOTO_QUALITY));
            
            //$this->_rotateImage($photo, $path . DS . $result['filename']);
            
            $photo->resize(AVATAR_WIDTH, AVATAR_HEIGHT)->save($path . DS . $result['filename']);
            
            $photo = PhpThumbFactory::create($path . DS . $result['filename']);
            $photo->adaptiveResize(AVATAR_THUMB_WIDTH, AVATAR_THUMB_HEIGHT)->save($path . DS . 't_' . $result['filename']);
            $result['avatar'] = $this->request->webroot . $url . 't_' . $result['filename'];
            $result['filename'] = $result['filename'];
        }
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    }

    public function member_avatar($id = null) {
        $this->autoRender = false;
        $this->loadModel('User');
        if (!$id) {
            $path = 'uploads' . DS . 'tmp';
            $url = 'uploads/tmp/';
        } else {
            $user = $this->User->findById($id);
            $path = 'uploads' . DS . 'avatars';
            $url = 'uploads/avatars/';
        }
        
        $this->_prepareDir($path);
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        
        App::import('Vendor', 'qqFileUploader');
        $uploader = new qqFileUploader($allowedExtensions);
        
        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
        $result = $uploader->handleUpload($path);
        
        if (!empty($result['success'])) {
            // resize image
            App::import('Vendor', 'phpThumb', array('file' => 'phpThumb/ThumbLib.inc.php'));
            
            $photo = PhpThumbFactory::create($path . DS . $result['filename'], array('jpegQuality' => PHOTO_QUALITY));
            //$this->_rotateImage($photo, $path . DS . $result['filename']);
            
            //$photo = PhpThumbFactory::create($path . DS . $result['filename']);
            $photo->resize(AVATAR_WIDTH, AVATAR_HEIGHT)->save($path . DS . $result['filename']);
            
            $photo = PhpThumbFactory::create($path . DS . $result['filename']);
            $photo->adaptiveResize(AVATAR_THUMB_WIDTH, AVATAR_THUMB_HEIGHT)->save($path . DS . 't_' . $result['filename']);
            
            if ($id) {
                // save to db
                $this->User->id = $this->Auth->user('id');
                $this->User->set(array('avatar' => $result['filename']));
                $this->User->save();
                
                // delete old files
                if ($user['User']['avatar'] && file_exists($path . DS . $user['User']['avatar'])) {
                    unlink($path . DS . $user['User']['avatar']);
                    unlink($path . DS . 't_' . $user['User']['avatar']);
                }
            }
            
            $result['avatar'] = $this->request->webroot . $url . 't_' . $result['filename'];
            $result['fullimage'] = $this->request->webroot . $url . $result['filename'];
            $result['filename'] = $result['filename'];
        }
        
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    }

    private function _prepareDir($path) {
		$path = WWW_ROOT . $path;
		if (!file_exists($path)) {
			mkdir($path, 0755, true);
			file_put_contents($path . DS . 'index.html', '');
		}
	}

    private function _rotateImage(&$photo, $path) { 
        // rotate image if necessary
        $exif = exif_read_data($path);
        
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 8:
                    $photo->rotateImageNDegrees(90)->save($path);
                    break;
                case 3:
                    $photo->rotateImageNDegrees(180)->save($path);
                    break;
                case 6:
                    $photo->rotateImageNDegrees(-90)->save($path);
                    break;
            }
        }
    }
}