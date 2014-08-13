<?php
abstract class Core_Controller_FrontAbstract extends Core_Controller_Abstract {

	public function init() {
		parent::init();
		$layout = Zend_Layout::getMvcInstance();

		$flagSession = Core_Util_Helper::isLogin();

		$controller = $this->getRequest()->getControllerName();
		$action = $this->getRequest()->getActionName();

		$userLogin = Core_Util_Helper::getLoginUser();
		if ($controller === "sendlink"){
			$layout->setLayout('layout_login');
		}else if (isset($userLogin) && $userLogin != NUll && $flagSession) {
			$layout->setLayout('layout_front');
		}else{
			$layout->setLayout('layout_login');
		}

		$sizeOfOrderCart = 0;
		$sizeOfQuotationCart = 0;
		if ($userLogin !== null) {
			$sizeOfOrderCart = $userLogin->sizeOrderCart();
			$sizeOfQuotationCart = $userLogin->sizeQuotationCart();
		}
		$this->view->userLogin = $userLogin;
		$this->view->sizeOrderCart = $sizeOfOrderCart;
		$this->view->sizeQuotationCart = $sizeOfQuotationCart;

		$formData = $this->_getAllParams();
		$this->view->conditions = array();
		if (isset($formData['condition'])){
			$this->view->conditions = $formData['condition'];
			$this->view->conditionkeys = $formData['conditionkey'];
		}
		if (isset($formData['keyword'])){
			$this->view->keyword = $formData['keyword'];
		}
		if (isset($formData['tylelist'])){
			$this->view->tylelist = $formData['tylelist'];
		}
		if (isset($formData['start_date'])){
			$this->view->start_date = $formData['start_date'];
		}
		if (isset($formData['end_date'])){
			$this->view->end_date = $formData['end_date'];
		}
		if (isset($formData['id'])){
			$this->view->product_id = $formData['id'];
		}

		if (isset($formData['controller'])){
			$this->view->mcontroller = $formData['controller'];
		}

		$sortClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_SORT_CLASS);
		$this->view->sorts = $sortClassSession->sort;
	}

// 	protected function createMenu($arrProductId = null, $formaction = null){
// 		$serv = new Core_Service_MstCategoryService();
// 		$m_categorys = $serv->getListCategory($arrProductId);
// 		$this->view->m_categorys = $m_categorys;
// 		$this->view->formaction = $formaction;
// 		$catehtml = $this->view->render('category/show.phtml');
// 		$this->view->catehtml = $catehtml;
// 	}

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
			//Edit 2014/06/24 Hieunm START 
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
			//Edit 2014/06/24 Hieunm END
		}
		Core_Util_Logger::writeLog($logKind, $operationContent, $operationDetail);
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

	protected function visitByDropdownInclParams($screenName, $labelLink) {
		$param = $this->_getAllParams();
		$this->logVisit($screenName, Core_Util_Logger::LOG_VISIT_BY_DROPDOWN, $labelLink, $param);
	}

	protected function getScreenName() {
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
		return $pageClassSession->lastViewName;
	}

	protected function setScreenName($nameToSet) {
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
		$pageClassSession->lastViewName = $nameToSet;
	}
}
