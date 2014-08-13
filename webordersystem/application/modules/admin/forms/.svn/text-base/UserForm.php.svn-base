<?php
class Admin_Form_UserForm extends Zend_Form {
  public function __construct() {
    parent::__construct ();
    $this->setName ( 'edit_user' );
    $this->setAction('');

    $length = '50';
    $class = 'class';

    $mode = new Zend_Form_Element_Hidden ( 'mode' );
    $id = new Zend_Form_Element_Hidden ( 'id' );

    $notEmpty1 = new Zend_Validate_NotEmpty();
    $notEmpty1->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W001, array('ユーザ名')));
    $string_length1 = new Zend_Validate_StringLength();
    $string_length1->setMax(150);
    $string_length1->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W002, array('ユーザ名', '150')));
    $user_name = new Zend_Form_Element_Text ( 'user_name' );
    $user_name->setRequired ( true )
      ->setAttribs(array(
        'size' => $length
      ))
      ->addValidator($notEmpty1, true)
      ->addValidator($string_length1, true);

    $notEmpty2 = new Zend_Validate_NotEmpty();
    $notEmpty2->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W001, array('ユーザＩＤ')));
    $string_length2 = new Zend_Validate_StringLength();
    $string_length2->setMax(50);
    $string_length2->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W002, array('ユーザＩＤ', '50')));
    $login_username = new Zend_Form_Element_Text ( 'login_username' );
    $login_username->setRequired ( true )
      ->setAttribs(array(
        'size' => $length
      ))
      ->addValidator($notEmpty2, true)
      ->addValidator($string_length2, true);

    $notEmptypw = new Zend_Validate_NotEmpty();
    $notEmptypw->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W001, array('パスワード')));
    $login_password = new Zend_Form_Element_Text ( 'login_password' );
    $login_password->setRequired ( true )
      ->setAttribs(array(
        'size' => $length,
        'placeholder' => 'パスワード'
        //,'readonly' => true
      ))
      ->addValidator($notEmptypw, true);


    $notEmpty6 = new Zend_Validate_NotEmpty();
    $notEmpty6->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W001, array('メールアドレス')));
    $string_length3 = new Zend_Validate_StringLength();
    $string_length3->setMax(50);
    $string_length3->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W002, array('メールアドレス', '50')));

    $email_error_msg = Core_Util_Messages::getMessage(Core_Util_Messages::W003, array('メールアドレス'));
    $validator_email = new Core_Validate_EmailAddress();
    $validator_email->set_error_msg($email_error_msg);

    $email = new Zend_Form_Element_Text ( 'email' );
    $email->setRequired ( true )
      ->setOptions(array(
        'filters' => array(
            'StringTrim',
            'StripTags',
        )
      ))
      ->setAttribs(array(
        'size' => $length
      ))
      //->addErrorMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W003, array('電子メール')))
      ->addValidator($notEmpty6, true)
      ->addValidator($string_length3, true)
      ->addValidator($validator_email, true);

    $notEmpty3 = new Zend_Validate_NotEmpty();
    $notEmpty3->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W001, array('エリア')));
    $area_code = new Zend_Form_Element_Select ( 'area_code' );
    $area_code->addMultiOption('', '')
      ->setRequired ( true )
      ->setAttribs(array(
        'class' => $class
      ))
      ->addValidator($notEmpty3, true);

    $notEmpty4 = new Zend_Validate_NotEmpty();
    $notEmpty4->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W001, array('郵便番号')));

    //$regex = new Zend_Validate_Regex(array('pattern' => '(\d{3}\-\d{4})'));
    //$regex->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W006, array('郵便番号')));

    $string_length4 = new Zend_Validate_StringLength();
    $string_length4->setMax(20);
    $string_length4->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W002, array('郵便番号', '20')));
    $post_no = new Zend_Form_Element_Text ( 'post_no' );
    $urlBase = Zend_Registry::get('url_base');
    $post_no->setRequired ( true )
      ->setAttribs(array(
        'onblur' => 'AjaxZip2.JSONDATA = "' . $urlBase . '/data";AjaxZip2.zip2addr(this,"address","address");',
        'onKeyUp' => 'AjaxZip2.JSONDATA = "' . $urlBase . '/data";AjaxZip2.zip2addr(this,"address","address");'
      ))
      ->addValidator($notEmpty4, true)
      //->addValidator($regex, true)
      ->addValidator($string_length4, true);

    /* $address_search = new Zend_Form_Element_Button( 'address_search' );
    $address_search->setLabel('住所検索');
    $address_search->setAttribs(array(
        'class' => 'button searchLog',
        'onclick' => 'AjaxZip2.zip2addr("post_no","address","address");'
      )); */

    $notEmpty5 = new Zend_Validate_NotEmpty();
    $notEmpty5->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W001, array('住所１')));
    $address = new Zend_Form_Element_Textarea ( 'address' );
    $address->setRequired ( true )
      ->setAttrib('rows', 4)
      ->addValidator($notEmpty5, true);

    $address2 = new Zend_Form_Element_Textarea ( 'address2' );
    $address2->setRequired ( false )
    ->setAttrib('rows', 4);

    $tel_no = new Zend_Form_Element_Text ( 'tel_no' );
    $tel_no->setAttribs(array(
        'maxlength' => 15,
        'class' => 'txtJustNumber'
      ));

    $fax_no = new Zend_Form_Element_Text ( 'fax_no' );
    $fax_no->setAttribs(array(
        'maxlength' => 15,
        'class' => 'txtJustNumber'
    ));

    $sales_id = new Zend_Form_Element_Select ( 'sales_id' );
    $sales_id->addMultiOption('', '');

    $user_class = new Zend_Form_Element_Select ( 'user_class' );
    $user_class->addMultiOption('', '');

    $admin_class = new Zend_Form_Element_Radio ( 'admin_class' );
    $admin_class->setSeparator('&nbsp;');
    $admin_class->setAttribs(array(
        'class' => 'admin_class'
    ));

    $digits2 = new Zend_Validate_Digits();
    $digits2->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W004, array('ポイント数')));
    $user_point = new Zend_Form_Element_Text ( 'user_point' );
    $user_point->setRequired ( false )
      ->setAttribs(array(
        'class' => 'number'
      ))
      ->addValidator($digits2, true);

    $validator_date = new Zend_Validate_Date(array('format' => 'yyyy/mm/dd'));
    $validator_date->setMessage(Core_Util_Messages::getMessage(Core_Util_Messages::W005, array('最終獲得日')));
    $update_point_date = new Zend_Form_Element_Text ( 'update_point_date' );
    $update_point_date->setRequired ( false )
      ->setAttribs(array(
        'placeholder' => 'yyyy/mm/dd',
        'class' => 'clsPriceApplyStartDate datepicker'
      ))
      ->addValidator($validator_date, true);


    /*$submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('更新');
    $submit->setAttribs(array(
      'class' => 'button2 searchLog',
      'style' => 'color:black;'
    ));*/

    $this->addElements ( array ( $mode,$id, $user_name, $login_username, $login_password, $area_code, $post_no, //$address_search,
        $address, $address2, $tel_no, $fax_no, $sales_id, $user_class, $admin_class, $email,  $user_point, $update_point_date) );


    foreach ($this->getElements() as $element) {
      $element->removeDecorator('DtDdWrapper')
      ->removeDecorator('HtmlTag')
      ->removeDecorator('Label')
      ->removeDecorator("Errors");
    }

  }

  public function initDefaultData($data) {
    $arrAreaCode 	= $data['area_code'];
    $area_code = $this->area_code;
    foreach ($arrAreaCode as $key => $areaCode) {
      $area_code->addMultiOption($areaCode->getItemCd(), $areaCode->getItemName());
    }

    $arrSalesId 	= $data['sales_id'];
    $sales_id = $this->sales_id;
    foreach ($arrSalesId as $key => $salesId) {
      $sales_id->addMultiOption($salesId->getUserId(), $salesId->getUserName());
    }

    $arrUserclass = $data['user_class'];
    $user_class = $this->user_class;
    foreach ($arrUserclass as $key => $userclass) {
      $user_class->addMultiOption($userclass->getItemCd(), $userclass->getItemName());
    }

    $arrAdminclass	= $data['admin_class'];
    $admin_class = $this->admin_class;
    foreach ($arrAdminclass as $key => $adminclass) {
      $admin_class->addMultiOption($adminclass->getItemCd(), ' '.$adminclass->getItemName());
    }
    $this->admin_class->setValue(Core_Util_Const::ADMIN_TYPE_NO);
  }

  public function setModeValue($mode) {
    $this->mode->setValue($mode);
  }
  public function setLoginPasswordValue($pass) {
    $this->login_password->setValue($pass);
  }
  public function setValue($data) {
    $user = $data['user'];
    //$user = new Core_Models_MstUser();
    $this->id->setValue($user->getUserId());
    $this->user_name->setValue($user->getUsername());
    $this->login_username->setValue($user->getLoginUsername());
    //$this->login_password->setValue($user->getLoginPassword());
    $this->area_code->setValue($user->getAreaCode());
    $this->post_no->setValue($user->getPostNo());
    $this->address->setValue($user->getAddress());
    $this->address2->setValue($user->getAddress2());
    $this->tel_no->setValue($data['tel_no']);
    $this->fax_no->setValue($data['fax_no']);
    $this->sales_id->setValue($user->getSalesId());
    $this->user_class->setValue($user->getUserClass());
    $this->admin_class->setValue($user->getAdminFlg());
    $this->email->setValue($user->getEmail());
    $this->user_point->setValue($user->getUserPoint());
    $this->update_point_date->setValue(str_replace('-','/', $user->getUpdatePointDate()));
  }
}