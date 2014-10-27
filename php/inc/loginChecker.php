<?php	
	$User = \Guestbook\UserAuthenticator::getLoggedInUser();
	
	if($User == "")
	{
		echo "<h1>Zutritt verweigert!</h1>";
		echo "Du hast nicht die Befugnis, dich hier aufzuhalten";
		exit();
	}
?>