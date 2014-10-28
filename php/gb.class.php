<?php
	namespace Guestbook;

	require_once("Models/EntryDbModel.php");
	require_once("BusinessObjects/Entry.php");
	require_once("BusinessObjects/User.php");
	require_once("BusinessObjects/EntryFilter.php");
	
	class gb
	{	
		private $except;
		
		private static $perSite = 5,
				$Title = 'Guestbook';
		
		public static function countAllEntries()
		{
			$Entries = \Guestbook\Models\EntryDbModel::readAll();
			return sizeof($Entries);
		}
		
		// Entries anzeigen mit selbst generiertem Filter
		public static function showEntries($loggedInUser, $Page)
		{			
			$Filter = self::generateFilter(0, "CreationDate", "desc"); 
			
			$EntriesAll = \Guestbook\Models\EntryDbModel::readAll($Filter); // um alle Einträge zu kriegen, zuerst nicht Paging aktivieren
			
			$Filter = self::generateFilter($Page, "CreationDate", "desc");
			
			$EntriesCurrentPage = \Guestbook\Models\EntryDbModel::readAll($Filter); // um nur Einträge von der aktuellen Page zu erhalten
			
			echo "<h2>". self::$Title ."</h2>";
			
			echo "<h3>Einträge (". sizeof($EntriesAll) .")</h3>";
			
			self::setPaging(sizeof($EntriesAll), $Page);
					
			if($loggedInUser != "")
			{
				echo "<p><a href='index.php?site=sign'>You want to make an entry?</a></p>" . PHP_EOL;
			}
			
			foreach($EntriesCurrentPage as $Entry)
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
				
				if($loggedInUser != "")
				{
					echo "<tr>". PHP_EOL;
					echo "<td colspan='2'>" . self::setOptions($Entry, $loggedInUser) . "</td>". PHP_EOL;
					echo "</tr>". PHP_EOL;
					echo "</table>". PHP_EOL;
				}
				
			}
			if(sizeof($EntriesCurrentPage) == 0)
			{
				echo "<p>Auf dieser Seite gibt es keine Einträge!</p>" . PHP_EOL;
			}
		}
		
		public static function setPaging($Entries, $currentPage)
		{
			$SiteAmount = ceil($Entries / self::$perSite);
			
			echo "<ul class='pagination'>" . PHP_EOL;
			
			for ($Page = 1; $Page <= $SiteAmount; $Page++)
			{
				$ActiveAttribute = ($Page == $currentPage) ? "class='active'" : "";
				echo "<li ". $ActiveAttribute ."><a href='index.php?site=show&page=". $Page ."'>". $Page ."</a></li>" . PHP_EOL; 
			}
			
			echo "</ul>" . PHP_EOL;
		}
		
		public static function generateFilter($currentPage, $orderBy, $orderByDirection)
		{
			$Endpoint = 0;
			$Startpoint = 0;
			
			if($currentPage != 0)
			{
				$Endpoint = $currentPage * self::$perSite; // bsp: Seite 2 = Endpunkt 20
				$Startpoint = $Endpoint - self::$perSite; // bsp: Seite 2 = Endpunkt 20 - 10 = 10
			}
			
			$generatedFilter = new \Guestbook\BusinessObjects\EntryFilter($Startpoint, $Endpoint, $orderBy, $orderByDirection);
			
			return $generatedFilter;
		}
		
		// Options depending on User Rights
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