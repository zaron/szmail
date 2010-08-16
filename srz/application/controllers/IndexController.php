<?php
/**
 * szMail
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://szlab.de/license/new-bsd
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@szlab.de so
 * we can send you a copy immediately.
 *
 *
 * @copyright  Copyright (c) 2010 - $currentYear$ Otto von Wesendonk, Eric Barmeyer (http://szlab.de)
 * @version    $Id$
 * @license    http://szlab.de/license/new-bsd     New BSD License
 */

class IndexController extends Zend_Controller_Action {


	public function indexAction() {
	}


	public function loginAction() {

		$form = new Form_Login();

		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$salt = "vhjdlrpsihgb5641";
			$adapter = new Zend_Auth_Adapter_DbTable(
			Zend_Db_Table::getDefaultAdapter(),
                    'users',
                    'username',
                    'password',
                    'MD5(CONCAT(?, "'.$salt.'"))'
                    );

                    $adapter->setIdentity($form->getValue('username'));
                    $adapter->setCredential($form->getValue('password'));

                    $auth = Zend_Auth::getInstance();
                    $result = $auth->authenticate($adapter);

                    if ($result->isValid()) {
                    	SZ_Session_Manager::createSession($adapter->getResultRowObject());
                    	$this->_redirect('/mail');
                    	return;
                    }
		}

		$this->view->form = $form;
	}

	public function logoutAction() {
		$this->_helper->viewRenderer->setNoRender();
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		SZ_Session_Manager::deleteSession();
		$this->_redirect('/');
	}

}

