<?php
	namespace Guestbook\BusinessObjects;

	require_once("php/Models/GroupDbModel.php");
	
	class User
	{
		public $idUser;
		public $idGroup;
		public $Firstname;
		public $Lastname;
		public $Nickname;
		public $Password;
		public $Email;
		public $Website;
		public $Place;
		public $BirthDate;
		public $ModificationDate;
		public $CreationDate;
	
		public function __construct()
		{
			
		}
		
		public function getFormattedBirthDate()
		{
			return date("d.m.Y", strtotime($this->BirthDate));
		}
		
		public function getFormattedCreationDate()
		{
			return date("d.m.Y", strtotime($this->CreationDate));
		}
		
		public function getGroup()
		{
			$Group = \Guestbook\Models\GroupDbModel::read($this->idGroup);

			return $Group;
		}
		
		public function getAllEntries()
		{
			$Entries = \Guestbook\Models\EntryDbModel::readAll();
			
			$AmountEntries = 0;
			
			foreach($Entries as $Entry)
			{
				if($Entry->idUser == $this->idUser)
				{
					$AmountEntries++;
				}
			}
			
			return $AmountEntries;
			
		}
		
		public function hasRight($Right)
		{
			return $this->getGroup()->hasRight($Right);
		}
			
		public function show()
		{
			$UserInformations = get_object_vars($this);

			echo "<table class='table table-striped'>";
			foreach ($UserInformations as $name => $value) {
				if($name != 'idGroup' && $name != 'UserImage' && $name != 'idUser' && $name != 'Password' && $name != 'ModificationDate' && $name != 'CreationDate' && $name != 'BirthDate')
				{
					echo "<tr>" . PHP_EOL;
					echo "<td>". $name ."</td>" . PHP_EOL;
					echo "<td>" . $value . "</td>". PHP_EOL;
					echo "</tr>" . PHP_EOL;
				}
			}
			echo "<tr>" . PHP_EOL;
			echo "<td>Group:</td>" . PHP_EOL;
			echo "<td>". $this->getGroup()->GroupName."</td>" . PHP_EOL;
			echo "</tr>" . PHP_EOL;
			
			echo "<tr>" . PHP_EOL;
			echo "<td>Entries:</td>" . PHP_EOL;
			echo "<td>". $this->getAllEntries()."</td>" . PHP_EOL;
			echo "</tr>" . PHP_EOL;
			
			echo "<tr>" . PHP_EOL;
			echo "<td>BirthDate:</td>" . PHP_EOL;
			echo "<td>". $this->getFormattedBirthDate()."</td>" . PHP_EOL;
			echo "</tr>" . PHP_EOL;
			
			echo "<tr>" . PHP_EOL;
			echo "<td>CreationDate:</td>" . PHP_EOL;
			echo "<td>". $this->getFormattedCreationDate()."</td>" . PHP_EOL;
			echo "</tr>" . PHP_EOL;
			
			echo "</table>";
		}
			
		/* SQL Functions*/
		
		public function update()
		{
			$this->ModificationDate = date("Y-m-d", time());
			$updateSuccessfull = \Guestbook\Models\UserDbModel::update($this);
			
			return $updateSuccessfull;
		}
		
		public function delete()
		{
			$deleteSuccessfull = \Guestbook\Models\UserDbModel::delete($this);
			
			return $deleteSuccessfull;
		}
		
		public function create()
		{
			$this->CreationDate = date("Y-m-d", time());
			$createSuccessfull = \Guestbook\Models\UserDbModel::create($this);
			
			return $createSuccessfull;
		}
		
	}
?>