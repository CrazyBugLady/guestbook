<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/Models/EntryDbModel.php");
	require_once("php/gb.class.php");

	require_once("php/inc/loginChecker.php");
?>
	<h2>Eintrag löschen</h2>
<?php
	if(array_key_exists("entry", $_REQUEST))
	{
		$Entry = Guestbook\Models\EntryDbModel::read($_REQUEST["entry"]);
			
		// hat der User das Recht, diesen Eintrag zu überarbeiten
		if($User->hasRight("delete_others") || ($User->hasRight("delete_own") && $Entry->getUser()->Nickname = $User->Nickname))
		{
			if($Entry->delete())
			{
				?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Löschen erfolgreich
						</div>
					
						<div class="panel-body">
							Der Eintrag wurde erfolgreich gelöscht. Zurück zum <a href='index.php?site=show'>Gästebuch?</a><br>
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
						Der Eintrag konnte nicht gelöscht werden, melde dich beim Administrator.
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
						Du hast nicht das Recht, diesen Eintrag zu löschen. Zurück zum <a href='index.php?site=show'>Gästebuch</a>.
					</div>
				</div>
			<?php
		}
	}
?>