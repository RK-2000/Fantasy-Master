<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Media_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	/*
		Description: 	Use to get list of uploaded media.
		Note:			$Field should be comma seprated and as per selected tables alias. 
	*/
	function getMedia($Field = '', $Where = array(), $multiRecords = FALSE)
	{
		$Return = array('Data' => array('Records' => array()));
		$this->db->select($Field);
		$this->db->select('M.MediaGUID,
		CONCAT("' . IMAGE_SERVER_PATH . '",MS.SectionFolderPath,"110_",M.MediaName) AS MediaThumbURL,
		CONCAT("' . IMAGE_SERVER_PATH . '",MS.SectionFolderPath,M.MediaName) AS MediaURL,
		M.MediaCaption');

		$this->db->from('tbl_media M');
		$this->db->from('tbl_media_sections MS');
		$this->db->where("M.SectionID", "MS.SectionID", FALSE);
		if (!empty($Where['SectionID'])) {
			$this->db->where("M.SectionID", $Where['SectionID']);
		}
		if (!empty($Where['MediaID'])) {
			$this->db->where("M.MediaID", $Where['MediaID']);
		}
		if (!empty($Where['EntityID'])) {
			$this->db->where("M.EntityID", $Where['EntityID']);
		}
		if (!empty($Where['MediaGUID'])) {
			$this->db->where("M.MediaGUID", $Where['MediaGUID']);
		}
		$this->db->order_by('M.MediaID', 'DESC');
		if ($multiRecords) {
			$TempOBJ = clone $this->db;
			$TempQ = $TempOBJ->get();
			$Return['Data']['TotalRecords'] = $TempQ->num_rows();
			$this->db->limit($this->PageSize, paginationOffset($this->PageNo, $this->PageSize));
		} else {
			$this->db->limit(1);
		}
		$Query = $this->db->get();
		if ($Query->num_rows() > 0) {
			if ($multiRecords) {
				$Return['Data']['Records'] = $Query->result_array();
				return $Return;
			} else {
				return $Query->row_array();
			}
		}
		return FALSE;
	}

	/*
	Description: 	Use to add new media
	*/
	function addMedia($UserID, $SectionID, $Input = array())
	{
		$this->db->trans_start();
		$EntityGUID = (!empty($Input['EntityGUID']) ? $Input['EntityGUID'] : get_guid());
		$InsertData = array_filter(array("MediaGUID" => $EntityGUID, "IsImage" => $Input['IsImage'], "UserID" => $UserID, "SectionID" => $SectionID, "MediaRealName" => $Input['MediaRealName'], "MediaName" => $Input['MediaName'], "MediaSize" => $Input['MediaSize'], "MediaExt" => $Input['MediaExt'], "MediaCaption" => $Input['MediaCaption']));
		$this->db->insert('tbl_media', $InsertData);
		$EntityID = $this->db->insert_id();
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return FALSE;
		}
		return array("EntityID" => $EntityID, "EntityGUID" => $EntityGUID, "MediaRealName" => $Input['MediaRealName']);
	}

	/*
	Description: 	Use to upload file to server
	*/
	function uploadFile($UserID, $SectionID, $Path, $Ext = 'gif|jpg|png', $PostData = array())
	{
		checkDirExist($Path);
		$FileInfo = (pathinfo($_FILES['File']['name']));
		$FileName = get_guid();
		$config['upload_path'] = $Path;
		$config['allowed_types'] = '*';
		$config['max_size'] = '250000';
		$config['max_width'] = '7000';
		$config['max_height'] = '7000';
		$config['file_ext_tolower'] = TRUE;
		$config['quality'] = '90%';
		$config['file_name'] = preg_replace('/\s+/', '_', strtolower($FileInfo['filename'])) . '_' . time() . '.' . $FileInfo['extension'];
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('File')) {
			echo $this->upload->display_errors();
			return FALSE;
		} else {
			$MediaDetails = $this->upload->data();
			if ($MediaDetails['file_type'] == 'video/mp4' || $MediaDetails['file_type'] == 'video/quicktime') {
				ob_start();
				system("ffmpeg -ss 4 -i $Path $FileName.mp4 -s 320x240 -frames:v 1 $Path $FileName.jpg 2>&1;");
				ob_clean();
			}
			$MediaDetails = array("EntityGUID" => $FileName, "IsImage" => $MediaDetails['is_image'], "MediaRealName" => $MediaDetails['client_name'], "MediaName" => $MediaDetails['orig_name'], "MediaSize" => $MediaDetails['file_size'], "MediaExt" => $MediaDetails['file_ext'], "MediaCaption" => @$PostData['MediaCaption']);

			$MediaData = $this->addMedia($UserID, $SectionID, $MediaDetails);
			$Return = array("MediaID" => $MediaData['EntityID'], "MediaGUID" => $MediaData['EntityGUID'], "MediaURL" => realpath($Path . '/' . $MediaDetails['MediaName']), "MediaName" => $MediaDetails["MediaName"], "MediaExt" => $MediaDetails["MediaExt"], "MediaRealName" => $MediaData["MediaRealName"]);
			return $Return;
		}
	}
	
	/*
	Description: 	Use to create thumb images
	*/
	function resizePicture($SourcePath, $NewPath, $FileType, $FileName, $Sizes = array(), $Ratio = FALSE)
	{
		checkDirExist($NewPath);
		array_push($Sizes, THUMBNAIL_SIZE); /*push extra thumbnail size to array*/
		foreach ($Sizes as $key => $Size) {
			if (!$this->createThumb($SourcePath, ($key > 0 ? $NewPath . $Size . '_' . $FileName : $NewPath . $FileName), $FileType, $Size, $Size, ($Ratio == 'No' ? '' : $Size))) {
				return FALSE;
			}
		}
		return TRUE;
	}

	/*
	Description: 	Use to create thumb images
	*/
	function createThumb($SourcePath, $NewPath, $FileType, $Width, $Height, $MaintainRatio = '')
	{
		$SourceImage = FALSE;
		if (preg_match("/jpg|JPG|jpeg|JPEG/", $FileType)) {
			$SourceImage = imagecreatefromjpeg($SourcePath);
		} elseif (preg_match("/png|PNG/", $FileType)) {
			if (!$SourceImage = @imagecreatefrompng($SourcePath)) {
				$SourceImage = imagecreatefromjpeg($SourcePath);
			}
		} elseif (preg_match("/gif|GIF/", $FileType)) {
			$SourceImage = imagecreatefromgif($SourcePath);
		}
		if ($SourceImage == FALSE) {
			$SourceImage = imagecreatefromjpeg($SourcePath);
		}
		$OrigW = imageSX($SourceImage);
		$OrigH = imageSY($SourceImage);
		if ($OrigW < $Width && $OrigH < $Height) {
			$DesiredW = $OrigW;
			$DesiredH = $OrigH;
		} else {
			$Scale = min($Width / $OrigW, $Height / $OrigH);
			$DesiredW = ceil($Scale * $OrigW);
			$DesiredH = ceil($Scale * $OrigH);
		}
		if ($MaintainRatio != '') {
			$DesiredW = $DesiredH = $MaintainRatio;
		}
		$VirtualImage = imagecreatetruecolor($DesiredW, $DesiredH);
		if (preg_match("/png|PNG/", $FileType)) {
			imagealphablending($VirtualImage, false);
			imagesavealpha($VirtualImage, true);
		} else {
			$Kek = imagecolorallocate($VirtualImage, 255, 255, 255);
			imagefill($VirtualImage, 0, 0, $Kek);
		}
		if ($MaintainRatio == '') {
			imagecopyresampled($VirtualImage, $SourceImage, 0, 0, 0, 0, $DesiredW, $DesiredH, $OrigW, $OrigH);
		} else {
			$wm = $OrigW / $MaintainRatio;
			$Hm = $OrigH / $MaintainRatio;
			$Hheight = $MaintainRatio / 2;
			$Wheight = $MaintainRatio / 2;
			if ($OrigW > $OrigH) {
				$AdjustedWidth = $OrigW / $Hm;
				$HalfWidth = $AdjustedWidth / 2;
				$IntWidth = $HalfWidth - $Wheight;
				imagecopyresampled($VirtualImage, $SourceImage, -$IntWidth, 0, 0, 0, $AdjustedWidth, $MaintainRatio, $OrigW, $OrigH);
			} elseif (($OrigW <= $OrigH)) {
				$AdjustedHeight = $OrigH / $wm;
				$half_height = $AdjustedHeight / 2;
				imagecopyresampled($VirtualImage, $SourceImage, 0, 0, 0, 0, $MaintainRatio, $AdjustedHeight, $OrigW, $OrigH);
			} else {
				imagecopyresampled($VirtualImage, $SourceImage, 0, 0, 0, 0, $MaintainRatio, $MaintainRatio, $OrigW, $OrigH);
			}
		}
		if (preg_match("/png|PNG/", $FileType)) {
			$ImgC = imagepng($VirtualImage, $NewPath, 9);
		} else {
			$ImgC = imagejpeg($VirtualImage, $NewPath, 100);
		}
		if (@$ImgC) {
			imagedestroy($VirtualImage);
			imagedestroy($SourceImage);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/*
		Description: 	Used to associate media to entity.
	*/
	function addMediaToEntity($MediaID, $UserID, $EntityID)
	{
		$this->db->limit(1);
		$this->db->where(array("MediaID" => $MediaID, "UserID" => $UserID, "EntityID" => null));
		$this->db->update('tbl_media', array("EntityID" => $EntityID));
		return TRUE;
	}

	/*
		Description: 	Used to associate media to entity.
	*/
	function updateMediaToEntity($Media = array(), $UserID, $EntityID)
	{
		foreach ($Media as $Key => $Value) {
			$MediaData = $this->Media_model->getMedia('M.MediaID', array("MediaGUID" => $Value['MediaGUID']));
			$updateArray[] = array(
				'MediaID'			=> $MediaData['MediaID'],
				'EntityID'			=> $EntityID,
				'MediaCaption'		=> @$Value['MediaCaption']
			);
		}
		$this->db->update_batch('tbl_media', $updateArray, 'MediaID');
	}
}
