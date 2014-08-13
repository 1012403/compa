<?php
class Admin_ContactController extends Core_Controller_AdminAbstract
{
	private $screenName = "問い合わせ対応《管理サイト》";
	private $userId;
	private $screenNameInput = "お問い合わせ入力《管理サイト》";
	private $screenNameDetail = "お問い合わせ内容《管理サイト》";
	
	/**
	 * init
	 * @see Core_Controller_FrontAbstract::init()
	 */
	public function init() {
		parent::init();
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/includes/ckeditor/ckeditor.js');
		$this->view->headTitle('お問い合わせ情報');

		// Get Login Session Info
		$userLogin = Core_Util_Helper::getLoginAdmin();
		$this->userId = $userLogin->getUserId();
		
		parent::createMenuOther();
	}

	/**
	 * indexAction
	 */
	public function indexAction() {
		parent::setScreenName($this->screenName);
		$contactForm       = new User_Form_Contact();
		$page = $this->_request->getParam('page', 1);
		$username = $this->_request->getParam('username', '');
		
		$formData = $this->_getAllParams();
		$operateElementType = null;
		$operateElementName = null;
		if (isset($formData["operateElementName"])) {
			$operateElementName = $formData["operateElementName"];
		}
		if (isset($formData["operateElementType"])) {
			$operateElementType = $formData["operateElementType"];
		}
		$params = null;
		if (!empty($username)) {
			$params["username"] = $username;
		}
		if (!empty($page)) {
			$params["page"] = $page;
		}
		
		if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_BUTTON) {
			parent::visitByBUtton(parent::getScreenName(), $operateElementName, $params);
		} else {
			parent::logVisit(parent::getScreenName(), null, null, $params);
		}
		
		//paging
		$pageClassSession = new Zend_Session_Namespace(Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
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
		$contactService = new Core_Service_ContactService();
		$totalItem =$contactService->countAskContactByUserName($username);
		
		$objPaginator = new Core_Util_Paginator ();
		$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$paginator->setView($this->view);
		
		$askContactInfo = $this->loadListAskContact($username, $paginatorData);
		
		
		//get contact class name
		$classServ = new Core_Service_MstClassService();
		$contactClass = $classServ->getMstClassByItemType(Core_Util_Const::CONTACT_CLASS);
		$contactClassName = array();
		foreach ($contactClass as $key=>$value){
			$contactClassName[$value->getItemCd()] = $value->getItemName();
		}
		
		$this->view->contactForm       = $contactForm;
		$this->view->askContactInfo = $askContactInfo;
		$this->view->contactClassName = $contactClassName;
		$this->view->username = $username;
	}

	/**
	 * insertNewAnsAction
	 */
	public function newreplyAction() {
		$formData = $this->_getAllParams();
		$contactId = $formData['contactId'];
		
		$contactModel = new Core_Models_ContactInfo();
		$contactModel->setContactId($contactId);
		$contactModel->setUserId($this->userId);
		$contactModel->setAskAnswerFlg(Core_Util_Const::CONTACT_ANS_FLG);
		$contactModel->setContactClass($formData['contactClass']);
		$contactModel->setContactDateTime(new Zend_Db_Expr('NOW()'));
		$contactModel->setTitle($formData['contactTitle']);
		$contactModel->setContent($formData['notice']);
		$contactModel->setDeleteFlg(Core_Util_Const::DELETE_FLG_0);
		$contactModel->setInsertYmd(new Zend_Db_Expr('NOW()'));
		$contactModel->setInsertUserId($this->userId);
		$contactModel->setUpdateYmd(new Zend_Db_Expr('NOW()'));
		$contactModel->setUpdateUserId($this->userId);
		
		// Log operation
		$params = null;
		if (!empty($contactId)) {
			$params["contactId"] = $contactId;
		}
		if (!empty($formData ['contactClass'])) {
			$params["contactClass"] = $formData ['contactClass'];
		}
		if (!empty($formData ['contactTitle'])) {
			$params["contactTitle"] = $formData ['contactTitle'];
		}
		if (!empty($formData ['notice'])) {
			$params["notice"] = $formData ['notice'];
		}
		parent::visitByButton(parent::getScreenName(), "送信", $params);

		$contactService = new Core_Service_ContactService();
		$res = $contactService->insertNewReplyContact($contactModel);
		$this->_redirect($this->view->config['url_base']."/admin/contact/viewdetail?contactId=".$contactId);
		
	}
	
	
	/**
	 * viewdetailAction
	 */
	public function viewdetailAction() {
		$arrParam = $this->_request->getParams();
		$contactId = $arrParam['contactId'];
		
		$params = null;
		if (!empty($contactId)) {
			$params["contactId"] = $contactId;
		}
		if (parent::getScreenName() == $this->screenName) {
			parent::visitByLink(parent::getScreenName(), "お問い合わせ内容／返答内容", $params);
		} else {
			parent::logVisit($this->screenNameDetail, null, null, $params);
		}
		parent::setScreenName($this->screenNameDetail);
		
		$contactService = new Core_Service_ContactService();
		$arrContact = $contactService->getAnswerContactByContactId($contactId, null);
		
		$this->view->arrAnsContact = $arrContact;
		$this->view->contactClass = $arrContact[0]->getContactClass();
		$this->view->contactId = $arrContact[0]->getContactId();
		$this->view->contactTitle = $arrContact[0]->getTitle();
		$this->render("viewcontactdetail");
	}
	
	/**
	 * loadListAskContact
	 * @param unknown $paginatorData
	 * @return Ambigous <multitype:, boolean, $res, multitype:unknown >
	 */
	private function loadListAskContact($userName, $paginatorData) {
		$contactService = new Core_Service_ContactService();
		$askContactInfo = $contactService->getAskContactByUserName($userName, $paginatorData);

		$askAnsContactInfo = array();
		return $askContactInfo;
	}
}