<?php
/**
 * 
 * @author Nguyen
 *
 */
class Core_Service_UserProductService extends Core_Service_Abstract {
	
	private $userproductDb;

	public function __construct() {
		parent::__construct();
		$this->userproductDb = new Core_Db_UserProductJoinDb();
	}
	

	public function queryAll($paginatorData, $user_name=null, $login_username=null, $product_name=null) {
		try {
			$db = $this->userproductDb;
			$arrup = $db->queryAll($paginatorData, $user_name, $login_username, $product_name);
			$arrresult = array();
			if ($arrup != null) {
				foreach ($arrup as $userp){
					$user_detail = array();
					$user_detail['user'] = new Core_Models_MstUser($userp);
					$upj = new Core_Models_UserProductJoin($userp);
					$upj->setValidUntilDate(str_replace('-', '/', $upj->getValidUntilDate()));
					$user_detail['upj']= $upj;
					$user_detail['product'] = new Core_Models_MstProduct($userp);
					//$user_detail['mpp_price_including_tax'] = Core_Util_Helper::getDataRow($userp, 'mpp_price_including_tax');
					//$user_detail['price_including_tax_format'] = Core_Util_Helper::getDataRow($userp, 'price_including_tax_format');
					$user_detail['mpp_price'] = Core_Util_Helper::getDataRow($userp, 'mpp_price');
					$user_detail['price_format'] = Core_Util_Helper::getDataRow($userp, 'price_format');
					$arrresult[] = $user_detail;
				}
			}
			return $arrresult;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function queryAllExport( $user_name=null, $login_username=null, $product_name=null) {
		try {
			$db = $this->userproductDb;
			$arrup = $db->queryAllExport($user_name, $login_username, $product_name);
			//$arrresult = array();
			/*if ($arrup != null) {
				foreach ($arrup as $userp){
					$user_detail = array();
					$user_detail['user'] = new Core_Models_MstUser($userp);
					$upj = new Core_Models_UserProductJoin($userp);
					$upj->setValidUntilDate(str_replace('-', '/', $upj->getValidUntilDate()));
					$user_detail['upj']= $upj;
					$user_detail['product'] = new Core_Models_MstProduct($userp);
					$user_detail['mpp_price'] = Core_Util_Helper::getDataRow($userp, 'mpp_price');
					$user_detail['price_format'] = Core_Util_Helper::getDataRow($userp, 'price_format');
					$arrresult[] = $user_detail;
				}
			}*/
			return $arrup;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}
	
	
	
	public function queryCountAll($user_name=null, $login_username=null, $product_name=null) {
		try {
			$db = $this->userproductDb;
			$countuserprice = $db->queryCountAll($user_name, $login_username, $product_name);
			return Core_Util_Helper::getDataRow($countuserprice, 'count');
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return 0;
		}
	}

	public function updatePrice($data) {
		try {
			$this->beginTransaction();
			$db = $this->userproductDb;
			$where = "user_id = ".$data['user_id'];
			$where .= " and product_id = ".$data['product_id'];
			$where .= " and valid_until_date = ".$data['valid_until_date'];
			$db->update($data, $where);
			$this->commit();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	public function getUserProduct(){
		try {
			$db = $this->userproductDb;
			$userProd= $db->getUserProduct();
			return $userProd;
		} catch (Exception $e) {
			return null;
		}
	}
	
	public function insertArrUserProd($arrMstUser){
		//Core_Util_LocalLog::writeLog("save user product");
		$errUserPro = "";
		try {
			$db = $this->userproductDb;
			$this->beginTransaction();
			/* @var $mstUser Core_Models_UserProductJoin */
			foreach ($arrMstUser as $key => $mstUser) {
				$intline = $key+1;
				$userProduct = new Core_Models_UserProductJoin($mstUser);
				$loginUser = $mstUser->getLoginUsername();
				$productNo = $mstUser->getProductNo();
				$validUntilDate= $mstUser->getValidUntilDate();
				
				$serUSer = new Core_Db_MstUserDb();
				$userId = $serUSer->getUserIdByUserNAme($loginUser);
				
				$dbPro = new Core_Db_MstProductDb();
				$productId = $dbPro->getidProdByProductNo($productNo);
				
				//check date yyyymmdd
				$date = new DateTime($validUntilDate);
				$date = date_format($date, 'Y/m/d');
				$dbUSerProd = new Core_Db_UserProductJoinDb();
				$resulCheck = $dbUSerProd->checkExistUserProdDate($userId, $productId, $date);
				
				//check userid exist
				if ($userId === 0){ 
					if ($errUserPro !== ""){
						$errUserPro .= ", ";
					}
					$errUserPro .= $intline;
				}
				//check product id exist
				elseif ($productId === null){
					if ($errUserPro !== ""){
						$errUserPro .= ", ";
					}
					$errUserPro .= $intline;
				}
				//check exist user prod and date
				elseif ($resulCheck){
					if ($errUserPro !== ""){
						$errUserPro .= ", ";
					}
					$errUserPro .= $intline;
					//Core_Util_LocalLog::writeLog("errUserPro = ".$errUserPro);
				} else {		
					$userProduct->setUserId($userId);
					$userProduct->setProductId($productId);
					$userProduct->setValidUntilDate($date);
					$userProduct->setPrice($mstUser->getPrice());
					$userIdShipping = $db->insertRecord($userProduct);
					//Core_Util_LocalLog::writeLog("errUserPro = ".$errUserPro);
				}
			}
			if ($errUserPro){
				throw new Exception($errUserPro);
			}
			$this->commit();
		} catch (Exception $e) {
			$this->rollBack();
			//parent::writeLog(__CLASS__, __METHOD__, $e);
		}
		return $errUserPro;
	}
}