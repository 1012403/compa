<?php

class Core_Db_MstMagnificationDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::MST_MAGNIFICATION;

	protected $_primary = array('apply_start_date','product_id');

	protected $_instanceClass = 'Core_Models_MstMagnification';

    public function getAllByProductId($productId,$checkStartDate=false){
		$where = array ();
		$where ['product_id  = ?'] = $productId;
		if($checkStartDate){
		    $where ['apply_start_date  <= ?'] = date('Y/m/d',time());
		}
		$order = "apply_start_date desc";

		$res = $this->getAll($where,$order);

		$res = $this->getAll($where);
		return $res;
	}
	
	/**
	 * getMagnificationPointByProductId
	 * @param unknown $productId
	 */
	public function getMagnificationPointByProductId($productId){
		$innerTableMagnificationStr = "";
		$innerTableMagnificationStr .= " (SELECT max(t.apply_start_date) as apply_start_date, t.product_id ";
		$innerTableMagnificationStr .= " FROM MST_MAGNIFICATION t ";
		$innerTableMagnificationStr .= " WHERE t.apply_start_date <= now() ";
		$innerTableMagnificationStr .= " GROUP BY t.product_id) ";
		$innerMagnification = new Zend_Db_Expr($innerTableMagnificationStr);
	
	
		$query = $this->select()->from(array('mag' =>'MST_MAGNIFICATION'));
		$query->setIntegrityCheck(false);
	
		$query->joinInner(array('tempMagnification' =>$innerMagnification),
				'tempMagnification.apply_start_date = mag.apply_start_date and tempMagnification.product_id = mag.product_id', array('mag.magnification_point'));
	
		$query->where('mag.product_id = ? ', $productId);
	
		$rows = $this->fetchRow($query);
		return $rows['magnification_point'];
	}
	
	public function isExistMaginDate($productId, $startDate) {
		$res = $this->get(array('product_id = ?' => $productId, 'apply_start_date = ?' => $startDate));
		if ($res === null || $res === false) {
			return false;
		} else {
			return true;
		}
	}
}
