<?php
	namespace Guestbook\Models;

	require_once("php/BusinessObjects/Group.php");
	
	class GroupDbModel
	{
		public static $DB;
		
		public function read($idGroup)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$stmt = self::$DB->prepare("SELECT id_group, groupname, rights FROM groups where id_group = ?");
			$stmt->bind_param("i", $idGroup);
			
			$group = "";
						
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				
				$group = new \Guestbook\BusinessObjects\Group($row["id_group"], $row["groupname"], explode(",", $row["rights"]));
			}
			
			return $group;
		}
		
		public function readAll()
		{
			// wird in diesem Fall nicht unterstützt
		}
		
		public function create()
		{
			// wird in diesem Fall nicht unterstützt
		}
		
		public function delete()
		{
			// wird in diesem Fall nicht unterstützt
		}
		
		public function update()
		{
			// wird in diesem Fall nicht unterstützt
		}


	}

?>