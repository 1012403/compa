<?php
/**
 *
 * @author Nguyen
 *
 */
class Core_Models_MstUser extends Core_Models_MasterModel {
  public static $TOTAL_FIELD = 22;
  private $userId;
  private $userName;
  private $loginUsername;
  private $loginPassword;
  private $adminFlg;
  private $userClass;
  private $email;
  private $telNo;
  private $faxNo;

  private $changePassToken;
  private $changePassDate;

  private $userPoint;
  private $updatePointDate;
  private $areaCode;
  private $postNo;
  private $address;
  private $address2;
  private $salesId;
  private $tockenCookie;
  private $tockenCookieAdmin;

  //--- extra properties
  private $saleUsername;

  private $arrOrderCartInfo;
  private $arrQuotationCartInfo;


  //user shipping
  private $shippingSeq;
  private $shippingDesName;
  private $shippingPostNo;
  private $shippingAddress1;
  private $shippingAddress2;
  private $shippingTelNo;
  private $shippingFaxNo;
  private $shippingTransType;
  //private $shippingRemark;

  //userclass, adminclass,transtype,areaclass
  private $userTypeClass;
  private $adminTypeClass;
  private $transtypeClass;
  private $areaClass;

  //user salesId
  private $userSales;



  public function __construct($data = null) {
      parent::__construct($data);
    if ($data != null) {
      if ($data instanceof Zend_Db_Table_Row_Abstract
      || is_array($data) == TRUE) {
        $this->userId			= $this->getData($data, 'user_id');
        $this->userName			= $this->getData($data, 'user_name');
        $this->loginUsername	= $this->getData($data, 'login_username');
        $this->loginPassword	= $this->getData($data, 'login_password');
        $this->adminFlg			= $this->getData($data, 'admin_class');
        $this->userClass		= $this->getData($data, 'user_class');
        $this->email			= $this->getData($data, 'email');
        $this->telNo			= $this->getData($data, 'tel_no');
        $this->faxNo			= $this->getData($data, 'fax_no');
        $this->changePassToken	= $this->getData($data, 'change_pass_token');
        $this->changePassDate	= $this->getData($data, 'change_pass_date');

        $this->userPoint		= $this->getData($data, 'user_point');
        $this->updatePointDate	= $this->getData($data, 'update_point_date');
        $this->areaCode			= $this->getData($data, 'area_code');
        $this->postNo			= $this->getData($data, 'post_no');
        $this->address			= $this->getData($data, 'address');
        $this->address2			= $this->getData($data, 'address2');
        $this->salesId			= $this->getData($data, 'sales_id');
        $this->tockenCookie		= $this->getData($data, 'token_cookie');
        $this->tockenCookieAdmin= $this->getData($data, 'token_cookie_admin');

        //shipping
        /* $this->shippingDesName	= $this->getData($data, 'shipping_des_name');
        $this->shippingPostNo	= $this->getData($data, 'post_no');
        $this->shippingAddress1	= $this->getData($data, 'address1');
        $this->shippingAddress2	= $this->getData($data, 'address2');
        $this->shippingTelNo	= $this->getData($data, 'tel_no');
        $this->shippingFaxNo	= $this->getData($data, 'fax_no');
        $this->shippingTransType= $this->getData($data, 'trans_type'); */
      }
    }
  }

  /**
   * (non-PHPdoc)
   * @see Core_Models_Domain::toArray()
   */
  public function toArray() {
    $arr = parent::toArray();
    $arr["user_id"]				= $this->userId;
    $arr["user_name"]			= $this->userName;
    $arr["login_username"]		= $this->loginUsername;
    $arr["login_password"]		= $this->loginPassword;
    $arr["admin_class"]			= $this->adminFlg;
    $arr["user_class"]			= $this->userClass;
    $arr["email"]				= $this->email;
    $arr["tel_no"]				= $this->telNo;
    $arr["fax_no"]				= $this->faxNo;
    $arr["change_pass_token"]	= $this->changePassToken;
    $arr["change_pass_date"]	= $this->changePassDate;

    $arr["user_point"]			= $this->userPoint;
    $arr["update_point_date"]	= $this->updatePointDate;
    $arr["area_code"]			= $this->areaCode;
    $arr["post_no"]				= $this->postNo;
    $arr["address"]				= $this->address;
    $arr["address2"]			= $this->address2;
    $arr["sales_id"]			= $this->salesId;
    $arr["token_cookie"]		= $this->tockenCookie;
    $arr["token_cookie_admin"]	= $this->tockenCookieAdmin;

    //shipping
    /* $arr["shipping_des_name"]	= $this->shippingDesName;
    $arr["post_no"]				= $this->shippingPostNo;
    $arr["address1"]			= $this->shippingAddress1;
    $arr["address2"]			= $this->shippingAddress2;
    $arr["tel_no"]				= $this->shippingTelNo;
    $arr["fax_no"]				= $this->shippingFaxNo;
    $arr["trans_type"]			= $this->shippingTransType; */

    return $arr;
  }

  /**
   * getHeaderCsv
   * @return multitype:string
   */
  public static function getHeaderCsv() {
    $arrHeader = array();
    $arrHeader[] = "ユーザ名"; //Username
    $arrHeader[] = "ログインユーザＩＤ"; //LoginUsername
    $arrHeader[] = "ログインパスワード"; //LoginPassword
    $arrHeader[] = "メールアドレス"; //Email
    $arrHeader[] = "エリア"; //Area
    $arrHeader[] = "郵便番号"; //PostNo
    $arrHeader[] = "住所１"; //Address1
    $arrHeader[] = "住所２"; //Address2
    $arrHeader[] = "電話番号";//TelNo
    $arrHeader[] = "ＦＡＸ番号";//FaxNo
    $arrHeader[] = "営業担当者";//SalesId
    //
    $arrHeader[] = "ユーザ区分";//UserClass
    $arrHeader[] = "管理権限";//UserClass ->Adminclass
    $arrHeader[] = "獲得ポイント数";//UserPoint
    $arrHeader[] = "ポイント最終獲得日";//UpdatePointDate

    //shipping
    $arrHeader[] = "発送先名";//user_nameshipping_des_name
    $arrHeader[] = "郵便番号";//PostNo
    $arrHeader[] = "発送先住所１";//Address1
    $arrHeader[] = "発送先住所2";//Address2
    $arrHeader[] = "発送先電話番号";//TelNo
    $arrHeader[] = "発送先ＦＡＸ番号";//FaxNo
    $arrHeader[] = "発送方法";//trans_type

    return $arrHeader;

  }

  public function toCsvData() {
    $arrData = array();
    $arrData[] = $this->getUserName();
    $arrData[] = $this->getLoginUsername();
    $arrData[] = $this->getLoginPassword();
    $arrData[] = $this->getEmail();
    if ($this->getAreaClass() !== NULL){
      $arrData[] = $this->getAreaCode(). ": ". $this->getAreaClass();
    } else
      $arrData[] = $this->getAreaCode();
    $arrData[] = $this->getPostNo();
    $arrData[] = $this->getAddress();
    $arrData[] = $this->getAddress2();
    $arrData[] = $this->getTelNo();
    $arrData[] = $this->getFaxNo();
   // $arrData[] = $this->getSalesId();

    if ($this->getSalesId() !== null){
       $arrData[] = $this->getUserSales();
    } else {
    	$arrData[] = null;
    }

    if ($this->getUserTypeClass() !== null){
      $arrData[] = $this->getUserClass(). ": ".$this->getUserTypeClass();
    }else
      $arrData[] = $this->getUserClass();
    if ( $this->getAdminTypeClass() !== null){
      $arrData[] = $this->getAdminFlg().": ".$this->getAdminTypeClass(); //adminclass
    } else
      $arrData[] = $this->getAdminFlg();

    $arrData[] = $this->getUserPoint();
    $arrData[] = $this->getUpdatePointDate();

    $arrData[] = $this->getShippingDesName();
    $arrData[] = $this->getShippingPostNo();
    $arrData[] = $this->getShippingAddress1();
    $arrData[] = $this->getShippingAddress2();
    $arrData[] = $this->getShippingTelNo();
    $arrData[] = $this->getShippingFaxNo();
    if ($this->getTranstypeClass() !== null){
      $arrData[] = $this->getShippingTransType(). ": ".$this->getTranstypeClass();
    } else
      $arrData[] = $this->getShippingTransType();

    return $arrData;
  }

  public function createMstUserFromCsvRow($row){
    $csvAgent = new Core_Models_CsvAgent();
    $arr = $csvAgent->convertCsvRowToArray($row);
    if (count($arr) == self::$TOTAL_FIELD) {
      $mstUser = new Core_Models_MstUser();
      $mstUser->setUserName($arr[0]);
      $mstUser->setLoginUsername($arr[1]);
      $mstUser->setLoginPassword($arr[2]);
      $mstUser->setEmail($arr[3]);
      
      $areaCode = null;
      $areaCodeParts = explode ( ":", $arr[4] );
      if (! empty ( $areaCodeParts )) {
      	$areaCode = $areaCodeParts [0];
      }
      $mstUser->setAreaCode($areaCode);

      //print_r($areaCode);
      
      $mstUser->setPostNo($arr[5]);
      
      $address = str_replace(Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION,
													Core_Util_Const::NEW_LINE_ANOTATION, 
													$arr[6]);
	  $mstUser->setAddress(str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
													'\r\n',
													$address));
	  $address2	= str_replace(Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION,
													Core_Util_Const::NEW_LINE_ANOTATION, 
													$arr[7]);
	  $mstUser->setAddress2(str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
													'\r\n',
													$address2));
													
      $mstUser->setTelNo($arr[8]);
      $mstUser->setFaxNo($arr[9]);
      $mstUser->setSaleUsername($arr[10]);
      
      $userClass = null;
      $userClassParts = explode ( ":", $arr[11] );
      if (! empty ( $userClassParts )) {
      	$userClass = $userClassParts [0];
      }
      $mstUser->setUserClass($userClass);

      $adminClass = null;
      $adminClassParts = explode ( ":", $arr[12] );
      if (! empty ( $adminClassParts )) {
      	$adminClass = $adminClassParts [0];
      }
      $mstUser->setAdminFlg($adminClass); //adminclass
      
      $mstUser->setUserPoint($arr[13]);
      $mstUser->setUpdatePointDate($arr[14]);

      //shipping
      //$mstUser->setShippingSeq($arr[14]);
      $mstUser->setShippingDesName($arr[15]);
      $mstUser->setShippingPostNo($arr[16]);
      $mstUser->setShippingAddress1($arr[17]);
      $mstUser->setShippingAddress2($arr[18]);
      $mstUser->setShippingTelNo($arr[19]);
      $mstUser->setShippingFaxNo($arr[20]);
      
      $transType = null;
      $transTypeParts = explode ( ":", $arr[21] );
      if (! empty ( $transTypeParts )) {
      	$transType = $transTypeParts [0];
      }
      $mstUser->setShippingTransType($transType);
      //$mstUser->setShippingRemark($arr[22]);

      return $mstUser;
    } else {
      throw new Exception("Csv field not match to MstUser Obj");
    }
  }

  public function getUserId(){
    return $this->userId;
  }

  public function setUserId($userId){
    $this->userId = $userId;
  }

  public function getUserName(){
    return $this->userName;
  }

  public function setUserName($userName){
    $this->userName = $userName;
  }

  public function getLoginUsername(){
    return $this->loginUsername;
  }

  public function setLoginUsername($loginUsername){
    $this->loginUsername = $loginUsername;
  }

  public function getLoginPassword(){
    return $this->loginPassword;
  }

  public function setLoginPassword($loginPassword){
    $this->loginPassword = $loginPassword;
  }

  public function getAdminFlg(){
    return $this->adminFlg;
  }

  public function setAdminFlg($adminFlg){
    $this->adminFlg = $adminFlg;
  }

  public function getUserClass(){
    return $this->userClass;
  }

  public function setUserClass($userClass){
    $this->userClass = $userClass;
  }

  public function getEmail(){
    return $this->email;
  }

  public function setEmail($email){
    $this->email = $email;
  }

  public function getChangePassToken(){
    return $this->changePassToken;
  }

  public function setChangePassToken($changePassToken){
    $this->changePassToken = $changePassToken;
  }

  public function getChangePassDate(){
    return $this->changePassDate;
  }

  public function setChangePassDate($changePassDate){
    $this->changePassDate = $changePassDate;
  }

  public function getUserPoint(){
    return Core_Util_Helper::nullToZero($this->userPoint);
  }

  public function setUserPoint($userPoint){
    $this->userPoint = $userPoint;
  }

  public function getUpdatePointDate(){
    return $this->updatePointDate;
  }

  public function setUpdatePointDate($updatePointDate){
    $this->updatePointDate = $updatePointDate;
  }

  public function getAreaCode(){
    return $this->areaCode;
  }

  public function setAreaCode($areaCode){
    $this->areaCode = $areaCode;
  }

  public function getPostNo(){
    return $this->postNo;
  }

  public function setPostNo($postNo){
    $this->postNo = $postNo;
  }

  public function getAddress(){
    return $this->address;
  }

  public function setAddress($address){
    $this->address = $address;
  }

  public function getSalesId(){
    return $this->salesId;
  }

  public function setSalesId($salesId){
    $this->salesId = $salesId;
  }

  public function getTockenCookie(){
    return $this->tockenCookie;
  }

  public function setTockenCookie($tockenCookie){
    $this->tockenCookie = $tockenCookie;
  }

  public function getTockenCookieAdmin(){
    return $this->tockenCookieAdmin;
  }

  public function setTockenCookieAdmin($tockenCookieAdmin){
    $this->tockenCookieAdmin = $tockenCookieAdmin;
  }

  public function getTelNo(){
    return $this->telNo;
  }

  public function setTelNo($telNo){
    $this->telNo = $telNo;
  }

  public function getFaxNo(){
    return $this->faxNo;
  }

  public function setFaxNo($faxNo){
    $this->faxNo = $faxNo;
  }

  public function getShippingSeq(){
    return $this->shippingSeq;
  }

  public function setShippingSeq($shippingSeq){
    $this->shippingSeq = $shippingSeq;
  }

  /* public function getTransType(){
    return $this->transType;
  }

  public function setTransType($transType){
    $this->transType = $transType;
  } */
  //shipping


  public function getShippingDesName(){
    return $this->shippingDesName;
  }

  public function setShippingDesName($shippingDesName){
    $this->shippingDesName = $shippingDesName;
  }

  public function getShippingPostNo(){
    return $this->shippingPostNo;
  }

  public function setShippingPostNo($shippingPostNo){
    $this->shippingPostNo = $shippingPostNo;
  }

  public function getShippingAddress1(){
    return $this->shippingAddress1;
  }

  public function setShippingAddress1($shippingAddress1){
    $this->shippingAddress1 = $shippingAddress1;
  }

  public function getShippingAddress2(){
    return $this->shippingAddress2;
  }

  public function setShippingAddress2($shippingAddress2){
    $this->shippingAddress2 = $shippingAddress2;
  }

  public function getShippingTelNo(){
    return $this->shippingTelNo;
  }

  public function setShippingTelNo($shippingTelNo){
    $this->shippingTelNo = $shippingTelNo;
  }

  public function getShippingFaxNo(){
    return $this->shippingFaxNo;
  }

  public function setShippingFaxNo($shippingFaxNo){
    $this->shippingFaxNo = $shippingFaxNo;
  }

  public function getTranstypeClass(){
    return $this->transtypeClass;
  }

  public function setTranstypeClass($transtypeClass){
    $this->transtypeClass = $transtypeClass;
  }

  public function getShippingTransType(){
    return $this->shippingTransType;
  }

  public function setShippingTransType($shippingTransType){
    $this->shippingTransType = $shippingTransType;
  }
  //end shipping

  //userclass, adminclass
  public function getUserTypeClass(){
    return $this->userTypeClass;
  }

  public function setUserTypeClass($userTypeClass){
    $this->userTypeClass = $userTypeClass;
  }

  public function getAdminTypeClass(){
    return $this->adminTypeClass;
  }

  public function setAdminTypeClass($adminTypeClass){
    $this->adminTypeClass = $adminTypeClass;
  }

  public function getTranstype(){
    return $this->transtype;
  }

  public function setTranstype($transtype){
    $this->transtype = $transtype;
  }

  public function getAreaClass(){
    return $this->areaClass;
  }

  public function setAreaClass($areaClass){
    $this->areaClass = $areaClass;
  }
  //end class
  public function getSaleUsername(){
    return $this->saleUsername;
  }

  public function setSaleUsername($saleUsername){
    $this->saleUsername = $saleUsername;
  }

  // --- Order cart management ---
  /**
   * getArrOrderCart
   * @return Ambigous <multitype:, unknown>
   */
  public function getArrOrderCart() {
    if ($this->arrOrderCartInfo === null) {
      $this->arrOrderCartInfo = array();
    }

    return $this->arrOrderCartInfo;
  }

  /**
   * setArrOrderCart
   * @param array $arrOrderCartInfo
   */
  public function setArrOrderCart($arrOrderCartInfo) {
    $this->arrOrderCartInfo = $arrOrderCartInfo;
  }
  //user salse
  /**
   * getUserSales
   */
  public function getUserSales(){
    return $this->userSales;
  }

  /**
   * setUserSales
   * @param unknown $userSales
   */
  public function setUserSales($userSales){
    $this->userSales = $userSales;
  }
  /**
   * check is product added to order cart
   * @param Core_Models_MstProduct $product
   * @return boolean
   */
  public function isExistProductInOrderCart(Core_Models_MstProduct $product) {
    $arrOrderCart = $this->getArrOrderCart();
    $isExist = false;
    /* @var $currentOrdeCartInfo Core_Models_OrderCartInfo */
    foreach ($arrOrderCart as $key => $currentOrdeCartInfo) {
      if ($currentOrdeCartInfo->getProductId() == $product->getProductId()) {
        $isExist = true;
        break;
      }
    }

    return $isExist;
  }

  /**
   * if product is not exist in order cart => add new product with quantity = 1,
   * if product is exist => increase order cart info od the product to 1
   * @param Core_Models_MstProduct $product
   */
  public function addProductToOrderCardInfo(Core_Models_MstProduct $product) {
    /* @var $currentOrdeCartInfo Core_Models_OrderCartInfo */
    if ($this->isExistProductInOrderCart($product)) {
      $this->increaseProductNumberOrderCart($product, 1);
    } else {
      // add new order cart with product
      $orderCart = new Core_Models_OrderCartInfo();
      $orderCart->setUserId($this->getUserId());
      $orderCart->setQuantity(1);
      $orderCart->setProductId($product->getProductId());

      $arrOrderCart = $this->getArrOrderCart();
      $arrOrderCart[] = $orderCart;
      $this->setArrOrderCart($arrOrderCart);
    }
  }

  /**
   * remove product from arr of Order cart;
   * @param Core_Models_MstProduct $product
   */
  public function removeProductFromOrderCardInfo(Core_Models_MstProduct $product) {
    $arrOrderCart = $this->getArrOrderCart();
    /* @var $currentOrdeCartInfo Core_Models_OrderCartInfo */
    foreach ($arrOrderCart as $key => $currentOrdeCartInfo) {
      if ($currentOrdeCartInfo->getProductId() == $product->getProductId()) {
        unset($arrOrderCart[$key]);
        break;
      }
    }

    $this->setArrOrderCart($arrOrderCart);
  }

  /**
   * decrease num of product in order cart
   * @param Core_Models_MstProduct $product
   * @param unknown $num
   */
  public function dereaseProductNumFromOrderCart(Core_Models_MstProduct $product, $num) {
    $arrOrderCart = $this->getArrOrderCart();
    /* @var $currentOrdeCartInfo Core_Models_OrderCartInfo */
    foreach ($arrOrderCart as $key => $currentOrdeCartInfo) {
      if ($currentOrdeCartInfo->getProductId() == $product->getProductId()) {
        $currentOrdeCartInfo->decreaseQuantity($num);
        $arrOrderCart[$key] = $currentOrdeCartInfo;
        break;
      }
    }

    $this->setArrOrderCart($arrOrderCart);
  }

  /**
   * set Quantity Of product in OrderCart
   * @param Core_Models_MstProduct $product
   * @param unknown $quantity
   */
  public function setQuantityOfOrderCart(Core_Models_MstProduct $product, $quantity) {
    $arrOrderCart = $this->getArrOrderCart();
    /* @var $currentOrdeCartInfo Core_Models_OrderCartInfo */
    foreach ($arrOrderCart as $key => $currentOrdeCartInfo) {
      if ($currentOrdeCartInfo->getProductId() == $product->getProductId()) {
        $currentOrdeCartInfo->setQuantity($quantity);
        $arrOrderCart[$key] = $currentOrdeCartInfo;
        break;
      }
    }

    $this->setArrOrderCart($arrOrderCart);
  }

  /**
   * increase Product quantity in OrderCart
   * @param Core_Models_MstProduct $product
   * @param int $num
   */
  public function increaseProductNumberOrderCart(Core_Models_MstProduct $product, $num) {
    $arrOrderCart = $this->getArrOrderCart();
    /* @var $currentOrdeCartInfo Core_Models_OrderCartInfo */
    foreach ($arrOrderCart as $key => $currentOrdeCartInfo) {
      if ($currentOrdeCartInfo->getProductId() == $product->getProductId()) {
        $currentOrdeCartInfo->increaseQuantity($num);
        $arrOrderCart[$key] = $currentOrdeCartInfo;
      }
    }

    $this->setArrOrderCart($arrOrderCart);
  }

  /**
   * sizeOrderCart
   * @return number
   */
  public function sizeOrderCart() {
    $arrOrderCart = $this->getArrOrderCart();
    return count($arrOrderCart);
  }

  // --- Quotation cart management ---
  /**
   * getArrQuotationCart
   * @return Ambigous <multitype:, array>
   */
  public function getArrQuotationCart() {
    if ($this->arrQuotationCartInfo === null) {
      $this->arrQuotationCartInfo = array();
    }

    return $this->arrQuotationCartInfo;
  }

  /**
   * set ArrQuotationCart
   * @param array $arrQuotationCartInfo
   */
  public function setArrQuotationCart($arrQuotationCartInfo) {
    $this->arrQuotationCartInfo = $arrQuotationCartInfo;
  }

  /**
   * isExistProductInQuotationCart
   * @param Core_Models_MstProduct $product
   * @return boolean
   */
  public function isExistProductInQuotationCart(Core_Models_MstProduct $product) {
    $arrQuotationCart = $this->getArrQuotationCart();
    $isExist = false;
    /* @var $currentQuotationCartInfo Core_Models_QuotationCartInfo */
    foreach ($arrQuotationCart as $key => $currentQuotationCartInfo) {
      if ($currentQuotationCartInfo->getProductId() == $product->getProductId()) {
        $isExist = true;
        break;
      }
    }

    return $isExist;
  }

  /**
   * if product is not exist in quotation cart => add new product with quantity = 1,
   * if product is exist => increase order cart info od the product to 1
   * @param Core_Models_MstProduct $product
   */
  public function addProductToQuotationCardInfo(Core_Models_MstProduct $product) {
    /* @var $currentOrdeCartInfo Core_Models_OrderCartInfo */
    if ($this->isExistProductInQuotationCart($product)) {
      $this->increaseProductNumber($product, 1);
    } else {
      // add new quotation cart with product
      $quotationCart = new Core_Models_QuotationCartInfo();
      $quotationCart->setUserId($this->getUserId());
      $quotationCart->setQuantity(1);
      $quotationCart->setProductId($product->getProductId());

      $arrQuotationCart = $this->getArrQuotationCart();
      $arrQuotationCart[] = $quotationCart;
      $this->setArrQuotationCart($arrQuotationCart);
    }
  }

  /**
   * remove Product From Quotation Card Info
   * @param Core_Models_MstProduct $product
   */
  public function removeProductFromQuotationCardInfo(Core_Models_MstProduct $product) {
    $arrQuotationCart = $this->getArrQuotationCart();
    /* @var $currentQuotationCartInfo Core_Models_QuotationCartInfo */
    foreach ($arrQuotationCart as $key => $currentQuotationCartInfo) {
      if ($currentQuotationCartInfo->getProductId() == $product->getProductId()) {
        unset($arrQuotationCart[$key]);
        break;
      }
    }

    $this->setArrQuotationCart($arrQuotationCart);
  }

  /**
   * derease Product Quantity in QuotationCart
   * @param Core_Models_MstProduct $product
   * @param unknown $num
   */
  public function dereaseProductNumFromQuotationCart(Core_Models_MstProduct $product, $num) {
    $arrQuotationCart = $this->getArrQuotationCart();
    /* @var $currentQuotationCartInfo Core_Models_QuotationCartInfo */
    foreach ($arrQuotationCart as $key => $currentQuotationCartInfo) {
      if ($$currentQuotationCartInfo->getProductId() == $product->getProductId()) {
        $currentQuotationCartInfo->decreaseQuantity($num);
        $arrQuotationCart[$key] = $currentQuotationCartInfo;
        break;
      }
    }

    $this->setArrQuotationCart($arrQuotationCart);
  }

  /**
   * setQuantity Of product in Quotation Cart
   * @param Core_Models_MstProduct $product
   * @param unknown $quantity
   */
  public function setQuantityOfQuotationCart(Core_Models_MstProduct $product, $quantity) {
    $arrQuotationCart = $this->getArrQuotationCart();
    /* @var $currentQuotationCartInfo Core_Models_QuotationCartInfo */
    foreach ($arrQuotationCart as $key => $currentQuotationCartInfo) {
      if ($currentQuotationCartInfo->getProductId() == $product->getProductId()) {
        $currentQuotationCartInfo->setQuantity($quantity);
        $arrQuotationCart[$key] = $currentQuotationCartInfo;
        break;
      }
    }

    $this->setArrQuotationCart($arrQuotationCart);
  }

  /**
   * increase Product quantity of Quotation Cart
   * @param Core_Models_MstProduct $product
   * @param int $num
   */
  public function increaseProductNumberQuotationCart(Core_Models_MstProduct $product, $num) {
    $arrQuotationCart = $this->getArrQuotationCart();
    /* @var $currentQuotatinCartInfo Core_Models_QuotationCartInfo */
    foreach ($arrQuotationCart as $key => $currentQuotatinCartInfo) {
      if ($currentQuotatinCartInfo->getProductId() == $product->getProductId()) {
        $currentQuotatinCartInfo->increaseQuantity($num);
        $arrQuotationCart[$key] = $currentQuotatinCartInfo;
      }
    }

    $this->setArrQuotationCart($arrQuotationCart);
  }

  /**
   * size of QuotationCart
   * @return number
   */
  public function sizeQuotationCart() {
    $arrQuotationCart = $this->getArrQuotationCart();
    return count($arrQuotationCart);
  }

  public function addPoint($point) {
    //Core_Util_LocalLog::writeLog("old user point = " . $this->userPoint);
    $this->userPoint = $this->userPoint + $point;
    //Core_Util_LocalLog::writeLog("new user point = " . $this->userPoint);
  }

  /**
   * check is sale user
   * @return boolean
   */
  public function isSaleUser() {
    return $this->getUserClass() == '1' && $this->getAdminFlg() != '1';
  }

  public function getAddress2(){
    return $this->address2;
  }

  public function setAddress2($address2){
    $this->address2 = $address2;
  }
}
