<?php
	namespace FormularGenerator;
	
	require_once("php/Data/DB.class.php");

	include "functions.inc.php";
	include "fieldtypes.class.php";
	include "FormField.class.php";
	include "Formvalidator.class.php";
	
	class formulargenerator
	{
		public $titel;
		public $datatable;
		public $except;
		public $repeat;
		public $placeholderValues;
		public $DB;
		public $showCaptcha;
		public $ImageUpload;
		
		public $Validator;
		
		private $publicKeyReCaptcha = "6Lfva98SAAAAAF7TvlubNWajw25uQWxa1xqqz9o6";
		private $privateKeyReCaptcha = "6Lfva98SAAAAAI8plTXyBznUWB2JEouddMM1kLWp";
		
		public function __construct($titel, $datatable, $except, $repeat, $placeholderValues, $showCaptcha)
		{
			$DBTest = new \Guestbook\Data\DB();
			$this->DB = $DBTest::getConnection("read", "Resources/Configuration/config.ini");
			$this->titel = $titel;
			$this->datatable = $datatable;
			$this->except = $except;
			$this->repeat = $repeat;
			$this->placeholderValues = $placeholderValues;
			$this->showCaptcha = $showCaptcha;
			$this->Validator = new Formvalidator();
		}
		
		public function createForm($Action)
		{
			echo "<h2>".$this->titel."</h2>";
			echo "<form method='post' action='". $Action ."' class='form-horizontal' role='form'>";
			
			$FormFields = $this->getAllFields();
			
			$i = 0;
			foreach($FormFields as $FormField)
			{
				if(sizeof($this->placeholderValues) >= $i && sizeof($this->placeholderValues) != 0)
				{
					$FormField->FieldValue = $this->placeholderValues[$i];
				}
				
				$FormField->buildField();
				$i++;
			}
						
			if($this->showCaptcha == true)
			{
				$this->showCaptcha();
			}
			
				echo "<div class='form-group'>";
					echo "<div class='col-sm-offset-2 col-sm-10'>";
						echo "<button type='submit' class='btn btn-success' name='submit'>Abschicken</button>";
						echo "<button type='reset' class='btn btn-default'>Zurücksetzen</button>";
					echo "</div>";
				echo "</div>";
			echo "</form>";
			
		}
		
		private function getAllFields()
		{
			$COLUMNS = $this->DB->query("SHOW COLUMNS FROM " . $this->datatable);
			
			$Formfields = array();
			$i = 0;
			while($data = $COLUMNS->fetch_array())
			{
				if(in_array($data['Field'], $this->except) == false && in_array($data['Field'], $this->repeat) == false)
				{
					$FormField = new FormField($data['Field'], $data['Type'], $data['Null'], false);
					$Formfields[$i] = $FormField;
					$i++;
				}
				
				if(in_array($data['Field'], $this->repeat) == true)
				{
					$descriptionsTwoRepeat = array('', 'new', 'repeat');
					$descriptionsOneRepeat = array('', 'repeat');
					
					$counts = array_count_values($this->repeat); // counts how many times some values are included
					
					$repeatCount = $counts[$data['Field']] + 1;
					
					$usedArray = ($repeatCount > 2) ? $descriptionsTwoRepeat : $descriptionsOneRepeat; // welcher Array wird für die Benennung verwendet
					
					for($repeatIndex = 0; $repeatIndex < $repeatCount; $repeatIndex++)
					{
						
						$Repetition = ($repeatIndex == 2 && sizeof($usedArray) == 2) ? true : (sizeOf($usedArray) > 2 && ($repeatIndex == 0 || $repeatIndex == 2)) ? true : false; // wenn ersteres der Fall ist, dann muss nur das erste überprüft werden... wenn letzteres der Fall ist, nur das zweite
						
						$FormField = new FormField($usedArray[$repeatIndex] . " " . $data['Field'], $data['Type'], $data['Null'], $Repetition);
						$Formfields[$i] = $FormField;
						$i++;
					}
					
				}
			}
			
			return $Formfields;
		}
		
		private function validate($Values) // The Values must be in correct order
		{
			$FormFields = $this->getAllFields();
			
			$i = 0;
			
			foreach($FormFields as $FormField)
			{
				$FormField->FieldValue = $Values[$i];
				
				$i++;
			}
			
			$this->Validator->validateAll($FormFields);
		}
			
		public function validationSuccessful($Values)
		{
			$this->validate($Values);
			$validationSuccessfull = $this->Validator->validationSuccessful();
			$this->Validator->errorValidation = "";
			
			return $validationSuccessfull;
		}
		
		public function showValidationResult($Values) // returns the errors
		{
			$this->validate($Values);
			
			return $this->Validator->errorValidation;
		}
		
		private function showCaptcha()
		{
			echo '<div id="recaptcha_widget" style="display:none">' . PHP_EOL . 
						'<div class="recaptcha_only_if_no_incorrect_sol"></div>' . PHP_EOL .
						'<div class="form-group">' . PHP_EOL . 
							'<label class="col-sm-2 control-label">reCAPTCHA</label>' . PHP_EOL . 
							'<div class="col-sm-10">' . PHP_EOL . 
								'<!--<a id="recaptcha_image" class="thumbnail"></a>-->' . PHP_EOL . 
								'<div id="recaptcha_image"></div>' . PHP_EOL . 
							'</div>' . PHP_EOL . 
						'</div>' . PHP_EOL . 
						'<div class="form-group">' . PHP_EOL . 
							'<label for="recaptcha_response_field" class="col-sm-2 recaptcha_only_if_image control-label">Angezeigte Wörter</label>' . PHP_EOL . 
							'<label for="recaptcha_response_field" class="col-sm-2 recaptcha_only_if_audio control-label">Gehörte Zahlen</label>' . PHP_EOL . 
							'<div class="col-sm-10">' . PHP_EOL . 
								'<div class="input-group">' . PHP_EOL . 
									'<input type="text" class="form-control" id="recaptcha_response_field" name="recaptcha_response_field" placeholder="reCAPTCHA">' . PHP_EOL . 
									'<span class="input-group-btn">' . PHP_EOL . 
										'<a class="btn btn-warning" href="javascript:Recaptcha.reload()"><span class="glyphicon glyphicon-refresh"></span></a>' . PHP_EOL . 
										'<a class="btn btn-primary recaptcha_only_if_image" href="javascript:Recaptcha.switch_type(' . "'audio'" . ')"><span title="Audio-CAPTCHA anfordern" class="glyphicon glyphicon-headphones"></span></a>' . PHP_EOL . 
										'<a class="btn btn-primary recaptcha_only_if_audio" href="javascript:Recaptcha.switch_type(' . "'image'" . ')"><span title="Bild-CAPTCHA anfordern" class="glyphicon glyphicon-picture"></span></a>' . PHP_EOL . 
										'<a class="btn btn-info" href="javascript:Recaptcha.showhelp()"><span class="glyphicon glyphicon-question-sign"></span></a>' . PHP_EOL . 
									'</span>' . PHP_EOL . 
								'</div>' . PHP_EOL . 
							'</div>' . PHP_EOL . 
						'</div>' . PHP_EOL . 
					'</div>';
		}
	}
?>