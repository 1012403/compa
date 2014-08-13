<?php
class Core_Db_OrderInfoDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::ORDER_INFO;

	protected $_primary = 'order_id';

	protected $_instanceClass = 'Core_Models_OrderInfo';
	/**
	 *
	 * @param unknown $idOrder
	 * @param unknown $idUsername
	 * @return getOrderByIdOderDetail
	 */
	public function getOrderByUser($idUsername){
		$where = array ();
		$where ['user_id  = ?'] = $idUsername;
		$where ['order_status  = ?'] = Core_Util_Const::ORDER_STATUS_3;
		$where ['delete_flg  = ?'] = Core_Util_Const::DELETE_FLG_0;
		$order = $this->getAll ( $where );
		return $order;
	}

	public function getListOrderInfo($username, $orderstatus, $paginatorData) {
		$query = $this->select()->setIntegrityCheck ( false )
		->from(array("ord" => $this->_name));

		$query = $this->joinAndWhereForGetList($username, $orderstatus, $query);

		//page
		if ($paginatorData['itemCountPerPage'] > 0) {
			$page     = $paginatorData['currentPage'];
			$rowCount = $paginatorData['itemCountPerPage'];
			$query->limitPage($page, $rowCount);
		}

		$query->order("ord.order_date_time DESC");
		$rows = $this->fetchAll($query);
		$data = $this->setRowsToArray($rows);
		return $data;
	}

	private function joinAndWhereForGetList($username, $orderstatus, Zend_Db_Table_Select  $query) {
		$query->joinInner(array("muser" => Core_Util_TableNames::MST_USER), "ord.user_id = muser.user_id")
		// MOD 20140428 Hieunm start
		//->joinInner(array("shipping" => Core_Util_TableNames::MST_USER_SHIPPING), "ord.shipping_seq = shipping.shipping_seq")
		//->joinLeft(array("shipping" => Core_Util_TableNames::MST_USER_SHIPPING),
		//					"ord.shipping_seq = shipping.shipping_seq AND ord.user_id = shipping.user_id ")
		// MOD 20140428 Hieunm end
		->where("ord.delete_flg = ?", Core_Util_Const::DELETE_FLG_0);

		if (Core_Util_Helper::isNotEmpty($username)) {
			$query->where("muser.user_name LIKE ?", '%' . $username . '%');
		}

		if (Core_Util_Helper::isNotEmpty($orderstatus)) {
			$query->where( "ord.order_status = ?", $orderstatus);
		}

		// ADD 20140428 Hieunm start
		$query->where("muser.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		// ADD 20140428 Hieunm end

		return $query;
	}

	public function getCountListOrdeInfo($username, $orderstatus) {
		$query = $this->select()->setIntegrityCheck (false)
		->from(array("ord" => $this->_name),
				array('count(*) as amount')
		);

		$query = $this->joinAndWhereForGetList($username, $orderstatus, $query);

		$rows = $this->fetchAll($query);
		return $rows[0]->amount;
	}

	public function updateStatusOrder($id, $orderstatus) {
		/* @var $order Core_Models_OrderInfo */
		$order = $this->get(array("order_id = ?" => $id));
		$order->setOrderStatus($orderstatus);
		$this->updateRecord($order, array("order_id = ?" => $id, "delete_flg = ?" => Core_Util_Const::DELETE_FLG_0));
		return true;
	}

	/**
	 *
	 * @param int $id
	 * @return Core_Models_OrderInfo
	 */
	public function getOrderById($id) {
		$query = $this->select()->from(array("ord" => $this->_name))->setIntegrityCheck(false)
		->joinLeft(array("usr" => Core_Util_TableNames::MST_USER), "usr.user_id = ord.user_id", "usr.user_name as user_name")
		->where("ord.order_id  = ?", $id)
		->where("ord.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);

		/*$where = array ();
		$where ['order_id  = ?'] = $id;
		$where ['delete_flg  = ?'] = Core_Util_Const::DELETE_FLG_0;
		$order = $this->get ( $where );*/
		$rows = $this->fetchAll($query);
		$arrOrders = $this->setRowsToArray($rows);
		if (count($arrOrders) > 0) {
			return $arrOrders[0];
		}
		return null;
	}

	public function getListOrderInfoForCsvExport($username, $orderstatus) {
		$query = $this->select()->setIntegrityCheck ( false )
		->from(array("ord" => $this->_name));

		$query = $this->joinAndWhereForGetListCsvExport($username, $orderstatus, $query);

		$query->order("ord.order_date_time DESC");
		$rows = $this->fetchAll($query);
		$data = $this->setRowsToArray($rows);
		return $data;
	}

	private function joinAndWhereForGetListCsvExport($username, $orderstatus, Zend_Db_Table_Select $query) {
		$query->joinInner(array("muser" => Core_Util_TableNames::MST_USER),
								"ord.user_id = muser.user_id",
								array("login_username" => "muser.login_username"))
		->joinLeft(array("ordDetail" => Core_Util_TableNames::ORDER_DETAIL_INFO),
						 "ord.order_id = ordDetail.order_id",
						 array("detail_no"				=> "ordDetail.detail_no",
						 	   "quantity"				=> "ordDetail.quantity",
						 	   "price"					=> "ordDetail.price",
						 	   "price_including_tax"	=> "ordDetail.price_including_tax",
							   "tax"					=> "ordDetail.tax",
						 	   "shipping_fee"			=> "ordDetail.shipping_fee",
						 	   "total_price"			=> "ordDetail.total_price"))
		->joinLeft(array("mstProd" => Core_Util_TableNames::MST_PRODUCT),
						 "ordDetail.product_id = mstProd.product_id",
						 array("product_name" => "mstProd.product_name", "product_no" => "mstProd.product_no"))
		->joinLeft(array("mstClass_orderStatus" => Core_Util_TableNames::MST_CLASS),
						 "mstClass_orderStatus.item_type = '" . Core_Util_Const::ORDER_STATUS
						 . "' AND mstClass_orderStatus.item_cd = ord.order_status",
						 array("order_status_name" => "mstClass_orderStatus.item_name"))
		->joinLeft(array("mstClass_transType" => Core_Util_TableNames::MST_CLASS),
						 "mstClass_transType.item_type = '" . Core_Util_Const::ITEM_TYPE_TRANS_TYPE
						 . "' AND mstClass_transType.item_cd = ord.trans_type",
						 array("trans_type_name" => "mstClass_transType.item_name"))

		->where("ord.delete_flg = ?", Core_Util_Const::DELETE_FLG_0);

		if (Core_Util_Helper::isNotEmpty($username)) {
			$query->where("muser.user_name LIKE ?", '%' . $username . '%');
		}

		if (Core_Util_Helper::isNotEmpty($orderstatus)) {
			$query->where( "ord.order_status = ?", $orderstatus);
		}

		$query->where("muser.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);

		return $query;
	}

}