<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Store_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*
		Description: 	Use to get single coupon or list of coupons.
	*/
	function getCoupons($Field, $Where = array(), $multiRecords = FALSE,  $PageNo = 1, $PageSize = 15)
	{
		/* Define section  */
		$Return = array('Data' => array('Records' => array()));
		/* Define variables - ends */
		$this->db->select('C.CouponID CouponIDForUse,IF(C.CouponBanner IS NULL,CONCAT("' . BASE_URL . '","uploads/Coupon/","default-coupon.png"), CONCAT("' . BASE_URL . '","uploads/Coupon/",C.CouponBanner)) CouponBanner');
		$this->db->select($Field);
		$this->db->select('
		CASE C.StatusID
		when "2" then "Active"
		when "6" then "Inactive"
		END as Status', false);
		$this->db->from('ecom_coupon C');
		if (!empty($Where['CouponID'])) {
			$this->db->where("C.CouponID", $Where['CouponID']);
		}
		if (!empty($Where['CouponCode'])) {
			$this->db->where("C.CouponCode", $Where['CouponCode']);
		}
		if (!empty($Where['CouponType'])) {
			$this->db->where("C.CouponType", $Where['CouponType']);
		}
		if (!empty($Where['StatusID'])) {
			$this->db->where("C.StatusID", $Where['StatusID']);
		}
		if (!empty($Where['ValidFrom'])) {
            $this->db->where("C.CouponValidTillDate >=", $Where['ValidFrom']);
        }
        if (!empty($Where['ValidTo'])) {
            $this->db->where("C.CouponValidTillDate <=", $Where['ValidTo']);
        }
		if (!empty($Where['Keyword'])) {
            $Where['Keyword'] = trim($Where['Keyword']);
            $this->db->group_start();
            $this->db->like("C.CouponCode", $Where['Keyword']);
            $this->db->or_like("C.CouponTitle", $Where['Keyword']);
            $this->db->or_like("C.CouponDescription", $Where['Keyword']);
            $this->db->group_end();
        }
		$this->db->order_by('C.CouponID', 'DESC');
		/* Total records count only if want to get multiple records */
		if ($multiRecords) {
			$TempOBJ = clone $this->db;
			$TempQ = $TempOBJ->get();
			$Return['Data']['TotalRecords'] = $TempQ->num_rows();
			$this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /*for pagination*/
		} else {
			$this->db->limit(1);
		}
		$Query = $this->db->get();
		if ($Query->num_rows() > 0) {
			foreach ($Query->result_array() as $Record) {
				unset($Record['CouponIDForUse']);
				if (!$multiRecords) {
					return $Record;
				}
				$Records[] = $Record;
			}
			$Return['Data']['Records'] = $Records;
			return $Return;
		}
		return FALSE;
	}

	/*
	Description: 	ADD new coupon.
	*/
	function addCoupon($UserID, $Input = array(), $StatusID)
	{
		$this->db->trans_start();
		$EntityGUID = get_guid();
		/* Add to entity table and get ID. */
		$CouponID = $this->Entity_model->addEntity($EntityGUID, array("EntityTypeID" => 13, "UserID" => $UserID, "StatusID" => $StatusID));
		
		/* Add product to product table*/
		$InsertArray = array_filter(array(
			"CouponID" 				=> 	$CouponID,
			"CouponTitle" 			=>	@$Input['CouponTitle'],
			"CouponDescription" 	=>	@$Input['CouponDescription'],
			"CouponBanner" 	        =>	@$Input['CouponBanner'],
			"ProductRegPrice" 		=>	@$Input['ProductRegPrice'],
			"CouponCode" 			=>	@$Input['CouponCode'],
			"CouponType" 			=>	@$Input['CouponType'],
			"CouponValue" 			=>	@$Input['CouponValue'],
			"CouponValidTillDate" 	=>	@$Input['CouponValidTillDate'],
			"Broadcast" 			=>	@$Input['Broadcast'],
			"MiniumAmount" 			=>	@$Input['MiniumAmount'],
			"MaximumAmount" 		=>	@$Input['MaximumAmount'],
			"NumberOfUses" 			=>	@$Input['NumberOfUses'],
			"StatusID" 			    =>	$StatusID
		));
		$this->db->insert('ecom_coupon', $InsertArray);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return FALSE;
		}
		return array('CouponID' => $CouponID, 'CouponGUID' => $EntityGUID);
	}

	/*
	Description: 	Use to update category.
	*/
	function updateCoupon($CouponID, $Input = array())
	{
		$UpdateArray = array_filter(array(
			"CouponTitle" 			=>	@$Input['CouponTitle'],
			"CouponDescription" 	=>	@$Input['CouponDescription'],
			"CouponBanner" 	        =>	@$Input['CouponBanner'],
			"ProductRegPrice" 		=>	@$Input['ProductRegPrice'],
			"CouponCode" 			=>	@$Input['CouponCode'],
			"CouponType" 			=>	@$Input['CouponType'],
			"CouponValue" 			=>	@$Input['CouponValue'],
			"CouponValidTillDate" 	=>	@$Input['CouponValidTillDate'],
			"Broadcast" 			=>	@$Input['Broadcast'],
			"MiniumAmount" 			=>	@$Input['MiniumAmount'],
			"MaximumAmount" 		=>	@$Input['MaximumAmount'],
			"NumberOfUses" 			=>	@$Input['NumberOfUses'],
			"StatusID" 			    =>	@$Input['StatusID']
		));

		if (isset($Input['CouponDescription']) && $Input['CouponDescription'] == '') {
			$UpdateArray['CouponDescription'] = null;
		}

		if (!empty($UpdateArray)) { /*Update product details*/
			$this->db->where('CouponID', $CouponID);
			$this->db->limit(1);
			$this->db->update('ecom_coupon', $UpdateArray);
		}
		return TRUE;
	}

	/*
		Description: 	Use to add new order
	*/
	function addOrder($Input = array(), $UserID, $CouponID = '', $StatusID = 1)
	{

		$OrderGUID = get_guid();
		$this->db->trans_start();

		/* get coupon data for apply */
		if (!empty($CouponID)) {
			$CouponData = $this->getCoupons('C.CouponType, C.CouponValue', array("CouponID" => $CouponID, "StatusID" => 2));
		}

		/*booking duration calculation*/
		if (!empty($Input['FromDateTime']) && !empty($Input['ToDateTime'])) {
			$BookedDuration = diffInHours($Input['FromDateTime'], $Input['ToDateTime']);
		}

		/* Add order to orders table */
		$OrderData = array_filter(array(
			"UserID"		=>	$UserID,
			"OrderGUID"		=>	$OrderGUID,
			"FromDateTime"	=>	@$Input['FromDateTime'],
			"ToDateTime"	=>	@$Input['ToDateTime'],
			"Note"			=>	@$Input['Note'],
			"BookedDuration" =>	@$BookedDuration,
			"EntryDate"		=>	date("Y-m-d h:i;s"),
			"FirstName"		=>	$Input['Recipient']['FirstName'],
			"LastName"		=>	@$Input['Recipient']['LastName'],
			"Address"		=>	@$Input['DeliveryPlace']['Address'],
			"Address1"		=>	@$Input['DeliveryPlace']['Address1'],
			"City"			=>	@$Input['DeliveryPlace']['City'],
			"PhoneNumber"	=>	@$Input['DeliveryPlace']['PhoneNumber'],
			"DeliveryType"	=>	$Input['DeliveryType'],
			"PaymentMode"	=>	$Input['PaymentMode'],
			"PaymentGateway" =>	@$Input['PaymentGateway'],
			"CouponID"		=>	@$CouponID,
			"RewardPoints"	=>	@$Input['RewardPoints'],
			"StatusID"		=>	$StatusID
		));
		$this->db->insert('ecom_orders', $OrderData);
		$OrderID = $this->db->insert_id();

		/* Add products order info to order_details table */
		$OrderPrice = 0;
		if (!empty($Input['Products']) && is_array($Input['Products'])) {
			foreach ($Input['Products'] as $key => $value) {
				$ProductData = $this->getProducts('P.ProductID, P.ProductRegPrice, P.ProductBuyPrice', array("ProductGUID" => $value['ProductGUID']));
				$OrderDetailData[] = array(
					'OrderID' => $OrderID,
					'ProductID' => $ProductData['ProductID'],
					'ProductQuantity' => (!empty($value['Quantity']) ? $value['Quantity'] : 1),
					"ProductRegPrice" => $ProductData['ProductRegPrice'],
					"ProductBuyPrice" => $ProductData['ProductBuyPrice']
				);
				$OrderPrice += $ProductData['ProductBuyPrice'] * $value['Quantity'];

				/*minus stock quantity*/
				$this->editProduct($ProductData['ProductID'], array("ProductInStock" => "ProductInStock-" . $value['Quantity']), $UserID);
			}
			$this->db->insert_batch('ecom_order_details', $OrderDetailData);


			/* apply discount (discount calculation) - start */
			$DiscountedPrice = 0;
			if (!empty($CouponData)) {
				$DiscountedPrice = ($CouponData['CouponType'] == 'Flat' ? $CouponData['CouponValue'] : ($OrderPrice / 100) * $CouponData['CouponValue']);
				$OrderPrice = ($OrderPrice - $DiscountedPrice);
			}
			/* apply discount (discount calculation) - ends */

			/* apply reward points - start */
			if (!empty($Input['RewardPoints'])) {
				$DiscountedPrice += $Input['RewardPoints'];
				$OrderPrice = ($OrderPrice - $Input['RewardPoints']);
				/*add reward points*/
				$this->Users_model->updateRewardPoints(
					array("TransactionID" => $OrderID, "TransactionDetails" => ''),
					$UserID,
					$Input['RewardPoints'],
					'Subtract',
					'Redemption'
				);
			}
			/* apply reward points - ends */
		} /*Add product ends*/

		/* update OrderPrice to orders table */
		$this->db->where('OrderID', $OrderID);
		$this->db->limit(1);
		$this->db->update('ecom_orders', array("OrderPrice" => $OrderPrice, "DiscountedPrice" => $DiscountedPrice));

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return FALSE;
		}
		return array("OrderGUID" => $OrderGUID, "OrderID" => $OrderID);
	}
}
