<?php
class User_OrdercartController extends Core_Controller_FrontAbstract {
	private $screenName = "注文内容確認";

	public function init() {
		parent::init();
	}

	/**
	 * getAllOrderCart
	 */
	private function getAllOrderCart(){
		$userLogin = Core_Util_Helper::getLoginUser();

		$classServ = new Core_Service_MstClassService();
		$serv = new Core_Service_OrderCartInfoService();
		$arrProducts = $serv->getOrderCartInfoByUser($userLogin->getUserId());

		// ADD 20140724 Locpht start
		foreach ($arrProducts as $key => $value){
			if ($value->getQuantityRestriction() == "1"){
				$value->setOrder_quantity('1'); 
			}
		}
		// ADD 20140724 Locpht end
			
		$this->view->arrProducts = $arrProducts;
		$this->view->userPoint = $userLogin->getUserPoint();

		$pointRate = Core_Util_Helper::getPointRate();
		$this->view->pointRate = $pointRate;

		$this->view->userLogin = $userLogin;

		// ADD 20140512 Hieunm start
		/* @var $arrUserShipping Core_Models_MstUserShipping */
		$arrUserShipping = $serv->getUserShippingInfo($userLogin->getUserId());
		if ($arrUserShipping == null || count($arrUserShipping) == 0) {
			$this->view->transTypeDefaultLoad = "";
		} else {
			$this->view->transTypeDefaultLoad = $arrUserShipping[0]->getTransType();
		}
		$this->view->arrShippingDes = $arrUserShipping;
		// ADD 20140512 Hieunm end

		// ADD 20140515 Hieunm start
		$arrTransType = $classServ->getMstClassByItemTypeDispl(Core_Util_Const::ITEM_TYPE_TRANS_TYPE);
		$this->view->arrTransType = $arrTransType;
		//$arrTransType = $classServ->getMstClassByItemTypeAndItemCdDispl(Core_Util_Const::ITEM_TYPE_TRANS_TYPE, $arrUserShipping[0]->getTransType());
		// ADD 20140515 Hieunm end

		//Edit 2014/06/13 Hungnh START
// 		$catServ = new Core_Service_MstCategoryService();
// 		$m_products = $catServ->queryProductIds(null);
		$formaction = $this->view->config['url_base'].'/product/index';
// 		parent::createMenu($m_products, $formaction);
		parent::createMenu(null, null, null, null, $formaction);
		//Edit 2014/06/13 Hungnh END
	}

	// ADD 20140512 Hieunm start
	/**
	 * changeCmbShippingDesAction
	 */
	public function changeCmbShippingDesAction() {
		$formData = $this->_getAllParams();
		$arrData = $this->getDataForCmbCondition($formData['itemCd']);

		echo($arrData['note1'].'-'.
			 $arrData['note2'].'-'.
			 $arrData['note3'].'-'.
			 $arrData['note4'].'-'.
			 $arrData['note5']);

		parent::setAjaxAction();
	}
	// ADD 20140512 Hieunm end

	/**
	 * indexAction
	 */
	public function indexAction() {
		parent::setScreenName("注文内容確認");
		parent::visitByOperation(parent::getScreenName(), null, null, null);
		$this->getAllOrderCart();
	}

	/**
	 * mainAction
	 */
	public function mainAction() {
		$arrParam = $this->_getAllParams();
		if($arrParam['actionName'] == 'deleteRow'){
			if (isset($arrParam["productId"])) {
				$productId = $arrParam["productId"];
			}
			
			$params = null;
			if (!empty($productId)) {
				$params["productId"] = $productId;
			}
			parent::visitByButton(parent::getScreenName(), "削除", $params);
			$this->deleteRow($arrParam);
		}

		if($arrParam['actionName'] == 'viewOrderCartInfo'){
			parent::visitByButton(parent::getScreenName(), "注文手続きへ");
			$this->changeQuantity($arrParam);
		}

		if($arrParam['actionName'] == 'insertOrder'){
			parent::visitByButton(parent::getScreenName(), "注文確定");
			$txtPoint = null;
			if (isset($arrParam['txtPoint']) && Core_Util_Helper::isNotEmpty($arrParam['txtPoint'])) {
				$txtPoint = $arrParam['txtPoint'];
			} 
			$txtPoint0 = null;
			if (isset($arrParam['txtPoint0']) && Core_Util_Helper::isNotEmpty($arrParam['txtPoint0'])){
				$txtPoint0 = $arrParam['txtPoint0'];
			}
			
			if (Core_Util_Helper::isNotEmpty($arrParam[$txtPoint0])) {
				$txtPoint = $txtPoint0;
			}
			$txtPoint = str_replace(",", "", $txtPoint);
			$arrParam['txtPoint'] = $txtPoint;
			
			$this->insertOrder($arrParam);
		}
	}

	/**
	 * deleteRow
	 * @param unknown $arrParam
	 */
	private function deleteRow($arrParam){
		$userLogin = Core_Util_Helper::getLoginUser();

		$productIdDelete = $arrParam['productId'];
		$serv = new Core_Service_OrderCartInfoService();
		$res = $serv->deleteRowInOrdercart($productIdDelete, $userLogin->getUserId());

		//update session
		$user_service = new Core_Service_UserService();
		$arrOrderCart = $user_service->getOrderCartForUser($userLogin);
		Core_Util_Helper::setLoginUserOrderCart($arrOrderCart);

		$this->_redirect($this->view->config['url_base']."/ordercart");
	}

	/**
	 * changeQuantity
	 * @param unknown $arrParam
	 */
	private function changeQuantity($arrParam){
		$userLogin = Core_Util_Helper::getLoginUser();

		$serv = new Core_Service_OrderCartInfoService();
		foreach ($arrParam as $key => $value){
			list($txtQuantity, $proId) = explode('-', $key.'-');
			if($txtQuantity == 'txtQuantity'){
				$serv->updateQuantity($proId, $value, $userLogin->getUserId());
			}
		}

		$this->getAllOrderCart();
		//$arrUserShipping = $serv->getUserShippingInfo($userLogin->getUserId());
		$this->render("ordercartinfo");
	}

	/**
	 * insertOrder
	 * @param unknown $arrParam
	 */
	private function insertOrder($arrParam){
		$userLogin = Core_Util_Helper::getLoginUser();
		$serv = new Core_Service_OrderCartInfoService();
		$userId = $userLogin->getUserId();


		/* @var $userShipping Core_Models_MstUserShipping */
		// MOD 20140512 Hieunm start
		//$arrUserShipping = $serv->getUserShippingInfo($userLogin->getUserId());
		$userShipping = new Core_Models_MstUserShipping();
		if (isset($arrParam['cmbShippingDes'])) {
			$arrUserShipping = $serv->getUserShippingInfo($userLogin->getUserId(), $arrParam['cmbShippingDes']);
			// MOD 20140512 Hieunm end
			if (count($arrUserShipping)>0) {
				$userShipping = $arrUserShipping[0];
			} 
		}

		//set order info to object
		/* @var $orderInfo Core_Models_OrderInfo */
		$orderInfo = new Core_Models_OrderInfo();
		$orderInfo->setUserId($userId);
		$orderInfo->setOrderDateTime(new Zend_Db_Expr('NOW()'));
		// DEL 20140514 Hieunm start
		//$orderInfo->setShippingSeq($userShipping->getShippingSeq());
		//$orderInfo->setShippingType($userShipping->getTransType());
		// DEL 20140514 Hieunm end
		$orderInfo->setShipppingHopeDate($arrParam['txtShippingHopeDate']);
		$orderInfo->setOrderStatus(Core_Util_Const::DEFAULT_NEW_ORDER_STATUS);
		// MOD 20140422 Hieunm start
		//$orderInfo->setUsedPoint(empty($arrParam['txtPoint']) ? 0 : $arrParam['txtPoint']);
		$orderInfo->setUsedPoint(empty($arrParam['txtPoint']) ? 0 : trim($arrParam['txtPoint']));
		// MOD 20140422 Hieunm end
		$orderInfo->setBonusPoint($arrParam['hiddenBonusPoint']);

		// MOD 20140513 Hieunm start
		$orderInfo->setShippingDesName($userShipping->getShippingDesName());
		$orderInfo->setPostNo($userShipping->getPostNo());
		$orderInfo->setAddress1($userShipping->getAddress1());
		$orderInfo->setAddress2($userShipping->getAddress2());
		$orderInfo->setTelNo($userShipping->getTelNo());
		$orderInfo->setFaxNo($userShipping->getFaxNo());
		$orderInfo->setTransType($arrParam['cmbTransType']);
		$orderInfo->setRemark($userShipping->getRemark());
		// MOD 20140513 Hieunm end

		$orderInfo->setDeleteFlg(Core_Util_Const::DELETE_FLG_0);
		$orderInfo->setInsertYmd(new Zend_Db_Expr('NOW()'));
		$orderInfo->setInsertUserId($userId);
		$orderInfo->setUpdateYmd(new Zend_Db_Expr('NOW()'));
		$orderInfo->setUpdateUserId($userId);

		$arrProducts =  $serv->getOrderCartInfoByUser($userLogin->getUserId());
		$arrOrderDetail = array();

		//set order detail info
		/* @var $product Core_Models_MstProduct */
		foreach($arrProducts as $id=>$product){
			$orderDetail = new Core_Models_OrderDetailInfo();
			$orderDetail->setDetailNo($id + 1);
			$orderDetail->setProductId($product->getProductId());
			$orderDetail->setQuantity($product->getOrderQuantity());
			$orderDetail->setPrice($product->getPrice());
			$orderDetail->setPriceIncludingTax($product->getPriceIncludingTax());
			$totalPrice = $orderDetail->getQuantity() * $orderDetail->getPrice();
			$orderDetail->setTotalPrice($totalPrice);
			$orderDetail->setTax($product->getTax());
			if($product->getShippingDisplayFlag() == Core_Util_Const::SHIPPING_DISPLAY_FLG_YES && $product->getShippingClass() == Core_Util_Const::SHIPPING_CLASS_HAS_FEE){
				$orderDetail->setShippingFee($product->getShippingFee());
			}
			else{
				$orderDetail->setShippingFee(0);
			}

			$orderDetail->setDeleteFlg(Core_Util_Const::DELETE_FLG_0);
			$orderDetail->setInsertYmd(new Zend_Db_Expr('NOW()'));
			$orderDetail->setInsertUserId($userId);
			$orderDetail->setUpdateYmd(new Zend_Db_Expr('NOW()'));
			$orderDetail->setUpdateUserId($userId);

			$arrOrderDetail[$id] = $orderDetail;
		}
		$res = $serv->insertOrder($orderInfo, $arrOrderDetail, $userLogin->getUserId());

		//update session = 0
		$user_service = new Core_Service_UserService();
		$arrOrderCart = $user_service->getOrderCartForUser($userLogin);
		Core_Util_Helper::setLoginUserOrderCart($arrOrderCart);

		$this->_redirect($this->view->config['url_base']."/");
	}
	
	public function getaddressAction() {
		$this->setAjaxAction();
		$id = $this->_request->getParam("id", null);
		$userId = $this->_request->getParam("userid", null);
		if (Core_Util_Helper::isEmpty($id)) {
			echo "";
		} else {
			$ser = new Core_Service_UserService();
			$shipping = $ser->getShipping($userId, $id);
			if ($shipping !== null) {
				$data = "住所：〒" . $shipping->getPostNo(). " " . $shipping->getAddress1();
				$data .= "<br />電話番号：" . $shipping->getTelNo();
				echo $data;
			} else {
				echo "";
			}
		}
	}
}