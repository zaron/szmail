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

class SZ_Session_Manager {

	private static $session;

	public function createSession($row) {
		$session = self::initSession();
		if($session->isLocked()) {
			throw new SZ_Exception("session is locked.");
		}
		$session->userId = $row->id;
		$session->username = $row->username;
		$session->password = $row->password;
	}

	private function initSession() {
		if(self::$session === null) {
			self::$session = new Zend_Session_Namespace('userdata');
		}
		return self::$session;
	}

	public function getSession() {
		if(self::$session === null) {
			$session = self::initSession();
			if(!isset($session->userId)) {
				throw new SZ_Exception('No session found.');
			}
			return $session;
		}
		return self::$session;
	}

	public function deleteSession() {
		$session = self::initSession();
		$session->unsetAll();
	}
}