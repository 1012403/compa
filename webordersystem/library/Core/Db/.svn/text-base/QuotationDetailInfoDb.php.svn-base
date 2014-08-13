<?php
class Core_Db_QuotationDetailInfoDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::QUOTATION_DETAIL_INFO;

	protected $_primary = array('quotation_id', 'detail_no');

	protected $_instanceClass = 'Core_Models_QuotationDetailInfo';

	public function getListQuotationDetail($quoteId){
	    $where = array ();
		$where ['quotation_id  = ?'] = $quoteId;
		$where ['delete_flg  = ?'] = Core_Util_Const::DELETE_FLG_0;
        $order = "detail_no ASC";
		$res = $this->getAll($where,$order);
		return $res;
	}
}












