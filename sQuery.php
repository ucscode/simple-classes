<?php

// Created BY UCSCODE;

class sQuery {
	
	protected static $mysqli;
	
	public static function connect( MYSQLI $mysqli ) {
		self::$mysqli = $mysqli;
	}
	
	public static function select( $tablename, $condition = 1 ) {
		$SQL = "SELECT * FROM `{$tablename}` WHERE {$condition}";
		return $SQL;
	}
	
	private static function val( $value ) {
		if( is_numeric($value) ) return $value;
		else if( is_null($value) ) return 'NULL';
		else return "'{$value}'";
	}
	
	public static function insert( string $tablename, array $data ) {
		$columns = implode(", ", array_map(function($key) {
			return "`{$key}`";
		}, array_keys($data)));
		$values = array_map(function($value) {
			if( self::$mysqli ) $value = self::$mysqli->real_escape_string( $value );
			return self::val( $value );
		}, array_values($data));
		$values = implode(", ", $values);
		$SQL = "INSERT INTO `{$tablename}` ($columns) VALUES ($values)";
		return $SQL;
	}
	
	public static function update( string $tablename, array $data, $condition = 1 ) {
		$fieldset = array_map(function($key, $value) {
			if( self::$mysqli ) $value = self::$mysqli->real_escape_string( $value );
			return "`{$key}` = " . self::val( $value );
		}, array_keys($data), array_values($data));
		$fieldset = implode(", ", $fieldset);
		$SQL = "UPDATE `{$tablename}` SET {$fieldset} WHERE {$condition}";
		return $SQL;
	}
	
	public static function delete( string $tablename, string $condition ) {
		$SQL = "DELETE FROM `{$tablename}` WHERE {$condition}";
		return $SQL;
	}
	
}