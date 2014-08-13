<?php

class Admin_Form_Login extends Zend_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('Login');

		$loginId = new Zend_Form_Element_Text('login_username');
		$loginId->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

		$passwd = new Zend_Form_Element_Password('login_password');
		$passwd->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

		$checklogin = new Zend_Form_Element_Checkbox('check_login');
		$checklogin->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'checkLogin')
		;
		
		$this->addElements(array($loginId, $passwd,$checklogin));

		foreach ($this->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper')
			->removeDecorator('HtmlTag')
			->removeDecorator('Label')
			->removeDecorator("Errors");
			;
		}
	}
	
	public function setLoginUser($loginId){
		$this->login_username->setValue($loginId);
	}
	
	public function setPassword($password){
		//set cookie for password
		$this->login_password->renderPassword=true;
		$this->login_password->setValue($password);
	}
	
	public function setcheckLogin($check){
		$this->check_login->setValue($check);
	}
}