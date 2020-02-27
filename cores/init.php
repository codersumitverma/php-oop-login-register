<?php

ini_set('display_errors', 1);

session_start();

$GLOBALS['config'] = [
	'mysql'	=> [
		'host'	=> '127.0.0.1',
		'username' => 'root',
		'password' => 'password',
		'db' => 'oop'
	],
	'remember' => [
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	],
	'session' => [
		'session_name' => 'user',
		'token_name' => 'token'
	]
];

spl_autoload_register(function($class) {
	require_once "classes/{$class}.php";
});

require_once 'functions/sanitize.php';

if(Cookie::get(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$check = DB::getInstance()->get('user_sessions', ['hash', '=', $hash]);

	if($check->count()) {
		Session::put(Config::get('session/session_name'), $check->first()->user_id);
	}
}


