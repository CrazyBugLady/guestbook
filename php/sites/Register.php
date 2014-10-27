<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/BusinessObjects/User.php");
	require_once("php/UserAuthenticator.php");

	$User = \Guestbook\UserAuthenticator::getLoggedInUser();
	$Nickname = "";
	$Firstname = "";
	$Lastname="";
	$Website = "";
	$Email ="";
	$Place = "";
	$BirthDate = "";
	
	if(array_key_exists("submit", $_REQUEST))
	{
		$Nickname = $_REQUEST["tbNickname"];
		$Firstname = $_REQUEST["tbFirstname"];
		$Lastname = $_REQUEST["tbLastname"];
		$Website = $_REQUEST["tbWebsite"];
		$Email = $_REQUEST["tbEmail"];
		$Place = $_REQUEST["tbPlace"];
		$BirthDate = $_REQUEST["tbBirthDate"];
	}
	
	$RegisterForm = Guestbook\UserAuthenticator::getRegistryForm(array("id", "id_group", "ModificationDate", "CreationDate"), array("Passwort_p"), array($Nickname, $Firstname, $Lastname, "", "", $Website, $Email, $Place, $BirthDate));
?>
	<h1>Join da' partey</h1>
<?php
	if(array_key_exists("submit", $_REQUEST))
	{
		$BirthDate = date("Y-m-d", strtotime($BirthDate));
		$Password = $_REQUEST["pwPasswort"];
		$PasswordRepeat = $_REQUEST["pwrepeatPasswort"];
	
		if($RegisterForm->validationSuccessful(array($Nickname, $Firstname, $Lastname, $Password, $PasswordRepeat, $Website, $Email, $Place, $BirthDate)))
		{
			$UserTemp = new \Guestbook\BusinessObjects\User();
			$UserTemp->Nickname = $Nickname;
			$UserTemp->Firstname = $Firstname;
			$UserTemp->Lastname = $Lastname;
			$UserTemp->Password = $Password;
			$UserTemp->Website = $Website;
			$UserTemp->Email = $Email;
			$UserTemp->BirthDate = $BirthDate;
			$UserTemp->Place = $Place;
			
			if(\Guestbook\UserAuthenticator::register($UserTemp, $PasswordRepeat))
			{
			?>
				<div class="panel panel-success">
					<div class="panel-heading">
						Registrierung erfolgreich
					</div>
		
					<div class="panel-body">
						Du konntest dich erfolgreich anmelden. Zum <a href='index.php?site=login'>Login?</a><br>
					</div>
				</div>
			<?php
			}
			else
			{
				$RegisterForm->createForm("index.php?site=register");
			?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						Registrierung nicht erfolgreich
					</div>
		
					<div class="panel-body">
						Du konntest nicht erfolgreich angemeldet werden. Ist der Username eventuell schon vergeben?
					</div>
				</div>
			<?php
			}
		}
		else
		{
			$RegisterForm->createForm("index.php?site=register");
			?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						Registrierung nicht erfolgreich
					</div>
		
					<div class="panel-body">
						<?php
							$RegisterForm->showValidationResult();
						?>
					</div>
				</div>
			<?php
		}
	}
	else
	{
		$RegisterForm->createForm("index.php?site=register");
	}
?>