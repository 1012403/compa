<?php
class Core_Service_OrderService extends Core_Service_Abstract {
	/**

	 * @param unknown $idUsername
	 * @return getProductHistory
	 */
	public function getProductHistory($idUsername, $keyword = null, $arrConditions = null, $tylelist = null, $statrtDate, $endDate, $paginatorData){
		try {
			$arrOrderDetail =array();
			$orderDetailDb = new Core_Db_OrderDetailInfoDb();
			$arr= $orderDetailDb->getDetailByOrder($idUsername,$keyword, $arrConditions, $tylelist, $statrtDate, $endDate ,$paginatorData);

			foreach ($arr as $value) {
				$productHis=array();
				$product = new Core_Models_MstProduct($value);
				$order=new Core_Models_OrderInfo($value);
				$detail=new Core_Models_OrderDetailInfo($value);

				$productHis['product'] = $product;
				$productHis['orderdetail'] = $detail;
				$productHis['order'] = $order;

				$arrOrderDetail[]=$productHis;
			}
			return $arrOrderDetail;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function countDetailByOrder($idUsername, $keyword = null, $arrConditions = null, $tylelist = null, $statrtDate= null, $endDate=null){
		try {
			$arrOrderDetail =array();
			$orderDetailDb = new Core_Db_OrderDetailInfoDb();
			$row = $orderDetailDb->countDetailByOrder($idUsername, $keyword, $arrConditions, $tylelist , $statrtDate, $endDate);
			$value = Core_Util_Helper::getDataRow($row, 'count',0);
			return $value;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	/* public function getDetailOrder( $idUsername, $paginatorData){
		try {
			$orderDb=new Core_Db_OrderInfoDb();
			$arrOrder = $orderDb->getOrderByUser($idUsername);
			$productHis=array();
			$i=0;
			foreach ($arrOrder as $key => $value) {
				$orderDetailDb = new Core_Db_OrderDetailInfoDb();
				$arrOrderDetail = $orderDetailDb->getDetailByOrder($value->getOrderId(),$paginatorData);

				foreach ($arrOrderDetail as $key1 => $value1) {
					$prodDb=new Core_Db_MstProductDb();
					$productHis[$i]['order'] = $value;
					$productHis[$i]['orderdetail'] = $value1;
					$productHis[$i]['product'] = $prodDb->getProductById($value1->getProductId());
					$i++;
				}
			}

			return $productHis;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	} */



	public function updateOrderCartInfo($productid, $quantity=1){
		try {
			$this->beginTransaction();
			$user = Core_Util_Helper::getLoginUser();
			$ordercart = new Core_Models_OrderCartInfo();
			$ordercart->setUserId($user->getUserId());
			$ordercart->setProductId($productid);
			$ordercart->setQuantity($quantity);

			$db 		= new Core_Db_OrderCartDb();
			$result = $db->getOrderCartInfo($ordercart->getUserId(), $ordercart->getProductId());
			if ($result == false){
				$db->insertData($ordercart);
			} else {
				$db->updateData($ordercart);
			}
			$result = $db->getCountProduct($ordercart->getUserId());
			$this->commit();
			return $result;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function getProductHistoryByProductId($paginatorData, $product_id){
		try {
			$arrOrderDetail =array();
			$user = Core_Util_Helper::getLoginUser();
			$orderDetailDb = new Core_Db_OrderDetailInfoDb();
			$arr= $orderDetailDb->getProductHistoryByProductId($paginatorData, $product_id, $user->getUserId());

			foreach ($arr as $value) {
				$productHis=array();
				$product = new Core_Models_MstProduct($value);
				$order=new Core_Models_OrderInfo($value);
				$detail=new Core_Models_OrderDetailInfo($value);

				$productHis['product'] = $product;
				$productHis['orderdetail'] = $detail;
				$productHis['order'] = $order;

				$arrOrderDetail[]=$productHis;
			}
			return $arrOrderDetail;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function countProductHistoryByProductId($product_id){
		try {
			$arrOrderDetail =array();
			$user = Core_Util_Helper::getLoginUser();
			$orderDetailDb = new Core_Db_OrderDetailInfoDb();
			$row = $orderDetailDb->countProductHistoryByProductId($product_id, $user->getUserId());
			$value = Core_Util_Helper::getDataRow($row, 'count');
			return $value;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function getListOrder($username, $orderStatus, $paginatorData) {
		try {
			$orderDb = new Core_Db_OrderInfoDb();
			$arrOrderInfo = $orderDb->getListOrderInfo($username, $orderStatus, $paginatorData);
			return $arrOrderInfo;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function getListOrderInfoForCsvExport($username, $orderStatus) {
		try {
			$orderDb = new Core_Db_OrderInfoDb();
			$arrOrderInfo = $orderDb->getListOrderInfoForCsvExport($username, $orderStatus);
			return $arrOrderInfo;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function countListOrder($username, $orderStatus) {
		try {
			$orderDb = new Core_Db_OrderInfoDb();
			$count = $orderDb->getCountListOrdeInfo($username, $orderStatus);
			return $count;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function updateStatusMultiOrder($arrIdOrder, $orderStatus) {
		try {
			$orderDb = new Core_Db_OrderInfoDb();
			$this->beginTransaction();
			if (is_array($arrIdOrder)) {
				foreach ($arrIdOrder as $key => $id) {
					/* @var $order Core_Models_OrderInfo */
					$orderDb->updateStatusOrder($id, $orderStatus);
				}

				if ($orderStatus == Core_Util_Const::FINAL_ORDER_STATUS) {
					// update user point
					$orderDetailDb = new Core_Db_OrderDetailInfoDb();
					$productDb = new Core_Db_MstProductDb();
					$userDb = new Core_Db_MstUserDb();

					// get all order detail of order
					$arrOrderDetail = $orderDetailDb->getListOrderDetailByOrderId($id);
					$totalPoint = 0;

					/* @var $orderDetail Core_Models_OrderDetailInfo */
					foreach ($arrOrderDetail as $key => $orderDetail) {
						// get product of order detail
						$productId = $orderDetail->getProductId();

						//$product = $productDb->getProductById($productId);
						$product = $productDb->getProductWithPriceAndMagni($productId);
						//Core_Util_LocalLog::writeLog("----------------------------------");

						$quantity = $orderDetail->getQuantity();
						// get point of product
						$productPoint = $product->getPoint($quantity);
						// sum point of product
						$totalPoint += $productPoint;
					}

					// get user of current order in loop
					$orderInfo = $orderDb->getOrderById($id);
					$userId = $orderInfo->getUserId();
					$user = $userDb->getUserById($userId);
					//Core_Util_LocalLog::writeLog("user id = " . $userId);
					//Core_Util_LocalLog::writeLog("total point = " . $totalPoint);
					// round point
					$totalPoint = round ($totalPoint);
					//Core_Util_LocalLog::writeLog("total point (round) = " . $totalPoint);
					// increase user point
					$user->addPoint($totalPoint);
					// save user
					$userDb->updateRecord($user, array("user_id = ?" => $user->getUserId()));
				}
			}
			$this->commit();
			return $res;
		}catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function getListOrderDetailService($orderId) {
		try {
			$orderDetailDb = new Core_Db_OrderDetailInfoDb();
			$arrOrderDetail = $orderDetailDb->getListOrderDetailByOrderId($orderId);
			return $arrOrderDetail;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function getOrderInfoById($orderId) {
		try {
			$orderDb = new Core_Db_OrderInfoDb();
			$orderInfo = $orderDb->getOrderById($orderId);
			return $orderInfo;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function updateOrderDetail($detaiNo, $orderId, $priceTax, $quantity, $totalPrice) {
		try {
			$orderDetailDb = new Core_Db_OrderDetailInfoDb();
			// kadai 52 => pricetax become price no tax
			$res = $orderDetailDb->updatePricetaxQuantity($detaiNo, $orderId, $priceTax, $quantity, $totalPrice);
			return $res;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}


	public function deleteOrderDetail($detaiNo, $orderId) {
		try {
			$this->beginTransaction();
			$orderDetailDb = new Core_Db_OrderDetailInfoDb();
			$res = $orderDetailDb->deleteOrderDetail($detaiNo, $orderId);
			$this->commit();
			return $res;
		}catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function getOrderStatus() {
		try {
			$db = new Core_Db_MstClassDb();
			$res = $db->getMstClassByItemType(Core_Util_Const::ORDER_STATUS);
			return $res;
		}catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	private $curOrderIdCsv = null;
	private $curOrderId = null;
	public function insertArrOrderInfo($arrOrderInfo) {
		try {
			$orderDb = new Core_Db_OrderInfoDb();
			$orderDetailDb = new Core_Db_OrderDetailInfoDb();
			$this->beginTransaction();

			if (is_array($arrOrderInfo)) {
				foreach ($arrOrderInfo as $key => $value) {
					if ($this->curOrderIdCsv == null || $this->curOrderIdCsv != $value->getOrderId()){
						/* @var $value Core_Models_OrderInfo */
						$user = Core_Util_Helper::getLoginAdmin();
				        $data[Core_Models_MasterModel::INSERT_ID_FIELD] 	= $user->getUserId();
				        $data[Core_Models_MasterModel::INSERT_DATE_FIELD] 	= new Zend_Db_Expr('NOW()');
				        $data[Core_Models_MasterModel::UPDATE_ID_FIELD] 	= $user->getUserId();
				        $data[Core_Models_MasterModel::UPDATE_DATE_FIELD] 	= new Zend_Db_Expr('NOW()');
	
				        $data["user_id"] 				= $value->getUserId();
						$data["order_date_time"] 		= $value->getOrderDateTime();
						$data["shippping_hope_date"] 	= $value->getShipppingHopeDate();
						$data["used_point"] 			= $value->getUsedPoint();
						$data["order_status"] 			= $value->getOrderStatus();
						$data["shipping_des_name"]		= $value->getShippingDesName();
						$data["post_no"] 				= $value->getPostNo();
						$data["address1"] 				= $value->getAddress1();
						$data["address2"] 				= $value->getAddress2();
						$data["tel_no"] 				= $value->getTelNo();
						$data["fax_no"] 				= $value->getFaxNo();
						$data["trans_type"] 			= $value->getTransType();
						$data["remark"] 				= $value->getRemark();
						$data["delete_flg"] 			= Core_Util_Const::DELETE_FLG_0;
	
	        			$this->curOrderId = $orderDb->insert($data);
	        			$this->curOrderIdCsv = $value->getOrderId();
					}
        			if ($this->curOrderId  !== '' && $this->curOrderId  !== null && $this->curOrderId  !== false) {
        				$detailData[Core_Models_MasterModel::INSERT_ID_FIELD] 	= $user->getUserId();
				        $detailData[Core_Models_MasterModel::INSERT_DATE_FIELD] = new Zend_Db_Expr('NOW()');
				        $detailData[Core_Models_MasterModel::UPDATE_ID_FIELD] 	= $user->getUserId();
				        $detailData[Core_Models_MasterModel::UPDATE_DATE_FIELD] = new Zend_Db_Expr('NOW()');

        				$detailData["order_id"] 				= $this->curOrderId;
        				$detailData["detail_no"] 				= $value->getDetailNo();
        				$detailData["product_id"] 				= $value->getProductId();
        				$detailData["price"] 					= $value->getPrice();
        				$detailData["price_including_tax"]		= $value->getPriceIncludingTax();
        				$detailData["tax"] 						= $value->getTax();
        				$detailData["quantity"] 				= $value->getQuantity();
        				$detailData["total_price"] 				= $value->getTotalPrice();
        				$detailData["shipping_fee"] 			= $value->getShippingFee();
        				$detailData["delete_flg"] 				= Core_Util_Const::DELETE_FLG_0;

						$orderDetailDb->insert($detailData);
        			}
				}
			}

			$this->commit();
			return true;
		}catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

}
