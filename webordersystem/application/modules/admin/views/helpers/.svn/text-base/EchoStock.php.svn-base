<?php
class Zend_View_Helper_EchoStock {
	function echoStock($stockCheck,$stock,$stockValue) {
		$db = new Core_Db_MstClassDb();
		$arrStock = $db->getMstClassByItemType(Core_Util_Const::SHIPPING_CLASS);
	    $str = "";
	    if($stockCheck=="1"){
	    	/*
	        switch ($stock){
	            case "1":
	                //$str = "【在庫無し】";
	                $str = isset($arrStock[1])?"【" . $arrStock[1]->getItemName(). "】":"";
	                break;
	            case "2":
	                //$str = "【在庫あり】";
	            	$str = isset($arrStock[1])?"【" . $arrStock[1]->getItemName(). "】":"";
	                break;
	            case "3":
	                //$str = "【在庫　" . number_format($stockValue)."個】";
	            	$str = isset($arrStock[1])?"【" . $arrStock[1]->getItemName(). "】":"";
	                break;
	        }*/
	        if (isset($arrStock[intval($stock)])) {
	        	if (intval($stock) == 3) {
	        		$str = "【".$arrStock[intval($stock)]->getItemName() . number_format($stockValue)."個】";
	        	} else {
	    			$str = "【".$arrStock[intval($stock)]->getItemName()."】";
	        	}
	    	}
	    }
	    

		return $str;
	}
}
