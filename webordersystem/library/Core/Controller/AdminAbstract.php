<?php
abstract class Core_Controller_AdminAbstract extends
		Core_Controller_Abstract {

	public function init() {
		parent::init();

		//Import file js into header of layout

		$layout = Zend_Layout::getMvcInstance();
		$flagAdminSession = Core_Util_Helper::isLoginAdmin();
		
		$userLogin = Core_Util_Helper::getLoginAdmin();
		if (isset($userLogin) && $userLogin != NUll && $userLogin->getAdminFlg() !== Core_Util_Const::ADMIN_TYPE_NO && $flagAdminSession) {
			$layout->setLayout('layout_admin_2');
		}else{
			$layout->setLayout('layout_login');
		}
		$this->view->userLogin = $userLogin;

		$formData = $this->_getAllParams();

		$this->view->conditions = array();
		if (isset($formData['condition'])){
			$this->view->conditions = $formData['condition'];
			$this->view->conditionkeys = $formData['conditionkey'];
		}

		if (isset($formData['date_start'])){
			$this->view->date_start = $formData['date_start'];
		}

		if (isset($formData['date_end'])){
			$this->view->date_end = $formData['date_end'];
		}

		if (isset($formData['username'])){
			$this->view->username = $formData['username'];
		}

		if (isset($formData['oper_contract'])){
			$this->view->oper_contract = $formData['oper_contract'];
		}

		if (isset($formData['oper_detail'])){
			$this->view->oper_detail = $formData['oper_detail'];
		}

	    if (isset($formData['keyword'])){
			$this->view->keyword = $formData['keyword'];
		}

		$sortClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_SORT_CLASS);
		$this->view->sorts = $sortClassSession->sort;
		
		$displayNamespace = new Zend_Session_Namespace('Display');
		if (!isset($displayNamespace->sort))
		{
			$sorts = $sortClassSession->sort;
			if ($sorts != null && is_array($sorts)):
                foreach ( $sorts as $id => $sort ) :
                	Core_Util_Const::$SORT_DEFAULT=$sort->getItemCD();
					$displayNamespace->sort = Core_Util_Const::$SORT_DEFAULT;
					break;
                endforeach;
			endif;
		}
	}

	/**
	 * create left menu other admin
	 * @see Core_Controller_Abstract::createMenu()
	 */
	protected function createMenuOther(){
		$menuother = $this->view->render('index/menuother.phtml');
		$this->view->catehtml = $menuother;
	}
	
	/**
	 * write log
	 * @param string $userId
	 * @param string $logKind
	 * @param string $operationContent
	 * @param string $operationDetail
	 */
	protected function logVisit($screenName, $visitBy, $labelButtonOrLink, $param = null){
		$logKind = Core_Util_Logger::LOG_KIND_VISIT;
	
		$operationContent = Core_Util_Logger::LOG_SCREEN_LABEL . "「" . $screenName . "」;";
		if ($visitBy == Core_Util_Logger::LOG_VISIT_BY_BUTTON ) {
			$operationContent = $operationContent . Core_Util_Logger::LOG_BUTTON_LABEL;
		}else if ($visitBy == Core_Util_Logger::LOG_VISIT_BY_LINK) {
			$operationContent = $operationContent . Core_Util_Logger::LOG_LINK_LABEL;
		}else if ($visitBy == Core_Util_Logger::LOG_VISIT_BY_DROPDOWN) {
			$operationContent = $operationContent . Core_Util_Logger::LOG_DROPDOWN_LABEL;
		}
		$operationContent = $operationContent . "「" . $labelButtonOrLink . "」";
		$operationDetail = "";
		if ($param !== null && is_array($param)) {
			//Edit 2014/06/24 Hungnh START 
			//$operationDetail = json_encode($param);
			$operationDetail = '{';
			$numCount = 1;
			foreach ($param as $key => $value) {
				$operationDetail .= '"' . $key . '":' . '"' . $value . '"';
				if($numCount === count($param)){
					$operationDetail .= '}';
				} else {
					$operationDetail .= ', ';
				}
				$numCount++;
			}
			//Edit 2014/06/24 Hungnh END
		}
		Core_Util_Logger::writeLogForAdmin($logKind, $operationContent, $operationDetail);
	}
	
	protected function visitByButton($screenName, $labelButton, $param = null) {
		$this->logVisit($screenName, Core_Util_Logger::LOG_VISIT_BY_BUTTON, $labelButton, $param);
	}
	
	protected function visitByLink($screenName, $labelLink, $param = null) {
		$this->logVisit($screenName, Core_Util_Logger::LOG_VISIT_BY_LINK, $labelLink, $param);
	}
	
	protected function visitByDropdown($screenName, $labelLink, $param = null) {
		$this->logVisit($screenName, Core_Util_Logger::LOG_VISIT_BY_DROPDOWN, $labelLink, $param);
	}
	
	protected function visitByOperation($screenName, $elementType, $elementName, $paramKeys = null) {
		$param = $this->_getAllParams();
		$paramsToLog = null;
	
		if ($paramKeys != null) {
			while($pKey = key($paramKeys)) {
				$pKeyToGetVal = current($paramKeys);
				$pVal = null;
				if (isset($param[$pKeyToGetVal])){
					$pVal = $param[$pKeyToGetVal];
				}
	
				if (!empty($pVal)) {
					$paramsToLog[$pKey] = $pVal;
				}
				next($paramKeys);
			}
		}
	
		$this->logVisit($screenName, $elementType, $elementName, $paramsToLog);
	}
	
	protected function visitByButtonInclParams($screenName, $labelButton) {
		$param = $this->_getAllParams();
		$this->logVisit($screenName, Core_Util_Logger::LOG_VISIT_BY_BUTTON, $labelButton, $param);
	}
	
	protected function visitByLinkInclParams($screenName, $labelLink) {
		$param = $this->_getAllParams();
		$this->logVisit($screenName, Core_Util_Logger::LOG_VISIT_BY_LINK, $labelLink, $param);
	}
	
	protected function exportCSVToClient($csvData, $filename) {
		$this->noRender();
		$this->disableLayout();
		
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') != true)
		{
			$filename = mb_convert_encoding($filename, 'SJIS-WIN', 'UTF-8');
		}
			
		//header('Content-type: application/csv');
		header('Content-Encoding: UTF-8');
		header('Content-type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $filename .  '"');
		
		echo "\xEF\xBB\xBF";
		echo $csvData;
	}
	
	protected function getScreenName() {
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS_ADMIN);
		return $pageClassSession->lastViewName;
	}
	
	protected function setScreenName($nameToSet) {
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS_ADMIN);
		$pageClassSession->lastViewName = $nameToSet;
	}
}
