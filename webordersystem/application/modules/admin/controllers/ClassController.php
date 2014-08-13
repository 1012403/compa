<?php
class Admin_ClassController extends Core_Controller_AdminAbstract{
	private $userId;
	private $screenName = "商品カテゴリー《管理サイト》";

	/**
	 * init
	 * @see Core_Controller_FrontAbstract::init()
	 */
	public function init() {
		parent::init();

		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery.form.3.5.js');		// Get Login Session Info
		$userLogin = Core_Util_Helper::getLoginAdmin();
		$this->userId = $userLogin->getUserId();
		parent::createMenuOther();
	}

	/**
	 * indexAction
	 */
	public function indexAction() {
		if (Core_Util_Helper::isReferenceAdmin()) {
			$this->_forward("shownoright","error");
			return;
		}
		
		parent::setScreenName($this->screenName);
		parent::visitByOperation(parent::getScreenName(), null, null, null);
		$classServ = new Core_Service_MstClassService();
		//get for combo
		$arrParentClass = $classServ->getMstClassByItemType(Core_Util_Const::PARENT_CLASS);
		$arrCmbCondition = $this->getDataForCmbCondition($arrParentClass[0]->getItemCd());

		//get list class
		$arrClassItem = $classServ->getMstClassByItemType($arrParentClass[0]->getItemCd());

		$this->view->arrClassItem = $arrClassItem;
		$this->view->arrParentClass = $arrParentClass;
		$this->view->arrCmbCondition = $arrCmbCondition;
		$this->view->itemTypeFirst = $arrParentClass[0]->getItemCd();
	}

	/**
	 * main action
	 */
	public function mainAction(){
		if (Core_Util_Helper::isReferenceAdmin()) {
			$this->_forward("shownoright","error");
			return;
		}
		
		$formData = $this->_getAllParams();
		if($formData['actionName'] == 'search'){
			$this->searchMstClass($formData);
		} else if($formData['actionName'] == 'update'){
			$this->updateMstClass($formData);
		} else if($formData['actionName'] == 'delete'){
			$this->deleteMstClass($formData);
		} else if($formData['actionName'] == 'exportcsv'){
			$this->exportCsvMstClass($formData);
		}
	}

	/**
	 * changeparentAction
	 */
	public function changeparentAction() {
		if (Core_Util_Helper::isReferenceAdmin()) {
			$this->_forward("shownoright","error");
			return;
		}
		
		parent::visitByButton(parent::getScreenName(),"編集");
		$formData = $this->_getAllParams();
		$arrData = $this->getDataForCmbCondition($formData['parentCd']);
		$arrData['item_cd'] = "item_cd";
		$arrData['item_name'] = "item_name";

		echo($arrData['item_cd'].'-'.
			 $arrData['item_name'].'-'.
			 $arrData['note1'].'-'.
			 $arrData['note2'].'-'.
			 $arrData['note3'].'-'.
			 $arrData['note4'].'-'.
			 $arrData['note5']);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}

	/**
	 * getDataForCmbCondition
	 * @param unknown $parentCd
	 */
	private function getDataForCmbCondition($parentCd){
		$classServ = new Core_Service_MstClassService();
		$classParent = $classServ->getMstClassByItemTypeAndItemCd(Core_Util_Const::PARENT_CLASS, $parentCd);
		$ret = array();
		$ret['note1'] = $classParent->getNote1();
		$ret['note2'] = $classParent->getNote2();
		$ret['note3'] = $classParent->getNote3();
		$ret['note4'] = $classParent->getNote4();
		$ret['note5'] = $classParent->getNote5();
		return $ret;
	}

	/**
	 * searchMstClass
	 */
	private function searchMstClass($formData, $error = ""){
		$classServ = new Core_Service_MstClassService();

		$condition = array();

		$condition['item_type'] = $formData['cmbParentClass'];
		$condition['condition1'] =  array_key_exists('cmbCondition1', $formData) ?  $formData['cmbCondition1'] : '';
		$condition['valueCondition1'] = $formData['txtCondition1'];
		$condition['condition2'] =  array_key_exists('cmbCondition2', $formData) ?  $formData['cmbCondition2'] : '';
		$condition['valueCondition2'] = $formData['txtCondition2'];

		$params = null;
		if (!empty($condition['item_type'])) {
			$params['item_type'] = $condition['item_type'];
		}
		if (!empty($condition['valueCondition1'])) {
			$params['condition1'] = $condition['condition1'];
			$params['valueCondition1'] = $condition['valueCondition1'];
		}
		if (!empty($condition['valueCondition2'])) {
			$params['condition2'] = $condition['condition2'];
			$params['valueCondition2'] = $condition['valueCondition2'];
		}
		parent::visitByButton(parent::getScreenName(),"検索", $params);
		
		//get for combo
		$arrParentClass = $classServ->getMstClassByItemType(Core_Util_Const::PARENT_CLASS);
		$arrCmbCondition = $this->getDataForCmbCondition($condition['item_type']);

		//get list class
		$arrClassItem = $classServ->getMstClassByCondition($condition);

		// ADD 20140516 Hieunm start
		// Set Error to view
		if($error != "") {
			$this->view->error = $error;
		}
		// ADD 20140516 Hieunm end

		$this->view->arrClassItem = $arrClassItem;
		$this->view->arrParentClass = $arrParentClass;
		$this->view->arrCmbCondition = $arrCmbCondition;
		$this->view->condition = $condition;
		$this->view->parentName = trim($formData["parentName"]);

		$this->render("index");
	}

	private function exportCsvMstClass($formData){
		parent::visitByButton(parent::getScreenName(),"ＣＳＶ出力");
		$classServ = new Core_Service_MstClassService();

		$condition = array();

		$condition['item_type'] = $formData['cmbParentClass'];
		$condition['condition1'] =  array_key_exists('cmbCondition1', $formData) ?  $formData['cmbCondition1'] : '';
		$condition['valueCondition1'] = $formData['txtCondition1'];
		$condition['condition2'] =  array_key_exists('cmbCondition2', $formData) ?  $formData['cmbCondition2'] : '';
		$condition['valueCondition2'] = $formData['txtCondition2'];

		//get for combo
		$arrParentClass = $classServ->getMstClassByItemType(Core_Util_Const::PARENT_CLASS);
		$arrCmbCondition = $this->getDataForCmbCondition($condition['item_type']);

		//get list class
		$arrClassItem = $classServ->getMstClassByCondition($condition);

		$csvData = $this->toCSVString($arrClassItem);

		$filename = "区分情報設定.csv";
		$this->exportCSVToClient($csvData, $filename);
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
	        	$error = "File is not csv";
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
	             $arrLineError = $this->saveCSVRows($arrRows);
	             if ($arrLineError === true) {
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
			return $this->saveRowToMstClass($data);
		} else {
			//Core_Util_LocalLog::writeLog("call to   aaaaa 2");
			return $validRow;
		}

	}

	private function validRow($arrRows) {
		//Core_Util_LocalLog::writeLog("call to   validRow 2");
		$arrErrors = array();
		$error = "";
		foreach ($arrRows as $key => $strRow) {
			$lineError = $key + 1;
			$validARow = $this->validARow($strRow, ($lineError));
			//if (is_string($validARow) && Core_Util_Helper::isNotEmpty($validARow)) {
			if (count($validARow) > 0) {
				//$error .= $validARow . "<br />";
				$arrErrors[] = $lineError;
			}
		}
// 		if (Core_Util_Helper::isEmpty($error)) {
// 			return true;
// 		} else {
// 			//Core_Util_LocalLog::writeLog("validRow return " . $error);
// 			return $error;
// 		}
		return $arrErrors;
	}
 
	private function validARow($strRow, $rowIndex) {
		$arrErrors = array();
		$csvAgent = new Core_Models_CsvAgent();
		$arrField = $csvAgent->convertCsvRowToArray($strRow);
		if (count($arrField) != Core_Models_MstClass::$TOTAL_FIELD) {
			//throw new Exception("Csv field not match to MstClass Obj");
			$arrErrors[] = $rowIndex;
		} else {
			$clsServ = new Core_Service_MstClassService();
			$error = "";
			$cnt = count($arrField);
			for ($i = 0; $i < $cnt; $i++) {
				switch ($i) {
					//case 0: // id
					// check is number and exist in DB
					//$idExist = $clsServ->checkIdExist($arrField[$i]);
					//if ($idExist) {
					//	$error .= "Line " . $rowIndex . ": 項目ID「".$arrField[$i]."」が登録されている。<br />";
					//	Core_Util_LocalLog::writeLog($error);
					//	$arrErrors[] = $rowIndex;
					//}
					//break;

					case 0: // item_type
						// check string length 20
						$res = Core_Util_Helper::checkString("項目種別", $arrField[$i], null, 20, true, "項目種別");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
					break;

					case 1: // item_cd
						// check string length 20
						$res = Core_Util_Helper::checkString("項目コード", $arrField[$i], null, 20, true, "項目コード");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
						
						// check item_type, item_cd exist in DB
						$classServ = new Core_Service_MstClassService();
						$itemType = $arrField[$i - 1];
						$itemCd= $arrField[$i];		
						$checkExist = $classServ->checkMstClassByItemTypeAndItemCd($itemType, $itemCd);	
						if ($checkExist > 0) {
							$error = "Line " . $rowIndex . ": 既存している区分を入力しました。";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
						
					break;

					case 2: // item_name
						// check string length 50
						$res = Core_Util_Helper::checkString("項目名", $arrField[$i], null, 50, true, "項目名");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
					break;

					case 3: // item_order
						// check number
						$res = Core_Util_Helper::checkNumberic("並び順", $arrField[$i], 0, 9999999, false, "並び順");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
					break;

					case 4: // display_flg
						// check string length 1
						$displayFlg = $arrField[$i];
						$displayFlg = trim($displayFlg);
						if ($displayFlg != "0" && $displayFlg != "1" && $displayFlg != "") {
							$error .= "Line " . $rowIndex . ":" . "表示フラクは1または0でなければなりません。" . "<br />";
							$arrErrors[] = $rowIndex;
						}
					break;

					case 5: // note1
						// check string length 50
						$res = Core_Util_Helper::checkString("備考１", $arrField[$i], null, 50, false, "備考１");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
					break;

					case 6: // note2
						// check string length 50
						$res = Core_Util_Helper::checkString("備考２", $arrField[$i], null, 50, false, "備考２");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
					break;

					case 7: // note3
						// check string length 50
						$res = Core_Util_Helper::checkString("備考３", $arrField[$i], null, 50, false, "備考３");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
					break;

					case 8: // note4
						// check string length 50
						$res = Core_Util_Helper::checkString("備考４", $arrField[$i], null, 50, false, "備考４");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
					break;

					case 9: // note5
						// check string length 50
						$res = Core_Util_Helper::checkString("備考５", $arrField[$i], null, 50, false, "備考５");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error .= "Line " . $rowIndex . ":" . $res . "<br />";
							//Core_Util_LocalLog::writeLog($error);
							$arrErrors[] = $rowIndex;
						}
					break;
				}
			}
			//return $error;
			
		}
		return $arrErrors;
	}

	private function saveRowToMstClass($arrRows){
		//Core_Util_LocalLog::writeLog("call to   saveRowToMstClass");
		$serv = new Core_Service_MstClassService();

		if (is_array($arrRows)) {
			$arrMstClass = array();
			foreach ($arrRows as $key => $row) {
				$mstClass = Core_Models_MstClass::createMstClassFromCsvRow($row);
				$arrMstClass[] = $mstClass;
			}
			if (!$serv->insertArrMstClass($arrMstClass)) {
				//Core_Util_LocalLog::writeLog("save " . $arrMstClass[0]->getId() . "fail");
				return false;
			}
			//Core_Util_LocalLog::writeLog("save " . $arrMstClass[0]->getId() . "sucess");
			return true;
		}
		return false;
	}

	private function toCSVString($arrClassItem) {
		$data = $this->createCSVHeader();
		$data .= $this->createCSVBody($arrClassItem);

		return $data;
	}

	private function createCSVHeader() {
		$arrHeader = Core_Models_MstClass::getHeaderCsv();
		$csvAgent = new Core_Models_CsvAgent();
		$header = $csvAgent->createLineString($arrHeader);
		return $header;
	}

	private function createCSVBody($arrClassItem) {
		$csvAgent = new Core_Models_CsvAgent();
		$csvBody = "";
		/* @var $item Core_Models_MstClass */
		foreach ($arrClassItem as $key => $item) {
			$csvArr = $item->toCsvData();
			$stringRow = $csvAgent->createLineString($csvArr);
			$csvBody .= $stringRow;
		}
		return $csvBody;

	}

	/**
	 * updateMstClass
	 * @param Core_Models_MstClass $mstClass
	 */
	private function updateMstClass($formData){
		$classServ = new Core_Service_MstClassService();

		$mstClass = new Core_Models_MstClass();
		$mstClass->setId($formData['hiddenId']);
		$mstClass->setItemType($formData['hiddenItemType']);
		$mstClass->setItemCd($formData['txtItemCd']);
		$mstClass->setItemName($formData['txtName']);
		$mstClass->setItemOrder($formData['txtOrder']);
		$mstClass->setDisplayFlg(isset($formData['txtDispFlg']) ? $formData['txtDispFlg'] : '0');
		$mstClass->setNote1($formData['txtNote1']);
		$mstClass->setNote2($formData['txtNote2']);
		$mstClass->setNote3($formData['txtNote3']);
		$mstClass->setNote4($formData['txtNote4']);
		$mstClass->setNote5($formData['txtNote5']);

		$params = null;
		if (!empty($formData['hiddenId'])) {
			$params['id'] = $formData['hiddenId'];
		}
		if (!empty($formData['hiddenItemType'])) {
			$params['itemType'] = $formData['hiddenItemType'];
		}
		if (!empty($formData['txtItemCd'])) {
			$params['itemCd'] = $formData['txtItemCd'];
		}
		if (!empty($formData['txtName'])) {
			$params['name'] = $formData['txtName'];
		}
		if (!empty($formData['txtOrder'])) {
			$params['order'] = $formData['txtOrder'];
		}
		if (!empty($formData['txtDispFlg'])) {
			$params['dispFlg'] = $formData['txtDispFlg'];
		}
		if (!empty($formData['txtNote1'])) {
			$params['note1'] = $formData['txtNote1'];
		}
		if (!empty($formData['txtNote2'])) {
			$params['note2'] = $formData['txtNote2'];
		}
		if (!empty($formData['txtNote3'])) {
			$params['note3'] = $formData['txtNote3'];
		}
		if (!empty($formData['txtNote4'])) {
			$params['note4'] = $formData['txtNote4'];
		}
		if (!empty($formData['txtNote5'])) {
			$params['note5'] = $formData['txtNote5'];
		}
		parent::visitByButton(parent::getScreenName(),"更新", $params);
		
		$error = "";	// ADD 20140516 Hieunm
		if($formData['hiddenId'] == 0){
			//$classServ->insertMstClass($mstClass);
			// ADD 20140516 Hieunm start
			$itemCd= $formData['txtItemCd'];
			$itemType = $formData['hiddenItemType'];
			$checkExist = $classServ->checkMstClassByItemTypeAndItemCd($itemType, $itemCd);

			if ($checkExist > 0) {
				$error = "既存している区分を入力しました。";
			} else {
				$classServ->insertMstClass($mstClass);
			}
			// ADD 20140516 Hieunm end
		}else{
			$classServ->updateMstClass($mstClass);
		}

		$this->searchMstClass($formData, $error);
	}

	/**
	 * deleteMstClass
	 */
	private function deleteMstClass($formData){
		$classServ = new Core_Service_MstClassService();
		$strIdDelete = $formData['hiddenIdDelete'];
		
		$params = null;
		if (!empty($strIdDelete)) {
			$params['IdsToDelete'] = $strIdDelete;
		}
		parent::visitByButton(parent::getScreenName(),"削除", $params);
		
		$arrIdDelete = explode('-', $strIdDelete);
		$classServ->deleteMstClass($arrIdDelete);

		$this->searchMstClass($formData);
	}
}