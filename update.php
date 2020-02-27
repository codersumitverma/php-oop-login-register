<?php 

require_once 'cores/init.php';

if(!Auth::user()) {
	Redirect::to('login.php');
}

if(Input::exists('post')) {

	if(Token::check(Input::get('_token'))) {
		
		$validation = new Validation();

		$validate = $validation->check($_POST, [
			'id'		=>	[
								'required'	=>	true,
								'exists'	=> 	'users'
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
				$user->update(Input::get('id'), [
					'password' => Hash::make(Input::get('password'), $salt),
					'name' => Input::get('name'),
					'joined' => date("yy-m-d H:i:s"),
					'salt' => $salt,
					'group' => 1
				]);
				Session::flash('success',  'Profile updated successfully!');
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
	<title>Update</title>
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
	<input type="hidden" name="id" value="<?php echo Auth::user()->id ?>">
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
<a href="index.php">Back to Index</a>
</body>
</html>