<?php
/**
 * @author nhanlt
 *
 */
class Core_Db_QuotationCartInfoDb extends Core_Db_Persistent {
	protected $_name = Core_Util_TableNames::QUOTATION_CART_INFO;
	protected $_primary = array('user_id', 'product_id');
	protected $_instanceClass = 'Core_Models_QuotationCartInfo';

	/**
	 * get QuotationCartInfo For User
	 * @param Core_Models_MstUser $user
	 * @return NULL|Ambigous <multitype:, multitype:unknown >
	 */
	public function getQuotationCartInfoForUser(Core_Models_MstUser $user) {
		if ($user === null) {
			return null;
		}
		
		$userId = $user->getUserId();
		if (Core_Util_Helper::isEmpty($userId)) {
			return null;
		}
		
		$where = array();
		$where['user_id = ?'] = $userId;
		$arrQuotationCart = $this->getAll($where);
		
		return $arrQuotationCart;
		
	}

	public function getQuotationCartInfo($user_id, $product_id) {
		$id['user_id'] = $user_id;
		$id['product_id'] = $product_id;
		$arrQuotationCartInfo = $this->getRecordById($id);
		return $arrQuotationCartInfo;
	}
	
	public function insertData(Core_Models_QuotationCartInfo $quotationcart){
		return $this->insert($quotationcart->toArray());
	}
	
	public function updateData(Core_Models_QuotationCartInfo $quotationcart){
		$data = array();
		$data['quantity']    = new Zend_Db_Expr('quantity + '.$quotationcart->getQuantity());
		$where = array();
		$where['user_id= ?'] = $quotationcart->getUserId();
		$where['product_id= ?']    = $quotationcart->getProductId();
		return $this->update($data, $where);
	}
	
	public function getCountProduct($userid) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("oci"=>$this->_name), array("count"=>"count(*)"))
		->where("oci.user_id = ?", $userid );
		$row = $this->fetchRow( $select )->toArray();
		return $row;
	}
	
	/**
	 * deleteQuotationCart
	 * @param unknown $idProduct
	 * @param unknown $idUser
	 * @return unknown
	 */
	public function deleteQuotationCart($idProduct, $idUser){
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
	public function deleteAllQuotation($idUser){
		$ret = null;
	
		$where['user_id = ?'] = $idUser;
		$ret = $this->delete($where);
		return $ret;
	}
}



