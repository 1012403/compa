<?php
class Core_Service_MstProductService extends Core_Service_Abstract {

	/**
	 *
	 * @var Core_Db_MstProductDb
	 */
	private $productDb;

	function __construct() {
		parent::__construct();
		$this->productDb = new Core_Db_MstProductDb();
	}

	/**
	 *
	 * @return array|boolean
	 */
	public function getAllProduct() {
		try {
			$db 		= $this->productDb;
			$arrProduct = $db->getAll();
			return $arrProduct;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function getAllTopProduct($page = null) {
		try {
			if ($page === null) {
				$page = 0;
			}
			$condition = array();
			$condition[Core_Models_MasterModel::DELETE_FIELD . " = ?"] = '0';
			$db  = $this->productDb;
			$order = null;
			$arrProduct = $db->getAllProduct($condition, $order, $page);
			return $arrProduct;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}


	public function insertProduct($data){
	    try {
            $this->beginTransaction();
            //Insert into 商品マスタ MST_PRODUCT
            $id = $this->insertMstProduct($data);
            //Insert into 商品単価マスタ MST_PRODUCT_PRICE
            $this->insertMstProductPrice($id,$data);
            //Insert into ポイント倍率マスタ MST_MAGNIFICATION
            $this->insertMagnification($id,$data);
            //Insert into 商品カテゴリーマスタ PRODUCT_CATEGORY_JOIN
            $this->insertProductCategoryJoin($id,$data);
            //Insert into 商品詳細説明マスタ MST_PRODUCT_EXTRA
            $this->insertProductExtra($id,$data);

            $this->commit();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            parent::writeLog(__CLASS__, __METHOD__, $e);
            return false;
        }
	}

	private function insertMstProduct($input){
	    $data = array();

	    //商品名称
        $data["product_name"] = isset($input["productName"])?$input["productName"]:"";
        //商品番号
        $data["product_no"] = isset($input["productNo"])?$input["productNo"]:"";
        //メーカー品番
        $data["maker_product_no"] = isset($input["makerProductNo"])?$input["makerProductNo"]:"";
        //仕入れ先
        $data["supplier_code"] = isset($input["supplierCode"])?$input["supplierCode"]:"";
        //商品説明
        $data["product_brief"]= isset($input["productBrief"])?$input["productBrief"]:"";
        //送料
        if($input["shippingCheck"] == "1") {
            $data["shipping_display_flag"] = "1";
            if($input["shipping"] == "2"){
                $data["shipping_class"] = $input["shipping"];
                $data["shipping_fee"] = isset($input["shippingValue"])?$input["shippingValue"]:"0";
            } else{
                $data["shipping_class"] = $input["shipping"];
                $data["shipping_fee"] = "0";
            }
        } else {
            $data["shipping_display_flag"] = "0";
            $data["shipping_class"] = "0";
            $data["shipping_fee"] = "0";
        }
	    //在庫
        if($input["stockCheck"] == "1") {
            $data["stock_display_flag"] = "1";
            if($input["stock"] == "2"){
                $data["stock_class"] = $input["stock"];
                $data["stock_qty"] = isset($input["stockValue"])?$input["stockValue"]:"0";
            } else{
                $data["stock_class"] = $input["stock"];
                $data["stock_qty"] = "0";
            }
        } else {
            $data["stock_display_flag"] = "0";
            $data["stock_class"] = "0";
            $data["stock_qty"] = "0";
        }
        //写真
        $pathFrom = "";
        if(isset($input["fileName"]) && $input["fileName"] != "") {
            //copy temp to folder
            $fileName = $input["fileName"];
            $pathTempDefault = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_TEMP;
            $pathProductsImageDefault = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH;
            $pathThumbImageDefault = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_THUMB_PATH;		// ADD 20140512 Hieunm

            $clsServ = new Core_Service_MstClassService();
            $pathImage = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_ITEM_CD);
            $pathImageTemp = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_TEMP_ITEM_CD);
            $pathImageThumb = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_THUMB_TEMP_ITEM_CD);	// ADD 20140512 Hieunm

            if (Core_Util_Helper::isEmpty($pathImage)) {
            	$pathImage = $pathProductsImageDefault;
            }

            if (Core_Util_Helper::isEmpty($pathImageTemp)) {
            	$pathImageTemp = $pathTempDefault;
            }
            // ADD 20140512 Hieunm start
            if (Core_Util_Helper::isEmpty($pathImageThumb)) {
            	$pathImageThumb = $pathThumbImageDefault;
            }
            // ADD 20140512 Hieunm end



            //$pathFrom = Zend_Registry::get('img_dir')."/upload/" .$fileName;
            //$pathTo = Zend_Registry::get('img_dir')."/products/".$fileName;

            $pathFrom = Zend_Registry::get('img_dir'). $pathImageTemp .$fileName;
            $pathTo = Zend_Registry::get('img_dir'). $pathImage .$fileName;

            $pathFromFolder = Zend_Registry::get('img_dir'). $pathImageTemp;
            $pathToFolder = Zend_Registry::get('img_dir'). $pathImage;

            if (!file_exists($pathFromFolder)) {
            	mkdir ( $pathFromFolder, 0777 );
            }

            if (!file_exists($pathToFolder)) {
            	mkdir ( $pathToFolder, 0777 );
            }

            if(file_exists($pathFrom)){
                copy($pathFrom, $pathTo);
                $data["image_path"] = $fileName;
            }

            // ADD 20140512 Hieunm start
            $pathThumbFolder = Zend_Registry::get('img_dir'). $pathImageThumb;

            if (!file_exists($pathThumbFolder)) {
            	mkdir ( $pathThumbFolder, 0777 );
            }

            if(file_exists($pathFrom)){
                //Delete image thumb
                $pathThumbFile = $pathThumbFolder . Core_Util_Const::THUMBNAIL_PRE_FIX_NAME_DEFAULT . $fileName;
                if(file_exists($pathThumbFile)){
                    unlink($pathThumbFile);
                }
                Core_Util_Helper::makeThumbnails($pathToFolder, $fileName, $pathThumbFolder);
            }
            // ADD 20140512 Hieunm end
        }

        //Datetime
        $user = Core_Util_Helper::getLoginAdmin();
        $data["insert_user_id"] = $user->getUserId();
        $data["insert_ymd"] = date("Y/m/d",time());
        $data["update_user_id"] = $user->getUserId();
        $data["update_ymd"] = date("Y/m/d",time());

        $productDb = new Core_Db_MstProductDb();
        $id = $productDb->insert($data);

        //Delete image temp
        if($pathFrom != "" && file_exists($pathFrom)){
            unlink($pathFrom);
        }
	    return $id;
	}

	private function insertMstProductPrice($id, $input){
	    $productPriceDb = new Core_Db_MstProductPriceDb();

	    $data = array("product_id"=>$id,
	                  "apply_start_date"=>"",
	                  "price_including_tax"=>"",
				      "price_condition_class"=>"",
				      "quantity_restriction"=>"",
	                  "tax"=>"");


	    if(isset($input["priceApplyStartDate"])) {
            $applyStartDate = $input["priceApplyStartDate"];
            $price = $input["price"];
            $priceIncludingTax = $input["priceIncludingTax"];
            $tax = $input["tax"];
            for ($i = 0; $i < count($applyStartDate); $i++) {
                //適用開始日
                $data["apply_start_date"] = $applyStartDate[$i];
                //見積り価格(税抜き)
                $data["price"] = str_replace(",", "", $price[$i]);
                //見積り価格(税込)
                $data["price_including_tax"] = str_replace(",", "", $priceIncludingTax[$i]);
                //見積り価格(消費税)
                $data["tax"] = str_replace(",", "", $tax[$i]);

                if (isset($input["priceCond_".$i])){
                	$data["price_condition_class"] = $input["priceCond_".$i];
                } else{
                	$data["price_condition_class"] = '0';
                }
               
                if (isset($input["quanRes_".$i])){
                	$data["quantity_restriction"] = $input["quanRes_".$i];	
                } else{
                	$data["quantity_restriction"] = '0';
                }

                $productPriceDb->insert($data);
            }
        }
	    /*//適用開始日
        $data["apply_start_date"] = isset($input["priceApplyStartDate"])?$input["priceApplyStartDate"]:"";
        //見積り価格(税抜き)
        $data["price"] = isset($input["price"])?$input["price"]:"";
        //見積り価格(税込)
        $data["price_including_tax"] = isset($input["priceIncludingTax"])?$input["priceIncludingTax"]:"";
        //見積り価格(消費税)
        $data["tax"] = isset($input["tax"])?$input["tax"]:"";*/
	}

    private function insertMagnification($id,$input){
        $magnificationDb = new Core_Db_MstMagnificationDb();

        $data = array("product_id"=>$id,
	                  "apply_start_date"=>"",
	                  "magnification_point"=>"");

        if(isset($input["pointApplyStartDate"])){
            $pointApplyStartDate = $input["pointApplyStartDate"];
            $magnificationPoint = $input["magnificationPoint"];
            for ($i = 0; $i < count($pointApplyStartDate); $i++) {
                //適用開始日
                $data["apply_start_date"] = $pointApplyStartDate[$i];
                //ポイント倍率
                $data["magnification_point"] = $magnificationPoint[$i];

                $magnificationDb->insert($data);
            }
        }
        /*//商品番号
        $data["product_id"] = $id;
        //適用開始日
        $data["apply_start_date"] = isset($input["pointApplyStartDate"])?$input["pointApplyStartDate"]:"";
        //ポイント倍率
        $data["magnification_point"] = isset($input["magnificationPoint"])?$input["magnificationPoint"]:"";*/
	}

	private function insertProductCategoryJoin($id,$input){
	    $productCategoryJoinDb = new Core_Db_ProductCategoryJoinDb();

        //商品番号
        $data = array('product_id'=>$id,'category_id'=>'');
        //商品カテゴリー(Child)
        if(isset($input["categoryChild"])) {
            $categoryChild =$input["categoryChild"];
    	    foreach ($categoryChild as $catId) {
    	        if($catId != "") {
                    $data['category_id'] = $catId;
                    $productCategoryJoinDb->insert($data);
    	        }
            }
        }
	}

	private function insertProductExtra($id,$input){

		$numOfClassDetail = $input['numclass'];

        $productExtraDb = new Core_Db_MstProductExtraDb();
		for($i = 0; $i < $numOfClassDetail; $i++) {
		    $data = array();
	        $data["product_id"] = $id;
	        $data["product_detail_info"] = isset($input["productDetailInfo_" . $i])?htmlspecialchars($input["productDetailInfo_" . $i]):"";
	        $data["display_order"] = isset($input["productDetailInfoOrder_" . $i])?htmlspecialchars($input["productDetailInfoOrder_" . $i]):"";
	        $data["detail_class"] = isset($input["productDetailClass_" . $i])?htmlspecialchars($input["productDetailClass_" . $i]):"";
	        $productExtraDb->insert($data);
		}
	}

	public function queryProductIds($keyword, $arrConditions = null, $tylelist = null, $user = null){
		try {
			$db 		= $this->productDb;
			$arrProductId = $db->queryProductIds($keyword, $arrConditions, $tylelist, $user);
			return $arrProductId;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function queryProducts($paginatorData, $keyword, $arrConditions = null, $tylelist = null){
		try {
			$db 		= $this->productDb;
			$arrProduct = $db->queryProducts($paginatorData, $keyword, $arrConditions, $tylelist);

			$products = array();
			if ($arrProduct != null) {
				foreach ($arrProduct as $pro) {
					$productdetail = array();
					$product = new Core_Models_MstProduct($pro);
					$productdetail['product'] = $product;
					$productdetail['like'] = Core_Util_Helper::getDataRow($pro, 'like');
					$productdetail['shipping_item_name'] = Core_Util_Helper::getDataRow($pro, 'shipping_item_name');
					$productdetail['stock_item_name'] = Core_Util_Helper::getDataRow($pro, 'stock_item_name');
					$productdetail['price'] = Core_Util_Helper::getDataRow($pro, 'price');
					$productdetail['quotation_id'] = Core_Util_Helper::getDataRow($pro, 'quotation_id');
					$productdetail['price1'] = Core_Util_Helper::getDataRow($pro, 'price1');
					// ADD 20140416 Hieunm start
					$productdetail['status'] = Core_Util_Helper::getDataRow($pro, 'status');
					$productdetail['is_valid_until_date'] = Core_Util_Helper::getDataRow($pro, 'is_valid_until_date');
					$productdetail['price_condition_class'] = Core_Util_Helper::getDataRow($pro, 'price_condition_class');
					$productdetail['quantity_restriction'] = Core_Util_Helper::getDataRow($pro, 'quantity_restriction');
					// ADD 20140416 Hieunm end
					$products[] = $productdetail;
				}
			}
			return $products;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	public function queryProductsRelation($product_id) {
		try {
			$db 		= $this->productDb;
			$arrProduct = $db->queryProductsRelation($product_id);

			$products = array();
			if ($arrProduct != null) {
				foreach ($arrProduct as $pro) {
					$product = new Core_Models_MstProduct($pro);
					$products[] = $product;
				}
			}
			return $products;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function queryProduct($productid){
		try {
			$db 		= $this->productDb;
			$proExtraDb = new Core_Db_MstProductExtraDb();
			$arrproduct = $db->queryProduct($productid);
			$arrresult = array();
			if ($arrproduct != null && $arrproduct[0]['product_id'] != null) {
				foreach ($arrproduct as $mproduct){
					$productdetail = array();
					$product = new Core_Models_MstProduct($mproduct);
					$arrresult['product'] = $product;
					$arrresult['like'] = Core_Util_Helper::getDataRow($mproduct, 'like');
					$arrresult['shipping_item_name'] = Core_Util_Helper::getDataRow($mproduct, 'shipping_item_name');
					$arrresult['stock_item_name'] = Core_Util_Helper::getDataRow($mproduct, 'stock_item_name');
					$arrresult['price'] = Core_Util_Helper::getDataRow($mproduct, 'price');
					$arrresult['quotation_id'] = Core_Util_Helper::getDataRow($mproduct, 'quotation_id');
					$arrresult['price1'] = Core_Util_Helper::getDataRow($mproduct, 'price1');
					// ADD 20140416 Hieunm start
					$arrresult['status'] = Core_Util_Helper::getDataRow($mproduct, 'status');
					$arrresult['is_valid_until_date'] = Core_Util_Helper::getDataRow($mproduct, 'is_valid_until_date');
					// ADD 20140416 Hieunm end
					$productdetail['product_detail_info'] = Core_Util_Helper::getDataRow($mproduct, 'product_detail_info');
					$productdetail['detail_class_name'] = Core_Util_Helper::getDataRow($mproduct, 'detail_class_name');
					
					$arrresult['price_condition_class'] = Core_Util_Helper::getDataRow($mproduct, 'price_condition_class');
					$arrresult['quantity_restriction'] = Core_Util_Helper::getDataRow($mproduct, 'quantity_restriction');
					
					$arrProductExtra = $proExtraDb->getByProductId($productid);

					$mstClassServ = new Core_Service_MstClassService();
					/* @var $productExtra Core_Models_MstProductExtra */
					foreach ($arrProductExtra as $key => $productExtra) {
						if (Core_Util_Helper::isNotEmpty($productExtra->getDetailClass()) ) {
							$itemName = $mstClassServ->getItemName(Core_Util_Const::ITEM_TYPE_PRODUCT_DETAIL, $productExtra->getDetailClass());
							$productExtra->setDetailClassLabel($itemName);
							$arrProductExtra[$key] = $productExtra;
						}
					}

					$arrresult['arrProExtra'] = $arrProductExtra;

					$arrresult['extra'][] = $productdetail;
				}
			} else{
				$product = new Core_Models_MstProduct($arrproduct[0]);
				$arrresult['product'] = $product;
				$arrresult['like'] = null;
				$arrresult['quotation_id'] = null;
				$arrresult['price'] = null;
				$arrresult['arrProExtra'] = null;
			}
			return $arrresult;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function updateLike($productid, $tyle){
		try {
			$this->beginTransaction();
			$user = Core_Util_Helper::getLoginUser();
			$favorite = array();
			$db 		= new Core_Db_UserFavoriteProductDb();
			$favorite = $db->updateLike($user->getUserId(), $productid, $tyle);
			$this->commit();
			return $favorite;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function getFeatureProduct($idUserLogin, $limit = Core_Util_Const::ITEMS_FEATURE_PRODUCT){
		try {
			$db = $this->productDb;
			$arrFeaProduct = $db->getFeatureProduct($idUserLogin, $limit);
			return $arrFeaProduct;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function getBestSellProduct($limit = Core_Util_Const::ITEMS_BEST_SELL) {
		try {
			$db = $this->productDb;
			$arrBestSellProduct = $db->getBestSellProduct($limit);
			return $arrBestSellProduct;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function getProductuserLike($idUser, $limit = Core_Util_Const::ITEMS_USER_LIKE) {
		try {
			$db = $this->productDb;
			$arrProductUserLike = $db->getProductUserLike($idUser, $limit);
			return $arrProductUserLike;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

    public function getByProductId($id,$checkStartDate=false){
	    $data = array(
	    			"productId"=>$id,
	                "productName"=>"",
	                "productNo"=>"",
	                "productBrief"=>"",
	                "imagePath"=>"",
	                "prices"=>array(),
	                "shippingCheck"=>"",
	                "shipping"=>"",
	                "shippingValue"=>"",
	                "stockCheck"=>"",
	                "stock"=>"",
	                "stockValue"=>"",
	                "points"=>array(),
	                "categories"=>array(),
	                "productDetailInfo"=>array(),
	    			"supplierCode"=>"",
	    			"makerProductNo"=>""
	    		);

	    #商品マスタ
	    $productDb = new Core_Db_MstProductDb();
        $res = $productDb->getProductById($id);

        if($res){
            //商品名称
            $data["productName"] = $res->getProductName();
            //商品番号
            $data["productNo"] = $res->getProductNo();
            //商品説明
            $data["productBrief"] = $res->getProductBrief();
            //写真
            $data["imagePath"] = $res->getImagePath();
            //送料
            $data["shippingCheck"] = $res->getShippingDisplayFlag();
            $data["shipping"] = $res->getShippingClass();
            $data["shippingValue"] = $res->getShippingFee();
            //在庫
            $data["stockCheck"] = $res->getStockDisplayFlag();
            $data["stock"] = $res->getStockClass();
            $data["stockValue"] = $res->getStockQty();
            $data["makerProductNo"] = $res->getMakerProductNo();
            $data["supplierCode"] = $res->getSupplierCode();

        } else {
            return null;
        }

        #商品単価マスタ
        $productPriceDb = new Core_Db_MstProductPriceDb();

        //標準価格
        $res = $productPriceDb->getAllByProductId($id,$checkStartDate);
        if($res){
            $prices = array();
            foreach ($res as $item) {
                $subItem = array();
                $subItem["priceApplyStartDate"] = $item->getApplyStartDate();
                $subItem["price"] = $item->getPrice();
                $subItem["priceIncludingTax"] = $item->getPriceIncludingTax();
                $subItem["tax"] = $item->getTax();
                $subItem["priceConditionClass"] = $item->getPriceConditionClass();
                $subItem["quantityRestriction"] = $item->getQuantityRestriction();

                $prices[] = $subItem;
            }
            $data["prices"] = $prices;
        }

        #ポイント倍率マスタ
        $magnificationDb = new Core_Db_MstMagnificationDb();

        //ポイント倍率
        $res = $magnificationDb->getAllByProductId($id,$checkStartDate);
        if($res){
            $points = array();
            foreach ($res as $item) {
                $subItem = array();
                $subItem["pointApplyStartDate"] = $item->getApplyStartDate();
                $subItem["magnificationPoint"] = $item->getMagnificationPoint();

                $points[] = $subItem;
            }
            $data["points"] = $points;
        }

        #商品カテゴリーマスタ
        $categoryDb = new Core_Db_MstCategoryDb();
        $categoryProductDb = new Core_Db_ProductCategoryJoinDb();

        //商品カテゴリー
        $res = $categoryProductDb->getAllByProductId($id);
        if($res){
            $categories = array();
            foreach ($res as $item) {
              	$subItem = array();
                //category child
                $objCategoryChild = $categoryDb->getByCategoryId($item->getCategoryId());
                if ($objCategoryChild !== null && $objCategoryChild !== false) {
	                $subItem["categoryChild"] =  $item->getCategoryId();
	                $subItem["categoryChildName"] = $objCategoryChild->getCategoryName();
	                //category parent: when occurring errors related the next row, 
	                // please check if the category that the current product belongs to is parent category
	                $objCategoryParent = $categoryDb->getByCategoryId($objCategoryChild->getParentId());
	                if ($objCategoryParent === null || $objCategoryParent === false) {
		                $subItem["categoryParent"] = null;
		                $subItem["categoryParentName"] = null;
		                $subItem["categoryChildComboData"] = null;
	                } else {
	                	$subItem["categoryParent"] = $objCategoryChild->getParentId();
	                	$subItem["categoryParentName"] = $objCategoryParent->getCategoryName();
	                	$subItem["categoryChildComboData"] = $categoryDb->getByParentId($objCategoryChild->getParentId());
	                }
                } else {
                	$subItem["categoryChild"] =  null;
                	$subItem["categoryChildName"] = null;
                	//category parent
                	$subItem["categoryParent"] = null;
                	$subItem["categoryParentName"] = null;
                	$subItem["categoryChildComboData"] = null;
                }

                $categories[] = $subItem;
            }
            $data["categories"] = $categories;
        }

        #商品詳細説明マスタ
        $productExtraDb = new Core_Db_MstProductExtraDb();

        //詳細説明
        $res = $productExtraDb->getByProductIdForAdmin($id);
        $mstClassServ = new Core_Service_MstClassService();
        if($res){
        	/* @var $productExtra Core_Models_MstProductExtra */
        	foreach ($res as $key => $productExtra) {
        		if (Core_Util_Helper::isNotEmpty($productExtra->getDetailClass()) ) {
	        		$itemName = $mstClassServ->getItemName(Core_Util_Const::ITEM_TYPE_PRODUCT_DETAIL, $productExtra->getDetailClass());
	        		$productExtra->setDetailClassLabel($itemName);
	        		$res[$key] = $productExtra;
        		}
        	}
            $data["productDetailInfo"] = $res;
        }
        
        $supplierCode = $data["supplierCode"];
        if ($supplierCode == null || $supplierCode==""){
        	$supplierCode="0";
        }
        $data["itemName"] = $mstClassServ->getItemName(Core_Util_Const::SUPPLIER_ITEM_TYPE, $supplierCode);
		
        return $data;
	}

	public function deleteByProductId($id){
	    try {
            $this->beginTransaction();
            //Update 商品マスタ MST_PRODUCT
            $this->deleteMstProduct($id);
            //Delete 商品単価マスタ MST_PRODUCT_PRICE
            $this->deleteMstProductPrice($id);
            //Delete ポイント倍率マスタ MST_MAGNIFICATION
            $this->deleteMagnification($id);
            //Delete 商品カテゴリーマスタ PRODUCT_CATEGORY_JOIN
            $this->deleteProductCategoryJoin($id);
            //Delete 商品詳細説明マスタ MST_PRODUCT_EXTRA
            $this->deleteProductExtra($id);

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            parent::writeLog(__CLASS__, __METHOD__, $e);
            return false;
        }
	}

	private function deleteMstProduct($id){
        $productDb = new Core_Db_MstProductDb();
		$data  = array("delete_flg" => 1);
		$where  = array("product_id = ?" => $id);

		return $productDb->update($data, $where);
	}

	private function deleteMstProductPrice($id){
        $productPriceDb = new Core_Db_MstProductPriceDb();
        $where  = array("product_id = ?" => $id);

        return $productPriceDb->delete($where);
	}

	private function deleteMagnification($id){
        $magnificationDb = new Core_Db_MstMagnificationDb();
        $where  = array("product_id = ?" => $id);

        return $magnificationDb->delete($where);
	}

	private function deleteProductCategoryJoin($id){
        $productCategoryJoinDb = new Core_Db_ProductCategoryJoinDb();
        $where  = array("product_id = ?" => $id);

        return $productCategoryJoinDb->delete($where);
	}

	private function deleteProductExtra($id){
	    $productExtraDb = new Core_Db_MstProductExtraDb();
	    $where  = array("product_id = ?" => $id);

        return $productExtraDb->delete($where);
	}

	public function getAllProductAvailable($keyword,$conditionkeys,$paginatorData){
	    $db = $this->productDb;
	    $res = $db->getListProduct($keyword,$conditionkeys,$paginatorData);
	    $data = array();
	    foreach ($res as $item){
	        $data[] = $this->getByProductId($item["product_id"],true);
	    }
	    return $data;
	}

    public function updateProduct($data){
	    try {
	        $id = $data["productId"];

            $this->beginTransaction();
            //Update  商品マスタ MST_PRODUCT
            $this->updateMstProduct($id,$data);
            //Update  商品単価マスタ MST_PRODUCT_PRICE
            $this->deleteMstProductPrice($id);
            $this->insertMstProductPrice($id,$data);
            //Update  ポイント倍率マスタ MST_MAGNIFICATION
            $this->deleteMagnification($id);
            $this->insertMagnification($id,$data);
            //Update 商品カテゴリーマスタ PRODUCT_CATEGORY_JOIN
            if(isset($data["categoryChild"])) {
                $this->deleteProductCategoryJoin($id);
                $this->insertProductCategoryJoin($id,$data);
            }
            //Update 商品詳細説明マスタ MST_PRODUCT_EXTRA
            $this->deleteProductExtra($id);
            $this->insertProductExtra($id,$data);

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            parent::writeLog(__CLASS__, __METHOD__, $e);
            return false;
        }
	}

	private function updateMstProduct($id,$input){
        $data = array();

	    //商品名称
        $data["product_name"] = isset($input["productName"])?$input["productName"]:"";
        //商品番号
        $data["product_no"] = isset($input["productNo"])?$input["productNo"]:"";
        //メーカー品番
        $data["maker_product_no"] = isset($input["makerProductNo"])?$input["makerProductNo"]:"";
        //仕入れ先
        $data["supplier_code"] = isset($input["supplierCode"])?$input["supplierCode"]:"";
        //商品説明
        $data["product_brief"]= isset($input["productBrief"])?$input["productBrief"]:"";
        //送料
        if($input["shippingCheck"] == "1") {
            $data["shipping_display_flag"] = "1";
            if(isset($input["shipping"]) && $input["shipping"] == "2"){
                $data["shipping_class"] = $input["shipping"];
                $data["shipping_fee"] = isset($input["shippingValue"])?$input["shippingValue"]:"0";
            } else{
                //$data["shipping_class"] = "0";
                $data["shipping_class"] = $input["shipping"];
                $data["shipping_fee"] = "0";
            }
        }else{
            $data["shipping_display_flag"] = "0";
            $data["shipping_class"] = "0";
            $data["shipping_fee"] = "0";
        }
	    //在庫
        if($input["stockCheck"] == "1") {
            $data["stock_display_flag"] = "1";
            if(isset($input["stock"]) && $input["stock"] == "2"){
                $data["stock_class"] = $input["stock"];
                $data["stock_qty"] = isset($input["stockValue"])?$input["stockValue"]:"0";
            } else{
                //$data["stock_class"] = "0";
            	$data["stock_class"] = $input["stock"];
                $data["stock_qty"] = "0";
            }
        }else {
            $data["stock_display_flag"] = "0";
            $data["stock_class"] = "0";
            $data["stock_qty"] = "0";
        }
        //写真
        $pathFrom = "";
        if(isset($input["fileName"]) && $input["fileName"] != "") {
            //copy temp to folder
            $fileName = $input["fileName"];

            $pathTempDefault = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_TEMP;
            $pathProductsImageDefault = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH;
            $pathThumbImageDefault = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_THUMB_PATH;		// ADD 20140512 Hieunm

            $clsServ = new Core_Service_MstClassService();
            $pathImage = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_ITEM_CD);
            $pathImageTemp = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_TEMP_ITEM_CD);
            $pathImageThumb = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_THUMB_TEMP_ITEM_CD);	// ADD 20140512 Hieunm

            if (Core_Util_Helper::isEmpty($pathImage)) {
            	$pathImage = $pathProductsImageDefault;
            }

            if (Core_Util_Helper::isEmpty($pathImageTemp)) {
            	$pathImageTemp = $pathTempDefault;
            }
            // ADD 20140512 Hieunm start
            if (Core_Util_Helper::isEmpty($pathImageThumb)) {
            	$pathImageThumb = $pathThumbImageDefault;
            }
            // ADD 20140512 Hieunm end

            $pathFrom = Zend_Registry::get('img_dir'). $pathImageTemp .$fileName;
            $pathTo = Zend_Registry::get('img_dir'). $pathImage .$fileName;

            $pathFromFolder = Zend_Registry::get('img_dir'). $pathImageTemp;
            $pathToFolder = Zend_Registry::get('img_dir'). $pathImage;

            if (!file_exists($pathFromFolder)) {
            	mkdir ( $pathFromFolder, 0777 );
            }

            if (!file_exists($pathToFolder)) {
            	mkdir ( $pathToFolder, 0777 );
            }

            if(file_exists($pathFrom)){
                copy($pathFrom, $pathTo);
                $data["image_path"] = $fileName;
            }

            // ADD 20140512 Hieunm start
            $pathThumbFolder = Zend_Registry::get('img_dir'). $pathImageThumb;

            if (!file_exists($pathThumbFolder)) {
            	mkdir ( $pathThumbFolder, 0777 );
            }

            if(file_exists($pathFrom)){
                //Delete image thumb
                $pathThumbFile = $pathThumbFolder . Core_Util_Const::THUMBNAIL_PRE_FIX_NAME_DEFAULT . $fileName;
                if(file_exists($pathThumbFile)){
                    unlink($pathThumbFile);
                }
                Core_Util_Helper::makeThumbnails($pathToFolder, $fileName, $pathThumbFolder);
            }
            // ADD 20140512 Hieunm end
        }
        //Datetime
        $data["update_user_id"] = Core_Util_Helper::getIdAdminLogin();
        $data["update_ymd"] = date("Y/m/d",time());

        $where = array("product_id = ?"=>$id);

        $productDb = new Core_Db_MstProductDb();
        //Core_Util_LocalLog::writeLog("----------------");
        //Core_Util_LocalLog::writeLog("update product : " . $id);
        //Core_Util_LocalLog::writeLog($data);
        $count = $productDb->update($data, $where);

        //Delete image temp
        if($pathFrom != "" && file_exists($pathFrom)){
            unlink($pathFrom);
        }

	    return $count;
	}

	public function getFeatureProductOfSale($saleUserId) {
		try {
			$db = $this->productDb;
			$arrData = $db->getFeatureProductOfSale($saleUserId);
			return $arrData;
		} catch ( Exception $e ) {
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}

	public function insertCategory($data){
	    try {
			$listProductId = array();
			$categoryId = "";

			//Check input data
	        if(isset($data["chkInsertCategory"]) && count($data["chkInsertCategory"])>0){
			    $listProductId = $data["chkInsertCategory"];
			} else {
			    return;
			}

			if(isset($data["categoryChild"]) && count($data["categoryChild"])>0){
			    $categoryId = $data["categoryChild"][0];
			} else {
			    return;
			}

			//Execute insert
			$productCategoryJoinDb = new Core_Db_ProductCategoryJoinDb();
			foreach ($listProductId as $productId){
                //Get all category by ProductId
                $arrCategory = $productCategoryJoinDb->getAllByProductId($productId);
                if(isset($arrCategory)&&count($arrCategory)>0){
                    $isExisted = false;
                    //Check exist
                    foreach ($arrCategory as $category){
                        if($category->getCategoryId()== $categoryId){
                            $isExisted = true;
                            break;
                        }
                    }
                    //Insert
                    if(!$isExisted){
                        $data = array('product_id'=>$productId,'category_id'=>$categoryId);
                        $productCategoryJoinDb->insert($data);
                    }
                }else{
                    //Insert
                    $data = array('product_id'=>$productId,'category_id'=>$categoryId);
                    $productCategoryJoinDb->insert($data);
                }
			}
			return true;
		} catch ( Exception $e ) {
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}

	public function addFeatureProduct($productId, $idUser) {
		$db = new Core_Db_FeaturedProductDb();
		try {
			$this->beginTransaction();
			$featureProduct = new Core_Models_FeaturedProduct();
			$featureProduct->setProductId($productId);
			$featureProduct->setUserId($idUser);
			$id = $db->insertRecord($featureProduct);
			$this->commit();
			return $id > 0;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}

	public function removeFeatureProduct($productId, $idUser) {
		$db = new Core_Db_FeaturedProductDb();
		try {
			$this->beginTransaction();
			$featureProduct = new Core_Models_FeaturedProduct();
			$featureProduct->setProductId($productId);
			$featureProduct->setUserId($idUser);
			$id = $db->delete(array("user_id = ? " => $idUser, 'product_id = ? ' => $productId));
			$this->commit();
			return $id > 0;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}

	public function isAddedFeatured($productId, $idUser) {
		try {
			$db = new Core_Db_FeaturedProductDb();
			$res = $db->isAddedFeatured($productId, $idUser);
			return $res;
		} catch (Exception $e) {
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}
	
	//Edit 20140627 Hungnh START
// 	public function queryProductForExportCSV($arrId) {
// 		try {
// 			$db = new Core_Db_MstProductDb();
// 			$res = $db->getListProductForExportCSV($arrId);
// 			return $res;
// 		} catch (Exception $e) {
// 			parent::writeLog ( __CLASS__, __METHOD__, $e );
// 			return false;
// 		}
// 	}
	
	public function queryProductForExportCSV($keyword = null, $arrConditions = null, $tylelist = null) {
		try {
			$db = new Core_Db_MstProductDb();
			$res = $db->getListProductForExportCSV($keyword, $arrConditions , $tylelist);
			return $res;
		} catch (Exception $e) {
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}
	//Edit 20140627 Hungnh END
	
	public function checkProductNoExist($productNo) {
		try {
			$db = new Core_Db_MstProductDb();
			$res = $db->checkProductNoExist($productNo);
			return $res;
		} catch (Exception $e) {
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}
	
	public function checkValidParentCateAndChildCate($parentCateName, $childCateName) {
	try {
		
			if (Core_Util_Helper::isEmpty($parentCateName) || Core_Util_Helper::isEmpty($childCateName)) {
				return false;
			}
			
			$db = new Core_Db_MstCategoryDb();
			$parentCategoryId = $db->getParentIdByParentName($parentCateName);
			
			if ($parentCategoryId === null) {
				return false;
			}
			$childCategory = $db->getCategoryByParentIdAndName($parentCategoryId, $childCateName);
			if ($childCategory === false || $childCategory === null) {
				return false;
			}
			
			$currentRow = $childCategory->current();
			$childCategoryId = Core_Util_Helper::getDataRow($currentRow, 'category_id');
			
			return $childCategoryId;
		} catch (Exception $e) {
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}
	
	public function getCategoryIdByName($categoryName) {
		try {
		
			$db = new Core_Db_MstCategoryDb();
			$parentCategory = $db->getCategoryByName($categoryName);
			if ($parentCategory === false || $parentCategory === null) {
				return false;
			} else {
				$currentRow = $parentCategory->current();
				$categoryId = Core_Util_Helper::getDataRow($currentRow, 'category_id');
				return $categoryId;
			}
				
		} catch (Exception $e) {
			parent::writeLog ( __CLASS__, __METHOD__, $e );
			return false;
		}
	}
	
	public function saveArrProduct($arrOfRows) {
		$arrError = array();
		try {
			$csvAgent = new Core_Models_CsvAgent();
			
			$arrProductNoAdded = array();
			$arrProductNoAndProductId = array();
			$currentProductNo = null;
			$curretnProductId = null;
			
			$error = "";
			$line = 0;
			$this->beginTransaction();
			
			$productJoinDb = new Core_Db_ProductCategoryJoinDb();
			$productDb = new Core_Db_MstProductDb();
			$productPriceDb = new Core_Db_MstProductPriceDb();
			$maginificationDb = new Core_Db_MstMagnificationDb();
			
			foreach ($arrOfRows as $key => $row) {
				$line++;
				
				$arrOfRow = $csvAgent->convertCsvRowToArray($row);
				
				$productName			= str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
													Core_Util_Const::DOUBLE_QUOTE,
													$arrOfRow[0]);
				$productNo				= str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
													Core_Util_Const::DOUBLE_QUOTE,
													$arrOfRow[1]);
				$productBrief			= str_replace(Core_Util_Const::REPLACEMENT_FOR_NEW_LINE_ANOTATION,
													Core_Util_Const::NEW_LINE_ANOTATION, 
													$arrOfRow[2]);
				$productBrief			= str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
													Core_Util_Const::DOUBLE_QUOTE,
													$productBrief);
					
				$price					= $arrOfRow[3];
				$applyStartDate			= str_replace(Core_Util_Const::DATE_SEPARATOR_IO_CSV, 
													Core_Util_Const::DATE_SEPARATOR_PROCESS_IN, 
													$arrOfRow[4]);
													
				$magnificationPoint		= $arrOfRow[5];
				$pointApplyStartDate	= str_replace(Core_Util_Const::DATE_SEPARATOR_IO_CSV, 
													Core_Util_Const::DATE_SEPARATOR_PROCESS_IN, 
													$arrOfRow[6]);
					
				$shippingDisplayFlag	= $arrOfRow[7];
				$shippingClassParts 	= explode(":", $arrOfRow[8]);
				$shippingClass			= $shippingClassParts[0];
				$shippingFee			= $arrOfRow[9];
					
				$stockDisplayFlag		= $arrOfRow[10];
				$stockClassParts		= explode(":", $arrOfRow[11]);
				$stockClass				= $stockClassParts[0];
				$stockQty				= $arrOfRow[12];

				
				$supplierName			= str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
													Core_Util_Const::DOUBLE_QUOTE,
													$arrOfRow[13]);
				
				$makerProductNo				= str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
									Core_Util_Const::DOUBLE_QUOTE,	
									$arrOfRow[14]);
				
				$parentCategoryName		= str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
													Core_Util_Const::DOUBLE_QUOTE,
													$arrOfRow[15]);
				$categoryName			= str_replace(Core_Util_Const::DOUBLE_DOUBLE_QUOTE,
													Core_Util_Const::DOUBLE_QUOTE,
													$arrOfRow[16]);
					
				// is not exist in array added
				if (!Core_Util_Helper::isInArray($arrProductNoAdded, $productNo)) {
					
					// check parent category and child category
					$idChildCategory = $this->checkValidParentCateAndChildCate($parentCategoryName, $categoryName);
					
					if ($idChildCategory === null || $idChildCategory === false) {
						$error .= "Line $line : 親カテゴリー名称 and 子カテゴリー名称 is not exist in DB";
						$arrError[] = $line;
						Core_Util_LocalLog::writeLog($error);
						break;
					}
					
					//$idShipping = $this->getIdShipping($shippingClass);
					//if ($idShipping === false) {
					//	$error .= "Line $line : 送料区分(込/別)($shippingClass) is not exist in DB";
					//	break;
					//}
					$idShipping = $shippingClass;
					//$idStock = $this->getIdStock($stockClass);
					//if ($idStock === false) {
					//	$error .= "Line $line : 在庫表示フラグ($idStock) is not exist in DB";
					//	break;
					//}
					$idStock = $stockClass; 
					
					$idSupplier = $this->getIdClass(Core_Util_Const::SUPPLIER_ITEM_TYPE, $supplierName);	
					if ($idSupplier === false) {			
						$error .= "Line $line : 仕入れ先 is not exist in DB";
						$arrError[] = $line;
						Core_Util_LocalLog::writeLog($error);
						break;
					}
					
					// save new product
					$product = new Core_Models_MstProduct();
					$product->setProductName($productName);
					$product->setProductNo($productNo);
					$product->setMakerProductNo($makerProductNo);
					$product->setSupplierCode($idSupplier);
					$product->setProductBrief($productBrief);
					if ($shippingDisplayFlag == "ON") {
						$shippingDisplayFlag = "1";
					} else {
						$shippingDisplayFlag = "0";
					}
					$product->setShippingDisplayFlag($shippingDisplayFlag);
					$product->setShippingClass($idShipping);
					$product->setShippingFee($shippingFee);
					if ($stockDisplayFlag == "ON") {
						$stockDisplayFlag = "1";
					} else {
						$stockDisplayFlag = "0";
					}
					$product->setStockDisplayFlag($stockDisplayFlag);
					$product->setStockClass($idStock);
					$product->setStockQty($stockQty);
					$product->setImagePath($productNo . ".jpg");
					$idProduct = $productDb->insertRecord($product);
					$productJoinDb->insert(array('product_id' => $idProduct, 'category_id' => $idChildCategory));
					
					// add product no to array
					$curretnProductId = $idProduct;
					$currentProductNo = $productNo;
					$arrProductNoAdded[] = $currentProductNo;
					$arrProductNoAndProductId[$currentProductNo] = $curretnProductId;
					
				}
				
				$productId = $arrProductNoAndProductId[$productNo];
				$priceStartDate = Core_Util_Helper::formatToMySQLDate($applyStartDate);
				// save price
				$isExistPriceDate = $productPriceDb->isExistPriceDate($productId, $priceStartDate);
				if (!$isExistPriceDate) {
					$productPrice = new Core_Models_MstProductPrice();
					$productPrice->setProductId($productId);
					$productPrice->setApplyStartDate($priceStartDate);
					$productPrice->setPrice($price);
					$productPrice->setPriceConditionClass('0');
					$productPrice->setQuantityRestriction('0');
					$productPriceDb->insertRecord($productPrice);
				}
				
				// save point
				$pointApplyStartDate = Core_Util_Helper::formatToMySQLDate($pointApplyStartDate);
				$isExistMaginDate = $maginificationDb->isExistMaginDate($productId, $pointApplyStartDate);
				if (!$isExistMaginDate) {
					$productPoint = new Core_Models_MstMagnification();
					$productPoint->setProductId($arrProductNoAndProductId[$productNo]);
					$productPoint->setMagnificationPoint($magnificationPoint);
					$productPoint->setApplyStartDate($pointApplyStartDate);
					$maginificationDb->insertRecord($productPoint);
				}
			}
			
			//if (Core_Util_Helper::isEmpty($error)) {
			if (count($arrError) == 0) {
				$this->commit();
				//return true;
			} else {
				$this->rollBack();
				//return $error;
			}
		} catch (Exception $e) {
			$this->rollBack();
			//return $e->getMessage();
		}
		return $arrError;
	}
	
	public function getIdShipping($shippingName) {
		$dbClass = new Core_Db_MstClassDb();
		$mstClass = $dbClass->getMstClassByItemTypeAndItemName(Core_Util_Const::SHIPPING_CLASS, $shippingName);
		if ($mstClass === false || $mstClass === null) {
			return false;
		} else {
			return $mstClass->getItemCd();
		}
	}
	
	public function getIdStock($stockName) {
		$dbClass = new Core_Db_MstClassDb();
		$mstClass = $dbClass->getMstClassByItemTypeAndItemName(Core_Util_Const::STOCK_CLASS, $stockName);
		if ($mstClass === false || $mstClass === null) {
			return false;
		} else {
			return $mstClass->getItemCd();
		}
	}
	
	/**
	 * getProductByCond
	 *
	 * @return array|boolean
	 */
	public function getProductByCond($condition) {
		try {
			$condition[Core_Models_MasterModel::DELETE_FIELD . " = ?"] = '0';
			$db  = $this->productDb;
			$arrProduct = $db->getProductByCond($condition);
			return $arrProduct;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	public function getIdClass($itemType, $name) {
		$dbClass = new Core_Db_MstClassDb();
		$mstClass = $dbClass->getMstClassByItemTypeAndItemName($itemType, $name);
		if ($mstClass === false || $mstClass === null) {
			return false;
		} else {
			return $mstClass->getItemCd();
		}
	}
}