<?php
class Core_Service_QuotationDetailInfoService extends Core_Service_Abstract {

	private $quotedetaildb;

	function __construct() {
		parent::__construct();
		$this->quotedetaildb = new Core_Db_QuotationDetailInfoDb();
	}

	public function getListQuotationDetail($quoteId,$userId){
	    try {
			$db = $this->quotedetaildb;
			$quoteDetail = $db->getListQuotationDetail($quoteId);

			$data = array();

			foreach ($quoteDetail as $item){
			    $arr = $item->toArray();
                $subItem = array(
			            "detail_no" => "",
                        "product_id"=>"",
			            "product_name"=>"",
			            "product_no"=>"",
						"image_path"=>"",
			            "price_product"=>"",
                        "quantity"=>"",
			            "comment"=>"",
			            "price_including_tax"=>"",
			            "quote_price_before"=>"",
                        "valid_until_date"=>""
			        );

			    #見積り明細情報
			    //No.
		        $subItem["detail_no"] = $arr["detail_no"];
		        //ユーザからのコメント
		        $subItem["comment"] = $arr["comment"];
		        //数量
		        $subItem["quantity"] = $arr["quantity"];
		        //見積り価格(税込)
		        $subItem["price_including_tax"] = $arr["price_including_tax"];
		        //見積り価格の有効期限
		        $subItem["valid_until_date"] = $arr["valid_until_date"];
                $subItem["product_id"] = $arr["product_id"];

		        $productId = $arr["product_id"];
		        #商品マスタ
    			$productDb = new Core_Db_MstProductDb();
                $res = $productDb->getProductById($productId);
                if($res){

                    //商品名称
                    $subItem["product_name"] = $res->getProductName();
                    //商品番号
                    $subItem["product_no"] = $res->getProductNo();
                    //写真
                    $subItem["image_path"] = $res->getImagePath();
                }

    			#商品単価マスタ
                //標準価格
                $productPriceDb = new Core_Db_MstProductPriceDb();
                $res = $productPriceDb->getAllByProductId($productId,true);
                if($res){
                    $prices = array();
                    /*@var $item Core_Models_MstProductPrice */
                    foreach ($res as $item) {
                        //標準価格（税込）
                        $subItem["price_product"] = $item->getPrice();
                        break;
                    }
                }

                #ユーザ商品単価マスタ
                $userProductJoinDb = new Core_Db_UserProductJoinDb();
                $res = $userProductJoinDb->getByUserIdProductId($userId,$productId);
                if($res !== null){
                    //前回の見積り価格(税込)
                    $subItem["quote_price_before"] = $res->getPrice();
                }

		        $data[] = $subItem;
			}
			return $data;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);

			return null;
		}
	}

	public function saveQuoteDetail($data,$mode){
	    try {
	        $userId = $data["user_id"];
            $quoteId = $data["quote_id"];
            $listPrice = $data["price"];
            $validDate = $data["valid_until_date"];
            $listProductId = $data["product_id"];
            $listDetailNo = $data["detail_no"];

            foreach ($listPrice as $key=>$price){
                //Update Quote_Detail_Info
                $this->updateQuotationDetailInfo($quoteId, $listDetailNo[$key], $price, $validDate);
                //Insert User_Product_Join
        	    if($mode=="SAVE"){
        	        //Delete
        	        $this->deleteUserProductJoin($userId, $listProductId[$key]);
        	        //Insert
        	        $this->insertUserProductJoin($userId, $listProductId[$key], $price, $validDate);
        	    }
            }

            //Update status of Quote Info
            if($mode=="SAVE"){
                $this->updateQuotationInfo($quoteId, Core_Util_Const::QUOTATION_STATUS_DONE);
            } else {
                $this->updateQuotationInfo($quoteId, Core_Util_Const::QUOTATION_STATUS_TEMP);
            }

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            parent::writeLog(__CLASS__, __METHOD__, $e);
            return false;
        }
	}

	private function updateQuotationInfo($quoteId,$status){
	    $user = Core_Util_Helper::getLoginAdmin();
	    $data = array();

        $data["status"] = $status;
        $data["update_user_id"] = $user->getUserId();
        $data["update_ymd"] = date("Y/m/d",time());

        $where = array("quotation_id = ?"=>$quoteId);

        $db = new Core_Db_QuotationInfoDb();
        $count = $db->update($data, $where);

	    return $count;
	}

	private function updateQuotationDetailInfo($quoteId,$detailNo,$price,$validDate){
        $data = array();

        $data["price_including_tax"] = $price;
        $data["valid_until_date"] = $validDate;

        $where = array("quotation_id = ?"=>$quoteId,"detail_no = ?"=>$detailNo);

        $db = new Core_Db_QuotationDetailInfoDb();
        $count = $db->update($data, $where);

	    return $count;
	}

    private function deleteUserProductJoin($userId,$productId){
        $db = new Core_Db_UserProductJoinDb();
        $where  = array("user_id = ?" => $userId,"product_id = ?"=>$productId);

        return $db->delete($where);
	}

    private function insertUserProductJoin($userId,$productId,$price,$validDate){
        $data = array();

        $data["user_id"] = $userId;
        $data["product_id"] = $productId;
        $data["valid_until_date"] = $validDate;
        // MOD 20140423 Hieunm start
        //$data["price_including_tax"] = $price;
        $data["price"] = $price;
        // MOD 20140423 Hieunm end

        $db = new Core_Db_UserProductJoinDb();
        $count = $db->insert($data);

	    return $count;
	}
}


