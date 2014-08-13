<?php
class Core_Service_QuotationInfoService extends Core_Service_Abstract {

	private $quotedb;

	function __construct() {
		parent::__construct();
		$this->quotedb = new Core_Db_QuotationInfoDb();
	}

	public function getListQuotation($isSelectAll=false,$userName="",$status="",$saleId="",$paginatorData=null){
	    try {
			$db = $this->quotedb;
			$res = $db->getListQuotation($isSelectAll,$userName,$status,$saleId,$paginatorData);

			return $res;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);

			return null;
		}
	}

	/**
	 * 
	 * @param string $quoteId
	 * @return Core_Models_QuotationInfo
	 */
	public function getByQuoteId($quoteId){
	     try {
			$db = $this->quotedb;
			$res = $db->getByQuoteId($quoteId);

			return $res;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);

			return null;
		}

	}
}


