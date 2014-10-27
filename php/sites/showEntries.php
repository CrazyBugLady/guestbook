<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/gb.class.php");

	require_once("php/inc/loginChecker.php");
	
	Guestbook\gb::showEntries($User);
			
?>