<?php
	namespace Guestbook\Models;
	
	interface iDbModel{
		public function __construct();
		
		public static function read($id);
		
		public static function readAll($filter);
		
		public static function update($object);
		
		public static function delete($object);
		
		public static function create($object);
	}
?>