<?php

class Admin_Form_Product extends Zend_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('Product');

		$productName = new Zend_Form_Element_Text('productName');
		$productName->setRequired(true)
		//->setAttrib('size', '115px');		// MOD 20140429 Hieunm modify size
		;

		$productNo = new Zend_Form_Element_Text('productNo');
		$productNo->setRequired(true)
		//->setAttrib('size', '45px');		// ADD 20140429 Hieunm add size
		;
		//ADD 20140722 locpht start
		$makerProductNo = new Zend_Form_Element_Text('makerProductNo');
		$makerProductNo->setRequired(false)
		->setAttrib('size', '40px')
		;
		//ADD 20140722 locpht end

		$productBrief = new Zend_Form_Element_Textarea('productBrief');
		$productBrief->setRequired(true)
		//->setAttrib('cols', '113')			// MOD 20140429 Hieunm modify cols
        ->setAttrib('rows', '5');
		;

		$priceApplyStartDate = new Zend_Form_Element_Text('priceApplyStartDate');
		$priceApplyStartDate->setRequired(true)
		->setAttribs(array('class' => 'clsPriceApplyStartDate datepicker'))
		;

		$price = new Zend_Form_Element_Text('price[]');
		$price->setRequired(true)
		->setAttrib('class', 'txtNumber clsPrice')
		->setAttrib('id', 'price_0')
		;

		$priceIncludingTax = new Zend_Form_Element_Text('priceIncludingTax[]');
		$priceIncludingTax->setRequired(true)
		->setAttrib('readonly', 'readonly')
		->setAttrib('class', 'txt_disable txtNumber clsPriceIncludingTax')
		->setAttrib('id', 'priceIncludingTax_0')
		;

		$tax = new Zend_Form_Element_Text('tax[]');
		$tax->setRequired(true)
		->setAttrib('readonly', 'readonly')
		->setAttrib('class', 'txt_disable txtNumber clsTax')
		->setAttrib('id', 'tax_0')
		;

		$pointApplyStartDate = new Zend_Form_Element_Text('pointApplyStartDate');
		$pointApplyStartDate->setRequired(true)
		->setAttribs(array('class' => 'clsPointApplyStartDate datepicker'))
		;


		$magnificationPoint = new Zend_Form_Element_Text('magnificationPoint');
		$magnificationPoint->setRequired(true)
		->setAttribs(array('class' => 'txtNumber clsMagnificationPoint'))		// ADD 20140429 Hieunm add class
		;

		$shippingCheck = new Zend_Form_Element_Checkbox("shippingCheck");
		$shippingCheck ->setValue(1)
               ->removeDecorator('label')
               ->removeDecorator('HtmlTag');

		$shipping = new Zend_Form_Element_Radio('shipping');
		$shipping->addMultiOption('1','送料無料')
		->addMultiOption('2','送料込み')
		->addMultiOption('3','送料')
		->setValue('1')
		->setSeparator('');

		$shippingValue = new Zend_Form_Element_Text('shippingValue');
		$shippingValue->setAttrib("size", "10")		// MOD 20140429 Hieunm modify size
		->setAttrib("class", "txtNumber")
		;

		$stockCheck = new Zend_Form_Element_Checkbox("stockCheck");
		$stockCheck ->setValue(1)
               ->removeDecorator('label')
               ->removeDecorator('HtmlTag');

		$stock = new Zend_Form_Element_Radio('stock');
		$stock->addMultiOption('1','在庫無し')
		->addMultiOption('2','在庫あり')
		->addMultiOption('3','在庫数')
		->setValue('1')
		->setSeparator(' ');	// MOD 20140429 Hieunm modify a space size

		$stockValue = new Zend_Form_Element_Text('stockValue');
		$stockValue->setAttrib("size", "10")	// MOD 20140429 Hieunm modify size
		->setAttrib("class", "txtNumber")
		;		
		//ADD 20140722 locpht start

		$priceCond = new Zend_Form_Element_Checkbox("priceCond_0");
		$priceCond ->setValue(0)
        ->removeDecorator('label')
        ->removeDecorator('HtmlTag')
        ->setAttrib('class', 'clsPriceConditionClass')
		->setAttrib('id', 'priceCond_0');
		
		$quanRes = new Zend_Form_Element_Checkbox("quanRes_0");
		$quanRes ->setValue(0)
        ->removeDecorator('label')
        ->removeDecorator('HtmlTag')
        ->setAttrib('class', 'clsQuantityRestriction')
		->setAttrib('id', 'quanRes_0');
		
		$supplier = new Zend_Form_Element_Select('supplierCode');
		$supplier->setRequired(true);
		$supplier->removeDecorator('label')
         ->removeDecorator('HtmlTag')
         ->setAttribs(array('style' => 'width: 150px; height: 23px;'))
         ;
         
		//Get supplier list
		$serv = new Core_Service_MstClassService();
		$supplierData = $serv->getMstClassByItemTypeDispl(Core_Util_Const::SUPPLIER_ITEM_TYPE);
		
        $comboData = array(""=>"");
        foreach ($supplierData as $item) {
            $comboData[$item->getItemCd()] = $item->getItemName();
        }
        $supplier->setMultiOptions($comboData);
        //ADD 20140722 locpht end

		$categoryParent = new Zend_Form_Element_Select('categoryParent');
		$categoryParent->removeDecorator('label')
         ->removeDecorator('HtmlTag')
         ->setAttribs(array('style' => 'width: 150px; height: 23px;'))	// MOD 20140429 Hieunm add height, width
         ->setAttrib('onchange', 'changeCategory(this)')
          ->setAttribs(array('class' => 'catParent'))
         ;

        $srv = new Core_Service_MstCategoryService();
        $category = $srv->getByParentId();
        $listCategory = array(""=>"");
        foreach ($category as $item) {
            $listCategory[$item['category_id']] = $item['category_name'];
        }
        $categoryParent->setMultiOptions($listCategory," ");

		$categoryChild = new Zend_Form_Element_Select('categoryChild');
		$categoryChild->removeDecorator('label')
         ->removeDecorator('HtmlTag')
         ->setAttribs(array('style' => 'width: 150px; height: 23px;'))	// MOD 20140429 Hieunm add height, width
         ->setAttribs(array('class' => 'catChild'))
         ;

        $productDetailInfo = new Zend_Form_Element_Textarea('productDetailInfo');
		$productDetailInfo->setRequired(true)
		->setAttrib('cols', '50')
        ->setAttrib('rows', '4');
		;

		$this->addElements(array($productName, $productNo, $makerProductNo, $productBrief,
		                        $priceApplyStartDate, $price, $priceIncludingTax,
		                        $tax, $pointApplyStartDate, $magnificationPoint,
		                        $priceCond, $quanRes, $supplier,
		                        $categoryParent, $categoryChild,$productDetailInfo,
		                        $shippingCheck,$shipping,$shippingValue,
		                        $stockCheck,$stock,$stockValue));

		foreach ($this->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper')
			->removeDecorator('HtmlTag')
			->removeDecorator('Label')
			->removeDecorator("Errors");
			;
		}
	}
}