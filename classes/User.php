<?php 

class user
{
	private $_db;

	public function __construct($user = null)
	{
		$this->_db = DB::getInstance();
	}

	public function create($fields = [])
	{
		if(!$this->_db->insert('users', $fields)) {
			throw new Exceptation('There was a problem.');
		}
		return true;
	}

	public function update($id, $fields = [])
	{
		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exceptation('There was a problem.');
		}
		return true;
	}

	public function login($username = null, $password = null, $remember = false)
	{
		$user = User::find($username);

		if($user) {
			if($user->password === Hash::make($password, $user->salt)) {
				Session::put(Config::get('session/session_name'), $user->id);

				if($remember) {
					$check = $this->_db->get('user_sessions', ['user_id', '=', $user->id]);

					if($check->count()) {
						$hash = $check->first()->hash;
					} else {
						$hash = Hash::unique();
						$this->_db->insert('user_sessions', [
							'user_id'	=>	$user->id,
							'hash'		=>	$hash
						]);
					}
					Cookie::put(Config::get('remember/cookie_name'), $hash, Config::get('remember/cookie_expiry'));
				}

				return true;
			}
		}
		return false;
	}

	public static function find($value = null)
	{
		if($value) {
			$field = (is_numeric($value)) ? 'id' : 'username';
			$res = DB::getInstance()->get('users', [$field, '=', $value]);

			if(!$res->error() && $res->count()) {
				return $res->first();
			}
		}

		return false;
	}
}