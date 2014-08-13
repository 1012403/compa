<?php
class Core_Db_QuotationInfoDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::QUOTATION_INFO;

	protected $_primary = 'quotation_id';

	protected $_instanceClass = 'Core_Models_QuotationInfo';

	public function getListQuotation($isSelectAll=false,$userName="",$status="",$saleId="",$paginatorData=null){
	    //Select
	    $saleName = "(select usr1.user_name from MST_USER usr1 where usr1.user_id = usr.sales_id) as sale_name";
	    $statusName = "(select cls.item_name from MST_CLASS cls  where cls.item_type LIKE '".Core_Util_Const::QUOTATION_STATUS."' and cls.item_cd = quo.status) as status_name";
	    $query = $this->select()->from(array("quo" =>$this->_name),
                                       array("quo.*","usr.*",$saleName,$statusName));
        $query->setIntegrityCheck(false);
        $query->joinInner(array("usr" => Core_Util_TableNames::MST_USER),"quo.user_id = usr.user_id", null);
		//Condition
		$query->where("quo.delete_flg = ?", '0');
		$query->where("usr.sales_id is not null");
		// ADD 20140425 Hieunm start
		$query->where('usr.delete_flg = ?', Core_Util_Const::DELETE_FLG_0);
		// ADD 20140425 Hieunm end

		if(isset($userName) && $userName!=""){
	        $query->where("LOWER(usr.user_name) LIKE ?","%".strtolower($userName)."%");
	    }
	    if(isset($status) && $status!=""){
	        $query->where("quo.status = ?",$status);
	    }
	    if(isset($saleId) && $saleId!=""){
	        $query->where("usr.sales_id = ?",$saleId);
	    }
	    if(!$isSelectAll){
    	    //Paging
    		if (isset($paginatorData) && $paginatorData['itemCountPerPage'] > 0) {
    			$page     = $paginatorData['currentPage'];
    			$rowCount = $paginatorData['itemCountPerPage'];
    			$query->limitPage($page, $rowCount);
    		}
		}

		//Order
		$query->order("quo.quotation_date_time desc");
		
		$rows = $this->fetchAll($query)->toArray();

		return $rows;
	}

	public function getByQuoteId($id){
		$where = array ();
		$where ['quotation_id  = ?'] = $id;
		$where ['delete_flg  = ?'] = Core_Util_Const::DELETE_FLG_0;

		$res = $this->get ( $where );

		return $res;
	}
}