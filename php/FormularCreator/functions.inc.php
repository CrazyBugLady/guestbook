<?php
	/**
	* addFormDivs legt die Divs um die Formularfelder, damit diese ans aktuelle Design angepasst werden können.
	*/
	function addFormDivs($String, $Labelname, $FieldType)
	{
		$Beginning = "<div class='form-group'>\n";
		$Beginning .= "<label for='lbl" . $Labelname ."' class='col-sm-2 control-label'>" . $Labelname . "</label>\n";
		$Beginning .= "<div class='col-sm-10'>\n" . $String . "\n</div>\n";
		$Ending = "</div>\n";
		
		return $Beginning . $Ending;
	}
	
	/**
	*addRadioLabels ist eine Klasse, die die Labels bei den Radiobuttons added, die jeweils einzeln gesetzt werden müssen.
	*/
	function addRadioLabels($FieldType, $Labelname, $String)
	{
		switch($FieldType)
		{
			case FieldTypes::Radiobutton:
			return "<label class='radio-inline'>\n". $String . "</label>\n";
		}
	}
?>