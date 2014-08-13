<?php

class Core_Db_ProductCategoryJoinDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::PRODUCT_CATEGORY_JOIN;

	protected $_primary = array('category_id','product_id');

	protected $_instanceClass = 'Core_Models_ProductCategoryJoin';

    public function getAllByProductId($productId){
		$where = array ();
		$where ['product_id  = ?'] = $productId;
		$res = $this->getAll($where);
		return $res;
	}
}
