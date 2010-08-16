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

class Plugin_Authentication extends Zend_Controller_Plugin_Abstract {

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
		$module = $this->getRequest()->getModuleName();
		$controller = $this->getRequest()->getControllerName();
		$action = $this->getRequest()->getActionName();

		if($controller != "index" && $controller != "test") {
			$auth = Zend_Auth::getInstance();
			if(!$auth->hasIdentity()) {
				$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
				$redirector->gotoSimpleAndExit("login","index","default");
			}
		}
	}

}