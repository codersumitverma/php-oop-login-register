<?php 

class Auth
{
	public static function user()
	{
		$id = Session::get(Config::get('session/session_name'));

		return $id ? User::find($id) : false;
	}

	public static function logout()
	{
		Session::delete(Config::get('session/session_name'));
		Cookie::delete(Config::get('remember/cookie_name'));

		return true;
	}
}