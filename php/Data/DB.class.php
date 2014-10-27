<?php
	namespace Guestbook\Data;
	
	class DB
	{
		public static function getConnection($DBFunction, $ConfigFile)
		{
			$ConfigData = parse_ini_file($ConfigFile, true);
			
			$instance = mysqli_connect($ConfigData[$DBFunction]["host"], $ConfigData[$DBFunction]["username"], $ConfigData[$DBFunction]["password"]); 
						
			// Verbindung zu MySQL
			if(!$instance)
			{
				echo "Keine Verbindung zu MySQL möglich.";
			}
	
			// Wählen der Datenbank
			if(!$instance->select_db($ConfigData["database_connect"]["database"]))
			{
				echo "Keine Verbindung zur Datenbank möglich.";
			}
	
			// Setzen des Charsets bei allfälligen Umlauten
			if (!$instance->set_charset("utf8")) {
				echo "Setzen des Charsets nicht möglich";
			}
			
			return $instance;
		}
		
	}
?>
