<?php
class Core_Service_QuotationCartInfoService extends Core_Service_Abstract {

	private $quotationCartInfoDb;
	private $quotationInfoDb;
	private $quotationDetailInfoDb;
	private $mstProductDb;

	function __construct() {
		parent::__construct();
		$this->quotationCartInfoDb = new Core_Db_QuotationCartInfoDb();
		$this->quotationInfoDb = new Core_Db_QuotationInfoDb();
		$this->quotationDetailInfoDb = new Core_Db_QuotationDetailInfoDb();
		$this->mstProductDb = new Core_Db_MstProductDb();
	}

	public function updateQuotationCartInfo($productid, $quantity=1){
		try {
			$this->beginTransaction();
			$user = Core_Util_Helper::getLoginUser();
			$quotationcart = new Core_Models_QuotationCartInfo();
			$quotationcart->setUserId($user->getUserId());
			$quotationcart->setProductId($productid);
			$quotationcart->setQuantity($quantity);

			$db 		= new Core_Db_QuotationCartDb();
			$result = $db->getQuotationCartInfo($quotationcart->getUserId(), $quotationcart->getProductId());
			if ($result == false){
				$db->insertData($quotationcart);
			} else {
				$db->updateData($quotationcart);
			}
			$result = $db->getCountProduct($quotationcart->getUserId());
			$this->commit();
			return $result;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	/**
	 *getQuotationCartByUser
	 * @return array|boolean
	 */
	public function getQuotationCartByUser($idUser) {
		try {
			$arrProduct = $this->mstProductDb->getProductInQuotationCartByUserId($idUser);
			return $arrProduct;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	/**
	 *deleteRowInQuotationcart
	 * @return array|boolean
	 */
	public function deleteRowInQuotationcart($idProduct, $idUser) {
		try {
			$this->beginTransaction();
			$db = $this->quotationCartInfoDb;
			$res = $db->deleteQuotationCart($idProduct, $idUser);

			$this->commit();
			return $res;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	/**
	 *insertQuotation
	 * @return array|boolean
	 */
	public function insertQuotation($quotationInfo, $arrQuotationDetail, $idUser) {
		try {
			$this->beginTransaction();
			$this->quotationInfoDb->insertRecord($quotationInfo);
			$lastInsertId = $this->quotationInfoDb->getAdapter()->lastInsertId();

			foreach ($arrQuotationDetail as $quotationDetail){
				$quotationDetail->setQuotationId($lastInsertId);
				$this->quotationDetailInfoDb->insertRecord($quotationDetail, $idUser);
			}

			$this->quotationCartInfoDb->deleteAllQuotation($idUser);
			$this->commit();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
}


