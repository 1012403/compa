<?php

class Core_Db_MstCategoryDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::MST_CATEGORY;

	protected $_primary = 'category_id';

	protected $_instanceClass = 'Core_Models_MstCategory';

	/**
	 *
	 * @param string $parentId
	 * @return getByParentId
	 */
	public function getByParentId($parentId){
	    $query = $this->select();
	    if(isset($parentId)) {
	        $query->where("parent_id = ?", $parentId);
	    } else {
	        $query->where("parent_id is null");
	    }

	    // ADD 20140423 Hieunm start
		$query->where("delete_flg = ?", '0');
		// ADD 20140423 Hieunm end

	    $query->order('display_order ASC');
	    $res = $this->fetchAll($query);

		return $res;
	}

	/**
	 * getByCategoryId
	 * @param unknown $categoryId
	 * @return Ambigous <object, unknown, boolean>
	 */
	public function getByCategoryId($categoryId){
		$where = array ();
		$where ['category_id  = ?'] = $categoryId;
		// ADD 20140423 Hieunm start
		$where ['delete_flg  = ?'] = '0';
		// ADD 20140423 Hieunm end
		//$where['category_id']=$categoryId;
		$res = $this->get($where);
		return $res;
	}

	public function getByCategoryId_($categoryId){
// 		$where = array ();
// 		$where ['category_id'] = $categoryId;
// 		$where ['delete_flg'] = '0';
// 		$res = $this->get($where);
// 		return $res;

		$where = array ();
		$where ['category_id  = ?'] = $categoryId;
		$where ['delete_flg  = ?'] = '0';
		$res = $this->get($where);
		return $res;
	}

	/**
	 * getCategoryParent
	 * @return $resulCat
	 */
	public function getCategoryParent(){
		$query = $this->select();
		$query->where("parent_id is null");
		// ADD 20140423 Hieunm start
		$query->where("delete_flg = ?", '0');
		// ADD 20140423 Hieunm end
		$query->order('display_order ASC');
		$res = $this->fetchAll($query)->toArray();
		return $res;
	}

	/**
	 * getCategoryByIdParent
	 * @param unknown $idParent
	 * @return multitype:
	 */
	public function getCategoryByIdParent($idParent){
		$query = $this->select();
		//$where ['parent_id  = ?'] = $idParent;
		$query->where('parent_id= ? ',$idParent);
		// ADD 20140423 Hieunm start
		$query->where("delete_flg = ?", '0');
		// ADD 20140423 Hieunm end
		$res = $this->fetchAll($query)->toArray();
		return $res;
	}

	/**
	 *
	 * @param string $idCategory
	 * @return getProductHistory
	 */
	public function getCategoryById($idCategory) {
		// MOD 20140424 Hieunm start
		//$category = $this->getRecordById ( $idCategory );
		//return $category;
		$where = array ();
		$where ['category_id  = ?'] = $idCategory;
		$where ['delete_flg  = ?'] = '0';
		$res = $this->get($where);
		return $res;
		// MOD 20140424 Hieunm end
	}

	/**
	 *
	 * @param string $idCategory
	 * @return getProductHistory
	 */
// 	public function getListCategory($arrProductId, $orderBy = null) {
// 		$select = $this->select ()->setIntegrityCheck ( false )->from ( array (
// 				"Child" => $this->_name
// 		), array (
// 				"Child.category_id" => "Child.category_id",
// 				"Child.category_name" => "Child.category_name",
// 				"Child.parent_id" => "Child.parent_id",
// 				"Child.display_order" => "Child.display_order",
// 				"Child.display_flg" => "Child.display_flg"
// 		) );

// 		$select = $this->innerJoinParrent ( $select );

// 		$subSelect = $this->select()->setIntegrityCheck ( false )->from(array("Prod"=>Core_Util_TableNames::PRODUCT_CATEGORY_JOIN), "category_id")
// 		->where("Prod.product_id IN (?) ", $arrProductId);

// 		$select->where('Child.category_id IN (?) ',$subSelect);

// 		// ADD 20140423 Hieunm start
// 		$select->where("Child.delete_flg = ?", '0');
// 		// ADD 20140423 Hieunm end

// 		$select->order ("Parent.display_order");
// 		$select->order ("Child.display_order");
// 		$select->order ( $orderBy );
// 		$rows = $this->fetchAll ( $select );
// 		return $rows;
// 	}
	
	// Add 20140613 Hungnh START
	public function getListCategory($keyword = null, $arrConditions = null, $tylelist = null, $orderBy = null) {
		
		$strSqlGetListCat = "( SELECT DISTINCT ";
		$strSqlGetListCat .= "mp.product_id ";
		$strSqlGetListCat .= "FROM " .Core_Util_TableNames::MST_PRODUCT ." AS mp ";
		
		$user = Core_Util_Helper::getLoginUser();
		
		if ($tylelist != null && $user != null){
			if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_FEATURED_PRODUCTS) == 0){

				$strSqlGetListCat .= "INNER JOIN " .Core_Util_TableNames::FEATURED_PRODUCT ." AS fp ON fp.product_id = mp.product_id ";
				$strSqlGetListCat .= "INNER JOIN " .Core_Util_TableNames::MST_USER ." AS mu ";
				$strSqlGetListCat .= "ON " .$this->_db->quoteInto("mu.sales_id = fp.user_id and mu.user_id = ?", $user->getUserId());
				
			} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS) == 0){
				
				$strSqlGetListCat .= "INNER JOIN " .Core_Util_TableNames::ORDER_DETAIL_INFO ." AS odi ";
				$strSqlGetListCat .= "ON odi.product_id = mp.product_id and odi.delete_flg = 0 ";
				
				$strSqlGetListCat .= "INNER JOIN " .Core_Util_TableNames::USER_FAVORITES_PRODUCT ." AS ufp ";
				$strSqlGetListCat .= "ON " .$this->_db->quoteInto("ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId());
				
			} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_WISH_LIST) == 0){

				$strSqlGetListCat .= "INNER JOIN " .Core_Util_TableNames::USER_FAVORITES_PRODUCT ." AS ufp ";
				$strSqlGetListCat .= "ON " .$this->_db->quoteInto("ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId());
				
			} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_HISTORY_PRODUCTS) == 0){

				$strSqlGetListCat .= "INNER JOIN " .Core_Util_TableNames::ORDER_DETAIL_INFO ." AS odi ";
				$strSqlGetListCat .= "ON odi.product_id = mp.product_id and odi.delete_flg='0' ";
				
				$strSqlGetListCat .= "INNER JOIN " .Core_Util_TableNames::ORDER_INFO ." AS oi ";
				$strSqlGetListCat .= "ON " .$this->_db->quoteInto("oi.order_id = odi.order_id and oi.delete_flg = ? ",Core_Util_Const::DELETE_FLG_0)
								.$this->_db->quoteInto(" AND oi.order_status = ? ", Core_Util_Const::ORDER_STATUS_3)
								.$this->_db->quoteInto(" AND oi.user_id = ? ", $user->getUserId());
			}
		}
		
		if (isset($arrConditions) && count($arrConditions) > 0) {
			$strSqlGetListCat .= "INNER JOIN " .Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ." AS Cate ON Cate.product_id = mp.product_id ";
			$strSqlGetListCat .= $this->_db->quoteInto("AND Cate.category_id IN (?) ", $arrConditions);
		}
		
		$strSqlGetListCat .= "WHERE ";
		$strSqlGetListCat .= "mp.delete_flg = 0 ";
		
		if (isset($keyword) && !empty($keyword)) {
			$strSqlGetListCat .= $this->_db->quoteInto("AND (mp.product_name LIKE ?) ", "%$keyword%");
		}
		
		if (isset($arrConditions) && count($arrConditions) > 0) {
			$strSqlGetListCat .= "GROUP BY ";
			$strSqlGetListCat .= "`Cate`.`product_id` ";
			$strSqlGetListCat .= "HAVING ";
			$strSqlGetListCat .= $this->_db->quoteInto("COUNT(DISTINCT Cate.category_id) >= ? ", count($arrConditions));
		}
		
		$strSqlGetListCat .= ") ";

		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("Child"=> Core_Util_TableNames::MST_CATEGORY), 
				array(
						"Child.category_id" => "Child.category_id",
						"Child.category_name" => "Child.category_name",
						"Child.parent_id" => "Child.parent_id",
						"Child.display_order" => "Child.display_order",
						"Child.display_flg" => "Child.display_flg"
				)
		);
		$select->joinInner(array("Parent"=> Core_Util_TableNames::MST_CATEGORY), 
				"Child.parent_id = Parent.category_id AND Parent.parent_id IS NULL AND Parent.delete_flg = '0'",
				array(
						"Parent.category_id" => "Parent.category_id",
						"Parent.category_name" => "Parent.category_name",
						"Parent.parent_id" => "Parent.parent_id",
						"Parent.display_order" => "Parent.display_order",
						"Parent.display_flg" => "Parent.display_flg"
				)
		);
		
		$select->joinInner(array("pcj"=> Core_Util_TableNames::PRODUCT_CATEGORY_JOIN),
				"Child.category_id = pcj.category_id",
				null);
		
		$select->joinInner(array("prod_Ids"=> new Zend_Db_Expr($strSqlGetListCat)),
				"pcj.product_id = prod_Ids.product_id",
				null);
		$select->where("Child.delete_flg = '0'");
		$select->order("Parent.display_order");
		$select->order("Child.display_order");
		$select->order($orderBy);
		
		$rows = $this->fetchAll ( $select );
		return $rows;
	}
	//Add 20140613 Hungnh END

	private function innerJoinParrent($select) {
		$select->joinInner (array("Parent" => $this->_name ),
				"Child.parent_id = Parent.category_id AND Parent.parent_id is null"  ,
				array("Parent.category_id" => "Parent.category_id",
						"Parent.category_name" => "Parent.category_name",
						"Parent.parent_id" => "Parent.parent_id",
						"Parent.display_order" => "Parent.display_order",
						"Parent.display_flg" => "Parent.display_flg" ));

		return $select;
	}

	public function queryProductIds($keyword = null, $arrConditions = null, $tylelist = null) {

		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("mp"=>Core_Util_TableNames::MST_PRODUCT), "product_id");

		$user = Core_Util_Helper::getLoginUser();

		if ($tylelist != null && $user != null){
			if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_FEATURED_PRODUCTS) == 0){
				$select = $select->joinInner (array("fp" => Core_Util_TableNames::FEATURED_PRODUCT ),
						"fp.product_id = mp.product_id"  ,null)
						->joinInner (array("mu" => Core_Util_TableNames::MST_USER ),
						"mu.sales_id = fp.user_id"  ,null)
						->where("mu.user_id = ?", $user->getUserId());
			} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS) == 0){
				$select = $select->joinInner (array("odi" => Core_Util_TableNames::ORDER_DETAIL_INFO ),
						"odi.product_id = mp.product_id"  ,null);
			} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_WISH_LIST) == 0){
				$select = $select->joinInner (array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT ),
						"ufp.product_id = mp.product_id"  ,null)
						->where("mu.user_id = ?", $user->getUserId());
			}
		}

		$select = $select->where("delete_flg = 0" )
		->where("product_name like ?", "%$keyword%" );

		$rows = $this->fetchAll ( $select )->toArray();
		return $rows;
	}

	/**
	 * insertCatParent
	 * @param unknown $idParent
	 */
	public function insertCategory($dataInput){
		/* @var $data Core_Models_MstCategory */
		$data= new Core_Models_MstCategory($dataInput);
		$parentId = $data->getParentId();
		if(trim($parentId) == ""){
			$parentId = null;
			$data->setParentId(null);
		}
		$dislay = $this->getMaxDisplayOrder($parentId);
		$data->setDisplayOrder($dislay+1);

		// MOD 20140423 Hieunm start
		//$result = $this->insert($data);
		$data->setDeleteFlg('0');
		$data->setDisplayFlg('1');
		$result = $this->insertRecord($data);
		// MOD 20140423 Hieunm end

		return $result;
	}
	/**
	 * updateCategory
	 * @param unknown $id
	 * @param unknown $formData
	 * @param string $idParent
	 * @return number
	 */
	public function updateCategory($id, $formData){
		// MOD 20140423 Hieunm start
		//$where=("category_id=".$id);
		$where=("category_id= " . $id . " and delete_flg = " . '0');
		$formData["update_ymd"] = new Zend_Db_Expr('NOW()');
		$formData["update_user_id"] = Core_Util_Helper::getIdUserLogin();
		// MOD 20140423 Hieunm end
		$result = $this->update($formData, $where);
		return $result;
	}

	/**
	 * deleteCategory
	 * @param unknown $d
	 */
	public function deleteCategory($id){
		//if (true){//!($this->isCatProdJoin($id))
			// MOD 20140423 Hieunm start
			//$where='category_id= '.$id;
			//$row=$this->delete($where);
			//return $row;
			$where=("category_id= " . $id);
			$arr["delete_flg"] = '1';
			$arr["update_ymd"] = new Zend_Db_Expr('NOW()');
			$arr["update_user_id"] = Core_Util_Helper::getIdUserLogin();
			$result = $this->update($arr, $where);
			return $result;
			// MOD 20140423 Hieunm end
		//}else
		//	return false;

	}

	public function deleteCategory_($id,$displayorder){
		//if (true){//!($this->isCatProdJoin($id))
			$resul= $this->updateDislayOrder($displayorder);
			$where='category_id= '.$id;
			$row=$this->delete($where);
			return $row;
		//}else
		//	return false;

	}
	/**
	 * deleteChildOfPar
	 * @param unknown $id
	 * @return boolean
	 */
	public function deleteChildOfPar($id){
		// MOD 20140424 Hieunm start
		//$where='parent_id= '.$id;
		//$row=$this->delete($where);
		//return true;
		$where=("parent_id= " . $id);
		$arr["delete_flg"] = '1';
		$arr["update_ymd"] = new Zend_Db_Expr('NOW()');
		$arr["update_user_id"] = Core_Util_Helper::getIdUserLogin();
		$result = $this->update($arr, $where);
		return $result;
		// MOD 20140424 Hieunm end
	}


	/**
	 * updateDislay
	 * @param unknown $displayorder
	 * @param string $idpar
	 * @return number
	 */
	public function updateDislay($displayorder, $idpar=null){
		if($idpar==null){
			$where=' parent_id is NULL and display_order > '.$displayorder;
		}else {
			$db = $this->getAdapter();
			$where = $db->quoteInto('parent_id = ?', $idpar)
			. $db->quoteInto(' AND parent_id > ?', $displayorder);
		}

		$arr=array('display_order'=> new Zend_Db_Expr('display_order-1'));
		// MOD 20140423 Hieunm start
		$where .= " and delete_flg = " . '0';
		$arr["update_user_id"] = Core_Util_Helper::getIdUserLogin();
		$arr["update_ymd"] = new Zend_Db_Expr('NOW()');
		// MOD 20140423 Hieunm end
		$row=$this->update($arr, $where);
		return true;
	}



	/**
	 * isCatProdJoin
	 * @param unknown $idCat
	 * @return boolean
	 */
	function isCatProdJoin($idCat){
		$select=$this->select()->setIntegrityCheck ( false )
			->from(array("Prod"=>Core_Util_TableNames::PRODUCT_CATEGORY_JOIN));
		$data=$this->fetchAll($select);
		foreach ($data as $value){
			if ($idCat==$value['category_id']){
				return true;
			}
		}
		return false;
	}


	/**
	 * getMaxId
	 * @return Ambigous <number, string, unknown>
	 */
	public function getMaxId() {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("cat"=>$this->_name), array("max"=>"max(category_id)"));
		// ADD 20140423 Hieunm start
		$select->where('cat.delete_flg = 0');
		// ADD 20140423 Hieunm end
		$rows = $this->fetchRow( $select );
		$id = Core_Util_Helper::getDataRow($rows, 'max');
		return Core_Util_Helper::nullToZero($id);
	}
	/**
	 * getMaxDisplayOrderPar
	 * @return Ambigous <number, string, unknown>
	 */
	public function getMaxDisplayOrder($idParent=null){
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("cat"=>$this->_name), array("max"=>"max(display_order)"));
		if($idParent == null){
			$select->where('cat.parent_id is null');
		}else{
			$select->where('cat.parent_id is not null');
			$select->where('cat.parent_id=?', $idParent);
		}
		// ADD 20140424 Hieunm start
		$select->where('cat.delete_flg = 0');
		// ADD 20140424 Hieunm end
		$rows = $this->fetchRow( $select );
		$id = Core_Util_Helper::getDataRow($rows, 'max');
		return Core_Util_Helper::nullToZero($id);
	}

	public function getMinDisplayOrder($idParent){
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("cat"=>$this->_name), array("min"=>"min(display_order)"));
		if($idParent == null){
			$select->where('cat.parent_id is null');
		}else{
			$select->where('cat.parent_id is not null');
			$select->where('cat.parent_id=?', $idParent);
		}
		// ADD 20140424 Hieunm start
		$select->where('cat.delete_flg = 0');
		// ADD 20140424 Hieunm end
		$rows = $this->fetchRow( $select );
		$id = Core_Util_Helper::getDataRow($rows, 'min');
		return Core_Util_Helper::nullToZero($id);
	}
	
	/**
	 * getCategoryNameParentAndChild
	 * @return Ambigous <multitype:, array>
	 */
	public function getCategoryNameParentAndChild(){
		$select = $this->select()->setIntegrityCheck ( false )
				->from(array("catpar"=>$this->_name),array("category_name as parent_name"));
		
		$select->join(array("catchild" => $this->_name),
				"catpar.category_id = catchild.parent_id " ,array("*"));
		
		$select->where('catpar.delete_flg = ?',Core_Util_Const::DELETE_FLG_0);
		$select->where('catchild.delete_flg = ?',Core_Util_Const::DELETE_FLG_0);
		
		$select->order("catpar.display_order");
		$select->order("catchild.display_order");
		$rows = $this->fetchAll( $select );
		return $rows;
	}
	
	
	/**
	 * checkNameAndParentId
	 * @param unknown $categoryName
	 * @param unknown $parentId
	 * @return Ambigous <number, unknown>
	 */
	public function checkNameAndParentId($parentId, $categoryName){
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("cat"=>$this->_name), array("count"=>"count(*)"));
		
		$select->where('cat.parent_id = ?', $parentId);
		
		$select->where('trim(cat.category_name) collate utf8_unicode_ci = ?', $categoryName);
		$rows = $this->fetchRow( $select );
		$count = Core_Util_Helper::getDataRow($rows, 'count');
		return Core_Util_Helper::nullToZero($count);
	}
	
	/**
	 * getParentIdByParentName
	 * @param string $categoryNameParent
	 * @return Ambigous <number, unknown>
	 */
	public function getParentIdByParentName($categoryNameParent){
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("cat"=>$this->_name),array("idCategory"=>"cat.category_id"));
		
		$select->where('cat.parent_id is null');
		$select->where('trim(cat.category_name) collate utf8_unicode_ci = ?', $categoryNameParent);
		$select->where('cat.delete_flg  = ?', Core_Util_Const::DELETE_FLG_0);
		$rows = $this->fetchRow( $select );
		$idCategory = Core_Util_Helper::getDataRow($rows, 'idCategory');
		return Core_Util_Helper::nullToZero($idCategory);
		
	}
	
	/**
	 * insertNewParentIdByName
	 * @param string $categoryNameParent
	 * @return Ambigous <id, mixed, multitype:>
	 */
	public function insertNewParentIdByName($categoryNameParent){
		$dataCategory = new Core_Models_MstCategory();
		
		$parentId = null;
		$dislay = $this->getMaxDisplayOrder($parentId);
		
		$dataCategory->setCategoryName($categoryNameParent);
		$dataCategory->setDisplayOrder($dislay+1);
		$dataCategory->setParentId(null);
		$dataCategory->setDeleteFlg( Core_Util_Const::DELETE_FLG_0 );
		$dataCategory->setDisplayFlg('1');
		$result = $this->insertRecord($dataCategory);
		
		return $result;
		
	}
	
	/**
	 * insertNewChildCategory
	 * @param int $idParent
	 * @param string $categoryNameChild
	 * @return Ambigous <id, mixed, multitype:>
	 */
	public function insertNewChildCategory($idParent, $categoryNameChild){
		$dataCategory = new Core_Models_MstCategory();
		
		$dislay = $this->getMaxDisplayOrder($idParent);
		
		$dataCategory->setCategoryName($categoryNameChild);
		$dataCategory->setDisplayOrder($dislay+1);
		$dataCategory->setParentId($idParent);
		$dataCategory->setDeleteFlg( Core_Util_Const::DELETE_FLG_0 );
		$dataCategory->setDisplayFlg('1');
		$result = $this->insertRecord($dataCategory);
		return $result;
	}
	
	/**
	 * 
	 * @param unknown $categoryName
	 * @return Core_Models_MstCategory
	 */
	public function getCategoryByName($categoryName) {
// 		$category = $this->get(array("category_name LIKE ?" => $categoryName));
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("cat"=>$this->_name),array("*"))
		->where("category_name = ?", $categoryName)
		->where("delete_flg = '0'")
		->order("category_id")
		->limitPage(0, 1);
		$category = $this->fetchAll( $select );
		return $category;
	}
	
	/**
	 * 
	 * @param unknown $parentId
	 * @param unknown $categoryName
	 * @return Core_Models_MstCategory
	 */
	public function getCategoryByParentIdAndName($parentId, $categoryName) {
		//$category = $this->get(array("parent_id = ?" => $parentId, "category_name LIKE ?" => $categoryName));
		//return $category;
		
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("cat"=>$this->_name),array("*"))
		->where("delete_flg = '0'")
		->where("parent_id = ?", $parentId)
		->where("category_name = ?", $categoryName)
		->order("category_id")
		->limitPage(0, 1);
		$category = $this->fetchAll( $select );
		return $category;
	}


}