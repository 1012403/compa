<?php
class Admin_MailtemplateController extends Core_Controller_AdminAbstract{
	private $screenName = "メール通知設定《管理サイト》";
	public function init() {
		parent::init();
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/admin/style.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/style.css');

		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/admin/mail.js');
		parent::createMenuOther();
	}
	/**
	 * indexAction
	 */
	public function indexAction(){
		if (Core_Util_Helper::isReferenceAdmin()) {
			$this->_forward("shownoright","error");
			return;
		}
		
		parent::setScreenName($this->screenName);
		
		$mailTemp=new Core_Service_MailTemplateService();
		$formData = $this->_getAllParams();
		$itempcd=null;
		
		
		//id item cd 
		if (isset($formData['class_itemp1'])){
			$itempcd = $formData['class_itemp1'];
		}
		
		$operateElementType = null;
		$operateElementName = null;
		if (isset($formData["operateElementType"])) {
			$operateElementType = $formData["operateElementType"];
		}
		if (isset($formData["operateElementName"])) {
			$operateElementName = $formData["operateElementName"];
		}
		
		$params = null;
		if (!empty($itempcd)) {
			$params['itempcd'] = $itempcd;
		}
		if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_BUTTON) {
			parent::visitByButton(parent::getScreenName(), $operateElementName, $params);
		} else {
			parent::logVisit(parent::getScreenName(), null, null, $params);
		}
		
		//id itemcd submit
		$itempcdsubmit=null;
		if (isset($formData['itemcdsubmit'])){
			$itempcdsubmit = $formData['itemcdsubmit'];
		}
		
		//Save db;
		if (isset($formData['save'])){
			$itempsave=null;
			if (isset($formData['class_itemp'])){
				$itempsave = $formData['class_itemp'];
			}
			//update apply_flg
			if (isset($formData['apply_flg'])){
				$arrmail=$mailTemp->getMailTemplateByItem($itempsave,Core_Util_Const::MAIL_TEMPLATE_FLAG_1);
				foreach ($arrmail as $value){
					$mailTemp->updateFlag($itempsave);
				}
			}
			$this->savemail($formData);
			
		}
		//update
		else if (isset($formData['update'])){
			$itemphiden=null;
			$idMail=null;
			if (isset($formData['classhidden'])){
				$itemphiden = $formData['classhidden'];
			}
			//update apply_flg
			if (isset($formData['apply_flg'])){
				$arrmail=$mailTemp->getMailTemplateByItem($itemphiden,Core_Util_Const::MAIL_TEMPLATE_FLAG_1);
				foreach ($arrmail as $value){
					$mailTemp->updateFlag($itemphiden);
				}
			}
			if (isset($formData['id'])){
				$idMail = $formData['id'];
			}
			$this->updatemail($idMail,$formData);
		}
		//count page
		$totalItem=$mailTemp->countEmailTemp($itempcd);
		//page
		$page = $this->_request->getParam('page', 1);
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
		
		//$paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_MAILTEMPLATE];
		//$paginatorData ['pageRange'] = $pageClassSession->pageRangeClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_MAILTEMPLATE];
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_MAILTEMPLATE];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1();
		$mstClassPage = $pageClassSession->pageRangeClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_MAILTEMPLATE];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2();
		
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
		
		$objPaginator = new Core_Util_Paginator ();
		$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$paginator->setView($this->view);
		//table
		$resulMail=$mailTemp->getMailTemplate( $itempcd,$paginatorData);
		$this->view->mailTemp=$resulMail;
		if ($itempcd==null)
			$itempcd=0;
		$this->view->itempcd = $itempcd;
		$this->view->itemcdsubmit = $itempcdsubmit;
		//combo
		$service= new Core_Service_MstClassService();
		$type=Core_Util_Const::ADMIN_CLASS_MAIL_17;
		$mailClass=$service->getMstClassByItemType($type);
		$this->view->mailClass=$mailClass;
	}
	/**
	 * detailmailAction
	 */
	public function detailmailAction(){
		if (Core_Util_Helper::isReferenceAdmin()) {
			$this->_forward("shownoright","error");
			return;
		}
		
		$idMail=null;
		$formData = $this->_getAllParams();
		if (isset($formData['id'])){
			$idMail = $formData['id'];
		}
		
		$params = null;
		if(!empty($idMail)) {
			$params['mail_id'] = $idMail;
		}
		parent::visitByButton(parent::getScreenName(), "編集", $params);
		
		$mailTemp=new Core_Service_MailTemplateService();
		$resulMail=$mailTemp->searchEmailTempById($idMail);
		$detailMail= new Core_Models_MailTemplate( $resulMail);
		$this->view->detailmail=$detailMail;
		$service= new Core_Service_MstClassService();
		$type=Core_Util_Const::ADMIN_CLASS_MAIL_17;
		$mailClass=$service->getMstClassByItemType($type);
		$this->view->mailClass=$mailClass;
		$this->_helper->layout->disableLayout();
	}
	
	/**
	 * savemail
	 * @param unknown $formData
	 */
	private function savemail($formData){
		$title=trim($this->getRequest()->getParam('title'));
		$class_itemp=trim($this->getRequest()->getParam('class_itemp'));
		
		if (isset($formData['class_itemp'])) {
			$class_itemp = $formData['class_itemp'];
		}
		if (isset($formData['title'])) {
			$title = $formData['title'];
		}
		if (isset($formData['header'])) {
			$header = $formData['header'];
		}
		if (isset($formData['footer'])) {
			$footer = $formData['footer'];
		}
		if (isset($formData['apply_flg'])) {
			$apply_flg = $formData['apply_flg'];
		}
		
		$params = null;
		if (!empty($class_itemp)) {
			$params['class_itemp'] = $class_itemp;
		}
		if (!empty($title)) {
			$params['title'] = $title;
		}
		if (!empty($header)) {
			$params['header'] = $header;
		}
		if (!empty($footer)) {
			$params['footer'] = $footer;
		}
		if (!empty($apply_flg)) {
			$params['apply_flg'] = $apply_flg;
		}
		
		parent::visitByButton(parent::getScreenName(), "保存", $params);
		
		if(!Core_Util_Helper::isNotEmpty($title)){
			//$err= Core_Util_Messages::getMessage(Core_Util_Messages::N007);
			//$this->view->errtitle=$err;
		}
		if($class_itemp=='0'){
			//$err= Core_Util_Messages::getMessage(Core_Util_Messages::N008);
			//$this->view->errclass=$err;
		}
		else {
			if(isset($formData['apply_flg'])){
				$formData['apply_flg']=1;
			}
			else {
				$formData['apply_flg']=0;
			}
			$ser=new Core_Service_MailTemplateService();
			$resultAdd= $ser->saveEmail($formData);
		}
	}
	/**
	 * updatemail
	 * @param unknown $id
	 * @param unknown $formData
	 */
	private function updatemail($id, $formData){
		
		if (isset($formData['class_itemp'])) {
			$class_itemp = $formData['class_itemp'];
		}
		if (isset($formData['title'])) {
			$title = $formData['title'];
		}
		if (isset($formData['header'])) {
			$header = $formData['header'];
		}
		if (isset($formData['footer'])) {
			$footer = $formData['footer'];
		}
		if (isset($formData['apply_flg'])) {
			$apply_flg = $formData['apply_flg'];
		}
		
		$params = null;
		if (!empty($id)) {
			$params['mail_id'] = $id;
		}
		if (!empty($class_itemp)) {
			$params['class_itemp'] = $class_itemp;
		}
		if (!empty($title)) {
			$params['title'] = $title;
		}
		if (!empty($header)) {
			$params['header'] = $header;
		}
		if (!empty($footer)) {
			$params['footer'] = $footer;
		}
		if (!empty($apply_flg)) {
			$params['apply_flg'] = $apply_flg;
		}
		parent::visitByButton(parent::getScreenName(), "保存", $params);
		
		$title=$this->getRequest()->getParam('title');
		$class_itemp=$this->getRequest()->getParam('class_itemp');
		if(!Core_Util_Helper::isNotEmpty($title)){
			//$err= Core_Util_Messages::getMessage(Core_Util_Messages::N007);
			//$this->view->errtitle=$err;
			return ;
		}else {
			if(isset($formData['apply_flg'])){
				$formData['apply_flg']=1;
			}
			else {
				$formData['apply_flg']=0;
			}
			$ser=new Core_Service_MailTemplateService();
			$resultAdd= $ser->updateEmail($id, $formData);
		}
			
	}
}

