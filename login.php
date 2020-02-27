<?php 
require_once 'cores/init.php';

if(Auth::user()) {
	Redirect::to('index.php');
}

if(Input::exists('post')) {
	if(Token::check(Input::get('_token'))) {

		$validation = new Validation();

		$validate = $validation->check($_POST, [
			'username'	=>	[
				'required'	=>	true,
				'max'		=>	'25'
			],
			'password'	=>	[
				'required'	=>	true,
				'max'		=>	'50'
			]
		]);

		if($validation->passed()) {
			$remember = (Input::get('remember') == 'on') ? true : false;
			$user = new User();
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);

			if($login) {
				Session::flash('success',  'You logined in successfully!');
				Redirect::to('index.php');
			} else {
				echo "Invalid username or password";
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
	<title>Login</title>
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
		<label for="remember">
			<input type="checkbox" name="remember" id="remember">
			Remember Me
		</label>
		<?php echo (isset($errors['password'])) ? $errors['password'] : ''; ?>
	</div>
	<input type="hidden" name="_token" value="<?php echo Token::generate() ?>">
	<input type="submit" name="submit">
</form><br>
<a href="register.php">Create an account</a>
</body>
</html>