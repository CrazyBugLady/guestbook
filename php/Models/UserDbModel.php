<?php
	
	namespace Guestbook\Models;
		
	require_once("php/Models/iDbModel.php");
	require_once("php/Data/DB.class.php");
	require_once("php/BusinessObjects/User.php");

	class UserDBModel //extends iDbModel 
	{
		public static $DB;
	
		public function __construct()
		{
			
		}
		
		public static function read($idUser)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
	
			$stmt = self::$DB->prepare("SELECT id, Nickname, Firstname, Lastname, Passwort_p, CreationDate, Website, Email_e, BirthDate, id_group, ModificationDate, Place FROM users WHERE id = ?");
			$stmt->bind_param("i", $idUser);
			
			$user = new \Guestbook\BusinessObjects\User();
			
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
		
				$user->idUser = $row["id"];
				$user->Firstname = $row["Firstname"];
				$user->Lastname = $row["Lastname"];
				$user->Nickname = $row["Nickname"];
				$user->Password = $row["Passwort_p"];
				$user->CreationDate = $row["CreationDate"];
				$user->Website = $row["Website"];
				$user->Email = $row["Email_e"];
				$user->BirthDate = $row["BirthDate"];
				$user->idGroup = $row["id_group"];
				$user->ModificationDate = $row["ModificationDate"];
				$user->Place = $row["Place"];
			
			}
	
			return $user;
		}
		
		public static function readAll($filter = null)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("SELECT id, Nickname, Lastname, Firstname, Passwort_p, CreationDate, Website, ModificationDate, Place, Email_e, BirthDate, id_group FROM users");
			
			$users = array();
			
			if ($stmt->execute()) {
			
			$result = $stmt->get_result();
			
			$i = 0;
				while ($row = $result->fetch_assoc()) {
					$i++;
					
					$user = new \Guestbook\BusinessObjects\User();
					$user->idUser = $row["id"];
					$user->idGroup = $row["id_group"];
					$user->Nickname = $row["Nickname"];
					$user->Firstname = $row["Firstname"];
					$user->Lastname = $row["Lastname"];
					$user->Password = $row["Passwort_p"];
					$user->Email = $row["Email_e"];
					$user->Website = $row["Website"];
					$user->Place = $row["Place"];
					$user->BirthDate = $row["BirthDate"];
					$user->ModificationDate = $row["ModificationDate"];
					$user->CreationDate = $row["CreationDate"];

					$users[$i] = $user;
				}
			}

			return $users;
		}
		
		public static function create($user)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("insert", "Resources/Configuration/config.ini");
			$stmt = self::$DB->prepare("INSERT INTO users (Nickname, Lastname, Firstname, Passwort_p, CreationDate, Website, Email_e, Place, BirthDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssssssss", 
							  $user->Nickname, $user->Lastname, $user->Firstname, $user->Password, $user->CreationDate, $user->Website, $user->Email, $user->Place, $user->BirthDate);
			$successCreate = $stmt->execute();
			
			self::$DB->close();
			
			return $successCreate;
		}
		
		public static function update($user)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("edit", "Resources/Configuration/config.ini");
			$stmt = self::$DB->prepare("UPDATE users SET Passwort_p = ?, " .
													"Lastname = ?," .
													"Email_e = ?, " .
													"Website = ?, " .
													"Place = ? " .
									   "WHERE id = ?");
			$stmt->bind_param("sssssi", $user->Password, $user->Lastname, $user->Email, $user->Website, $user->Place, $user->idUser);
			$successUpdate = $stmt->execute();	

			self::$DB->close();			
			
			return $successUpdate;
		}
		
		public static function delete($user)
		{
			self::$DB = \Guestbook\Data\DB::getConnection("delete", "Resources/Configuration/config.ini");
			$stmt = self::$DB->prepare("DELETE FROM users WHERE id = ?");
			$stmt->bind_param("i", $user->idUser);
			$successDelete = $stmt->execute();			
			
			self::$DB->close();
			
			return $successUpdate;
		}

	}

?>