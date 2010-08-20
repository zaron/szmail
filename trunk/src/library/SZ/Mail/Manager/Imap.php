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

	private $count = -1;

	public function __construct($conf = null) {
		if($conf === null) {
			$conf = new Zend_Config_Ini(APPLICATION_PATH."/configs/accounts.ini",'tgmail');
		}
		$this->mail = new SZ_Mail_Storage_Imap($conf->imap);
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

	function getFolders($root = NULL) {
		return $this->mail->getFolders($root);
	}

	public function foobar() {
		$this->selectFolder();
	}

	public function getMail() {
		
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
			$result[] = $this->getMainInfoFromMessage($msg);
		}
		return $result;
	}

	public function selectFolder($folder = self::DEFAULT_FOLDER) {
		$this->mail->selectFolder($folder);
	}
	

	public function getMessage($uid, $folder = self::DEFAULT_FOLDER) {
		$this->selectFolder($folder);
		$message = $this->mail->getMessageByUid($uid);
		$result = $this->getMainInfoFromMessage($message);

		if($message->isMultiPart()) {
			foreach($message as $part) {
				$result = $this->getInfofromPart($part,$result);
			}
		} else {
			$result = $this->getInfofromPart($message,$result);
		}
		return $result;
	}


	public function getPaginator($default, $folder = self::DEFAULT_FOLDER) {
		$this->selectFolder($folder);
		$paginator = new Zend_Paginator(new SZ_Paginator_Adapter_Mail($this));
		$paginator->setItemCountPerPage($default);
		$paginator->setCacheEnabled(false);
		return $paginator;
	}

	public function sendMail($info, $isAnwser = false) {
		if(is_array($info)) {
			$info = (object) $info;
		} else if(!is_object($info)) {
			throw new SZ_Exception("");
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
	
	private function getMainInfoFromMessage(SZ_Mail_Message $msg) {
		$result = array();
		$result['uid'] = $msg->getUniqueId();
		$result['id'] = $msg->getId();
		$result['subject'] = isset($msg->subject)?$msg->subject:'';
		$result['from'] = isset($msg->from)?$msg->from:'';
		$result['to'] = isset($msg->to)?$msg->to:'';
		$result['cc'] = isset($msg->cc)?$msg->cc:'';
		$result['bcc'] = isset($msg->bcc)?$msg->bcc:'';
		$result['date'] = isset($msg->date)?$msg->date:'';
		$result['replyto'] = ($msg->headerExists('Reply-To'))?$msg->getHeader('Reply-To'):null;

		$result['unread'] = false;
		$result['new']    = false;
		$result['answered'] = false;
		if ($msg->hasFlag(Zend_Mail_Storage::FLAG_RECENT)) {
			$result['unread'] = true;
			$result['new']    = true;
		}
		if (!$msg->hasFlag(Zend_Mail_Storage::FLAG_SEEN)) {
			$result['unread'] = true;
		}
		if ($msg->hasFlag(Zend_Mail_Storage::FLAG_ANSWERED)) {
			$result['answered'] = true;
		}

		return $result;
	}
	
	private function getInfofromPart($part, $result) {
		$contentType = $part->headerExists('content-type')?$part->getHeader('content-type'):'';
		$transferEncoding =  $part->headerExists('content-transfer-encoding')?$part->getHeader('content-transfer-encoding'):'';

		if(empty($result['text']) && $this->isText($contentType)) {
			$result['text'] = $this->decode($part->getContent(),$transferEncoding);

		} else if (empty($result['html']) && $this->isHtml($contentType)) {
			$result['html'] = $this->decode($part->getContent(),$transferEncoding);

		} else if(empty($result['text']) && empty($contentType)) {
			$result['text'] = $this->decode($part->getContent(),$transferEncoding);
		}

		return $result;

		//TODO attachments
		//EVIL HACK MUAHAHAHHAHAH
		/*
		 if($this->isJpeg($part->contentType)) {
			$test = "<img src=\"data:image/jpeg;base64,";
			$test .= base64_encode(base64_decode($part->getContent()));
			$test .= "\" alt=\"test\"";
			$result['text'] .= $test;
			$result['html'] .= $test;
			}
			*/
	}

	private function isText($str) {
		return (strpos(trim($str),"text/plain")>-1);
	}

	private function isHtml($str) {
		return (strpos(trim($str),"text/html")>-1);
	}

	private function isJpeg($str) {
		return (strpos(trim($str),"image/jpeg")>-1);
	}

	private function decode($str,$enc) {
		switch (strtolower($enc)) {
			case Zend_Mime::ENCODING_QUOTEDPRINTABLE:
				$str = quoted_printable_decode($str);
				break;
			case Zend_Mime::ENCODING_BASE64:
				$str = base64_decode($str);
				break;
			case Zend_Mime::ENCODING_7BIT:
			case Zend_Mime::ENCODING_8BIT:
				break;
			default:
				return "Unknown encoding!";
		}
		if(mb_check_encoding($str, 'UTF-8')) return $str;
		return utf8_encode($str);
	}

	private function addReceiver($mail,$method,$receiver,$useName = true) {
		if(empty($receiver)) {
			return;
		}
		$_receiver = SZ_Mail_Helper::extractEmail($receiver);
		foreach($_receiver as $contact) {
			if($useName && !empty($contact['name'])) {
				$mail->$method($contact['email'],$contact['name']);
			} else {
				$mail->$method($contact['email']);
			}
		}
	}
}
