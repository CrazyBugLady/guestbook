<?php
	namespace Guestbook\BusinessObjects;
	
	class EntryFilter
	{
		public $LimitStart;
		public $LimitEnd;
		
		public $OrderBy;
		public $OrderByDirection;
	
		public function __construct($LimitStart, $LimitEnd, $OrderBy, $OrderByDirection)
		{
			$this->LimitStart = ($LimitStart != "") ? $LimitStart : 0;
			$this->LimitEnd = ($LimitEnd != "") ? $LimitEnd : 1000;
			
			$this->OrderBy = $OrderBy;
			$this->OrderByDirection = $OrderByDirection;
		}
		
		public function addToSql($SQLString)
		{
			if($this->OrderBy != "")
			{
				$SQLString .= " ORDER BY " . $this->OrderBy . " " . $this->OrderByDirection;
			}
			
			$SQLString .= " LIMIT " . $this->LimitStart . ", " . $this->LimitEnd;
			
			return $SQLString;
		}
	}
?>