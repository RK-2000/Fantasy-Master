<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Recovery_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*
	Description: 	Use to set OTP and send to user for password recovery.
	Procedures:
	1. Select User data by provided username, and continew if user exist.
	2. Delete old unused verification token.
	3. Genrate a Token for set new password and save to tokens table, if user status is Pending or Email-Confirmed.
	4. Send Password Assistance Email to User with Token (If user is not Pending or Email-Confirmed then email send without Token).
	*/

	function recovery($Username)
	{

		$UserData = $this->Users_model->getUsers('UserID,PhoneNumber,Email,StatusID', array('LoginKeyword' => $Username));
		if (!empty($UserData)) {
			if ($UserData['StatusID'] == 1 || $UserData['StatusID'] == 2) {
				/* Genrate a Token for set new password and save to tokens table, if user status is Pending or Email-Confirmed. */
				$Token = $this->generateToken($UserData['UserID'], 1);
				$Message = "Please check your email / phone No. for instructions.";
			} elseif ($UserData['StatusID'] == 3) {
				$Message = "Your account has been deleted. Please contact the Admin for more info.";
			} elseif ($UserData['StatusID'] == 4) {
				$Message = "Your account has been blocked. Please contact the Admin for more info.";
			} elseif ($UserData['StatusID'] == 5) {
				$Message = "You have deactivated your account, please contact the Admin to reactivate.";
			}
			if ($this->Post['type'] != "Email") {
				/* Send change phonenumber SMS to User with Token. */
				$this->Utility_model->sendMobileSMS(array(
					'PhoneNumber' 	=> $UserData['PhoneNumber'],
					'Text' 			=> @$Token
				));
			} else {
				send_mail(array(
					'emailTo'       =>  $UserData['Email'],
					'template_id'   =>  'd-e27a7baab3274e30b30a023038997b5e',
					'Subject'       =>	SITE_NAME . " Password Assistance",
					"Name" 			=> 	$UserData['FullName'],
					'Token' 		=> 	@$Token,
					'EmailText'		=> 	"One time password reset code is given below:"
				));
			}
			return $Message;
		} else {
			return FALSE;
		}
	}

	/*
	Description: 	Use to Verify Token
	*/
	function verifyToken($Token, $Type)
	{
		if (empty($Token)) {
			return FALSE;
		}
		$this->db->select('UserID');
		$this->db->from('tbl_tokens');
		$this->db->where(array('Token' => $Token, 'Type' => $Type, 'StatusID' => 1));
		$this->db->limit(1);
		$Query = $this->db->get();
		if ($Query->num_rows() > 0) {
			return $Query->row()->UserID;
		} else {
			return FALSE;
		}
	}


	/*
	Description: 	Use to Delete Token
	*/
	function deleteToken($Token, $Type = 1)
	{
		$this->db->where(array("Token" => $Token, "Type" => $Type));
		$this->db->limit(1);
		$this->db->update('tbl_tokens', array("StatusID" => 3));
		return TRUE;
	}


	/*
	Description: 	Use to add Token
	*/
	function generateToken($UserID, $Type = 1)
	{
		/* Generate Token */
		$Token =  random_string('numeric', 6);

		/* To check duplicate token */
		if($this->db->query('SELECT Token FROM tbl_tokens WHERE Token = "'.$Token.'" LIMIT 1')->num_rows() > 0){
			$this->generateToken($UserID, $Type);
		}else{
			/* delete old unused token */
			$this->db->where(array("UserID" => $UserID, "Type" => $Type, "StatusID" => 1));
			$this->db->delete('tbl_tokens');
			$this->db->limit(1);

			$this->db->insert('tbl_tokens', array('UserID' => $UserID, 'Type' => $Type, 'Token' => $Token, 'EntryDate' => date("Y-m-d H:i:s")));
			return $Token;
		}
	}
}
