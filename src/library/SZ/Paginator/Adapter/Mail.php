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

class SZ_Paginator_Adapter_Mail implements Zend_Paginator_Adapter_Interface {

	protected $_manager;

	public function __construct(SZ_Mail_Manager_Imap $manager) {
		if($manager === NULL) {
			throw new SZ_Exception('No manager provided.');
		}
		$this->_manager = $manager;
	}

	public function count() {
		return $this->_manager->countMessages();
	}

	public function getItems($offset, $itemCountPerPage) {
		$from = $this->countMessages()-$offset;
		$to = $from - $itemCountPerPage;
		$to = ($to<1)?1:$to;
		return $this->_manager->getList($from,$to);
	}

}