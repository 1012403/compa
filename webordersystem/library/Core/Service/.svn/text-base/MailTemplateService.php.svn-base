<?php
class Core_Service_MailTemplateService extends Core_Service_Abstract {
	public function getMailTemplate( $itemClass=null, $paginatorData){
		try {
			$mailtempDb=new Core_Db_MailTemplateDb();
			$resulMail=$mailtempDb->getMailTemplate($itemClass, $paginatorData);
			return $resulMail;
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	public function countEmailTemp($itempcd=null){
		try {
			$mailtempDb=new Core_Db_MailTemplateDb();
			$resulcount=$mailtempDb->countEmailTemp($itempcd);
			return Core_Util_Helper::getDataRow($resulcount, "count", 0);
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	
	public function searchEmailTempById($id){
		try {
			$mailtempDb=new Core_Db_MailTemplateDb();
			$resul=$mailtempDb->searchEmailTempById($id);
			return $resul;
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	public function saveEmail($formData){
		try {
			$mailtempDb=new Core_Db_MailTemplateDb();
			$resul=$mailtempDb->saveEmail($formData);
			return $resul;
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	public function updateEmail($id, $formData){
		try {
			$mailtempDb=new Core_Db_MailTemplateDb();
			$resul=$mailtempDb->updateMail($id, $formData);
			return $resul;
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	public function updateFlag($id){
		try {$mailtempDb=new Core_Db_MailTemplateDb();
			$resul=$mailtempDb->updateFlag($id);
			return $resul;
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
 	/* public function getMailTemplateByItem($itemClass=null){
		try {
			$mailtempDb=new Core_Db_MailTemplateDb();
			$resul=$mailtempDb->getMailTemplateByItem($itemClass);
			return $resul;
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	} */
	
	public function getMailTemplateByItem($itemClass=null,$flag=null){
		try {
			$mailtempDb=new Core_Db_MailTemplateDb();
			$resul=$mailtempDb->getMailTemplateByItem($itemClass,$flag);
			return $resul;
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
}