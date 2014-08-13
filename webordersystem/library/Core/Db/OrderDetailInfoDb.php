<?php
class Core_Db_OrderDetailInfoDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::ORDER_DETAIL_INFO;

	protected $_primary = array('order_id', 'detail_no');

	protected $_instanceClass = 'Core_Models_OrderDetailInfo';
	/**
	 *
	 * @return getDetailByOrder
	 */
	public function getDetailByOrder($idUsername, $keyword = null, $arrConditions = null, $tylelist = null, $startDate=null, $endDate=null, $paginatorData) {

		$query = $this->select()->from(array("detailOrder" =>$this->_name));
		$query->setIntegrityCheck(false);

		$query->joinInner(array("prod" => Core_Util_TableNames::MST_PRODUCT),
				"prod.product_id = detailOrder.product_id",
				array("prod.*"));

		$query->joinInner(array("ord" => Core_Util_TableNames::ORDER_INFO),
				"ord.order_id = detailOrder.order_id ",array("ord.*"));

		$query->where("ord.user_id  = ?",$idUsername);
		$query->where("detailOrder.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		// ADD 20140428 Hieunm start
		$query->where("ord.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		// ADD 20140428 Hieunm end
		if ($arrConditions != null && is_array($arrConditions)){
			$query->joinInner(array("Cate" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ),
					$this->_db->quoteInto(
							"Cate.product_id = prod.product_id and Cate.category_id IN (?)", $arrConditions
					), null)
					->group("Cate.product_id")
					->having("COUNT(DISTINCT Cate.category_id) >= ?", count($arrConditions));
		}
		$query->where("prod.delete_flg = ?",Core_Util_Const::DELETE_FLG_0 );
		$query->where("prod.product_name like ?", "%$keyword%" );

		if ($startDate!=null && $endDate!=null){
			$dt = "ord.order_date_time between ? and ? ";
			$dt = $this->_db->quoteInto($dt, $startDate, null, 1);
			$dt = $this->_db->quoteInto($dt, $endDate, null, 1);
			$query->where($dt);
		}
		if ($startDate!=null ){
			$query->where("ord.order_date_time >=?",$startDate );
		}
		if ($endDate!=null ){
			$query->where("ord.order_date_time <=?",$endDate);
		}

		$displayNamespace = new Zend_Session_Namespace('Display');
		if (!isset($displayNamespace->sort))
		{
			$displayNamespace->sort = Core_Util_Const::$SORT_DEFAULT;
		}
		if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0){
			$query->order ("price");
		} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
			$query->order ("price DESC");
		} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
			$query->order ("detailOrder.update_ymd DESC");
		}
		else {
			$query->order("prod.product_no ASC");
		}

		//page
		if ($paginatorData['itemCountPerPage'] > 0) {
			$page     = $paginatorData['currentPage'];
			$rowCount = $paginatorData['itemCountPerPage'];
			$query->limitPage($page, $rowCount);

		}
		$rows = $this->fetchAll($query)->toArray();
		return $rows;

	}

	/**
	 * count detail order
	 * @param unknown $idUsername
	 * @param string $keyword
	 * @param string $arrConditions
	 * @param string $tylelist
	 * @param string $startDate
	 * @param string $endDate
	 * @return multitype:
	 */
	public function countDetailByOrder($idUsername, $keyword = null, $arrConditions = null, $tylelist = null, $startDate=null, $endDate=null) {
		$query = $this->select()->from(array("detailOrder" =>$this->_name), "count(*) as count");
		//$query = $this->select()->from(array("detailOrder" =>$this->_name));
		$query->setIntegrityCheck(false);

		$query->joinInner(array("prod" => Core_Util_TableNames::MST_PRODUCT),
				"prod.product_id = detailOrder.product_id",null);

		$query->joinInner(array("ord" => Core_Util_TableNames::ORDER_INFO),
				"ord.order_id = detailOrder.order_id ",null);

		$query->where("ord.user_id  = ?",$idUsername);
		$query->where("detailOrder.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		// ADD 20140428 Hieunm start
		$query->where("ord.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		// ADD 20140428 Hieunm end
		if ($arrConditions != null && is_array($arrConditions)){
			$query->joinInner(array("Cate" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ),
					$this->_db->quoteInto(
							"Cate.product_id = prod.product_id and Cate.category_id IN (?)", $arrConditions
					), null)
					->group("Cate.product_id")
					->having("COUNT(DISTINCT Cate.category_id) >= ?", count($arrConditions));
		}
		$query->where("prod.delete_flg = ?",Core_Util_Const::DELETE_FLG_0 );
		$query->where("prod.product_name like ?", "%$keyword%" );

		if ($startDate!=null && $endDate!=null){
			$dt = "ord.order_date_time between ? and ? ";
			$dt = $this->_db->quoteInto($dt, $startDate, null, 1);
			$dt = $this->_db->quoteInto($dt, $endDate, null, 1);
			$query->where($dt);

		}
		$displayNamespace = new Zend_Session_Namespace('Display');
		if (!isset($displayNamespace->sort))
		{
			$displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
		}
		if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0){
			$query->order ("price_including_tax");
		} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
			$query->order ("price_including_tax DESC");
		} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
			$query->order ("prod.update_ymd");
		}

		//print $query;
		$rows = $this->fetchRow($query)->toArray();
		return $rows;

	}

	/* public function getDetailByOrder($order_id,$paginatorData){
		$query = $this->select()->from(array("detailOrder" =>$this->_name));
		$query->setIntegrityCheck(false);

		$query->joinInner
				(array("prod" => Core_Util_TableNames::MST_PRODUCT),
						"prod.product_id = detailOrder.product_id",
				array());

		$query->where("detailOrder.order_id  = ?", $order_id);
		$query->where("detailOrder.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		$query->order("prod.product_no ASC");

		//page
		if ($paginatorData['itemCountPerPage'] > 0) {
			$page     = $paginatorData['currentPage'];
			$rowCount = $paginatorData['itemCountPerPage'];
			$query->limitPage($page, $rowCount);

		}
		$rows = $this->fetchAll($query);
		$arrOrderDetail	= $this->setRowsToArray($rows);
		return $arrOrderDetail;

	} */

	public function getProductHistoryByProductId($paginatorData, $product_id, $idUsername){

		$query = $this->select()->from(array("detailOrder" =>$this->_name));
		$query->setIntegrityCheck(false);

		$query->joinInner(array("prod" => Core_Util_TableNames::MST_PRODUCT),
				"prod.product_id = detailOrder.product_id",
				array("prod.*"));

		$query->joinInner(array("ord" => Core_Util_TableNames::ORDER_INFO),
				"ord.order_id = detailOrder.order_id ",array("ord.*"));

		$query->where("ord.user_id  = ?",$idUsername);
		$query->where("detailOrder.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		$query->where("prod.delete_flg = 0" );
		$query->where("prod.product_id = ?", $product_id );
		// ADD 20140428 Hieunm start
		$query->where("ord.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		// ADD 20140428 Hieunm end
		$query->order("ord.order_date_time");

		if ($paginatorData['itemCountPerPage'] > 0) {
			$page     = $paginatorData['currentPage'];
			$rowCount = $paginatorData['itemCountPerPage'];
			$query->limitPage($page, $rowCount);
		}

		$rows = $this->fetchAll($query)->toArray();
		return $rows;
	}


	/**
	 * countProductHistoryByProductId
	 * @param unknown $product_id
	 * @param unknown $idUsername
	 * @return Ambigous <multitype:, array>
	 */
	public function countProductHistoryByProductId($product_id, $idUsername){

		$query = $this->select()->from(array("detailOrder" =>$this->_name), "count(*) as count");
		$query->setIntegrityCheck(false);

		$query->joinInner(array("prod" => Core_Util_TableNames::MST_PRODUCT),
				"prod.product_id = detailOrder.product_id",
				null);

		$query->joinInner(array("ord" => Core_Util_TableNames::ORDER_INFO),
				"ord.order_id = detailOrder.order_id",null);

		$query->where("ord.user_id  = ?",$idUsername);
		$query->where("detailOrder.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		$query->where("prod.delete_flg = 0" );
		$query->where("prod.product_id = ?", $product_id );
		// ADD 20140428 Hieunm start
		$query->where("ord.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		// ADD 20140428 Hieunm end

		$rows = $this->fetchRow($query)->toArray();
		return $rows;
	}

	public function getListOrderDetailByOrderId($orderId) {
		$query = $this->select()->from(array("detailOrder" =>$this->_name));
		$query->setIntegrityCheck(false);
		$query->joinInner(array("product" => Core_Util_TableNames::MST_PRODUCT),
				"detailOrder.product_id = product.product_id");
		$query->joinLeft(array("mc" => Core_Util_TableNames::MST_CLASS),
				"mc.item_type='SUPPLIER' AND mc.item_cd = product.supplier_code",
				array("mc.item_name"));
		$query->where("detailOrder.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		$query->where("detailOrder.order_id = ?", $orderId );
		// ADD 20140428 Hieunm start
		$query->where("product.delete_flg  = ?", Core_Util_Const::DELETE_FLG_0);
		// ADD 20140428 Hieunm end
		$rows = $this->fetchAll($query);
		$data = $this->setRowsToArray($rows);

		return $data;
	}

	public function updatePricetaxQuantity($detaiNo, $orderId , $priceTax, $quantity, $totalPrice) {
		$data = array();
		// kadai 52 => pricetax become price no tax
		
		$data["price"] = $priceTax;
		$data["quantity"] = $quantity;
		// ADD 20140428 Hieunm start
		$data['update_ymd'] = new Zend_Db_Expr('NOW()');
		// ADD 20140428 Hieunm end
		$data['total_price'] = $totalPrice;
		
		$where = array();
		$where["detail_no = ?"] = $detaiNo;
		$where["order_id = ?"] = $orderId;
		// ADD 20140428 Hieunm start
		$where['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140428 Hieunm end
		$num = $this->update($data, $where);
		return $num > 0;
	}

	public function deleteOrderDetail($detaiNo, $orderId) {
		$data = array();
		$data["delete_flg"] = '1';
		// ADD 20140428 Hieunm start
		$data['update_ymd'] = new Zend_Db_Expr('NOW()');
		// ADD 20140428 Hieunm end
		$where = array();
		$where["detail_no = ?"] = $detaiNo;
		$where["order_id = ?"] = $orderId;
		$num = $this->update($data, $where);
		return $num > 0;
	}

}

