<?php
class Admin_LoginController extends Core_Controller_AdminAbstract{
	private $screenName = "ログイン　【管理サイト】";
	public function init() {
		parent::init();
        $this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/user/login.css');
        parent::createMenuOther();
	}

	public function indexAction() {
		//parent::visitByButton($this->screenName, "ログイン　【管理サイト】");
        $loginForm = new Admin_Form_Login();
		$flag = Core_Util_Helper::isLoginAdmin();
		if($flag) {
			$this->_redirect("/admin/index");
		}
		if($this->_request->isPost()) {
			$formData = $this->_getAllParams();
			$user = new Core_Models_MstUser();
			$user->setLoginUsername($formData['login_username']) ;
			$user->setLoginPassword($formData['login_password']) ;
			$remenber=$formData['check_login'];
			
			$staffSer = new Core_Service_UserService();
			$userLogin = $staffSer->loginAdmin($user);
			if ($userLogin) {
				if($remenber==1){
					$tockenCookie = $staffSer->getLoginToken();
					$userLogin->setTockenCookieAdmin($tockenCookie);
					$staffSer->updateUserTokenCookieAdmin($userLogin, $tockenCookie);
					setcookie(Core_Util_Const::TOCKEN_COOKIE_ADMIN, $tockenCookie,time()+Core_Util_Helper::getTimeCookie());
					
				}else {
					setcookie(Core_Util_Const::TOCKEN_COOKIE_ADMIN, null,time()-Core_Util_Helper::getTimeCookie());
				}
				// save sesion
				$this->setSession($userLogin);
				
				
				//save sesion user id
				//$this->setSessionUserId ( $userLogin->getLoginUsername () );
				$adminSession =  Core_Util_Helper::getAdminSession();
				if (isset($adminSession->redirect) && Core_Util_Helper::isNotEmpty($adminSession->redirect) ) {
					$arrRedirect = $adminSession->redirect;
					$url = $arrRedirect['module'] . "/" . $arrRedirect['controller'] . "/" . $arrRedirect['action'];
					$params = $arrRedirect['params'];
					foreach ($params as $key => $value) {
						if ($key != "module" && $key != "controller" && $key != "action") {
							$url .= "/" . $key . "/" . $value;
						}
					}
					unset($adminSession->redirect);
					$this->_redirect($url);
				} else {
					$this->_redirect("/admin/index");
				}
			}else{
				$err = Core_Util_Messages::getMessage(Core_Util_Messages::N001, array('ログインID'));
				$this->view->err = $err;
			}
		}else {
			$tockenCookie = Zend_Controller_Request_Http::getCookie(Core_Util_Const::TOCKEN_COOKIE_ADMIN);
			if (Core_Util_Helper::isNotEmpty($tockenCookie)){
				$serUser=new Core_Service_UserService();
				$userCookie=$serUser->getAdminByCookie($tockenCookie);

				if ($userCookie){
					$this->setSession($userCookie);
					$this->_redirect("/admin/index");
				}
			}

			//set userlogin for User_Form_Login
			$formData = $this->_getAllParams();
			if (isset($formData['login_username'])) {
				$loginForm->setLoginUser ( $formData['login_username'] );
			}
		}
		$this->view->loginForm = $loginForm;
	}
	/**
	 * loginAction
	 */
	public function loginAction() {
		$this->_forward("index");
	}

	/**
	 * logoutAction
	 */
	public function logoutAction() {
		$user = Core_Util_Helper::getLoginAdmin();
		if($user==null){
			$this->_redirect("/login/login");
		}
		$userServ = new Core_Service_UserService();
		$userServ->updateUserTokenCookieAdmin($user, null);
		//Zend_Session::destroy(true);
		Core_Util_Helper::logOutAdmin();
		
		setcookie(Core_Util_Const::TOCKEN_COOKIE_ADMIN,null,time()-Core_Util_Helper::getTimeCookie());
		$this->_redirect("admin/login?login_username=".$user->getLoginUsername());

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
	function setSession($userLogin){
		$loginSession = new Zend_Session_Namespace(Core_Util_Const::SESSION_NAMESPACE_LOGIN_ADMIN);
		$loginSession->loginInfoAdmin = $userLogin;
	
		$sortClassSession = new Zend_Session_Namespace(Core_Util_Const::SESSION_NAMESPACE_SORT_CLASS);
		$class_serv = new Core_Service_MstClassService();
		$mc_sorts = $class_serv->getMstClassByItemTypeDispl(Core_Util_Const::SORT_CLASS);
		//$mc_sorts = $class_serv->getMstClassByItemType(Core_Util_Const::SORT_CLASS);
		$sortClassSession->sort = $mc_sorts;
			
		$pageClassSession = new Zend_Session_Namespace(Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
			
		$pageclass = $class_serv->getMstClassByItemType(Core_Util_Const::ITEMS_PER_PAGE_CLASS);
		$arrpageclass = array();
		/* @var $p Core_Models_MstClass */
		foreach ($pageclass as $p){
			//$arrpageclass[$p->getItemCd()] = $p->getNote1();
			$arrpageclass[$p->getItemCd()] = $p;
		}
		$pagerangeclass = $class_serv->getMstClassByItemType(Core_Util_Const::ITEMS_PER_PAGE_CLASS);
		$arrpagerangeclass = array();
		/* @var $p Core_Models_MstClass */
		foreach ($pagerangeclass as $p){
			//$arrpagerangeclass[$p->getItemCd()] = $p->getNote2();
			$arrpagerangeclass[$p->getItemCd()] = $p;
		}
		$pageClassSession->pageClass = $arrpageclass;
		$pageClassSession->pageRangeClass = $arrpagerangeclass;
	}
}
