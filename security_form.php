<?php
require ("functions.php");


$email = $_POST['email'];
$password = $_POST['password'];
$user_id = $_SESSION['user_data']['user_id'];
$username = $_SESSION['user_data']['name'];

$user = get_user_by_email ($email);

if (empty($email) || empty($password)) {
	set_flash_message ('danger', "Строки не должны быть пустыми.");
	redirect_to ("security.php?user_id=".$user_id);
	} 
	
if (($email == ($_SESSION['user_data']['email'])) || ($user == false)) {
	edit_credentials ($user_id, $email, $password);
	set_flash_message ('success', "Профиль успешно обновлен");
	redirect_to ("page_profile.php?user_id=".$user_id);
	} 
	
	elseif (!empty($user)) {
	set_flash_message ('danger', "Данный email {$email} уже занят.");
	redirect_to ("security.php?user_id=".$user_id);
	}
