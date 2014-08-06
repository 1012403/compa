<?php
require_once 'Zend/Controller/Action.php';

class IndexController extends Zend_Controller_Action{
	public function indexAction() {
		$this->view->assign('title', 'Hello Zend, I am Daisy');
		$this->view->assign('welcome','Welcome to my site. This site is built using Zend Framework. Enjoy it!');
	    $this->view->assign('webmaster','Daisy');
	}
}
?>