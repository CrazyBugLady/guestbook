<?php
	namespace FormularGenerator;
	
	require_once("fieldtypes.class.php");
	require_once("errorCodes.class.php");
	require_once("FormField.class.php");
	
	class formvalidator
	{
		public $errorValidation = "";
		
		public function validateAll($Fields)
		{
			foreach($Fields as $Field)
			{
				self::validate($Field);
			}
		}
	
		public function validate($Field)
		{
			switch($Field->validate())
			{
				case errorCodes::ERR_MAXLENGTH:
					$this->errorValidation .= "Inhalt vom Feld " . $$Field->FormFieldName . " zu lang! <br>";
					break;
				case errorCodes::ERR_REQUIRED:
					$this->errorValidation .= "Feld " . $Field->FormFieldName . " nicht ausgefüllt! <br>";
					break;
				case errorCodes::ERR_FORMAT:
					$this->errorValidation .= "Format von " . $Field->FormFieldName . " falsch! <br>";
					break;
			}
		}

		public function validationSuccessful()
		{
			return $this->errorValidation == "";
		}
		
		public function showErrors()
		{
			return $this->errorValidation;
		}
		
	}
?>