<?php
	namespace Guestbook\BusinessObjects;
	
	require_once("php/Models/UserDbModel.php");
	require_once("php/Models/EntryDbModel.php");

	class Entry
	{
		public $idEntry;
		public $idUser;
		public $CreationDate;
		public $Title;
		public $Comment;
		public $ModificationDate;
	
		public function __construct($idEntry, $CreationDate, $ModificationDate, $Title, $Comment, $idUser)
		{
			$this->ModificationDate = $ModificationDate;
			$this->CreationDate = $CreationDate;
			$this->idEntry = $idEntry;
			$this->Title = $Title;
			$this->Comment = $Comment;
			$this->idUser = $idUser;
		} 
		
		public function getUser()
		{
			$User = \Guestbook\Models\UserDbModel::read($this->idUser);
			
			return $User;
		}
		
		public function getFormattedModificationDate()
		{
			return date("d.m.Y", strtotime($this->ModificationDate));
		}
		
		public function getFormattedCreationDate()
		{
			return date("d.m.Y", strtotime($this->CreationDate));
		}
		
		public function hasBeenModified()
		{
			return $this->ModificationDate != "0000-00-00";
		}
		
		public function create()
		{
			$this->CreationDate = date("Y-m-d", time());
			$createSuccessfull = \Guestbook\Models\EntryDbModel::create($this);
			
			return $createSuccessfull;
		}
		
		public function delete()
		{
			$deleteSuccessfull = \Guestbook\Models\EntryDbModel::delete($this);
			
			return $deleteSuccessfull;
		}
		
		public function update()
		{
			$this->ModificationDate = date("Y-m-d", time());
			$updateSuccessfull = \Guestbook\Models\EntryDbModel::update($this);
			
			return $updateSuccessfull;
		}
		
	}
?>