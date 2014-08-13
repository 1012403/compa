<?php
class Admin_OrderController extends Core_Controller_AdminAbstract {
	private $screenName = "注文情報管理《管理サイト》";
	public function init() {
		parent::init();
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery.form.3.5.js');		// Get Login Session Info
	}

	public function indexAction() {
		parent::setScreenName($this->screenName);

	    $page = $this->_request->getParam('page', 1);
	    $username = $this->_request->getParam('username', null);
	    $orderStatus = $this->_request->getParam('orderStatus', null);

	    $params = null;
	    if(!empty($page)) {
	    	$params["page"] = $page;
	    }
	    if(!empty($username)) {
	    	$params["username"] = $username;
	    }
	    if(!empty($orderStatus)) {
	    	$params["orderStatus"] = $orderStatus;
	    }

	    $operateElementName = $this->_request->getParam('operateElementName', null);
	    if (empty($operateElementName)) {
	    	parent::visitByOperation(parent::getScreenName(), null, null, null);
	    } else {
	    	parent::visitByButton(parent::getScreenName(), $operateElementName, $params);
	    }

	    $paginatorData ['itemCountPerPage'] = Core_Util_Const::ITEMS_PER_PAGE;
	    $paginatorData ['pageRange'] = Core_Util_Const::PAGE_RANGE;
	    $paginatorData ['currentPage'] = $page;
	    Zend_Paginator::setDefaultScrollingStyle('Sliding');
	    Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

	    /// get list order info
        $orderServ = new Core_Service_OrderService();
        $arrOrderInfo = $orderServ->getListOrder($username, $orderStatus, $paginatorData);
        $totalItem = $orderServ->countListOrder($username, $orderStatus);
        $this->view->arrOrderInfo = $arrOrderInfo;

        $objPaginator = new Core_Util_Paginator ();
        $paginator = $objPaginator->createPaginator ( $totalItem, $paginatorData );
        $this->view->paginator = $paginator;
        $paginator->setView($this->view);

        $form = new Admin_Form_OrderSearch();
        $arrOrderStatus = $this->getOrderStatus();
        /* @var $mstClass Core_Models_MstClass */
        $arr = array();
        $arr[null] = "すべて";
        foreach ($arrOrderStatus as $key => $mstClass) {
        	$arr[$mstClass->getItemCd()] = $mstClass->getItemName();
        }

        $form->orderStatus->setMultiOptions($arr);
        unset($arr[null]);
        $form->orderStatusChange->setMultiOptions($arr);
        $allParams = $this->_getAllParams();
        $form->populate($allParams);
        $this->view->form = $form;

	}

	public function updatestatusAction(){
		$selectedIds = $this->_request->getParam('selectedIds', null);
		$orderStatus = $this->_request->getParam('orderStatus', null);

		$params = null;
		if(!empty($selectedIds)) {
			$params["selectedIds"] = $selectedIds;
		}
		if(!empty($orderStatus)) {
			$params["orderStatus"] = $orderStatus;
		}
		parent::visitByButton(parent::getScreenName(),"更新", $params);

		$arrSelectedIds = Core_Util_Helper::getArrFromString($selectedIds);
		$orderServ = new Core_Service_OrderService();
		$orderServ->updateStatusMultiOrder($arrSelectedIds, $orderStatus);
		$this->_redirect("admin/order/");
	}

	public function detailAction() {
		$id = $this->_request->getParam('id', null);

		$params = null;
		if (!empty($id)) {
			$params["orderId"] = $id;
		}
		parent::visitByButton(parent::getScreenName(),"詳細", $params);
		parent::setScreenName("注文情報詳細　《管理サイト》");

		$deOrderSer = new Core_Service_OrderService();
		$arrDetailOrder = $deOrderSer->getListOrderDetailService($id);

		$this->view->arrOrderDetail = $arrDetailOrder;
		$orderInfo = $deOrderSer->getOrderInfoById($id);
		$this->view->orderInfo = $orderInfo;
	}

	public function updatedetailAction() {
		$this->noRender();
		$this->disableLayout();
		$detailNo = $this->_request->getParam('detailNo', null);
		$orderId = $this->_request->getParam('orderId', null);
		$priceTax = $this->_request->getParam('priceTax', null);
		// kadai 52 => pricetax become price no tax
		$price = $priceTax;
		$quantity = $this->_request->getParam('quantity', null);

		$price = str_replace(",", "", $price);
		$quantity = str_replace(",", "", $quantity);

		$params = null;
		if (!empty($detailNo)) {
			$params["detailNo"] = $detailNo;
		}
		if (!empty($orderId)) {
			$params["orderId"] = $orderId;
		}
		if (!empty($priceTax)) {
			$params["priceTax"] = $priceTax;
		}
		if (!empty($quantity)) {
			$params["quantity"] = $quantity;
		}

		$totalPrice = intval($price) * intval($quantity);

		parent::visitByButton(parent::getScreenName(),"更新", $params);

		$ordeServ = new Core_Service_OrderService();
		$success = $ordeServ->updateOrderDetail($detailNo, $orderId, $price, $quantity, $totalPrice);

		if ($success) {
			echo "true";
		} else {
			echo "false";
		}
	}

	function deletedetailAction(){
		$this->noRender();
		$this->disableLayout();
		$detailNo = $this->_request->getParam('detailNo', null);
		$orderId = $this->_request->getParam('orderId', null);

		$params = null;
		if (!empty($detailNo)) {
			$params["detailNo"] = $detailNo;
		}
		if (!empty($orderId)) {
			$params["orderId"] = $orderId;
		}
		parent::visitByButton(parent::getScreenName(),"削除", $params);

		$ordeServ = new Core_Service_OrderService();
		$success = $ordeServ->deleteOrderDetail($detailNo, $orderId);

		if ($success) {
			echo "true";
		} else {
			echo "false";
		}
	}

	/**
	 * importcsvAction
	 */
	public function importcsvAction(){
		parent::visitByButton($this->screenName,"ＣＳＶ取込み");
		$this->disableLayout();
		$this->noRender();
		$temp = explode(".", $_FILES["fileUser"]["name"]);
		$extension = end($temp);

		if (isset($_FILES["fileUser"])) {
			// Check error file extension
			if (strtolower($extension) != "csv") {//$_FILES["fileUser"]["error"] > 0 &&
				$error = "選択したファイルの形式が不正です。";
				echo $error;
			} else {
				$str = file_get_contents($_FILES["fileUser"]["tmp_name"]);
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
				unset($arrRows[0]); //delete Header of file content
				$success = $this->saveCSVRows($arrRows);
				
				if ($success === true) {
					echo "true";
				} else {
					/*$error = " 行にエラーが発生しています。内容を確認してください。\n \n";
					foreach($success as $key => $value){
						foreach($value as $subKey => $subValue) {
							$error .= $subValue . "\n";
						}
						$error .= "\n";
					}*/
					$error = $success ." 行にエラーが発生しています。内容を確認してください。";
					echo $error;
				}
			}
		} else {
			echo "選択したファイルの形式が不正です。";
		}
	}

	/**
	 * saveCSVRows
	 */
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

			$validRows = $this->validRows($data);
			if (is_bool($validRows)) {
				return $this->saveRowToDB($data);
			} else {
				return $validRows;
			}
		}
		return "arrRows is not array";
	}

	/**
	 * saveRowToDB
	 */
	private function saveRowToDB($arrRows){
		$serv = new Core_Service_OrderService();
		$userServ = new Core_Service_UserService();
		$prodServ = new Core_Service_MstProductService();

		if (is_array($arrRows)) {
			$arrOrderInfo = array();	// This array contains array object
			foreach ($arrRows as $key => $row) {
				$orderInfo = Core_Models_OrderInfo::createOrderInfoFromCsvRow($row);

				$loginUserName = $orderInfo->getLoginName();
				$userId = $userServ->getUserIdByUserNAme($loginUserName);
				$orderInfo->setUserId($userId);

				$condition["product_no = ?"] = $orderInfo->getProductNo();
				$productData = $prodServ->getProductByCond($condition);
				/* @var $productId Core_Models_MstProduct */
				$productId = $productData[0]->getProductId();
				$orderInfo->setProductId($productId);

				$arrOrderInfo[] = $orderInfo;
			}

			if (!$serv->insertArrOrderInfo($arrOrderInfo)) {
				//Core_Util_LocalLog::writeLog("save " . $arrMstClass[0]->getId() . "fail");
				return false;
			}

			return true;
			// remark
			/*
			$res = $serv->insertArrOrderInfo($arrOrderInfo);
			if (Core_Util_Helper::isEmpty($res)) {
				return true;
			} else {
				return $res;
			}
			*/
		}
		return "arrRows is not array";
	}

	/**
	 * validRows
	 */
	private function validRows($arrRows) {
		$arrErrors = array();
		$error = "";
		if (is_array($arrRows)) {
			foreach ($arrRows as $key => $strRow) {
				$lineError = $key + 1;
				$validARow = $this->validARow($strRow, ($lineError));
				if (is_string($validARow) &&  Core_Util_Helper::isNotEmpty($validARow)) {//
					if ($error !== ""){
						$error .= ", ";
					}
					$error .= $lineError ;
				}
				/*
				if (count($validARow) > 0) {
					//$error .= $validARow . "<br />";
					//$arrErrors[] = $lineError;
					$arrErrors[] = $validARow;
				}*/
			}

			/*if (count($arrErrors) > 0) {
				return $arrErrors;
			} else {
				return true;
			}
			*/
			if (Core_Util_Helper::isEmpty($error)) {
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
	 * @param string $strRow
	 * @param int $rowIndex
	 * @throws Exception
	 * @return string
	 */
	private function validARow($strRow, $rowIndex) {
		//$arrErrors = array();
		$arrErrors = "";
		$csvAgent = new Core_Models_CsvAgent();
		$arrField = $csvAgent->convertCsvRowToArray($strRow);

		if (count($arrField) != Core_Models_OrderInfo::$TOTAL_FIELD) {
			//throw new Exception("Csv field not match to MstUser Obj");
			$arrErrors .= $rowIndex;
			Core_Util_LocalLog::writeLog($arrErrors);
		} else {
			$serv = new Core_Service_OrderService();
			$productServ = new Core_Service_MstProductService();
			$userServ = new Core_Service_UserService();
			$error = "";
			$cnt = count($arrField);
			for ($i = 0; $i < $cnt; $i++) {
				switch ($i) {
					case 0: // order_id

						break;
					case 1: // login_username
						// check string length 50, not null
						$res = Core_Util_Helper::checkString("login_username ", $arrField[$i], null, 50, true, "login_username ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						// check login_username exist in DB
						$loginNameExist = $userServ->getUserByUsername(trim($arrField[$i]));
						if ($loginNameExist === false) {
							$error = "Line " . $rowIndex . ": ログインユーザＩＤ「".$arrField[$i]."」項目が存在しません。";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						break;
					case 2: // order_date_time
						// check string for date time
						$res = Core_Util_Helper::checkString("order_date_time ", $arrField[$i], 19, 19, true, "order_date_time ");
						//$resNUmber = Core_Util_Helper::checkNumberic("order_date_time ", $arrField[$i], NULL, 99999999, true, "order_date_time ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}
						// check string number
						/*elseif (Core_Util_Helper::isNotEmpty($resNUmber)) {
							//Core_Util_LocalLog::writeLog($i.'='.$resNUmber);
							$error = "Line " . $rowIndex . ":" . $resNUmber . "";
							$arrErrors .=$error ;
						} */
						else {
							try {
								$date = new DateTime($arrField[$i]);
								$arrField[$i] = $date->format('Y/m/d');
								$res = Core_Util_Helper::checkDate("order_date_time ", $arrField[$i], true, "order_date_time ");
								if (Core_Util_Helper::isNotEmpty($res)) {
									$error = "Line " . $rowIndex . ":" . $res . "";
									$arrErrors .= $error;
									Core_Util_LocalLog::writeLog($i.'='.$res);
								}
							} catch (Exception $e) {
								$arrErrors .= $i;
							}
							
						}

						break;
					case 3: // shipping_hope_date
						// check string for date
						$res = Core_Util_Helper::checkString("shipping_hope_date ", $arrField[$i], 10, 10, true, "shipping_hope_date ");
						//$resNUmber = Core_Util_Helper::checkNumberic("shipping_hope_date ", $arrField[$i], NULL, 99999999, true, "shipping_hope_date ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}
						/*
						// check string number
						elseif (Core_Util_Helper::isNotEmpty($resNUmber)) {
							//Core_Util_LocalLog::writeLog($i.'='.$resNUmber);
							$error = "Line " . $rowIndex . ":" . $resNUmber . "";
							$arrErrors .=$error ;
						}
						*/
						else {
							$date = new DateTime($arrField[$i]);
							$arrField[$i] = $date->format('Y/m/d ');
							$res = Core_Util_Helper::checkDate("shipping_hope_date ", $arrField[$i], true, "shipping_hope_date ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}

						break;
					case 4: // order_status
						// Get orderStatus Code
						//$orderStatus = explode(":", $arrField[$i]);
						//$orderStatus = $orderStatus[0];
						// check string
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							
							if (strpos($arrField[$i],':')){
								$iparr = explode ( ":", $arrField[$i]);							
								$value = $iparr[0];
							} else{
								$value = $arrField[$i];
							}
							
							$res = Core_Util_Helper::checkNumberic("order_status ", $value, null, 
							9999999999999999999, true, "order_status ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}	
							
						}											

						break;
					case 5: // used_point
						// check numeric
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							$res = Core_Util_Helper::checkNumberic("used_point ", $arrField[$i], null, 9, false, "used_point ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}

						break;
					case 6: // shipping_des_name
						// check string length 100, null
						$res = Core_Util_Helper::checkString("shipping_des_name ", $arrField[$i], null, 100, false, "shipping_des_name ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						break;
					case 7: // post_no
						// check string length 20, null
						$res = Core_Util_Helper::checkString("post_no ", $arrField[$i], null, 20, false, "post_no ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						break;
					case 8: // address1
						// check string length 250, null
						$res = Core_Util_Helper::checkString("address1 ", $arrField[$i], null, 250, false, "address1 ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						break;
					case 9: // address2
						// check string length 250, null
						$res = Core_Util_Helper::checkString("address2 ", $arrField[$i], null, 250, false, "address2 ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						break;
					case 10: // tel_no
						// check string length 50, null
						$res = Core_Util_Helper::checkString("tel_no ", $arrField[$i], null, 50, false, "tel_no ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						break;
					case 11: // fax_no
						// check string length 50, null
						$res = Core_Util_Helper::checkString("fax_no ", $arrField[$i], null, 50, false, "fax_no ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						break;
					case 12: // trans_type
						// Get transType Code
						//$transType = explode(":", $arrField[$i]);
						//$transType = $transType[0];
						// check string length 1, null						
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							
							if (strpos($arrField[$i],':')){
								$iparr = explode ( ":", $arrField[$i]);							
								$value = $iparr[0];
							} else{
								$value = $arrField[$i];
							}
							
							$res = Core_Util_Helper::checkNumberic("trans_type ", $value, null, 
							99999999999, true, "trans_type ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}

						break;
					case 13: // remark
						// check string length 5000, null
						$res = Core_Util_Helper::checkString("remark ", $arrField[$i], null, 5000, false, "remark ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}

						break;
					case 14: // detail_no
						// check numeric
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							$res = Core_Util_Helper::checkNumberic("detail_no ", $arrField[$i], null, 4, true, "detail_no ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}

						break;
					case 15: // product_name

						break;
					case 16: // product_no
						// check string length 20, null
						$res = Core_Util_Helper::checkString("product_no ", $arrField[$i], null, 20, true, "product_no ");
						if (Core_Util_Helper::isNotEmpty($res)) {
							$error = "Line " . $rowIndex . ":" . $res . "";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$res);
						}
						
						// check product exist in DB
						$condition = array();
						$condition["product_no = ?"] = $arrField[$i];
						$productExist = $productServ->getProductByCond($condition);
						if (count($productExist) === 0) {
							$error = "Line " . $rowIndex . ": 商品番号「".$arrField[$i]."」項目が存在しません。";
							$arrErrors .= $error;
							//$arrErrors[] = $rowIndex;
							Core_Util_LocalLog::writeLog($i.'='.$error);
						}
						
						break;
					case 17: // price
						// check numeric
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							$res = Core_Util_Helper::checkNumberic("price ", $arrField[$i], null, 11, true, "price ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}

						break;
					case 18: // price_including_tax
						// check numeric
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							$res = Core_Util_Helper::checkNumberic("price_including_tax ", $arrField[$i], null, 11, true, "price_including_tax ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}

						break;
					case 19: // tax
						// check numeric
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							$res = Core_Util_Helper::checkNumberic("tax ", $arrField[$i], null, 11, true, "tax ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}

						break;
					case 20: // quantity
						// check numeric
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							$res = Core_Util_Helper::checkNumberic("quantity ", $arrField[$i], null, 11, true, "quantity ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}	

						break;
					case 21: // total_price
						// check numeric
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							$res = Core_Util_Helper::checkNumberic("total_price ", $arrField[$i], null, 11, true, "total_price ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}

						break;
					case 22: // shipping_fee
						// check numeric
						if (Core_Util_Helper::isNotEmpty($arrField[$i])) {
							$res = Core_Util_Helper::checkNumberic("shipping_fee ", $arrField[$i], null, 11, false, "shipping_fee ");
							if (Core_Util_Helper::isNotEmpty($res)) {
								$error = "Line " . $rowIndex . ":" . $res . "";
								$arrErrors .= $error;
								//$arrErrors[] = $rowIndex;
								Core_Util_LocalLog::writeLog($i.'='.$res);
							}
						}
						break;
				}
			}
		}
		return $arrErrors;
	}

	/**
	 * exportAction
	 */
	public function exportAction(){
		parent::visitByButton($this->screenName,"ＣＳＶ出力");

		$username = $this->_request->getParam('username', null);
	    $orderStatus = $this->_request->getParam('orderStatus', null);
	    $paginatorData['itemCountPerPage'] = 0;

		$orderServ = new Core_Service_OrderService();
		$arrOrder = $orderServ->getListOrderInfoForCsvExport($username, $orderStatus, $paginatorData);

		$csvData = $this->toCSVString($arrOrder);
		$filename = trim("注文情報管理"). ".csv";
		$result=$this->exportCSVToClient($csvData, $filename);
	}

	private function getOrderStatus() {
		$ordeServ = new Core_Service_OrderService();
		return $ordeServ->getOrderStatus();
	}

	/**
	 * toCSVString
	 * @param unknown $arrUser
	 * @return unknown
	 */
	private function toCSVString($arrOrder) {
		$data = $this->createCSVHeader();
		$data .= $this->createCSVBody($arrOrder);
		return $data;
	}

	private function createCSVHeader() {
		$arrHeader = Core_Models_OrderInfo::getHeaderCsv();
		$csvAgent = new Core_Models_CsvAgent();
		$header = $csvAgent->createLineString($arrHeader);
		return $header;
	}

	/**
	 * createCSVBody
	 * @param string $arrCategory
	 * @return Ambigous <string, NULL, unknown>
	 */
	private function createCSVBody($arrOrder) {
		$csvAgent = new Core_Models_CsvAgent();
		$csvBody = "";
		/* @var $item Core_Models_OrderInfo */
		foreach ($arrOrder as $key => $item) {
			$csvArr = $item->toCsvData();
			$stringRow = $csvAgent->createLineString($csvArr);
			$csvBody .= $stringRow;
		}
		return $csvBody;
	}
}
