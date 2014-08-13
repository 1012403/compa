<?php
class Core_Service_OrderCartInfoService extends Core_Service_Abstract {

	/**
	 *
	 * @var Core_Db_OrderCartInfoDb
	 */
	private $orderCartInfoDb;
	private $orderInfoDb;
	private $orderDetailInfoDb;
	private $mstUserDb;
	private $mstProductDb;
	private $mstMagnificationDb;
	private $mstUserShippingDb;

	function __construct() {
		parent::__construct();
		$this->orderCartInfoDb = new Core_Db_OrderCartInfoDb();
		$this->orderInfoDb = new Core_Db_OrderInfoDb();
		$this->orderDetailInfoDb = new Core_Db_OrderDetailInfoDb();
		$this->mstUserDb = new Core_Db_MstUserDb();
		$this->mstProductDb = new Core_Db_MstProductDb();
		$this->mstMagnificationDb = new Core_Db_MstMagnificationDb();
		$this->mstUserShippingDb = new Core_Db_MstUserShippingDb();
	}

	/**
	 *getOrderCartInfoByUser
	 * @return array|boolean
	 */
	public function getOrderCartInfoByUser($idUser) {
		try {
			$arrProduct = $this->mstProductDb->getProductInOrderCartByUserId($idUser);
			foreach ($arrProduct as $product){
				$magnificationPoint = $this->mstMagnificationDb->getMagnificationPointByProductId($product->getProductId());
				$magnificationPoint = $magnificationPoint == null ? 1 : $magnificationPoint;

				$product->setMagnificationPoint($magnificationPoint);
			}
			return $arrProduct;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	/**
	 *getUserShippingInfo
	 * @return array|boolean
	 */
	public function getUserShippingInfo($idUser, $shippingSeq = null) {
		try {
			$userShipping = $this->mstUserShippingDb->getUserShippingById($idUser, $shippingSeq);
			return $userShipping;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	/**
	 *deleteRowInOrdercart
	 * @return array|boolean
	 */
	public function deleteRowInOrdercart($idProduct, $idUser) {
		try {
			$this->beginTransaction();
			$db = $this->orderCartInfoDb;
			$res = $db->deleteOrderCart($idProduct, $idUser);

			$this->commit();
			return $res;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	/**
	 *updateQuantity
	 * @return array|boolean
	 */
	public function updateQuantity($idProduct, $quantity, $idUser) {
		try {
			$this->beginTransaction();
			$db = $this->orderCartInfoDb;
			$res = $db->updateQuantity($idProduct, $quantity, $idUser);

			$this->commit();
			return $res;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}

	/**
	 *insertOrder
	 * @return array|boolean
	 */
	public function insertOrder($orderInfo, $arrOrderDetail, $idUser) {
		try {
			$this->beginTransaction();
			$this->orderInfoDb->insertRecord($orderInfo);
			$lastInsertId = $this->orderInfoDb->getAdapter()->lastInsertId();

			foreach ($arrOrderDetail as $orderDetail){
				$orderDetail->setOrderId($lastInsertId);
				$this->orderDetailInfoDb->insertRecord($orderDetail);
			}

			$this->orderCartInfoDb->deleteAllOrder($idUser);
			$this->commit();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
}