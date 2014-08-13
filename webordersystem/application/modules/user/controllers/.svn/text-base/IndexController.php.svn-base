<?php
class User_IndexController extends Core_Controller_FrontAbstract {
	private $screenName = "トップページ";
	public function init() {
		parent::init();
	}

	public function indexAction() {
		//parent::visitByLink($this->screenName, "");
		parent::setScreenName("トップページ");
		parent::visitByOperation(parent::getScreenName(), null, null);
		//Edit 2014/06/13 Hungnh START
// 		$serv = new Core_Service_MstCategoryService();
// 		$m_products = $serv->queryProductIds(null);
		$formaction = $this->view->config['url_base'].'/product/index';
//		parent::createMenu($m_products, $formaction);
		parent::createMenu(null, null, null, null, $formaction);
		//Edit 2014/06/13 Hungnh END
		
		$data = "this is front data";
		$this->view->data = $data;
		$productSer = new Core_Service_MstProductService();
		
		$idUserLogin = Core_Util_Helper::getIdUserLogin();
		
		
		// get limit items on top page
		$conTypeProductTopLimit = Core_Util_Const::ITEMS_PER_PAGE_CLASS;
		
		$mstClassSer = new Core_Service_MstClassService();
		$limitTopProduct = $mstClassSer->getItemTypeNote1($conTypeProductTopLimit, Core_Util_Const::ITEM_CD_ITEMS_TOP_PRODUCT);
		$limitTopProduct = Core_Util_Helper::nullEmptyToValue($limitTopProduct,  Core_Util_Const::ITEMS_TOP_PRODUCT_DEFAULT);
		
		// get products
		$arrFeaProduct = $productSer->getFeatureProduct($idUserLogin, $limitTopProduct);
		$arrBestSell = $productSer->getBestSellProduct($limitTopProduct);
		$arrProUserLike = $productSer->getProductuserLike($idUserLogin,$limitTopProduct);
		
		$this->view->arrFeaProduct  = $arrFeaProduct;
		$this->view->arrBestSell    = $arrBestSell;
		$this->view->arrProUserLike = $arrProUserLike;
		
		$noticeSer = new Core_Service_NoticeInfoService();
		
		$news = $noticeSer->getNews();
		$this->view->news = $news;
		
		$userLogin = Core_Util_Helper::getLoginUser();
		if ($userLogin !== null && Core_Util_Helper::isNotEmpty($userLogin->getSalesId())) {
			$notice = $noticeSer->getNoticeOfSale($userLogin->getSalesId());
			$this->view->notice = $notice;
		}
	}
	
}