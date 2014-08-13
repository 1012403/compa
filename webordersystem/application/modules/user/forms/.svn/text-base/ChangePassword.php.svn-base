<?php

class User_Form_ChangePassword extends Zend_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('ChangePassword');

		$passwd = new Zend_Form_Element_Password('passwd');
		$passwd->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

		$passwdConfirm = new Zend_Form_Element_Password('passwdConfirm');
		$passwdConfirm->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

	 	$hiddenName = new Zend_Form_Element_Hidden("hiddenUsername");
		$hiddenName->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

		$name = new Zend_Form_Element_Text("username");
		$name->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		->setAttrib('disabled','disabled')
		;

		$hiddenToken = new Zend_Form_Element_Hidden("hiddenToken");
		$hiddenToken->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

		$this->addElements(array( $passwd, $passwdConfirm, $hiddenName, $hiddenToken,$name));

		foreach ($this->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper')
			->removeDecorator('HtmlTag')
			->removeDecorator('Label')
			->removeDecorator("Errors");
			;
		}
	}
}