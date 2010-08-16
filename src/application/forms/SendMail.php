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

class Form_SendMail extends Zend_Form {

	public function init() {
		$this->setDecorators(array('FormElements','Form'));
		$this->setAction('/mail/send/')->setMethod('post');

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Select('identity', array(
                        'label'      => 'Absender:',
                        'decorators' => array(
                                'ViewHelper',
                                'Label',
                                'Errors',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'li', 'class' => 'item'))
		),
                        'MultiOptions' => SZ_Setting_Manager::getIdentities()
		)));

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Text('to', array(
                        'required'   => true,
                        'label'      => 'An:',
                        'decorators' => array(
                                'ViewHelper',
                                'Label',
                                'Errors',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'li', 'class' => 'item'))
		)
		)));

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Text('cc', array(
                        'label'      => 'CC:',
                        'decorators' => array(
                                'ViewHelper',
                                'Label',
                                'Errors',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'li', 'class' => 'item'))
		)
		)));

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Text('bcc', array(
                        'label'      => 'BCC:',
                        'decorators' => array(
                                'ViewHelper',
                                'Label',
                                'Errors',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'li', 'class' => 'item'))
		)
		)));

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Text('subject', array(
                        'label'      => 'Betreff:',
                        'decorators' => array(
                                'ViewHelper',
                                'Label',
                                'Errors',
		new Zend_Form_Decorator_HtmlTag(array('tag' => 'li', 'class' => 'item'))
		)
		)));

		// Element for identity selection.
		$this->addElement(new Zend_Form_Element_Textarea('content', array(
                        'decorators' => array(
                                'ViewHelper'
                                )
                                )));

                                // Element for identity selection.
                                $this->addElement(new Zend_Form_Element_Submit('submit', array(
                        'required'   => true,
                        'label'      => 'Absenden',
                        'decorators' => array(
                                'ViewHelper',
                                )
                                )));

                                /*
                                 $this->addElement('select', 'identity');
                                 $this->addElement('text', 'to',array('required'  => true));
                                 $this->addElement('text', 'cc');
                                 $this->addElement('text', 'bcc');
                                 $this->addElement('text', 'subject',array('required'  => true));
                                 $this->addElement('text', 'content',array('required'  => true));
                                 $this->addElement('submit','send');
                                 */
	}

}