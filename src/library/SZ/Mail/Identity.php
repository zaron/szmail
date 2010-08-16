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

class SZ_Mail_Identity {

	var $name;
	var $email;
	var $config;

	function __construct($name, $email, $config) {
		$this->name = $name;
		$this->email = $email;
		$this->config = $config;
	}

	public function send(Zend_Mail $mail) {
		$smtp = new Zend_Mail_Transport_Smtp($this->config['host'], $this->config);
		$mail->setFrom($this->email, $this->name);
		$mail->addHeader("X-MailGenerator",SZ_Version::getUserAgent());
		$mail->setMessageId($this->generateMessageId());
		$mail->send($smtp);
	}

	private function generateMessageId() {
		return "<".md5(uniqid(microtime())).".szmail".substr($this->email,strpos($this->email,"@")).">";
	}

}
