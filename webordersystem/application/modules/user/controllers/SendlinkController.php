<?php
class User_SendLinkController extends Core_Controller_FrontAbstract {
	private $screenName = "パスワード変更依頼";
	
	public function init() {
		parent::init ();
		$this->view->headTitle ( 'パスワード変更依頼' );
	}

	/**
	 * indexAction
	 */
	public function indexAction() {
		parent::visitByLinkInclParams($this->screenName, "");
		$sendLinkForm = new User_Form_SendLink ();
		$this->view->sendLinkForm = $sendLinkForm;
	}

	/**
	 * sendlinkAction
	 */
	public function sendlinkAction() {
		parent::visitByLinkInclParams($this->screenName, "パスワードを忘れた場合（再設定）");
		$sendLinkForm = new User_Form_SendLink ();
		$this->view->sendLinkForm = $sendLinkForm;
		
		$username = $this->getRequest ()->getParam ( "login_username", null );
		$email=$this->getRequest ()->getParam ( "email", null );
		
		$formData = $this->_getAllParams();
		
		$service = new Core_Service_UserService ();
		
		if(Core_Util_Helper::isNotEmpty($username) &&Core_Util_Helper::isNotEmpty($email)){
			$user = $service->getUserByUsername ( $username );
			$isEmail=$service->checkEmail($username, $email);
			if (!$user){
				$err = Core_Util_Messages::getMessage ( Core_Util_Messages::N004, array ( 'ログインID') );
				$this->view->err = $err;
				$this->view->flagError = "1";
				$sendLinkForm->populate($formData);
				$this->render('index');
			}
			else if(!$isEmail){
				$err = Core_Util_Messages::getMessage ( Core_Util_Messages::N005, array ( 'ログインID') );
				$this->view->err = $err;
				$this->view->flagError = "2";
				$sendLinkForm->populate($formData);
				$this->render('index');
			}
			else {
				$newToken = $service->updateTokenDateSendlink ( $username );
				if ($newToken !== null) {
					$title=null;
					$header=null;
					$footer=null;
					$sermail=new Core_Service_MailTemplateService();
					$mailtemp=$sermail->getMailTemplateByItem(Core_Util_Const::MAIL_TEMPLATE_6,Core_Util_Const::MAIL_TEMPLATE_FLAG_1);
					foreach ($mailtemp as $value){
						$title= $value['title'];
						$header=$value['header'];
						$footer=$value['footer'];
					}
					//$mail
					$mail = $user->getEmail ();
					$urlBase = Zend_Registry::get('url_base');
					$link = $urlBase ."/changepassword/changepasswd?username=" . $username . "&token=" . $newToken;
					$header = str_replace("「URL」", $link, $header);
					$mailInfo = array ();
					$mailInfo ['to'] = $mail;
					$mailInfo ['from'] = "";
					$mailInfo ['subject'] = $title;
					$mailInfo ['body'] = $header."\n".$footer;
					$sendMailSuccess = Core_Util_Mail::sendMail ( $mailInfo);
					if ($sendMailSuccess) {
						echo "true";
						$this->_helper->layout->disableLayout();
						$this->_helper->viewRenderer->setNoRender(TRUE);
					}
					$this->view->link = $link;
				}
			}
		}
	}

}
