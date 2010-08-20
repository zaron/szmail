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
class SZ_Mail_Formatter {

	public function getMainInfoFromMessage(SZ_Mail_Message $msg) {
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

	public function getInfofromPart($part, $result) {
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
	}

	public function extractFolder($folders) {
		$result = array();
		$folderIterator = new RecursiveIteratorIterator($folders, RecursiveIteratorIterator::SELF_FIRST);
		foreach ($folderIterator as $localName => $folder) {
			$result[] = array (
				'name' => $localName,
				'global' => $folder->getGlobalName(),
				'type' => $this->resolveType($localName),
				'selectable' => $folder->isSelectable(),
				'isLeaf' => $folder->isLeaf(),
				'mails' => 23,   //TODO
				'unread' => 0	//TODO
			);
		}
		return $result;
	}

	private function resolveType($name) {
		$map = array(
			'INBOX'        => 'INBOX',
			'Papierkorb'   => 'TRASH',
			'Gesendet'     => 'SENT',
			'Spam'         => 'SPAM',
			'Entw&APw-rfe' => 'DRAFTS'
		);
		return (isset($map[$name]))?$map[$name]:"";
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
}