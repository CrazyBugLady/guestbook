<?php

	namespace Guestbook;
	
	session_start();
		
	require_once("BusinessObjects/User.php");
	require_once("Models/UserDbModel.php");
	
	class UserAuthenticator
	{
		
		// Passwortverschlüsselung
		public static function encrypt($Value)
		{
			$Salt = "gb_3020_salt_M151"; 
			$Hash = hash('sha256', $Salt . $Value);
			
			return $Hash;
		}
		
		// User einloggen mit Cookie
		public static function AutoLogin()
		{
			if(isset($_COOKIE["UserId"]) && isset($_COOKIE["UserToken"]))
			{
				$UserID = base64_decode($_COOKIE["UserId"]);

				$User = Models\UserDbModel::read($UserID);
		
				$TokenComparisonString = self::encrypt("|" . $User->Password . "|" . $User->BirthDate . "|" . $User->Nickname . "|");
				
				if($_COOKIE["UserToken"] == $TokenComparisonString)
				{
					self::logIn($User, false);
				}
			}
		}
		
		// prüfen, ob der User bereits eingeloggt ist
		public static function isUserAlreadyLoggedIn()
		{
			// User einloggen mit Session (beruhend auf Cookie) => sofern überhaupt eingeloggt
			self::AutoLogin();
			if(isset($_SESSION["User"]))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public static function logIn($User, $withAutoLogin)
		{
			$_SESSION["User"] = serialize($User);
			
			if($withAutoLogin)
			{
				setcookie("UserId", base64_encode($User->idUser), time()+(3600*24*100));
				setcookie("UserToken",self::encrypt("|" . $User->Password . "|" . $User->BirthDate . "|" . $User->Nickname . "|"), time()+(3600*24*100));
			}
		}
						
		// User ausloggen
		public static function logOut()
		{
			session_unset();
			
			if(array_key_exists("UserId", $_COOKIE) && array_key_exists("UserToken", $_COOKIE))
			{
				unset($_COOKIE["UserId"]);
				setcookie("UserId", '', time() - 3600); // empty value and old timestamp
				
				unset($_COOKIE["UserToken"]);
				setcookie("UserToken", '', time() - 3600); // empty value and old timestamp	
			}
		}
		
		public static function setPassword($User, $oldPassword, $newPassword, $repeatNewPassword)
		{			
			if(self::encrypt($oldPassword) == $User->Password && $repeatNewPassword == $newPassword) // zu dem Zeitpunkt ist das Userpasswort noch das alte Passwort, muss also nicht extra mitübergeben werden
			{
				$User->Password = self::encrypt($newPassword);
				
				return $User->update();
			}
			else
			{
				return false;
			}
		}
		
		public static function checkLogin($Nickname, $Password)
		{
			$Users = Models\UserDbModel::readAll(); 
			
			$validLogin = false;
			$currentUser = "";

			foreach($Users as $User)
			{
				if($User->Nickname == $Nickname && $User->Password == self::encrypt($Password))
				{
					self::logIn($User, true);
					return true;
				}
			}
			return false;
		}
		
		public static function register($UserTemp, $PasswortRepeat)
		{
			if($UserTemp->Password == $PasswortRepeat)
			{
				$Users = Models\UserDbModel::readAll();
				
				foreach($Users as $User)
				{
					if($User->Nickname == $UserTemp->Nickname)
					{
						return false;
					}
				}
				
				$UserTemp->Password = self::encrypt($PasswortRepeat);
				$RegistrySuccessfull = $UserTemp->create();
				return $RegistrySuccessfull;
			}
			
		}
		
		public static function getLogin($except, $repeat, $placeholders)
		{
			$LoginForm = new \FormularGenerator\formulargenerator("Login", "users", $except, $repeat, $placeholders, false);
			return $LoginForm;
		}
		
		public static function getRegistryForm($except, $repeat, $placeholders)
		{
			$RegisterForm = new \FormularGenerator\formulargenerator("Registrierungsformular", "users", $except, $repeat, $placeholders, false);
			return $RegisterForm;
		}
	
		public static function getLoggedInUser()
		{
			$User = "";
			if(self::isUserAlreadyLoggedIn())
			{
				$User = unserialize($_SESSION["User"]);
			}
			
			return $User;
		}
	}
?>