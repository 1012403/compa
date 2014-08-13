<?php

class Admin_Form_Quote extends Zend_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('Product');

		//ユーザ名
		$quotationUsername = new Zend_Form_Element_Text('quotationUsername');
		$quotationUsername->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttribs(array('style' => 'width: 130px; height: 22px; line-height: 0px; padding:3px'))
		;

		//見積り状態
		$quotationStatus = new Zend_Form_Element_Select('quotationStatus');
		$quotationStatus->removeDecorator('label')
         ->removeDecorator('HtmlTag')
         ->setAttribs(array('style' => 'width: 100px;'))
         ;

        //Get quotation status
		$serv = new Core_Service_MstClassService();
		$quotationStatusData = $serv->getMstClassByItemType(Core_Util_Const::QUOTATION_STATUS);

        $comboData = array(""=>"すべて");
        foreach ($quotationStatusData as $item) {
            $comboData[$item->getItemCd()] = $item->getItemName();
        }
        $quotationStatus->setMultiOptions($comboData);

        //営業担当者
		$quotationSale = new Zend_Form_Element_Select('quotationSale');
		$quotationSale->removeDecorator('label')
         ->removeDecorator('HtmlTag')
         ->setAttribs(array('style' => 'width: 100px;'))
         ;

        //Get quotation sale info
		$serv = new Core_Service_UserService();
		$saleUsers = $serv->getSaleUser();
        $comboData = array(""=>"すべて");
        foreach ($saleUsers as $item) {
            $comboData[$item["user_id"]] = $item["user_name"];
        }
        $quotationSale->setMultiOptions($comboData);

		$this->addElements(array($quotationUsername, $quotationStatus, $quotationSale));

		foreach ($this->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper')
			->removeDecorator('HtmlTag')
			->removeDecorator('Label')
			->removeDecorator("Errors");
			;
		}
	}
}