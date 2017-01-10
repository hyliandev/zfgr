<?php

class Model {
	// == FUNCTIONS ==
	
	public function __construct(){
		self::addNecessaryColumns();
	}
	
	// Static
	
	public static function addNecessaryColumns(){
		$class=get_called_class();
		
		$class::$data=array_merge(['id'=>[
			'datatype'=>'int unsigned not null auto_increment primary key'
		]],$class::$data);
		
		$class::$data['time_created']=[
			'datatype'=>'int unsigned',
			'prepare'=>'getTimeIfNull'
		];
		
		$class::$data['time_updated']=[
			'datatype'=>'int unsigned',
			'prepare'=>'time'
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
		
		self::addNecessaryColumns();
		
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
	
	public static function get($fields='*',$where='',$limit=0,$offset=0){
		$class=get_called_class();
		$table=$class::tableName();
		
		$sql="SELECT $fields FROM $table";
		
		if(!empty($where)) $sql.=' WHERE ' .$where;
		
		if(!empty($limit)) $sql.=" LIMIT " . (!empty($offset) ? "$offset," : '') . $limit;
		
		if(!$q=CMS::$DB->query($sql)) return false;
		
		$fetch=$q->fetchAll(PDO::FETCH_ASSOC);
		
		$ret=[];
		foreach($fetch as $key=>$value){
			$r=new $class();
			foreach($value as $k=>$v){
				if(!empty($class::$data[$k]['dont_load'])) continue;
				$r->$k=$v;
			}
			
			if(count($fetch)==1) return $r;
			
			$ret[]=$r;
		}
		
		return $ret;
	}
	
	// Object
	
	public function verify(){
		$table=self::tableName();
		
		$errors=[];
		
		foreach(get_called_class()::$data as $key=>$value){
			if(!isset($this->$key) && (empty($value['required']) || isset($this->id))) continue;
			
			if(!empty($value['required']) && empty($this->$key))
				$errors[$key]='This field is required.';
			elseif(!empty($value['minlength']) && strlen($this->$key) < $value['minlength'])
				$errors[$key]='This field must be at least ' . $value['minlength'] . ' characters long';
			elseif(!empty($value['maxlength']) && strlen($this->$key) > $value['maxlength'])
				$errors[$key]='This field must be less than ' . $value['maxlength'] . ' characters long';
			elseif(
				!empty($value['unique'])
				&&
				($id=CMS::$DB->query("SELECT id FROM $table WHERE $key=" . CMS::$DB->quote($this->username))->fetch(PDO::FETCH_ASSOC)['id'])
				&&
				(empty($this->id) || $id!=$this->id)
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
		
		foreach($class::$data as $key=>$value){
			if(!empty($function=$value['prepare']) && !empty($value['prepare_after']))
				$this->$key=call_user_func($function,$this->$key);
		}
		
		$table=self::tableName();
		$sql='';
		
		if(!empty($this->id)){
			// update
			$sql="UPDATE $table SET ";
			
			$values=[];
			foreach($class::$data as $key=>$value){
				if(!empty($this->$key))
					$values[]=$key . '=' . CMS::$DB->quote($this->$key);
			}
			$sql.=implode(',',$values);
			
			$sql.=" WHERE $table.ID=" . CMS::$DB->quote($this->id);
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

?>