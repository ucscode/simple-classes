<?php 

// Created By UCSCODE;

class pairs {
	
	private $tablename;
	private $mysqli;
	
	public function __construct(MYSQLI $mysqli, string $tablename) {
		if( !class_exists('sQuery') ) 
			throw new Exception( "pairs::__construct() relies on class `sQuery` to operate" );
		$this->tablename = $tablename;
		$this->mysqli = $mysqli;
		$this->createTable();
	}
	
	private function createTable() {
		$SQL = "
			CREATE TABLE IF NOT EXISTS `{$this->tablename}` (
				`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				`_ref` INT,
				`_key` varchar(255) NOT NULL UNIQUE,
				`_value` text
			);
		";
		$this->mysqli->query( $SQL );
	}
	
	private function test( ?int $ref = null ) {
		if( is_null($ref) ) $test = " IS " . sQuery::val( $ref );
		else $test = " = " . sQuery::val( $ref );
		return $test;
	}
	
	public function set(string $key, $value, ?int $ref = null) {
		$value = json_encode($value);
		$value = $this->mysqli->real_escape_string($value);
		if( is_null($this->get($key, $ref)) ) {
			$method = "insert";
			$condition = null;
		} else {
			$method = "update";
			$condition = "_key = '{$key}' AND _ref " . $this->test($ref);
		};
		$Query = sQuery::{$method}( $this->tablename, array( 
			"_key" => $key, 
			"_value" => $value,
			"_ref" => $ref
		), $condition );
		$result = $this->mysqli->query( $Query );
		return $result;
	}
	
	public function get(string $key, ?int $ref = null) {
		$Query = sQuery::select( $this->tablename, "_key = '{$key}' AND _ref " . $this->test( $ref ) );
		$result = $this->mysqli->query( $Query )->fetch_assoc();
		if( $result ) {
			$value = json_decode($result['_value']);
			return $value;
		}
	}
	
	public function remove(string $key, ?int $ref = null) {
		$Query = "DELETE FROM `{$this->tablename}` WHERE _key = '{$key}' AND _ref " . $this->test( $ref );
		$result = $this->mysqli->query( $Query );
		return $result;
	}
	
}