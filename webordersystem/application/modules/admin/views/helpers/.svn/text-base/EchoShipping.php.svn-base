<?php
class Zend_View_Helper_EchoShipping {
	function echoShipping($shippingCheck,$shipping,$shippingValue) {
		$db = new Core_Db_MstClassDb();
		$arrShipping = $db->getMstClassByItemType(Core_Util_Const::SHIPPING_CLASS);
	    $str = "";
	    if($shippingCheck=="1"){
	    	/*
	        switch ($shipping){
	            case "1":
	                //$str = "【送料無料】";
	            	$str = isset($arrShipping[1])?"【" . $arrShipping[1]->getItemName(). "】":"";
	                break;
	            case "2":
	                //$str = "【送料込み】";
	            	$str = isset($arrShipping[2])?"【" . $arrShipping[2]->getItemName(). "】":"";
	                break;
	            case "3":
	                //$str = "【送料　" . number_format($shippingValue)."円】";
	            	$str = isset($arrShipping[3])?"【" . $arrShipping[3]->getItemName(). number_format($shippingValue). "円】":"";
	                break;
                case "4":
                	$str = isset($arrShipping[4])?"【" . $arrShipping[4]->getItemName(). "】":"";
                	break;
	        }
	        */
		    if (isset($arrShipping[intval($shipping)])) {
		    	if (intval($shipping) == 3) {
		    		$str = "【".$arrShipping[intval($shipping)]->getItemName(). " ". number_format($shippingValue). "円】";
		    	} else {
		    		$str = "【".$arrShipping[intval($shipping)]->getItemName()."】";
		    	}
		    }
	    }
	    

		return $str;
	}
}