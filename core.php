<?php 

class core {

	/*
		--- [ CONVERT BACKSLASH TO FORWARD SLASH ]
	*/
	
	public static function rslash( $PATH ) {
		return str_replace("\\", "/", $PATH);
	}
	
	
	/* 
		--- [ CONVERT SERVER PATH TO URL ] 
	*/
	
	public static function url( $DOC_ROOT = ROOT_DIR, $MINI = FALSE ) {
		$DOC_ROOT = self::rslash( $DOC_ROOT );
		$SRV_URL = preg_replace( "~^{$_SERVER['DOCUMENT_ROOT']}~i", $_SERVER['SERVER_NAME'], $DOC_ROOT );
		$SCHEME = ($_SERVER['REQUEST_SCHEME'] ?? ($_SERVER['SERVER_PORT'] == '80' ? 'http' : 'https'));
		return (!$MINI ? ($SCHEME . "://") : '/') . $SRV_URL;
	}
	
	
	/* 
		--- [ GET REQUEST_URI ]
	*/
	
	public static function request_uri() {
		$FULL_REQUEST_URI = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; 
		$ROOT_URI = substr(core::url( ROOT_DIR, true ),1);
		$REQUEST_URI = str_replace($ROOT_URI, NULL, $FULL_REQUEST_URI);
		return $REQUEST_URI;
	}
	
	
	/* 
		--- [ ARRAY TO ATTR ] 
	*/
	
	public static function array_to_html_attrs( array $array ) {
		return implode(" ", array_map(function($key, $value) {
			if( is_array($value) ) $value = implode(",", $value);
			return "{$key}=\"{$value}\"";
		}, array_keys($array), array_values($array)));
	}
	
	
	/* 
		--- [ GENERATE KEY ]
	*/
	
	public static function keygen($length = 10, bool $use_spec_char = false) {
		$data = array( ...range(0, 9), ...range('a', 'z'), ...range('A', 'Z') );
		if( $use_spec_char ) {
			$special = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '[', ']', '{', '}', '/', ':', '.', ';'];
			foreach( $special as $char ) $data[] = $char;
		}
		$key = '';
		for( $x = 0; $x < $length; $x++ ) {
			shuffle($data);
			$key .= $data[0];
		};
		return $key;
	}
	
	
	/* 
		--- [ REPLACE %{var} WITH ARRAY VALUE ] 
	*/
	
	public static function replace_var( string $string, array $data ) {
		$new_string = preg_replace_callback( "~%\{([^\}]+)\}~", function( $match ) use($data) {
			$key = $match[1];
			return $data[ $key ] ?? $match[0];
		}, $string );
		return $new_string;
	}
	
	
	/* 
		--- [ GET STRONG REGULAR EXPRESSION ]
	*/
	
	public static function regex( string $name, $strict = false ) {
		if( $strict )  {
			$BEGIN = '^';
			$END = '$';
		} else $BEGIN = $END = NULL;
		## ----- Create REGEX ------
		switch( strtoupper($name) ) {
			case 'EMAIL':	
				return '/' . $BEGIN . '(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))' . $END . '/';
			case "URL":
				return "/{$BEGIN}(?:https?:\/\/)?(?:[\w.-]+(?:(?:\.[\w\.-]+)+)|(?:localhost(:\d{1,4})?\/))[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.%]+{$END}/i";
			case "NUMBER":
				return "/{$BEGIN}\-?\d+(?:\.\d+)?{$END}/";
			case "DATE":
				return "/{$BEGIN}(0[1-9]|[1-2][0-9]|3[0-1])(?:\-|\/)(0[1-9]|1[0-2])(?:\-|\/)[0-9]{4}{$END}/i";
			case "BTC":
				$regex = "/{$BEGIN}[13][a-km-zA-HJ-NP-Z0-9]{26,33}{$END}/i";
				break;
		}
	}

	
	/*
		--- [ CHECK IF PHP NAMESPACE EXISTS ]
	*/
	
	public function namespace_exists($namespace) {
		// credit to stackoverflow
		$namespace .= '\\';
		foreach( get_declared_classes() as $classname )
			if( strpos($classname, $namespace) === 0 ) return true;
		return false;
	}
	
	
	/*
		--- [ SANITIZE INPUT OR ARRAY ]
	*/
	
	public function sanitize( $content, $func ) {
		if( is_array($content) || is_object($content) ) {
			foreach( $content as $key => $value )
				$content[ $key ] = self::sanitize( $value, $func );
		} else if( is_callable($func) ) $content = call_user_func($func, $content);
		return $content;
	}
	
}