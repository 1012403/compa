<?php
/**
 *
 * @author Nguyen
 *
 */
class Core_Db_MstProductDb extends Core_Db_Persistent {

  protected $_name = Core_Util_TableNames::MST_PRODUCT;

  protected $_primary = 'product_id';

  protected $_instanceClass = 'Core_Models_MstProduct';

  /**
   *
   * @param string $idUsername
   * @return Core_Models_MstProduct
   */
  public function getProductById($idProduct){
    $where = array ();
    $where ['product_id  = ?'] = $idProduct;
    $where ['delete_flg  = ?'] = Core_Util_Const::DELETE_FLG_0;
    $arrProduct = $this->get ( $where );
    return $arrProduct;
  }

  public function getAllProduct($condition, $order,  $page, $count = null) {
    $offset = Core_Util_Helper::getOffset($page, $count);
    if ($count === null) {
      $count = Core_Util_Const::NUMBER_ITEMS_PAGE;
    }
    $arr = $this->getAll($condition, $order, $offset, $count);
    return $arr;
  }



  public function queryProductIds($keyword = null, $arrConditions = null, $tylelist = null, $user = null) {

    $select = $this->select()->setIntegrityCheck ( false )->distinct()
    ->from(array("mp"=>$this->_name), $this->_primary);

    if ($user == null){
    	$user = Core_Util_Helper::getLoginUser();	
    }

    if ($tylelist != null && $user != null){
      if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_FEATURED_PRODUCTS) == 0){
        $select = $select->joinInner (array("fp" => Core_Util_TableNames::FEATURED_PRODUCT ),
            "fp.product_id = mp.product_id"  ,null)
            ->joinInner (array("mu" => Core_Util_TableNames::MST_USER ),
                $this->_db->quoteInto(
                  "mu.sales_id = fp.user_id and mu.user_id = ?", $user->getUserId()
                ), null);
      } else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS) == 0){
        $select = $select->joinInner (array("odi" => Core_Util_TableNames::ORDER_DETAIL_INFO ),
            "odi.product_id = mp.product_id and odi.delete_flg = 0"  ,null);
        $select = $select->joinInner (array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT ),
          $this->_db->quoteInto(
              "ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId()
          ),null);
      } else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_WISH_LIST) == 0){
        $select = $select->joinInner (array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT ),
          $this->_db->quoteInto(
              "ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId()
          ),null);
      } else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_HISTORY_PRODUCTS) == 0){
        $select = $select->joinInner (array("odi" => Core_Util_TableNames::ORDER_DETAIL_INFO ),
            "odi.product_id = mp.product_id and odi.delete_flg='0'"  ,null)
            ->joinInner (array("oi" => Core_Util_TableNames::ORDER_INFO ),
              $this->_db->quoteInto("oi.order_id = odi.order_id and oi.delete_flg = ? ",Core_Util_Const::DELETE_FLG_0).
              $this->_db->quoteInto( "and oi.order_status = ? ", Core_Util_Const::ORDER_STATUS_3).
              $this->_db->quoteInto(" and oi.user_id = ? ", $user->getUserId()), null);
      }
    }
    if ($arrConditions != null && is_array($arrConditions)){
      $select->joinInner(array("Cate" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ),
            $this->_db->quoteInto(
              "Cate.product_id = mp.product_id and Cate.category_id IN (?)", $arrConditions
            ), null)
            ->group("Cate.product_id")
            ->having("COUNT(DISTINCT Cate.category_id) >= ?", count($arrConditions));
    }
    //add 20140724 locpht start
    $select->joinLeft (array("mpp" => Core_Util_TableNames::MST_PRODUCT_PRICE ),
          "mpp.product_id = mp.product_id
          AND mpp.apply_start_date = (SELECT MAX(mppc.apply_start_date) AS apply_start_date
          FROM  " .Core_Util_TableNames::MST_PRODUCT_PRICE ." AS mppc
          WHERE mppc.product_id = mp.product_id
          AND mppc.apply_start_date <= current_date
          ) ");
    //add 20140724 locpht end

    $select = $select->where("mp.delete_flg = 0" )
    ->where("mp.product_name like '". "%$keyword%'") 
    //add 20140724 locpht start
    ->where("((mpp.price_condition_class <> '1') OR (mpp.price_condition_class ='1'
    		and exists ( select * from USER_PRODUCT_JOIN AS upj1 
    		WHERE upj1.user_id = '".$user->getUserId()."' AND upj1.product_id = mpp.product_id)))");
	//add 20140724 locpht end
    $rows = $this->fetchAll ( $select )->toArray();
    return $rows;
  }

  //Edit 20140613 Hungnh START
// 	public function queryProducts($paginatorData, $keyword = null, $arrConditions = null, $tylelist = null) {
// 		$user = Core_Util_Helper::getLoginUser();

// 		$select = $this->select()->setIntegrityCheck ( false )->distinct()
// 		->from(array("mp"=>$this->_name), array("*"));
// 		if ($tylelist != null && $user != null && (strcmp($tylelist, Core_Util_Const::TYLE_LIST_WISH_LIST) == 0 ||
// 		strcmp($tylelist, Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS) == 0)){
// 			$select->joinInner (array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT ),
// 					$this->_db->quoteInto(
// 							"ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId()
// 					)  ,array("like"=>"ufp.product_id"));
// 		} else {
// 			$select->joinLeft (array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT ),
// 					$this->_db->quoteInto(
// 							"ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId()
// 					),array("like"=>"ufp.product_id"));
// 		}
// 		$selectprice = $this->select()->setIntegrityCheck ( false )
// 						->from(array("mppc"=>Core_Util_TableNames::MST_PRODUCT_PRICE), array("MAX(mppc.apply_start_date)"))
// 						->where("mppc.Product_id = mp.Product_id and mppc.apply_start_date <= current_date");
// 		$select->joinLeft (array("mpp" => Core_Util_TableNames::MST_PRODUCT_PRICE ),
// 						$this->_db->quoteInto(
// 							"mpp.product_id = mp.product_id and mpp.apply_start_date <= current_date and mpp.apply_start_date = (?) ", $selectprice
// 						), array("price"=>"FORMAT(mpp.price, 0)"));

// 		$select->joinLeft (array("qdi" => Core_Util_TableNames::QUOTATION_DETAIL_INFO ),
// 						"qdi.product_id = mp.product_id and qdi.delete_flg = 0"  ,null);

// 		// MOD 20140416 Hieunm start
// 		/*$select->joinLeft (array("qi" => Core_Util_TableNames::QUOTATION_INFO ),
// 				$this->_db->quoteInto(
// 					"qi.quotation_id = qdi.quotation_id and qi.delete_flg = 0 and qi.user_id = ?",$user->getUserId()
// 				) , array("qi.quotation_id"));*/
// 		$select->joinLeft (array("qi" => Core_Util_TableNames::QUOTATION_INFO ),
// 				$this->_db->quoteInto(
// 					"qi.quotation_id = qdi.quotation_id and qi.delete_flg = 0 and qi.user_id = ?",$user->getUserId()
// 				) , array("quotation_id" => "MAX(qi.quotation_id)",
// 						  "status" => "(select status FROM " . Core_Util_TableNames::QUOTATION_INFO
// 											. " WHERE user_id = '" . $user->getUserId()
// 											. "' AND quotation_id = MAX(qi.quotation_id))"));
// 		// MOD 20140416 Hieunm end

// 		// MOD 20140416 Hieunm start
// 		/*$select->joinLeft (array("upj" => Core_Util_TableNames::USER_PRODUCT_JOIN ),
// 						$this->_db->quoteInto(
// 							"upj.product_id = mp.product_id and upj.user_id = ? and upj.valid_until_date >= current_date",$user->getUserId()
// 						), array("price1"=>"FORMAT(upj.price,0)"));*/
// 		$select->joinLeft (array("upj" => Core_Util_TableNames::USER_PRODUCT_JOIN ),
// 						$this->_db->quoteInto(
// 							"upj.product_id = mp.product_id and upj.user_id = ? ", $user->getUserId()
// 						), array("price1"=>"FORMAT(upj.price, 0)", "is_valid_until_date" => "(upj.valid_until_date >= current_date)"));
// 		// MOD 20140416 Hieunm end

// 		$select->joinLeft (array("mc" => Core_Util_TableNames::MST_CLASS ),
// 				$this->_db->quoteInto(
// 						"mc.item_cd = mp.shipping_class and mc.item_type LIKE ? ",Core_Util_Const::SHIPPING_CLASS
// 				), array("shipping_item_name"=>"mc.item_name"));
// 		$select->joinLeft (array("mc1" => Core_Util_TableNames::MST_CLASS ),
// 				$this->_db->quoteInto(
// 						"mc1.item_cd = mp.stock_class and mc1.item_type LIKE ? ",Core_Util_Const::STOCK_CLASS
// 				), array("stock_item_name"=>"mc1.item_name"));

// 		if ($tylelist != null && $user != null){
// 			if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_FEATURED_PRODUCTS) == 0){
// 				$select->joinInner (array("fp" => Core_Util_TableNames::FEATURED_PRODUCT ),
// 						"fp.product_id = mp.product_id"  ,null)
// 						->joinInner (array("mu" => Core_Util_TableNames::MST_USER ),
// 								$this->_db->quoteInto(
// 									"mu.sales_id = fp.user_id and mu.user_id = ?", $user->getUserId()
// 								), null);
// 			} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS) == 0){
// 				$select->joinInner (array("odi" => Core_Util_TableNames::ORDER_DETAIL_INFO ),
// 						"odi.product_id = mp.product_id and odi.delete_flg = 0"  ,null)
// 						->group("odi.product_id")
// 						->order("SUM(odi.quantity)");
// 			}
// 		}
// 		if ($arrConditions != null && is_array($arrConditions)){
// 			$select->joinInner(array("Cate" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ),
// 						$this->_db->quoteInto(
// 							"Cate.product_id = mp.product_id and Cate.category_id IN (?)", $arrConditions
// 						), null)
// 						->group("Cate.product_id")
// 						->having("COUNT(DISTINCT Cate.category_id) >= ?", count($arrConditions));
// 		}
// 		$select->where("mp.delete_flg = 0" )
// 		->where("mp.product_name like ?", "%$keyword%" );
// 		$select->group("mp.product_id");
// 		$displayNamespace = new Zend_Session_Namespace('Display');
// 		if (!isset($displayNamespace->sort))
// 		{
// 			$displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
// 		}
// 		if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0){
// 			$select->order ("mpp.price_including_tax");
// 		} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
// 			$select->order ("mpp.price_including_tax DESC");
// 		} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
// 			$select->order ("mp.insert_ymd");
// 		}
// 		else {
// 			$select->order ("mp.product_no");
// 		}

// 		if ($paginatorData['itemCountPerPage'] > 0) {
// 			$page     = $paginatorData['currentPage'];
// 			$rowCount = $paginatorData['itemCountPerPage'];
// 			$select->limitPage($page, $rowCount);
// 		}

// 		//print $select;
// 		$rows = $this->fetchAll ( $select )->toArray();
// 		return $rows;
// 	}


  public function queryProducts($paginatorData, $keyword = null, $arrConditions = null, $tylelist = null) {
    $user = Core_Util_Helper::getLoginUser();

    $displayNamespace = new Zend_Session_Namespace('Display');
    if (!isset($displayNamespace->sort))
    {
      $displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
    }

    $selectRawProducts = "(";
    $selectRawProducts .= "SELECT DISTINCT mp2.* ";
    if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0
      || strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
      $selectRawProducts .= ",FORMAT(mpp.price, 0) AS price, mpp.price AS price_final_sort,
           mpp.price_condition_class,
          mpp.quantity_restriction
      ";
    }

    $selectRawProducts .= "FROM $this->_name AS `mp2` ";

    if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0
    || strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
      $selectRawProducts .= "LEFT JOIN " .Core_Util_TableNames::MST_PRODUCT_PRICE ." AS mpp ";
      $selectRawProducts .= "ON mpp.Product_id = mp2.Product_id ";

    }

    if ($tylelist != null && $user != null){
      if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_FEATURED_PRODUCTS) == 0){
        $selectRawProducts .= "INNER JOIN " .Core_Util_TableNames::FEATURED_PRODUCT ." AS fp ON fp.product_id = mp2.product_id ";
        $selectRawProducts .= "INNER JOIN " .Core_Util_TableNames::MST_USER ." AS mu ";
        $selectRawProducts .= "ON " .$this->_db->quoteInto("mu.sales_id = fp.user_id and mu.user_id = ?", $user->getUserId());

      } else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS) == 0){

        $selectRawProducts .= "INNER JOIN " .Core_Util_TableNames::ORDER_DETAIL_INFO ." AS odi ";
        $selectRawProducts .= "ON odi.product_id = mp2.product_id and odi.delete_flg = '0' ";

        $selectRawProducts .= "INNER JOIN " .Core_Util_TableNames::USER_FAVORITES_PRODUCT ." AS ufp ";
        $selectRawProducts .= "ON " .$this->_db->quoteInto("ufp.product_id = mp2.product_id and ufp.user_id = ? ", $user->getUserId());

      } else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_WISH_LIST) == 0){

        $selectRawProducts .= "INNER JOIN " .Core_Util_TableNames::USER_FAVORITES_PRODUCT ." AS ufp ";
        $selectRawProducts .= "ON " .$this->_db->quoteInto("ufp.product_id = mp2.product_id and ufp.user_id = ? ", $user->getUserId());

      } else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_HISTORY_PRODUCTS) == 0){

        $selectRawProducts .= "INNER JOIN " .Core_Util_TableNames::ORDER_DETAIL_INFO ." AS odi ";
        $selectRawProducts .= "ON odi.product_id = mp2.product_id and odi.delete_flg='0' ";

        $selectRawProducts .= "INNER JOIN " .Core_Util_TableNames::ORDER_INFO ." AS oi ";
        $selectRawProducts .= "ON " .$this->_db->quoteInto("oi.order_id = odi.order_id and oi.delete_flg = ? ",Core_Util_Const::DELETE_FLG_0)
        .$this->_db->quoteInto(" AND oi.order_status = ? ", Core_Util_Const::ORDER_STATUS_3)
        .$this->_db->quoteInto(" AND oi.user_id = ? ", $user->getUserId());
      }
    }

    $selectRawProducts .= "WHERE mp2.delete_flg = 0 ";
    $selectRawProducts .= $this->_db->quoteInto("AND mp2.product_name like ? ", "%$keyword%");
    if ($arrConditions != null && is_array($arrConditions)){
      $selectRawProducts .= "AND " .count($arrConditions) ." = ";
      $selectRawProducts .= "(SELECT COUNT(*) ";
      $selectRawProducts .= "FROM " .Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ." AS Cate ";
      $selectRawProducts .= "WHERE Cate.product_id = mp2.product_id ";
      $selectRawProducts .= $this->_db->quoteInto("AND Cate.category_id IN (?) ", $arrConditions);
      $selectRawProducts .= ") ";
    }

    if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0
      || strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
      $selectRawProducts .= "AND mpp.apply_start_date = ";
      $selectRawProducts .= "( ";
      $selectRawProducts .= "SELECT MAX(mppc.apply_start_date) ";
      $selectRawProducts .= "FROM " .Core_Util_TableNames::MST_PRODUCT_PRICE ." AS mppc ";;
      $selectRawProducts .= "WHERE mppc.product_id = mp2.product_id ";
      $selectRawProducts .= "AND mppc.apply_start_date <= current_date ";
      $selectRawProducts .= ") ";
    }

    $selectRawProducts .= "ORDER BY ";
    
    If (!isset($displayNamespace->sort) || empty($displayNamespace->sort)) {
    	$displayNamespace->sort = Core_Util_Const::$SORT_DEFAULT;
    }

//     if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0
//       || strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
//       if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE) ==0) {
//         $selectRawProducts .= "mpp.price ";
//       } else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
//         $selectRawProducts .= "mpp.price DESC ";
//       } else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
//         $selectRawProducts .= "mp2.insert_ymd DESC ";
//       } else {
//       	$selectRawProducts .= "mp2.product_no ";
//       }
//     }

// 	$selectRawProducts .= "mp2.product_no ";
	
	if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE) ==0) {
		$selectRawProducts .= "mpp.price, ";
	} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
		$selectRawProducts .= "mpp.price DESC, ";
	} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
		$selectRawProducts .= "mp2.update_ymd DESC, ";
	}
	
	$selectRawProducts .= "mp2.product_no ";

	/*
    if ($paginatorData['itemCountPerPage'] > 0) {
      $page     = $paginatorData['currentPage'];
      $rowCount = $paginatorData['itemCountPerPage'];
      $selectRawProducts .= " LIMIT " .(($page - 1) * $rowCount) .", " .$rowCount;
    }
    */

    $selectRawProducts .= " )";

    $select = $this->select()->setIntegrityCheck ( false )->distinct()
    ->from(array("mp"=> new Zend_Db_Expr($selectRawProducts)), array("*"));

    if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)!=0
      && strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)!=0){
      $select->joinLeft (array("mpp" => Core_Util_TableNames::MST_PRODUCT_PRICE ),
          "mpp.product_id = mp.product_id
          AND mpp.apply_start_date = (SELECT MAX(mppc.apply_start_date) AS apply_start_date
          FROM  " .Core_Util_TableNames::MST_PRODUCT_PRICE ." AS mppc
          WHERE mppc.product_id = mp.product_id
          AND mppc.apply_start_date <= current_date
          ) ",
          array("price"=>"FORMAT(mpp.price, 0)",
          "price_condition_class"=>"mpp.price_condition_class",
          "quantity_restriction"=>"mpp.quantity_restriction"
          ));
    }
    $select->joinLeft (array("qdi" => Core_Util_TableNames::QUOTATION_DETAIL_INFO ),
        "qdi.product_id = mp.product_id AND qdi.delete_flg = '0'  "  ,null);

    $select->joinLeft (array("qi" => Core_Util_TableNames::QUOTATION_INFO ),
            $this->_db->quoteInto(
                "qi.quotation_id = qdi.quotation_id AND qi.quotation_id = (SELECT MAX(qdi.quotation_id) FROM " .Core_Util_TableNames::QUOTATION_INFO
                      ." WHERE user_id = ? AND qi.delete_flg = '0') ",$user->getUserId()),
            array("quotation_id", "status"));

    $select->joinLeft (array("upj" => Core_Util_TableNames::USER_PRODUCT_JOIN ),
        $this->_db->quoteInto(
            "upj.product_id = mp.product_id and upj.user_id = ? ", $user->getUserId()
        ), array("price1"=>"FORMAT(upj.price, 0)", "is_valid_until_date" => "(upj.valid_until_date >= current_date)"));

    $select->joinLeft (array("mc" => Core_Util_TableNames::MST_CLASS ),
        $this->_db->quoteInto(
            "mc.item_cd = mp.shipping_class and mc.item_type = ? ",Core_Util_Const::SHIPPING_CLASS
        ), array("shipping_item_name"=>"mc.item_name"));
    $select->joinLeft (array("mc1" => Core_Util_TableNames::MST_CLASS ),
        $this->_db->quoteInto(
            "mc1.item_cd = mp.stock_class and mc1.item_type = ? ",Core_Util_Const::STOCK_CLASS
        ), array("stock_item_name"=>"mc1.item_name"));
    //add 20140724 locpht start
    $select->where(" ((price_condition_class <> '1') OR (price_condition_class ='1'
    		and exists ( select * from USER_PRODUCT_JOIN AS upj1 
    		WHERE upj1.user_id = '".$user->getUserId()."' AND upj1.product_id = mpp.product_id)))");
    //add 20140724 locpht end

    $select->group("mp.product_id");

    if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE) ==0) {
    	$select->order ("mp.price_final_sort");
    } else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
    	$select->order( "mp.price_final_sort DESC");
    } else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
    	$select->order ("mp.update_ymd DESC");
    }
    
    $select->order ("mp.product_no");
    
    //add 20140724 locpht start
    if ($paginatorData['itemCountPerPage'] > 0) {
      $page     = $paginatorData['currentPage'];
      $rowCount = $paginatorData['itemCountPerPage'];
      $select->limit($rowCount,($page - 1) * $rowCount);
    }
    //add 20140724 locpht end
    $rows = $this->fetchAll ( $select )->toArray();
    return $rows;
  }

// 	public function queryProductsRelation($product_id) {
// 		$user = Core_Util_Helper::getLoginUser();

// 		$sub_select = $this->select()->setIntegrityCheck ( false )->distinct()
// 				->from(array("pcj"=>Core_Util_TableNames::PRODUCT_CATEGORY_JOIN), array("pcj.category_id"))
// 				->where("pcj.product_id = ?", $product_id);

// 		$select = $this->select()->setIntegrityCheck ( false )->distinct()
// 		->from(array("mp"=>$this->_name), array("*"));
// 		// MOD 20140429 Hieunm start
// 		/*
// 		$select->joinInner (array("odi" => Core_Util_TableNames::ORDER_DETAIL_INFO ),
// 				"odi.product_id = mp.product_id and odi.delete_flg = 0"  ,null)
// 				->group("odi.product_id")
// 				->order("SUM(odi.quantity)");
// 		$select->joinInner(array("Cate" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ),
// 				$this->_db->quoteInto(
// 						"Cate.product_id = mp.product_id and Cate.category_id IN (?)", $sub_select
// 				), null);
// 		*/
// 		$select->joinLeft(array("odi" => Core_Util_TableNames::ORDER_DETAIL_INFO ),
// 				"odi.product_id = mp.product_id and odi.delete_flg = 0" ,null);
// 		$select->joinLeft(array("Cate" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ),
// 				"Cate.product_id = mp.product_id "
// 				, null);
// 		$select->group("mp.product_id")
// 				->order("odi.quantity DESC");
// 		$select->where("Cate.category_id IN (?)", $sub_select );
// 		// MOD 20140429 Hieunm end

// 		$select->where("mp.delete_flg = 0 and mp.product_id <> ?", $product_id );

// 		$pageClassSession = new Zend_Session_Namespace(
// 				Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
// 		/* @var $mstClassPage Core_Models_MstClass */
// 		$mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_RELATION_ITEM];

// 		$itemCountPerPage = $mstClassPage->getNote1();
// 		$select->limit($itemCountPerPage, 0);

// 		$rows = $this->fetchAll ( $select )->toArray();

// 		print $select;
// 		return $rows;
// 	}

  public function queryProductsRelation($product_id) {

    $pageClassSession = new Zend_Session_Namespace(
        Core_Util_Const::SESSION_NAMESPACE_PAGE_CLASS);
    /* @var $mstClassPage Core_Models_MstClass */
    $mstClassPage = $pageClassSession->pageClass[Core_Util_Const::ITEMS_PER_PAGE_CLASS_LIST_RELATION_ITEM];

    $itemCountPerPage = $mstClassPage->getNote1();

    $selectRawProducts = "";
    $selectRawProducts .= "( ";
    $selectRawProducts .= "SELECT ";
    $selectRawProducts .= "tp.product_id ";
    $selectRawProducts .= "FROM ";
    $selectRawProducts .= "( ";
    $selectRawProducts .= "SELECT DISTINCT ";
    $selectRawProducts .= "pcj.product_id ";
    $selectRawProducts .= "FROM " .Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ." AS pcj ";
    $selectRawProducts .= "INNER JOIN ( ";
    $selectRawProducts .= "SELECT ";
    $selectRawProducts .= "category_id ";
    $selectRawProducts .= "FROM " .Core_Util_TableNames::PRODUCT_CATEGORY_JOIN;
    $selectRawProducts .= " WHERE " .$this->_db->quoteInto( "product_id = ? ", $product_id );
    $selectRawProducts .= ") AS pcj2 ON pcj.category_id = pcj2.category_id ";
    $selectRawProducts .= $this->_db->quoteInto( "WHERE pcj.product_id <> ? ", $product_id );
    $selectRawProducts .= ") tp ";
    $selectRawProducts .= "LEFT JOIN ( ";
    $selectRawProducts .= "SELECT ";
    $selectRawProducts .= "odi.product_id, ";
    $selectRawProducts .= "SUM(odi.quantity) AS quantity ";
    $selectRawProducts .= "FROM ";
    $selectRawProducts .= Core_Util_TableNames::ORDER_DETAIL_INFO . " AS odi ";
    $selectRawProducts .= "GROUP BY ";
    $selectRawProducts .= "odi.product_id ";
    $selectRawProducts .= ") AS od ON od.product_id = tp.product_id ";
    $selectRawProducts .= "ORDER BY ";
    $selectRawProducts .= "od.quantity DESC ";
    $selectRawProducts .= "LIMIT " . $itemCountPerPage;
    $selectRawProducts .= ") ";

    $select = $this->select()->setIntegrityCheck ( false )
      ->from(array("rp"=> New Zend_Db_Expr($selectRawProducts)), null);

    $select->joinInner(array("mp" => Core_Util_TableNames::MST_PRODUCT),
        "mp.product_id = rp.product_id",
        array("*"));

    $rows = $this->fetchAll ( $select )->toArray();
    return $rows;
  }
  //Edit 20140613 Hungnh END

  public function queryProduct($productid) {
    $user = Core_Util_Helper::getLoginUser();

    $select = $this->select()->setIntegrityCheck ( false )->distinct()
    ->from(array("mp"=>$this->_name), array("*"));

    $select->joinLeft (array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT ),
        $this->_db->quoteInto(
            "ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId()
        ),array("like"=>"ufp.product_id"));

    $selectprice = $this->select()->setIntegrityCheck ( false )
    ->from(array("mppc"=>Core_Util_TableNames::MST_PRODUCT_PRICE), array("MAX(mppc.apply_start_date)"))
    ->where("mppc.Product_id = mp.Product_id and mppc.apply_start_date <= current_date");
    $select->joinLeft (array("mpp" => Core_Util_TableNames::MST_PRODUCT_PRICE ),
        $this->_db->quoteInto(
            "mpp.product_id = mp.product_id and mpp.apply_start_date <= current_date and mpp.apply_start_date = (?) ", $selectprice
        ), array("price"=>"FORMAT(mpp.price, 0)",
          "mpp.price_condition_class",
          "mpp.quantity_restriction"
          ));

    $select->joinLeft (array("qdi" => Core_Util_TableNames::QUOTATION_DETAIL_INFO ),
        "qdi.product_id = mp.product_id and qdi.delete_flg = 0"  ,null);

    // MOD 20140416 Hieunm start
    /*$select->joinLeft (array("qi" => Core_Util_TableNames::QUOTATION_INFO ),
        $this->_db->quoteInto(
            "qi.quotation_id = qdi.quotation_id and qi.delete_flg = 0 and qi.user_id = ?",$user->getUserId()
        ), array("MAX(qi.quotation_id)"));*/
    $select->joinLeft (array("qi" => Core_Util_TableNames::QUOTATION_INFO ),
        $this->_db->quoteInto(
            "qi.quotation_id = qdi.quotation_id and qi.delete_flg = 0 and qi.user_id = ?",$user->getUserId()
        ), array("quotation_id" => "MAX(qi.quotation_id)",
             "status" => "(select status FROM " . Core_Util_TableNames::QUOTATION_INFO
                      . " WHERE user_id = '" . $user->getUserId()
                      . "' AND quotation_id = MAX(qi.quotation_id))"));
    // MOD 20140416 Hieunm end

    // MOD 20140416 Hieunm start
    /*$select->joinLeft (array("upj" => Core_Util_TableNames::USER_PRODUCT_JOIN ),
        $this->_db->quoteInto(
            "upj.product_id = mp.product_id and upj.user_id = ? and upj.valid_until_date >= current_date",$user->getUserId()
        ), array("price1"=>"FORMAT(upj.price,0)"));*/
    $select->joinLeft (array("upj" => Core_Util_TableNames::USER_PRODUCT_JOIN ),
        $this->_db->quoteInto(
            "upj.product_id = mp.product_id and upj.user_id = ? ", $user->getUserId()
        ), array("price1"=>"FORMAT(upj.price,0)", "is_valid_until_date" => "(upj.valid_until_date >= current_date)"));
    // MOD 20140416 Hieunm end

    $select->joinLeft (array("mc" => Core_Util_TableNames::MST_CLASS ),
        $this->_db->quoteInto(
            "mc.item_cd = mp.shipping_class and mc.item_type LIKE ? ",Core_Util_Const::SHIPPING_CLASS
        ), array("shipping_item_name"=>"mc.item_name"));
    $select->joinLeft (array("mc1" => Core_Util_TableNames::MST_CLASS ),
        $this->_db->quoteInto(
            "mc1.item_cd = mp.stock_class and mc1.item_type LIKE ? ",Core_Util_Const::STOCK_CLASS
        ), array("stock_item_name"=>"mc1.item_name"));

    $select->joinLeft (array("mpe" => Core_Util_TableNames::MST_PRODUCT_EXTRA),
                    "mpe.product_id = mp.product_id", array("mpe.product_detail_info"));

    $select->joinLeft (array("mc2" => Core_Util_TableNames::MST_CLASS ),
        $this->_db->quoteInto(
            //change  DETAIL_CLASS = 14  by const ITEM_TYPE_PRODUCT_DETAIL = '4';
            "mc2.item_cd = mpe.detail_class and mc2.item_type LIKE ? ", Core_Util_Const::ITEM_TYPE_PRODUCT_DETAIL
        ), array("detail_class_name"=>"mc2.item_name"));

    $select->where("mp.delete_flg = 0 and mp.product_id = '". $productid
    //add 20140724 locpht start
    ."' and ((mpp.price_condition_class <> '1') OR (mpp.price_condition_class ='1'
    		and exists ( select * from USER_PRODUCT_JOIN AS upj1 
    		WHERE upj1.user_id = '".$user->getUserId()."' AND upj1.product_id = mpp.product_id)))");
    //add 20140724 locpht end
    $select->order("mpe.display_order");
    //print $select;
    $row = $this->fetchAll( $select )->toArray();
    return $row;
  }

  /**
   *
   * @param unknown $idUserLogin
   * @return Ambigous <multitype:, multitype:unknown >
   */
  public function getFeatureProduct($idUser, $limit) {
    $select = $this->select()->setIntegrityCheck ( false )
    ->from(array("product" => $this->_name))
    ->joinInner(array("fea_pro" => Core_Util_TableNames::FEATURED_PRODUCT),
        "fea_pro.product_id = product.product_id")
    ->joinInner(array("user"=>Core_Util_TableNames::MST_USER),
      "`user`.sales_id = fea_pro.user_id")
    ->where("`user`.user_id = ?", $idUser)
    ->where("product.delete_flg = ? ", '0')
    ->limit($limit, 0);
    $rows = $this->fetchAll($select);
    $data = $this->setRowsToArray($rows);
    return $data;
  }

  /**
   * get best sell product
   * @return Ambigous <multitype:, multitype:unknown >
   */
  public function getBestSellProduct($limit) {
    $select = $this->select()->setIntegrityCheck ( false )
    ->from(array("t" => Core_Util_TableNames::ORDER_DETAIL_INFO))
    ->joinInner(array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT),
        "t.product_id = ufp.product_id")
    ->joinInner(array("product" => Core_Util_TableNames::MST_PRODUCT),
        "t.product_id = product.product_id")
    ->where("t.delete_flg = ?", '0')
    ->where("product.delete_flg = ? ", '0')    // ADD 20140512 Hieunm
    ->group("t.product_id")
    ->order("t.quantity DESC")
    ->limit($limit, 0)
    ;
    $rows = $this->fetchAll($select);
    $data = $this->setRowsToArray($rows);
    return $data;
  }


  public function getProductUserLike($userId, $limit) {
    $select = $this->select()->setIntegrityCheck ( false )
    ->from(array("UFPro" => Core_Util_TableNames::USER_FAVORITES_PRODUCT))
    ->joinInner(array("user" => Core_Util_TableNames::MST_USER),
        "`user`.user_id = UFPro.user_id")
    ->joinInner(array("product" => Core_Util_TableNames::MST_PRODUCT),
        "UFPro.product_id = product.product_id")
    ->where("`user`.user_id = ?", $userId)
    ->where(" product.delete_flg = ? ", '0')
    ->limit($limit, 0);
    $rows = $this->fetchAll($select);
    $data = $this->setRowsToArray($rows);
    return $data;
  }

  public function getFeatureProductOfSale($saleUserId) {
    $select = $this->select()->setIntegrityCheck ( false )
    ->from(array("feapro" => Core_Util_TableNames::FEATURED_PRODUCT))
    ->joinInner(array("product" => Core_Util_TableNames::MST_PRODUCT),
    "product.product_id = feapro.product_id")
    ->where(" product.delete_flg = ? ", '0')
    ->where(" feapro.user_id = ? ", $saleUserId);
    $rows = $this->fetchAll($select);
    $data = $this->setRowsToArray($rows);
    return $data;
  }

  //Edit 20140616 Hungnh START
// 	public function getListProduct($keyword,$conditionkeys,$paginatorData){
// 	    $queryPrice = "(select price from MST_PRODUCT_PRICE pro_price where pro_price.product_id = pro.product_id and pro_price.apply_start_date <= '"
// 	                    .date("Y/m/d",time())."' order by apply_start_date desc  limit 1) as price";
// 	    $query = $this->select()->from(array("pro" =>$this->_name),array("pro.*",$queryPrice));
// 	    $query->setIntegrityCheck(false);

// //	    $query->joinLeft(array("pro_price" => Core_Util_TableNames::MST_PRODUCT_PRICE),
// //				$this->_db->quoteInto("pro_price.product_id = pro.product_id",null),null);

//         $query->where("pro.delete_flg = ?",0);
// 	    if(isset($keyword) && $keyword!=""){
// 	        $query->where("LOWER(product_name) like ?","%".strtolower($keyword)."%");
// 	    }
// 	    if(isset($conditionkeys) && count($conditionkeys)>0){
// 	        $query->where("pro.product_id in (select product_id from PRODUCT_CATEGORY_JOIN cat where cat.category_id IN (?))",$conditionkeys);
// 	    }
// 	    //page
// 		if ($paginatorData['itemCountPerPage'] > 0) {
// 			$page     = $paginatorData['currentPage'];
// 			$rowCount = $paginatorData['itemCountPerPage'];
// 			$query->limitPage($page, $rowCount);
// 		}

// 	    $displayNamespace = new Zend_Session_Namespace('Display');
// 		if (!isset($displayNamespace->sort))
// 		{
// 			$displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
// 		}
// 		if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0){
// 			$query->order ("price ASC");
// 		} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
// 			$query->order ("price DESC");
// 		} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
// 			$query->order ("update_ymd desc");
// 		}
// 		else {
// 			$query->order("product_no ASC");
// 		}
		
// 		print $query;

// 		$rows = $this->fetchAll($query)->toArray();

// 		return $rows;
// 	}

  public function getListProduct($keyword,$conditionkeys,$paginatorData){

    $query = $this->select()->setIntegrityCheck(false);
    $query->from(array("mp" =>$this->_name),array("*"))
    ->group("mp.product_id");

    if(isset($conditionkeys) && count($conditionkeys)>0){
      $query->joinInner(array("pcj" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN),
          $this->_db->quoteInto("pcj.product_id = mp.product_id AND pcj.category_id IN (?) ", $conditionkeys), null);
      $query->having("COUNT(DISTINCT pcj.category_id) >= ?", count($conditionkeys));
    }

    $query->joinLeft(array("mpp" => Core_Util_TableNames::MST_PRODUCT_PRICE),
    "mp.product_id = mpp.product_id AND mpp.apply_start_date = "
                ."(SELECT MAX(mpp2.apply_start_date) FROM " .Core_Util_TableNames::MST_PRODUCT_PRICE.
                " mpp2 WHERE mpp2.product_id = mp.product_id AND mpp2.apply_start_date <= CURRENT_DATE) ",
    array("price"));

    $query->where("mp.delete_flg = ?",0);
    if(isset($keyword) && !empty($keyword)) {
      $query->where("LOWER(product_name) like ?","%".strtolower($keyword)."%");
    }

    if ($paginatorData['itemCountPerPage'] > 0) {
      $page     = $paginatorData['currentPage'];
      $rowCount = $paginatorData['itemCountPerPage'];
      $query->limitPage($page, $rowCount);
    }

    $displayNamespace = new Zend_Session_Namespace('Display');
    if (!isset($displayNamespace->sort))
    {
      $displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
    }
    if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0){
      $query->order ("mpp.price ASC");
    } else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
      $query->order ("mpp.price DESC");
    } else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
      $query->order ("mp.update_ymd desc");
    }
    
    $query->order("mp.product_no ASC");

    $rows = $this->fetchAll($query)->toArray();
    return $rows;
  }
  //Edit 20140616 Hungnh END

  /**
   * getProductWithPriceAndMagni
   * @param int $idProduct
   * @return Core_Models_MstProduct
   */
  public function getProductWithPriceAndMagni($idProduct){
    $this->_instanceClass = 'Core_Models_MstProduct';

    $innerTablePriceStr = "";
    $innerTablePriceStr .= " (SELECT max(t.apply_start_date) as apply_start_date, t.product_id ";
    $innerTablePriceStr .= " FROM " . Core_Util_TableNames::MST_PRODUCT_PRICE . " t ";
    $innerTablePriceStr .= " WHERE t.apply_start_date <= now() ";
    $innerTablePriceStr .= " GROUP BY t.product_id) ";
    $innerPrice = new Zend_Db_Expr($innerTablePriceStr);



    $innerMagniStr = "";
    $innerMagniStr .= " (SELECT max(t.apply_start_date) as apply_start_date, t.product_id ";
    $innerMagniStr .= " FROM " . Core_Util_TableNames::MST_MAGNIFICATION . " t ";
    $innerMagniStr .= " WHERE t.apply_start_date <= now() ";
    $innerMagniStr .= " GROUP BY t.product_id) ";
    $innerMagni = new Zend_Db_Expr($innerMagniStr);



    $query = $this->select()->from(array('product' =>'MST_PRODUCT'));
    $query->setIntegrityCheck(false);

    $query->joinInner(array('proPrice' => Core_Util_TableNames::MST_PRODUCT_PRICE),
        'proPrice.product_id = product.product_id', array('proPrice.price', 'proPrice.price_including_tax', 'proPrice.tax'));

    $query->joinInner(array('tempPrice' =>$innerPrice),
        'tempPrice.apply_start_date = proPrice.apply_start_date AND tempPrice.product_id = product.product_id');

    $query->joinInner(array('magnifi' =>Core_Util_TableNames::MST_MAGNIFICATION),
        'magnifi.product_id = product.product_id',
        array('magnifi.magnification_point')
    );

    $query->joinInner(array('tempMagnifi' => $innerMagni),
        'tempMagnifi.apply_start_date = magnifi.apply_start_date AND tempMagnifi.product_id = product.product_id');

    $query->where('product.delete_flg = 0 ');
    $query->where('product.product_id = ? ', $idProduct);

    $rows = $this->fetchAll($query);
    $arrProduct	= $this->setRowsToArray($rows);
    if (count($arrProduct) > 0) {
      return $arrProduct[0];
    } else {
      null;
    }
  }

  /**
   * getProductInOrderCartByUserId
   * @param unknown $idUser
   * @return unknown
   */
  public function getProductInOrderCartByUserId($idUser){
    $innerTablePriceStr = "";
    $innerTablePriceStr .= " (SELECT max(t.apply_start_date) as apply_start_date, t.product_id ";
    $innerTablePriceStr .= " FROM ". Core_Util_TableNames::MST_PRODUCT_PRICE ." t ";
    $innerTablePriceStr .= " WHERE t.apply_start_date <= now() ";
    $innerTablePriceStr .= " GROUP BY t.product_id) ";
    $innerPrice = new Zend_Db_Expr($innerTablePriceStr);

    $query = $this->select()->from(array('product' => Core_Util_TableNames::MST_PRODUCT));
    $query->setIntegrityCheck(false);

    $query->joinInner(array('orderCart' => Core_Util_TableNames::ORDER_CART_INFO),
        'orderCart.product_id = product.product_id', array('orderCart.quantity as order_quantity'));

    $query->joinInner(array('proPrice' => Core_Util_TableNames::MST_PRODUCT_PRICE),
        'proPrice.product_id = product.product_id', array('proPrice.price', 'proPrice.price_including_tax', 'proPrice.tax',
        'proPrice.price_condition_class',
        'proPrice.quantity_restriction'));

    $query->joinInner(array('tempPrice' =>$innerPrice),
        'tempPrice.apply_start_date = proPrice.apply_start_date and tempPrice.product_id = product.product_id');

    $query->where('product.delete_flg = 0 ');
    $query->where('orderCart.user_id = ? ', $idUser);

    $rows = $this->fetchAll($query);
    $arrProduct	= $this->setRowsToArray($rows);
    return $arrProduct;
  }

  /**
   * getProductInQuotationCartByUserId
   * @param unknown $idUser
   * @return unknown
   */
  public function getProductInQuotationCartByUserId($idUser){
    $innerTablePriceStr = "";
    $innerTablePriceStr .= " (SELECT max(t.apply_start_date) as apply_start_date, t.product_id ";
    $innerTablePriceStr .= " FROM ". Core_Util_TableNames::MST_PRODUCT_PRICE ." t ";
    $innerTablePriceStr .= " WHERE t.apply_start_date <= now() ";
    $innerTablePriceStr .= " GROUP BY t.product_id) ";
    $innerPrice = new Zend_Db_Expr($innerTablePriceStr);

    $query = $this->select()->from(array('product' => Core_Util_TableNames::MST_PRODUCT));
    $query->setIntegrityCheck(false);

    $query->joinInner(array('quotationCart' => Core_Util_TableNames::QUOTATION_CART_INFO),
        'quotationCart.product_id = product.product_id', array('quotationCart.quantity as order_quantity'));

    $query->joinInner(array('proPrice' => Core_Util_TableNames::MST_PRODUCT_PRICE),
        'proPrice.product_id = product.product_id', array('proPrice.price', 'proPrice.price_including_tax', 'proPrice.tax'));

    $query->joinInner(array('tempPrice' =>$innerPrice),
        'tempPrice.apply_start_date = proPrice.apply_start_date and tempPrice.product_id = product.product_id');

    $query->where('product.delete_flg = 0 ');
    $query->where('quotationCart.user_id = ? ', $idUser);

    $rows = $this->fetchAll($query);
    $arrProduct	= $this->setRowsToArray($rows);
    return $arrProduct;
  }
  
  //Edit 20140627 Hungnh START
//   public function getListProductForExportCSV($arrId) {
//     $query = $this->select()->from(
//         array('product' => Core_Util_TableNames::MST_PRODUCT),
//         // product columns
//         array(
//             'product_no'			=> 'product.product_no',
//             'product_name'			=> 'product.product_name',
//             'product_brief'			=> 'product.product_brief',
//             'shipping_display_flag' => 'product.shipping_display_flag',
//             'shipping_class_cd' 	=> 'product.shipping_class',
//             'shipping_fee' 			=> 'product.shipping_fee',
//             'stock_display_flag'	=> 'product.stock_display_flag',
//             'stock_class_cd'		=> 'product.stock_class',
//             'stock_qty'				=> 'product.stock_qty'
//         )
//     )
//     ->setIntegrityCheck(false)
//     // MST_PRODUCT_PRICE
//     ->joinLeft(
//         array("product_price" => Core_Util_TableNames::MST_PRODUCT_PRICE),
//           "product.product_id = product_price.product_id",
//           array(
//               "price" => "product_price.price",
//               "apply_start_date" => "product_price.apply_start_date"
//           )
//         )
//     // MST_MAGNIFICATION
//     ->joinLeft(
//         array("maginification" => Core_Util_TableNames::MST_MAGNIFICATION),
//         "product.product_id = maginification.product_id",
//         array(
//             "magnification_point" => "maginification.magnification_point",
//             "point_apply_start_date" => "maginification.apply_start_date"
//         )
//       )
//     // MST_CLASS for Shipping class name
//     ->joinLeft(
//         array("class_shipping" => Core_Util_TableNames::MST_CLASS),
//           "class_shipping.item_type LIKE '" . Core_Util_Const::SHIPPING_CLASS. "' AND class_shipping.item_cd = product.shipping_class",
//         array(
//             "shipping_class_name" => "class_shipping.item_name"
//         )
//       )

//     // MST_CLASS for stock class name
//     ->joinLeft(
//         array("stock_shipping" => Core_Util_TableNames::MST_CLASS),
//         "stock_shipping.item_type LIKE '" . Core_Util_Const::STOCK_CLASS. "' AND product.stock_class = stock_shipping.item_cd",
//         array(
//             "stock_class_name" => "stock_shipping.item_name"
//         )
//       )
//     ->joinLeft(
//         array("category_join" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN),
//         "product.product_id = category_join.product_id",
//         array()
//       )


//     // MST_CATEGORY for child category
//     ->joinLeft(
//           array("child_category" => Core_Util_TableNames::MST_CATEGORY),
//           "category_join.category_id = child_category.category_id",
//           array(
//               "category_name" => "child_category.category_name"
//           )
//       )

//     // MST_CATEGORY for parent category
//     ->joinLeft(
//         array("parent_category" => Core_Util_TableNames::MST_CATEGORY),
//         "child_category.parent_id = parent_category.category_id",
//         array(
//             "parent_category_name" => "parent_category.category_name"
//         )
//       )
//     ;
//     $strProductId = implode(",", $arrId);
//     $query->where("product.product_id IN (?)", new Zend_Db_Expr($strProductId));

//     //sort
//     $displayNamespace = new Zend_Session_Namespace('Display');
//     if (!isset($displayNamespace->sort))
//     {
//       $displayNamespace->sort = Core_Util_Const::SORT_DEFAULT;
//     }
//     if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0){
//       $query->order ("product_price.price ASC");
//     } else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
//       $query->order ("product_price.price DESC");
//     } else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
//       $query->order ("product.update_ymd desc");
//     }
//     else {
//       $query->order("product.product_no ASC");
//     }

//     return $this->fetchAll($query)->toArray();
//   }
  
  public function sqlToQueryProductId($keyword = null, $arrConditions = null, $tylelist = null) {
  
  	$select = $this->select()->setIntegrityCheck ( false )->distinct()
  	->from(array("mp"=>$this->_name), array("*"));
  
  	$user = Core_Util_Helper::getLoginUser();
  
  	if ($tylelist != null && $user != null){
  		if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_FEATURED_PRODUCTS) == 0){
  			$select = $select->joinInner (array("fp" => Core_Util_TableNames::FEATURED_PRODUCT ),
  					"fp.product_id = mp.product_id"  ,null)
  					->joinInner (array("mu" => Core_Util_TableNames::MST_USER ),
  							$this->_db->quoteInto(
  									"mu.sales_id = fp.user_id and mu.user_id = ?", $user->getUserId()
  							), null);
  		} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_POPULAR_PRODUCTS) == 0){
  			$select = $select->joinInner (array("odi" => Core_Util_TableNames::ORDER_DETAIL_INFO ),
  					"odi.product_id = mp.product_id and odi.delete_flg = 0"  ,null);
  			$select = $select->joinInner (array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT ),
  					$this->_db->quoteInto(
  							"ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId()
  					),null);
  		} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_WISH_LIST) == 0){
  			$select = $select->joinInner (array("ufp" => Core_Util_TableNames::USER_FAVORITES_PRODUCT ),
  					$this->_db->quoteInto(
  							"ufp.product_id = mp.product_id and ufp.user_id = ?", $user->getUserId()
  					),null);
  		} else if (strcmp($tylelist, Core_Util_Const::TYLE_LIST_HISTORY_PRODUCTS) == 0){
  			$select = $select->joinInner (array("odi" => Core_Util_TableNames::ORDER_DETAIL_INFO ),
  					"odi.product_id = mp.product_id and odi.delete_flg='0'"  ,null)
  					->joinInner (array("oi" => Core_Util_TableNames::ORDER_INFO ),
  							$this->_db->quoteInto("oi.order_id = odi.order_id and oi.delete_flg = ? ",Core_Util_Const::DELETE_FLG_0).
  							$this->_db->quoteInto( "and oi.order_status = ? ", Core_Util_Const::ORDER_STATUS_3).
  							$this->_db->quoteInto(" and oi.user_id = ? ", $user->getUserId()), null);
  		}
  	}
  	if ($arrConditions != null && is_array($arrConditions)){
  		$select->joinInner(array("Cate" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN ),
  				$this->_db->quoteInto(
  						"Cate.product_id = mp.product_id and Cate.category_id IN (?)", $arrConditions
  				), null)
  				->group("Cate.product_id")
  				->having("COUNT(DISTINCT Cate.category_id) >= ?", count($arrConditions));
  	}
  
  	$select = $select->where("mp.delete_flg = '0'" );
  	
  	if (isset($keyword) && !empty($keyword)) {
  		$select->where("mp.product_name like ?", "%$keyword%" );
  	}
  
  	return $select;
  }

  public function getListProductForExportCSV($keyword = null, $arrConditions = null, $tylelist = null) {
  	$query = $this->select()->from(
  			array('product' => $this->sqlToQueryProductId($keyword, $arrConditions, $tylelist)),
  			// product columns
  			array(
  					'product_no'			=> 'product.product_no',
  					'product_name'			=> 'product.product_name',
  					'product_brief'			=> 'product.product_brief',
  					'shipping_display_flag' => 'product.shipping_display_flag',
  					'shipping_class_cd' 	=> 'product.shipping_class',
  					'shipping_fee' 			=> 'product.shipping_fee',
  					'stock_display_flag'	=> 'product.stock_display_flag',
  					'stock_class_cd'		=> 'product.stock_class',
  					'stock_qty'				=> 'product.stock_qty',
  					'maker_product_no'		=> 'product.maker_product_no',
  					'supplier_code'			=> 'product.supplier_code'
  			)
  	)
  	->setIntegrityCheck(false)
  	// MST_PRODUCT_PRICE
  	->joinLeft(
  			array("product_price" => Core_Util_TableNames::MST_PRODUCT_PRICE),
  			"product.product_id = product_price.product_id",
  			array(
  					"price" => "product_price.price",
  					"price_including_tax" => "product_price.price_including_tax",
  					"apply_start_date" => "product_price.apply_start_date",
		  			"price_condition_class" => "product_price.price_condition_class",
		  			"quantity_restriction" => "product_price.quantity_restriction"
  			)
  	)
  	// MST_MAGNIFICATION
  	->joinLeft(
  			array("maginification" => Core_Util_TableNames::MST_MAGNIFICATION),
  			"product.product_id = maginification.product_id",
  			array(
  					"magnification_point" => "maginification.magnification_point",
  					"point_apply_start_date" => "maginification.apply_start_date"
  			)
  	)
  	// MST_CLASS for Shipping class name
  	->joinLeft(
  			array("class_shipping" => Core_Util_TableNames::MST_CLASS),
  			"class_shipping.item_type LIKE '" . Core_Util_Const::SHIPPING_CLASS. "' AND class_shipping.item_cd = product.shipping_class",
  			array(
  					"shipping_class_name" => "class_shipping.item_name"
  			)
  	)
  
  	// MST_CLASS for stock class name
  	->joinLeft(
  			array("stock_shipping" => Core_Util_TableNames::MST_CLASS),
  			"stock_shipping.item_type LIKE '" . Core_Util_Const::STOCK_CLASS. "' AND product.stock_class = stock_shipping.item_cd",
  			array(
  					"stock_class_name" => "stock_shipping.item_name"
  			)
  	)
  // MST_CLASS for supplier name
  	->joinLeft(
  			array("class_supplier" => Core_Util_TableNames::MST_CLASS),
  			"class_supplier.item_type LIKE '" . Core_Util_Const::SUPPLIER_ITEM_TYPE. "' AND 
  			class_supplier.item_cd = product.supplier_code ",
  			array(
  					"item_name" => "class_supplier.item_name"
  			)
  	)
  	->joinLeft(
  			array("category_join" => Core_Util_TableNames::PRODUCT_CATEGORY_JOIN),
  			"product.product_id = category_join.product_id",
  			array()
  	)
  
  
  	// MST_CATEGORY for child category
  	->joinLeft(
  			array("child_category" => Core_Util_TableNames::MST_CATEGORY),
  			"category_join.category_id = child_category.category_id",
  			array(
  					"category_name" => "child_category.category_name"
  			)
  	)
  
  	// MST_CATEGORY for parent category
  	->joinLeft(
  			array("parent_category" => Core_Util_TableNames::MST_CATEGORY),
  			"child_category.parent_id = parent_category.category_id",
  			array(
  					"parent_category_name" => "parent_category.category_name"
  			)
  	)
  	;
  
  	//sort
  	$displayNamespace = new Zend_Session_Namespace('Display');
  	if (!isset($displayNamespace->sort))
  	{
  		$displayNamespace->sort = Core_Util_Const::$SORT_DEFAULT;
  	}
  	if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_LOW_TO_HIGH_PRICE)==0){
  		$query->order ("product_price.price ASC");
  	} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_HIGH_TO_LOW_PRICE)==0){
  		$query->order ("product_price.price DESC");
  	} else if (strcmp($displayNamespace->sort, Core_Util_Const::SORT_NEWEST)==0){
  		$query->order ("product.update_ymd DESC");
  	}
  	
  	$query->order("product.product_no ASC");
  	
  	return $this->fetchAll($query)->toArray();
  }
  //Edit 20140627 Hungnh END

  public function checkProductNoExist($productNo) {
    $res = $this->get(array("product_no = ? and delete_flg = '0'" => $productNo));
    if ($res === false || $res === null) {
      return false;
    } else {
      return true;
    }
  }

  /**
   * getProductByCond
   * @return array
   */
  public function getProductByCond ($condition) {
    $arr = $this->getAll($condition);
    return $arr;
  }

  public function getidProdByProductNo($productNo){
    $product = $this->get(array('product_no = ?' => $productNo));
    if ($product !== false && $product !== null) {
       return $product->getProductId();
    } else {
      return null;
    }


  }

}
