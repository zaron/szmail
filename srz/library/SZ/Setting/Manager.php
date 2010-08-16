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

class SZ_Setting_Manager {

	public function getMainImapConfig($userId = null) {
		if($userId === null) {
			$session = SZ_Session_Manager::getSession();
			$userId = $session->userId;
		}
		$db = Zend_Registry::get('db');
		$select = new Zend_Db_Select($db);

		$select->from('server')->where('user_id = ?',$userId)->where('type = ?','imap')->where('main = ?','1')->limit(1);
		$qry = $select->query()->fetch();
		if($qry == false) {
			throw new SZ_Exception('Couldn\'t load config.');
		}
		$result = array();
		$result['imap']['host'] = $qry['host'];
		$result['imap']['port'] = $qry['port'];
		$result['imap']['ssl'] = $qry['protocol'];
		$result['imap']['user'] = $qry['username'];
		$result['imap']['password'] = self::decryptPassword($qry['password']);

		return (object)$result;
	}

	//TODO implement
	private function decryptPassword($pwd) {
		return $pwd;
	}

	public function getIdentities($userId = null) {
		if($userId === null) {
			$session = SZ_Session_Manager::getSession();
			$userId = $session->userId;
		}
		$db = Zend_Registry::get('db');
		$select = new Zend_Db_Select($db);

		$select->from(array('i' => 'identities'),array('id','name','email'));
		$select->where('i.user_id = ?',$userId);

		$qry = $select->query()->fetchAll();
		if($qry == false || empty($qry)) {
			throw new SZ_Exception('Couldn\'t load config.');
		}

		$result = array();
		foreach($qry as $column) {
			$result[$column['id']] = $column['name'].' <'.$column['email'].'>';
		}
		return $result;
	}

	public function getIdentity($id,$userId=null) {
		if($userId === null) {
			$session = SZ_Session_Manager::getSession();
			$userId = $session->userId;
		}
		$db = Zend_Registry::get('db');
		$select = new Zend_Db_Select($db);

		$select->from(array('i' => 'identities'),array('name','email'));
		$select->from(array('s' => 'server'),array('protocol','host','port','username','password'));
		$select->where('i.server_id = s.id');
		$select->where('i.user_id = ?',$userId);
		$select->where('i.id = ?',$id);
		$select->limit(1);

		$qry = $select->query()->fetch();
		if($qry == false || empty($qry)) {
			throw new SZ_Exception('Couldn\'t load config.');
		}

		$result = array(
		'host' => $qry['host'],
		'port' => $qry['port'],
		'ssl' => $qry['protocol'],
		'username' => $qry['username'],
		'password' => self::decryptPassword($qry['password']),
		'auth' => 'login'
		);

		return new SZ_Mail_Identity($qry['name'],$qry['email'],$result);
	}

}