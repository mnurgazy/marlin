<?php

require ("functions.php");

$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$job = $_POST['job'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$status = $_POST['status'];
$avatar = $_FILES['avatar'];
$vk = $_POST['vk'];
$telegram = $_POST['telegram'];
$instagram = $_POST['instagram'];

$user = get_user_by_email ($email);


if (!empty($user)) {
	set_flash_message ('danger', "{$email}, уже занят!");
	redirect_to ("create_user.php");
	exit;	
} else {
	if (isset($password) && !empty($password)) {
		if (empty($username) || empty($job) || empty($phone) || empty($address)) {
			set_flash_message ('danger', "Заполните все строки общей информации!");
			redirect_to ("create_user.php");
		} 
			else {
			$user_id = add_user ($email, $password, $role);
			edit_info ($user_id, $username, $job, $phone, $address);
			set_status ($status, $user_id);			
			upload_avatar ($avatar, $user_id);			
			add_social_links ($vk, $telegram, $instagram, $user_id);
			set_flash_message ('success', "Пользователь {$username} успешно добавлен.");
			redirect_to ("users.php");
		}
	}
	else {
		set_flash_message ('danger', "Введите пароль.");
		redirect_to ("create_user.php");
		exit;
	}
}