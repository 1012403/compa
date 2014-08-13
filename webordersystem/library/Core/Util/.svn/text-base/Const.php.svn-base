<?php
class Core_Util_Const {

	const SESSION_NAMESPACE_LOGIN = 'loginUserSession';
	const SESSION_NAMESPACE_LOGIN_ADMIN = 'loginAdminSession';
	const SESSION_NAMESPACE_PAGE_CLASS = 'pageClassSession';
	const SESSION_NAMESPACE_PAGE_CLASS_ADMIN = 'pageClassAdminSession';
	const SESSION_NAMESPACE_SORT_CLASS = 'sortClassSession';
	const NUMBER_TOCKEN = '30';
	const ORDER_STATUS_1 = '1';
	const ORDER_STATUS_2 = '2';
	const ORDER_STATUS_3 = '3';

	const NUMBER_ITEMS_PAGE = 20;

	const TYLE_LIST_WISH_LIST = "WishList";
	const TYLE_LIST_POPULAR_PRODUCTS = "PopularProducts";
	const TYLE_LIST_FEATURED_PRODUCTS = "FeaturedProducts";
	const TYLE_LIST_HISTORY_PRODUCTS = "HistoryProducts";

	const SORT_CLASS = 'PRODUCT_ORDER';
	public static $SORT_DEFAULT = 0;//"商品番号順";
	const SORT_LOW_TO_HIGH_PRICE  = 2;//"価格の安い順";
	const SORT_HIGH_TO_LOW_PRICE  = 3;//"価格の高い順";
	const SORT_NEWEST = 4;//"新着順";

	const DISPLAY_SUMMARY = "一覧";
	const DISPLAY_IMAGES = "画像";

	const SHIPPING_CLASS = 'SHIPPING_CLASS';
	const SHIPPING_CLASS_3 = '3'; //送料
	const STOCK_CLASS = 'STOCK_CLASS';
	const STOCK_CLASS_3 = '3'; //在庫数
	//change  DETAIL_CLASS = 14  by const ITEM_TYPE_PRODUCT_DETAIL = '4';
	const DETAIL_CLASS = 14;

	//const ITEMS_PER_PAGE_CLASS = 15;
	const ITEMS_PER_PAGE_CLASS = 'PAGINATION';
	const ITEMS_PER_PAGE_CLASS_LIST_PRODUCT = 1;
	const ITEMS_PER_PAGE_CLASS_LIST_TOP_PRODUCT = 2;
	const ITEMS_PER_PAGE_CLASS_LIST_RELATION_ITEM = 3;
	const ITEMS_PER_PAGE_CLASS_LIST_MAILTEMPLATE= 6;
	const ITEMS_PER_PAGE_CLASS_LIST_LOGINFO= 4;// 7 => 4
	const ITEMS_PER_PAGE_CLASS_LIST_ORDER= 5;// 8 => 5

	const PAGE_RANGE_CLASS = 16;
	const PAGE_RANGE_CLASS_DESKTOP = 1;
	const PAGE_RANGE_CLASS_MOBILE = 2;

	const USER_TYPE = 'USER_CLASS';
	const USER_TYPE_DEFAULT = 0;
	const USER_TYPE_SALES_REPRESENTATIVE = '1';
	const USER_TYPE_ACCOUNTANT = '2';
	const USER_TYPE_ORTHER = '3';
	const ADMIN_TYPE = 'ADMIN_CLASS';
	const ADMIN_TYPE_DEFAULT = 0;
	const ADMIN_TYPE_NO = '1';
	const ADMIN_TYPE_REFERENCE_ONLY = '2';
	const ADMIN_TYPE_MASTER_BE_CHANGED = '3';
	const ADMIN_TYPE_SYSTEM_ADMINISTRATOR = '4';

	const AREA_CODE_CLASS = 'AREA';

	const QUOTATION_INFO_WAIT = "見積中";
	// Options for class contact
	public static $CLASS_CONTACT_SETTING = array(
			"0" => "納期について",
			"1" => "在庫状況について",
			"2" => "商品仕様について",
			"3" => "クレーム",
			"4" => "その他"
	);


	public static $VIEW_CONST = array(
		"HEADER_TITLE" => "愛知県、岐阜県、三重県を中心に製造業・物流業・小売業の<br />みなさまをハード・ソフト両面からサポートをしている株式会社イシダテクノ",
		"ADDRESS_INFO" => "株式会社イシダテクノ<br />〒451-0082　愛知県名古屋市西区大金町1-55 TEL：(052)521-1110（代）",
		//"COPYRIGHT_INFO" => "Copyright© 1998 ISHIDA CO.,LTD. All rights reserved"
		"COPYRIGHT_INFO" => "",
		"ADMIN_HEADER_TITLE" => "Ｗｅｂ注文システム　《管理サイト》"
	);

	public static function getViewConstVal($key) {
		if (isset(self::$VIEW_CONST[$key])) {
			return self::$VIEW_CONST[$key];
		} else {
			return "";
		}
	}
	const DELETE_FLG_0 = '0';
	const DELETE_FLG_1 = '1';
	const PAGE_RANGE = '4';
	const ITEMS_PER_PAGE = '10';
	const ITEMS_PER_PAGE_MAIL = '5';
	const ITEMS_TOP_PRODUCT_DEFAULT = 12;
	const ITEMS_PER_PAGE_LOGINFO = '15';
	const ITEMS_PER_PAGE_ORDER = '7';

	const NO_IMAGE_PRODUCT = "nopicture.png";

	const IMAGE_EDITOR_DIR = "editor_img";

	const CONTACT_ASK_FLG = 0;
	const CONTACT_ANS_FLG = 1;
	const DEFAULT_NEW_ORDER_STATUS = '1';
	const DEFAULT_NEW_QUOTATION_STATUS = '1';
	const CONTACT_CLASS = 'CONTACT_CLASS';
	const CONTACT_SEQ_FIRST = 0;
	const SHIPPING_DISPLAY_FLG_YES = '1';
	const SHIPPING_CLASS_HAS_FEE = '2';		//送料

	const POINT_RATE_CLASS = 'POINT_RATE';
	const POINT_RATE_DEFAULT = 1;

	const ADMIN_ITEM_TYPE= 'MAIL_TEMPLATE';
	const ADMIN_CLASS_1 = '1';
	const ADMIN_CLASS_MAIL_17 = 'MAIL_TEMPLATE';
	const PARENT_CLASS = 0;

	const QUOTATION_STATUS = 'QUOTATION_STATUS';
	const SUPPLIER_ITEM_TYPE = 'SUPPLIER';

	const SESSION_MANAGE_QUOTATION = 'manageQuotationSession';

	/*public static $ORDER_STATUS = array(
			null => "すべて",
			"1" => "未処理",
			"2" => "入荷待ち",
			"3" => "発送準備中",
			"4" => "出荷済み"
	);

	public static $ORDER_STATUS_HTML = array(
			null => "<span style=\"color:black;\">すべて</span>",
			"1" => "<span style=\"color:#00B0F0;\">未処理</span>",
			"2" => "<span style=\"color:#FF0000;\">入荷待ち</span>",
			"3" => "<span style=\"color:#92D050;\">発送準備中</span>",
			"4" => "<span style=\"color:#000000;\">出荷済み</span>"
	);*/

	const ORDER_STATUS = 'ORDER_STATUS';

	const FINAL_ORDER_STATUS = '4';
	const MAIL_TEMPLATE_6='6';
	const MAIL_TEMPLATE_FLAG_1='1';

	const QUOTATION_STATUS_NOT_PROCESS = "1";
	const QUOTATION_STATUS_DONE = "2";
	const QUOTATION_STATUS_TEMP = "9";


	const ITEM_CD_ITEMS_TOP_PRODUCT = 2;
	const APPLY_FLG_0='0';

	const ITEM_TYPE_TAX = 'TAX';
	const ITEM_TYPE_PRODUCT_DETAIL = 'PRODUCT_EXTRA';

	const TOCKEN_COOKIE_DATE= 30;
	const TOCKEN_COOKIE_TIME='60*60*24*30';
	const TOCKEN_COOKIE='token_cookie';
	const TOCKEN_COOKIE_ADMIN ='token_cookie_admin';

	const UPLOAD_PRODUCT_IMAGE_PATH_TEMP = "/images/upload/";
	const UPLOAD_PRODUCT_IMAGE_PATH = "/images/products/";
	const UPLOAD_PRODUCT_IMAGE_THUMB_PATH = "/images/products/thumb/";

	const UPLOAD_PRODUCT_IMAGE_ITEM_TYPE = "PASS";
	const UPLOAD_PRODUCT_IMAGE_PATH_ITEM_CD = "1";
	const UPLOAD_PRODUCT_IMAGE_PATH_THUMB_TEMP_ITEM_CD = "2";
	const UPLOAD_PRODUCT_IMAGE_PATH_TEMP_ITEM_CD = "9";

	// ADD 20140512 Hieunm start
	const THUMBNAIL_WIDTH_DEFAULT = 130;
	const THUMBNAIL_HEIGHT_DEFAULT = 130;
	const THUMBNAIL_PRE_FIX_NAME_DEFAULT = "thumb";
	// ADD 20140512 Hieunm end

	const ITEM_TYPE_TRANS_TYPE = "TRANS_TYPE";
	
	
	const STATUS_QUOTATION_DETAULT = '1';
	const STATUS_QUOTATION_TEMP_SAVE = '9';
	
	const REPLACEMENT_FOR_NEW_LINE_ANOTATION = "\\\\";
	const NEW_LINE_ANOTATION = "\r\n";
	
	const NEW_LINE_ANOTATION_FULL= "/\r|\n/";
	const REPLACEMENT_FOR_NEW_LINE_ANOTATION_FULL = "\\\\\\";
	
	const SINGLE_QUOTE = "'";
	const DOUBLE_QUOTE = "\"";
	const DOUBLE_DOUBLE_QUOTE = "\"\"";
	
	const IO_CSV_FLAG_ON = "ON";
	const IO_CSV_FLAG_OFF = "OFF";

	const DATE_SEPARATOR_IO_CSV = "/";
	const DATE_SEPARATOR_PROCESS_IN = "";
}


