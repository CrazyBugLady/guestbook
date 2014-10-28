<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/BusinessObjects/Entry.php");
	require_once("php/gb.class.php");

	require_once("php/inc/loginChecker.php");
	
	// kreieren des Formulars zur Bearbeitung eines Eintrags
	$EntryForm = Guestbook\gb::getEntryForm("Gästebucheintrag schreiben", "gbentries", array("id", "id_user", "CreationDate", "ModificationDate"), array(), array());
	$EntryForm->createForm("index.php?site=sign");
	
	echo "<a href='index.php?site=show'>Zurück zum Gästebuch</a>";
	
	if(array_key_exists("submit", $_REQUEST))
	{
		if($EntryForm->validationSuccessful(array($_REQUEST["tbTitle"], $_REQUEST["txtComment"])))
		{
			$Entry = new Guestbook\BusinessObjects\Entry("", "", "", $_REQUEST["tbTitle"], $_REQUEST["txtComment"], $User->idUser);
			
			if($Entry->create())
			{
				?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Eintrag erfolgreich
						</div>
						
						<div class="panel-body">
							Der Eintrag wurde erfolgreich gemacht. Sieh ihn dir jetzt im <a href="index.php?site=show">Gästebuch</a> an!<br>
						<div>
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
							Ein Fehler ist aufgetreten. Kontaktiere den Administrator.	
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
						Fehler
					</div>
					
					<div>
						<?php 
							$EntryForm->showValidationResult(array($_REQUEST["tbTitle"], $_REQUEST["txtComment"]));
						?>
					</div>
				</div>
			<?php
		}
	}
		
?>