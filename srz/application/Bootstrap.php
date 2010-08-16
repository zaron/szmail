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

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype() {
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_TRANSITIONAL');
	}

	protected function _initAutoload() {
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
			'namespace' => '',
			'basePath' => APPLICATION_PATH));
		$a =  Zend_Loader_Autoloader::getInstance();
		$a->registerNamespace("SZ_");
		return $moduleLoader;
	}

	protected function _initUTF8DB() {
		$this->bootstrap('db');
		$db = $this->getResource('db');
		$db->query("SET NAMES 'utf8'");
		Zend_Registry::set('db',$db);
	}

	protected function _initPlugins() {
		$this->bootstrap('FrontController');
		$front = $this->getResource('FrontController');
		$front->registerPlugin(new Plugin_Authentication());

		$this->bootstrap('db');
		$db = $this->getResource('db');
	}
}

