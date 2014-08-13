<?php

class Core_Db_MstProductPriceDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::MST_PRODUCT_PRICE;

	protected $_primary = array('apply_start_date','product_id');

	protected $_instanceClass = 'Core_Models_MstProductPrice';

	public function getAllByProductId($productId,$checkStartDate=false){
		$where = array ();
		$where ['product_id  = ?'] = $productId;
		if($checkStartDate){
		    $where ['apply_start_date  <= ?'] = date('Y-m-d',time());
		}
		$order = "apply_start_date desc";

		$res = $this->getAll($where,$order);

		return $res;
	}
	
	public function isExistPriceDate($productId, $startDate) {
		$res = $this->get(array('product_id = ?' => $productId, 'apply_start_date = ?' => $startDate));
		if ($res === null || $res === false) {
			return false;
		} else {
			return true;
		}
	}
}
