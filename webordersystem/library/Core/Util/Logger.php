<?php
class Core_Util_Logger {
	
	const LOG_KIND_VISIT = "1";
	
	const LOG_VISIT_BY_BUTTON = "0";
	const LOG_VISIT_BY_LINK = "1";
	const LOG_VISIT_BY_DROPDOWN = "2";
	
	const LOG_BUTTON_LABEL = "ボタン";
	const LOG_LINK_LABEL   = "リンク";
	const LOG_DROPDOWN_LABEL = "コンボ";
	const LOG_SCREEN_LABEL = "画面";
	
	/**
	 * writeLog
	 * @param string $userId
	 * @param string $logKind
	 * @param string $operationContent
	 * @param string $operationDetail
	 * @return boolean
	 */
	public static function writeLog($logKind, $operationContent, $operationDetail) {
		$userId = Core_Util_Helper::getIdUserLogin();
		//$userId = $idInsert;
		$logDb = new Core_Db_OperationLogInfoDb();
		$res = $logDb->saveLog($userId, $logKind, $operationContent, $operationDetail);
		return $res;
	}
	/**
	 * writeLogForAdmin
	 * @param string $logKind
	 * @param string $operationContent
	 * @param string $operationDetail
	 * @return boolean
	 */
	public static function writeLogForAdmin($logKind, $operationContent, $operationDetail) {
		$userId = Core_Util_Helper::getIdAdminLogin();
		//$userId = $idInsert;
		$logDb = new Core_Db_OperationLogInfoDb();
		$res = $logDb->saveLog($userId, $logKind, $operationContent, $operationDetail);
		return $res;
	}
}