<?php 

require_once 'cores/init.php';

if(Auth::user()) {
	Redirect::to('index.php');
}

if(Input::exists('post')) {

	if(Token::check(Input::get('_token'))) {
		// echo Input::get('username');
		$validation = new Validation();

		$validate = $validation->check($_POST, [
			'username'	=>	[
								'required'	=>	true,
								'min'		=>	3,
								'max'		=>	20,
								'unique'	=> 	'users'
							],
			'password'	=>	[
								'required'	=>	true,
								'min'		=>	6,
								'max'		=>	20,
								'matches'	=>	're_password'
							],
			'name'		=>	[
								'required'	=>	true,
								'min'		=>	2,
								'max'		=>	25
							]
		]);

		if($validation->passed()) {
			$user = new User();
			try {
				$salt = Hash::salt();
				$user->create([
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'name' => Input::get('name'),
					'joined' => date("yy-m-d H:i:s"),
					'salt' => $salt,
					'group' => 1
				]);
				Session::flash('success',  'You registered successfully!');
				Redirect::to('index.php');
			}
			catch(Exception $e) {
				die($e->getMessage());
			}
		} else {
			$errors = $validation->errors();
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<style type="text/css">
		.field {
			padding: 10px;
		}
		.field label {
			padding-right: 20px;
		}
	</style>
</head>
<body>
<form method="post" action="">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo Input::get('username') ?>" autocomplete="off">
		<?php echo (isset($errors['username'])) ? $errors['username'] : ''; ?>
	</div>
	<div class="field">
		<label for="password">Password</label>
		<input type="password" name="password" id="password" value="<?php echo Input::get('password') ?>" autocomplete="off">
		<?php echo (isset($errors['password'])) ? $errors['password'] : ''; ?>
	</div>
	<div class="field">
		<label for="re_password">Re-Password</label>
		<input type="text" name="re_password" id="re_password" value="<?php echo Input::get('re_password') ?>" autocomplete="off">
		<?php echo (isset($errors['re_password'])) ? $errors['re_password'] : ''; ?>
	</div>
	<div class="field">
		<label for="name">Name</label>
		<input type="text" name="name" id="name" value="<?php echo Input::get('name') ?>" autocomplete="off">
		<?php echo (isset($errors['name'])) ? $errors['name'] : ''; ?>
	</div>
	<input type="hidden" name="_token" value="<?php echo Token::generate() ?>">
	<input type="submit" name="submit">
</form><br>
<a href="login.php">Already have an account</a>
</body>
</html>