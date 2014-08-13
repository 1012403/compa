<?php

class Admin_Form_Shipping extends Zend_Form
{
  public function __construct()
  {
    parent::__construct();
    $this->setName('Product');

    $desName = new Zend_Form_Element_Text('des_name');
    $desName->setRequired(true)
    ->addFilter('StripTags')
    ->addFilter('StringTrim')
    ->setAttribs(array('maxlength' => '100', 'id' => 'shipping_des_name'
    ))
    ;



    //ユーザ名

    $urlBase = Zend_Registry::get('url_base');
    $postNo = new Zend_Form_Element_Text('post_no');
    $postNo->setRequired(true)
    ->addFilter('StripTags')
    ->addFilter('StringTrim')
    ->setAttribs(array('id' => 'shipping_post_no',
        //,'onblur' => 'AjaxZip2.zip2addr(this,"address1","address1");'
        'onblur' => 'AjaxZip2.JSONDATA = "' . $urlBase . '/data";AjaxZip2.zip2addr(this,"address1","address1");',
        'onKeyUp' => 'AjaxZip2.JSONDATA = "' . $urlBase . '/data";AjaxZip2.zip2addr(this,"address1","address1");'
    ))
    ;

    $address1 = new Zend_Form_Element_Text('address1');
    $address1->setRequired(true)
    ->addFilter('StripTags')
    ->addFilter('StringTrim')
    ->setAttribs(
        array(
            'id' => 'shipping_address1',
            'style' => 'width: 100%;',
            'maxlength' => '250'
        )
    )
    ;

    $address2 = new Zend_Form_Element_Text('address2');
    $address2->setRequired(true)
    ->addFilter('StripTags')
    ->addFilter('StringTrim')
    ->setAttribs(
        array(
            'id' => 'shipping_address2',
            'style' => 'width: 100%;',
            'maxlength' => '250'
        )
    )
    ;

    $telNo = new Zend_Form_Element_Text('tel_no');
    $telNo->setRequired(true)
    ->addFilter('StripTags')
    ->addFilter('StringTrim')
    ->setAttribs(
        array(
            'id' => 'shipping_tel_no',
            'maxlength' => '15',
        )
    )
    ;

    $faxNo = new Zend_Form_Element_Text('fax_no');
    $faxNo->setRequired(true)
    ->addFilter('StripTags')
    ->addFilter('StringTrim')
    ->setAttribs(
        array(
            'id' => 'shipping_fax_no',
            'maxlength' => '15',
        )
    )
    ;

    //見積り状態
    $shippingMethod = new Zend_Form_Element_Select('shipping_method');
    $shippingMethod->removeDecorator('label')
         ->removeDecorator('HtmlTag')
         ->setAttribs(array('id' => 'shipping_method'))
         ;

    $this->addElements(array($desName, $postNo, $address1, $address2, $telNo, $faxNo, $shippingMethod));

    foreach ($this->getElements() as $element) {
      $element->removeDecorator('DtDdWrapper')
      ->removeDecorator('HtmlTag')
      ->removeDecorator('Label')
      ->removeDecorator("Errors");
      ;
    }
  }
}