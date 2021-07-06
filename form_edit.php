<?php
require ("functions.php");


$username = $_POST['username'];
$job = $_POST['job'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$user_id = $_SESSION['user_data']['user_id'];

edit_info ($user_id, $username, $job, $phone, $address);
set_flash_message ('success', "Профиль успешно обновлен");
redirect_to ("users.php");