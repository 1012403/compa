<?php
class Core_Db_UserProductJoinDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::USER_PRODUCT_JOIN;

	protected $_primary = array('user_id','product_id');

	protected $_instanceClass = 'Core_Models_UserProductJoin';

	/**
	 * 
	 * @param string $userId
	 * @param string $productId
	 * @return Core_Models_UserProductJoin
	 */
	public function getByUserIdProductId($userId,$productId){
	    $where = array ();
	    $where ['user_id  = ?'] = $userId;
		$where ['product_id  = ?'] = $productId;
		$order = "valid_until_date DESC";
		$res = $this->getAll($where, $order);
		if (count($res) > 0) {
			return $res[0];
		}
		return null;
	}

	public function queryAll($paginatorData, $user_name=null, $login_username=null, $product_name=null) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("upj"=>$this->_name), array(
				"product_id", 
				"user_id", 
				//"price_including_tax", 
				"price",
				"valid_until_date", 
				//"price_including_tax_format"=>"FORMAT(upj.price_including_tax,0)"
				"price_format"=>"FORMAT(upj.price,0)"
		));
	
		$select->joinLeft(array("mu" => Core_Util_TableNames::MST_USER),
				"upj.user_id = mu.user_id and mu.delete_flg = 0",
				array("user_name", "login_username"));
	
		$select->joinLeft(array("mp" => Core_Util_TableNames::MST_PRODUCT),
				"upj.product_id = mp.product_id and mp.delete_flg = 0",
				array("image_path", "product_name", "product_no"));

		$selectprice = $this->select()->setIntegrityCheck ( false )
		->from(array("mppc"=>Core_Util_TableNames::MST_PRODUCT_PRICE), array("MAX(mppc.apply_start_date)"))
		->where("mppc.Product_id = mp.Product_id and mppc.apply_start_date <= current_date");
				
		$select->joinLeft(array("mpp" => Core_Util_TableNames::MST_PRODUCT_PRICE),
				$this->_db->quoteInto(
					"mpp.product_id = mp.product_id and mpp.apply_start_date <= current_date and mpp.apply_start_date = (?) ", $selectprice
				), array(
						//"mpp_price_including_tax"=>"price_including_tax"
						"mpp_price"=>"FORMAT(mpp.price,0)"
				));
		
		if ($user_name != null){
			$select->where("mu.user_name like ?", "%$user_name%" );
		}
		if ($login_username != null){
			$select->where("mu.login_username = ?", $login_username );
		}
		if ($product_name != null){
			$select->where("mp.product_name like ?", "%$product_name%" );
		}

		$select->order("mu.user_name");
		$select->order("mp.product_no");
	
		if ($paginatorData['itemCountPerPage'] > 0) {
			$page     = $paginatorData['currentPage'];
			$rowCount = $paginatorData['itemCountPerPage'];
			$select->limitPage($page, $rowCount);
		}
		
		$rows = $this->fetchAll ( $select )->toArray();
		return $rows;
	}
	
	/**
	 * queryAllExport
	 * @param string $user_name
	 * @param string $login_username
	 * @param string $product_name
	 * @return multitype:
	 */
	public function queryAllExport( $user_name=null, $login_username=null, $product_name=null) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("upj"=>$this->_name), array(
				"product_id",
				"user_id",
				"price",
				"valid_until_date",
				"price_format"=>"FORMAT(upj.price,0)"
		));
	
		$select->joinLeft(array("mu" => Core_Util_TableNames::MST_USER),
				"upj.user_id = mu.user_id and mu.delete_flg = 0",
				array("user_name", "login_username"));
	
		$select->joinLeft(array("mp" => Core_Util_TableNames::MST_PRODUCT),
				"upj.product_id = mp.product_id and mp.delete_flg = 0",
				array("product_name", "product_no"));
	/* 
		$selectprice = $this->select()->setIntegrityCheck ( false )
		->from(array("mppc"=>Core_Util_TableNames::MST_PRODUCT_PRICE), array("MAX(mppc.apply_start_date)"))
		->where("mppc.Product_id = mp.Product_id and mppc.apply_start_date <= current_date");
	
		$select->joinLeft(array("mpp" => Core_Util_TableNames::MST_PRODUCT_PRICE),
				$this->_db->quoteInto(
						"mpp.product_id = mp.product_id and mpp.apply_start_date <= current_date and mpp.apply_start_date = (?) ", $selectprice
				), array(
						//"mpp_price_including_tax"=>"price_including_tax"
						"mpp_price"=>"price"
				));
	 */
		if ($user_name != null){
			$select->where("mu.user_name like ?", "%$user_name%" );
		}
		if ($login_username != null){
			$select->where("mu.login_username = ?", $login_username );
		}
		if ($product_name != null){
			$select->where("mp.product_name like ?", "%$product_name%" );
		}
	
		$select->order("mu.user_name");
		$select->order("mp.product_no");
	
		$rows = $this->fetchAll ( $select )->toArray();
		return $rows;
	}
	
	
	public function queryCountAll($user_name=null, $login_username=null, $product_name=null) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("upj"=>$this->_name), array("count"=>"count(*)"));
	
		$select->joinLeft(array("mu" => Core_Util_TableNames::MST_USER),
				"upj.user_id = mu.user_id and mu.delete_flg = 0",
				null);
	
		$select->joinLeft(array("mp" => Core_Util_TableNames::MST_PRODUCT),
				"upj.product_id = mp.product_id and mp.delete_flg = 0",
				null);
		
		if ($user_name != null){
			$select->where("mu.user_name like ?", "%$user_name%" );
		}
		if ($login_username != null){
			$select->where("mu.login_username = ?", $login_username );
		}
		if ($product_name != null){
			$select->where("mp.product_name like ?", "%$product_name%" );
		}
		
		$rows = $this->fetchRow( $select );
		return $rows;
	}
	
	public function getUserProduct(){
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("upj"=>$this->_name), array("product_id","user_id",
				"price","valid_until_date","price_format"=>"FORMAT(upj.price,0)"
		));
		
		$select->joinLeft(array("mu" => Core_Util_TableNames::MST_USER),
				"upj.user_id = mu.user_id and mu.delete_flg = 0",
				array("user_name", "login_username"));
		
		$select->joinLeft(array("mp" => Core_Util_TableNames::MST_PRODUCT),
				"upj.product_id = mp.product_id and mp.delete_flg = 0",
				array( "product_name", "product_no"));

		$rows = $this->fetchAll ( $select )->toArray();
		return $rows;
	}
	
	/*public function checkExistUserProdDate($idUser, $idProd, $date) {
		//Core_Util_LocalLog::writeLog("check user prod date:  ".$date);
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("upj"=>$this->_name), array("user_id"));
		
		$select->where ('user_id  = ?',$idUser);
		$select->where ('product_id = ?',$idProd);
		$select->where ('valid_until_date = ?',$date);
		$rows = $this->fetchAll($select)->toArray();
		if ($rows !== NULL ){
			return $rows;
		}
		return null;
	}
	*/
	
	public function  checkExistUserProdDate($idUser, $idProd, $date) {
		$res = $this->get(array(
			'user_id = ?' => $idUser,
			'product_id = ?' => $idProd,
			'valid_until_date = ?' => $date
		));
		if ($res === false || $res === null) {
			return false;
		} else {
			return true;
		}
	}
	
}