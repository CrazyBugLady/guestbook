<?php
	namespace Guestbook\BusinessObjects;
	
	class Group
	{
		public $idGroup;
		public $GroupName;
		public $Rights;
	
		public function __construct($idGroup, $GroupName, $Rights)
		{
			$this->idGroup = $idGroup;
			$this->GroupName = $GroupName;
			$this->Rights = $Rights;
		}
		
		public function hasRight($Right)
		{
			return in_array($Right, $this->Rights);
		}
		
	}
?>