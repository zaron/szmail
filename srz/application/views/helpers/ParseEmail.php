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

class Zend_View_Helper_ParseEmail extends Zend_View_Helper_Abstract {

	public function parseEmail($email) {

		if(strpos($email,'<')>-1) {
			$email = substr($email,0,strpos($email,'<'));
		}
		$email = str_replace('"','',$email);
		return $this->view->escape(trim($email));
	}
}