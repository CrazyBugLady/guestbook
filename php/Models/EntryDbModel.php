<?php
	namespace Guestbook\Models;

	require_once("php/Models/iDbModel.php");
	require_once("php/Data/DB.class.php");
	require_once("php/BusinessObjects/Entry.php");
	
	class EntryDbModel //extends iDbModel
	{
		public static $DB;
	
		public function __construct()
		{
			
		}
		
		public static function read($idEntry)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("SELECT id, Title, Comment, ModificationDate, CreationDate, id_user from gbentries where id = ?");
			$stmt->bind_param("i", $idEntry);
			
			$entry = "";
			
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				
				$entry = new \Guestbook\BusinessObjects\Entry($row["id"], $row["CreationDate"], $row["ModificationDate"], $row["Title"], $row["Comment"], $row["id_user"]);
			}
			
			return $entry;
		}
		
		public static function readAll($filter = null)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("SELECT id, Title, Comment, ModificationDate, CreationDate, id_user FROM gbentries");
			
			$entries = array();
			
			if ($stmt->execute()) {
			
			$result = $stmt->get_result();
			
			$i = 0;
				while ($row = $result->fetch_assoc()) {
					$i++;
				
					$entry = new \Guestbook\BusinessObjects\Entry($row["id"], $row["CreationDate"], $row["ModificationDate"], $row["Title"], $row["Comment"], $row["id_user"]);
					
					$entries[$i] = $entry;
				}
			}
			
			self::$DB->close();
			
			return $entries;
		}
		
		public static function create($entry)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("insert", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("INSERT INTO gbentries" .
									   " (Title, Comment, CreationDate, id_user)" .
									   " VALUES (?, ?, ?, ?)");
			$stmt->bind_param("sssi", $entry->Title, $entry->Comment, $entry->CreationDate, $entry->idUser);
			
			$successCreate = $stmt->execute();
			
			self::$DB->close();
			
			return $successCreate;
		}
		
		public static function update($entry)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("edit", "Resources/Configuration/config.ini");

			$stmt = self::$DB->prepare("UPDATE gbentries SET ModificationDate = ?, " . 
													"Title = ?, " .
													"Comment = ? " .
									   "WHERE id = ?");
			$stmt->bind_param("sssi", $entry->ModificationDate, $entry->Title, $entry->Comment, $entry->idEntry);
			
			$successUpdate = $stmt->execute();
			
			self::$DB->close();
			
			return $successUpdate;
		}
		
		public function delete($entry)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("delete", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("DELETE FROM gbentries WHERE id = ?");
			$stmt->bind_param("i", $entry->idEntry);
			
			$successDelete = $stmt->execute();
			
			return $successDelete;
		}

	}

?>