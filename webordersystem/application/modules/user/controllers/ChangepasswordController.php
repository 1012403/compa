<?php
/**
 * 
 * @author Nguyen
 *
 */
class User_ChangepasswordController extends Core_Controller_FrontAbstract{
	private $screenName = "パスワード変更";

	public function init() {
		parent::init();
		$this->view->headTitle('パスワード変更');
	}
	
	/**
	 * indexAction
	 */
	public function indexAction() {
		parent::visitByButton($this->screenName, "");
		$changePasswdForm = new User_Form_ChangePassword();
		$this->view->changePasswdForm = $changePasswdForm;
	}
	
	/**
	 * changepasswdAction
	 */
	public function changepasswdAction() {
		parent::visitByButton($this->screenName, "パスワード変更");
		$changePasswdForm = new User_Form_ChangePassword ();
		$formData = $this->_getAllParams ();

		$username=$formData['username'];
		$token=$formData['token'];
		if(isset($username) && isset($token)){
			$this->checkDate_Tocken($username , $token);
			$changePasswdForm->getElement("hiddenUsername")->setValue($username);
			$changePasswdForm->getElement("hiddenToken")->setValue($token);
			$changePasswdForm->getElement("username")->setValue($username);
			$this->view->changePasswdForm = $changePasswdForm;
			$this->render ( 'index' );
		}else {
			$this->_redirect("login");
		}
		
	}
	
	/**
	 * processchangepasswordAction
	 */
	public function processchangepasswordAction() {
		parent::visitByButton($this->screenName, "更新する");
		$changePasswdForm = new User_Form_ChangePassword ();
		$formData = $this->_getAllParams ();
		$this->view->changePasswdForm = $changePasswdForm;
		
		if ($this->_request->isPost ()) {
			$username = $formData ['hiddenUsername'];
			$password = $formData ['passwd'];
			$service = new Core_Service_UserService ();
			$success = $service->changePasswd ( $username, $password );
			if ($success) {
				echo "true".$username;
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender(TRUE);
			}
		}
	}
	
	function checkDate_Tocken($username , $token){
			$service = new Core_Service_UserService ();
			$user = $service->getUserByUsername ( $username );
			if($user){
				$date= $user->getChangePassDate();
					
				$isValidDate= $service->checkPasswdDate($date);
				$isTokenValid = $service->checkToken($username, $token);
					
				if(!$isValidDate){
					$err = Core_Util_Messages::getMessage ( Core_Util_Messages::N003, array ('ログインID') );
					$this->view->err = $err;
					$this->view->buttonErr="1";
				}
				else if(!$isTokenValid){
					$err = Core_Util_Messages::getMessage ( Core_Util_Messages::N002, array ('ログインID') );
					$this->view->err = $err;
					$this->view->buttonErr="1";
				}
			}else {
				$err = Core_Util_Messages::getMessage ( Core_Util_Messages::N002, array ('ログインID') );
				$this->view->err = $err;
				$this->view->buttonErr="1";
			}
	}
}