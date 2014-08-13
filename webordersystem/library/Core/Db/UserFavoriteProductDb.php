<?php
class Core_Db_UserFavoriteProductDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::USER_FAVORITES_PRODUCT;

	protected $_primary = array('user_id', 'product_id');
	
	protected $_instanceClass = 'Core_Models_UserFavoriteProduct';


	public function updateLike($user_id, $product_id, $tyle){
		$data = array();
		if ($tyle == 0){
			$data['user_id'] = $user_id;
			$data['product_id']    = $product_id;
			return $this->insert($data);
		} else {
			$data['user_id = ?'] = $user_id;
			$data['product_id = ?']    = $product_id;
			return $this->delete($data);
		}
	}
}












