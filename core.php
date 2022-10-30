<?php 

class core {

	# [ CONVERT BACKSLASH TO FORWARD SLASH ]
	
	public static function rslash( $PATH ) {
		return str_replace("\\", "/", $PATH);
	}
	
	# [ CONVERT SERVER PATH TO URL ] 
	
	public static function url( $DOC_ROOT = ROOT_DIR, $MINI = FALSE ) {
		$DOC_ROOT = self::rslash( $DOC_ROOT );
		$SRV_URL = preg_replace( "~^{$_SERVER['DOCUMENT_ROOT']}~i", $_SERVER['SERVER_NAME'], $DOC_ROOT );
		$SCHEME = ($_SERVER['REQUEST_SCHEME'] ?? ($_SERVER['SERVER_PORT'] == '80' ? 'http' : 'https'));
		return (!$MINI ? ($SCHEME . "://") : '/') . $SRV_URL;
	}
	
	# [ GET REQUEST_URI ]
	
	public static function request_uri() {
		$FULL_REQUEST_URI = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; 
		$ROOT_URI = substr(core::url( ROOT_DIR, true ),1);
		$REQUEST_URI = str_replace($ROOT_URI, NULL, $FULL_REQUEST_URI);
		return $REQUEST_URI;
	}
	
	# [ ARRAY TO ATTR ] 
	
	public static function array_to_html_attrs( array $array ) {
		return implode(" ", array_map(function($key, $value) {
			if( is_array($value) ) $value = implode(",", $value);
			return "{$key}=\"{$value}\"";
		}, array_keys($array), array_values($array)));
	}

}