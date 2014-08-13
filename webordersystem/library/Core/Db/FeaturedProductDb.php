<?php
/**
 * 
 * @author nhanlt
 *
 */
class Core_Db_FeaturedProductDb extends Core_Db_Persistent {
	protected $_name = Core_Util_TableNames::FEATURED_PRODUCT;
	protected $_primary = 'user_id';
	protected $_instanceClass = 'Core_Models_FeaturedProduct';

	public function isAddedFeatured($productId, $idUser) {
		$obj = $this->get(array("user_id = ? " => $idUser, 'product_id = ? ' => $productId));
		return $obj !== null && $obj !== false;
	}
}
