<?php
	namespace Guestbook\Functions;
	
	class ImageHelper
	{
		public static $ImageFolderPath = "Resources/UserImages";
		
		public function static destroyImage($ImageName)
		{
			// gibt zurück, ob Bild gelöscht werden darf
			return var_dump(unlink($ImageFolderPath."/".$ImageName));
		}
		
		public function static uploadImage($Files)
		{
			$FilePath = self::$ImageFolderPath. basename( $Files["uploadFile"]["name"]);
			$uploadOk=1;
			$Error = "";
			
			// Existiert das Bild schon
			if (file_exists($target_dir . $_FILES["uploadFile"]["name"])) {
				$Error .= "Sorry, file already exists.";
				$uploadOk = 0;
			}

			// Ist das Bild zu gross
			if ($uploadFile_size > 500000) {
				$Error .= "Sorry, your file is too large.";
				$uploadOk = 0;
			}

			// Stimmt das Format?
			if (!($uploadFile_type == "image/png") || !($uploadFile_type == "image/jpg")) {
				$Error .= "Sorry, only PNG and JPG files are allowed.";
				$uploadOk = 0;
			}

			// Konnte das Bild hochgeladen werden?
			if ($uploadOk == 0) {
				$Error .= "Sorry, your file was not uploaded.";
				// Ist alles in Ordnung, so kann das Bild hochgeladen werden.
			} 
			else {
				if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_dir)) {
					$Error .="The file ". basename( $_FILES["uploadFile"]["name"]). " has been uploaded.";
				} else {
					$Error .="Sorry, there was an error uploading your file.";
				}
			}
			
			return $Error;
		}
	}

	
?>