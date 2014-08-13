<?php
class User_LoginController extends Core_Controller_FrontAbstract {
	private $screenName = "ログイン";
	public function init() {
		parent::init ();
		$this->view->headTitle ( 'ログイン' );
	}
	public function indexAction() {
		$this->_redirect ( "/user/login/login" );
	}
	/**
	 * loginAction
	 */
	public function loginAction() {
		//parent::visitByButton ( $this->screenName, "ログイン" );
		$loginForm = new User_Form_Login ();
		if (Core_Util_Helper::isLogin ()) {
			$this->_redirect ( "/user/index" );
		} else {
			$layout = Zend_Layout::getMvcInstance ();
			$layout->setLayout ( 'layout_login' );
		}
		if ($this->_request->isPost ()) {
			$formData = $this->_getAllParams ();
			
			$user = new Core_Models_MstUser ();
			$user->setLoginUsername ( $formData ['login_username'] );
			$user->setLoginPassword ( $formData ['login_password'] );
			
			$remenber = $formData ['check_login'];
			
			$staffSer = new Core_Service_UserService ();
			$userLogin = $staffSer->authorize ( $user );
			if ($userLogin) {
				if ($remenber == 1) {
					$tockenCookie = $staffSer->getLoginToken ();
					$userLogin->setTockenCookie ( $tockenCookie );
					$staffSer->updateUserTokenCookie ( $userLogin, $tockenCookie );
					setcookie ( Core_Util_Const::TOCKEN_COOKIE, $tockenCookie, time () + Core_Util_Helper::getTimeCookie () );
				} else {
					setcookie ( Core_Util_Const::TOCKEN_COOKIE, null, time () - Core_Util_Helper::getTimeCookie () );
				}
				// save sesion
				$this->setSession ( $userLogin );
				//save sesion user id
				//$this->setSessionUserId ( $userLogin->getLoginUsername () );
				
				$userSession =  Core_Util_Helper::getUserSession();
				if (isset($userSession->redirect) && Core_Util_Helper::isNotEmpty($userSession->redirect) ) {
					$arrRedirect = $userSession->redirect;
					$url = "/" . $arrRedirect['module'] . "/" . $arrRedirect['controller'] . "/" . $arrRedirect['action'];
					$params = $arrRedirect['params'];
					foreach ($params as $key => $value) {
						if ($key != "module" && $key != "controller" && $key != "action") {
							$url .= "/" . $key . "/" . $value;
						}
					}
					unset($userSession->redirect);
					$this->_redirect($url);
				} else {
					$this->_redirect ( "index" );
				}
				
			} else {
				$err = Core_Util_Messages::getMessage ( Core_Util_Messages::N001, array (
						'ログインID' 
				) );
				$this->view->errLogin = "1";
				$this->view->err = $err;
			}
			
			$loginForm->populate ( $formData );
		} else {
			// read cookies
			$gettockenCookie = Zend_Controller_Request_Http::getCookie ( Core_Util_Const::TOCKEN_COOKIE );
			
			// set value form login
			if (Core_Util_Helper::isNotEmpty ( $gettockenCookie )) {
				$serUser = new Core_Service_UserService ();
				$userCookie = $serUser->getUserByCookie ( $gettockenCookie );
				
				if ($userCookie) {
					$this->setSession ( $userCookie );
					$this->_redirect ( "index" );
				}
			}
			//set userlogin for User_Form_Login
			$formData = $this->_getAllParams();
			if (isset($formData['login_username'])) {
				$loginForm->setLoginUser ( $formData['login_username'] );
			}
		}
		$this->view->loginForm = $loginForm;
		$this->render ( 'index' );
	}
	/**
	 * logoutAction
	 */
	public function logoutAction() {
		//parent::visitByButton ( $this->screenName, "ログアウト" );
		$user = Core_Util_Helper::getLoginUser ();
		if ($user == null) {
			$this->_redirect ( "/login/login" );
		}
		$userServ = new Core_Service_UserService ();
		$userServ->updateUserTokenCookie ( $user, null );
		Core_Util_Helper::logOutUser();
		//Zend_Session::destroy(true);
		//set login id, destroy
		//$userId = Core_Util_Helper::getLoginUserId ();
		//$this->destroySession();
		//$this->setSessionUserId ( $userId );
		
		setcookie ( Core_Util_Const::TOCKEN_COOKIE, null, time () - Core_Util_Helper::getTimeCookie () );
		$this->_redirect ( "/login/login?login_username=".$user->getLoginUsername() );
	}
	/**
	 * destroySession
	 */
	/* function destroySession() {
		foreach ( Zend_Session::getIterator () as $name => $ns ) {
			Zend_Session::namespaceUnset ( $ns );
		}
	} */
	/**
	 * setSessionUserId
	 * @param unknown $userId
	 */
	/* function setSessionUserId($userId) {
		$loginSession = new Zend_Session_Namespace ( Core_Util_Const::SESSION_NAMESPACE_LOGIN );
		$loginSession->userId = $userId;
	} */
	/**
	 * setSession
	 * @param unknown $userLogin
	 */
	function setSession($userLogin) {
		$loginSession = new Zend_Session_Namespace ( Core_Util_Const::SESSION_NAMESPACE_LOGIN );
		$loginSession->loginInfo = $userLogin;
		
		$sortClassSession = new Zend_Session_Namespace ( Core_Util_Const::SESSION_NAMESPACE_SORT_CLASS );
		$class_serv = new Core_Service_MstClassService ();
		$mc_sorts = $class_serv->getMstClassByItemType ( Core_Util_Const::SORT_CLASS );
		$sortClassSession->sort = $mc_sorts;
		
		$pageClassSession = new Zend_Session_Namespace ( Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS );
		
		$pageclass = $class_serv->getMstClassByItemType ( Core_Util_Const::ITEMS_PER_PAGE_CLASS );
		$arrpageclass = array ();
		foreach ( $pageclass as $p ) {
			//$arrpageclass [$p->getItemCd ()] = $p->getItemName ();
			$arrpageclass [$p->getItemCd ()] = $p;
		}
		$pagerangeclass = $class_serv->getMstClassByItemType ( Core_Util_Const::ITEMS_PER_PAGE_CLASS );
		$arrpagerangeclass = array ();
		foreach ( $pagerangeclass as $p ) {
			//$arrpagerangeclass [$p->getItemCd ()] = $p->getItemName ();
			$arrpagerangeclass [$p->getItemCd ()] = $p;
		}
		$pageClassSession->pageClass = $arrpageclass;
		$pageClassSession->pageRangeClass = $arrpagerangeclass;
	}
}