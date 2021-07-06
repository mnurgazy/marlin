<?php
require ("functions.php");

$email = $_POST['email'];
$password = $_POST['password'];

$verify = login ($email, $password);

if ($verify == false) {
	set_flash_message ('danger', 'Email или пароль не правильные!');
	redirect_to ("page_login.php");
	exit;
}

set_flash_message ("success", "Добро пожаловать, {$email}!");
$_SESSION['log-in'] = $email;
redirect_to ("users.php");