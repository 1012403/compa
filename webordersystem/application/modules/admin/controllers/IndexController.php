<?php
class Admin_IndexController extends Core_Controller_AdminAbstract {
	private $screenName = "トップページ《管理サイト》";

	public function init() {
		parent::init();
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/includes/ckeditor/ckeditor.js');
	}

	public function indexAction() {
		parent::setScreenName($this->screenName);
		parent::visitByOperation(parent::getScreenName(), null, null, null);
		
		$flag = Core_Util_Helper::isLoginAdmin();
		if(!$flag) {
			$this->_redirect("/admin/login");
		}
		//Edit 2014/06/13 Hungnh START
// 		$serv = new Core_Service_MstCategoryService();
// 		$m_products = $serv->queryProductIds(null);
		$formaction = $this->view->config['url_base'].'/admin/product';
// 		parent::createMenu($m_products, $formaction);
		parent::createMenu(null, null, null, null, $formaction);
		//Edit 2014/06/13 Hungnh END

		$idUserLogin = Core_Util_Helper::getIdAdminLogin();
		$proServ = new Core_Service_MstProductService();
		$arrFeatureProducts = $proServ->getFeatureProductOfSale($idUserLogin);
		$this->view->arrFeatureProducts = $arrFeatureProducts;

		$noticeServ = new Core_Service_NoticeInfoService();
		$arrNotice = $noticeServ->getListNoticeOfSaleUser($idUserLogin);
		$this->view->arrNotice = $arrNotice;

		$notice = $noticeServ->getNoticeOfSale($idUserLogin);
		if ($notice != null) {
			$this->view->idcurnotice = $notice->getNoticeId();
			$this->view->datenotice = str_replace("-", "/", $notice->getUpdateDate());
		} else {
			$this->view->idcurnotice = null;
		}
	}

	public function getnoticeAction() {
		$allParams = $this->_getAllParams();
		$id = $allParams['id'];
		
		$params = null;
		if (!empty($id)) {
			$params['id'] = $id;
		}
		parent::visitByLink(parent::getScreenName(),"お知らせ履歴", $params);
		
		$this->disableLayout();
		$this->noRender();
		$noticeSer = new Core_Service_NoticeInfoService();
		$notice = $noticeSer->getNoticeById($id);
		if ($notice !== null) {
			echo $notice->getContent();
		} else {
			echo "";
		}
	}

	public function updatenoticeAction(){
		$this->disableLayout();
		$this->noRender();
		$allParams = $this->_getAllParams();
		$id = $allParams['id'];
		$date = $allParams['date'];
		$content = $allParams['content'];
		
		$params = null;
		if (!empty($id)) {
			$params["id"] = $id;
		}
		if (!empty($date)) {
			$params["date"] = $date;
		}
		if (!empty($content)) {
			$params["content"] = $content;
		}
		parent::visitByButton(parent::getScreenName(),"更新", $params);
		
		$noticeService = new Core_Service_NoticeInfoService();
		$success = true;
		if (Core_Util_Helper::isEmpty($id)) {
			// new mode
			$success = $noticeService->insertNotice($date, $content);

		} else {
			// update mode
			$success = $noticeService->updateNotice($id, $date, $content);
		}
		$arr = array();
		if ($success) {
			$arr['success'] = true;
		} else {
			$arr['success'] = false;
		}

		echo json_encode($arr);
	}
	
	public function deletenoticeAction() {
		$this->setAjaxAction();
		$allParams = $this->_getAllParams();
		$id = $allParams['id'];
		
		$params = null;
		if (!empty($id)) {
			$params["id"] = $id;
		}
		parent::visitByButton(parent::getScreenName(),"削除", $params);
		
		$noticeService = new Core_Service_NoticeInfoService();
		$success = true;
		if (Core_Util_Helper::isNotEmpty($id)) {
			$idUpdate = Core_Util_Helper::getIdAdminLogin();
			$success = $noticeService->deleteNotice($id, $idUpdate);
			if ($success) {
				echo "true";
			} else {
				echo "Can not update";
			}
		}
	}

	public function getlistnoticeAction(){
		//parent::visitByButton($this->screenName,"");
		$this->disableLayout();
		$idUserLogin = Core_Util_Helper::getIdAdminLogin();
		$noticeServ = new Core_Service_NoticeInfoService();
		$arrNotice = $noticeServ->getListNoticeOfSaleUser($idUserLogin);
		$this->view->arrNotice = $arrNotice;
	}

	public function checknoticedateAction(){
		//parent::visitByButton($this->screenName,"");
		$this->disableLayout();
		$this->noRender();
		$allParams = $this->_getAllParams();
		$id = $allParams['id'];
		$date = $allParams['date'];
		$idUserLogin = Core_Util_Helper::getIdAdminLogin();
		$noticeService = new Core_Service_NoticeInfoService();
		$isExist = $noticeService->checkNoticeDate($id, $date, $idUserLogin);
		if ($isExist) {
			echo "false";
		} else {
			echo "true";
		}
	}
}
