<?php
class Core_Service_OperationLogInfoService extends Core_Service_Abstract {
	public function getLog($dateStart,$dateEnd, $user, $operContent, $operDetail,$paginatorData){
		try {
			$logInfoDb=new Core_Db_OperationLogInfoDb();
			//$loginfo=$logInfoDb->getLogInfo();
			$loginfo=$logInfoDb->getLogInfo($dateStart,$dateEnd, $user, $operContent, $operDetail,$paginatorData);
			return $loginfo;
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
		}
	}
	public function getCountLog($dateStart,$dateEnd, $user, $operContent, $operDetail){
		try {
			$logInfoDb=new Core_Db_OperationLogInfoDb();
			$loginfo=$logInfoDb->getCountLogInfo($dateStart,$dateEnd, $user, $operContent, $operDetail);
			return Core_Util_Helper::getDataRow($loginfo, "count", 0);
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return 0;
		}
	}
	

}
