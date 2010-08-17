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

class Controller_Mail {

	private $map = array(
		'INBOX'        => 'INBOX',
		'Papierkorb'   => 'TRASH',
		'Gesendet'     => 'SENT',
		'Spam'         => 'SPAM',
		'Entw&APw-rfe' => 'DRAFTS'
	);

	/**
	 * Returns the folders
	 *
	 * @param  string $folder
	 * @param  int    $offset
	 * @return array
	 */
	public function getMails($folder = 'INBOX', $offset = 0)
	{
		$mail = $this::getMail();
		$paginator = $mail->getPaginator(15, $folder);
		$mails = array(); 
		/*foreach ($paginator as $email) {
			$mails[] = array(
        		"name" => $localName,
				"type" => $type,
				"mails" => $mails,
				"unread" => ($mails - $seen)
			);
		}*/
		return $paginator;
	}

	/**
	 * Returns the folders
	 *
	 * @param  string $root
	 * @return array
	 */
	public function getFolders($root = '')
	{
		$mail = $this::getMail();
		$folderIterator = new RecursiveIteratorIterator($mail->getFolders(), RecursiveIteratorIterator::SELF_FIRST);
		foreach ($folderIterator as $localName => $folder) {
			$seen = $mail->countMessages($folder->getGlobalName(),array(Zend_Mail_Storage::FLAG_SEEN));
			$mails = $mail->countMessages($folder->getGlobalName());
			$type = '';
			if(isset($this->map[$localName]))
			{
				$type = $this->map[$localName];
			}
			$folders[] = array(
        		"name" => $localName,
				"type" => $type,
				"mails" => $mails,
				"unread" => ($mails - $seen)
			);
		};
		return  $folders;
	}


	private function getMail() {
		return new SZ_Mail_Manager_Imap(SZ_Setting_Manager::getMainImapConfig());;
	}
}