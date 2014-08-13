<?php
class User_QuotationcartController extends Core_Controller_FrontAbstract {
	private $screenName = "見積り内容確認";
	
	public function init() {
		parent::init();
	}

	/**
	 * getAllQuotationCart
	 */
	private function getAllQuotationCart(){
		$userLogin = Core_Util_Helper::getLoginUser();
		
		$serv = new Core_Service_QuotationCartInfoService();
		$arrProducts = $serv->getQuotationCartByUser($userLogin->getUserId());
		
		$this->view->arrProducts = $arrProducts;
		
		//Edit 2014/06/13 Hungnh START
// 		$catServ = new Core_Service_MstCategoryService();
// 		$m_products = $catServ->queryProductIds(null);
		$formaction = $this->view->config['url_base'].'/product/index';
// 		parent::createMenu($m_products, $formaction);
		parent::createMenu(null, null, null, null, $formaction);
		//Edit 2014/06/13 Hungnh END
	}
	
	/**
	 * indexAction
	 */
	public function indexAction() {
		parent::setScreenName("見積り内容確認");
		parent::visitByOperation(parent::getScreenName(), null, null, null);
		$this->getAllQuotationCart();
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
		
		if($arrParam['actionName'] == 'insertQuotation'){
			parent::visitByButton(parent::getScreenName(), "見積り依頼");
			$this->insertQuotation($arrParam);
		}
	}
	
	/**
	 * deleteRow
	 * @param unknown $arrParam
	 */
	private function deleteRow($arrParam){
		$userLogin = Core_Util_Helper::getLoginUser();
		
		$productIdDelete = $arrParam['productId'];
		$serv = new Core_Service_QuotationCartInfoService();
		$res = $serv->deleteRowInQuotationcart($productIdDelete, $userLogin->getUserId());
		
		//update session
		$user_service = new Core_Service_UserService();
		$arrQuotationCart = $user_service->getQuotationCartForUser($userLogin);
		Core_Util_Helper::setLoginUserQuotationCart($arrQuotationCart);
		
		$this->_redirect($this->view->config['url_base']."/quotationcart");
	}
	
	/**
	 * changeQuantity
	 * @param unknown $arrParam
	 */
	private function changeQuantity($arrParam){
		$userLogin = Core_Util_Helper::getLoginUser();
		
		$serv = new Core_Service_OrderCartInfoService();
		foreach ($arrParam as $key => $value){
			list($txtQuantity, $proId) = split('-', $key.'-');
			if($txtQuantity == 'txtQuantity'){
				$serv->updateQuantity($proId, $value, $userLogin->getUserId());
			}
		}
		
		$this->getAllOrderCart();
		$userShipping = $serv->getUserShippingInfo($userLogin->getUserId());
		$this->render("ordercartinfo");
	}
	
	/**
	 * insertQuotation
	 * @param unknown $arrParam
	 */
	private function insertQuotation($arrParam){
		$userLogin = Core_Util_Helper::getLoginUser();
		$serv = new Core_Service_QuotationCartInfoService();
		$userId = $userLogin->getUserId();
		
		
		$quotationInfo = new Core_Models_QuotationInfo();
		$quotationInfo->setUserId($userId);
		$quotationInfo->setQuotationDateTime(new Zend_Db_Expr('NOW()'));
		$quotationInfo->setRemark($arrParam['txtRemark']);
		$quotationInfo->setStatus(Core_Util_Const::DEFAULT_NEW_QUOTATION_STATUS);
		
		$quotationInfo->setDeleteFlg(Core_Util_Const::DELETE_FLG_0);
		$quotationInfo->setInsertYmd(new Zend_Db_Expr('NOW()'));
		$quotationInfo->setInsertUserId($userId);
		$quotationInfo->setUpdateYmd(new Zend_Db_Expr('NOW()'));
		$quotationInfo->setUpdateUserId($userId);
		
		$arrProducts =  $serv->getQuotationCartByUser($userId);
		$arrQuotationDetail = array();
		
		//set order detail info
		/* @var $product Core_Models_MstProduct */
		foreach($arrProducts as $id=>$product){
			$quotationDetail = new Core_Models_QuotationDetailInfo();
			$quotationDetail->setDetailNo($id + 1);
			$quotationDetail->setProductId($product->getProductId());
			$quotationDetail->setQuantity($arrParam['txtQuantity-'.$product->getProductId()]);
			$quotationDetail->setComment($arrParam['txtComment-'.$product->getProductId()]);
			
			$quotationDetail->setDeleteFlg(Core_Util_Const::DELETE_FLG_0);
			$quotationDetail->setInsertYmd(new Zend_Db_Expr('NOW()'));
			$quotationDetail->setInsertUserId($userId);
			$quotationDetail->setUpdateYmd(new Zend_Db_Expr('NOW()'));
			$quotationDetail->setUpdateUserId($userId);
			
			$arrQuotationDetail[$id] = $quotationDetail;
		}
		$res = $serv->insertQuotation($quotationInfo, $arrQuotationDetail, $userLogin->getUserId());
	
		//update session  = 0
		$user_service = new Core_Service_UserService();
		$arrQuotationCart = $user_service->getQuotationCartForUser($userLogin);
		Core_Util_Helper::setLoginUserQuotationCart($arrQuotationCart);
		
		$this->_redirect($this->view->config['url_base']."/");
	}
}