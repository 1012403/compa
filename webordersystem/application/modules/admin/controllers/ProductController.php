<?php
class Admin_ProductController extends Core_Controller_AdminAbstract {
  private $screenName = "商品カテゴリー《管理サイト》";
  private $screenNameView = "商品詳細《管理サイト》";
  private $screenNameEdit = "商品編集《管理サイト》";
  private $screenNameAdd = "商品追加《管理サイト》";
  public function init() {
    parent::init();

      $this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/admin/product.css');
      $this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/includes/ckeditor/ckeditor.js');
      $this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/includes/ckeditor/ckfinder/ckfinder.js');

      $this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery.form.js');
      $this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/admin/product.js');
      //Datetpicker
      $this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/jquery-ui.css');
      $this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery-ui.js');
  }

  public function indexAction() {
    $post = $this->_getAllParams();
    $page = $this->_request->getParam('page', 1);

    // Log operation
    $operateElementType = null;
    $operateElementName = null;
    $afterDelete = null;
    if (isset($post["operateElementType"])) {
      $operateElementType = $post["operateElementType"];
    }
    if (isset($post["operateElementName"])) {
      $operateElementName = $post["operateElementName"];
    }
    if (isset($post["AfterDelete"])) {
      $afterDelete = $post["AfterDelete"];
    }
    
    $params = null;
    if (isset($post["keyword"]) && !empty($post["keyword"])) {
      $params['keyword'] = $post["keyword"];
    }
    if (isset($post["category_id"]) && !empty($post["category_id"])) {
      $params['category_id'] = $post["category_id"];
    }
    if (isset($post["productSelectSort"]) && !empty($post["productSelectSort"])) {
      $params['productSelectSort'] = $post["productSelectSort"];
    }
    $params['page'] = $page;

    if (!isset($post["mode"]) || $post["mode"] != "insertCategory") {
      if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_BUTTON
        || $operateElementType == Core_Util_Logger::LOG_VISIT_BY_LINK) {
        parent::logVisit(parent::getScreenName(), $operateElementType, $operateElementName, $params);
        parent::setScreenName($this->screenName);
      } else {
        parent::setScreenName($this->screenName);
        parent::logVisit(parent::getScreenName(), $operateElementType, $operateElementName, $params);
      }
    }

	//Form
	$productForm = new Admin_Form_Product();
	    if(!$afterDelete){
		    if($this->getRequest()->isPost()){
		          if($post["mode"] == "changefilter"){
		                $this->changesort($post["productSelectSort"]);
		          } else if($post["mode"] == "insertCategory") {
		            parent::visitByButton(parent::getScreenName(),"追加(商品のカテゴリ)");
		              $this->insertCategory($post);
		          }
		//	        $productForm->populate($post);
		    }
	    }
	$this->view->form = $productForm;

      //View category
      $serv = new Core_Service_MstProductService();
      //Edit 2014/06/13 Hungnh START
    $user =  Core_Util_Helper::getLoginAdmin();
    if(!$afterDelete){
   		$m_products = $serv->queryProductIds($this->view->keyword, $this->view->conditionkeys, null, $user);
	    //parent::createMenu($m_products,"/admin/product");
	      parent::createMenu($this->view->keyword, $this->view->conditionkeys, null, null, "/admin/product");
	      //Edit 2014/06/13 Hungnh END
	    $adminSession =  Core_Util_Helper::getAdminSession();
	    $adminSession->productIds = $m_products;
	    $adminSession->lastKeyword = $this->view->keyword;
	    $adminSession->lastConditionKeys = $this->view->conditionkeys;
    } else{
    	$adminSession =  Core_Util_Helper::getAdminSession();
    	$m_products = $serv->queryProductIds($adminSession->lastKeyword, $adminSession->lastConditionKeys, null, $user);
    	$this->view->keyword = $adminSession->lastKeyword;
	    parent::createMenu($adminSession->lastKeyword, $adminSession->lastConditionKeys,
	     null, null, "/admin/product");
	     
    }

// 		if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_BUTTON) {
// 			parent::visitByButton(parent::getScreenName(), $operateElementName, $params);
// 		} elseif ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_LINK) {
// 			parent::visitByLink(parent::getScreenName(), $operateElementName, $params);
// 		} elseif ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_DROPDOWN) {
// 			parent::visitByDropdown(parent::getScreenName(), $operateElementName, $params);
// 		} else {
// 			parent::logVisit(parent::getScreenName(), null, null, $params);
// 		}

    $paginatorData ['itemCountPerPage'] = Core_Util_Const::ITEMS_PER_PAGE;
    $paginatorData ['pageRange'] = Core_Util_Const::PAGE_RANGE;
    $paginatorData ['currentPage'] = $page;
    Zend_Paginator::setDefaultScrollingStyle('Sliding');
    Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

    //Get data
    $productSrv = new Core_Service_MstProductService();
    if(!$afterDelete){
      $products = $productSrv->getAllProductAvailable($this->view->keyword,
      $this->view->conditionkeys,$paginatorData);
    } else{
    	$products = $productSrv->getAllProductAvailable($adminSession->lastKeyword, 
    	$adminSession->lastConditionKeys,$paginatorData);
    }
    
      $idLoginUser = Core_Util_Helper::getIdAdminLogin();
      if ($products !== null &&  $products !== false && is_array($products) ) {
        foreach ($products as $key => $product) {
          $product['isFeaturedProduct'] = $this->isAddFeatured($product['productId'], $idLoginUser);
          $products[$key] = $product;
        }
      }
      $this->view->products = $products;

      //Pagging
    $totalItem =count($m_products);
    $objPaginator = new Core_Util_Paginator();
    $paginator = $objPaginator->createPaginator( $totalItem, $paginatorData );
    $this->view->paginator = $paginator;
    $paginator->setView($this->view);

  }

  public function viewAction(){
      $productId = $this->_request->getParam("id", null);

      $params = null;
      if (!empty($productId)) {
        $params['product_id'] = $productId;
      }
      if (parent::getScreenName() == $this->screenNameView
        || parent::getScreenName() == "トップページ　《管理サイト》") {
        parent::visitByButton(parent::getScreenName(),"詳細はこちら", $params);
      } else {
        parent::visitByButton(parent::getScreenName(),"詳細", $params);
      }
      parent::setScreenName($this->screenNameView);

      if(Core_Util_Helper::isEmpty($productId)){
          $this->_redirect("/admin/product/index");
          return;
      }

        $productSrv = new Core_Service_MstProductService();
      $product = $productSrv->getByProductId($productId);
    if ($product === null || $product === false) {
      $this->_redirect("/admin/product/index");
    }
      $this->view->product = $product;

      $relaProducts = $productSrv->queryProductsRelation($productId);
      $this->view->relaProducts = $relaProducts;

      $loginUser = Core_Util_Helper::getLoginAdmin();
      $isAddedFeature = $this->isAddFeatured($productId, $loginUser->getUserId());
      $this->view->isAddedFeature = $isAddedFeature;
  }

  public function addAction(){
    parent::visitByButton(parent::getScreenName(),"新規登録");
    parent::setScreenName($this->screenNameAdd);

      $productForm = new Admin_Form_Product();
      $this->view->form = $productForm;
      $this->view->mode = "ADD";

      $arrClassDetail = $this->getAllClassProductDetail();
      $this->view->arrClassDetail = $arrClassDetail;
  }

  public function editAction(){

    if (Core_Util_Helper::isReferenceAdmin()) {
      $this->_forward("view");
      return;
    }
      $productId = $this->_request->getParam("id", null);

      $params = null;
      if (!empty($productId)) {
        $params['product_id'] = $productId;
      }
      parent::visitByButton(parent::getScreenName(),"編集", $params);
      parent::setScreenName($this->screenNameEdit);

      if(Core_Util_Helper::isEmpty($productId)){
        $this->_redirect("/admin/product/index");
        return;
      }

      $productSrv = new Core_Service_MstProductService();
      $product = $productSrv->getByProductId($productId);
      if(count($product["prices"])==0){
          $subItem = array();
            $subItem["priceApplyStartDate"] = date("Y/m/d",time());
            $subItem["price"] = "";
            $subItem["priceIncludingTax"] = "";
            $subItem["tax"] = "";
            $product["prices"][] = $subItem;
      }
      if(count($product["points"])==0){
            $subItem = array();
            $subItem["pointApplyStartDate"] = date("Y/m/d",time());
            $subItem["magnificationPoint"] = "";
            $product["points"][] = $subItem;
      }
      if(count($product["categories"])==0){
            $subItem = array();
            $subItem["categoryParent"] = "";
            $subItem["categoryParentName"] = "";
            $subItem["categoryChild"] =  "";
            $subItem["categoryChildName"] = "";
            $subItem["categoryChildComboData"] = array();
            $product["categories"][] = $subItem;
      }

      $productForm = new Admin_Form_Product();
      $productForm->populate($product);

      $this->view->product = $product;
      $this->view->form = $productForm;
      $this->view->mode = "EDIT";

      $loginUser = Core_Util_Helper::getLoginAdmin();
      $isSaleUser = $loginUser->isSaleUser();
      $this->view->isSaleUser = $isSaleUser;
      $isAddedFeature = $this->isAddFeatured($productId, $loginUser->getUserId());
      $this->view->isAddedFeature = $isAddedFeature;
  }

    public function deleteAction(){
      $params = null;
      if (isset($_POST['id']) && !empty($_POST['id'])) {
        $params['id'] =  $_POST['id'];
      }
      parent::visitByButton(parent::getScreenName(),"削除", $params);

        $productId = "";
      if(isset($_POST["id"])){
            $productId = $_REQUEST["id"];
      }else{
          $this->_redirect("/admin/product/index");
          return;
      }

      $productSrv = new Core_Service_MstProductService();
      $res = $productSrv->deleteByProductId($productId);

        echo json_encode(array("success"=>$res));
      $this->_helper->viewRenderer->setNoRender();
    $this->_helper->layout->disableLayout();
  }

  public function saveAction(){
    $productForm = new Admin_Form_Product();

      $post = $this->_request->getPost();

      // Log operation
      $params = null;
      if (isset($post['productId']) && !empty($post['productId'])) {
        $params['productId'] =  $post['productId'];
      }
      if (isset($post['productNo']) && !empty($post['productNo'])) {
        $params['productNo'] =  $post['productNo'];
      }
      if (isset($post['makerProductNo']) && !empty($post['makerProductNo'])) {
        $params['makerProductNo'] =  $post['makerProductNo'];
      }
      if (isset($post['productName']) && !empty($post['productName'])) {
        $params['productName'] =  $post['productName'];
      }

      if (isset($post["mode"]) && $post["mode"] === "EDIT") {
        parent::visitByButton(parent::getScreenName(),"更新", $params);
      } else {
        parent::visitByButton(parent::getScreenName(),"追加", $params);
      }

      $error = $this->checkInput($post);

        if($error == ""){
          // ADD 20140429 Hieunm start
      if(isset($post['magnificationPoint']) && !empty($post['magnificationPoint'][0])){
            //ポイント倍率
        $magnificationPoint = $post['magnificationPoint'];
        foreach ($magnificationPoint as $item => $value){
          $magnificationPoint[$item] = str_replace(",", "", $value);
        }
        $post['magnificationPoint'] = $magnificationPoint;
      } else {
        $post['magnificationPoint'] = '1';
      }
      // ADD 20140429 Hieunm end
            $productSrv = new Core_Service_MstProductService();
            $idLogin = Core_Util_Helper::getIdAdminLogin();
            //Insert data into db
            if(isset($post["mode"]) && $post["mode"] === "ADD") {
        // ADD 20140429 Hieunm start

        if (isset($post['stockValue'])) {
                $post['stockValue'] = str_replace(",", "", $post['stockValue']);
        }

        if (isset($post['shippingValue'])) {
                $post['shippingValue'] = str_replace(",", "", $post['shippingValue']);
        }
        // ADD 20140429 Hieunm end
                $productId = $productSrv->insertProduct($post);
                if ($productId !== false) {
                  if ($post["addFeature"] == "1") {
                    $sev = new Core_Service_MstProductService();
                    $success = $sev->addFeatureProduct($productId, $idLogin);
                  }
                }
                echo "INSERT";
            } else if(isset($post["mode"]) && $post["mode"] === "EDIT") {
              // ADD 20140429 Hieunm start

              if (isset($post['stockValue'])) {
                $post['stockValue'] = str_replace(",", "", $post['stockValue']);
              }
              if (isset($post['shippingValue'])) {
                $post['shippingValue'] = str_replace(",", "", $post['shippingValue']);
              }
        // ADD 20140429 Hieunm end
                $productSrv->updateProduct($post);
                $productId = $post["productId"];
                $sev = new Core_Service_MstProductService();
                if ($post["addFeature"] == "1") {
                  // MOD 20140424 Hieunm start
                  //$success = $sev->addFeatureProduct($productId, $idLogin);
                  $isAddedFeature = $this->isAddFeatured($productId, $idLogin);
                  if (!$isAddedFeature) {
                    $success = $sev->addFeatureProduct($productId, $idLogin);
                  }
                  // MOD 20140424 Hieunm end
                } else {
                  $success = $sev->removeFeatureProduct($productId, $idLogin);
                }
                echo "UPDATE";
            }
        } else {
            echo $error;
        }

        $this->_helper->viewRenderer->setNoRender();
      $this->_helper->layout->disableLayout();
  }

  public function ckfinderAction(){
      $this->_helper->layout->disableLayout();
  }

  public function uploadAction() {
    parent::visitByButton(parent::getScreenName(),"画像を設定");
    $path = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_TEMP;
    $clsServ = new Core_Service_MstClassService();
    $pathImage = $clsServ->getItemTypeNote1(
        Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE,
        Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_TEMP_ITEM_CD);

    if (Core_Util_Helper::isNotEmpty($pathImage)) {
      $path = $pathImage;
    }



        //$path = Zend_Registry::get('url_base') . $path;
        //$uploadDir = Zend_Registry::get('img_dir')."/upload";
        $uploadDir = Zend_Registry::get('img_dir').$path;
        if (!file_exists($uploadDir)) {
          mkdir ( $path, 0777 );
        }
        // if the form is submitted
        if ($this->_request->isPost()) {
            $name = $_FILES['file']['name'];
      $size = $_FILES['file']['size'];
      $fileName = $_REQUEST['fileName'];
      if(strlen($name)) {
        list($text, $extension) = explode(".", $name);
        if(in_array($extension, array("jpg","png","gif"))) {
                    $actual_image_name = uniqid().'_' . $fileName;
            $tmp = $_FILES['file']['tmp_name'];
            $src = $uploadDir.'/'.$actual_image_name;
          if(move_uploaded_file($tmp, $src)) {
                        echo $path.$actual_image_name;
          }
        }
      }
        }

        $this->_helper->viewRenderer->setNoRender();
    $this->_helper->layout->disableLayout();
  }

  public function categoryAction(){
    parent::visitByButton(parent::getScreenName(),"");
        $id = $_REQUEST["categoryParent"];

      $srv = new Core_Service_MstCategoryService();
        $category = $srv->getByParentId($id);
        $listCategory = array();
        foreach ($category as $item) {
            $listCategory[$item['category_id']] = $item['category_name'];
        }
        echo json_encode($listCategory);

      $this->_helper->viewRenderer->setNoRender();
    $this->_helper->layout->disableLayout();
  }

  public function test1Action(){
      echo "OK";
      $this->_helper->viewRenderer->setNoRender();
    $this->_helper->layout->disableLayout();
  }

  public function changesortAction(){
      echo "ok";
//	    $formData = $this->_getAllParams();
//		$val=null;
//		if (isset($formData['val'])){
//			$val = $formData['val'];
//		}
//		$displayNamespace = new Zend_Session_Namespace('Display');
//		if ($val==null)
//		{
//			$displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
//		}else {
//			$displayNamespace->sort = $val;
//		}

      $this->_helper->viewRenderer->setNoRender();
    $this->_helper->layout->disableLayout();
  }

    private function changesort($val){
    $displayNamespace = new Zend_Session_Namespace('Display');
    if (!isset($val))
    {
      $displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
    }else {
      $displayNamespace->sort = $val;
    }
  }

  public function insertcategoryAction(){
      $formData = $this->_getAllParams();
      $productSrv = new Core_Service_MstProductService();
        $productSrv->insertCategory($formData);

      $this->_helper->viewRenderer->setNoRender();
    $this->_helper->layout->disableLayout();
  }

  public function gettaxAction() {
    $this->disableLayout();
    $this->noRender();
    $tax = $this->getTax();
    echo $tax;
  }

  private function insertCategory($data){
        $productSrv = new Core_Service_MstProductService();
        $productSrv->insertCategory($data);
  }

  private function getTax(){
    $mstClassServ = new Core_Service_MstClassService();
    $tax = $mstClassServ->getTax();
    return $tax;
  }

  private function getAllClassProductDetail() {
    $mstClassServ = new Core_Service_MstClassService();
    $arrCls = $mstClassServ->getMstClassByItemType(Core_Util_Const::ITEM_TYPE_PRODUCT_DETAIL);
    return $arrCls;
  }

  private function checkInput($post) {
    $error = "";

    //商品名称
    $msg = Core_Util_Helper::checkString('商品名称',$post["productName"],0,100,true,'商品名称');
    if($msg != ""){
      $error.= $msg . "<br />";
    }
    //商品番号
    $msg = Core_Util_Helper::checkString('商品番号',$post["productNo"],0,20,true,'商品番号');
    if($msg != ""){
      $error.= $msg . "<br />";
    }
    //メーカー品番
    $msg = Core_Util_Helper::checkString('メーカー品番',$post["makerProductNo"],0,50,false,'メーカー品番');
    if($msg != ""){
      $error.= $msg . "<br />";
    }
    //商品説明
    $msg = Core_Util_Helper::checkString('商品説明',$post["productBrief"],0,250,true,'商品説明');
    if($msg != ""){
      $error.= $msg . "<br />";
    }
    //写真
    if(!isset($post['fileName']) || $post['fileName'] == "") {
      $msg = "Please select image of product";
      $error .= $msg . "<br/>";
    }

    //適用開始日
    $priceApplyStartDate = $post['priceApplyStartDate'];
    $arrTemp = array();
    foreach ($priceApplyStartDate as $item){
      $msg = Core_Util_Helper::checkDate('適用開始日',$item,true,'適用開始日');
      if($msg != ""){
        $error.= $msg . "<br />";
        break;
      }
      if(in_array($item,$arrTemp)) {
        $msg = Core_Util_Messages::getMessage(Core_Util_Messages::W017,array("適用開始日"));
        $error.= $msg . "<br />";
        break;
      }
      $arrTemp[] = $item;

    }

    if (isset($post['shipping']) && $post['shipping'] == 2
    && isset($post['shippingValue']) && Core_Util_Helper::isEmpty($post['shippingValue'])) {
      $error.= "コメント欄の送料を入力してください。" . "<br />";
    }

    if (isset($post['stock']) && $post['stock'] == 2
      && isset($post['stockValue']) && Core_Util_Helper::isEmpty($post['stockValue'])) {
      $error.= "コメント欄の在庫数を入力してください。" . "<br />";
    }
    
    if (isset($post['supplierCode']) && $post['supplierCode'] == "") {
      $error.= "仕入れ先を入力してください。" . "<br />";
    }
    /*
     //見積り価格(税抜き)
    $price = $post['price'];
    foreach ($price as $item){
    $msg = Core_Util_Helper::checkNumberic('見積り価格(税抜き)',$item,1,10,true,'見積り価格(税抜き)');
    if($msg != ""){
    $error.= $msg . "<br />";
    break;
    }
    }
    //見積り価格(税込)
    $priceIncludingTax = $post['priceIncludingTax'];
    foreach ($priceIncludingTax as $item){
    $msg = Core_Util_Helper::checkNumberic('見積り価格(税込)',$item,1,10,true,'見積り価格(税込)');
    if($msg != ""){
    $error.= $msg . "<br />";
    break;
    }
    }
    //見積り価格(消費税)
    $tax = $post['tax'];
    foreach ($tax as $item){
    $msg = Core_Util_Helper::checkNumberic('見積り価格(消費税)',$item,1,10,true,'見積り価格(消費税)');
    if($msg != ""){
    $error.= $msg . "<br />";
    break;
    }
    }
    */
    //適用開始日
    $pointApplyStartDate = $post['pointApplyStartDate'];
    $arrTemp = array();
    foreach ($pointApplyStartDate as $item){
      $msg = Core_Util_Helper::checkDate('適用開始日',$item,false,'適用開始日');		// MOD 20140429 Hieunm modify true->false
      if($msg != ""){
        $error.= $msg . "<br />";
        break;
      }
      if(in_array($item,$arrTemp)) {
        $msg = Core_Util_Messages::getMessage(Core_Util_Messages::W017,array("適用開始日"));
        $error.= $msg . "<br />";
        break;
      }
      $arrTemp[] = $item;
    }
    //ポイント倍率
    $magnificationPoint = $post['magnificationPoint'];
    foreach ($magnificationPoint as $item){
      //$msg = Core_Util_Helper::checkNumberic('ポイント倍率',$item,1,10,true,'ポイント倍率');	// DEL 20140429 Hieunm
      $msg = Core_Util_Helper::checkNumberic('ポイント倍率', str_replace(",", "", $item), 1, 10, false, 'ポイント倍率'); 	// ADD 20140429 Hieunm
      if($msg != ""){
        $error.= $msg . "<br />";
        break;
      }
    }
    //送料
    if(isset($post['shippingValue'])){
      //$msg = Core_Util_Helper::checkNumberic('送料',$post['shippingValue'],1,10,false,'送料');							// DEL 20140429 Hieunm
      $msg = Core_Util_Helper::checkNumberic('送料', str_replace(",", "", $post['shippingValue']), 1, 10, false, '送料'); // ADD 20140429 Hieunm
      if($msg!=""){
        $error .= $msg . "<br/>";
      }
    }
    //在庫
    if(isset($post['stockValue'])){
      //$msg = Core_Util_Helper::checkNumberic('在庫',$post['stockValue'],1,10,false,'在庫');							// DEL 20140429 Hieunm
      $msg = Core_Util_Helper::checkNumberic('在庫', str_replace(",", "", $post['stockValue']), 1, 10, false, '在庫');	// ADD 20140429 Hieunm
      if($msg!=""){
        $error .= $msg . "<br/>";
      }
    }

    //商品カテゴリー
    if(isset($post['categoryChild'])){
      $categoryChild = $post['categoryChild'];
      $arrTemp = array();
      foreach ($categoryChild as $item){
        // DEL 20140429 Hieunm start
        /*if($item === ""){
          $msg = Core_Util_Messages::getMessage(Core_Util_Messages::W001,array("商品カテゴリー"));
          $error.= $msg . "<br />";
          break;
        }*/
        // DEL 20140429 Hieunm end
        if(in_array($item,$arrTemp)) {
          $msg = Core_Util_Messages::getMessage(Core_Util_Messages::W017,array("商品カテゴリー"));
          $error.= $msg . "<br />";
          break;
        }
        $arrTemp[] = $item;
      }
    }
    // DEL 20140429 Hieunm start
    /*else {
      $msg = Core_Util_Messages::getMessage(Core_Util_Messages::W017,array("商品カテゴリー"));
      $error.= $msg . "<br />";
    }*/
    // DEL 20140429 Hieunm end

    //詳細説明
    /*if(trim($post["productDetailInfo"]) == ""){
      $msg = Core_Util_Messages::getMessage(Core_Util_Messages::W001,array("詳細説明"));
      $error.= $msg . "<br />";
    }*/

    return $error;
  }

  public function addfeadtureAction() {
    $this->noRender();
    $this->disableLayout();
    $sev = new Core_Service_MstProductService();
    try {
      $productId = $this->_request->getParam("id", null);

      // Log operation
      $params = null;
      $params['product_id'] = $productId;
      parent::visitByButton(parent::getScreenName(), "おすすめへ", $params);

      $idLogin = Core_Util_Helper::getIdAdminLogin();

      if (Core_Util_Helper::isEmpty($productId)) {
        throw new Exception("Id prouduct is Empty");
      }

      $success = $sev->addFeatureProduct($productId, $idLogin);
      if ($success) {
        echo json_encode(array("success" => true));
      } else {
        throw new Exception("insert fail");
      }
    } catch (Exception $e) {
      echo json_encode(array("success" => false, "error" => $e->getMessage()));
    }
  }

  public function removefeadtureAction() {
    $this->noRender();
    $this->disableLayout();
    try {
      $productId = $this->_request->getParam("id", null);

      // Log operation
      $params = null;
      $params['product_id'] = $productId;
      parent::visitByButton(parent::getScreenName(), "☆おすすめ", $params);

      $idLogin = Core_Util_Helper::getIdAdminLogin();

      if (Core_Util_Helper::isEmpty($productId)) {
        throw new Exception("Id prouduct is Empty");
      }

      $sev = new Core_Service_MstProductService();
      $success = $sev->removeFeatureProduct($productId, $idLogin);
      if ($success) {
        echo json_encode(array("success" => true));
      } else {
        throw new Exception("remove fail");
      }
    } catch (Exception $e) {
      echo json_encode(array("success" => false, "error" => $e->getMessage()));
    }
  }

  public function isAddFeatured($productId, $idUser) {
    $sev = new Core_Service_MstProductService();
    $res = $sev->isAddedFeatured($productId, $idUser);
    return $res;
  }

  //Edit 20140627 Hungnh START
  //public function queryProductForExport($arrProductIds) {
  public function queryProductForExport($keyword = null, $conditionkeys = null, $tylelist = null) {
//     $arrIds = array();
//     foreach ($arrProductIds as $key => $productId) {
//       $arrIds[] = $productId['product_id'];
//     }

    $sev = new Core_Service_MstProductService();

    //$res = $sev->queryProductForExportCSV($arrIds);
    $res = $sev->queryProductForExportCSV($keyword, $conditionkeys, $tylelist);
    return $res;
  }
  //Edit 20140627 Hungnh END

  public function exportCSV($arrData) {
    $filename = "商品情報.csv";
    $csvData = $this->createCSVData($arrData);
    $this->exportCSVToClient($csvData, $filename);
  }

  public function createCSVData($arrData) {
    $csvAgent = new Core_Models_CsvAgent();
    $arrHeaderOfProductCSV = $this->createProductHeader();
    $strHeader = $csvAgent->createLineString($arrHeaderOfProductCSV, true);
    $strBody = $this->createCSVBody($csvAgent, $arrData);

    return $strHeader . $strBody;
  }

  public function createProductHeader() {
    $header = array();
    $header[] = "商品名";
    $header[] = "商品番号";
    $header[] = "商品説明";
    $header[] = "販売価格(税抜)";
    $header[] = "販売適用開始日";
    $header[] = "ポイント倍率";
    $header[] = "ポイント適用開始日";
    $header[] = "送料表示フラグ";
    $header[] = "送料区分(込/別)";
    $header[] = "送料";
    $header[] = "在庫表示フラグ";
    $header[] = "在庫区分(有/無)";
    $header[] = "在庫数";
    $header[] = "仕入れ先"; //No13
    $header[] = "メーカー品番"; //No14
    $header[] = "親カテゴリー名称";
    $header[] = "子カテゴリー名称";
    return $header;
  }

  /**
   *
   * @param Core_Models_CsvAgent $csvAgent
   * @param array $arrData
   */
  public function createCSVBody($csvAgent, $arrData) {
    $body = "";
    foreach ($arrData as $key => $row) {
      $arrLine = array();
      //Edit 20140627 Hungnh START
      //$product_name_with_double = str_replace( '"', '""', $row['product_name']);
      //$arrLine[] = $row['product_name'];
      $arrLine[] = str_replace( '"', '""', $row['product_name']);
      //$arrLine[] = $row['product_no'];
      $arrLine[] = str_replace( '"', '""', $row['product_no']);
      $product_brief_with_double = str_replace( '"', '""', $row['product_brief']);
      $arrLine[] = str_replace(Core_Util_Const::NEW_LINE_ANOTATION,
      		Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION,
      		$product_brief_with_double);
      $arrLine[] = $row['price'];
      $arrLine[] = str_replace("-", "/", $row['apply_start_date']) ;
      $arrLine[] = $row['magnification_point'];
      $arrLine[] = str_replace("-", "/", $row['point_apply_start_date']);
      //Edit 20140627 Hungnh END
      $arrLine[] = $row['shipping_display_flag'] == "1" ? "ON" : "OFF";
      $arrLine[] = $row['shipping_class_cd']. ":" . $row['shipping_class_name'];
      $arrLine[] = $row['shipping_fee'];
      $arrLine[] = $row['stock_display_flag'] == "1" ? "ON" : "OFF";
      $arrLine[] = $row['stock_class_cd'] . ":" . $row['stock_class_name'];
      $arrLine[] = $row['stock_qty'];
      $arrLine[] = $row['item_name'];
      $arrLine[] = str_replace( '"', '""', $row['maker_product_no']);
      $arrLine[] = $row['parent_category_name'];
      $arrLine[] = $row['category_name'];
      $strLine = $csvAgent->createLineString($arrLine, true);
      $body .= $strLine;
    }
    return $body;
  }

  public function exportAction() {
    $this->setAjaxAction();
    $adminSession =  Core_Util_Helper::getAdminSession();
    if (isset($adminSession->productIds)) {
      //Edit 20140627 Hungnh START
//       $productIds = $adminSession->productIds;
//       $arrData = $this->queryProductForExport($productIds);
      $keyword = $adminSession->lastKeyword;
      $conditionkeys = $adminSession->lastConditionKeys;
      $arrData = $this->queryProductForExport($keyword, $conditionkeys, null);
      $this->exportCSV($arrData);
      //Edit 20140627 Hungnh END
    }
  }
  
  public function file_get_contents_utf8($content) { 
      return mb_convert_encoding($content, 'UTF-8', 
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)); 
} 

  public function importcsvAction(){
    if (Core_Util_Helper::isReferenceAdmin()) {
      $this->_forward("shownoright","error");
      return;
    }

    parent::visitByButton(parent::getScreenName(),"ＣＳＶ取込み");
    $this->disableLayout();
    $this->noRender();
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);

    if ( isset($_FILES["file"])) {
      //if there was an error uploading the file
      if ($_FILES["file"]["error"] > 0 && strtolower($extension) != "csv") {
        $error = "選択したファイルの形式が不正です。";
        echo $error;
      } else {
        $str = file_get_contents($_FILES["file"]["tmp_name"]);
        //add 20140724 locpht start;
        if (!mb_detect_encoding($str, 'UTF-8', true)){
        	if (mb_detect_encoding($str, 'sjis-win', true)){
        		$str = mb_convert_encoding($str, 'utf-8', 'sjis-win');
        	} else{
		        		$error = "文字コードが判定できません。";
		        		echo $error;
		        		return;
        	}
        	
        }
		//add 20140724 locpht end
        $arrRows = explode("\n", $str);
        unset($arrRows[0]);
        //$success = $this->saveCSVRows($arrRows);
        //if ($success === true) {
        
        $arrLineError = $this->saveCSVRows($arrRows);
        if (count($arrLineError) == 0) {
          echo "true";
        } else {
          //$error = $success;
          $error = implode(",", $arrLineError) . "行にエラーが発生しています。内容を確認してください。";
          echo $error;
        }
      }
    } else {
      echo "file not selected";
    }
  }

  private function saveCSVRows($arrRows) {
    //Core_Util_LocalLog::writeLog("call to   saveCSVRows");
    $data = array();
    // filter empty line
    foreach ($arrRows as $key => $row) {
      $row = trim($row);
      if ($row !== "") {
        $data[] = $row;
      }
    }
    //Core_Util_LocalLog::writeLog("call to   saveCSVRows 2");
    $validRow = $this->validRow($data);
    //if (is_bool($validRow)) {
    if (count($validRow) == 0) {
      return $this->saveRowToMstProduct($data);
    } else {
      //Core_Util_LocalLog::writeLog("call to   aaaaa 2");
      return $validRow;
    }
  }


  private function validRow($arrRows) {
    $error = "";
    $arrLineError = array();
    foreach ($arrRows as $key => $strRow) {
      $lineNumber = $key + 1;
      $validARow = $this->validARow($strRow, ($key + 0));
      //if (is_string($validARow) && Core_Util_Helper::isNotEmpty($validARow)) {
      //	$error .= $validARow . "\n";
      //}
      if (count($validARow) > 0){
        $arrLineError[] = $lineNumber;
      }
    }
    //if (Core_Util_Helper::isEmpty($error)) {
    //if (count($validARow)){
    //	return true;
    //} else {
      //return $error;
    //	return $validARow;
    //}
    return $arrLineError;

  }

  private function validARow($strRow, $rowIndex) {
    $csvAgent = new Core_Models_CsvAgent();
    $arrField = $csvAgent->convertCsvRowToArray($strRow);

    if (count($arrField) != 15) {
      //throw new Exception("Csv field not match to mstProdcut Obj (" . count($arrField) .") ");
      $arrError[] = $rowIndex;
    } else {
      $proServ = new Core_Service_MstProductService();
      $arrError = array();
      $error = "";
      $cnt = count($arrField);
      $shippingClass = "";
      $stockClass = "";

      for ($i = 0; $i < $cnt; $i++) {
        switch ($i) {
          case 0: // product_name
          	$arrField[$i] = str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE, 
          						Core_Util_Const::DOUBLE_QUOTE, 
          						$arrField[$i]);
            $res = Core_Util_Helper::checkString("商品名", $arrField[$i], null, 100, true, "商品名");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              Core_Util_LocalLog::writeLog($error);
              $arrError[] = $rowIndex;
            }
            break;

          case 1: // product_no
            //exist in DB
          	$arrField[$i] = str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE, 
          						Core_Util_Const::DOUBLE_QUOTE, 
          						$arrField[$i]);
            $idExist = $proServ->checkProductNoExist($arrField[$i]);
            if ($idExist) {
              $error .= "Line " . $rowIndex . ": 商品番号「".$arrField[$i]."」が登録されている。<br />";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            } else {
              $res = Core_Util_Helper::checkString("商品番号", $arrField[$i], null, 20, true, "商品番号");
              if (Core_Util_Helper::isNotEmpty($res)) {
                $error .= "Line " . $rowIndex . ":" . $res . "\n";
                $arrError[] = $rowIndex;
                Core_Util_LocalLog::writeLog($error);
              }
            }
            break;
          case 2: // product_brief
            // restore original characters
          	$arrField[$i] = str_replace(Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION,
          			Core_Util_Const::NEW_LINE_ANOTATION,
          			$arrField[$i]);
          	$arrField[$i] = str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
          			Core_Util_Const::DOUBLE_QUOTE,
          			$arrField[$i]);
          	// check string length 250
            $res = Core_Util_Helper::checkString("商品説明", $arrField[$i], null, 250, true, "商品説明");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 3: // price
            // cnumber
            $res = Core_Util_Helper::checkNumberic("並販売価格(税抜)", $arrField[$i], 0, 9999999, true, "販売価格(税抜)");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 4: // apply_start_date
          	$arrField[$i] = str_replace(Core_Util_Const::DATE_SEPARATOR_IO_CSV, Core_Util_Const::DATE_SEPARATOR_PROCESS_IN, $arrField[$i]);
            // check date
            $isValidDate = Core_Util_Helper::validateDate($arrField[$i]);
            if (!$isValidDate) {
              $innerError = "販売適用開始日は有効な日付ではありません。";
              $error .= "Line " . $rowIndex . ":" . $innerError . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 5: // magnification_point
            $res = Core_Util_Helper::checkNumberic("ポイント倍率", $arrField[$i], 0, 9999999, true, "ポイント倍率");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 6: // point_apply_start_date
          	$arrField[$i] = str_replace(Core_Util_Const::DATE_SEPARATOR_IO_CSV, Core_Util_Const::DATE_SEPARATOR_PROCESS_IN, $arrField[$i]);
            $isValidDate = Core_Util_Helper::validateDate($arrField[$i]);
            if (!$isValidDate) {
              $innerError = "ポイント適用開始日は有効な日付ではありません。";
              $error .= "Line " . $rowIndex . ":" . $innerError . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 7:
            // shipping_display_flag
            $displayFlg = $arrField[$i];
            $displayFlg = trim($displayFlg);
            if ($displayFlg != Core_Util_Const::IO_CSV_FLAG_ON && $displayFlg != Core_Util_Const::IO_CSV_FLAG_OFF && $displayFlg != "") {
              $error .= "Line " . $rowIndex . ":" . "送料表示フラグは1または0でなければなりません。" . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 8: // shipping_class
            // check string length 50
            //$data = $arrField[$i];

            /*$chk = Core_Util_Helper::getArrCdNameFromString($data);
            $res = "";
            if ($chk === false) {
              $res = "送料区分(込/別) is not cd:name";
            } else {
              $shippingClass = $chk[0];
            }
            */
            $res = Core_Util_Helper::checkString("送料区分(込/別)", $arrField[$i], null, 20, true, "送料区分(込/別)");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 9: // note4
            // shipping_fee
            $isRequireShippingFee = false;
            if ($shippingClass == "2") {
              $isRequireShippingFee = true;
            }
            $res = Core_Util_Helper::checkNumberic("送料", $arrField[$i], 0, 9999999, $isRequireShippingFee, "送料");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 10:
            // stock_display_flag
            $displayFlg = $arrField[$i];
            $displayFlg = trim($displayFlg);
            if ($displayFlg != Core_Util_Const::IO_CSV_FLAG_ON && $displayFlg != Core_Util_Const::IO_CSV_FLAG_OFF && $displayFlg != "") {
              $error .= "Line " . $rowIndex . ":" . "在庫表示フラグは1または0でなければなりません。" . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;
          case 11: // stock_class
            //$data = $arrField[$i];

            /*$chk = Core_Util_Helper::getArrCdNameFromString($data);
            $res = "";
            if ($chk === false) {
              $res = "在庫区分(有/無) is not cd:name";
            } else {
              $stockClass = $chk[0];
            }*/

            $res = Core_Util_Helper::checkString("在庫区分(有/無)", $arrField[$i], null, 20, true, "在庫区分(有/無)");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;

          case 12: // stock_qty
            $isRequireStockQuantity = false;
            if ($stockClass == "2") {
              $isRequireStockQuantity = true;
            }
            $res = Core_Util_Helper::checkNumberic("在庫数", $arrField[$i], 0, 9999999, $isRequireStockQuantity, "在庫数");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;
            
          case 13: // supplier_code
            $res = Core_Util_Helper::checkString("仕入れ先", $arrField[$i], null, 60, true, "仕入れ先");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;        
            
          case 14: // maker_product_no
          	$arrField[$i] = str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE, 
          						Core_Util_Const::DOUBLE_QUOTE, 
          						$arrField[$i]);
            $res = Core_Util_Helper::checkString("メーカー品番", $arrField[$i], null, 50, false, "メーカー品番");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              Core_Util_LocalLog::writeLog($error);
              $arrError[] = $rowIndex;
            }
            break;          
            
      
          case 15: // parent_category_name
          	// restore original characters
          	$arrField[$i] = str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
          			Core_Util_Const::DOUBLE_QUOTE,
          			$arrField[$i]);
            // check string length 150
            $res = Core_Util_Helper::checkString("親カテゴリー名称", $arrField[$i], null, 150, true, "親カテゴリー名称");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;
          case 16: // category_name
          	// restore original characters
          	$arrField[$i] = str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
          			Core_Util_Const::DOUBLE_QUOTE,
          			$arrField[$i]);
            // check string length 150
            $res = Core_Util_Helper::checkString("子カテゴリー名称", $arrField[$i], null, 150, true, "子カテゴリー名称");
            if (Core_Util_Helper::isNotEmpty($res)) {
              $error .= "Line " . $rowIndex . ":" . $res . "\n";
              $arrError[] = $rowIndex;
              Core_Util_LocalLog::writeLog($error);
            }
            break;
        }
      }
      //return $error;
      return $arrError;
    }

  }

  private function saveRowToMstProduct($arrOfRows) {

    $ser = new Core_Service_MstProductService();
    $res = $ser->saveArrProduct($arrOfRows);
    return $res;

  }

  private function isExistProductNo($productNo) {
    $ser = new Core_Service_MstProductService();
    $isExist = $ser->checkProductNoExist($productNo);
    return $isExist;
  }


}
