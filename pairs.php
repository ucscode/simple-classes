<?php 

// Created By UCSCODE;

class pairs {
	
	private $tablename;
	private $mysqli;
	
	public function __construct(MYSQLI $mysqli, string $tablename) {
		if( !class_exists('sQuery') ) 
			throw new Exception( "pairs::__construct() relies on static class `sQuery` to operate" );
		$this->tablename = $tablename;
		$this->mysqli = $mysqli;
	}
	
	public function set(string $key, $value) {
		$value = json_encode($value);
		$value = $this->mysqli->real_escape_string($value);
		if( is_null($this->get($key)) ) {
			$method = "insert";
			$condition = null;
		} else {
			$method = "update";
			$condition = "_key = '{$key}'";
		};
		$Query = sQuery::{$method}( $this->tablename, array( "_key" => $key, "_value" => $value ), $condition );
		$result = $this->mysqli->query( $Query );
		return $result;
	}
	
	public function get(string $key) {
		$Query = sQuery::select( $this->tablename, "_key = '{$key}'" );
		$result = $this->mysqli->query( $Query )->fetch_assoc();
		if( $result ) {
			$value = json_decode($result['_value']);
			return $value;
		}
	}
	
	public function remove(string $key) {
		$Query = "DELETE FROM `{$this->tablename}` WHERE _key = '{$key}'";
		$result = $this->mysqli->query( $Query );
		return $result;
	}
	
}