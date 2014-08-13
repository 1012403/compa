<?php

class User_Form_SendLink extends Zend_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('SendLink');

		$loginId = new Zend_Form_Element_Text('login_username');
		$loginId->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

		$email = new Zend_Form_Element_Text('email');
		$email->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

		$passToken = new Zend_Form_Element_Text('change_pass_token');
		$passToken->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'textLogin')
		;

		$this->addElements(array($loginId, $email, $passToken));

		foreach ($this->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper')
			->removeDecorator('HtmlTag')
			->removeDecorator('Label')
			->removeDecorator("Errors");
			;
		}
	}
}