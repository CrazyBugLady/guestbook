<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/gb.class.php");

	$User = \Guestbook\UserAuthenticator::getLoggedInUser();
	
	$currentPage = 1;
	
	if(array_key_exists("page", $_REQUEST))
	{
		$currentPage = $_REQUEST["page"];
	}
	
	Guestbook\gb::showEntries($User, $currentPage);
			
?>