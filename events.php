<?php

/**
	* Name: Events
	
	* Version: 1.1.0
	
	* Author: UCSCODE
	
	* Author Name: Uchenna Ajah
	
	* Author URI: https://ucscode.com
	
	* Github URI: https://github.com/ucscode
	
	* Title: A touch of javascript events in PHP environment
	
	* Description: This amazing class allows you to declear events functions and execute it at a certain position without overriding or intruding a previously declared function.
	
	* Requires PHP: 5.6
	
**/

class events {

	protected static $events = array();
	
	public static function exec( string $eventType, array $eventdata = array() ) {
		if( !array_key_exists($eventType, self::$events) ) return;
		foreach( self::$events[ $eventType ] as $action ) {
			$action( $eventdata, $eventType );
		};
	}
	
	# --- [ add event listener ] ---
	
	public static function addListener(string $eventTypes, callable $function, ?string $uid = null) {
		# UID = Unique ID
		self::splitEvents($eventTypes, function($event) use($function, $uid) {
			if( !array_key_exists($event, self::$events) ) self::$events[ $event ] = array();
			$eventList = &self::$events[ $event ];
			if( is_null($uid) ) $eventList[] = $function;
			else if( !array_key_exists($uid, $eventList) ) $eventList[ $uid ] = $function; 
		});
	}
	
	# --- [ remove event listener by id ] ---
	
	public static function removeListener(string $eventTypes, string $uid) {
		self::splitEvents($eventTypes, function($event) use($uid) {
			if( !array_key_exists($event, self::$events) ) return;
			$eventList = &self::$events[ $event ];
			if( array_key_exists($uid, $eventList) ) unset($eventList[ $uid ]);
		});
	}
	
	# --- [ clear all relative event listeners ] ---
	
	public static function clear(string $eventTypes) {
		self::splitEvents($eventTypes, function($event) {
			if( array_key_exists($event, self::$events) ) unset(self::$events[ $event ]);
		});
	}
	
	# --- [ clear all relative event listeners ] ---
	
	private static function splitEvents(string $eventTypes, callable $func) {
		$eventTypes = array_map("trim", explode(",", $eventTypes));
		foreach( $eventTypes as $eventType ) $func( $eventType );
	}
	
	public static function viewlist( $priority = false ) {
		return !$priority ? array_keys(self::$events) : self::$events;
	}

};

