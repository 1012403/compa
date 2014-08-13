<?php
class Core_Db_OrderCartInfoDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::ORDER_CART_INFO;
	protected $_primary = array('user_id', 'product_id');
	protected $_instanceClass = 'Core_Models_OrderCartInfo';
	
	
	/**
	 * deleteOrderCart
	 * @param unknown $idProduct
	 * @param unknown $idUser
	 * @return unknown
	 */
	public function deleteOrderCart($idProduct, $idUser){
		$ret = null;

		$where['product_id = ?'] = $idProduct;
		$where['user_id = ?'] = $idUser;
		$ret = $this->delete($where);
		return $ret;
	}
	
	/**
	 * deleteAllOrder
	 * @param unknown $idUser
	 * @return unknown
	 */
	public function deleteAllOrder($idUser){
		$ret = null;
	
		$where['user_id = ?'] = $idUser;
		$ret = $this->delete($where);
		return $ret;
	}
	
	/**
	 * updateQuantity
	 * @param unknown $idProduct
	 * @param unknown $quantity
	 * @param unknown $idUser
	 * @return boolean
	 */
	public function updateQuantity($idProduct, $quantity, $idUser) {
		$update = array ();
		$update ['quantity'] = $quantity;
		
		$where = array ();
		$where ['product_id = ?'] = $idProduct;
		$where ['user_id = ?'] = $idUser;
		$ret = $this->update ( $update, $where );
		
		return $ret;
	}
}
