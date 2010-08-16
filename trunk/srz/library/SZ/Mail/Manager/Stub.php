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

class SZ_Mail_Manager_Stub extends SZ_Mail_Manager_Imap {

	var $sleeptime = 2;

	var $_mails;


	public function __construct() {
		$apppath = APPLICATION_PATH;
		require_once($apppath."/configs/testdata.php");
		$testData = getTestData();
		$this->_mails = $testData['mails'];
	}

	public function count() {
		return sizeof($this->_mails);
	}

	public function getList($from, $to) {
		//sleep($this->sleeptime);

		$smaller = ($from>$to)? $to:$from;
		$bigger = ($from>$to)? $from:$to;

		$result = array();
		for($i = $smaller; $i<=$bigger;$i++) {
			$result[] = $this->_mails[$i];
		}
		return array_reverse($result);
	}

	public function getMessage($uid) {
		sleep($this->sleeptime);

		foreach($this->_mails as $mail) {
			if($mail['uid'] == $uid) {
				return $mail;
			}
		}
		throw new SZ_Exception("Message not found.");
	}

	public function sendMail($info) {
		throw new SZ_Exception("Stub doesn't support send mail yet");
	}
}