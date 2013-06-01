<?php
namespace ProductLabels;

class DB{

	protected static $connections = array();

	public static function addConnection($name, $connection)
	{
		static::$connections[$name] = $connection;
	}

	public static function connection($name)
	{
		return isset(static::$connections[$name]) ? static::$connections[$name] : false;
	}

}