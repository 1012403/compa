<?php
class User_OrderController extends Core_Controller_FrontAbstract {
	private $screenName = "購入履歴 ";
	public function init() {
		parent::init();
	}
	
	public function indexAction() {
		parent::setScreenName("購入履歴");
		$formData = $this->_getAllParams();
		$operateElementType = null;
		if (isset($formData["operateElementType"])) {
			$operateElementType = $formData["operateElementType"];
		}
		if (isset($formData["operateElementName"])) {
			$operateElementName = $formData["operateElementName"];
		}
		
		$params = null;
		if (!empty($this->view->keyword)) {
			$params["keyword"] = $this->view->keyword;
		}
		if (!empty($this->view->start_date)) {
			$params["start_date"] = $this->view->start_date;
		}
		if (!empty($this->view->end_date)) {
			$params["end_date"] = $this->view->end_date;
		}
		
		if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_LINK) {
			parent::visitByLink(parent::getScreenName(), $operateElementName, $params);
		} else if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_BUTTON) {
			parent::visitByButton(parent::getScreenName(), $operateElementName, $params);
		} else {
			$params2 = array("tylelist" => "tylelist");
			parent::visitByOperation(parent::getScreenName(), null, null, $params2);
		}
		
		$servive = new Core_Service_MstProductService();
		$m_products = $servive->queryProductIds($this->view->keyword, $this->view->conditionkeys, Core_Util_Const::TYLE_LIST_HISTORY_PRODUCTS, null);
		//Edit 2014/06/13 Hungnh START
// 		parent::createMenu($m_products);
		parent::createMenu($this->view->keyword, $this->view->conditionkeys, Core_Util_Const::TYLE_LIST_HISTORY_PRODUCTS, null, null);
		//Edit 2014/06/13 Hungnh END
		
		$serv = new Core_Service_OrderService ();
		$userlogin = Core_Util_Helper::getLoginUser ();
		
		$page = $this->_request->getParam('page', 1);
		$pageClassSession = new Zend_Session_Namespace(Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
		
		//$paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_ORDER];
		//$paginatorData ['pageRange'] = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_ORDER];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1();
		$mstClassPage = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2();
		
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
		
		$getDetailList = null;
		$getDetailList = $serv->getProductHistory($userlogin->getUserId(),$this->view->keyword, 
					$this->view->conditionkeys, $this->view->tylelist,$this->view->start_date,$this->view->end_date,
					$paginatorData);
		$totalItem =$serv->countDetailByOrder($userlogin->getUserId(),$this->view->keyword, 
					$this->view->conditionkeys, $this->view->tylelist,$this->view->start_date,$this->view->end_date);
		$objPaginator = new Core_Util_Paginator ();
		$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$paginator->setView($this->view);
		
		$this->view->arrProdHis = $getDetailList;
       
	}
}
