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

class Form_Login extends Zend_Form {

	public function init() {

		$this->setDecorators(array('FormElements','Form'));
		$this->setAttrib('id', 'loginform');
		$this->setAction('/index/login')->setMethod('post');

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Text('username', array(
                        'required'    => true,
                        'label'       => 'Nutzername:',
                        'description' => 'Geben Sie hier ihren Benutzernamen ein.',
                        'decorators'  => array(
                                'ViewHelper',
                                'Description',
                                'Label',
                                'Errors',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'div'))
		)
		)));

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Password('password', array(
                        'required'    => true,
                        'label'       => 'Passwort:',
                        'description' => 'Sesam, öffne dich!',
                        'decorators'  => array(
                                'ViewHelper',
                                'Description',
                                'Label',
                                'Errors',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'div'))
		)
		)));

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Checkbox('keep_login', array(
                        'required'    => true,
                        'label'       => 'Angemeldet bleiben',
                        'description' => 'Wenn\'s mal wieder länger dauert...',
                        'decorators'  => array(
                                'ViewHelper',
		new Zend_Form_Decorator_Label(array('placement' => 'append')),
                                'Description',
                                'Errors',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'div'))
		)
		)));

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Submit('login', array(
                        'required'   => true,
                        'label'      => 'Anmelden',
                        'decorators' => array(
                                'ViewHelper',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'div'))
		),
                        'style' => 'display:none'
                        )));
	}

}