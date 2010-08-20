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

		/**
		 * Returns the folders'
		 *
		 *	attachments
		 *  id 
		 *  subject 
		 *  from
		 *  date
		 *  size
		 * {[{
		 *     "" : "" //
		 *   }, ...]}
		 *
		 * @param  string $folder
		 * @param  int    $offset
		 * @return array
		 */
		public function getMails($folder = 'INBOX', $offset = 1, $items = 15) {
			$mail = self::getMailbox();
			$mail->selectFolder($folder);
			return $mail->getList($offset, $items);
		}

		/**
		 * Returns an email
		 *
		 * {
		 *     "headers" : {
		 *                   "id"      : "",   //
		 *                   "date"    : "",   //
		 *                   "subject" : "",   //
		 *                   "from"    : "",   //
		 *                   "to"      : [""], //
		 *                   "cc"      : [""], //
		 *                   "bcc"     : [""], //
		 *                   "replyTo" : "id"  //
		 *                 },
		 *     "body" : {
		 *                "text" : "",
		 *                "html" : "",
		 *                "attachments" : [
		 *                  {
		 *                    "name"      : "",
		 *                    "id"        : 1234,
		 *                    "mime-type" : "image/png"
		 *                  }, ... ]
		 *              },
		 * }
		 *
		 * @param  string $root
		 * @return array
		 */
		public function getMail($id) {
			return self::getMailBox()->getMessage($id);
		}

		/**
		 * Returns the folders
		 *
		 * {[{
		 *     "name"   : "FolderName", // UTF-8 encoded (no ASCII, no UTF-7, etc.)
		 *     "global" : "/top/sub", // UTF-8 encoded (no ASCII, no UTF-7, etc.)
		 *     "type"   : "",           // one of INBOX, DRAFTS, SENT, SPAM, TRASH or not set
		 *     "flags"  : [...],        // array with NOINFERIORS, NOSELECT, MARKED, UNMARKED or not set.
		 *     "mails"  : 1234          // integer value
		 *     "unread" : 1234          // integer value
		 *   }, ...]}
		 *
		 * @param  string $root
		 * @return array
		 */
		public function getFolders($root = null) {
			$mail = self::getMailbox();
			return $mail->getFolders($root);
		}

		/**
		 * test
		 *
		 * @param string $a
		 * @return string
		 */
		public function fooBar($a = '') {
			return print_r($this::getMailbox()->foobar(),true);
		}

		private function getMailbox() {
			return new SZ_Mail_Manager_Imap(SZ_Setting_Manager::getMainImapConfig(1));;
		}
}