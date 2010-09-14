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

class Bootstrap extends Zend_Application_Bootstrap_BootstrapAbstract
{
	protected function _initAutoload() {
		$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
			'namespace' => '',
			'basePath' => APPLICATION_PATH));
		$a =  Zend_Loader_Autoloader::getInstance();
		$a->registerNamespace("SZ_");
	 	$resourceLoader->addResourceType('Controller', 'controller/', 'Controller');
		return $resourceLoader;
	}

	protected function _initUTF8DB() {
		$this->bootstrap('db');
		$db = $this->getResource('db');
		$db->query("SET NAMES 'utf8'");
		Zend_Registry::set('db',$db);
	}

	private function addClasses(Zend_Json_Server $server){
		$server->setClass('Controller_Test');
		$server->setClass('Controller_Mail');
		$server->setClass('Controller_Settings');
	}

	private function preDispatch(Zend_Json_Server $server) {
		$request = $server->getRequest();
		if($request->getMethod() != "login") {
			//do nothing yet
		}
	}

	public function run() {
		$server = new Zend_Json_Server();

		// register methods
		$this->addClasses($server);

		// check request
		$this->preDispatch($server);

		if($server->getResponse()->isError()) {
			echo $server->getResponse();
			return;
		}

		if ('GET' == $_SERVER['REQUEST_METHOD']) {
			// Indicate the URL endpoint, and the JSON-RPC version used:
			$server->setTarget('/api/1.0/jsonrpc.php')
			->setEnvelope(Zend_Json_Server_Smd::ENV_JSONRPC_2);

			// Return the SMD to the client
			header('Content-Type: application/json');
			echo $server->getServiceMap();
			return;
		}
		$server->handle();
	}
}

