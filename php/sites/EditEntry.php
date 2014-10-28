<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/BusinessObjects/Entry.php");
	require_once("php/Models/EntryDbModel.php");
	require_once("php/gb.class.php");

	require_once("php/inc/loginChecker.php");
	
	// Eintrag erfragen
	$Entry = \Guestbook\Models\EntryDbModel::read($_REQUEST["entry"]);
	
	// kreieren des Formulars zur Bearbeitung eines Eintrags
	$EntryForm = Guestbook\gb::getEntryForm("Gästebucheintrag bearbeiten", "gbentries", array("id", "id_user", "CreationDate", "ModificationDate"), array(), array($Entry->Title, $Entry->Comment));
	$EntryForm->createForm("index.php?site=edit&entry=" . $_REQUEST["entry"]);
	
	echo "<a href='index.php?site=show'>Zurück zum Gästebuch?</a>";
	
	if(array_key_exists("submit", $_REQUEST))
	{
		if($EntryForm->validationSuccessful(array($_REQUEST["tbTitle"], $_REQUEST["txtComment"])))
		{
			$Entry->Title = $_REQUEST["tbTitle"];
			$Entry->Comment = $_REQUEST["txtComment"];
			
			if($User->hasRight("change_others") || ($User->hasRight("change_own") && $Entry->getUser()->Nickname = $User->Nickname))
			{
				if($Entry->update())
				{
				?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Update erfolgreich
						</div>
						
						<div class="panel-body">
							Der Eintrag konnte geändert werden. Zurück zum <a href="index.php?site=show">Gästebuch</a>?<br>
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
						Achtung!
					</div>
					
					<div class="panel-body">
						Du hast nicht das Recht, diesen Eintrag zu bearbeiten. Zurück zum <a href='index.php?site=show'>Gästebuch</a>.
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