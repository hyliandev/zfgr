<?php

class Model {
	// == FUNCTIONS ==
	
	public function __construct(){
		
	}
	
	// Static
	
	public static function tableName(){
		return CMS::$DB_prefix . get_called_class()::$table;
	}
	
	public static function isInstalled(){
		$q=CMS::$DB->query("SELECT COUNT(*) FROM " . self::tableName() . " LIMIT 1");
		
		return !CMS::$DB->errorInfo()[1];
	}
	
	public static function install(){
		
	}
	
	public static function uninstall(){
		CMS::$DB->query("DROP TABLE " . self::tableName());
	}
	
	// Object
	
	public function save(){
		
	}
	
	
	
	
	
	
	
	
	
	
	// == VARIABLES ==
	
	public static $table='lol';
}

?>