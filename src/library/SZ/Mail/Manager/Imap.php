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

class SZ_Mail_Manager_Imap {

	const DEFAULT_FOLDER = 'INBOX';

	private $mail;
	private $outputFormatter;

	private $count = -1;

	public function __construct($conf = null) {
		if($conf === null) {
			$conf = new Zend_Config_Ini(APPLICATION_PATH."/configs/accounts.ini",'tgmail');
		}
		$this->mail = new SZ_Mail_Storage_Imap($conf->imap);
		$this->outputFormatter = new SZ_Mail_Formatter();
	}

	public function countMessages($folder = null, $flags = null) {
		if($folder !== null) {
			$count = 'n/a';
			try {
				$this->selectFolder($folder);
				if($flags !== null) $count = $this->mail->countMessages($flags);
				else $count = $this->mail->count();
			} catch(Exception $e){}
			$this->selectFolder();
			return $count;
		}
		if($this->count == -1) {
			$this->count = $this->mail->count();
		}
		return $this->count;
	}

	public function selectFolder($folder = self::DEFAULT_FOLDER) {
		$this->mail->selectFolder($folder);
	}

	function getFolders($root = NULL) {
		return $this->outputFormatter->extractFolder($this->mail->getFolders($root));
	}

	public function getList($from, $to) {
		$smaller = ($from>$to)? $to:$from;
		$bigger = ($from>$to)? $from:$to;

		$request = array();
		for($i = $smaller; $i<=$bigger;$i++) {
			$request[] = $i;
		}
		return $this->getListByArray($request);
	}

	public function getListByArray($request) {
		$result = array();
		foreach($this->mail->getMessagesWithUid($request) as $id => $msg) {
			$result[] = $this->outputFormatter->getMainInfoFromMessage($msg);
		}
		return $result;
	}
	

	public function getMessage($uid, $folder = self::DEFAULT_FOLDER) {
		$this->selectFolder($folder);
		$message = $this->mail->getMessageByUid($uid);
		$result = $this->outputFormatter->getMainInfoFromMessage($message);

		if($message->isMultiPart()) {
			foreach($message as $part) {
				$result = $this->outputFormatter->getInfofromPart($part,$result);
			}
		} else {
			$result = $this->outputFormatter->getInfofromPart($message,$result);
		}
		return $result;
	}

	public function sendMail($info, $isAnwser = false) {
		if(is_array($info)) {
			$info = (object) $info;
		} else if(!is_object($info)) {
			throw new SZ_Exception("Invalid input.");
		}
		$identity = SZ_Setting_Manager::getIdentity($info->identity);
		$mail = new Zend_Mail();
		$this->addReceiver($mail,"addTo",$info->to);
		$this->addReceiver($mail,"addCc",$info->cc);
		$this->addReceiver($mail,"addBcc",$info->bcc,false);
		$mail->setSubject($info->subject);
		$mail->setBodyText($info->content);

		if($isAnwser === true) {
			// TODO fetch mail and set respective "in reply to" and "references" headers
		}

		$identity->send($mail);
		//TODO copy mail to send folder
	}

	private function addReceiver($mail, $method, $receiver, $useName = true) {
		if(empty($receiver)) {
			return;
		}
		$_receiver = SZ_Mail_Helper::extractEmail($receiver);
		foreach($_receiver as $contact) {
			if($useName && !empty($contact['name'])) {
				$mail->$method($contact['email'], $contact['name']);
			} else {
				$mail->$method($contact['email']);
			}
		}
	}

	public function foobar() {
		$this->selectFolder();
		return $this->outputFormatter->extractFolder($this->mail->getFolders());
	}
}
