<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/UserAuthenticator.php");
	
	$Active = (array_key_exists("active", $_REQUEST)) ? $_REQUEST["active"] : "profile";
	
	if($Active == 'deleteProfile')
	{
		// User ausloggen	
		$UserTemp = $User;
		
		\Guestbook\Userauthenticator::logOut();
		if($UserTemp->delete())
		{
			header("Location: http://localhost/GUESTBOOK/GUESTBOOK/index.php?site=start");				
		}
	}
	
	require_once("php/inc/loginChecker.php");
?>
	<h1>Edit Profile</h1>

	<div id="content">
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li <?php if($Active == "profile"){ echo "class='active'"; } ?>><a href="#profile" data-toggle="tab"><span class='glyphicon glyphicon-user'> Übersicht</span></a></li>
        <li <?php if($Active == "passwort") { echo "class='active'"; } ?>><a href="#passwort" data-toggle="tab"><span class='glyphicon glyphicon-pencil'> Passwort</span></a></li>
        <li <?php if($Active == "contactdata") { echo "class='active'"; } ?>><a href="#contactdata" data-toggle="tab"><span class='gylphicon glyphicon-envelope'> Kontaktdaten</span></a></li>
        <li <?php if($Active == "deleteProfile") { echo "class='active'"; } ?>><a href="#deleteProfile" data-toggle="tab"><span class='glyphicon glyphicon-trash'> Profil löschen</span></a></li>
    </ul>
    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane <?php if((array_key_exists("active", $_REQUEST) && $_REQUEST["active"] == "profile") || array_key_exists("active", $_REQUEST) == false) { echo "active"; } ?>" id="profile">
            <h2>Profilübersicht</h2>
            <?php
				$User->show();
			?>
        </div>
        <div class="tab-pane <?php if($Active == "passwort") { echo "active"; } ?>" id="passwort">
            <?php
				$PasswordForm = new \FormularGenerator\formulargenerator("Passwort ändern", "users", array('Nickname', 'Lastname', 'Firstname', 'BirthDate', 'CreationDate', 'ModificationDate', 'id', 'id_group', 'UserImage_i', 'Email_e', "Website", 'Place'), array("Passwort_p", "Passwort_p"), array(), false);
				$PasswordForm->createForm("index.php?site=profile&active=passwort");
				
				if(array_key_exists("pwPasswort", $_REQUEST))
				{	
					if($PasswordForm->validationSuccessful(array($_REQUEST["pwPasswort"], $_REQUEST["pwnewPasswort"], $_REQUEST["pwrepeatPasswort"])))
					{
						if(\Guestbook\UserAuthenticator::setPassword($User, $_REQUEST["pwPasswort"], $_REQUEST["pwnewPasswort"], $_REQUEST["pwrepeatPasswort"]))
						{
						?>
							<div class="panel panel-success">
								<div class="panel-heading">
									Ändern des Passworts erfolgreich
								</div>
					
								<div class="panel-body">
									Du konntest dein Passwort erfolgreich ändern. Zurück zum <a href='index.php?site=show'>Gästebuch</a>?
								</div>
							</div>
						<?php
						}
						else
						{
						?>
							<div class="panel panel-danger">
								<div class="panel-heading">
									Ändern des Passworts nicht erfolgreich
								</div>
					
								<div class="panel-body">
									Du konntest dein Passwort nicht ändern, überprüfe folgende Kriterien:
									<ul>	
										<li>Altes Passwort richtig</li>
										<li>Neues Passwort und Wiederholung davon stimmen überein</li>
										<li>Wende dich bei Zweifeln an den Administrator!</li>
									</ul>
								</div>
							</div>
						<?php
						}
					}
					else
					{
						?>
							<div class="panel panel-danger">
								<div class="panel-heading">
									Ändern des Passworts nicht erfolgreich
								</div>
					
								<div class="panel-body">
									<?php
										echo $PasswordForm->showValidationResult(array($_REQUEST["pwPasswort"], $_REQUEST["pwnewPasswort"], $_REQUEST["pwrepeatPasswort"]));
									?>
								</div>
							</div>
						<?php
					}
				}
			?>
        </div>
        <div class="tab-pane <?php if($Active == "contactdata") { echo "active"; } ?>" id="contactdata">
            <?php
				$Lastname = $User->Lastname;
				$Website = $User->Website;
				$Email = $User->Email;
				$Place = $User->Place;
			
				if($Active == 'contactdata' && array_key_exists("submit", $_REQUEST))
				{
					$Lastname = $_REQUEST["tbLastname"];
					$Website = $_REQUEST["tbWebsite"];
					$Email = $_REQUEST["tbEmail"];
					$Place = $_REQUEST["tbPlace"];
				}
			
				$ContactDataForm = new \FormularGenerator\formulargenerator("Kontaktdaten ändern", "users", array('id_group', 'id', 'Nickname', 'Firstname', 'BirthDate', 'CreationDate', 'ModificationDate', 'Passwort_p', 'UserImage_i'), array(), array($Lastname, $Website, $Email, $Place), false);
				$ContactDataForm->createForm("index.php?site=profile&active=contactdata");
				
				if($Active == 'contactdata' && array_key_exists("submit", $_REQUEST))
				{
					if($ContactDataForm->validationSuccessful(array($_REQUEST["tbLastname"], $_REQUEST["tbWebsite"], $_REQUEST["tbEmail"], $_REQUEST["tbPlace"])))
					{
						$User->Lastname = $_REQUEST["tbLastname"];
						$User->Website = $_REQUEST["tbWebsite"];
						$User->Email = $_REQUEST["tbEmail"];
						$User->Place = $_REQUEST["tbPlace"];
						
						if($User->update())
						{
						?>
							<div class="panel panel-success">
								<div class="panel-heading">
									Ändern der Kontaktdaten erfolgreich
								</div>
					
								<div class="panel-body">
									Du konntest deine Kontaktdaten erfolgreich ändern. Zurück zum <a href='index.php?site=show'>Gästebuch</a>?
								</div>
							</div>
						<?php
						}	
						else
						{
							?>
							<div class="panel panel-danger">
								<div class="panel-heading">
									Fehler
								</div>
					
								<div class="panel-body">
									Es gab ein Problem beim Ändern der Daten. Kontaktiere den Administrator.
								</div>
							</div>
						<?php
						}
					}
					else
					{
					?>
							<div class="panel panel-danger">
								<div class="panel-heading">
									Ändern der Kontaktdaten nicht erfolgreich
								</div>
					
								<div class="panel-body">
									<?php
										echo $ContactDataForm->showValidationResult(array($_REQUEST["tbLastname"], $_REQUEST["tbWebsite"], $_REQUEST["tbEmail"], $_REQUEST["tbPlace"]));
									?>
								</div>
							</div>
						<?php
					}
				}

			?>
        </div>
        <div class="tab-pane <?php if($Active == "deleteProfile") { echo "active"; } ?>" id="deleteProfile">
            <h2>Profil löschen</h2>
			<p>Sofern du kein Interesse mehr an deiner Mitgliedschaft hast, kannst du dein Profil mit Klick auf den unteren Button löschen. 
			Hast du die gewünschte Löschung bestätigt, wirst du ausgeloggt und direkt zur Startseite weitergeleitet.</p>
            <p><button class='btn btn-danger' data-href='index.php?site=profile&active=deleteProfile' href='#' data-toggle='modal' data-target='#confirm-delete'><span class='glyphicon glyphicon-remove'>Delete</span></button></p>
        </div>
    </div>
</div>