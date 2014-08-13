<?php
/**
 * @author nhanlt
 *
 */
class Core_Db_OrderCartDb extends Core_Db_Persistent {
	protected $_name = Core_Util_TableNames::ORDER_CART_INFO;
	protected $_primary = array('user_id', 'product_id');
	protected $_instanceClass = 'Core_Models_OrderCartInfo';

	/**
	 * get order cart for user
	 * @param Core_Models_MstUser $user
	 * @return NULL|Ambigous <multitype:, multitype:unknown >
	 */
	public function getOrderCartInfoForUser(Core_Models_MstUser $user) {
		if ($user === null) {
			return null;
		}
		
		$userId = $user->getUserId();
		if (Core_Util_Helper::isEmpty($userId)) {
			return null;
		}
		
		$where = array();
		$where['user_id = ?'] = $userId;
		$arrOrderCart = $this->getAll($where);
		
		return $arrOrderCart;
		
	}

	public function getOrderCartInfo($user_id, $product_id) {
		$id['user_id'] = $user_id;
		$id['product_id'] = $product_id;
		$arrOrderCart = $this->getRecordById($id);
		return $arrOrderCart;
	}
	
	public function insertData(Core_Models_OrderCartInfo $ordercart){
		return $this->insert($ordercart->toArray());
	}
	
	public function updateData(Core_Models_OrderCartInfo $ordercart){
		$data = array();
		$data['quantity']    = new Zend_Db_Expr('quantity + '.$ordercart->getQuantity());
		$where = array();
		$where['user_id= ?'] = $ordercart->getUserId();
		$where['product_id= ?']    = $ordercart->getProductId();
		return $this->update($data, $where);
	}
	
	public function getCountProduct($userid) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("oci"=>$this->_name), array("count"=>"count(*)"))
		->where("oci.user_id = ?", $userid );
		$row = $this->fetchRow( $select )->toArray();
		return $row;
	}
}

