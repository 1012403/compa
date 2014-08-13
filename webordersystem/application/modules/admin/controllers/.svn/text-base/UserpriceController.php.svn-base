<?php
class Admin_UserPriceController extends Core_Controller_AdminAbstract{
	private $screenName = "ユーザ商品単価《管理サイト》";
	public function init() {
		parent::init();
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/admin/style.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/style.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/user/adminlayout.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/jquery.timepicker.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/bootstrap-datepicker.css');
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery.timepicker.js');
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/bootstrap-datepicker.js');
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery.form.js');
		parent::createMenuOther();
 
	}
	
	public function indexAction() {
		parent::setScreenName($this->screenName);
		parent::logVisit(parent::getScreenName(), null, null, null);
		$up_serv = new Core_Service_UserProductService();
		$txt_username		= null;
		$txt_login_username	= null;
		$txt_product_name	= null;
		
		$formData = $this->_getAllParams();
		if (isset($formData['txt_username'])){
			$txt_username = $formData['txt_username'];
		}
		if (isset($formData['txt_login_username'])){
			$txt_login_username = $formData['txt_login_username'];
		}
		if (isset($formData['txt_product_name'])){
			$txt_product_name = $formData['txt_product_name'];
		}
		$page = $this->_request->getParam('page', '1');
		
		$operateElementType = null;
		$operateElementName = null;
		if (isset($formData["operateElementType"])) {
			$operateElementType = $formData["operateElementType"];
		}
		if (isset($formData["operateElementName"])) {
			$operateElementName = $formData["operateElementName"];
		}
		
		$params = null;
		if (!empty($txt_username)) {
			$params["username"] = $txt_username;
		}
		if (!empty($txt_login_username)) {
			$params["login_username"] = $txt_login_username;
		}
		if (!empty($txt_product_name)) {
			$params["product_name"] = $txt_product_name;
		}
		if (!empty($page)) {
			$params["page"] = $page;
		}

		if ($operateElementType == Core_Util_Logger::LOG_VISIT_BY_BUTTON) {
			parent::visitByBUtton(parent::getScreenName(), $operateElementName, $params);
		} else {
			parent::logVisit(parent::getScreenName(), null, null, $params);
		}
		
		$this->view->txt_username		= $txt_username;
		$this->view->txt_login_username	= $txt_login_username;
		$this->view->txt_product_name	= $txt_product_name;
		
		$pageClassSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
		
		//$paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		//$paginatorData ['pageRange'] = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1();
		$mstClassPage = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2();
		
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
		
		if (!isset($formData['export'])) {
			// search
			$this->view->userprices = $up_serv->queryAll($paginatorData, $txt_username, $txt_login_username, $txt_product_name);
			$totalItem = $up_serv->queryCountAll($txt_username, $txt_login_username, $txt_product_name);
			$objPaginator = new Core_Util_Paginator ();
			$paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
			$this->view->paginator = $paginator;
			$paginator->setView($this->view);
		} else {
			// export CSV
			$arrData = $up_serv->queryAllExport( $txt_username, $txt_login_username, $txt_product_name);
			$csvData = $this->toCSVString($arrData);
			$filename = trim(" ユーザ商品単価 "). ".csv";
			$result=$this->exportCSVToClient($csvData, $filename);
		}
	}
	
	public function updatepriceAction(){
		$formData = $this->_getAllParams();
		$user_id = $formData['user_id'];
		$product_id = $formData['product_id'];
		$price = $formData['price'];
		$date = $formData['date'];
		
		$params = null;
		if (!empty($user_id)) {
			$params['user_id'] = $user_id;
		}
		if (!empty($product_id)) {
			$params['product_id'] = $product_id;
		}
		if (!empty($price)) {
			$params['price'] = $price;
		}
		if (!empty($date)) {
			$params['date'] = $date;
		}
		parent::visitByButton(parent::getScreenName(),"更新", $params);
		
		$flag = true;
		$errormsg = "<ul>";
		$isnumber = new Zend_Validate_Digits();
		if (!$isnumber->isValid($price)){
			$errormsg .= "<li>「見積り単価(税抜)」を半角数値で入力して下さい。</li>";
			$flag = false;
		}
		$isdate = new Zend_Validate_Date(array('format' => 'yyyy/mm/dd'));
		if (!$isdate->isValid($date)){
			$errormsg .= "<li>「有効期間」は正しい形式で入力してください。(yyyy/mm/dd)</li>";
			$flag = false;
		}
		
		if ($flag){
			$data=array();
			$data['user_id']	= $user_id;
			$data['product_id']	= $product_id;
			//$data['price_including_tax']=$price;
			$data['price']		= $price;
			$data['valid_until_date']	=$date;
			$up_serv = new Core_Service_UserProductService();
			$result = $up_serv->updatePrice($data);
			
			if ($result==false){
				$errormsg .= "<li>SQL Error.</li>";
				$errormsg .= "</ul>";
				echo $errormsg;
			} else {
				echo '';
			}
		} else {
			$errormsg .= "</ul>";
			echo $errormsg;
		}

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}
	
	/**
	 * toCSVString
	 * @param unknown $arrUser
	 * @return unknown
	 */
	private function toCSVString($arrUser) {
		$data = $this->createCSVHeader();
		$data .= $this->createCSVBody($arrUser);
		return $data;
	}
	
	private function createCSVHeader() {
		$arrHeader = Core_Models_UserProductJoin::getHeaderCsv();
		$csvAgent = new Core_Models_CsvAgent();
		$header = $csvAgent->createLineString($arrHeader);
		return $header;
	}
	
	/**
	 * createCSVBody
	 * @param string $arrCategory
	 * @return Ambigous <string, NULL, unknown>
	 */
	private function createCSVBody($arrUser) {
		$csvAgent = new Core_Models_CsvAgent();
		$csvBody = "";
		/* @var $item Core_Models_UserProductJoin */
		foreach ($arrUser as $key => $item) {
			$mstUserPro = new Core_Models_UserProductJoin ($item);
			
			$mstUserPro->setValidUntilDate( str_replace("-", "/", $item['valid_until_date']));			
			$mstUserPro->setUserName($item['user_name']);
			$mstUserPro->setLoginUsername($item['login_username']);
			$mstUserPro->setProductName($item['product_name']);
			$mstUserPro->setProductNo($item['product_no']);
			$csvArr = $mstUserPro->toCsvData();
			$stringRow = $csvAgent->createLineString($csvArr);
			$csvBody .= $stringRow;
		}
		return $csvBody;
	}
	
	public function importcsvAction(){
		parent::visitByButton($this->screenName,"ＣＳＶ取込み");
		$this->disableLayout();
		$this->noRender();
		$temp = explode(".", $_FILES["fileUserProd"]["name"]);
		$extension = end($temp);
		if ( isset($_FILES["fileUserProd"])) {
			//if there was an error uploading the file
			if ( strtolower($extension) != "csv") {//$_FILES["fileUserProd"]["error"] > 0 &&
				$error = "選択したファイルの形式が不正です。";
				echo $error;
			} else {
				$str = file_get_contents($_FILES["fileUserProd"]["tmp_name"]);
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
				$success = $this->saveCSVRows($arrRows);
				if ($success === true) {
					echo "true";
				} else {
					$error = $success ." 行にエラーが発生しています。内容を確認してください。";
					echo $error;
				}
			}
		} else {
			echo "選択したファイルの形式が不正です。";
		}
	}
	
	private function saveCSVRows($arrRows) {
		if (is_array($arrRows)) {
			$data = array();
			// filter empty line
			foreach ($arrRows as $key => $row) {
				$row = trim($row);
				if ($row !== "") {
					$data[] = $row;
				}
			}
			$validRow = $this->validRow($data);
			if ($validRow === true) {
				return $this->saveRowToMstUserProd($data);
			} else {
				return $validRow;
			}
		}
		return "arrRows is not array";
	}
	
	private function saveRowToMstUserProd($arrRows){
		//Core_Util_LocalLog::writeLog("call to saveRowToMstUserProd");
		$serv = new Core_Service_UserProductService();
		if (is_array($arrRows)) {
			$arrMstUserProd = array();
			foreach ($arrRows as $key => $row) {
				$mstUser = Core_Models_UserProductJoin::createUserProductJoinFromCsvRow($row);
				$arrMstUserProd[] = $mstUser;
			}
			//Core_Util_LocalLog::writeLog("tao mst");
			$res = $serv->insertArrUserProd($arrMstUserProd);
			if (Core_Util_Helper::isEmpty($res)) {
				return true;
			} else {
				return $res;
			}
		}
		return "arrRows is not array";
	}
	
	private function validRow($arrRows) {
		//Core_Util_LocalLog::writeLog("call to validRow");
		$error = "";
		if (is_array($arrRows)) {
			foreach ($arrRows as $key => $strRow) {
				$errUser=$key + 1;
				//Core_Util_LocalLog::writeLog($errUser);
				$validARow = $this->validARow($strRow,$key);
				if (is_string($validARow) && Core_Util_Helper::isNotEmpty($validARow)) {
					if ($error !== ""){
						$error .= ", ";
					}
					$error .= $errUser ;
				}
			}
			if (Core_Util_Helper::isEmpty($error)) {
				return true;
			} else {
				return $error;
			}
		} else {
			return "データの内容が配列ではありません。";
		}
	}
	
	private function validARow($strRow, $rowIndex) {
		//Core_Util_LocalLog::writeLog("call to validARow");
		$rowIndex=$rowIndex+1;
		$csvAgent = new Core_Models_CsvAgent();
		$arrField = $csvAgent->convertCsvRowToArray($strRow);
		$innerError = "";
		if (count($arrField) != Core_Models_UserProductJoin::$TOTAL_FIELD) {
			//throw new Exception($rowIndex. "Csv field not match to MstCategory Obj");
			$innerError .= $rowIndex;
			//Core_Util_LocalLog::writeLog($innerError."=". count($arrField));
		} else {
			//Core_Util_LocalLog::writeLog("else");
			$catServ = new Core_Service_UserProductService();
			$cnt = count($arrField);
			for ($i = 0; $i < $cnt; $i++) {
				switch ($i) {
					case 0: // user_name
						// check string length 150
						$res = Core_Util_Helper::checkString("user name", $arrField[$i], null, 100, false, "user name ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							Core_Util_LocalLog::writeLog($i.'='.$res);
							$innerError .=$i;
						}
						break;
					case 1: // login_username
						// check string length 150
						$res = Core_Util_Helper::checkString("login_username ", $arrField[$i], null, 50, true, "login_username");
						if (Core_Util_Helper::isNotEmpty($res)) {
							Core_Util_LocalLog::writeLog($i.'='.$res);
							$innerError .= $i ;
						}
						break;
					case 2: // product_name
						// check string length 150
						$res = Core_Util_Helper::checkString("product_name ", $arrField[$i], null, 100, false, "product_name");
						if (Core_Util_Helper::isNotEmpty($res)) {
							Core_Util_LocalLog::writeLog($i.'='.$res);
							$innerError .= $i ;
						}
						break;
					case 3: // product_no
						// check string length 150
						$res = Core_Util_Helper::checkString("product_no ", $arrField[$i], null, 20, true, "product_no");
						if (Core_Util_Helper::isNotEmpty($res)) {
							Core_Util_LocalLog::writeLog($i.'='.$res);
							$innerError .= $i ;
						}
						break;
					case 4: // valid_until_date
						// check string length 10
						$resString = Core_Util_Helper::checkString("valid_until_date ", $arrField[$i], 10, 10, true, "valid_until_date ");
						//$resNUmber = Core_Util_Helper::checkNumberic("valid_until_date ", $arrField[$i], NULL, 99999999, true, "valid_until_date ");
						if (Core_Util_Helper::isNotEmpty($resString)) {
							Core_Util_LocalLog::writeLog($i.'='.$resString);
							$innerError .=$i ;
						}
						//check number
						/*
						elseif (Core_Util_Helper::isNotEmpty($resNUmber)) {
							Core_Util_LocalLog::writeLog($i.'='.$resNUmber);
							$innerError .=$i ;
						}
						*/
						//check date
						else {
							$date = new DateTime( $arrField[$i]);
							$arrField[$i] = $date->format('Y/m/d ');
							$res = Core_Util_Helper::checkDate("valid_until_date ", $arrField[$i], true, "valid_until_date ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								Core_Util_LocalLog::writeLog($i.'='.$res);
								$innerError .=$i ;
							}
						}
						break;
					case 5: // price
						// check number
						if (Core_Util_Helper::isNotEmpty($arrField[$i])){
							$res = Core_Util_Helper::checkNumberic("price ", $arrField[$i], "", 9999999999999, true, "price ");	
						}
						
						if (Core_Util_Helper::isNotEmpty($res)) {
							Core_Util_LocalLog::writeLog($i.'='.$res);
							$innerError .= $i ;
						}
						break;
				}
			}
		}
		return $innerError;
	
	}
}