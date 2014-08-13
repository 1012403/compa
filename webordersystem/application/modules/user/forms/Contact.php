<?php
/**
 * All Rights Reserved, Copyright (c) 株式会社アイ・エンター 2013
 * @author Hieunm
 */
class User_Form_Contact extends Zend_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('Contact');

		$title = new Zend_Form_Element_Text('contact_title');
		
		$title->setRequired(true)
		//->addValidator('NotEmpty')
		//->addValidator('StringLength', false, array('min' => 3, 'messages' => "You must be sure."))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'form-control')
		->setAttribs(array('style' => 'width:100%;'))
		->setAttribs(array('maxlength' => '40'))
		;

		$content = new Zend_Form_Element_Textarea('contact_content');
		$content->setRequired(true)
		//->addValidator('NotEmpty')
		//->addValidator('StringLength', false, array('min' => 3, 'messages' => "You must be sure."))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('class', 'form-control')
		->setAttribs(array('style' => 'width:100%; height:150px'))
		;

		//get contact class name
		$classServ = new Core_Service_MstClassService();
		$contactClass = $classServ->getMstClassByItemType(Core_Util_Const::CONTACT_CLASS);
		$contactClassName = array();
		foreach ($contactClass as $key=>$value){
			$contactClassName[$value->getItemCd()] = $value->getItemName(); 
		}
		
		$class = new Zend_Form_Element_Radio('contact_class');
		$class->setRequired(true)
		->addMultiOptions($contactClassName)
		->setSeparator('')
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttribs(array('style' => 'margin-left:20px; margin-right:5px; width: 20px; height: 15px'))
		->setValue("1");
		;
		
		$class_small = new Zend_Form_Element_Select('contact_class_small');
		$class_small->setRequired(true)
		->setLabel('分類')
		->addMultiOptions($contactClassName)
		->setSeparator('')
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttribs(array('style' => 'margin-left:20px; margin-right:5px; width: 150px; height: 20px'))
		->setValue("1");
		; 

		$this->addElements(array($title, $content, $class, $class_small));

		foreach ($this->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper')
			->removeDecorator('HtmlTag')
			->removeDecorator('Label')
			->removeDecorator("Errors");
			;
		}
	}
}