<?php
	namespace Guestbook;

	require_once("Models/EntryDbModel.php");
	require_once("BusinessObjects/Entry.php");
	require_once("BusinessObjects/User.php");
	
	class gb
	{	
		private $except;
		
		private static $perSite = 10,
				$Title = 'Guestbook';
		
		public static function countAllEntries()
		{
			$Entries = \Guestbook\Models\EntryDbModel::readAll();
			return sizeof($Entries);
		}
		
		public static function showEntries($loggedInUser)//$SortBy)
		{
			$Entries = \Guestbook\Models\EntryDbModel::readAll(); 
			
			//$EndPoint = $_SESSION["Startpoint"] + $this->perSite;
			
			echo "<h2>". self::$Title ."</h2>";
			
			echo "<h3>Einträge (". sizeof($Entries) .")</h3>";
			
			echo "<ul class='pagination'>" . PHP_EOL . 
					"<li><a href='#'>&laquo;</a></li>" . PHP_EOL . 
					"<li class='active'><a href='#'>1</a></li>" . PHP_EOL . 
					"<li><a href='#'>2</a></li>" . PHP_EOL . 
					"<li><a href='#'>3</a></li>" . PHP_EOL . 
					"<li><a href='#'>4</a></li>" . PHP_EOL . 
					"<li><a href='#'>&raquo;</a></li>" . PHP_EOL . 
				"</ul>";
			
			foreach($Entries as $Entry)
			{
				$User = $Entry->getUser();
				echo "<table class='table table-striped'>" . PHP_EOL;
				echo "<thead>" . PHP_EOL;
				echo "<tr>". PHP_EOL;
				echo "<td colspan='2'><a href='#' data-html='true' data-toggle='popover' data-content='Email: ".$User->Email."</br> Website: ".$User->Website."</br> Place: ".$User->Place."' data-placement='top' data-trigger='hover' data-title='User: ".$User->Nickname."'>".$User->Nickname."</a> von ". $User->Place ." schrieb am ".$Entry->getFormattedCreationDate()." ...</td>"; //<a href='mailto:".$User->Email."'>".$User->Nickname."</a> von ". $User->Place ." schrieb am ".$Entry->getFormattedCreationDate()." ...</th>". PHP_EOL;W
				echo "</tr>". PHP_EOL;
				echo "</thead>" . PHP_EOL;
				echo "<tr>". PHP_EOL;
				echo "<th colspan='2'>Title: " . $Entry->Title. "</th>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				echo "<tr>". PHP_EOL;
				echo "<td colspan='2'>" . $Entry->Comment . "</td>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				
				if($Entry->hasBeenModified())
				{
					echo "<tr>" . PHP_EOL;
					echo "<td>Dieser Eintrag wurde zuletzt überarbeitet am ".$Entry->getFormattedModificationDate()."</td>";
					echo "</tr>" . PHP_EOL;
				}
			
				echo "<tr>". PHP_EOL;
				echo "<td colspan='2'>" . self::setOptions($Entry, $loggedInUser) . "</td>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				echo "</table>". PHP_EOL;
			}
			
			echo "<a href='index.php?site=sign'>You want to make an entry?</a>";
			
			/*$Sites = round($AmountEntries / $this->perSite) + 1; 
			
			for($i = 1; $i <= $Sites; $i++)
			{
				if($i == $_SESSION['Startpoint'])
				{
					echo "<div class='text-center'><b>" . $i . "</b></div> ";
				}
				else
				{
					echo "<div class='text-center'>" . $i . "</div>";
				}
 			}*/
		}
		
		public static function setOptions($Entry, $User)
		{
			$edit = "<a href='index.php?site=edit&entry=". $Entry->idEntry ."'><span class='glyphicon glyphicon-pencil'>Edit</span></a>" . PHP_EOL;
			$delete = "<a href='#' data-href='index.php?site=delete&entry=". $Entry->idEntry ."' data-toggle='modal' data-target='#confirm-delete'><span class='glyphicon glyphicon-remove'>Delete</span></a>" . PHP_EOL;			
			
			$Options = "";
			
			if(($Entry->getUser()->Nickname == $User->Nickname && $User->hasRight("change_own")) || $User->hasRight("change_others"))
			{
				$Options .= $edit;
			}
	
			if(($Entry->getUser()->Nickname == $User->Nickname && $User->hasRight("delete_own")) || $User->hasRight("delete_others"))
			{
				$Options .= $delete;
			}
			
			if($Options == "")
			{
				$Options = "No options available";
			}
			
			return $Options;
			
		} 
		
		public static function getEntryForm($title, $datatable, $except, $repeat, $placeholders)
		{
			$EntryForm = new \FormularGenerator\formulargenerator($title, $datatable, $except, $repeat, $placeholders, false);
			return $EntryForm;
			//$EntryForm->createForm();
		}
		
		public function createEntry($idUser, $Titel, $Comment)
		{
			$GbNewEntry = $this->DB->prepare("INSERT INTO gbentries (CreationDate, Title, Comment, id_user) VALUES (NOW(), ?, ?, ?)");
			$GbNewEntry->bind_param("ssi", $Titel, $Comment, $idUser);
			
		}

	}
?>