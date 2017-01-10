<?php

class Model {
	// == FUNCTIONS ==
	
	public function __construct(){
		self::addIDColumn();
	}
	
	// Static
	
	public static function addIDColumn(){
		get_called_class()::$data['id']=[
			'datatype'=>'int unsigned not null auto_increment primary key'
		];
	}
	
	public static function tableName(){
		return CMS::$DB_prefix . get_called_class()::$table;
	}
	
	public static function isInstalled(){
		$q=CMS::$DB->query("SELECT COUNT(*) FROM " . self::tableName() . " LIMIT 1");
		
		return !CMS::$DB->errorInfo()[1];
	}
	
	public static function install($drop=false){
		$table=self::tableName();
		
		self::addIDColumn();
		
		$sql=($drop ? "DROP TABLE IF EXISTS $table;" : '') .
		"CREATE TABLE IF NOT EXISTS $table (";
		
		$values=[];
		foreach(get_called_class()::$data as $key=>$value){
			$values[]="$key $value[datatype]\n";
		}
		$sql.=implode(',',$values);
		
		$sql.=");";
		
		CMS::$DB->query($sql);
	}
	
	public static function uninstall(){
		CMS::$DB->query("DROP TABLE " . self::tableName());
	}
	
	// Object
	
	public function verify(){
		$errors=[];
		
		foreach(get_called_class()::$data as $key=>$value){
			
		}
	}
	
	public function save(){
		$this->verify();
		
		$table=self::tableName();
		$sql='';
		
		if(!empty($this->id)){
			// update
		}else{
			// insert
			$this->id=0;
			$list=[];
			foreach(get_called_class()::$data as $key=>$value){
				if(isset($this->$key)) $list[]=$key;
			}
			
			$sql="INSERT INTO $table ( " . implode(',',$list) . " ) VALUES (";
			
			$values=[];
			foreach($list as $node){
				$value=$this->$node;
				
				if(!empty($function=get_called_class()::$data[$node]['prepare']))
					$value=call_user_func($function,$value);
				
				$values[]=CMS::$DB->quote($value);
			}
			$sql.=implode(',',$values);
			
			$sql.=");";
		}
		CMS::$DB->query($sql);
	}
	
	
	
	
	
	
	
	
	
	
	// == VARIABLES ==
	
	// Static
	
	public static $data=[];
	
	public static $table;
}

class User extends Model {
	public static $data=[
		'username'=>[
			'datatype'=>'varchar(64)',
			'maxlength'=>64,
			'minlength'=>1,
			'unique'=>true
		],
		
		'password'=>[
			'datatype'=>'varchar(255)',
			'minlength'=>5,
			'prepare'=>'CMS::passwordHash'
		]
	];
	
	public static $table='users';
}

$user=new User();
$user->username='';
$user->password='boob';
$user->save();

?>