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

class SZ_Mail_Helper {

	/**
	 * @name isValidEmail
	 * @param string Email to validate
	 * @param int Validation Level (optional; 1 = text, 2 = domain)
	 * @return boolean True if the email is a valid address, false otherwise
	 */

	static function isValidEmail($email, $level = 1) {
		//If there are any non-email parts
		if(strpos($email, " ") !== false) {
			//Remove them
			$email = substr($email, strrpos($email, " "));
			$email = str_replace(array('<', '>'), '', $email);
		}

		//Match against an email regex
		if(preg_match("/([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/i", $email) == 0) {
			return false;
		}

		//If requested, check the host of the email
		if($level == 2 ) {
			$host = substr($email, strrpos($email, "@"));
			if(!checkdnsrr($host, 'MX') || !checkdnsrr($host, 'A')) {
				return false;
			}
		}
		//Valid Email
		return true;
	}

	/**
	 * @name extractEmail
	 * @param string|array Emails to validate
	 * @return array Seperated emails
	 */
	static function extractEmail($emails) {
		//Init output array
		$output = array();
		//Trim email string
		$emails = trim($emails);
		//If the emails aren't in array form
		if(!is_array($emails)) {
			//Seperate emails (, or ;)
			if(strpos($emails, ',') !== false) {
				$emails = explodeOutQuote(',', $emails);
			} elseif(strpos($emails, ';') !== false) {
				$emails = explodeOutQuote(';', $emails);
			} else {
				$emails = array($emails);
			}
			//Trim all of the emails
			array_map('trim', $emails);
		}

		//Cycle through emails
		foreach($emails as $key => $email) {
			$email = trim($email);
			//If they are valid
			if(self::isValidEmail($email)) {
				//Reset name
				$name = "";
				//Only set the name if it exists
				if(strrpos($email, ' ') !== false) {
					$name = substr($email, 0, strrpos($email, ' '));
				}
				//Set the email address
				$address = substr($email, strrpos($email, ' '));
					
				//Add them to the array (in the place of the email)
				$output[$key]['name'] = str_replace('"', '', trim($name));
				$output[$key]['email'] = str_replace(array('<', '>'), "", trim($address));
				continue;
			} else {
				throw new SZ_Exception("Given email is not valid.");
			}
		}

		//Return array
		return $output;
	}

	/**
	 * @name explodeOutQuote
	 * @param string Seperator character or string
	 * @param string String to parse
	 * @return array Parsed seperated results
	 */
	private static function explodeOutQuote($seperator, $str) {
		//Init insideQuotes, output array, and part counter
		$insideQuotes = false;
		$output = array();
		$parts = 0;

		//Cycle through each character in the string
		for($char = 0; $char < strlen($str); $char++) {
			//Get character
			$c = substr($str, $char, 1);
			//Toggle qutoes if the char is a quote
			if($c == '"') {
				$insideQutoes = !$insideQutoes;
			}
			//If the char is a seperator (and outside quote) being new part
			if($c == $seperator && !$insideQutoes) {
				$parts++;
				continue;
			}
			//Otherwise, just add the char to the current part
			$output[$parts] .= $c;
		}
		//Return the parts
		return $output;
	}
}