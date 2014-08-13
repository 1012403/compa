<?php
class User_ProductController extends Core_Controller_FrontAbstract {
	private $screenName = "商品一覧";

	public function init() {
		parent::init();

		$displayNamespace = new Zend_Session_Namespace('Display');
		if (!isset($displayNamespace->display))
		{
			$displayNamespace->display = Core_Util_Const::DISPLAY_SUMMARY;
		}
		if (!isset($displayNamespace->sort))
		{
			$displayNamespace->sort = Core_Util_Const::$SORT_DEFAULT;
		}

		if (!isset($displayNamespace->tylelist))
		{
			$displayNamespace->tylelist = $this->view->tylelist;
		} else {
			if (strcmp($displayNamespace->tylelist, $this->view->tylelist) != 0){
				$displayNamespace->display = Core_Util_Const::DISPLAY_SUMMARY;
				$displayNamespace->sort = Core_Util_Const::$SORT_DEFAULT;
			}
		}
		$displayNamespace->tylelist = $this->view->tylelist;
	}

	public function indexAction() {
		//parent::visitByButton($this->screenName,"");
		$formData = $this->_getAllParams();
		$operateElementType = null;
		$operateElementName = null;
		if (isset($formData["operateElementType"])) {
			$operateElementType = $formData["operateElementType"];
		}
		if (isset($formData["operateElementName"])) {
			$operateElementName = $formData["operateElementName"];
		}
		
		$params = null;
		if (!empty($this->view->tylelist)) {
			$params["tylelist"] = $this->view->tylelist;
		}
		if (!empty($this->view->keyword)) {
			$params["keyword"] = $this->view->keyword;
		}
		if (!empty($this->view->conditionkeys)) {
			$params["category_id"] = $this->view->conditionkeys;
		}
		
		// Log operation
		if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_LINK) {
			parent::visitByLink(parent::getScreenName(), $operateElementName, "");
		} else if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_BUTTON) {
			parent::visitByButton(parent::getScreenName(), $operateElementName, "");
		} else {
			$params2 = array("tylelist" => "tylelist");
			parent::visitByOperation($this->detectScreenName(), null, null, "");
		}

		// Get title name & update screen name
		$this->view->title_label = "商品一覧";
		if (strcmp(Core_Util_Const::TYLE_LIST_FEATURED_PRODUCTS, $this->view->tylelist)==0){
			$this->view->title_label = "おすすめ商品";
		} else if (strcmp(Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS, $this->view->tylelist)==0){
			$this->view->title_label = "人気商品";
		} else if (strcmp(Core_Util_Const::TYLE_LIST_WISH_LIST, $this->view->tylelist)==0){
			$this->view->title_label = "お気に入り商品";
		}
		
		parent::setScreenName($this->view->title_label);
		
		$pro_serv = new Core_Service_MstProductService();
		$m_productids = $pro_serv->queryProductIds($this->view->keyword, $this->view->conditionkeys, $this->view->tylelist, null);

		$formaction = $this->view->config['url_base'].'/product/index';
		//Edit 2014/06/13 Hungnh START
// 		parent::createMenu($m_productids, $formaction);
		parent::createMenu($this->view->keyword, $this->view->conditionkeys, $this->view->tylelist, null, $formaction);
		//Edit 2014/06/13 Hungnh END
		
		$page = $this->_request->getParam('page', 1);
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
		//$paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		//$paginatorData ['pageRange'] = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1();
		$mstClassPage = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2();
		
		$paginatorData ['currentPage'] = $page;
		
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
		
		$m_products = array();
		$m_products = $pro_serv->queryProducts($paginatorData, $this->view->keyword, $this->view->conditionkeys, $this->view->tylelist);
		$this->view->m_products = $m_products;

		$totalItem = count($m_productids);
		$objPaginator = new Core_Util_Paginator ();
		$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$paginator->setView($this->view);
	}

	public function detailAction() {
		$formData = $this->_getAllParams();
		if (isset($formData['id'])){
			$productid = $formData['id'];
		}
		if (isset($formData['tylelist'])){
			$tylelist = $formData['tylelist'];
		}
		
		$params = null;
		if (!empty($tylelist)) {
			$params["tylelist"] = $tylelist;
		}
		if (!empty($productid)) {
			$params["productid"] = $productid;
		}
		parent::visitByButton(parent::getScreenName(),"詳細はこちら", $params);
		$pro_serv = new Core_Service_MstProductService();
		$m_productids = $pro_serv->queryProductIds($this->view->keyword, $this->view->conditionkeys, $this->view->tylelist, null);
		
		$formaction = $this->view->config['url_base'].'/product/index';
		//Edit 2014/06/13 Hungnh START
// 		parent::createMenu($m_productids, $formaction);
		parent::createMenu($this->view->keyword, $this->view->conditionkeys, $this->view->tylelist, null, $formaction);
		//Edit 2014/06/13 Hungnh END

		$m_products = $pro_serv->queryProduct($productid);
		if ($m_products['product']->getProductId() == null){
			$m_products['productValid'] = false;
		} else{
			$m_products['productValid'] = true;
		}
		$this->view->products = $m_products;

		$page = $this->_request->getParam('page', 1);
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);

		//$paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		//$paginatorData ['pageRange'] = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1();
		$mstClassPage = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2();
		
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

		$order_serv = new Core_Service_OrderService();
		$arrOrderDetail = $order_serv->getProductHistoryByProductId($paginatorData, $productid);
		if ($m_products['product']->getProductId() == null){
			$this->view->producthis = null;
		} else{
			$this->view->producthis = $arrOrderDetail;
		}

		$totalItem = $order_serv->countProductHistoryByProductId($productid);
		$objPaginator = new Core_Util_Paginator ();
		$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$paginator->setView($this->view);

		if ($m_products['product']->getProductId() == null){
			$this->view->productrelations = null;
		} else{
			$this->view->productrelations = $pro_serv->queryProductsRelation($productid);
		}
		
		parent::setScreenName("商品詳細");
	}


	public function historyorderAction() {
		parent::setScreenName("購入履歴");
		$formData = $this->_getAllParams();
		if (isset($formData['id'])){
			$productid = $formData['id'];
		}
		
		$params = array("tylelist" => "tylelist");
		parent::visitByOperation(parent::getScreenName(), null, null, $params);
		
		$page = $this->_request->getParam('page', 1);

		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
		//$paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		//$paginatorData ['pageRange'] = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1();
		$mstClassPage = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2();
		
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

		$order_serv = new Core_Service_OrderService();
		$arrOrderDetail = $order_serv->getProductHistoryByProductId($paginatorData, $productid);
		$this->view->producthis = $arrOrderDetail;

		$totalItem = $order_serv->countProductHistoryByProductId($productid);
		$objPaginator = new Core_Util_Paginator ();
		$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$this->view->id = $productid;
		$paginator->setView($this->view);
		$this->_helper->layout->disableLayout();
	}

	public function updatelikeAction() {
		$formData = $this->_getAllParams();
		if (isset($formData['id'])){
			$productid = $formData['id'];
		}
		if (isset($formData['tyle'])){
			$tyle = $formData['tyle'];
		}
		if (isset($formData['tylelist'])){
			$tylelist = $formData['tylelist'];
		}
		if (!isset($productid) || !isset($tyle)){
			echo "false";
		} else {
			$params = null;
			if (!empty($tylelist)) {
				$params["tylelist"] = $tylelist;
			}
			if (!empty($productid)) {
				$params["productid"] = $productid;
			}
			
			if ($tyle == 0){
				parent::visitByButton(parent::getScreenName(),"お気に入りへ", $params);
			} else {
				parent::visitByButton(parent::getScreenName(),"☆お気に入り", $params);
			}
			$pro_serv = new Core_Service_MstProductService();
			$result = $pro_serv->updateLike($productid, $tyle);
			if ($result == null){
				echo "false";
			} else {
				echo "true";
			}
		}

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}

	public function updateordercartinfoAction(){
		$formData = $this->_getAllParams();
		if (isset($formData['id'])){
			$productid = $formData['id'];
		}
		if (isset($formData['quantity'])){
			$quantity = trim($formData['quantity']);
		}
		if (isset($formData['tylelist'])){
			$tylelist = $formData['tylelist'];
		}
		if (isset($formData['quanRes'])){
			$quanRes = $formData['quanRes'];
		}
		
		$params = null;
		if (!empty($tylelist)) {
			$params["tylelist"] = $tylelist;
		}
		if (!empty($productid)) {
			$params["productid"] = $productid;
		}
		if (!empty($quantity)) {
			$params["quantity"] = $quantity;
		}
		if (!empty($quanRes)) {
			
			if ("1" == $quanRes){
				$quantity  = "1";
				$params["quantity"] = "1";
			} else{
				$params["quanRes"] = $quanRes;
			}
		}
					
		parent::visitByButton(parent::getScreenName(),"注文カートへ入れる", $params);
		
		$or_serv = new Core_Service_OrderService();
		$ordercartinfo = $or_serv->updateOrderCartInfo($productid, $quantity);
		if ($ordercartinfo == null || !isset($ordercartinfo['count'])){
			echo "null";
		} else {
			echo $ordercartinfo['count'];
		}

		$userLogin = Core_Util_Helper::getLoginUser();
		$user_service = new Core_Service_UserService();
		$arrOrderCart = $user_service->getOrderCartForUser($userLogin);
		Core_Util_Helper::setLoginUserOrderCart($arrOrderCart);

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}

	public function updatequotationcartinfoAction(){
		$formData = $this->_getAllParams();
		if (isset($formData['tylelist'])){
			$tylelist = $formData['tylelist'];
		}
		if (isset($formData['id'])){
			$productid = $formData['id'];
		}
		if (isset($formData['quantity'])){
			$quantity = $formData['quantity'];
		}
		
		$params = null;
		if (!empty($tylelist)) {
			$params["tylelist"] = $tylelist;
		}
		if (!empty($productid)) {
			$params["productid"] = $productid;
		}
		if (!empty($quantity)) {
			$params["quantity"] = $quantity;
		}
		
		parent::visitByButton(parent::getScreenName(),"見積りカートへ入れる", $params);
		
		$or_serv = new Core_Service_QuotationCartService();
		$quotationcartinfo = $or_serv->updateQuotationCartInfo($productid, $quantity);
		if ($quotationcartinfo == null || !isset($quotationcartinfo['count'])){
			echo "null";
		} else {
			echo $quotationcartinfo['count'];
		}

		$userLogin = Core_Util_Helper::getLoginUser();
		$user_service = new Core_Service_UserService();
		$arrQuotationCart = $user_service->getQuotationCartForUser($userLogin);
		Core_Util_Helper::setLoginUserQuotationCart($arrQuotationCart);

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}

	public function changedisplayAction(){
		$formData = $this->_getAllParams();
		$displayNamespace = new Zend_Session_Namespace('Display');
		if (!isset($displayNamespace->display))
		{
			$displayNamespace->display = Core_Util_Const::DISPLAY_SUMMARY;
		}else if (strcmp($displayNamespace->display, Core_Util_Const::DISPLAY_SUMMARY) == 0){
			$displayNamespace->display = Core_Util_Const::DISPLAY_IMAGES;
		} else {
			$displayNamespace->display = Core_Util_Const::DISPLAY_SUMMARY;
		}
		if (isset($formData['tylelist'])){
			$tylelist = $formData['tylelist'];
		}
		$params = null;
		if (!empty($tylelist)) {
			$params["tylelist"] = $tylelist;
		}
		parent::visitByButton(parent::getScreenName(),$displayNamespace->display, $params);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}

	public function changesortAction(){
		$formData = $this->_getAllParams();
		$val=null;
		if (isset($formData['val'])){
			$val = $formData['val'];
		}
		if (isset($formData['tylelist'])){
			$tylelist = $formData['tylelist'];
		}
		
		$params = null;
		if (!empty($tylelist)) {
			$params["tylelist"] = $tylelist;
		}
		if (!empty($val)) {
			$params["sortkey"] = $val;
		}
		parent::visitByDropdown(parent::getScreenName(),"並び替え", $params);
		
		$displayNamespace = new Zend_Session_Namespace('Display');
		if ($val==null)
		{
			$displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
		}else {
			$displayNamespace->sort = $val;
		}
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}
	
	private function detectScreenName() {
		$formData = $this->_getAllParams();
		$detailMode = null;
		if (isset($formData['detailMode'])){
			$detailMode = $formData['detailMode'];
		}
		if ($detailMode == "1") {
			return "商品詳細";
		}
		$tylelist = null;
		if (isset($formData['tylelist'])){
			$tylelist = $formData['tylelist'];
		}
		$scrName = "商品一覧";
		if (strcmp(Core_Util_Const::TYLE_LIST_FEATURED_PRODUCTS, $tylelist)==0){
			$scrName = "おすすめ商品";
		} else if (strcmp(Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS, $tylelist)==0){
			$scrName = "人気商品";
		} else if (strcmp(Core_Util_Const::TYLE_LIST_WISH_LIST, $tylelist)==0){
			$scrName = "お気に入り商品";
		}
		return $scrName;
	}
	
// 	private function setScreenName($nameToSet) {
// 		$pageClassSession = new Zend_Session_Namespace(
// 				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
// 		$pageClassSession->lastViewName = $nameToSet;
// 	}
}