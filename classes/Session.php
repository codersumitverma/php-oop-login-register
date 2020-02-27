<?php 

class Session
{
	public static function put($name, $value)
	{
		return $_SESSION[$name] = $value;
	}

	public static function exists($name)
	{
		return (isset($_SESSION[$name])) ? true : false;
	}


	public static function get($name)
	{
		return (isset($_SESSION[$name])) ? $_SESSION[$name] : false;
	}

	public static function delete($name)
	{
		if(self::exists($name)) {
			unset($_SESSION[$name]);
		}
	}

	public static function flash($name, $string = '')
	{
		if(self::exists($name)) {
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else {
			self::put($name, $string);
		}
		return '';
	}
}