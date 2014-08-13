<?php

class Core_Service_MstCategoryService extends Core_Service_Abstract {

	/**
	 *
	 * @var Ishida_Db_MstCategoryDb
	 */
	private $categoryDb;

	function __construct() {
		parent::__construct();
		$this->categoryDb = new Core_Db_MstCategoryDb();
	}

	/**
	 *
	 * @return array|boolean
	 */
	public function getAllCategory() {
		try {
			$db 		= $this->categoryDb;
			$arrProduct = $db->getAll();
			return $arrProduct;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}


	public function getCategoryNameParentAndChild(){
		try {
			$db = new Core_Db_MstCategoryDb();
			$mstCategory = $db->getCategoryNameParentAndChild();
			return $mstCategory;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	
	/**
	 *
	 * @return array|boolean
	 */
	//public function getListCategory($arrProductId = null, $orderBy = null){
	public function getListCategory($keyword = null, $arrConditions = null, $tylelist = null, $orderBy = null){
		try {
			$m_categorys = array();
			//Edit 20140613 Hungnh START
// 			if ($arrProductId == null){
// 				return $m_categorys;
// 			}
			$db 		= $this->categoryDb;
// 			$categorys = $db->getListCategory($arrProductId);
			$categorys = $db->getListCategory($keyword, $arrConditions, $tylelist);
			//Edit 20140613 Hungnh END
			if ($categorys != null) {
				foreach ($categorys as $category) {
					if ($category != null) {
						if ($category instanceof Zend_Db_Table_Row_Abstract || is_array ( $category ) == TRUE) {
							$cate_parent = new Core_Models_MstCategory();
							$cate_parent->setCategoryId(Core_Util_Helper::getDataRow($category, 'Parent.category_id'));
							$cate_parent->setCategoryName(Core_Util_Helper::getDataRow($category, 'Parent.category_name'));
							$cate_parent->setParentId(Core_Util_Helper::getDataRow($category, 'Parent.parent_id'));
							$cate_parent->setDisplayOrder(Core_Util_Helper::getDataRow($category, 'Parent.display_order'));
							$cate_parent->setDisplayFlg(Core_Util_Helper::getDataRow($category, 'Parent.display_flg'));
							$cate_child = new Core_Models_MstCategory();
							$cate_child->setCategoryId(Core_Util_Helper::getDataRow($category, 'Child.category_id'));
							$cate_child->setCategoryName(Core_Util_Helper::getDataRow($category, 'Child.category_name'));
							$cate_child->setParentId(Core_Util_Helper::getDataRow($category, 'Child.parent_id'));
							$cate_child->setDisplayOrder(Core_Util_Helper::getDataRow($category, 'Child.display_order'));
							$cate_child->setDisplayFlg(Core_Util_Helper::getDataRow($category, 'Child.display_flg'));

							if (isset($m_categorys[$cate_parent->getCategoryId()]) == null){
								$m_categorys[$cate_parent->getCategoryId()]['parent'] = $cate_parent;
								$m_categorys[$cate_parent->getCategoryId()]['child'][$cate_child->getCategoryId()] = $cate_child;
							} else {
								$m_categorys[$cate_parent->getCategoryId()]['child'][$cate_child->getCategoryId()] = $cate_child;
							}
						}
					}
				}
			}
			return $m_categorys;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function queryProductIds($keyword){
		try {
			$db 		= $this->categoryDb;
			$arrProductId = $db->queryProductIds($keyword);
			return $arrProductId;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	public function getByParentId($parentId=null) {
		try {
			$db = $this->categoryDb;
			$res = $db->getByParentId($parentId);
			return $res;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	/**
	 * getCategoryParent
	 * @return Ambigous <$resulCat, multitype:>|boolean
	 */
	public function getCategoryParent(){
		try {
			$db = new Core_Db_MstCategoryDb();
			$res = $db->getCategoryParent();
			return $res;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
		
	}
	
	public function getCategoryByIdParent($idParent){
		try {
			$db = new Core_Db_MstCategoryDb();
			$res = $db->getCategoryByIdParent($idParent);
			return $res;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	
	}
	
	/**
	 * insertCategory
	 * @param unknown $formData
	 * @return Ambigous <number, string, unknown>|number
	 */
	public function insertCategory($formData){
		try {
			$db = new Core_Db_MstCategoryDb();
			$res = $db->insertCategory($formData);
			$id = $db->getMaxId();
			return $id;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return 0;
		}
	
	}
	
	/**
	 * insertCategory
	 * @param unknown $formData
	 * @return Ambigous <number, string, unknown>|number
	 */
	public function insertArrCategory($formData){
		$errCatgory="";
		try {
			$this->beginTransaction();
			$db = new Core_Db_MstCategoryDb();
			/* @var $mstCat Core_Models_MstCategory */
			foreach ($formData as $key => $mstCat) {
				$intline=$key+1;
				$categoryNameParent = trim($mstCat->getParentName());
				$categoryNameChild=trim($mstCat->getCategoryName());
				
				$idParent = null;
				//empty parent name 
				if($categoryNameParent == ""){
					if ($errCatgory !== ""){
						$errCatgory .= ", ";
					}
					$errCatgory .= $intline ;
				}else {
					$idParent = $db->getParentIdByParentName($categoryNameParent);
					if($idParent !== 0) {
						if( $categoryNameChild === "" ) {
							if ($errCatgory !== ""){
								$errCatgory .= ", ";
							}
							$errCatgory .= $intline ;
							
						
						} else {
							$isExist = $db->checkNameAndParentId($idParent, $categoryNameChild);
							if($isExist ){
								if ($errCatgory !== ""){
									$errCatgory .= ", ";
								}
								$errCatgory .= $intline ;
							}else {
								$db->insertNewChildCategory($idParent, $categoryNameChild); 
							}
						}
						
					} else {
						if( $categoryNameChild === "" ) {
							$idParent = $db->insertNewParentIdByName($categoryNameParent);
						
						} else {
							$isExist = $db->checkNameAndParentId($idParent, $categoryNameChild);
							if($isExist ){
								if ($errCatgory !== ""){
									$errCatgory .= ", ";
								}
								$errCatgory .= $intline ;
							}else {
								$idParent = $db->insertNewParentIdByName($categoryNameParent);
								$db->insertNewChildCategory($idParent, $categoryNameChild);
							}
						}
					}
				}
			}
			if ($errCatgory){
				throw new Exception($errCatgory);
			}
			$this->commit();
		} catch (Exception $e) {
			$this->rollBack();
		}
		return $errCatgory;
	
	}
	
	/**
	 * updateCategory
	 * @param unknown $id
	 * @param unknown $formData
	 * @return boolean
	 */
	public function updateCategory($id,$formData){
		try {
			$db = new Core_Db_MstCategoryDb();
			$res = $db->updateCategory($id,$formData);
			return true;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	public function updateDislay($displayorder, $idpar=null){
		try {
			$db = new Core_Db_MstCategoryDb();
			$res = $db->updateDislay($displayorder, $idpar);
			return true;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	/**
	 * deleteCategory
	 * @param unknown $id
	 * @return boolean
	 */
	public function deleteCategory($id){
		try {
			$this->beginTransaction();
			$db = new Core_Db_MstCategoryDb();
			$isssetIs=$db->isCatProdJoin($id);
			if (!$isssetIs){
				$res = $db->deleteCategory($id);
				$this->commit();
				return true;
			}
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	
	}
	
	public function deleteCategory_($id, $displayorder){
		try {
			$this->beginTransaction();
			$db = new Core_Db_MstCategoryDb();
			$res = $db->deleteCategory_($id, $displayorder);
			$this->commit();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	
	}
	
	public function deleteChildOfPar($id){
		try {
			$this->beginTransaction();
			$db = new Core_Db_MstCategoryDb();
			$res = $db->deleteChildOfPar($id);
			$this->commit();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	
	}
	
	/**
	 * getByCategoryId
	 * @param unknown $id
	 * @return Ambigous <Ambigous, object, unknown, boolean>|boolean
	 */
	public function getByCategoryId($id){
		try {
			$db = new Core_Db_MstCategoryDb();
			$res = $db->getByCategoryId($id);
			return $res;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	/**
	 * getMaxDisplayOrder
	 * @param unknown $data
	 * @return Ambigous <Ambigous, number, string, unknown>|boolean
	 */
	public function getMaxDisplayOrder($idParent){
		try {
			$db = new Core_Db_MstCategoryDb();
			$res = $db->getMaxDisplayOrder($idParent);
			return $res;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	/**
	 * getMinDisplayOrder
	 * @param unknown $data
	 * @return Ambigous <number, string, unknown>|boolean
	 */
	public function getMinDisplayOrder($idParent){
		try {
			$db = new Core_Db_MstCategoryDb();
			$res = $db->getMinDisplayOrder($idParent);
			return $res;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	/**
	 * checkIdExist
	 * @param int $id
	 * @return boolean
	 */
	public function checkIdExist($id){
		try {
			$db = new Core_Db_MstCategoryDb();
			$mstCategory = $db->getRecordById($id);
			return $mstCategory !== null && $mstCategory !== false;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
		}
	}
	
	public function checkIdParentExist($id){
		try {
			$db = new Core_Db_MstCategoryDb();
			$mstCategory = $db->getRecordById($id);
			return $mstCategory !== false;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
		}
	}
	
	

}