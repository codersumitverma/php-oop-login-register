<?php 

class Cookie
{
	public static function get($name)
	{
		return (isset($_COOKIE[$name])) ? $_COOKIE[$name] : false;
	}

	public static function put($name, $value, $expiry = 604800)
	{
		if(setcookie($name, $value, time() + $expiry, '/')) {
			return true;
		}
		return false;
	}

	public static function delete($name)
	{
		self::put($name, '', -1);
		return true;
	}
}