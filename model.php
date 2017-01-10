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
		$table=self::tableName();
		
		$errors=[];
		
		foreach(get_called_class()::$data as $key=>$value){
			if(!isset($this->$key) && empty($value['required'])) continue;
			
			if(!empty($value['required']) && empty($this->$key))
				$errors[$key]='This field is required.';
			elseif(!empty($value['minlength']) && strlen($this->$key) < $value['minlength'])
				$errors[$key]='This field must be at least ' . $value['minlength'] . ' characters long';
			elseif(!empty($value['maxlength']) && strlen($this->$key) > $value['maxlength'])
				$errors[$key]='This field must be less than ' . $value['maxlength'] . ' characters long';
			elseif(!empty($value['unique']) &&
				CMS::$DB->query("SELECT COUNT(*) AS x FROM $table WHERE $key=" . CMS::$DB->quote($this->username))->fetch(PDO::FETCH_ASSOC)['x']
			)
				$errors[$key]='This field must be unique; an entry with this value already exists';
		}
		
		return $errors;
	}
	
	public function save(){
		$class=get_called_class();
		
		foreach($class::$data as $key=>$value){
			if(!empty($function=$value['prepare']) && empty($value['prepare_after']))
				$this->$key=call_user_func($function,$this->$key);
		}
		
		if(!empty($errors=$this->verify())) return $errors;
		
		$table=self::tableName();
		$sql='';
		
		if(!empty($this->id)){
			// update
		}else{
			// insert
			$this->id=0;
			$list=[];
			foreach($class::$data as $key=>$value){
				if(isset($this->$key)) $list[]=$key;
			}
			
			$sql="INSERT INTO $table ( " . implode(',',$list) . " ) VALUES (";
			
			$values=[];
			foreach($list as $node){
				$value=$this->$node;
				
				if(!empty($function=$class::$data[$node]['prepare']) && !empty($class::$data[$node]['prepare_after']))
					$value=call_user_func($function,$value);
				
				$values[]=CMS::$DB->quote($value);
			}
			$sql.=implode(',',$values);
			
			$sql.=");";
		}
		CMS::$DB->query($sql);
		
		return true;
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
			'required'=>true,
			'unique'=>true
		],
		
		'password'=>[
			'datatype'=>'varchar(255)',
			'minlength'=>5,
			'prepare'=>'CMS::passwordHash',
			'prepare_after'=>true,
			'required'=>true
		]
	];
	
	public static $table='users';
}

$user=new User();
$user->username=base64_encode(rand());
$user->password='boobs';

if(is_array($errors=$user->save())){
	die(CMS::debug($errors));
}

?>