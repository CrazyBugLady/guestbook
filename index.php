<?php
	require("php\FormularCreator/formulargenerator.class.php");
	require_once("php\UserAuthenticator.php");
	require_once("php\gb.class.php");
	
	$User = \Guestbook\UserAuthenticator::getLoggedInUser();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GuestBook</title>

	<link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">

  </head>
  <body>
  
 <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php?site=start" class="navbar-brand">Guestbook</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
		  <?php
			if(Guestbook\UserAuthenticator::isUserAlreadyLoggedIn())
			{
				include("php/inc/menu.logout.inc.php");
			}
			else
			{
				include("php/inc/menu.login.inc.php");
			}
		  ?>
        </div>
      </div>
    </div>
  
		<div class="container">
			
			<?php
			
						echo "<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>" . PHP_EOL .
						"<div class='modal-dialog'>" . PHP_EOL . 
							"<div class='modal-content'>" . PHP_EOL .
								"<div class='modal-header'>" . PHP_EOL .
									"Löschung" . PHP_EOL .
								"</div>" . PHP_EOL .
								"<div class='modal-body'>" . PHP_EOL .
									"Willst du die angeforderte Löschung wirklich bestätigen? (Achtung: kann NICHT rückgängig gemacht werden!)" . PHP_EOL .
								"</div>" . PHP_EOL .
								"<div class='modal-footer'>" . PHP_EOL .
									"<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>" . PHP_EOL .
										"<a href='#' class='btn btn-danger danger'>Delete</a>" . PHP_EOL .
								"</div>" . PHP_EOL . 
							"</div>" . PHP_EOL .
						"</div>" . PHP_EOL .
					   "</div>";
			
			if(Guestbook\UserAuthenticator::isUserAlreadyLoggedIn())
			{
				include_once("php/inc/sidebar.logout.inc.php");
			}
			else
			{
				include_once("php/inc/sidebar.login.inc.php");
			}
					   
			if(array_key_exists("site", $_REQUEST))
			{
				switch(strtolower($_REQUEST["site"]))
				{
					case "start":
						include_once("php/sites/Start.php");
						break;
					case "sign":
						include_once("php/sites/signIn.php");
						break;
					case "delete":
						include_once("php/sites/DeleteEntry.php");
						break;
					case "login":
						include_once("php/sites/Login.php");
						break;
					case "edit":
						include_once("php/sites/EditEntry.php");
						break;
					case "profile":
						include_once("php/sites/EditProfile.php");
						break;
					case "register":
						include_once("php/sites/Register.php");
						break;
					case "show":
						include_once("php/sites/showEntries.php");
						break;
					case "logout":
						include_once("php/sites/LogOut.php");
						break;
				}
			}
			else
			{
				include_once("php/sites/Start.php");
			}
			?>
				</div>
			</div>
		</div>

		
    <!-- jQuery (wird für Bootstrap JavaScript-Plugins benötigt) -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="Resources/js/bootstrap.min.js"></script>
    <script src="Resources/js/custom.js"></script>
  </body>
</html>