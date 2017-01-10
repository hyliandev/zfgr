<?php

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
			'dont_load'=>true,
			'minlength'=>5,
			'prepare'=>'CMS::passwordHash',
			'prepare_after'=>true,
			'required'=>true
		]
	];
	
	public static $table='users';
}

?>