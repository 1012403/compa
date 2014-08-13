<?php
class Admin_QuoteController extends Core_Controller_AdminAbstract {
	private $screenName = "見積り依頼管理　《管理サイト》";
	public function init() {
		parent::init();

		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery.form.js');
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/admin/quote.js');
		//Datetpicker
	    $this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/jquery-ui.css');
	    $this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery-ui.js');
	}

	public function indexAction() {
		parent::setScreenName($this->screenName);
        $post = $this->_getAllParams();

		//Get search condition
        $userName = "";
        $status = "";
        $saleId = "";
		$quoteSession = new Zend_Session_Namespace(
						Core_Util_Const::SESSION_MANAGE_QUOTATION);

		//Clear session
		if(!isset($post["page"])){
            $quoteSession->userName = "";
            $quoteSession->status = "";
            $quoteSession->saleId = "";
		}

		if($this->getRequest()->isPost()){
		    $userName = isset($post["quotationUsername"])?$post["quotationUsername"]:"";
    		$status = isset($post["quotationStatus"])?$post["quotationStatus"]:"";
    		$saleId = isset($post["quotationSale"])?$post["quotationSale"]:"";

    		//Save search condition into session
	        $quoteSession->userName = $userName;
            $quoteSession->status = $status;
            $quoteSession->saleId = $saleId;
		} else {
		    $userName = isset($quoteSession->userName)?$quoteSession->userName:"";
		    $status = isset($quoteSession->status)?$quoteSession->status:"";
		    $saleId = isset($quoteSession->saleId)?$quoteSession->saleId:"";
		}
		
		// Log operation
		$params = null;
		if (isset($post['operateElementName']) && !empty($post['operateElementName'])) {
			$operateElementName = $post['operateElementName'];
		}
		if (isset($post['quotationUsername']) && !empty($post['quotationUsername'])) {
			$params['username'] = $post['quotationUsername'];
		}
		if (isset($post['quotationStatus']) && !empty($post['quotationStatus'])) {
			$params['status'] = $post['quotationStatus'];
		}
		if (isset($post['quotationSale']) && !empty($post['quotationSale'])) {
			$params['saleId'] = $post['quotationSale'];
		}
		
		if (!empty($operateElementName)) {
			parent::visitByButton(parent::getScreenName(), $operateElementName, $params);
		} else {
			$params2 = array (
					'username' => 'quotationUsername',
					'status' => 'quotationStatus',
					'saleId' => 'quotationSale',
			);
			parent::visitByOperation(parent::getScreenName(), null, null, $params2);
		}

		//Init form value
		$quoteForm = new Admin_Form_Quote();
		$data = array();
		$data["quotationUsername"] = $userName;
		$data["quotationStatus"] = $status;
		$data["quotationSale"] = $saleId;
		$quoteForm->populate($data);
	    $this->view->form = $quoteForm;

		$page = $this->_request->getParam('page', 1);

		$paginatorData ['itemCountPerPage'] = Core_Util_Const::ITEMS_PER_PAGE;
		$paginatorData ['pageRange'] = Core_Util_Const::PAGE_RANGE;
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

		//Get data
	    $serv = new Core_Service_QuotationInfoService();
		$allQuotations= $serv->getListQuotation(true,$userName,$status,$saleId,$paginatorData);

		$quotations = $serv->getListQuotation(false,$userName,$status,$saleId,$paginatorData);
	    $this->view->quotations = $quotations;

	    //Pagging
		$totalItem = count($allQuotations);
		$objPaginator = new Core_Util_Paginator();
		$paginator = $objPaginator->createPaginator( $totalItem, $paginatorData );
		$this->view->paginator = $paginator;
		$paginator->setView($this->view);
	}

	public function detailAction(){
	    //Get parameter
        $post = $this->_getAllParams();

        $username = isset($post["username"])?$post["username"]:"";
        $userid = isset($post["userid"])?$post["userid"]:"";
        $id = isset($post["id"])?$post["id"]:"";
        
        $params = null;
        if (!empty($id)) {
        	$params['id'] = $id;
        }
        parent::visitByButton(parent::getScreenName(),"詳細", $params);
        parent::setScreenName('見積り詳細　《管理サイト》');
        
        if($id==""){
            $this->_redirect("/admin/quote");
	        return;
        }

        //Get quote detail info
        $serv = new Core_Service_QuotationDetailInfoService();
		$quotationDetail= $serv->getListQuotationDetail($id,$userid);

		//Get quote info
		$serv = new Core_Service_QuotationInfoService();
		$quotationsInfo = $serv->getByQuoteId($id);
		$remark = $quotationsInfo->getRemark();
		
		$quotationStatus = $quotationsInfo->getStatus();
		$isDisplaySaveTempButton = Core_Util_Const::STATUS_QUOTATION_DETAULT == $quotationStatus 
		|| Core_Util_Const::STATUS_QUOTATION_TEMP_SAVE == $quotationStatus;

		$this->view->user_id = $userid;
		$this->view->quote_id = $id;
        $this->view->username = $username;
        $this->view->quotations = $quotationDetail;
        $this->view->remark = $remark;
        $this->view->isDisplaySaveTempButton = $isDisplaySaveTempButton;
	}

	public function saveAction(){
	    $post = $this->_request->getPost();
	    $error = "";

	    $params = null;
	    $elementName = null;
	    if (isset($post['mode']) && !empty($post['mode'])) {
	    	$params['mode'] = $post['mode'];
	    	if ($post['mode'] == 'SAVE') {
	    		$elementName = '見積り金額を確定';
	    	} else {
	    		$elementName = '一時保存';
	    	}
	    }
	    parent::visitByButton(parent::getScreenName(),$elementName, $params);
	    
	    #Validate
	    //見積り価格(税抜き)
	    $post['price'] = str_replace(",", "", $post['price']);
	    $price = $post['price'];
        foreach ($price as $item){
            $msg = Core_Util_Helper::checkNumberic('見積り価格(税抜)',$item,1,10,true,'見積り価格(税抜)');
            if($msg != ""){
                $error.= $msg . "<br />";
                break;
            }
        }

        //見積り価格の有効期限
        $validUnitilDate = $post["valid_until_date"];
	    $msg = Core_Util_Helper::checkDate('適用開始日',$validUnitilDate,true,'適用開始日');
	    
        if($msg != ""){
            $error.= $msg . "<br />";
        }else{
            $date1 = strtotime($validUnitilDate);
            $date2 = strtotime(date("Y/m/d",time()));
            
            if($date1 < $date2){
                $msg = Core_Util_Messages::getMessage(Core_Util_Messages::W017,array("見積り価格の有効期限"));
                if($msg != ""){
                    $error.= $msg . "<br />";
                }
            }
        }

	    #Save data
        if($error == ""){
          $srv = new Core_Service_QuotationDetailInfoService();
          $res = $srv->saveQuoteDetail($post,$post["mode"]);
          if($res){
              echo "OK";
          } else {
              $error = "Save not success";
          }
        }

	    echo $error;

	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	}
}
