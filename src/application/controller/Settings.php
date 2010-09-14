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

class Controller_Settings {

	/**
	 * 
	 * @param array $key
	 * @param array $value
	 *
	 */
	public function setValue($key, $value) {
		SZ_Setting_Manager::setValue($key,$value);	
	}
	
	/**
	 * 
	 * @param array $key
	 * @return array
	 */
	public function getValue($key) {
		return SZ_Setting_Manager::getValue($key);
	}
	
	public function deleteValue($key) {
		SZ_Setting_Manager::deleteValue($key);	
	}
	
}