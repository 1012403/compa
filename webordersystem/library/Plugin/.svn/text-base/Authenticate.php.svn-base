<?php
/**
 * All Rights Reserved, Copyright (c) 株式会社アイ・エンター 2013
 */
class Plugin_Authenticate extends Zend_Controller_Plugin_Abstract {
	public function routeStartup(Zend_Controller_Request_Abstract $request) {

	}

	public function routeShutdown(Zend_Controller_Request_Abstract $request) {

	}

	public function dispatchLoopStartup(
			Zend_Controller_Request_Abstract $request) {

	}

	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$moduleName 	= $this->getRequest()->getModuleName();
		$controllerName = $this->getRequest()->getControllerName();
		$actionName 	= $this->getRequest()->getActionName();
		$params 		= $this->getRequest()->getParams();
		
		if ($moduleName == 'admin') {
			
			
			if($controllerName != "login"
					&& $controllerName != "changepassword") {
				
				if (!Core_Util_Helper::isLoginAdmin()) {
					$adminSession = Core_Util_Helper::getAdminSession();
					$arrRedirect = array();
					$arrRedirect['action'] = $actionName;
					$arrRedirect['controller'] = $controllerName;
					$arrRedirect['module'] = $moduleName;
					$arrRedirect['params'] = $params;
					$adminSession->redirect = $arrRedirect;
					
					header("Location:/admin/login/");
					return;
				}
			}
		} else {
		
			if($controllerName != "login"
				&& $controllerName != "changepassword"
				&& $controllerName != "sendlink") {
				
				if (!Core_Util_Helper::isLogin()) {
					$userSession = Core_Util_Helper::getUserSession();
					$arrRedirect = array();
					$arrRedirect['action'] = $actionName;
					$arrRedirect['controller'] = $controllerName;
					$arrRedirect['module'] = $moduleName;
					$arrRedirect['params'] = $params;
					$userSession->redirect = $arrRedirect;
					header("Location:/login/login");
					return;
				}
			}
		}
		
// 		if ($controllerName != "login" && $controllerName != "changepassword" && $controllerName != "error") {
// 			if (Communityfloor_Util_Helper::checkAuthByPath($moduleName, $controllerName) === false) {
// 				$errorSession = new Zend_Session_Namespace(
// 						Communityfloor_Util_Const::SESSION_NAMESPACE_ERROR);
// 				$errorSession->messages = Communityfloor_Util_Messages::getMessage(
// 						Communityfloor_Util_Messages::E002, array());
// 				header("Location:/" . $moduleName . '/error/error');
// 				return;
// 			}
// 		}

	}

	public function postDispatch(Zend_Controller_Request_Abstract $request) {

	}

	public function dispatchLoopShutdown() {

	}
}

$front = Zend_Controller_Front::getInstance();

?>