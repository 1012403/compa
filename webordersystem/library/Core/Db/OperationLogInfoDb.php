<?php
/**
 * Database class of Operation log
 * @author nhanlt
 *
 */
class Core_Db_OperationLogInfoDb extends Core_Db_Persistent {
	protected $_name = 'OPERATE_LOG_INFO';
	protected $_primary = 'log_id';
	protected $_instanceClass = 'Core_Models_OperationLogInfo';

	/**
	 * saveLog
	 * @param int $userId
	 * @param string $logKind
	 * @param string $operationContent
	 * @param string $operationDetail
	 * @param int $idInsert
	 * @return boolean
	 */
	public function saveLog($userId, $logKind, $operationContent, $operationDetail) {
		$operationLogInfo = new Core_Models_OperationLogInfo();
		$operationLogInfo->setUserId($userId);
		$operationLogInfo->setLogKind($logKind);
		$operationLogInfo->setOperateContent($operationContent);
		$operationLogInfo->setOperateDetail($operationDetail);
		$operationLogInfo->setLogDateTime(Core_Util_Helper::getCurrentMysqlTime());
		$arr = $operationLogInfo->toArray();
		$arr['log_date_time'] = new Zend_Db_Expr("NOW()");
		$idInserted = $this->insert($arr);
		return $idInserted !== null;
	}
	
	/**
	 * @return getLogInfo
	 */
/* 	public function getLogInfo2(){
		$query = $this->select()->from(array("log" =>$this->_name));
		$query->setIntegrityCheck(false);
		
		$query->joinInner(array("user" => Core_Util_TableNames::MST_USER),
				"log.user_id = user.user_id",
				array("user.user_name"));
		
		$rows = $this->fetchAll($query)->toArray();
		return $rows;
	} */

	public function getLogInfo($dateStart,$dateEnd, $user, $operContent, $operDetail,$paginatorData){
		$query = $this->select()->from(array("log" =>$this->_name));
		$query->setIntegrityCheck(false);
	
		$query->joinInner(array("mUser" => Core_Util_TableNames::MST_USER),
				"log.user_id = mUser.user_id",
				array("mUser.user_name"));

		if ($dateStart!=null ){
			$dateStart.=' 00:00:00';
		}
		if ($dateEnd!=null ){
			$dateEnd.=' 23:59:59';
		}
		if ($dateStart!=null && $dateEnd!=null){
			$dt = "log.log_date_time between ? and ? ";
			$dt = $this->_db->quoteInto($dt, $dateStart, null, 1);
			$dt = $this->_db->quoteInto($dt, $dateEnd, null, 1);
			$query->where($dt);
		}
		if ($dateStart!=null ){
			$query->where("log.log_date_time >=?",$dateStart );
		}
		if ($dateEnd!=null ){
			$query->where("log.log_date_time <=?",$dateEnd);
		}
		
		
		if($user!=null){
			$query->where("mUser.user_name like ?","%$user%" );
	
		}
		if($operContent!=null){
			$query->where("log.operate_content like ?","%$operContent%" );
		}
		if($operDetail!=null){
			$query->where("log.operate_detail like ?", "%$operDetail%" );
		}
		$query->order('log.log_date_time DESC');
		//page
		if ($paginatorData['itemCountPerPage'] > 0) {
			$page     = $paginatorData['currentPage'];
			$rowCount = $paginatorData['itemCountPerPage'];
			$query->limitPage($page, $rowCount);
		}
		$rows = $this->fetchAll($query)->toArray();
		return $rows;
	}
	public function getCountLogInfo($dateStart,$dateEnd, $user, $operContent, $operDetail){
		$query = $this->select()->from(array("log" =>$this->_name),array("count" => "count(*)"));
		$query->setIntegrityCheck(false);
		
		$query->joinInner(array("mUser" => Core_Util_TableNames::MST_USER),
				"log.user_id = mUser.user_id",null);
		
		if ($dateStart!=null && $dateEnd!=null){
			$dt = "log.log_date_time between ? and ? ";
			$dt = $this->_db->quoteInto($dt, $dateStart, null, 1);
			$dt = $this->_db->quoteInto($dt, $dateEnd, null, 1);
			$query->where($dt);
		}
		if ($dateStart!=null ){
			$query->where("log.log_date_time >=?",$dateStart );
		}
		if ($dateEnd!=null ){
			$query->where("log.log_date_time <=?",$dateEnd);
		}
		
		if($user!=null){
			$query->where("mUser.user_name like ?","%$user%" );
				
		}
		if($operContent!=null){
			$query->where("log.operate_content like ?","%$operContent%" );
		}
		if($operDetail!=null){
			$query->where("log.operate_detail like ?", "%$operDetail%" );
		}

		$rows = $this->fetchRow($query);
		return $rows;
	}
	
}



