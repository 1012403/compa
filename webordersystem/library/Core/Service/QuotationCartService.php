<?php
class Core_Service_QuotationCartService extends Core_Service_Abstract {
	
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
}


