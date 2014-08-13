<?php

class Core_Db_MstProductExtraDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::MST_PRODUCT_EXTRA;

	protected $_primary = 'product_id';

	protected $_instanceClass = 'Core_Models_MstProductExtra';

    public function getByProductId($productId){
		$where = array ();
		$where ['product_id  = ?'] = $productId;
		$res = $this->getAll($where, "display_order ASC");
		
		return $res;
	}
	
	public function getByProductIdForAdmin($productId){
		/*$query = $this->select()->from(array("mstcls" => Core_Util_TableNames::MST_CLASS), array("*", "detail_class" => "mstcls.item_cd"))->setIntegrityCheck(false)
		->joinLeft(array("productExtra" => Core_Util_TableNames::MST_PRODUCT_EXTRA), "productExtra.detail_class = mstcls.item_cd")
		->order("mstcls.item_order ASC")
		->where(" productExtra.product_id = ? OR  productExtra.product_id IS NULL ", $productId)
		->where(" mstcls.item_type = ? ", Core_Util_Const::ITEM_TYPE_PRODUCT_DETAIL)
		;*/
		
		
		
		$clsSql = "(SELECT *
			FROM ".Core_Util_TableNames::MST_CLASS." CLS
			WHERE CLS.item_type LIKE '" .Core_Util_Const::ITEM_TYPE_PRODUCT_DETAIL. "')";
		
		$clsEx = new Zend_Db_Expr($clsSql);
		$clsSql2 = "(SELECT * FROM ".Core_Util_TableNames::MST_PRODUCT_EXTRA." pro
				where pro.product_id = '" .$productId. "')";
		$clsEx2 = new Zend_Db_Expr($clsSql2);
		
		$query = $this->select()->from(array("t1" => $clsEx),array('detail_class' => 't1.item_cd', 'display_order' => 't1.item_order'))->setIntegrityCheck(false)
		->joinLeft(array("t2" => $clsEx2),"t1.item_cd = t2.detail_class" , "t2.product_detail_info");
		$query->order("display_order ASC");
		$rows = $this->fetchAll($query);
		$res = $this->setRowsToArray($rows);
		
		return $res;
	}
}
