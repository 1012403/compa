<?php
/**
 * All Rights Reserved, Copyright (c) 株式会社アイ・エンター 2013
 * @author Hieunm
 */
class User_ContactController extends Core_Controller_FrontAbstract {
	private $screenName = "お問い合わせ情報";
	private $userId;
	private $screenNameInput = "お問い合わせ入力";
	private $screenNameDetail = "お問い合わせ内容";
	
	/**
	 * init
	 * 
	 * @see Core_Controller_FrontAbstract::init()
	 */
	public function init() {
		parent::init ();
		// $this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/includes/ckeditor/ckeditor.js');
		$this->view->headTitle ( 'お問い合わせ情報' );
		// Get Login Session Info
		$userLogin = Core_Util_Helper::getLoginUser ();
		$this->userId = $userLogin->getUserId ();
	}
	
	/**
	 * indexAction
	 */
	public function indexAction() {
		parent::setScreenName($this->screenNameInput);
		parent::visitByOperation(parent::getScreenName(), null, null, null);
		$contactForm = new User_Form_Contact ();
		$page = $this->_request->getParam ( 'page', 1 );
		// paging
		$pageClassSession = new Zend_Session_Namespace ( Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS );
		
		//$paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass [Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		//$paginatorData ['pageRange'] = $pageClassSession->pageRangeClass [Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass [Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1();
		$mstClassPage = $pageClassSession->pageRangeClass [Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2();
		
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle ( 'Sliding' );
		Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'pagination.phtml' );
		
		$totalItem = count ( $this->loadListAskContact ( null ) );
		$objPaginator = new Core_Util_Paginator ();
		$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$paginator->setView ( $this->view );
		
		$askContactInfo = $this->loadListAskContact ( $paginatorData );
		
		// menu
		$this->createMenuCategory ();
		
		// get contact class name
		$classServ = new Core_Service_MstClassService ();
		$contactClass = $classServ->getMstClassByItemType ( Core_Util_Const::CONTACT_CLASS );
		$contactClassName = array ();
		foreach ( $contactClass as $key => $value ) {
			$contactClassName [$value->getItemCd ()] = $value->getItemName ();
		}
		
		$this->view->contactForm = $contactForm;
		$this->view->askContactInfo = $askContactInfo;
		$this->view->contactClassName = $contactClassName;
	}
	
	/**
	 * contactAction
	 */
	public function contactAction() {
		$formData = $this->_getAllParams ();
		if (isset ( $formData ['contact_class'] )) {
			$contact_class = $formData ['contact_class'];
		}
		if (isset ( $formData ['contact_class_small'] )) {
			$contact_class_small = $formData ['contact_class_small'];
		}
		if (isset ( $formData ['contact_title'] )) {
			$contact_title = $formData ['contact_title'];
		}
		if (isset ( $formData ['contact_content'] )) {
			$contact_content = $formData ['contact_content'];
		}
		if (isset ( $formData ['notice'] )) {
			$notice = $formData ['notice'];
		}
		
		$params = null;
		if (!empty($contact_class)) {
			$params["contact_class"] = $contact_class;
		}
		if (!empty($contact_class_small)) {
			$params["contact_class_small"] = $contact_class_small;
		}
		if (!empty($contact_title)) {
			$params["contact_title"] = $contact_title;
		}
		if (!empty($notice)) {
			$params["notice"] = $notice;
		}
		if (!empty($contact_content)) {
			$params["contact_content"] = $contact_content;
		}
		parent::visitByButton(parent::getScreenName(), "送信", $params);
		
		$userId = $this->userId;
		if (! isset ( $formData ['page'] )) {
			$contactModel = new Core_Models_ContactInfo ();
			$contactModel->setSeq ( 0 );
			$contactModel->setUserId ( $userId );
			$contactModel->setAskAnswerFlg ( Core_Util_Const::CONTACT_ASK_FLG );
			$contactModel->setContactClass ( $formData ['contact_class'] );
			$contactModel->setContactDateTime ( new Zend_Db_Expr ( 'NOW()' ) );
			$contactModel->setTitle ( $formData ['contact_title'] );
			
			$android = stripos( strtolower($_SERVER['HTTP_USER_AGENT']),'android');
			if( $android !== false ) { //|| $iphone ||$ipod || $isiPad
				$contactModel->setContent ( $formData ['contact_content']);
			}else {
				$contactModel->setContent ( $formData ['notice'] );
			}
			$contactModel->setDeleteFlg ( Core_Util_Const::DELETE_FLG_0 );
			$contactModel->setInsertYmd ( new Zend_Db_Expr ( 'NOW()' ) );
			$contactModel->setInsertUserId ( $this->userId );
			$contactModel->setUpdateYmd ( new Zend_Db_Expr ( 'NOW()' ) );
			$contactModel->setUpdateUserId ( $this->userId );
			$contactService = new Core_Service_ContactService ();
			$res = $contactService->insertAskContact ( $contactModel );
			$this->_redirect ( "contact" );
		} else {
			$this->_forward ( "index" );
		}
	}
	
	/**
	 * insertNewAnsAction
	 */
	public function newreplyAction() {
		$formData = $this->_getAllParams ();
		$contactId = $formData ['contactId'];
		
		$contactModel = new Core_Models_ContactInfo ();
		$contactModel->setContactId ( $contactId );
		$contactModel->setUserId ( $this->userId );
		$contactModel->setAskAnswerFlg ( Core_Util_Const::CONTACT_ASK_FLG );
		$contactModel->setContactClass ( $formData ['contactClass'] );
		$contactModel->setContactDateTime ( new Zend_Db_Expr ( 'NOW()' ) );
		$contactModel->setTitle ( $formData ['contactTitle'] );
		
		$android = stripos( strtolower($_SERVER['HTTP_USER_AGENT']),'android');
		if( $android !== false ) { //|| $iphone ||$ipod || $isiPad
			$contactModel->setContent ( $formData ['contact_content']);
		}else {
			$contactModel->setContent ( $formData ['notice'] );
		}
		$contactModel->setDeleteFlg ( Core_Util_Const::DELETE_FLG_0 );
		$contactModel->setInsertYmd ( new Zend_Db_Expr ( 'NOW()' ) );
		$contactModel->setInsertUserId ( $this->userId );
		$contactModel->setUpdateYmd ( new Zend_Db_Expr ( 'NOW()' ) );
		$contactModel->setUpdateUserId ( $this->userId );
		
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
		
		$contactService = new Core_Service_ContactService ();
		$res = $contactService->insertNewReplyContact ( $contactModel );
		$this->_redirect ( $this->view->config ['url_base'] . "/contact/viewdetail?contactId=" . $contactId );
	}
	
	/**
	 * viewdetailAction
	 */
	public function viewdetailAction() {
		$arrParam = $this->_request->getParams ();
		$contactId = $arrParam ['contactId'];
		
		$params = null;
		if (!empty($contactId)) {
			$params["contactId"] = $contactId;
		}
		if (parent::getScreenName() == $this->screenNameInput) {
			parent::visitByLink(parent::getScreenName(), "お問い合わせ内容／返答内容", $params);
		} else {
			$params = array("contactId" => "contactId");
			parent::visitByOperation($this->screenNameDetail, null, null, $params);
		}
		parent::setScreenName($this->screenNameDetail);
		$this->createMenuCategory ();
		
		$contactService = new Core_Service_ContactService ();
		$arrContact = $contactService->getAnswerContactByContactId ( $contactId, null );
		
		$this->view->arrAnsContact = $arrContact;
		$this->view->contactClass = $arrContact [0]->getContactClass ();
		$this->view->contactId = $arrContact [0]->getContactId ();
		$this->view->contactTitle = $arrContact [0]->getTitle ();
		$this->render ( "viewcontactdetail" );
	}
	
	/**
	 * loadListAskContact
	 * 
	 * @param unknown $paginatorData        	
	 * @return Ambigous <multitype:, boolean, $res, multitype:unknown >
	 */
	private function loadListAskContact($paginatorData) {
		$contactService = new Core_Service_ContactService ();
		$askContactInfo = $contactService->getAskContactByUserId ( $this->userId, $paginatorData );
		
		$askAnsContactInfo = array ();
		return $askContactInfo;
	}
	
	/**
	 * createMenuCategory
	 */
	private function createMenuCategory() {
		// menu
		$catServ = new Core_Service_MstCategoryService ();
		//$m_products = $catServ->queryProductIds ( null );
		$formaction = $this->view->config ['url_base'] . '/product/index';
		
		//parent::createMenu ( $m_products, $formaction );
		parent::createMenu(null, null, null, null, $formaction);
	}
	
	/**
	 * checkInput
	 * 
	 * @param unknown $formData        	
	 * @return string
	 */
	private function checkInput($formData) {
		// check input
		$error = "";
		$msg = Core_Util_Helper::checkString ( 'タイトル', $formData ["txtNewTitle"], 0, 100, true, 'タイトル' );
		if ($msg != "") {
			$error .= $msg . "<br />";
		}
		
		$msg = Core_Util_Helper::checkString ( '内容', $formData ["txtNewContent"], 0, 250, true, '内容' );
		if ($msg != "") {
			$error .= $msg . "<br />";
		}
		
		return $error;
	}
}