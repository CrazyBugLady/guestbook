<?php
	require_once("php/UserAuthenticator.php");
	
	if(GuestBook\UserAuthenticator::logOut())
	{
		header('Location: http://localhost/GUESTBOOK/GUESTBOOK/index.php?site=start');
		exit;
	}
?>