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

class SZ_Mail_Storage_Imap extends Zend_Mail_Storage_Imap {

	function __construct($params) {
		$this->_messageClass = 'SZ_Mail_Message';
		parent::__construct($params);
	}

	//@Override
	/*public function getNumberByUniqueId($id)
	 {
		if(is_int($id) || ctype_digit($id)) {
		$result = $this->_protocol->search(array('UID',(int) $id));
		if(sizeof($result)==1) {
		return $result[0];
		}
		}

		/**
		* @see Zend_Mail_Storage_Exception
		*
		throw new Zend_Mail_Storage_Exception('unique id not found');
		}*/

	function getMessageByUid($uid) {
		$id = $this->getNumberByUniqueId($uid);
		$data = $this->_protocol->fetch(array('UID','FLAGS', 'RFC822.HEADER'), $id);
		$header = $data['RFC822.HEADER'];
		$_uid = $data['UID'];
		$flags = array();
		foreach ($data['FLAGS'] as $flag) {
			$flags[] = isset(self::$_knownFlags[$flag]) ? self::$_knownFlags[$flag] : $flag;
		}

		return new $this->_messageClass(array('handler' => $this, 'uid' => $_uid, 'id' => $id, 'headers' => $header, 'flags' => $flags));
	}

	function getMessagesWithUid(array $ids) {
		$data = $this->_protocol->fetch(array('UID', 'FLAGS', 'RFC822.HEADER'), $ids);
		$result = array();
		foreach($data as $id => $message) {
			$header = $message['RFC822.HEADER'];
			$uid = $message['UID'];
			$flags = array();
			foreach ($message['FLAGS'] as $flag) {
				$flags[] = isset(self::$_knownFlags[$flag]) ? self::$_knownFlags[$flag] : $flag;
			}
			$result[$id] = new $this->_messageClass(array('handler' => $this, 'id' => $id, 'uid' => $uid, 'headers' => $header, 'flags' => $flags));
		}
		return array_reverse($result);
	}

	private function getMessageWithUid($id) {
		$data = $this->_protocol->fetch(array('UID','FLAGS', 'RFC822.HEADER'), $id);
		$header = $data['RFC822.HEADER'];
		$uid = $data['UID'];
		$flags = array();
		foreach ($data['FLAGS'] as $flag) {
			$flags[] = isset(self::$_knownFlags[$flag]) ? self::$_knownFlags[$flag] : $flag;
		}

		return new $this->_messageClass(array('handler' => $this, 'uid' => $uid, 'id' => $id, 'headers' => $header, 'flags' => $flags));
	}

	private function extract() {
	}
}