<?php 

require_once 'cores/init.php';

if(Auth::user()) {
	Auth::logout();
}

Redirect::to('login.php');