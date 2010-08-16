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

class MailController extends Zend_Controller_Action
{

	var $mail;

	public function init() {
		$this->mail = new SZ_Mail_Manager_Imap(SZ_Setting_Manager::getMainImapConfig());
		//$this->mail = new SZ_Mail_Manager_Stub();
		Zend_Registry::set("MailManager", $this->mail);

		//$this->_helper->layout()->setLayout("mail_layout");

		$context = $this->_helper->getHelper('contextSwitch');
		//$ajaxContext = $this->_helper->getHelper('AjaxContext');
		//$ajaxContext->addActionContext('index', 'json')
		$context->addActionContext('index', 'json')
		//  ->addActionContext('index', 'xml')

		//->addActionContext('process', 'json')
		->initContext();
		$this->_helper->contextSwitch()->setAutoJsonSerialization(true);
	}

	public function indexAction() {
		$folder = $this->getRequest()->getParam('folder');
		$folder = (empty($folder)) ?  'INBOX' : $folder;
		$request = $this->getRequest()->getParam('page');
		$itemsPerPage = ($this->getRequest()->getParam('mobile') == true)? 10: 15;
		$page = (empty($request))?1:$request;
		$paginator = $this->mail->getPaginator($itemsPerPage,$folder);
		$paginator->setCurrentPageNumber($page);
		$this->view->folderName = $folder;
		$this->view->mails = $paginator;
	}


	public function showAction() {
		$folder = $this->getRequest()->getParam('folder');
		$folder = (empty($folder)) ?  'INBOX' : $folder;
		$id = $this->getRequest()->getParam('id');
		$this->view->mail = $this->mail->getMessage($id,$folder);
		//$this->_helper->layout->disableLayout();
	}

	public function sendAction() {
		$form = new Form_SendMail();

		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$this->mail->sendMail((object) $form->getValues());
		}
		$this->view->uid = $this->getRequest()->getParam("uid");
		$this->view->sendform = $form;
		$this->_helper->layout->disableLayout();
	}

	public function answerAction() {
		throw new SZ_Exception("Answer email not implemented yet");

		$form = new Form_SendMail();

		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$this->mail->sendMail((object) $form->getValues());
		}
		$this->view->uid = $this->getRequest()->getParam("uid");
		$this->view->sendform = $form;
		$this->_helper->layout->disableLayout();
	}

	public function createFolderAction()
	{
		//imap_createmailbox
	}

	public function deleteFolderAction()
	{

	}
}