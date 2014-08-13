<?php

class Admin_Form_OrderSearch extends Zend_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('OrderSearchForm');

		$username = new Zend_Form_Element_Text('username');
		$username->addFilter('StripTags')
		->addFilter('StringTrim')
		;

		$orderStatus = new Zend_Form_Element_Select('orderStatus');
		$orderStatus->addFilter('StripTags')
		->addFilter('StringTrim')
		;
		
		//$orderStatus->setMultiOptions(Core_Util_Const::$ORDER_STATUS);
		
		$orderStatusChange = new Zend_Form_Element_Select('orderStatusChange');
		$orderStatusChange->addFilter('StripTags')
		->addFilter('StringTrim')
		;
		//$arr = Core_Util_Const::$ORDER_STATUS;
		//unset($arr[null]);
		//$orderStatusChange->setMultiOptions($arr);

		$this->addElements(array($username, $orderStatus, $orderStatusChange));

		foreach ($this->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper')
			->removeDecorator('HtmlTag')
			->removeDecorator('Label')
			->removeDecorator("Errors");
		}
	}
}