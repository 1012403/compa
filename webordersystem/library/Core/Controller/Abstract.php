<?php
abstract class Core_Controller_Abstract extends Zend_Controller_Action {

	public function init() {

	}
	
	//Edit 20140613 Hungnh START
// 	protected function createMenu($arrProductId = null, $formaction = null){
// 		$serv = new Core_Service_MstCategoryService();
// 		$m_categorys = $serv->getListCategory($arrProductId);
// 		$this->view->m_categorys = $m_categorys;
// 		$this->view->formaction = $formaction;
// 		$catehtml = $this->view->render('category/show.phtml');
// 		$this->view->catehtml = $catehtml;
// 	}
	
	protected function createMenu($keyword = null, $arrConditions = null, $tylelist = null, $orderBy = null, $formaction = null){
		$serv = new Core_Service_MstCategoryService();
		$m_categorys = $serv->getListCategory($keyword, $arrConditions, $tylelist, null);
		$this->view->m_categorys = $m_categorys;
		$this->view->formaction = $formaction;
		$catehtml = $this->view->render('category/show.phtml');
		$this->view->catehtml = $catehtml;
	}
	//Edit 20140613 Hungnh END
	
	protected function disableLayout() {
		$this->view->layout()->disableLayout();
	}
	
	protected function noRender() {
		$this->getHelper('viewRenderer')->setNoRender();
	}
	
	protected function setAjaxAction() {
		$this->disableLayout();
		$this->noRender();
	}

}
