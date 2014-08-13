<?php
class Admin_UserController extends Core_Controller_AdminAbstract {
	private $screenName = "ユーザ情報管理";
	public function init() {
		parent::init ();
		$this->view->headLink ()->prependStylesheet ( Zend_Registry::get ( 'url_base' ) . '/css/admin/style.css' );
		$this->view->headLink ()->prependStylesheet ( Zend_Registry::get ( 'url_base' ) . '/css/style.css' );
		$this->view->headLink ()->prependStylesheet ( Zend_Registry::get ( 'url_base' ) . '/css/user/adminlayout.css' );
		
		// $this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/zip/prototype.js');
		$this->view->headScript ()->appendFile ( Zend_Registry::get ( 'url_base' ) . '/js/zip/ajaxzip2.js' );
		
		$this->view->headScript ()->appendFile ( Zend_Registry::get ( 'url_base' ) . '/js/admin/product.js' );
		$this->view->headScript ()->appendFile ( Zend_Registry::get ( 'url_base' ) . '/js/admin/user.js' );
		$this->view->headScript ()->appendFile ( Zend_Registry::get ( 'url_base' ) . '/js/jquery.form.3.5.js' );
		
		// Datetpicker
		parent::createMenuOther ();
	}
	public function indexAction() {
		parent::visitByButton ( $this->screenName, "" );
		$user_serv = new Core_Service_UserService ();
		$txt_username = null;
		$sel_area = null;
		$txt_telno = null;
		$sel_sales = null;
		$afterDelete = null;
		$formData = $this->_getAllParams ();
		
		if (isset ( $formData ['txt_username'] )) {
			$txt_username = $formData ['txt_username'];
		}
		if (isset ( $formData ['sel_area'] )) {
			$sel_area = $formData ['sel_area'];
		}
		if (isset ( $formData ['txt_telno'] )) {
			$txt_telno = $formData ['txt_telno'];
		}
		if (isset ( $formData ['sel_sales'] )) {
			$sel_sales = $formData ['sel_sales'];
		}
		if (isset($formData["AfterDelete"])) {
			$afterDelete = $formData["AfterDelete"];
		}
		$this->view->txt_username = $txt_username;
		$this->view->sel_area = $sel_area;
		$this->view->txt_telno = $txt_telno;
		$this->view->sel_sales = $sel_sales;
		
		$page = $this->_request->getParam ( 'page', 1 );
		$pageClassSession = new Zend_Session_Namespace ( Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS );
		
		// $paginatorData ['itemCountPerPage'] = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		// $paginatorData ['pageRange'] = $pageClassSession->pageRangeClass[Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		/* @var $mstClassPage Core_Models_MstClass */
		$mstClassPage = $pageClassSession->pageClass [Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_PRODUCT];
		$paginatorData ['itemCountPerPage'] = $mstClassPage->getNote1 ();
		$mstClassPage = $pageClassSession->pageRangeClass [Core_Util_Const::PAGE_RANGE_CLASS_DESKTOP];
		$paginatorData ['pageRange'] = $mstClassPage->getNote2 ();
		
		$paginatorData ['currentPage'] = $page;
		Zend_Paginator::setDefaultScrollingStyle ( 'Sliding' );
		Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'pagination.phtml' );
		if (! isset ( $formData ['export'] )) {
			if(!$afterDelete){
				$this->view->users=$user_serv->queryAllUser($paginatorData, $txt_username, $sel_area, $txt_telno, $sel_sales);
	
			    $totalItem = $user_serv->queryCountAllUser($txt_username, $sel_area, $txt_telno, $sel_sales);
			    $objPaginator = new Core_Util_Paginator ();
			    $paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
			    $this->view->paginator = $paginator;
			    $paginator->setView($this->view);
			
			    $this->view->managerusers=$user_serv->queryAllUserManager();
			    $class_serv = new Core_Service_MstClassService();
			    $this->view->area_code = $class_serv->getMstClassByItemType(Core_Util_Const::AREA_CODE_CLASS);
	
	    		$adminSession =  Core_Util_Helper::getAdminSession();
	    		$adminSession->userName = $txt_username;
	    		$adminSession->selArea = $sel_area;
	    		$adminSession->txt_telno = $txt_telno;
	    		$adminSession->sel_sales = $sel_sales;
	    	} else{
	    		$adminSession =  Core_Util_Helper::getAdminSession();    		 
	    		$this->view->users=$user_serv->queryAllUser($paginatorData, $adminSession->userName, $adminSession->selArea,
	    		 											$adminSession->txt_telno, $adminSession->sel_sales);
	
			    $totalItem = $user_serv->queryCountAllUser($adminSession->userName, $adminSession->selArea,
	    		 											$adminSession->txt_telno, $adminSession->sel_sales);
			    $objPaginator = new Core_Util_Paginator ();
			    $paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
			    $this->view->paginator = $paginator;
			    $paginator->setView($this->view);
			
			    $this->view->managerusers=$user_serv->queryAllUserManager();
			    $class_serv = new Core_Service_MstClassService();
			    $this->view->area_code = $class_serv->getMstClassByItemType(Core_Util_Const::AREA_CODE_CLASS);
			    
			    $this->view->txt_username = $adminSession->userName;
			    $this->view->sel_area = $adminSession->selArea;
			    $this->view->txt_telno = $adminSession->txt_telno;
			    $this->view->sel_sales =  $adminSession->sel_sales;
	    	}
		} else {
			// clear export flag to prevent the next request from export file (when user click page link) 
			unset($formData ['export']);
			$arrUser = $user_serv->queryAllUserForExport ( $txt_username, $sel_area, $txt_telno, $sel_sales );
			$csvData = $this->toCSVString ( $arrUser );
			$filename = trim ( "ユーザ情報管理" ) . ".csv";
			$result = $this->exportCSVToClient ( $csvData, $filename );
		}
	}
	function checkPostRequest() {
		if (! $this->getRequest ()->isPost ()) {
			$this->_redirect ( "/admin/user" );
			return;
		}
	}
	function checkModeAdd($mode) {
		return (strcmp ( 'add', $mode ) == 0);
	}
	function checkParamId($formData) {
		if (! isset ( $formData ['id'] ) || '' === trim ( $formData ['id'] )) {
			$this->_redirect ( "/admin/user" );
			return;
		}
	}
	public function viewAction($user_id = null) {
		parent::visitByButton ( $this->screenName, "詳細" );
		$this->insertCssForView ();
		
		if ($user_id == null) {
			$formData = $this->_getAllParams ();
			$this->checkParamId ( $formData );
			
			$user_id = $formData ['id'];
		}
		$user_serv = new Core_Service_UserService ();
		$this->view->user = $user_serv->queryUserDetail ( $user_id );
		$class_serv = new Core_Service_MstClassService ();
		$this->view->area_code = $class_serv->getMstClassByItemType ( Core_Util_Const::AREA_CODE_CLASS );
		
		$this->getListUserShippingForView ( $user_id );
	}
	public function editAction() {
		if (Core_Util_Helper::isReferenceAdmin ()) {
			$this->_forward ( "view" );
			return;
		}
		
		parent::visitByButton ( $this->screenName, "" );
		$this->insertCssForView ();
		
		$mode = $this->_request->getParam ( 'mode', '' );
		
		$formData = $this->_getAllParams ();
		if (! $this->checkModeAdd ( $mode )) {
			$this->checkParamId ( $formData );
			$user_id = $formData ['id'];
		}
		
		$form = new Admin_Form_UserForm ();
		
		$user_serv = new Core_Service_UserService ();
		$class_serv = new Core_Service_MstClassService ();
		$data = array ();
		$data ['mode'] = $mode;
		$data ['area_code'] = $class_serv->getMstClassByItemType ( Core_Util_Const::AREA_CODE_CLASS );
		$data ['sales_id'] = $user_serv->queryAllUserManager ();
		$data ['user_class'] = $class_serv->getMstClassUserType ();
		$data ['admin_class'] = $class_serv->getMstClassAdminType ();
		
		$form->initDefaultData ( $data );
		$form->setModeValue ( $mode );
		
		if ($this->_request->isPost ()) {
			parent::visitByButton ( $this->screenName, "更新" );
			$formDataPost = $this->_request->getPost ();
			if (! isset ( $formDataPost ['login_password'] )) {
				$formDataPost ['login_password'] = "";
			}
			
			// $shippingParams = $this->getShippingParam($formDataPost);
			// $formDataPost = $this->removeShippingParam($formDataPost);
			
			if (! $this->checkModeAdd ( $mode )) {
				$login_password = $formDataPost ['login_password'];
				// 20140507
				$login_user = $formDataPost ['login_username'];
				$formDataPost ['login_password'] = '.';
				
				if ($form->isValid ( $formDataPost )) {
					
					$this->view->id = $user_id;
					// start nguyenpth 20140507
					$userDb = new Core_Db_MstUserDb ();
					$username = $userDb->getUserById ( $user_id );
					// end nguyenpth 20140507
					$userdata = array_merge ( array (), $formDataPost );
					
					/*
					 * $userdatashipping = array( 'post_no' => $userdata['post_no'], 'address1' => $userdata['address'], 'tel_no' => $userdata['tel_no'] );
					 */
					unset ( $userdata ['mode'] );
					unset ( $userdata ['id'] );
					unset ( $userdata ['submit'] );
					// unset($userdata['tel_no']);
					unset ( $userdata ['login_password'] );
					
					if (strlen ( $login_password ) > 0) {
						$userdata ['login_password'] = Core_Util_Helper::encryptPassword ( $login_password );
						// ADD 20140424 Hieunm start
						$userdata ['token_cookie'] = null;
						$userdata ['token_cookie_admin'] = null;
						// ADD 20140424 Hieunm end
					}
					// add nguyenpth 20140507
					
					if (strcmp ( trim ( $login_user ), trim ( $username->getLoginUsername () ) ) != 0) {
						$userdata ['token_cookie'] = null;
						$userdata ['token_cookie_admin'] = null;
					}
					// end nguyenpth 20140507
					
					if ($user_serv->updateUserFormData ( $this->view->id, $userdata )) {
						$this->view->success = 'successEdit';
					} else {
						$this->error = "SQL Exec error";
						$form->populate ( $formDataPost );
					}
				} else {
					$form->populate ( $formDataPost );
				}
				$form->setLoginPasswordValue ( '' );
			} else {
				if ($form->isValid ( $formDataPost )) {
					// Core_Util_LocalLog::writeLog("aaaaaaaaaa");
					$userdata = array_merge ( array (), $formDataPost );
					$userdata ['login_password'] = Core_Util_Helper::encryptPassword ( $userdata ['login_password'] );
					
					// $user_model = new Core_Models_MstUser($userdata);
					// $user_model->setDeleteFlg(0);
					
					// $user_shipping_model = new Core_Models_MstUserShipping($userdata);
					// $user_shipping_model->setAddress1($user_model->getAddress());
					
					$user_id = $user_serv->insertModelUser ( $userdata );
					$this->view->id = $user_id;
					
					if ($user_id !== null) {
						$this->view->success = 'successAdd';
					} else {
						$this->error = "SQL Exec error";
						$form->populate ( $formDataPost );
					}
				} else {
					$form->populate ( $formDataPost );
				}
			}
			$this->getListUserShippingForView ( $user_id );
			$this->disableLayout ();
		} else {
			if (strcmp ( 'add', $mode ) != 0) {
				parent::visitByButton ( $this->screenName, "編集" );
				$user_serv = new Core_Service_UserService ();
				$user = $user_serv->queryUserDetail ( $user_id );
				$this->view->id = $user ['user']->getUserId ();
				if ($user != null) {
					$user ['user']->setLoginPassword ( '' );
				}
				$form->setValue ( $user );
				$this->getListUserShippingForView ( $user_id );
			} else {
				// add
				parent::visitByButton ( $this->screenName, "新規登録" );
				$form->user_class->setValue ( Core_Util_Const::USER_TYPE_DEFAULT );
				$form->admin_class->setValue ( Core_Util_Const::ADMIN_ITEM_TYPE );
			}
		}
		$this->view->form = $form;
	}
	public function deleteAction() {
		parent::visitByButton ( $this->screenName, "削除" );
		$this->checkPostRequest ();
		$formData = $this->_getAllParams ();
		$this->checkParamId ( $formData );
		$id = $this->_request->getParam ( 'id' );
		if ($id == null)
			echo 'false';
		
		$user_serv = new Core_Service_UserService ();
		$result = $user_serv->deleteUserById ( $id );
		if ($result) {
			echo 'true';
		} else {
			echo 'false';
		}
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ( TRUE );
	}
	
	/**
	 * toCSVString
	 * 
	 * @param unknown $arrUser        	
	 * @return unknown
	 */
	private function toCSVString($arrUser) {
		$data = $this->createCSVHeader ();
		$data .= $this->createCSVBody ( $arrUser );
		return $data;
	}
	private function createCSVHeader() {
		$arrHeader = Core_Models_MstUser::getHeaderCsv ();
		$csvAgent = new Core_Models_CsvAgent ();
		$header = $csvAgent->createLineString ( $arrHeader );
		return $header;
	}
	
	/**
	 * createCSVBody
	 * 
	 * @param string $arrCategory        	
	 * @return Ambigous <string, NULL, unknown>
	 */
	private function createCSVBody($arrUser) {
		$csvAgent = new Core_Models_CsvAgent ();
		$csvBody = "";
		$serUser = new Core_Service_UserService ();
		/* @var $item Core_Models_MstUser */
		$lastArrUserShip = null;
		$arrUserShip = null;
		foreach ( $arrUser as $key => $item ) {
			$arrUserShip = new Core_Models_MstUser ( $item );
			$arrUserShip->setUserTypeClass ( $item ['user_type_name'] );
			$arrUserShip->setAdminTypeClass ( $item ['admin_type_name'] );
			$arrUserShip->setAreaClass ( $item ['area_code_class'] );
			$arrUserShip->setTranstypeClass ( $item ['trans_type'] );
			
			$address = str_replace( '"', '""', $item ['address']);
      		$arrUserShip->setAddress(preg_replace(Core_Util_Const::NEW_LINE_ANOTATION_FULL,
      		Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION_FULL,
      		$address));
      		$address2 = str_replace( '"', '""', $item ['address2']);
      		$arrUserShip->setAddress2(preg_replace(Core_Util_Const::NEW_LINE_ANOTATION_FULL,
      		Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION_FULL,
      		$address2));
			
			if (Core_Util_Helper::isNotEmpty($item['sales_id'])){
				$userSale = $serUser->getUserById($item['sales_id']);
				if ($userSale != null) {
					$arrUserShip->setUserSales($userSale->getLoginUsername());
				}
			} else{
				$arrUserShip->setUserSales(null);
			}
			
			$date = new DateTime ( $item ['update_point_date'] );
			$arrUserShip->setUpdatePointDate ( $date->format ( 'Y/m/d' ) );
			$arrUserShip->setShippingSeq ( $item ['shipping_seq'] );
			$shippingSeq = $arrUserShip->getShippingSeq ();
			
			if ($shippingSeq === '1') {
				$arrUserShip->setShippingDesName ( null );
				$arrUserShip->setShippingPostNo ( null );
				$arrUserShip->setShippingAddress1 ( null );
				$arrUserShip->setShippingAddress2 ( null );
				$arrUserShip->setShippingTelNo ( null );
				$arrUserShip->setShippingFaxNo ( null );
				$arrUserShip->setShippingTransType ( null );
				$arrUserShip->setTranstypeClass ( null );
			} else {
				$arrUserShip->setShippingDesName ( $item ['shipping_des_name'] );
				$arrUserShip->setShippingPostNo ( $item ['shipping_post_no'] );
				$arrUserShip->setShippingAddress1 ( $item ['shipping_address1'] );
				$arrUserShip->setShippingAddress2 ( $item ['shipping_address2'] );
				$arrUserShip->setShippingTelNo ( $item ['shipping_tel_no'] );
				$arrUserShip->setShippingFaxNo ( $item ['shipping_fax_no'] );
				$arrUserShip->setShippingTransType ( $item ['shipping_trans_type'] );
				$arrUserShip->setTranstypeClass ( $item ['trans_type'] );
			}
			if ($lastArrUserShip !== null) { 
				if ($lastArrUserShip->getUserId() === $arrUserShip->getUserId()) {
					$csvArr = $arrUserShip->toCsvData ();
					$stringRow = $csvAgent->createLineString ( $csvArr );
					$csvBody .= $stringRow;
				}
				else {
					if ($lastArrUserShip->getShippingSeq() === '1') {
						$csvArr = $lastArrUserShip->toCsvData ();
					 	$stringRow = $csvAgent->createLineString ( $csvArr );
					 	$csvBody .= $stringRow;
					}
				}
			}
			$lastArrUserShip = $arrUserShip;
		}
		if ($lastArrUserShip->getShippingSeq() === '1') {
			$csvArr = $lastArrUserShip->toCsvData ();
			$stringRow = $csvAgent->createLineString ( $csvArr );
			$csvBody .= $stringRow;
		}
		return $csvBody;
	}
	public function importcsvAction() {
		parent::visitByButton ( $this->screenName, "ＣＳＶ取込み" );
		$this->disableLayout ();
		$this->noRender ();
		$temp = explode ( ".", $_FILES ["fileUser"] ["name"] );
		$extension = end ( $temp );
		if (isset ( $_FILES ["fileUser"] )) {
			// if there was an error uploading the file
			if (strtolower ( $extension ) != "csv") { // $_FILES["fileUser"]["error"] > 0 &&
				$error = "選択したファイルの形式が不正です。";
				echo $error;
			} else {
				$str = file_get_contents ( $_FILES ["fileUser"] ["tmp_name"] );
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
				$arrRows = explode ( "\n", $str );
				unset ( $arrRows [0] );
				$success = $this->saveCSVRows ( $arrRows );
				if ($success === true) {
					echo "true";
				} else {
					$error = $success . " 行にエラーが発生しています。内容を確認してください。";
					echo $error;
				}
			}
		} else {
			echo "選択したファイルの形式が不正です。";
		}
	}
	
	private $curLoginUserName = null;
	private $lastLoginUserName = null;
	
	private function saveCSVRows($arrRows) {
		if (is_array ( $arrRows )) {
			$data = array ();
			// filter empty line
			foreach ( $arrRows as $key => $row ) {
				$row = trim ( $row );
				if ($row !== "") {
					$data [] = $row;
				}
			}
			$validRow = $this->validRow ( $data );
			if ($validRow === true) {
				return $this->saveRowToMstUser ( $data );
			} else {
				return $validRow;
			}
		}
		return "arrRows is not array";
	}
	private function saveRowToMstUser($arrRows) {
		$serv = new Core_Service_UserService ();
		if (is_array ( $arrRows )) {
			$arrMstUser = array ();
			foreach ( $arrRows as $key => $row ) {
				$mstUser = Core_Models_MstUser::createMstUserFromCsvRow ( $row );
				$arrMstUser [] = $mstUser;
			}
			$res = $serv->insertArrUser ( $arrMstUser ); 
			if (Core_Util_Helper::isEmpty ( $res )) {
				return true;
			} else {
				return $res;
			}
		}
		return "arrRows is not array";
	}
	private function validRow($arrRows) {
		$error = "";
		if (is_array ( $arrRows )) {
			foreach ( $arrRows as $key => $strRow ) {
				$errUser = $key + 1;
				// Core_Util_LocalLog::writeLog($errUser);
				$validARow = $this->validARow ( $strRow, $key );
				if (is_string ( $validARow ) && Core_Util_Helper::isNotEmpty ( $validARow )) {
					if ($error !== "") {
						$error .= ", ";
					}
					$error .= $errUser;
				}
			}
			if (Core_Util_Helper::isEmpty ( $error )) {
				return true;
			} else {
				return $error;
			}
		} else {
			return "データの内容が配列ではありません。";
		}
	}
	
	/**
	 * validARow
	 * 
	 * @param string $strRow        	
	 * @param int $rowIndex        	
	 * @throws Exception
	 * @return string
	 */
	private function validARow($strRow, $rowIndex) {
		$rowIndex = $rowIndex + 1;
		
		$csvAgent = new Core_Models_CsvAgent ();
		$arrField = $csvAgent->convertCsvRowToArray ( $strRow );
		
		$mustCheckShipping = false;
		if ((isset($arrField[15]) && !empty($arrField[15]))
		|| (isset($arrField[16]) && !empty($arrField[16]))
		|| (isset($arrField[17]) && !empty($arrField[17]))) {
			$mustCheckShipping = true;
		}
		$innerError = "";
		$serUser = new Core_Service_UserService ();
		if (count ( $arrField ) != Core_Models_MstUser::$TOTAL_FIELD) {
			// throw new Exception("Csv field not match to MstUser Obj");
			$innerError .= $rowIndex;
		} else {
			$catServ = new Core_Service_UserService ();
			$cnt = count ( $arrField );
			
			for($i = 0; $i < $cnt; $i ++) {
				switch ($i) {
					case 0 : // user name
					        // check string length 100, not null
						$res = Core_Util_Helper::checkString ( "user name", $arrField [$i], null, 100, true, "user name " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 1 : // login username
// 						if ($this->lastLoginUserName == null || $this->curLoginUserName != $this->lastLoginUserName) {
// 							$curLoginUserName = $arrField [$i];
// 						}
					        // check string length 50, not null
						$res = Core_Util_Helper::checkString ( "login username ", $arrField [$i], null, 50, true, "login username " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 2 : // login password
					        // check string length 50, null
						$res = Core_Util_Helper::checkString ( "login password", $arrField [$i], null, 50, true, "login password " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 3 : // email
					        // check string length 50, not null
						$res = Core_Util_Helper::checkString ( "email ", $arrField [$i], null, 50, true, "email " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 4 : // area_code
					        // check string
						$areaCode = null;
						$areaCodeParts = explode ( ":", $arrField [$i] );
						if (! empty ( $areaCodeParts )) {
							$areaCode = $areaCodeParts [0];
						}
						$res = Core_Util_Helper::checkString ( "area_code ", $areaCode, null, 20, true, "area_code" );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 5 : // post no
					        // check string length 20, null
						$res = Core_Util_Helper::checkString ( "post no ", $arrField [$i], null, 20, true, "post no" );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 6 : // address 1

						$arrField[$i] = str_replace(Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION,
		          		'\r\n', $arrField[$i]);
						
		          		$arrField[$i] = str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
		          		Core_Util_Const::DOUBLE_QUOTE, $arrField[$i]);
		          		
					        // check string length 200, null
						$res = Core_Util_Helper::checkString ( "address 1 ", $arrField [$i], null, 200, true, "address 1 " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 7 : // address 2
						$arrField[$i] = str_replace(Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION,
		          		'\r\n', $arrField[$i]);
		          		$arrField[$i] = str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
		          		Core_Util_Const::DOUBLE_QUOTE, $arrField[$i]);

					        // check string length 200, null
						$res = Core_Util_Helper::checkString ( "address 2 ", $arrField [$i], null, 200, false, "address 2 " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 8 : // tell no
					        // check string length 50, null
						$res = Core_Util_Helper::checkString ( "tell no ", $arrField [$i], null, 50, true, "tell no " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 9 : // fax no
					        // check string length 50, null
						$res = Core_Util_Helper::checkString ( "fax no ", $arrField [$i], null, 50, false, "fax no " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 10 : // sales name
				         // check string length 100, null
						$res = Core_Util_Helper::checkString ( "sales name ", $arrField [$i], null, 100, TRUE, "sales name " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						} else {
							$userSales = $serUser->getUserIdByUserNAme ( $arrField [$i] );
							if ($userSales === null && $userSales === "") {
								Core_Util_LocalLog::writeLog ( 'error user sales' );
								$innerError .= $i;
							}
						}
						break;
					case 11 : // UserClass
				        // check string length 1, null
						$userClass = null;
						$userClassParts = explode ( ":", $arrField [$i] );
						if (! empty ( $userClassParts )) {
							$userClass = $userClassParts [0];
						}
						$res = Core_Util_Helper::checkString ( "UserClass", $userClass, NULL, 1, false, "UserClass" );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 12 : // AdminClass
				        // check string length 1, null
						$adminClass = null;
						$adminClassParts = explode ( ":", $arrField [$i] );
						if (! empty ( $adminClassParts )) {
							$adminClass = $adminClassParts [0];
						}
						$res = Core_Util_Helper::checkString ( "AdminClass", $adminClass, NULL, 1, false, "AdminClass" );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 13 : // UserPoint
					         // check int
						$res = Core_Util_Helper::checkNumberic ( "UserPoint ", $arrField [$i], null, 9999999999, false, "UserPoint " );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 14 : // UpdatePointDate
					    //check string length 10
// 					    $resString = Core_Util_Helper::checkString ( "UpdatePointDate ", $arrField [$i], 8, 8, true, "UpdatePointDate " );
// 						$resNUmber = Core_Util_Helper::checkNumberic ( "UpdatePointDate ", $arrField [$i], NULL, 99999999, true, "UpdatePointDate " );
// 						if (Core_Util_Helper::isNotEmpty ( $resString )) {
// 							Core_Util_LocalLog::writeLog ( $i . '=' . $resString );
// 							$innerError .= $i;
// 						}						// check string number
// 						elseif (Core_Util_Helper::isNotEmpty ( $resNUmber )) {
// 							Core_Util_LocalLog::writeLog ( $i . '=' . $resNUmber );
// 							$innerError .= $i;
// 						} 						// check date
// 						else {
// 							$date = new DateTime ( $arrField [$i] );
// 							$arrField [$i] = $date->format ( 'Y/m/d ' );
// 							$res = Core_Util_Helper::checkDate ( "UpdatePointDate ", $arrField [$i], true, "UpdatePointDate " );
// 							if (Core_Util_Helper::isNotEmpty ( $res )) {
// 								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
// 								$innerError .= $i;
// 							}
// 						}

						$updatePointDate = str_replace ( Core_Util_Const::DATE_SEPARATOR_IO_CSV, Core_Util_Const::DATE_SEPARATOR_PROCESS_IN, $arrField [$i] );
						$res = Core_Util_Helper::validateDate ( $updatePointDate );
						if (!$res) {
							Core_Util_LocalLog::writeLog($i . "=" . $res . "\n");
							$innerError = $i;
						}
						break;
					
					case 15 : // user_nameshipping_des_name
// 					    // check if this is first row for new user
// 						if ($this->lastLoginUserName == null || $this->curLoginUserName != $this->lastLoginUserName) {
// 							$lastLoginUserName = $curLoginUserName;
// 						} else { // only check from the 2nd row for new user
// 							$res = Core_Util_Helper::checkString ( "user_nameshipping_des_name ", $arrField [$i], null, 100, true, "user_nameshipping_des_name" );
// 							if (Core_Util_Helper::isNotEmpty ( $res )) {
// 								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
// 								$innerError .= $i;
// 							}
// 						}
						if ($mustCheckShipping === true) {
							$res = Core_Util_Helper::checkString ( "user_nameshipping_des_name ", $arrField [$i], null, 100, true, "user_nameshipping_des_name" );
							if (Core_Util_Helper::isNotEmpty ( $res )) {
								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
								$innerError .= $i;
							}
						}
						break;
					case 16 : // PostNo
// 						// check if this is first row for new user
// 						if ($this->lastLoginUserName == null || $this->curLoginUserName != $this->lastLoginUserName) {
// 							$lastLoginUserName = $curLoginUserName;
// 						} else { // only check from the 2nd row for new user
// 							$res = Core_Util_Helper::checkString ( "PostNo ", $arrField [$i], null, 20, true, "PostNo" );
// 							if (Core_Util_Helper::isNotEmpty ( $res )) {
// 								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
// 								$innerError .= $i;
// 							}
// 						}
						
						if ($mustCheckShipping === true) {
							$res = Core_Util_Helper::checkString ( "PostNo ", $arrField [$i], null, 20, true, "PostNo" );
							if (Core_Util_Helper::isNotEmpty ( $res )) {
								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
								$innerError .= $i;
							}
						}
						break;
					case 17 : // Address1
// 						// check if this is first row for new user
// 						if ($this->lastLoginUserName == null || $this->curLoginUserName != $this->lastLoginUserName) {
// 							$lastLoginUserName = $curLoginUserName;
// 						} else { // only check from the 2nd row for new user
// 							$res = Core_Util_Helper::checkString ( "Address1 ", $arrField [$i], null, 200, true, "Address1" );
// 							if (Core_Util_Helper::isNotEmpty ( $res )) {
// 								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
// 								$innerError .= $i;
// 							}
// 						}
						
						if ($mustCheckShipping === true) {
							$res = Core_Util_Helper::checkString ( "Address1 ", $arrField [$i], null, 200, true, "Address1" );
							if (Core_Util_Helper::isNotEmpty ( $res )) {
								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
								$innerError .= $i;
							}
						}
						break;
					case 18 : // Address2
						$res = Core_Util_Helper::checkString ( "Address2 ", $arrField [$i], null, 200, false, "Address2" );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 19 : // TelNo
						$res = Core_Util_Helper::checkString ( "TelNo ", $arrField [$i], null, 50, false, "TelNo" );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 20 : // FaxNo
						$res = Core_Util_Helper::checkString ( "FaxNo ", $arrField [$i], null, 50, false, "FaxNo" );
						if (Core_Util_Helper::isNotEmpty ( $res )) {
							Core_Util_LocalLog::writeLog ( $i . '=' . $res );
							$innerError .= $i;
						}
						break;
					case 21 : // trans_type
// 						//check if this is first row for new user
// 						if ($this->lastLoginUserName == null || $this->curLoginUserName != $this->lastLoginUserName) {
// 							$lastLoginUserName = $curLoginUserName;
// 						} else { // only check from the 2nd row for new user
// 							$transType = null;
// 							$transTypeParts = explode ( ":", $arrField [$i] );
// 							if (! empty ( $transTypeParts )) {
// 								$transType = $transTypeParts [0];
// 							}
// 							$res = Core_Util_Helper::checkNumberic ( "trans_type ", $transType, null, 99999999, true, "trans_type " );
// 							if (Core_Util_Helper::isNotEmpty ( $res )) {
// 								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
// 								$innerError .= $i;
// 							}
// 						}
						
						if ($mustCheckShipping === true) {
							$transType = null;
							$transTypeParts = explode ( ":", $arrField [$i] );
							if (! empty ( $transTypeParts )) {
								$transType = $transTypeParts [0];
							}
							$res = Core_Util_Helper::checkNumberic ( "trans_type ", $transType, null, 99999999, true, "trans_type " );
							if (Core_Util_Helper::isNotEmpty ( $res )) {
								Core_Util_LocalLog::writeLog ( $i . '=' . $res );
								$innerError .= $i;
							}
						}
						break;
				}
				$this->lastLoginUserName = $this->curLoginUserName;
			}
		}
		return $innerError;
	}
	public function newusershippingAction() {
		$comboData = $this->getShippingMethodDataForCombo ();
		$form = new Admin_Form_Shipping ();
		$form->shipping_method->setMultiOptions ( $comboData );
		$this->view->form = $form;
		$idShipping = $this->_request->getParam ( "id", null );
		$this->view->id = $idShipping;
		
		$this->disableLayout ();
	}
	public function editusershippingAction() {
		$this->disableLayout ();
		$idShipping = $this->_request->getParam ( "id", null );
		
		$comboData = $this->getShippingMethodDataForCombo ();
		$form = new Admin_Form_Shipping ();
		$form->shipping_method->setMultiOptions ( $comboData );
		$this->view->form = $form;
		$this->view->id = $idShipping;
	}
	
	/**
	 * get list of mstUserShipping
	 * 
	 * @param string $userId        	
	 * @return array of Core_Models_MstUserShipping
	 */
	private function getListUserShipping($userId) {
		$userServ = new Core_Service_UserService ();
		$arrShipping = $userServ->getShippingByUserId ( $userId );
		$arrShipping = Core_Util_Helper::nullToEmptyArray ( $arrShipping );
		return $arrShipping;
	}
	
	/**
	 * get list of Core_Models_MstUserShipping and send to view
	 * 
	 * @param unknown $user_id        	
	 */
	private function getListUserShippingForView($user_id) {
		$arrUserShipping = $this->getListUserShipping ( $user_id );
		if (count ( $arrUserShipping ) >= 1) {
			unset ( $arrUserShipping [0] );
		}
		$this->view->arrUserShipping = $arrUserShipping;
	}
	private function insertCssForView() {
		$this->view->headLink ()->prependStylesheet ( Zend_Registry::get ( 'url_base' ) . '/css/user/detail.css' );
		$this->view->headLink ()->prependStylesheet ( Zend_Registry::get ( 'url_base' ) . '/css/admin/user.css' );
	}
	private function getShipping($userId, $id) {
		$serv = new Core_Service_UserService ();
		return $serv->getShipping ( $userId, $id );
	}
	private function getShippingMethodDataForCombo() {
		$serv = new Core_Service_MstClassService ();
		$arr = $serv->getMstClassByItemTypeDispl ( Core_Util_Const::ITEM_TYPE_TRANS_TYPE );
		$comboData = array ();
		/* @var $item Core_Models_MstClass */
		foreach ( $arr as $key => $item ) {
			$comboData [$item->getItemCd ()] = $item->getItemName ();
		}
		
		return $comboData;
	}
	/**
	 *
	 * @param Admin_Form_Shipping $form        	
	 * @param Core_Models_MstUserShipping $shipping        	
	 */
	private function setDataToForm($form, $shipping) {
		$data = array ();
		$data ['post_no'] = $shipping->getPostNo ();
		$data ['address1'] = $shipping->getAddress1 ();
		$data ['address2'] = $shipping->getAddress2 ();
		$data ['tel_no'] = $shipping->getTelNo ();
		$data ['fax_no'] = $shipping->getFaxNo ();
		$data ['shipping_method'] = $shipping->getTransType ();
		$form->populate ( $data );
		return $form;
	}
	public function checkinputAction() {
		$this->noRender ();
		$this->disableLayout ();
		$mode = $this->_request->getParam ( 'mode', '' );
		$formDataPost = $this->_request->getPost ();
		if (! $this->checkModeAdd ( $mode )) {
			$formDataPost ['login_password'] = '.';
		}
		$class_serv = new Core_Service_MstClassService ();
		$user_serv = new Core_Service_UserService ();
		
		$data = array ();
		$data ['mode'] = $mode;
		$data ['area_code'] = $class_serv->getMstClassByItemType ( Core_Util_Const::AREA_CODE_CLASS );
		$data ['sales_id'] = $user_serv->queryAllUserManager ();
		$data ['user_class'] = $class_serv->getMstClassUserType ();
		$data ['admin_class'] = $class_serv->getMstClassAdminType ();
		
		$form = new Admin_Form_UserForm ();
		$form->initDefaultData ( $data );
		
		if ($form->isValid ( $formDataPost )) {
			echo "true";
		} else {
			$this->echoErrorsMessage ( $form->getMessages () );
		}
	}
	public function getshippingnameAction() {
		$this->setAjaxAction ();
		$id = $this->_request->getParam ( "id", null );
		$shippingName = Core_Util_Helper::getShippingMethodName ( $id );
		echo $shippingName;
	}
	private function echoErrorsMessage($errs) {
		if (count ( $errs ) > 0) {
			echo '<ul>';
			foreach ( $errs as $err ) {
				foreach ( $err as $key => $msg ) {
					echo "<li>$msg</li>";
				}
			}
			echo '</ul>';
		} else if (isset ( $this->error )) {
			echo $this->error;
		}
	}
	public function newrowshippingAction() {
		$this->disableLayout ();
		$data = $this->_getAllParams ();
		$this->view->data = $data;
	}
	private function getShippingParam($postParam) {
		$totalShipping = $postParam ['totalShipping'];
		for($i = 0; $i < $index; $i ++) {
		}
	}
}