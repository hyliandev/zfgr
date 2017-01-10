<?php

class CMS {
	// == FUNCTIONS ==
	// ===============
	
	public static function debug($v){
		return '<pre>' . print_r($v,true) . '</pre>';
	}
	
	public static function view($view,$vars=[]){
		foreach($vars as $key=>$value){
			$$key=$value;
		}
		
		unset($vars);
		
		if(file_exists($file='views/' . $view . '.php')){
			ob_start();
			require_once $file;
			return ob_get_clean();
		}else
			echo '<div class="alert alert-danger">View <code>' . $view . '</code> not found</div>';
	}
	
	public static function passwordHash($str){
		return password_hash($str,PASSWORD_BCRYPT);
	}
	
	public static function URL(){
		$s=$_SERVER;
		
		return $s['REQUEST_SCHEME'] . '://' . $s['SERVER_NAME'] . str_replace('index.php','',$s['SCRIPT_NAME']);
	}
	
	public static function _URI(){
		if(strpos('//',CMS::$uri)!=-1){
			CMS::$uri=str_replace('//','/',CMS::$uri);
		}
		
		if(substr(CMS::$uri,0,1)=='/') CMS::$uri=substr(CMS::$uri,1);
		
		$ret='';
		for($i=0;$i<substr_count(CMS::$uri,'/');$i++){
			$ret.='../';
		}
		
		return $ret;
	}
	
	
	
	
	
	// == VARIABLES ==
	// ===============
	
	public static $DB;
	public static $DB_prefix='zfgr_';
	
	public static $site_title='ZFGR';
	public static $site_title_fill='Zelda Fan Game Resource';
	
	public static $uri='';
	public static $path=[];
	public static $file='controllers';
	public static $params=[];
	
	public static $session;
	
	public static $yield='';
}

try {
	CMS::$DB=new PDO('mysql:host=localhost;dbname=zfgr','root','');
}
catch(Exception $e){
	die('<h1>DATABASE ERROR:</h1><pre>' . $e . '</pre>');
}

?>