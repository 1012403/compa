<?php
class Admin_OperationloginfoController extends Core_Controller_AdminAbstract{
	//private $screenName = "購入履歴";
	public function init() {
		parent::init();
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/admin/style.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/style.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/user/adminlayout.css');
		
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/jquery.timepicker.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/bootstrap-datepicker.css');
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery.timepicker.js');
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/bootstrap-datepicker.js');
		parent::createMenuOther();
	}
	
	public function indexAction() {
		
		if (Core_Util_Helper::isReferenceAdmin()) {
			$this->_forward("shownoright","error");
			return;
		}
		
		//parent::visitByButton($this->screenName, "購入履歴");
		$page = $this->_request->getParam('page', 1);
		
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
		
		//$paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_LOGINFO];
		//$paginatorData ['pageRange'] = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_LOGINFO];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1();
		$mstClassPage = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2();
		
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
		
		$log=new Core_Service_OperationLogInfoService();
		$loginfo=$log->getLog($this->view->date_start, $this->view->date_end, $this->view->username, $this->view->oper_contract, $this->view->oper_detail,$paginatorData);
		$totalItem = $log->getCountLog($this->view->date_start, $this->view->date_end, $this->view->username, $this->view->oper_contract, $this->view->oper_detail);
		$objPaginator = new Core_Util_Paginator ();
		$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$paginator->setView($this->view);
		
		$this->view->loginfo=$loginfo;
	}
	
}