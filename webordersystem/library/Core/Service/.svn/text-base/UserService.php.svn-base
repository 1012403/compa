<?php
/**
 *
 * @author Nguyen
 *
 */
class Core_Service_UserService extends Core_Service_Abstract {

  private $userDb;

  public function __construct() {
    parent::__construct();
    $this->userDb = new Core_Db_MstUserDb();
  }

  //login
  /**
   * login
   * @param Core_Models_MstUser $user
   * @return Core_Models_MstUser
   */
  public function authorize(Core_Models_MstUser $user) {
    try {
      $db = $this->userDb;
      $userAuth = $db->authorize($user);
      if ($userAuth !== false && $userAuth !== null) {

        // get order cart and quotation cart for login user
        $arrOrderCart = $this->getOrderCartForUser($userAuth);
        $arrQuotationCart = $this->getQuotationCartForUser($userAuth);
        $userAuth->setArrOrderCart($arrOrderCart);
        $userAuth->setArrQuotationCart($arrQuotationCart);
      }

      return $userAuth;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }
  //login Session
  /**
   * loginSession
   * @param Core_Models_MstUser $user
   * @return Core_Models_MstUser
   */
  public function authorizeSession(Core_Models_MstUser $user) {
    try {
      $db = $this->userDb;
      $userAuth = $db->authorizeSession($user);
      if ($userAuth !== false && $userAuth !== null) {

        // get order cart and quotation cart for login user
        $arrOrderCart = $this->getOrderCartForUser($userAuth);
        $arrQuotationCart = $this->getQuotationCartForUser($userAuth);
        $userAuth->setArrOrderCart($arrOrderCart);
        $userAuth->setArrQuotationCart($arrQuotationCart);
      }

      return $userAuth;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

  //loginAdmin
  public function loginAdmin(Core_Models_MstUser $user) {
    try {
      $db = $this->userDb;
      $userAdmin = $db->loginAdmin($user);
      return $userAdmin;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }
  //loginAdminSession
  public function loginAdminSession(Core_Models_MstUser $user) {
    try {
      $db = $this->userDb;
      $userAdmin = $db->loginAdminSession($user);
      return $userAdmin;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }
  ////sendlink
  /**
   *
   * @param unknown $username
   * @return Core_Models_MstUser
   */
  public function getUserByUsername($username) {
    try {
      $db = $this->userDb;
      return $db->getUserByUsername($username);
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

  public function getUserById($userId) {
    try {
      $db = $this->userDb;
      return $db->getUserById($userId);
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

  public function getUserByCookie($cookie) {
    try {
      $db = $this->userDb;
      return $db->getUserByCookie($cookie);
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

  public function getAdminByCookie($cookie) {
    try {
      $db = $this->userDb;
      return $db->getAdminByCookie($cookie);
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

  public function updateTokenDateSendlink($username) {
    try {
      $token =md5($this->getRandString(Core_Util_Const::NUMBER_TOCKEN));
      $db = $this->userDb;
      if($db->updateTokenDateSendlink($username, $token)) {
        return $token;
      } else {
        return null;
      }
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

  /**
   *
   * @param unknown $length
   * @return string
   */
  public function getRandString($length) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@";
    $str="";
    $size = strlen ( $chars );
    for($i = 0; $i < $length; $i ++) {
      $str .= $chars [rand ( 0, $size - 1 )];
    }
    return $str;
  }

  /**
   *
   * @param unknown $username
   * @param unknown $email
   * @return Ambigous <object, unknown, boolean>|boolean
   */
  public function checkEmail($username, $email) {
    try {
      $db = $this->userDb;
      return $db->checkEmail($username, $email);
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

  /**
   * change pass
   * @param unknown $username
   * @param unknown $password
   * @return number|boolean
   */
  public function changePasswd($username, $password) {
    try {
      $db = $this->userDb;
      return $db->changePasswd($username, $password);
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }
  /**
   *
   * @param unknown $username
   * @param unknown $token
   * @return Ambigous <Ambigous, object, unknown, boolean>|boolean
   */
  public function checkToken($username, $token) {
    try {
      $db = $this->userDb;
      return $db->checkToken($username, $token);
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }
  /**
   *
   * @param unknown $date
   * @return boolean
   */
  public function checkPasswdDate($date) {
    try {
      $db = $this->userDb;
      return $db->checkPasswdDate($date);
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

  /**
   * get Order Cart For User
   * @param Core_Models_MstUser $user
   * @return Ambigous <NULL, Ambigous, multitype:, multitype:unknown >|NULL
   */
  public function getOrderCartForUser(Core_Models_MstUser $user) {
    if ($user !== null) {
      $orderCartDb = new Core_Db_OrderCartDb();
      $arrOrderCart = $orderCartDb->getOrderCartInfoForUser($user);
      return $arrOrderCart;
    }

    return null;
  }

  /**
   * get Order Cart For User
   * @param Core_Models_MstUser $user
   * @return Ambigous <NULL, Ambigous, multitype:, multitype:unknown >|NULL
   */
  public function getQuotationCartForUser(Core_Models_MstUser $user) {
    if ($user !== null) {
      $quotationCartDb = new Core_Db_QuotationCartDb();
      $arrQuotationCart = $quotationCartDb->getQuotationCartInfoForUser($user);
      return $arrQuotationCart;
    }
    return null;
  }

  public function queryAllUserManager(){
    try {
      $db = $this->userDb;
      $arruser = $db->queryAllUserManager();
      return $arruser;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }

  public function queryAllUser($paginatorData, $user_name=null, $area_code=null, $tel_no=null, $sales_id=null) {
    try {
      $db = $this->userDb;
      $arruser = $db->queryAllUser($paginatorData, $user_name, $area_code, $tel_no, $sales_id);
      $arrresult = array();
      if ($arruser != null) {
        foreach ($arruser as $user){
          $user_detail = array();
          $user_detail['user'] = new Core_Models_MstUser($user);
          $user_detail['tel_no'] = Core_Util_Helper::getDataRow($user, 'tel_no');
          $user_detail['user_type_name'] = Core_Util_Helper::getDataRow($user, 'user_type_name');
          $user_detail['admin_type_name'] = Core_Util_Helper::getDataRow($user, 'admin_type_name');
          $user_detail['sales_name'] = Core_Util_Helper::getDataRow($user, 'sales_name');
          $arrresult[] = $user_detail;
        }
      }
      return $arrresult;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }


  public function queryAllUserForExport( $user_name=null, $area_code=null, $tel_no=null, $sales_id=null) {
    try {
      $db = $this->userDb;
      $arruser = $db->queryAllUserForExport( $user_name, $area_code, $tel_no, $sales_id);

      return $arruser;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }


  public function queryCountAllUser($user_name=null, $area_code=null, $tel_no=null, $sales_id=null) {
    try {
      $db = $this->userDb;
      $countuser = $db->queryCountAllUser($user_name, $area_code, $tel_no, $sales_id);
      return Core_Util_Helper::getDataRow($countuser, 'count');;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return 0;
    }
  }

  public function queryUserDetail($user_id) {
    try {
      $db = $this->userDb;
      $user = $db->queryUserDetail($user_id);
      $user_detail = null;
      if ($user != null) {
        $user_detail = array();
        $user_detail['user'] = new Core_Models_MstUser($user);
        $user_detail['tel_no'] = Core_Util_Helper::getDataRow($user, 'tel_no');
        $user_detail['fax_no'] = Core_Util_Helper::getDataRow($user, 'fax_no');
        $user_detail['user_type_name'] = Core_Util_Helper::getDataRow($user, 'user_type_name');
        $user_detail['admin_type_name'] = Core_Util_Helper::getDataRow($user, 'admin_type_name');
        $user_detail['sales_name'] = Core_Util_Helper::getDataRow($user, 'sales_name');
      }
      return $user_detail;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }

  public function insertModelUser($userdata){
    try {
      //Core_Util_LocalLog::writeLog("insertModelUser");

      if ( Core_Util_Helper::isEmpty($userdata['update_point_date']) ) {// blank
        $userdata['update_point_date'] = null;
      }
      $user = new Core_Models_MstUser($userdata);
      $user->setDeleteFlg(0);
      if ( Core_Util_Helper::isEmpty($user->getUserPoint()) ) {
        $user->setUserPoint("0");
      }
      $db = $this->userDb;
      $this->beginTransaction();


      $user_id = $db->queryNextUserId();
      $user->setUserId($user_id);
      $idUser = $db->insertRecord($user);

      $this->saveShippingParams($idUser, $userdata);
      //$userdata = $this->unsetShippingParams($userdata);

      //$user_shipping_db = new Core_Db_MstUserShippingDb();
      //$user_shipping->setUserId($user_id);
      //$user_shipping->setShippingSeq(1);
      //$user_shipping_db->insertRecord($user_shipping);
      //Core_Util_LocalLog::writeLog("insert success");
      $this->commit();
      return $user_id;
    } catch (Exception $e) {
      $this->rollBack();
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }

  public function updateUserFormData($id, $data){
    try {


      $db = $this->userDb;
      $this->beginTransaction();

      $this->saveShippingParams($id, $data);
      $data = $this->unsetShippingParams($data);

      $where = "user_id = ".$id;
      $data = Core_Util_Helper::createMetadataForUpdateRow($data);
      if ( Core_Util_Helper::isEmpty($data['update_point_date']) ) {// blank
        $data['update_point_date'] = null;
      }

      if ( Core_Util_Helper::isEmpty($data['user_point']) ) {// blank
        $data['user_point'] = "0";
      }

      $db->update($data, $where);

      $this->commit();
      return true;
    } catch (Exception $e) {
      $this->rollBack();
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return false;
    }
  }

  private function unsetShippingParams($arr) {
    $total = $arr['totalShipping'];
    for ($i = 0; $i <= $total; $i++) {
      if (isset($arr['hidden_shiping_no_' . $i ])) {
        unset($arr['hidden_des_name_'. $i]);
        unset($arr['hidden_shiping_no_'. $i]);
        unset($arr['hidden_post_no_'. $i]);
        unset($arr['hidden_address1_' . $i]);
        unset($arr['hidden_address2_' . $i]);
        unset($arr['hidden_tel_no_' . $i]);
        unset($arr['hidden_fax_no_' . $i]);
        unset($arr['hidden_shipping_method_' . $i]);
      }
    }
    unset($arr['totalShipping']);
    return $arr;
  }

  private function saveShippingParams($idUser, $arr) {
    $db = new Core_Db_MstUserShippingDb();
    $db->delete(array('user_id = ?' => $idUser));

    // insert first shipping
    $desName = $arr['user_name'];
    $shippingMethod = null;
    $postNo = $arr['post_no'];
    $address1 = $arr['address'];
    $address2 = $arr['address2'];
    $telNo = $arr['tel_no'];
    $faxNo = $arr['fax_no'];
    $shipping = new Core_Models_MstUserShipping();
    $shipping->setShippingSeq("1");
    $shipping->setUserId($idUser);
    $shipping->setPostNo($postNo);
    $shipping->setAddress1($address1);
    $shipping->setAddress2($address2);
    $shipping->setTelNo($telNo);
    $shipping->setFaxNo($faxNo);
    $shipping->setShippingDesName($desName);
    $shipping->setTransType($shippingMethod);
    $idKey = $db->insertRecord($shipping);
    $total = $arr['totalShipping'];
    for ($i = 0; $i <= $total; $i++) {
      if (isset($arr['hidden_shiping_no_' . $i ])) {
        $desName = $arr['hidden_des_name_'. $i];
        $postNo = $arr['hidden_post_no_'. $i];
        $address1 = $arr['hidden_address1_' . $i];
        $address2 = $arr['hidden_address2_' . $i];
        $telNo = $arr['hidden_tel_no_' . $i];
        $faxNo = $arr['hidden_fax_no_' . $i];
        $shippingMethod = $arr['hidden_shipping_method_' . $i];
        $shipping = new Core_Models_MstUserShipping();
        $seq = $db->nextSeq($idUser);
        if ($seq == '0') {
          $seq = 2;
        }
        $shipping->setUserId($idUser);
        $shipping->setPostNo($postNo);
        $shipping->setAddress1($address1);
        $shipping->setAddress2($address2);
        $shipping->setTelNo($telNo);
        $shipping->setFaxNo($faxNo);
        $shipping->setShippingDesName($desName);
        $shipping->setShippingSeq($seq);
        $shipping->setTransType($shippingMethod);
        $idKey = $db->insertRecord($shipping);
      }
    }
  }

  public function deleteUserById($user_id) {
    try {
      $this->beginTransaction();
      $db = $this->userDb;
      $db->deleteUserById($user_id);
      $this->commit();
      return true;
    } catch (Exception $e) {
      $this->rollBack();
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return false;
    }
  }

  public function getSaleUser(){
      try {
      $db = $this->userDb;
      $res = $db->getSaleUser();

      return $res;
    } catch (Exception $e) {
      $this->rollBack();
      parent::writeLog(__CLASS__, __METHOD__, $e);

      return null;
    }
  }

  public function queryAll(){
    try {
      $db = $this->userDb;
      $arruser = $db->getAll();
      return $arruser;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }

  public function getLoginToken() {
    return $this->getRandString(Core_Util_Const::NUMBER_TOCKEN).time();
  }

  /**
   *
   * @param Core_Models_MstUser $user
   * @return unknown|NULL
   */
  public function updateUserTokenCookie($user, $token) {
    try {
      $this->beginTransaction();
      $db = $this->userDb;
      $res = $db->update(array('token_cookie' => $token), array('login_username = ?' => $user->getLoginUsername()));
      $this->commit();
      return $res;
    } catch (Exception $e) {
      $this->rollBack();
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }
  /**
   *
   * @param Core_Models_MstUser $user
   * @return number|NULL
   */
  public function updateUserTokenCookieAdmin($user, $token) {
    try {
      $this->beginTransaction();
      $db = $this->userDb;
      $res = $db->update(array('token_cookie_admin' => $token), array('login_username = ?' => $user->getLoginUsername()));
      $this->commit();
      return $res;
    } catch (Exception $e) {
      $this->rollBack();
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }

  /**
   * getShippingByUserId
   * @param Core_Models_MstUser $userId
   * @return array of Core_Models_MstUserShipping
   */
  public function getShippingByUserId($userId) {
    try {
      $db = new Core_Db_MstUserShippingDb();
      $res = $db->getUserShippingById($userId);
      return $res;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }

  public function getShipping($userId, $id) {
    try {
      $db = new Core_Db_MstUserShippingDb();
      $res = $db->getShipping($userId, $id);
      return $res;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return null;
    }
  }

  /**
   *
   * @param Core_Models_MstUser $arrMstUser
   * @return boolean
   */
  public function insertArrUser($arrMstUser){
    $errUser="";
    try {
      $db = $this->userDb;
      $this->beginTransaction();
      $arrLoginUser = array();
      /* @var $username Core_Models_MstUser */
      foreach ($arrMstUser as $key =>$value) {
        $userlogin = trim($arrMstUser[$key]->getLoginUsername());
        if(!in_array($userlogin , $arrLoginUser)){
          $arrLoginUser[] = $arrMstUser[$key]->getLoginUsername();
        }
      }
      $i = 0;
      for ( $i = count($arrLoginUser)-1; $i>= 0; $i--) {
        $intline =$i+1;
        $userId = $db->getUserIdByUserNAme($arrLoginUser[$i]);
        if ($userId !== 0){
          if ($errUser !== ""){
            $errUser .= ", ";
          }
          $errUser .= $intline;
          unset($arrLoginUser[$i]);
        }
      }

      // if errors uccur
      if (!empty($errUser)) {
      	return $errUser;
      }
      
      
      /* @var $mstUser Core_Models_MstUser */
      foreach ($arrMstUser as $key => $mstUser) {
        $intline=$key+1;

        $serShipping= new Core_Db_MstUserShippingDb();

        $dataShippingByUser = new Core_Models_MstUserShipping();
        //ins user
        $userId = $db->getUserIdByUserNAme($mstUser->getLoginUsername());
        if ($userId === 0){
          $mstUser->setLoginPassword(md5($mstUser->getLoginPassword()));

          $date = new DateTime($mstUser->getUpdatePointDate());
          $mstUser->setUpdatePointDate( $date->format('Y-m-d '));
          $userNameOfSale = $mstUser->getSaleUsername();
          $userIdOfSale = $db->getUserIdByUserNAme($userNameOfSale);
          $mstUser->setSalesId($userIdOfSale);
          $db = $this->userDb;
          $userIdShipping = $db->insertRecord($mstUser);
          
          //ins shipping trans =1
          $dataShippingByUser = new Core_Models_MstUserShipping();
          $dataShippingByUser->setUserId($userIdShipping);
          $dataShippingByUser->setShippingDesName($mstUser->getLoginUsername());
          $dataShippingByUser->setPostNo($mstUser->getPostNo());
          $dataShippingByUser->setTelNo($mstUser->getTelNo());
          $dataShippingByUser->setAddress1($mstUser->getAddress());
          $dataShippingByUser->setAddress2($mstUser->getAddress2());
          $dataShippingByUser->setFaxNo($mstUser->getFaxNo());
          $dataShippingByUser->setTransType(null);

          $serShipping->insertShippingUser($dataShippingByUser);
          
          //shipping trans >1
          if ($mstUser->getShippingDesName() != null
		      && $mstUser->getShippingPostNo() != null
		      && $mstUser->getShippingAddress1() != null) {
          	
	          $dataShipping = new Core_Models_MstUserShipping();
	          $nameShipping = $mstUser->getShippingDesName();
	          $postNoShipping = $mstUser->getShippingPostNo();
	          $addressShipping1 = $mstUser->getShippingAddress1();
	          $telNoShipping = $mstUser->getShippingTelNo();
	          $addressShipping2 = $mstUser->getShippingAddress2();
	          $faxNoShipping = $mstUser->getShippingFaxNo();
	          $tranShipping = $mstUser->getShippingTransType();
	          $dataShipping->setUserId($userIdShipping);
	          $dataShipping->setShippingDesName($nameShipping);
	          $dataShipping->setPostNo($postNoShipping);
	          $dataShipping->setTelNo($telNoShipping);
	          $dataShipping->setAddress1($addressShipping1);
	          $dataShipping->setAddress2($addressShipping2);
	          $dataShipping->setFaxNo($faxNoShipping);
	          $dataShipping->setTransType($tranShipping);
	
	          $serShipping->insertShippingUser($dataShipping);
	          
          }

        }

        //shipping trans >1
        if ($userId !== 0){
          $dataShipping = new Core_Models_MstUserShipping();
          $nameShipping = $mstUser->getShippingDesName();
          $postNoShipping = $mstUser->getShippingPostNo();
          $addressShipping1 = $mstUser->getShippingAddress1();
          $telNoShipping = $mstUser->getShippingTelNo();
          $addressShipping2 = $mstUser->getShippingAddress2();
          $faxNoShipping = $mstUser->getShippingFaxNo();
          $tranShipping = $mstUser->getShippingTransType();
          $dataShipping->setUserId($userId);
          $dataShipping->setShippingDesName($nameShipping);
          $dataShipping->setPostNo($postNoShipping);
          $dataShipping->setTelNo($telNoShipping);
          $dataShipping->setAddress1($addressShipping1);
          $dataShipping->setAddress2($addressShipping2);
          $dataShipping->setFaxNo($faxNoShipping);
          $dataShipping->setTransType($tranShipping);

          $serShipping->insertShippingUser($dataShipping);
        }
      }
      $this->commit();
    } catch (Exception $e) {
    	$errUser = $e;	
    	$this->rollBack();
    }
    return $errUser;
  }

  public function getUserIdByUserNAme($user){
  try {
      $db = $this->userDb;
      $user = $db->getUserIdByUserNAme($user);

      return $user;
    } catch (Exception $e) {
      parent::writeLog(__CLASS__, __METHOD__, $e);
      return FALSE;
    }
  }

}