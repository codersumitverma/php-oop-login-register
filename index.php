<?php 

require_once 'cores/init.php';

if(!Auth::user()) {
	Redirect::to('login.php');
}

if(Session::exists('success'))
{
	echo Session::flash('success');
}

// // $users =  DB::getInstance()->query('SELECT * FROM users WHERE username = ?', ['uname']);
// $users =  DB::getInstance()->get('users', ['username', '=', 'uname']);

// if(!$users->error()) {
// 	// var_dump($users->first());
// 	if($users->count()) {
// 		foreach ($users->results() as $row) {
// 			echo "Username => {$row->username} <br>";
// 			echo "Name => {$row->name} <br>";
// 			echo "Joined => {$row->joined} <br>";
// 		}
// 	} else {
// 		echo "No record found";
// 	}
// }

// $res = DB::getInstance()->insert('users', [
// 	'username' => 'hello',
// 	'password' => 'pass',
// 	'name' => 'name',
// 	'salt' => 'salt',
// 	'joined' => date("yy-m-d H:i:s"),
// 	'group' => 1,
// ]);

// $res = DB::getInstance()->update('users', 36, [
// 	'username' => 'newhello',
// 	'password' => 'pass',
// 	'name' => 'newname',
// 	'salt' => 'salt',
// 	'joined' => date("yy-m-d H:i:s"),
// 	'group' => 1,
// ]);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Index</title>
	<style type="text/css">
		#account {
			position: relative;
			float: right;
			margin-top: 30px;
			margin-right: -60px;
		}
	</style>
</head>
<body>
	<a href="javascript:;" id="user" style="float: right">Hello <?php echo Auth::user()->name; ?></a>
	<ul id="account">
		<li><a href="update.php">Update</a></li>
		<li><a href="logout.php">Logout</a></li>
	</ul>
</body>
</html>